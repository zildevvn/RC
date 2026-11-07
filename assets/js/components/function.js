import Swiper from 'swiper';
import { Pagination, Navigation, Autoplay, EffectFade, Keyboard, Thumbs } from 'swiper/modules';
import { CountUp } from 'countup.js';


(function ($) {
    "use strict";

    const modalSearch = () => {
        const $modal = $('#search-modal');
        const $trigger = $('#btn_search');

        if (!$modal.length || !$trigger.length || $modal.data('rcModalSearchInit')) {
            return;
        }

        $modal.data('rcModalSearchInit', true);

        const $closeBtn = $modal.find('.search-modal__close');
        const $overlay = $modal.find('.search-modal__overlay');
        const $input = $modal.find('#search-modal-input');
        let focusTimer = null;

        const openModal = () => {
            if ($modal.hasClass('is-active')) {
                return;
            }

            $modal.addClass('is-active').attr('aria-hidden', 'false');
            $trigger.attr('aria-expanded', 'true');
            $('body').addClass('search-modal-open');

            if ($input.length) {
                $input.trigger('focus');
                clearTimeout(focusTimer);
                focusTimer = setTimeout(() => {
                    if ($modal.hasClass('is-active')) {
                        $input.trigger('focus');
                    }
                }, 50);
            }
        };

        const closeModal = () => {
            if (!$modal.hasClass('is-active')) {
                return;
            }

            clearTimeout(focusTimer);
            $modal.removeClass('is-active').attr('aria-hidden', 'true');
            $trigger.attr('aria-expanded', 'false');
            $('body').removeClass('search-modal-open');
            $trigger.trigger('focus');
        };

        $trigger.on('click.rcModalSearch', function (e) {
            e.preventDefault();
            openModal();
        });

        $closeBtn.on('click.rcModalSearch', function (e) {
            e.preventDefault();
            closeModal();
        });

        $overlay.on('click.rcModalSearch', function () {
            closeModal();
        });

        $modal.on('click.rcModalSearch', function (e) {
            if ($(e.target).is($modal)) {
                closeModal();
            }
        });

        $(document).on('keydown.rcModalSearch', function (e) {
            if (!$modal.hasClass('is-active')) {
                return;
            }

            if (e.key === 'Escape') {
                e.preventDefault();
                closeModal();
                return;
            }

            if (e.key === 'Tab') {
                const $focusable = $modal
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
    };

    const testimonialsSwiper = () => {
        const sections = document.querySelectorAll('.testimonials-section');
        if (!sections.length) {
            return;
        }

        sections.forEach((section) => {
            const sliderEl = section.querySelector('.testimonials-section__grid.swiper');
            if (!sliderEl || sliderEl.swiper) {
                return;
            }

            const prevEl = section.querySelector('.swiper-prev');
            const nextEl = section.querySelector('.swiper-next');
            const paginationEl = section.querySelector('.swiper-pagination');

            new Swiper(sliderEl, {
                modules: [Navigation, Pagination],
                slidesPerView: 1,
                spaceBetween: 20,
                watchOverflow: true,
                navigation: {
                    prevEl: prevEl,
                    nextEl: nextEl,
                },
                pagination: {
                    el: paginationEl,
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 32,
                    },
                },
            });
        });
    };

    $(document).ready(function () {
        modalSearch();
        testimonialsSwiper();
    });
})(jQuery);
