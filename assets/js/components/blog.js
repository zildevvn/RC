/**
 * Blog Filtering & Search Component (blog.js)
 *
 * Implements:
 * - Dynamic Category Filtering (Pills)
 * - Real-time Search with Debouncing
 * - Combined Category + Search Filtering
 * - AJAX Pagination with smooth scroll
 * - Race condition prevention (Active request tracking & aborting)
 * - Loading overlay, Empty state, and Error recovery
 *
 * @package rc
 */

(function ($) {
    'use strict';

    function initBlogFiltering() {
        const $section = $('#blog-main-section');
        if (!$section.length) return;

        const $wrapper = $('#rc-blog-wrapper');
        const $results = $('#rc-blog-results');
        const $loading = $('#rc-blog-loading');
        const $empty = $('#rc-blog-empty');
        const $error = $('#rc-blog-error');
        const $errorMessage = $('#rc-blog-error-message');
        const $retryBtn = $('#rc-blog-retry-btn');
        const $pagination = $('#rc-blog-pagination');
        const $countEl = $('#rc-blog-count');
        const $countText = $('#rc-blog-count-text');
        const $activeTags = $('#rc-blog-active-tags');
        const $clearAllBtn = $('#rc-blog-clear-all');

        // Toolbar inputs
        const $catPills = $section.find('.blog-cat-pill');
        const $searchInput = $('#rc-blog-search-input');
        const $searchClear = $('#rc-blog-search-clear');
        const $emptyReset = $('#rc-blog-empty-reset');

        // Configuration
        const ajaxUrl = (typeof php_data !== 'undefined' && php_data.ajax_url)
            ? php_data.ajax_url
            : '/wp-admin/admin-ajax.php';
        const nonce = (typeof php_data !== 'undefined' && php_data.filter_posts_nonce)
            ? php_data.filter_posts_nonce
            : ($wrapper.data('nonce') || '');

        // Centralized Filter State
        const state = {
            category: 'all',
            categoryName: 'All Articles',
            search: '',
            paged: 1
        };

        let activeXhr = null;
        let currentRequestId = 0;
        let searchDebounceTimer = null;

        /**
         * Render active filter tags and clear all button
         */
        function updateActiveFilterTags() {
            let tagsHtml = '';
            const hasCategory = state.category !== 'all';
            const hasSearch = state.search.trim().length > 0;

            if (hasCategory) {
                tagsHtml += '<span class="blog-tag-chip" data-remove="category">' +
                    '<span class="blog-tag-chip__label">Category: <strong>' + escapeHtml(state.categoryName) + '</strong></span>' +
                    '<button type="button" class="blog-tag-chip__remove" aria-label="Remove category filter">&times;</button>' +
                    '</span>';
            }

            if (hasSearch) {
                tagsHtml += '<span class="blog-tag-chip" data-remove="search">' +
                    '<span class="blog-tag-chip__label">Search: "<strong>' + escapeHtml(state.search) + '</strong>"</span>' +
                    '<button type="button" class="blog-tag-chip__remove" aria-label="Remove search filter">&times;</button>' +
                    '</span>';
            }

            if (hasCategory || hasSearch) {
                $activeTags.html(tagsHtml).show();
                $clearAllBtn.show();
            } else {
                $activeTags.html('').hide();
                $clearAllBtn.hide();
            }

            // Toggle search input clear button
            if (hasSearch) {
                $searchClear.show();
            } else {
                $searchClear.hide();
            }
        }

        /**
         * Escape HTML string helper
         */
        function escapeHtml(str) {
            return $('<div>').text(str || '').html();
        }

        /**
         * Send AJAX request to filter posts
         */
        function fetchPosts(shouldScroll) {
            currentRequestId++;
            const requestId = currentRequestId;

            if (activeXhr) {
                activeXhr.abort();
            }

            // Set loading state
            $loading.fadeIn(150);
            $results.addClass('is-loading');
            $error.hide();
            updateActiveFilterTags();

            activeXhr = $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rc_ajax_filter_posts',
                    nonce: nonce,
                    category: state.category,
                    search: state.search,
                    paged: state.paged
                },
                success: function (response) {
                    if (requestId !== currentRequestId) return; // Discard stale response

                    $loading.fadeOut(150);
                    $results.removeClass('is-loading');

                    if (response.success && response.data) {
                        const data = response.data;

                        if (data.html && data.html.trim().length > 0) {
                            $results.html(data.html).show();
                            $empty.hide();
                        } else {
                            $results.html('').hide();
                            $empty.fadeIn(200);
                        }

                        $pagination.html(data.pagination || '');

                        if (typeof data.found_posts !== 'undefined') {
                            $countEl.text(data.found_posts);
                            const countTextLabel = data.found_posts === 1 ? 'article found' : 'articles found';
                            $countText.html('<strong><span>' + data.found_posts + '</span></strong> <span>' + (data.count_text || countTextLabel) + '</span>');
                        }

                        if (shouldScroll) {
                            const offsetTop = $section.offset().top - 90;
                            $('html, body').animate({
                                scrollTop: Math.max(0, offsetTop)
                            }, 400);
                        }
                    } else {
                        const msg = (response.data && response.data.message)
                            ? response.data.message
                            : 'Failed to load articles. Please try again.';
                        $errorMessage.text(msg);
                        $error.fadeIn(200);
                    }
                },
                error: function (xhr, status) {
                    if (status === 'abort') return;
                    if (requestId !== currentRequestId) return;

                    $loading.fadeOut(150);
                    $results.removeClass('is-loading');
                    $errorMessage.text('An error occurred while fetching articles. Please check your connection and try again.');
                    $error.fadeIn(200);
                }
            });
        }

        // ----------------------------------------------------------------------
        // Category Pill Selection
        // ----------------------------------------------------------------------
        $section.on('click', '.blog-cat-pill', function (e) {
            e.preventDefault();
            const $this = $(this);
            const catVal = $this.data('category') || 'all';
            const catName = $this.data('name') || ($this.find('span:first-child').text() || 'All Articles');

            if (state.category === catVal) return;

            $catPills.removeClass('is-active').attr('aria-selected', 'false');
            $this.addClass('is-active').attr('aria-selected', 'true');

            state.category = catVal;
            state.categoryName = catName;
            state.paged = 1;

            fetchPosts(false);
        });

        // ----------------------------------------------------------------------
        // Search Input Handling (with Debounce & Enter key)
        // ----------------------------------------------------------------------
        $searchInput.on('input', function () {
            const val = $(this).val();
            if (val.trim().length > 0) {
                $searchClear.show();
            } else {
                $searchClear.hide();
            }

            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(function () {
                const query = val.trim();
                if (state.search !== query) {
                    state.search = query;
                    state.paged = 1;
                    fetchPosts(false);
                }
            }, 350);
        });

        $searchInput.on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchDebounceTimer);
                const query = $(this).val().trim();
                if (state.search !== query) {
                    state.search = query;
                    state.paged = 1;
                    fetchPosts(false);
                }
            }
        });

        // Clear Search Button inside Search Box
        $searchClear.on('click', function (e) {
            e.preventDefault();
            $searchInput.val('').focus();
            $searchClear.hide();

            if (state.search !== '') {
                state.search = '';
                state.paged = 1;
                fetchPosts(false);
            }
        });

        // ----------------------------------------------------------------------
        // Active Filter Tag Removal
        // ----------------------------------------------------------------------
        $activeTags.on('click', '.blog-tag-chip', function (e) {
            e.preventDefault();
            const removeType = $(this).data('remove');

            if (removeType === 'category') {
                state.category = 'all';
                state.categoryName = 'All Articles';
                $catPills.removeClass('is-active').attr('aria-selected', 'false');
                $section.find('.blog-cat-pill[data-category="all"]').addClass('is-active').attr('aria-selected', 'true');
                state.paged = 1;
                fetchPosts(false);
            } else if (removeType === 'search') {
                state.search = '';
                $searchInput.val('');
                $searchClear.hide();
                state.paged = 1;
                fetchPosts(false);
            }
        });

        // ----------------------------------------------------------------------
        // Clear All Filters
        // ----------------------------------------------------------------------
        function resetAllFilters() {
            state.category = 'all';
            state.categoryName = 'All Articles';
            state.search = '';
            state.paged = 1;

            $catPills.removeClass('is-active').attr('aria-selected', 'false');
            $section.find('.blog-cat-pill[data-category="all"]').addClass('is-active').attr('aria-selected', 'true');
            $searchInput.val('');
            $searchClear.hide();

            fetchPosts(false);
        }

        $clearAllBtn.on('click', function (e) {
            e.preventDefault();
            resetAllFilters();
        });

        $emptyReset.on('click', function (e) {
            e.preventDefault();
            resetAllFilters();
        });

        $retryBtn.on('click', function (e) {
            e.preventDefault();
            fetchPosts(false);
        });

        // ----------------------------------------------------------------------
        // AJAX Pagination Click Delegation
        // ----------------------------------------------------------------------
        $pagination.on('click', 'a.page-numbers', function (e) {
            e.preventDefault();
            const $this = $(this);
            const targetPage = parseInt($this.data('page'), 10);

            if (!targetPage || targetPage === state.paged || $results.hasClass('is-loading')) {
                return;
            }

            state.paged = targetPage;
            fetchPosts(true);
        });
    }

    $(document).ready(function () {
        initBlogFiltering();
    });

})(jQuery);

