<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$title = get_the_title();
$image = get_the_post_thumbnail_url();
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

// Taxonomy for Type/Badge (assume 'tour_category' or similar, we'll use a generic placeholder or try to fetch it if exists)
$tour_types = get_the_terms(get_the_ID(), 'tour_cats');

?>
<section class="hero-section" style="background-image: url('<?php echo esc_url($image); ?>');">
    <div class="hero-section-overlay"></div>
    <div class="container">
        <div class="hero-section-content">

            <?php rc_breadcrumbs($title); ?>

            <h1 class="hero-section__title"><?php echo esc_html($title); ?></h1>

            <?php if (has_excerpt()): ?>
                <div class="hero-section__excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <div class="hero-section__metadata d-flex align-items-center">
                <?php if ($time_tour): ?>
                    <div class="meta-item">
                        <?php echo rc_svg_icon('clock_check'); ?>
                        <span><?php echo esc_html($time_tour); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($formatted_price !== ''): ?>
                    <div class="meta-item">
                        <span>From
                            <?php if (!empty($currency)): ?>
                                <span class="mb-0"><?= esc_html($currency) ?></span>
                            <?php endif; ?>
                            <?= esc_html($formatted_price) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($features)): ?>
                <div class="hero-section__features">
                    <?php foreach ($features as $feature): ?>
                        <span class="feature-badge">
                            <?php echo rc_svg_icon('check_check'); ?>
                            <?= $feature['item'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (function_exists('kk_star_ratings')): ?>
                <div class="hero-section__ratings">
                    <?php echo kk_star_ratings(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
