<?php
/**
 * Template part for displaying the Best Time to Visit section on Tour Locations taxonomy
 *
 * Includes:
 * - Section Header with Category Badge, Editorial Heading, and Descriptive Subheading
 * - Two-Column Layout: Left Column (Travel Insights & Seasonal Advice), Right Column (Responsive Google Maps Embed)
 * - Safe iframe handling for ACF textarea embed codes or direct Google Maps URLs
 *
 * @package rc
 */

$term = get_queried_object();
$best_time = get_field('best_time_visit_section', $term);
$desc = !empty($best_time['description']) ? $best_time['description'] : '';
$map = !empty($best_time['map']) ? trim($best_time['map']) : '';
$term_name = !empty($term->name) ? $term->name : '';

// -----------------------------------------------------------------------------
// Safe Google Maps Embed Resolution
// -----------------------------------------------------------------------------
$map_html = '';
if (!empty($map)) {
    // If the editor provided an <iframe> embed code
    if (strpos($map, '<iframe') !== false) {
        // Ensure accessible title attribute
        if (strpos($map, 'title=') === false) {
            $map = str_replace('<iframe', '<iframe title="' . esc_attr(sprintf(__('Google Maps orientation for %s', 'rc'), $term_name)) . '"', $map);
        }
        // Ensure lazy loading
        if (strpos($map, 'loading=') === false) {
            $map = str_replace('<iframe', '<iframe loading="lazy"', $map);
        }
        $allowed_html = [
            'iframe' => [
                'src' => true,
                'width' => true,
                'height' => true,
                'style' => true,
                'frameborder' => true,
                'allowfullscreen' => true,
                'loading' => true,
                'referrerpolicy' => true,
                'title' => true,
                'aria-hidden' => true,
            ],
        ];
        $map_html = wp_kses($map, $allowed_html);
    } else {
        // If the editor provided a direct Google Maps URL
        $map_html = sprintf(
            '<iframe src="%s" title="%s" width="100%%" height="100%%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            esc_url($map),
            esc_attr(sprintf(__('Google Maps orientation for %s', 'rc'), $term_name))
        );
    }
} else {
    // Graceful destination embed fallback if map field is empty
    $destination_query = !empty($term_name) ? $term_name . ', Central Vietnam' : 'Da Nang, Vietnam';
    $fallback_map_url = add_query_arg([
        'q' => urlencode($destination_query),
        'output' => 'embed',
    ], 'https://maps.google.com/maps');

    $map_html = sprintf(
        '<iframe src="%s" title="%s" width="100%%" height="100%%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
        esc_url($fallback_map_url),
        esc_attr(sprintf(__('Google Maps orientation for %s', 'rc'), $term_name))
    );
}
?>

<section class="ui-section best-time-section" id="best-time-section" aria-labelledby="best-time-heading">
    <div class="container">

        <!-- 1. Section Header -->
        <div class="best-time-section__header text-center mx-auto">
            <span class="ui-badge mb-2">
                <?= esc_html(!empty($term_name) ? sprintf(__('Travel Guide • %s', 'rc'), $term_name) : __('Travel Guide & Seasonality', 'rc')); ?>
            </span>
            <h2 id="best-time-heading" class="ui-heading mb-3">
                <?php esc_html_e('Best Time to Visit', 'rc'); ?>
                <?php if (!empty($term_name)): ?>
                           <?= esc_html($term_name); ?>
                <?php endif; ?>
            </h2>
            <p class="ui-sub-heading text-muted mx-auto mb-0">
                <?php esc_html_e('Plan your trip with seasonal weather advice, ideal months for sightseeing, and local chauffeur navigation.', 'rc'); ?>
            </p>
        </div>

        <!-- 2. Two-Column Editorial & Map Layout -->
        <div class="best-time-section__grid">

            <!-- Left Column: Best Time & Seasonal Insights -->
            <div class="best-time-section__content">
                <?php if (!empty($desc)): ?>
                    <div class="best-time-section__prose">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php else: ?>
                    <div class="best-time-cards">
                        <!-- Season Card 1: Dry / Peak Season -->
                        <div class="best-time-card">
                            <div class="best-time-card__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="4" />
                                    <path
                                        d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                                </svg>
                            </div>
                            <div class="best-time-card__body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-1 flex-wrap">
                                    <h3 class="best-time-card__title mb-0"><?php esc_html_e('Dry & Sunny Season', 'rc'); ?>
                                    </h3>
                                    <span
                                        class="best-time-card__badge"><?php esc_html_e('February – August', 'rc'); ?></span>
                                </div>
                                <p class="best-time-card__desc mb-0">
                                    <?php esc_html_e('Warm sunshine, calm seas, and dry weather make this the ideal window for beach trips, Hai Van Pass scenic drives, and mountain visits like Ba Na Hills and Marble Mountains.', 'rc'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Season Card 2: Mild / Cultural Season -->
                        <div class="best-time-card">
                            <div class="best-time-card__icon best-time-card__icon--emerald" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                            <div class="best-time-card__body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-1 flex-wrap">
                                    <h3 class="best-time-card__title mb-0">
                                        <?php esc_html_e('Pleasant & Tranquil Season', 'rc'); ?>
                                    </h3>
                                    <span
                                        class="best-time-card__badge best-time-card__badge--emerald"><?php esc_html_e('September – January', 'rc'); ?></span>
                                </div>
                                <p class="best-time-card__desc mb-0">
                                    <?php esc_html_e('Cooler temperatures and fewer tourist crowds. Perfect for leisurely cultural tours in Hoi An Ancient Town, Hue Imperial Citadel, and enjoying local culinary specialties.', 'rc'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Chauffeur Travel Tip Box -->
                        <div class="best-time-tip">
                            <div class="best-time-tip__icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 11.1 2 11.5 2 12v4c0 .6.4 1 1 1h2" />
                                    <circle cx="7" cy="17" r="2" />
                                    <path d="M9 17h6" />
                                    <circle cx="17" cy="17" r="2" />
                                </svg>
                            </div>
                            <div class="best-time-tip__text">
                                <strong class="d-block mb-1"><?php esc_html_e('Private Chauffeur Tip', 'rc'); ?></strong>
                                <span><?php esc_html_e('Booking a private driver gives you flexible pickup times to beat peak midday sun or avoid passing rain, ensuring a comfortable, air-conditioned journey throughout your day.', 'rc'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Interactive Google Map -->
            <div class="best-time-section__map-col">
                <div class="best-time-map-wrapper">
                    <div class="best-time-map-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="best-time-map-header__icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </span>
                            <span class="best-time-map-header__title fw-bold">
                                <?= esc_html(!empty($term_name) ? sprintf(__('%s Orientation Map', 'rc'), $term_name) : __('Destination Map', 'rc')); ?>
                            </span>
                        </div>
                        <span class="best-time-map-header__live-pill">
                            <span class="best-time-map-header__live-dot" aria-hidden="true"></span>
                            <?php esc_html_e('Live Map', 'rc'); ?>
                        </span>
                    </div>

                    <div class="best-time-map-frame">
                        <?= $map_html; ?>
                    </div>

                    <div class="best-time-map-footer d-flex align-items-center justify-content-between">
                        <span class="best-time-map-footer__note d-inline-flex align-items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 14 14"></polyline>
                            </svg>
                            <?php esc_html_e('Hotel & Airport Pickup Available', 'rc'); ?>
                        </span>
                        <span class="best-time-map-footer__badge">
                            <?php esc_html_e('Google Maps', 'rc'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>