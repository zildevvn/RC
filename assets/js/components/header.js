(function ($) {
    "use strict";

    const mobileHeaderMenu = () => {
        const $drawer = $('#mobile-menu-drawer');
        const $trigger = $('#btn_mobile_menu');

        if (!$drawer.length || !$trigger.length || $drawer.data('rcMobileMenuInit')) {
            return;
        }

        $drawer.data('rcMobileMenuInit', true);

        const $closeBtn = $drawer.find('.mobile-menu__close');
        const $overlay = $drawer.find('.mobile-menu__overlay');
        const $menuRoot = $drawer.find('.mobile-primary-menu');
        const XL_BREAKPOINT = 1200;
        let focusTimer = null;

        // Inject dedicated submenu toggle buttons for items with children
        $menuRoot.find('.menu-item-has-children').each(function (index) {
            const $item = $(this);
            const $link = $item.children('a').first();
            const $subMenu = $item.children('.sub-menu').first();

            if (!$link.length || !$subMenu.length || $item.children('.mobile-menu__submenu-toggle').length) {
                return;
            }

            const subMenuId = $subMenu.attr('id') || `mobile-submenu-${index + 1}`;
            $subMenu.attr('id', subMenuId);

            const linkLabel = $.trim($link.text()) || 'submenu';
            const isInitiallyOpen = $item.hasClass('current-menu-ancestor') || $item.hasClass('current-menu-parent');

            const $toggleBtn = $('<button/>', {
                type: 'button',
                class: 'mobile-menu__submenu-toggle',
                'aria-expanded': isInitiallyOpen ? 'true' : 'false',
                'aria-controls': subMenuId,
                'aria-label': `Toggle ${linkLabel} submenu`
            }).append('<span class="mobile-menu__chevron" aria-hidden="true"></span>');

            $link.after($toggleBtn);

            if (isInitiallyOpen) {
                $item.addClass('is-open');
                $subMenu.show();
            } else {
                $subMenu.hide();
            }
        });

        const toggleSubmenu = ($toggleBtn) => {
            const $item = $toggleBtn.closest('.menu-item-has-children');
            const $subMenu = $item.children('.sub-menu').first();

            if (!$subMenu.length) {
                return;
            }

            const isOpen = $item.hasClass('is-open');

            if (isOpen) {
                $item.removeClass('is-open');
                $toggleBtn.attr('aria-expanded', 'false');
                $subMenu.stop(true, true).slideUp(220);
            } else {
                $item.addClass('is-open');
                $toggleBtn.attr('aria-expanded', 'true');
                $subMenu.stop(true, true).slideDown(220);
            }
        };

        const openMenu = () => {
            if ($drawer.hasClass('is-active')) {
                return;
            }

            $drawer.addClass('is-active').attr('aria-hidden', 'false');
            $trigger.attr('aria-expanded', 'true');
            $('body').addClass('mobile-menu-open');

            clearTimeout(focusTimer);
            if ($closeBtn.length) {
                $closeBtn.trigger('focus');
                focusTimer = setTimeout(() => {
                    if ($drawer.hasClass('is-active')) {
                        $closeBtn.trigger('focus');
                    }
                }, 50);
            }
        };

        const closeMenu = (returnFocus = true) => {
            if (!$drawer.hasClass('is-active')) {
                $('body').removeClass('mobile-menu-open');
                return;
            }

            clearTimeout(focusTimer);
            $drawer.removeClass('is-active').attr('aria-hidden', 'true');
            $trigger.attr('aria-expanded', 'false');
            $('body').removeClass('mobile-menu-open');

            if (returnFocus && $trigger.is(':visible')) {
                $trigger.trigger('focus');
            }
        };

        $trigger.on('click.rcMobileMenu', function (e) {
            e.preventDefault();
            if ($drawer.hasClass('is-active')) {
                closeMenu(true);
            } else {
                openMenu();
            }
        });

        $closeBtn.on('click.rcMobileMenu', function (e) {
            e.preventDefault();
            closeMenu(true);
        });

        $overlay.on('click.rcMobileMenu', function () {
            closeMenu(true);
        });

        // Independent submenu toggle button
        $menuRoot.on('click.rcMobileMenu', '.mobile-menu__submenu-toggle', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSubmenu($(this));
        });

        // Handle clicks on menu links: preserve parent link navigation if URL is real, or toggle if '#'
        $menuRoot.on('click.rcMobileMenu', 'a', function (e) {
            const $link = $(this);
            const href = ($link.attr('href') || '').trim();
            const $parentItem = $link.parent('.menu-item-has-children');

            if ($parentItem.length && (!href || href === '#')) {
                e.preventDefault();
                const $toggleBtn = $parentItem.children('.mobile-menu__submenu-toggle').first();
                if ($toggleBtn.length) {
                    toggleSubmenu($toggleBtn);
                }
                return;
            }

            if (href && href !== '#') {
                closeMenu(false);
            }
        });

        // Close mobile menu if search button is clicked
        $('#btn_search').on('click.rcMobileMenu', function () {
            if ($drawer.hasClass('is-active')) {
                closeMenu(false);
            }
        });

        // Keyboard accessibility: Escape & Focus Trap
        $(document).on('keydown.rcMobileMenu', function (e) {
            if (!$drawer.hasClass('is-active')) {
                return;
            }

            if (e.key === 'Escape') {
                e.preventDefault();
                closeMenu(true);
                return;
            }

            if (e.key === 'Tab') {
                const $focusable = $drawer
                    .find('.mobile-menu__drawer')
                    .find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')
                    .filter(':visible:not(:disabled)');

                if (!$focusable.length) {
                    return;
                }

                const firstEl = $focusable[0];
                const lastEl = $focusable[$focusable.length - 1];

                if (e.shiftKey && document.activeElement === firstEl) {
                    e.preventDefault();
                    lastEl.focus();
                } else if (!e.shiftKey && document.activeElement === lastEl) {
                    e.preventDefault();
                    firstEl.focus();
                }
            }
        });

        // Reset state when resizing from mobile/tablet to desktop (XL >= 1200px)
        $(window).on('resize.rcMobileMenu', function () {
            if (window.innerWidth >= XL_BREAKPOINT && ($drawer.hasClass('is-active') || $('body').hasClass('mobile-menu-open'))) {
                closeMenu(false);
            }
        });
    };

    $(document).ready(function () {
        mobileHeaderMenu();
    });
})(jQuery);

