<?php
/**
 * Template part for displaying the Tour Grid Section on Tour Locations taxonomy
 *
 * Includes:
 * - Section Header with Badge, Heading, and Descriptive Subheading (dynamic with destination fallbacks)
 * - Modern 3-Column Tour Grid reusing the shared `rc_item_tour()` component
 * - AJAX-compatible Pagination with fallback to standard WordPress pagination
 * - Proper HTML container structure with valid closing tags
 *
 * @package rc
 */

$term       = get_queried_object();
$grid_tour  = get_field('grid_tour_section', $term);
$term_name  = !empty($term->name) ? $term->name : '';

// Retrieve ACF fields with smart, conversion-focused destination fallbacks
$heading     = !empty($grid_tour['heading']) ? $grid_tour['heading'] : '';
$sub_heading = !empty($grid_tour['sub_heading']) ? $grid_tour['sub_heading'] : '';

if (empty($heading)) {
    $heading = !empty($term_name)
        ? sprintf(__('Popular %s Tours & Private Car Services', 'rc'), $term_name)
        : __('Popular Tours & Private Car Services', 'rc');
}

if (empty($sub_heading)) {
    $badge = !empty($term_name)
        ? sprintf(__('Explore %s', 'rc'), $term_name)
        : __('Featured Tours', 'rc');
} else {
    $badge = $sub_heading;
}

$description = !empty($grid_tour['description'])
    ? $grid_tour['description']
    : sprintf(__('Handcrafted private day tours, scenic coastal transfers, and custom road trips in %s with English-speaking drivers.', 'rc'), !empty($term_name) ? $term_name : 'Central Vietnam');
?>

<?php if (have_posts()): ?>
    <section class="ui-section grid-tour-section" id="grid-tour-section" aria-labelledby="grid-tour-heading">
        <div class="container">

            <!-- Section Header -->
            <div class="grid-tour-section__header text-center mx-auto">
                <?php if (!empty($badge)): ?>
                    <span class="ui-badge">
                        <?= esc_html($badge); ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 id="grid-tour-heading" class="ui-heading">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($description)): ?>
                    <p class="ui-sub-heading">
                        <?= esc_html($description); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Tour Grid Results Container -->
            <div class="grid-tour-section__grid" id="ui-grid-tours-results" role="region" aria-label="<?php esc_attr_e('Tour List', 'rc'); ?>">
                <?php
                while (have_posts()):
                    the_post();
                    rc_item_tour(get_the_ID(), 'grid-tour-section__card h-100');
                endwhile;
                ?>
            </div>

            <!-- AJAX / Standard Pagination Container -->
            <div class="grid-tour-section__pagination" id="ui-grid-tours-pagination" data-ajax="true" data-action="ui_ajax_load_grid_tours"
                data-container="#ui-grid-tours-results" data-nonce="<?= esc_attr(wp_create_nonce('ui_load_grid_tours')) ?>"
                data-params="<?= esc_attr(wp_json_encode(['category' => $term->slug ?? ''])) ?>">
                <?php
                global $wp_query;
                $current_paged = get_query_var('paged') ? get_query_var('paged') : 1;
                rc_pagination($current_paged, $wp_query->max_num_pages);
                ?>
            </div>

        </div>
    </section>
<?php endif; ?>
