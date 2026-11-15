<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$title = get_the_title();
$image = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$image) {
    $gallery = get_field('gallery');
    if (!empty($gallery) && is_array($gallery)) {
        $first_img = $gallery[0];
        $image = is_array($first_img) ? ($first_img['url'] ?? '') : (is_numeric($first_img) ? wp_get_attachment_image_url($first_img, 'full') : $first_img);
    }
}
if (!$image) {
    // fallback image
    $image = get_template_directory_uri() . '/assets/images/default-tour-bg.jpg';
}

$location_tour = get_field('location_tour');
$time_tour = get_field('time_tour');
$price_tour = get_field('price_tour');
$formatted_price = rc_format_price($price_tour);
$paxs_tours = get_field('paxs_tours');
$features = get_field('features_tour');

// Taxonomy for Type/Badge
$tour_types = get_the_terms(get_the_ID(), 'tour_cats');
$primary_tour_type = (!empty($tour_types) && !is_wp_error($tour_types)) ? $tour_types[0]->name : __('Signature Experience', 'rc');
?>
<section class="hero-section" style="background-image: url('<?php echo esc_url($image); ?>');">
    <div class="hero-section-overlay"></div>
    <div class="hero-section__motif" aria-hidden="true">
        <?php echo rc_svg_icon('hero_pattern'); ?>
    </div>
    <div class="container">
        <div class="hero-section-content">
            <!-- Top Editorial Meta Bar: Breadcrumbs + Category Badge & Ratings -->
            <div class="hero-section__top-bar">
                <div class="hero-section__breadcrumbs">
                    <?php rc_breadcrumbs($title); ?>
                </div>

                <div class="hero-section__top-meta">
                    <?php if (!empty($primary_tour_type)): ?>
                        <span class="hero-section__badge">
                            <?php echo rc_svg_icon('sparkle'); ?>
                            <span><?php echo esc_html($primary_tour_type); ?></span>
                        </span>
                    <?php endif; ?>

                    <?php if (function_exists('kk_star_ratings')): ?>
                        <div class="hero-section__ratings">
                            <?php echo kk_star_ratings(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tour Title & Lead Excerpt -->
            <div class="hero-section__header">
                <h1 class="hero-section__title"><?php echo esc_html($title); ?></h1>

                <?php if (has_excerpt()): ?>
                    <div class="hero-section__excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Structured Quick-Facts Bar -->
            <?php if ($time_tour || $location_tour || $paxs_tours || $formatted_price !== ''): ?>
                <div class="hero-section__metadata d-flex align-items-center flex-wrap">
                    <?php if ($time_tour): ?>
                        <div class="meta-item">
                            <span class="meta-item__icon" aria-hidden="true">
                                <?php echo rc_svg_icon('clock_check'); ?>
                            </span>
                            <div class="meta-item__body">
                                <span class="meta-item__label"><?php esc_html_e('Duration', 'rc'); ?></span>
                                <span class="meta-item__value"><?php echo esc_html($time_tour); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($location_tour): ?>
                        <div class="meta-item">
                            <span class="meta-item__icon" aria-hidden="true">
                                <?php echo rc_svg_icon('map_pin_check'); ?>
                            </span>
                            <div class="meta-item__body">
                                <span class="meta-item__label"><?php esc_html_e('Destination', 'rc'); ?></span>
                                <span class="meta-item__value"><?php echo esc_html($location_tour); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($paxs_tours): ?>
                        <div class="meta-item">
                            <span class="meta-item__icon" aria-hidden="true">
                                <?php echo rc_svg_icon('users'); ?>
                            </span>
                            <div class="meta-item__body">
                                <span class="meta-item__label"><?php esc_html_e('Tour Type', 'rc'); ?></span>
                                <span class="meta-item__value"><?php echo esc_html($paxs_tours); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($formatted_price !== ''): ?>
                        <div class="meta-item meta-item--price">
                            <span class="meta-item__icon" aria-hidden="true">
                                <?php echo rc_svg_icon('step_tag'); ?>
                            </span>
                            <div class="meta-item__body">
                                <span class="meta-item__label"><?php esc_html_e('Starting From', 'rc'); ?></span>
                                <span class="meta-item__value">
                                    <?php if (!empty($currency)): ?>
                                        <span class="meta-item__currency"><?= esc_html($currency) ?></span>
                                    <?php endif; ?>
                                    <strong><?= esc_html($formatted_price) ?></strong>
                                    <small>/ <?php esc_html_e('car', 'rc'); ?></small>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Tour Feature Badges -->
            <?php if (!empty($features) && is_array($features)): ?>
                <div class="hero-section__features">
                    <?php foreach ($features as $feature):
                        if (empty($feature['item']))
                            continue;
                        ?>
                        <span class="feature-badge">
                            <?php echo rc_svg_icon('check_check'); ?>
                            <span><?= wp_kses_post($feature['item']); ?></span>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>