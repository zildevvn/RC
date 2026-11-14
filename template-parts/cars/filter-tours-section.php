<?php
/**
 * Cars Template - Filter Tours Section
 *
 * Responsive layout:
 * - Desktop (>= 992px): Left-side filter sidebar + Right-side tour results.
 * - Mobile (< 992px): "Filter Options" trigger button opening mobile filter modal.
 * - Single source of truth for tour price filtering and sorting.
 * - 100% Shared Homepage tour-item component.
 *
 * @package rc
 */

$currency = function_exists('get_field') ? get_field('currency', 'option') : '';
if (empty($currency)) {
    $currency = '$';
}

// Compute dynamic price range boundary from published tours
global $wpdb;
$price_stats = $wpdb->get_row("
    SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_p, 
           MAX(CAST(meta_value AS DECIMAL(10,2))) as max_p 
    FROM {$wpdb->postmeta} pm
    INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
    WHERE pm.meta_key = 'price_tour' 
      AND p.post_type = 'tours' 
      AND p.post_status = 'publish' 
      AND pm.meta_value IS NOT NULL 
      AND pm.meta_value != ''
");

$min_bound = ($price_stats && $price_stats->min_p !== null) ? floor((float) $price_stats->min_p) : 0;
$max_bound = ($price_stats && $price_stats->max_p !== null) ? ceil((float) $price_stats->max_p) : 500;

if ($min_bound < 0) {
    $min_bound = 0;
}
if ($max_bound <= $min_bound) {
    $max_bound = $min_bound + 100;
}

// Fetch tour_locations taxonomy terms dynamically
$all_location_terms = get_terms([
    'taxonomy' => 'tour_locations',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
]);

$locations_hierarchy = [];
$location_children = [];
$parent_ids = (!empty($all_location_terms) && !is_wp_error($all_location_terms))
    ? wp_list_pluck($all_location_terms, 'term_id')
    : [];

if (!empty($all_location_terms) && !is_wp_error($all_location_terms)) {
    foreach ($all_location_terms as $term) {
        if ($term->parent == 0 || !in_array($term->parent, $parent_ids)) {
            $locations_hierarchy[] = $term;
        } else {
            $location_children[$term->parent][] = $term;
        }
    }
}

// Check if any initial location was requested via URL query param
$initial_selected_locations = [];
if (!empty($_GET['tour_location'])) {
    $loc_param = sanitize_text_field(wp_unslash($_GET['tour_location']));
    if (is_numeric($loc_param)) {
        $initial_selected_locations[] = intval($loc_param);
    } else {
        $term_obj = get_term_by('slug', $loc_param, 'tour_locations');
        if ($term_obj && !is_wp_error($term_obj)) {
            $initial_selected_locations[] = $term_obj->term_id;
        }
    }
} elseif (!empty($_GET['location'])) {
    $loc_param = sanitize_text_field(wp_unslash($_GET['location']));
    $term_obj = get_term_by('slug', $loc_param, 'tour_locations');
    if ($term_obj && !is_wp_error($term_obj)) {
        $initial_selected_locations[] = $term_obj->term_id;
    } elseif (is_numeric($loc_param)) {
        $initial_selected_locations[] = intval($loc_param);
    }
}

// Initial query on page load
$initial_args = [
    'post_type' => 'tours',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => 1,
    'orderby' => [
        'menu_order' => 'ASC',
        'date' => 'DESC',
    ],
];

if (!empty($initial_selected_locations)) {
    $initial_args['tax_query'] = [
        [
            'taxonomy' => 'tour_locations',
            'field' => 'term_id',
            'terms' => $initial_selected_locations,
            'operator' => 'IN',
            'include_children' => true,
        ],
    ];
}

$initial_query = new WP_Query($initial_args);
$nonce = wp_create_nonce('rc_filter_tours_nonce');

/**
 * Helper to render single location item checkbox
 */
$rc_render_location_item = function ($term, $is_child = false, $prefix = 'desktop') use ($initial_selected_locations) {
    $input_id = 'rc-' . $prefix . '-loc-' . $term->term_id;
    $input_class = ($prefix === 'modal') ? 'ui-modal-filter-location-checkbox' : 'ui-filter-location-checkbox';
    $item_class = 'cars-filter-checkbox';
    if ($is_child) {
        $item_class .= ' cars-filter-checkbox--child';
    }
    if ($term->count == 0) {
        $item_class .= ' cars-filter-checkbox--empty';
    }
    $is_checked = in_array($term->term_id, $initial_selected_locations);
    ?>
    <label class="<?php echo esc_attr($item_class); ?>" for="<?php echo esc_attr($input_id); ?>">
        <input type="checkbox" id="<?php echo esc_attr($input_id); ?>" class="<?php echo esc_attr($input_class); ?>"
            value="<?php echo esc_attr($term->term_id); ?>" data-term-id="<?php echo esc_attr($term->term_id); ?>"
            data-term-slug="<?php echo esc_attr($term->slug); ?>" <?php checked($is_checked, true); ?>>
        <span class="cars-filter-checkbox__label"><?php echo esc_html($term->name); ?></span>
        <span class="cars-filter-checkbox__count">(<?php echo esc_html($term->count); ?>)</span>
    </label>
    <?php
};

/**
 * Helper to render location list
 */
$rc_render_locations_tree = function ($prefix = 'desktop') use ($locations_hierarchy, $location_children, $rc_render_location_item) {
    if (!empty($locations_hierarchy)) {
        foreach ($locations_hierarchy as $parent_term) {
            $rc_render_location_item($parent_term, false, $prefix);
            if (!empty($location_children[$parent_term->term_id])) {
                echo '<div class="cars-filter-locations-children">';
                foreach ($location_children[$parent_term->term_id] as $child_term) {
                    $rc_render_location_item($child_term, true, $prefix);
                }
                echo '</div>';
            }
        }
    } else {
        echo '<p class="cars-filter-no-terms">' . esc_html__('No locations found.', 'rc') . '</p>';
    }
};
?>

<section class="ui-section cars-filter-section" id="cars-filter-section" aria-labelledby="cars-filter-heading"
    data-nonce="<?php echo esc_attr($nonce); ?>" data-currency="<?php echo esc_attr($currency); ?>"
    data-min-bound="<?php echo esc_attr($min_bound); ?>" data-max-bound="<?php echo esc_attr($max_bound); ?>"
    data-initial-locations="<?php echo esc_attr(json_encode($initial_selected_locations)); ?>">
    <div class="container">
        <!-- Section Header -->
        <div class="cars-filter-section__header text-center">
            <span class="ui-badge"><?php esc_html_e('Explore Our Fleet & Routes', 'rc'); ?></span>
            <h2 id="cars-filter-heading" class="cars-filter-section__title">
                <?php esc_html_e('Private Car Tours & Transfers', 'rc'); ?>
            </h2>
            <p class="cars-filter-section__subtitle">
                <?php esc_html_e('Browse popular sightseeing trips and door-to-door transfers with private English-speaking chauffeur.', 'rc'); ?>
            </p>
        </div>

        <!-- Two-Column Layout: Left Sidebar + Right Results -->
        <div class="cars-filter-layout">

            <!-- 1. Left Sidebar Filter (Visible on Desktop >= 992px) -->
            <aside class="cars-filter-sidebar" id="cars-filter-desktop-bar"
                aria-label="<?php esc_attr_e('Tour Filters', 'rc'); ?>">
                <div class="cars-filter-sidebar__card">
                    <div class="cars-filter-sidebar__header d-flex align-items-center justify-content-between">
                        <h3 class="cars-filter-sidebar__title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            <span><?php esc_html_e('Filters', 'rc'); ?></span>
                        </h3>
                        <button type="button" class="cars-filter-clear-link" id="rc-filter-clear-desktop"
                            style="display: none;">
                            <?php esc_html_e('Clear All', 'rc'); ?>
                        </button>
                    </div>

                    <!-- Filter 1: Search -->
                    <div class="cars-filter-widget">
                        <label for="rc-filter-search"
                            class="cars-filter-label"><?php esc_html_e('Search Tours', 'rc'); ?></label>
                        <div class="cars-filter-search-box">
                            <span class="cars-filter-search-box__icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </span>
                            <input type="search" id="rc-filter-search" class="cars-filter-search-box__input"
                                placeholder="<?php esc_attr_e('Search by title...', 'rc'); ?>" autocomplete="off">
                            <button type="button" class="cars-filter-search-box__clear" id="rc-filter-search-clear"
                                aria-label="<?php esc_attr_e('Clear search', 'rc'); ?>" style="display: none;">
                                &times;
                            </button>
                        </div>
                    </div>

                    <!-- Filter 2: Tour Locations -->
                    <div class="cars-filter-widget">
                        <div class="cars-filter-widget__header d-flex align-items-center justify-content-between">
                            <span class="cars-filter-label"><?php esc_html_e('Tour Locations', 'rc'); ?></span>
                            <span class="cars-filter-widget__badge" id="rc-locations-badge-desktop"
                                style="display: none;">0</span>
                        </div>
                        <div class="cars-filter-locations-list" id="rc-filter-locations-desktop">
                            <?php $rc_render_locations_tree('desktop'); ?>
                        </div>
                    </div>

                    <!-- Filter 3: Price Range Slider -->
                    <div class="cars-filter-widget">
                        <div class="cars-filter-price-header">
                            <span class="cars-filter-label"><?php esc_html_e('Price Range', 'rc'); ?></span>
                            <span class="cars-filter-price-display" id="rc-price-display">
                                <span
                                    id="rc-price-display-min"><?php echo esc_html($currency . number_format($min_bound)); ?></span>
                                <span class="sep">&ndash;</span>
                                <span
                                    id="rc-price-display-max"><?php echo esc_html($currency . number_format($max_bound)); ?></span>
                            </span>
                        </div>
                        <div class="cars-filter-price-slider-wrap">
                            <input type="range" id="rc-filter-price-min" min="<?php echo esc_attr($min_bound); ?>"
                                max="<?php echo esc_attr($max_bound); ?>" value="<?php echo esc_attr($min_bound); ?>"
                                step="1" class="cars-filter-range-input range-min"
                                aria-label="<?php esc_attr_e('Minimum price', 'rc'); ?>">
                            <input type="range" id="rc-filter-price-max" min="<?php echo esc_attr($min_bound); ?>"
                                max="<?php echo esc_attr($max_bound); ?>" value="<?php echo esc_attr($max_bound); ?>"
                                step="1" class="cars-filter-range-input range-max"
                                aria-label="<?php esc_attr_e('Maximum price', 'rc'); ?>">
                            <div class="cars-filter-slider-track">
                                <div class="cars-filter-slider-progress" id="rc-price-progress"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- 2. Right Content: Tour Results -->
            <main class="cars-filter-results" id="cars-filter-results-main">
                <!-- Results Header: Count on left, Sort By & Mobile Filter Trigger on right -->
                <div class="cars-filter-results-header cars-filter-meta-bar d-flex align-items-center justify-content-between">
                    <div class="cars-filter-count" id="rc-filter-count-text">
                        <span id="rc-filter-count"><?php echo esc_html($initial_query->found_posts); ?></span>
                        <span><?php echo esc_html(_n('tour found', 'tours found', $initial_query->found_posts, 'rc')); ?></span>
                    </div>

                    <div class="cars-filter-results-actions d-flex align-items-center">
                        <!-- Sort By Control -->
                        <div class="cars-filter-sort d-flex align-items-center">
                            <label for="rc-filter-sort" class="cars-filter-sort__label"><?php esc_html_e('Sort By:', 'rc'); ?></label>
                            <div class="cars-filter-select-wrapper">
                                <select id="rc-filter-sort" class="cars-filter-select">
                                    <option value="recommended"><?php esc_html_e('Recommended', 'rc'); ?></option>
                                    <option value="price_low"><?php esc_html_e('Price: Low to High', 'rc'); ?></option>
                                    <option value="price_high"><?php esc_html_e('Price: High to Low', 'rc'); ?></option>
                                    <option value="newest"><?php esc_html_e('Newest', 'rc'); ?></option>
                                    <option value="oldest"><?php esc_html_e('Oldest', 'rc'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Mobile Filter Options Trigger Button (< 992px) -->
                        <button type="button" class="cars-filter-mobile-trigger d-lg-none" id="rc-filter-mobile-trigger"
                            aria-haspopup="dialog" aria-expanded="false" aria-controls="cars-filter-modal">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            <span><?php esc_html_e('Filter Options', 'rc'); ?></span>
                            <span class="cars-filter-mobile-trigger__badge" id="rc-mobile-active-count"
                                style="display: none;">0</span>
                        </button>
                    </div>
                </div>

                <!-- Results Grid Container -->
                <div class="cars-filter-results-container position-relative">
                    <!-- Loading Overlay -->
                    <div class="cars-filter-loading" id="rc-filter-loading" aria-hidden="true" style="display: none;">
                        <div class="cars-filter-spinner"></div>
                    </div>

                    <!-- Shared Grid reusing homepage tour-item card component -->
                    <div class="cars-filter-grid" id="rc-filter-results-grid">
                        <?php
                        if ($initial_query->have_posts()) {
                            while ($initial_query->have_posts()) {
                                $initial_query->the_post();
                                rc_item_tour(get_the_ID());
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    </div>

                    <!-- Empty State -->
                    <div class="cars-filter-empty" id="rc-filter-empty"
                        style="<?php echo !$initial_query->have_posts() ? 'display: block;' : 'display: none;'; ?>">
                        <div class="cars-filter-empty__card">
                            <div class="cars-filter-empty__icon">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </div>
                            <h3 class="cars-filter-empty__title">
                                <?php esc_html_e('No tours found matching your criteria', 'rc'); ?>
                            </h3>
                            <p class="cars-filter-empty__desc">
                                <?php esc_html_e('Try expanding your price range, clearing your search keyword, or resetting all filters.', 'rc'); ?>
                            </p>
                            <button type="button" class="ui-button ui-button--secondary" id="rc-filter-empty-reset">
                                <?php esc_html_e('Reset All Filters', 'rc'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div class="cars-filter-error" id="rc-filter-error" style="display: none;">
                        <p><?php esc_html_e('An error occurred while loading tours. Please try again.', 'rc'); ?></p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="cars-filter-pagination" id="rc-filter-pagination">
                    <?php echo rc_get_ajax_pagination(1, $initial_query->max_num_pages); ?>
                </div>
            </main>
        </div>
    </div>
</section>

<!-- Mobile Filter Modal (< 992px) -->
<div id="cars-filter-modal" class="cars-filter-modal" aria-hidden="true" role="dialog" aria-modal="true"
    aria-labelledby="cars-filter-modal-title">
    <div class="cars-filter-modal__overlay" id="rc-filter-modal-overlay"></div>
    <div class="cars-filter-modal__content" role="document">
        <!-- Modal Header -->
        <div class="cars-filter-modal__header">
            <h3 id="cars-filter-modal-title" class="cars-filter-modal__title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span><?php esc_html_e('Filter Tours', 'rc'); ?></span>
            </h3>
            <button type="button" class="cars-filter-modal__close" id="rc-filter-modal-close"
                aria-label="<?php esc_attr_e('Close filters', 'rc'); ?>">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="cars-filter-modal__body">
            <!-- Modal Filter 1: Search -->
            <div class="cars-filter-widget">
                <label for="rc-modal-filter-search"
                    class="cars-filter-label"><?php esc_html_e('Search Tours', 'rc'); ?></label>
                <div class="cars-filter-search-box">
                    <span class="cars-filter-search-box__icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="search" id="rc-modal-filter-search" class="cars-filter-search-box__input"
                        placeholder="<?php esc_attr_e('Search by title...', 'rc'); ?>" autocomplete="off">
                    <button type="button" class="cars-filter-search-box__clear" id="rc-modal-filter-search-clear"
                        aria-label="<?php esc_attr_e('Clear search', 'rc'); ?>" style="display: none;">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Modal Filter 2: Tour Locations -->
            <div class="cars-filter-widget">
                <div class="cars-filter-widget__header d-flex align-items-center justify-content-between">
                    <span class="cars-filter-label"><?php esc_html_e('Tour Locations', 'rc'); ?></span>
                    <span class="cars-filter-widget__badge" id="rc-modal-locations-badge"
                        style="display: none;">0</span>
                </div>
                <div class="cars-filter-locations-list" id="rc-modal-filter-locations">
                    <?php $rc_render_locations_tree('modal'); ?>
                </div>
            </div>

            <!-- Modal Filter 3: Price Range -->
            <div class="cars-filter-widget">
                <div class="cars-filter-price-header">
                    <span class="cars-filter-label"><?php esc_html_e('Price Range', 'rc'); ?></span>
                    <span class="cars-filter-price-display" id="rc-modal-price-display">
                        <span
                            id="rc-modal-price-display-min"><?php echo esc_html($currency . number_format($min_bound)); ?></span>
                        <span class="sep">&ndash;</span>
                        <span
                            id="rc-modal-price-display-max"><?php echo esc_html($currency . number_format($max_bound)); ?></span>
                    </span>
                </div>
                <div class="cars-filter-price-slider-wrap">
                    <input type="range" id="rc-modal-filter-price-min" min="<?php echo esc_attr($min_bound); ?>"
                        max="<?php echo esc_attr($max_bound); ?>" value="<?php echo esc_attr($min_bound); ?>" step="1"
                        class="cars-filter-range-input range-min"
                        aria-label="<?php esc_attr_e('Minimum price', 'rc'); ?>">
                    <input type="range" id="rc-modal-filter-price-max" min="<?php echo esc_attr($min_bound); ?>"
                        max="<?php echo esc_attr($max_bound); ?>" value="<?php echo esc_attr($max_bound); ?>" step="1"
                        class="cars-filter-range-input range-max"
                        aria-label="<?php esc_attr_e('Maximum price', 'rc'); ?>">
                    <div class="cars-filter-slider-track">
                        <div class="cars-filter-slider-progress" id="rc-modal-price-progress"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer: Clear All & Apply Filters -->
        <div class="cars-filter-modal__footer">
            <button type="button" class="ui-button ui-button--secondary cars-filter-modal__btn-clear"
                id="rc-modal-filter-clear">
                <?php esc_html_e('Clear All', 'rc'); ?>
            </button>
            <button type="button" class="ui-button cars-filter-modal__btn-apply" id="rc-modal-filter-apply">
                <?php esc_html_e('Apply Filters', 'rc'); ?>
            </button>
        </div>
    </div>
</div>