<?php
/**
 * Template part for displaying the Blog Main Section
 *
 * Includes:
 * - Editorial Section Header (Badge, Heading, Subheading from ACF with authentic fallbacks)
 * - Modern Blog Toolbar: Dynamic category filter pills + Search box with debounce & clear button
 * - Live Results Meta Bar: Result counter, Active filters chips, and Clear all button
 * - Responsive 3-Column Post Grid with Single-Source-of-Truth card renderer (rc_post_item)
 * - Native WordPress AJAX Pagination (rc_get_ajax_pagination)
 * - Accessible Loading Overlay, Empty State, and Error Handling
 *
 * @package rc
 */

$heading = get_field('hd_blog_tpl');
$sub_hd  = get_field('sub_hd_blog_tpl');

// Fallback headers if ACF fields are empty
$badge   = __('Travel Journal & Guides', 'rc');
$heading = !empty($heading) ? $heading : __('Latest Travel Guides & Central Vietnam Insights', 'rc');
$sub_hd  = !empty($sub_hd) ? $sub_hd : __('Explore curated destination guides, road trip itineraries, and practical transportation tips written by local drivers.', 'rc');

// Retrieve published categories
$blog_cats = get_terms([
    'taxonomy'   => 'category',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
]);

// Initial WP Query
$paged = max(1, get_query_var('paged'), get_query_var('page'));
$initial_args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
$query = new WP_Query($initial_args);

$nonce = wp_create_nonce('rc_filter_posts_nonce');
?>

<section class="ui-section blog-main-section" id="blog-main-section" aria-labelledby="blog-main-heading">
    <div class="container">

        <!-- Section Header -->
        <div class="blog-main-header">
            <span class="ui-badge blog-main-header__badge">
                <?= esc_html($badge); ?>
            </span>
            <h2 id="blog-main-heading" class="blog-main-header__title">
                <?= esc_html($heading); ?>
            </h2>
            <?php if (!empty($sub_hd)): ?>
                <p class="blog-main-header__desc">
                    <?= esc_html($sub_hd); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Modern Blog Toolbar (Category Pills & Search) -->
        <div class="blog-toolbar" role="region" aria-label="<?php esc_attr_e('Blog Filters and Search', 'rc'); ?>">
            <!-- Category Filter Pills -->
            <div class="blog-toolbar__cats">
                <div class="blog-cat-pills" role="tablist" aria-label="<?php esc_attr_e('Filter articles by category', 'rc'); ?>">
                    <button type="button" 
                            class="blog-cat-pill is-active" 
                            role="tab" 
                            aria-selected="true" 
                            data-category="all">
                        <span><?php esc_html_e('All Articles', 'rc'); ?></span>
                        <span class="blog-cat-pill__count"><?= (int) wp_count_posts('post')->publish; ?></span>
                    </button>

                    <?php if (!empty($blog_cats) && !is_wp_error($blog_cats)): ?>
                        <?php foreach ($blog_cats as $cat): ?>
                            <button type="button" 
                                    class="blog-cat-pill" 
                                    role="tab" 
                                    aria-selected="false" 
                                    data-category="<?= esc_attr($cat->term_id); ?>"
                                    data-slug="<?= esc_attr($cat->slug); ?>"
                                    data-name="<?= esc_attr($cat->name); ?>">
                                <span><?= esc_html($cat->name); ?></span>
                                <span class="blog-cat-pill__count"><?= (int) $cat->count; ?></span>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Search Input Box -->
            <div class="blog-toolbar__search">
                <div class="blog-search-box">
                    <span class="blog-search-box__icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="search"
                           id="rc-blog-search-input"
                           class="blog-search-box__input"
                           placeholder="<?php esc_attr_e('Search guides, routes, tips...', 'rc'); ?>"
                           aria-label="<?php esc_attr_e('Search articles', 'rc'); ?>"
                           autocomplete="off">
                    <button type="button" 
                            id="rc-blog-search-clear" 
                            class="blog-search-box__clear" 
                            style="display: none;" 
                            aria-label="<?php esc_attr_e('Clear search', 'rc'); ?>">
                        &times;
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Status Bar (Results count & Active filter tags) -->
        <div class="blog-status-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="blog-status-bar__count">
                <span id="rc-blog-count-text">
                    <strong><span id="rc-blog-count"><?= (int) $query->found_posts; ?></span></strong> 
                    <span><?= sprintf(_n('article found', 'articles found', $query->found_posts, 'rc'), $query->found_posts); ?></span>
                </span>
            </div>

            <div class="blog-status-bar__active-tags d-flex align-items-center flex-wrap gap-2" id="rc-blog-active-tags" style="display: none;">
                <!-- Dynamically populated chips -->
            </div>

            <div class="blog-status-bar__actions">
                <button type="button" id="rc-blog-clear-all" class="blog-clear-btn" style="display: none;">
                    <span><?php esc_html_e('Clear all filters', 'rc'); ?></span>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

        <!-- Post Results Container -->
        <div class="blog-results-wrapper position-relative" 
             id="rc-blog-wrapper" 
             data-nonce="<?= esc_attr($nonce); ?>" 
             data-total-pages="<?= esc_attr($query->max_num_pages); ?>">

            <!-- Loading Overlay -->
            <div class="ui-loading-overlay blog-loading-overlay" id="rc-blog-loading" style="display: none;" aria-hidden="true">
                <div class="ui-loading-spinner">
                    <div class="spinner-dot"></div>
                </div>
            </div>

            <!-- Post Grid -->
            <div id="rc-blog-results" class="blog-grid" aria-live="polite">
                <?php if ($query->have_posts()): ?>
                    <?php while ($query->have_posts()): $query->the_post(); ?>
                        <?php rc_post_item(get_the_ID()); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>

            <!-- Empty State -->
            <div id="rc-blog-empty" class="blog-empty-state" style="<?php echo ($query->have_posts()) ? 'display: none;' : ''; ?>">
                <div class="blog-empty-state__card">
                    <div class="blog-empty-state__icon" aria-hidden="true">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 class="blog-empty-state__title"><?php esc_html_e('No Articles Found', 'rc'); ?></h3>
                    <p class="blog-empty-state__desc">
                        <?php esc_html_e('We could not find any articles matching your search or category filter. Try using different keywords or reset your filters.', 'rc'); ?>
                    </p>
                    <button type="button" class="ui-button ui-button--secondary blog-empty-state__reset" id="rc-blog-empty-reset">
                        <span><?php esc_html_e('Reset All Filters', 'rc'); ?></span>
                    </button>
                </div>
            </div>

            <!-- Error State -->
            <div id="rc-blog-error" class="blog-error-state" style="display: none;">
                <p id="rc-blog-error-message"></p>
                <button type="button" class="ui-button ui-button--secondary" id="rc-blog-retry-btn">
                    <span><?php esc_html_e('Try Again', 'rc'); ?></span>
                </button>
            </div>

            <!-- AJAX Pagination -->
            <div id="rc-blog-pagination" class="blog-pagination">
                <?php if (function_exists('rc_get_ajax_pagination')): ?>
                    <?= rc_get_ajax_pagination($paged, $query->max_num_pages); ?>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>
