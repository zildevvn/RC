(function ($) {
    "use strict";

    const rcInitQuantitySelectors = () => {
        $(document).on('click', '.quantity-selector .btn-qty, .quantity-selector .qty-btn', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const $input = $btn.siblings('input[type="number"]');
            if (!$input.length) return;

            let val = parseInt($input.val()) || 0;
            const min = parseInt($input.attr('min')) || 0;
            const max = parseInt($input.attr('max')) || 50;

            if ($btn.hasClass('btn-plus') || $btn.text().trim() === '+') {
                if (val < max) val++;
            } else if ($btn.hasClass('btn-minus') || $btn.text().trim() === '-') {
                if (val > min) val--;
            }

            $input.val(val).trigger('change');
        });
    };

    const rcInitAjaxTourOptions = () => {
        const $form = $('.ui-form-booking, .vm-form-booking');
        const $btn = $form.find('#rc-btn-check-availability, .ui-btn-check-availability, #vm-btn-check-availability, .vm-btn-check-availability');
        const $container = $('#rc-tour-options-container, #vm-tour-options-container, .ui-tour-options-container');

        if (!$form.length || !$btn.length || !$container.length) return;

        $container.hide();

        $btn.on('click', function (e) {
            e.preventDefault();

            const date = $form.find('input[type="date"]').val();
            const $error = $form.find('.ui-form-error, .vm-form-error');

            $error.hide().text('');

            if (!date) {
                $error.text('Please select a tour date.').slideDown(200);
                return;
            }

            const $adultsInput = $form.find('input[name="adults"]').length
                ? $form.find('input[name="adults"]')
                : $form.find('.quantity-selector input').eq(0);
            const $childrenInput = $form.find('input[name="children"]').length
                ? $form.find('input[name="children"]')
                : $form.find('.quantity-selector input').eq(1);

            const adults = parseInt($adultsInput.val()) || 0;
            const children = parseInt($childrenInput.val()) || 0;
            const totalGuests = adults + children;

            if ($btn.hasClass('is-loading')) return;

            const originalText = $btn.text();
            $btn.addClass('is-loading').text('CHECKING...');
            $btn.prop('disabled', true);

            const postId = $container.data('post-id') || $('[data-post-id]').data('post-id');

            $.ajax({
                url: php_data.ajax_url,
                type: 'POST',
                data: {
                    action: 'rc_ajax_check_availability',
                    nonce: php_data.tour_options_nonce,
                    post_id: postId,
                    date: date,
                    adults: adults,
                    children: children,
                    total_guests: totalGuests
                },
                success: function (response) {
                    $btn.removeClass('is-loading').text(originalText);
                    $btn.prop('disabled', false);

                    if (response.success) {
                        if (response.data.html) {
                            $container.html(response.data.html);
                            $container.slideDown(400, function () {
                                $('html, body').animate({
                                    scrollTop: $container.offset().top - 120
                                }, 600);
                            });
                        } else {
                            $container.html('<div class="container"><div class="ui-tour-options-empty vm-tour-options-empty" style="padding:40px 20px;text-align:center;background:#fff;border-radius:12px;margin:30px 0;border:1px solid #eaeaea;">Không có option phù hợp. Vui lòng thử lại với số lượng khách khác.</div></div>');
                            $container.slideDown(400);
                        }
                    } else {
                        const msg = (response.data && response.data.message) ? response.data.message : 'An error occurred.';
                        $error.text(msg).slideDown(200);
                    }
                },
                error: function (xhr) {
                    $btn.removeClass('is-loading').text(originalText);
                    $btn.prop('disabled', false);
                    let msg = 'Server error. Please try again later.';
                    if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                        msg = xhr.responseJSON.data.message;
                    }
                    $error.text(msg).slideDown(200);
                }
            });
        });

        $container.on('click', '.option-item', function (e) {
            // Do not trigger selection logic if clicking the active button itself
            if ($(e.target).hasClass('btn-select--active')) return;

            $container.find('.option-item').removeClass('is-selected');
            $container.find('.btn-select').removeClass('btn-select--active').text('Select');

            $(this).addClass('is-selected');
            $(this).find('.btn-select').addClass('btn-select--active').text('Continue');
        });

        $container.on('click', '.btn-select.btn-select--active', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $thisBtn = $(this);
            if ($thisBtn.hasClass('is-loading')) return;

            const originalText = $thisBtn.text();
            $thisBtn.addClass('is-loading').text('Processing...');
            $thisBtn.prop('disabled', true);

            const $tourItem = $thisBtn.closest('.option-item');
            const optionId = $tourItem.data('key');
            const postId = $container.data('post-id');
            const date = $form.find('input[type="date"]').val();

            const $adultsInput = $form.find('input[name="adults"]').length
                ? $form.find('input[name="adults"]')
                : $form.find('.quantity-selector input').eq(0);
            const $childrenInput = $form.find('input[name="children"]').length
                ? $form.find('input[name="children"]')
                : $form.find('.quantity-selector input').eq(1);

            const adults = parseInt($adultsInput.val()) || 0;
            const children = parseInt($childrenInput.val()) || 0;

            $.ajax({
                url: php_data.ajax_url,
                type: 'POST',
                data: {
                    action: 'rc_ajax_process_booking',
                    nonce: php_data.tour_options_nonce,
                    post_id: postId,
                    option_id: optionId,
                    date: date,
                    adults: adults,
                    children: children
                },
                success: function (response) {
                    if (response.success && response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    } else {
                        $thisBtn.removeClass('is-loading').text(originalText).prop('disabled', false);
                        alert(response.data.message || 'An error occurred.');
                    }
                },
                error: function () {
                    $thisBtn.removeClass('is-loading').text(originalText).prop('disabled', false);
                    alert('Server error. Please try again later.');
                }
            });
        });
    };

    /**
     * AJAX Tour Filtering for Cars Template
     * - Desktop (>= 992px): Left sidebar controls
     * - Mobile (< 992px): Filter Options trigger button + Mobile Filter Modal
     * - Synchronized shared filter state between desktop and mobile modal
     * - Preserves results when modal is closed without applying
     * - Reuses homepage tour-item layout & styling
     */
    const rcInitCarsTourFilter = () => {
        const $section = $('#cars-filter-section');
        if (!$section.length) return;

        // Desktop elements
        const $desktopSearch = $section.find('#rc-filter-search');
        const $desktopSearchClear = $section.find('#rc-filter-search-clear');
        const $desktopPriceMin = $section.find('#rc-filter-price-min');
        const $desktopPriceMax = $section.find('#rc-filter-price-max');
        const $desktopPriceDisplayMin = $section.find('#rc-price-display-min');
        const $desktopPriceDisplayMax = $section.find('#rc-price-display-max');
        const $desktopPriceProgress = $section.find('#rc-price-progress');
        const $desktopSort = $section.find('#rc-filter-sort');
        const $desktopClearAll = $section.find('#rc-filter-clear-desktop');

        // Results elements
        const $resultsGrid = $section.find('#rc-filter-results-grid');
        const $countEl = $section.find('#rc-filter-count');
        const $countText = $section.find('#rc-filter-count-text');
        const $loading = $section.find('#rc-filter-loading');
        const $empty = $section.find('#rc-filter-empty');
        const $error = $section.find('#rc-filter-error');
        const $pagination = $section.find('#rc-filter-pagination');
        const $emptyReset = $section.find('#rc-filter-empty-reset');

        // Mobile elements
        const $mobileTrigger = $section.find('#rc-filter-mobile-trigger');
        const $mobileBadge = $section.find('#rc-mobile-active-count');
        const $modal = $('#cars-filter-modal');
        const $modalOverlay = $modal.find('#rc-filter-modal-overlay');
        const $modalClose = $modal.find('#rc-filter-modal-close');
        const $modalSearch = $modal.find('#rc-modal-filter-search');
        const $modalSearchClear = $modal.find('#rc-modal-filter-search-clear');
        const $modalPriceMin = $modal.find('#rc-modal-filter-price-min');
        const $modalPriceMax = $modal.find('#rc-modal-filter-price-max');
        const $modalPriceDisplayMin = $modal.find('#rc-modal-price-display-min');
        const $modalPriceDisplayMax = $modal.find('#rc-modal-price-display-max');
        const $modalPriceProgress = $modal.find('#rc-modal-price-progress');
        const $modalClear = $modal.find('#rc-modal-filter-clear');
        const $modalApply = $modal.find('#rc-modal-filter-apply');

        // Config
        const nonce = $section.data('nonce') || (window.php_data ? window.php_data.filter_tours_nonce : '');
        const ajaxUrl = (window.php_data && window.php_data.ajax_url) ? window.php_data.ajax_url : '/wp-admin/admin-ajax.php';
        const currency = $section.data('currency') || '$';
        const minBound = parseFloat($section.data('min-bound')) || 0;
        const maxBound = parseFloat($section.data('max-bound')) || 500;

        let initialLocations = [];
        try {
            const rawLocs = $section.data('initial-locations');
            if (rawLocs && Array.isArray(rawLocs)) {
                initialLocations = rawLocs.map(id => parseInt(id)).filter(id => !isNaN(id));
            }
        } catch (e) {
            initialLocations = [];
        }

        // Shared Filter State (Source of Truth for Applied Filters)
        let appliedState = {
            search: '',
            sort: 'recommended',
            minPrice: minBound,
            maxPrice: maxBound,
            locations: initialLocations,
            paged: 1
        };

        // Modal Draft State (Working copy while modal is open)
        let modalDraftState = {
            search: '',
            sort: 'recommended',
            minPrice: minBound,
            maxPrice: maxBound,
            locations: []
        };

        let searchTimer = null;
        let priceTimer = null;
        let locationTimer = null;
        let activeXhr = null;
        let currentRequestId = 0;

        const formatNumber = (num) => {
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        };

        /**
         * Update price track visual bar & labels for a given set of elements
         */
        const renderPriceTrack = ($minInput, $maxInput, $progress, $displayMin, $displayMax, valMin, valMax) => {
            if (isNaN(valMin)) valMin = minBound;
            if (isNaN(valMax)) valMax = maxBound;

            if (valMin > valMax) {
                const temp = valMin;
                valMin = valMax;
                valMax = temp;
            }

            const range = maxBound - minBound || 1;
            const leftPercent = Math.max(0, Math.min(100, ((valMin - minBound) / range) * 100));
            const rightPercent = Math.max(0, Math.min(100, 100 - (((valMax - minBound) / range) * 100)));

            $progress.css({
                left: leftPercent + '%',
                right: rightPercent + '%'
            });

            $displayMin.text(currency + formatNumber(valMin));
            $displayMax.text(currency + formatNumber(valMax));
        };

        /**
         * Calculate and update active filter indicators across desktop and mobile trigger
         */
        const updateActiveIndicators = () => {
            let activeCount = 0;

            if (appliedState.search.trim().length > 0) activeCount++;
            if (appliedState.sort !== 'recommended') activeCount++;
            if (appliedState.minPrice > minBound || appliedState.maxPrice < maxBound) activeCount++;
            if (appliedState.locations && appliedState.locations.length > 0) {
                activeCount += appliedState.locations.length;
            }

            // Desktop Location badge
            const desktopLocCount = appliedState.locations ? appliedState.locations.length : 0;
            const $desktopLocBadge = $section.find('#rc-locations-badge-desktop');
            if (desktopLocCount > 0) {
                $desktopLocBadge.text(desktopLocCount).show();
            } else {
                $desktopLocBadge.hide();
            }

            // Desktop Clear All button
            if (activeCount > 0) {
                $desktopClearAll.fadeIn(150);
            } else {
                $desktopClearAll.fadeOut(150);
            }

            // Mobile Badge
            if (activeCount > 0) {
                $mobileBadge.text(activeCount).show();
            } else {
                $mobileBadge.hide();
            }
        };

        /**
         * Synchronize desktop input controls from appliedState
         */
        const syncDesktopUIFromState = () => {
            $desktopSearch.val(appliedState.search);
            if (appliedState.search.length > 0) {
                $desktopSearchClear.show();
            } else {
                $desktopSearchClear.hide();
            }

            $desktopSort.val(appliedState.sort);
            $desktopPriceMin.val(appliedState.minPrice);
            $desktopPriceMax.val(appliedState.maxPrice);

            // Sync desktop checkboxes
            $section.find('.ui-filter-location-checkbox').each(function () {
                const termId = parseInt($(this).val());
                $(this).prop('checked', appliedState.locations.includes(termId));
            });

            renderPriceTrack(
                $desktopPriceMin,
                $desktopPriceMax,
                $desktopPriceProgress,
                $desktopPriceDisplayMin,
                $desktopPriceDisplayMax,
                appliedState.minPrice,
                appliedState.maxPrice
            );

            updateActiveIndicators();
        };

        /**
         * Synchronize modal input controls from modalDraftState
         */
        const syncModalUIFromDraft = () => {
            $modalSearch.val(modalDraftState.search);
            if (modalDraftState.search.length > 0) {
                $modalSearchClear.show();
            } else {
                $modalSearchClear.hide();
            }

            $modalPriceMin.val(modalDraftState.minPrice);
            $modalPriceMax.val(modalDraftState.maxPrice);

            // Sync modal checkboxes
            $modal.find('.ui-modal-filter-location-checkbox').each(function () {
                const termId = parseInt($(this).val());
                $(this).prop('checked', modalDraftState.locations.includes(termId));
            });

            // Modal Location badge
            const modalLocCount = modalDraftState.locations ? modalDraftState.locations.length : 0;
            const $modalLocBadge = $modal.find('#rc-modal-locations-badge');
            if (modalLocCount > 0) {
                $modalLocBadge.text(modalLocCount).show();
            } else {
                $modalLocBadge.hide();
            }

            renderPriceTrack(
                $modalPriceMin,
                $modalPriceMax,
                $modalPriceProgress,
                $modalPriceDisplayMin,
                $modalPriceDisplayMax,
                modalDraftState.minPrice,
                modalDraftState.maxPrice
            );
        };

        /**
         * Execute AJAX query based on current appliedState
         */
        const triggerFilter = (paged = 1, shouldScroll = false) => {
            appliedState.paged = paged;

            // Abort pending request
            if (activeXhr && activeXhr.readyState !== 4) {
                activeXhr.abort();
            }

            const requestId = ++currentRequestId;

            $loading.fadeIn(150);
            $resultsGrid.addClass('is-loading');
            $error.hide();

            activeXhr = $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rc_ajax_filter_tours',
                    nonce: nonce,
                    search: appliedState.search,
                    sort: appliedState.sort,
                    min_price: appliedState.minPrice,
                    max_price: appliedState.maxPrice,
                    locations: appliedState.locations,
                    paged: appliedState.paged
                },
                success: function (response) {
                    if (requestId !== currentRequestId) return; // Outdated response discarded

                    $loading.fadeOut(150);
                    $resultsGrid.removeClass('is-loading');

                    if (response.success && response.data) {
                        const data = response.data;

                        if (data.html && data.html.trim().length > 0) {
                            $resultsGrid.html(data.html).show();
                            $empty.hide();
                        } else {
                            $resultsGrid.html('').hide();
                            $empty.fadeIn(200);
                        }

                        $pagination.html(data.pagination || '');

                        if (typeof data.found_posts !== 'undefined') {
                            $countEl.text(data.found_posts);
                            $countText.html('<span>' + data.found_posts + '</span> <span>' + (data.count_text || 'tours found') + '</span>');
                        }

                        if (shouldScroll) {
                            $('html, body').animate({
                                scrollTop: $section.offset().top - 90
                            }, 450);
                        }
                    } else {
                        $error.text(response.data?.message || 'Unable to load tours. Please try again.').fadeIn(200);
                    }
                },
                error: function (xhr, status) {
                    if (status === 'abort') return;
                    if (requestId !== currentRequestId) return;

                    $loading.fadeOut(150);
                    $resultsGrid.removeClass('is-loading');
                    $error.fadeIn(200);
                }
            });
        };

        // ----------------------------------------------------------------------
        // Desktop Controls Event Handlers
        // ----------------------------------------------------------------------
        $desktopPriceMin.on('input', function () {
            let minVal = parseFloat($(this).val());
            let maxVal = parseFloat($desktopPriceMax.val());
            if (minVal > maxVal) {
                $(this).val(maxVal);
                minVal = maxVal;
            }
            appliedState.minPrice = minVal;
            appliedState.maxPrice = maxVal;

            renderPriceTrack(
                $desktopPriceMin,
                $desktopPriceMax,
                $desktopPriceProgress,
                $desktopPriceDisplayMin,
                $desktopPriceDisplayMax,
                minVal,
                maxVal
            );
            updateActiveIndicators();

            clearTimeout(priceTimer);
            priceTimer = setTimeout(() => {
                triggerFilter(1);
            }, 300);
        });

        $desktopPriceMax.on('input', function () {
            let maxVal = parseFloat($(this).val());
            let minVal = parseFloat($desktopPriceMin.val());
            if (maxVal < minVal) {
                $(this).val(minVal);
                maxVal = minVal;
            }
            appliedState.minPrice = minVal;
            appliedState.maxPrice = maxVal;

            renderPriceTrack(
                $desktopPriceMin,
                $desktopPriceMax,
                $desktopPriceProgress,
                $desktopPriceDisplayMin,
                $desktopPriceDisplayMax,
                minVal,
                maxVal
            );
            updateActiveIndicators();

            clearTimeout(priceTimer);
            priceTimer = setTimeout(() => {
                triggerFilter(1);
            }, 300);
        });

        $desktopSearch.on('input', function () {
            const query = $(this).val();
            appliedState.search = query;

            if (query.length > 0) {
                $desktopSearchClear.show();
            } else {
                $desktopSearchClear.hide();
            }

            updateActiveIndicators();

            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                triggerFilter(1);
            }, 350);
        });

        $desktopSearchClear.on('click', function (e) {
            e.preventDefault();
            appliedState.search = '';
            $desktopSearch.val('');
            $(this).hide();
            updateActiveIndicators();
            triggerFilter(1);
        });

        $desktopSort.on('change', function () {
            appliedState.sort = $(this).val();
            updateActiveIndicators();
            triggerFilter(1);
        });

        // Desktop Tour Locations checkbox handler
        $section.on('change', '.ui-filter-location-checkbox', function () {
            const termId = parseInt($(this).val());
            if ($(this).is(':checked')) {
                if (!appliedState.locations.includes(termId)) {
                    appliedState.locations.push(termId);
                }
            } else {
                appliedState.locations = appliedState.locations.filter(id => id !== termId);
            }
            updateActiveIndicators();

            clearTimeout(locationTimer);
            locationTimer = setTimeout(() => {
                triggerFilter(1);
            }, 200);
        });

        const resetAllAppliedFilters = () => {
            appliedState = {
                search: '',
                sort: 'recommended',
                minPrice: minBound,
                maxPrice: maxBound,
                locations: [],
                paged: 1
            };
            syncDesktopUIFromState();
            triggerFilter(1);
        };

        $desktopClearAll.on('click', function (e) {
            e.preventDefault();
            resetAllAppliedFilters();
        });

        $emptyReset.on('click', function (e) {
            e.preventDefault();
            resetAllAppliedFilters();
        });

        // ----------------------------------------------------------------------
        // Mobile Modal UX & Events
        // ----------------------------------------------------------------------
        const openMobileModal = () => {
            // Clone applied state into draft state
            modalDraftState = {
                search: appliedState.search,
                sort: appliedState.sort,
                minPrice: appliedState.minPrice,
                maxPrice: appliedState.maxPrice,
                locations: [...appliedState.locations]
            };
            syncModalUIFromDraft();

            $modal.addClass('is-active').attr('aria-hidden', 'false');
            $mobileTrigger.attr('aria-expanded', 'true');
            $('body').addClass('cars-filter-modal-open');

            // Focus management
            $modalClose.trigger('focus');
        };

        const closeMobileModal = () => {
            if (!$modal.hasClass('is-active')) return;

            $modal.removeClass('is-active').attr('aria-hidden', 'true');
            $mobileTrigger.attr('aria-expanded', 'false');
            $('body').removeClass('cars-filter-modal-open');

            // Restore focus
            if ($mobileTrigger.is(':visible')) {
                $mobileTrigger.trigger('focus');
            }
        };

        $mobileTrigger.on('click', function (e) {
            e.preventDefault();
            openMobileModal();
        });

        $modalClose.on('click', function (e) {
            e.preventDefault();
            closeMobileModal(); // Discards draft changes
        });

        $modalOverlay.on('click', function () {
            closeMobileModal(); // Discards draft changes
        });

        $(document).on('keydown.rcFilterModal', function (e) {
            if ($modal.hasClass('is-active') && e.key === 'Escape') {
                e.preventDefault();
                closeMobileModal();
            }
        });

        // Modal inputs only update draft state
        $modalSearch.on('input', function () {
            const query = $(this).val();
            modalDraftState.search = query;
            if (query.length > 0) {
                $modalSearchClear.show();
            } else {
                $modalSearchClear.hide();
            }
        });

        $modalSearchClear.on('click', function (e) {
            e.preventDefault();
            modalDraftState.search = '';
            $modalSearch.val('');
            $(this).hide();
        });

        // Modal Tour Locations checkbox handler (updates draft only)
        $modal.on('change', '.ui-modal-filter-location-checkbox', function () {
            const termId = parseInt($(this).val());
            if ($(this).is(':checked')) {
                if (!modalDraftState.locations.includes(termId)) {
                    modalDraftState.locations.push(termId);
                }
            } else {
                modalDraftState.locations = modalDraftState.locations.filter(id => id !== termId);
            }

            const modalLocCount = modalDraftState.locations.length;
            const $modalLocBadge = $modal.find('#rc-modal-locations-badge');
            if (modalLocCount > 0) {
                $modalLocBadge.text(modalLocCount).show();
            } else {
                $modalLocBadge.hide();
            }
        });

        $modalPriceMin.on('input', function () {
            let minVal = parseFloat($(this).val());
            let maxVal = parseFloat($modalPriceMax.val());
            if (minVal > maxVal) {
                $(this).val(maxVal);
                minVal = maxVal;
            }
            modalDraftState.minPrice = minVal;
            modalDraftState.maxPrice = maxVal;

            renderPriceTrack(
                $modalPriceMin,
                $modalPriceMax,
                $modalPriceProgress,
                $modalPriceDisplayMin,
                $modalPriceDisplayMax,
                minVal,
                maxVal
            );
        });

        $modalPriceMax.on('input', function () {
            let maxVal = parseFloat($(this).val());
            let minVal = parseFloat($modalPriceMin.val());
            if (maxVal < minVal) {
                $(this).val(minVal);
                maxVal = minVal;
            }
            modalDraftState.minPrice = minVal;
            modalDraftState.maxPrice = maxVal;

            renderPriceTrack(
                $modalPriceMin,
                $modalPriceMax,
                $modalPriceProgress,
                $modalPriceDisplayMin,
                $modalPriceDisplayMax,
                minVal,
                maxVal
            );
        });

        // Modal Clear All (Resets draft state inside modal)
        $modalClear.on('click', function (e) {
            e.preventDefault();
            modalDraftState = {
                search: '',
                minPrice: minBound,
                maxPrice: maxBound,
                locations: []
            };
            syncModalUIFromDraft();
        });

        // Modal Apply Filters (Commits draft to applied state & triggers AJAX)
        $modalApply.on('click', function (e) {
            e.preventDefault();
            appliedState.search = modalDraftState.search;
            appliedState.minPrice = modalDraftState.minPrice;
            appliedState.maxPrice = modalDraftState.maxPrice;
            appliedState.locations = [...modalDraftState.locations];

            syncDesktopUIFromState();
            closeMobileModal();
            triggerFilter(1, true);
        });

        // ----------------------------------------------------------------------
        // Pagination Click Handler
        // ----------------------------------------------------------------------
        $section.on('click', '.ui-ajax-pagination a, .ui-ajax-pagination .page-numbers a', function (e) {
            e.preventDefault();
            const paged = parseInt($(this).data('page'));
            if (paged) {
                triggerFilter(paged, true);
            }
        });

        // Initial setup
        syncDesktopUIFromState();
    };

    $(document).ready(function () {
        rcInitQuantitySelectors();
        rcInitAjaxTourOptions();
        rcInitCarsTourFilter();
    });
})(jQuery);