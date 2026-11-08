<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}
// ₫ : VND
// $ : USD
// € : EUR

$heading = get_field('heading_tours_home');
$badge = get_field('badge_tours_home');
$sub_heading = get_field('desc_tours_home');

$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 6,
    'post_status' => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="rc-section tours-section" data-purpose="popular-tours-transfers">
        <div aria-hidden="true" class="rc-section__bg tours-section__bg">
            <div class="tours-section__bg-dots"></div>
            <?php echo rc_svg_icon('tours_bg'); ?>
        </div>
        <div class="container">
            <!-- 1. Section Header -->
            <div class="tours-section__header text-center mx-auto" data-aos="fade-up">
                <?php if (!empty($badge)): ?>
                    <p class="rc-badge">
                        <?= $badge ?>
                    </p>
                <?php endif; ?>

                <h2 class="h2">
                    <?= $heading ?>
                </h2>

                <?php if (!empty($sub_heading)): ?>
                    <p class="ha-desc">
                        <?= $sub_heading ?>
                    </p>
                <?php endif; ?>
            </div>
            <!-- 3. 6 Tour Cards Grid -->
            <div class="tours-section__grid" data-aos="fade-up">
                <?php
                $card_index = 0;
                while ($query->have_posts()):
                    $query->the_post();
                    $post_id = get_the_ID();
                    $title = get_the_title();
                    $permalink = get_permalink();
                    $thumb_id = get_post_thumbnail_id($post_id);
                    $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
                    $thumb_alt = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
                    if (empty($thumb_alt)) {
                        $thumb_alt = $title;
                    }

                    $tour_cats = get_the_terms($post_id, 'tour_cats');
                    $card_badge = (!empty($tour_cats) && !is_wp_error($tour_cats)) ? $tour_cats[0]->name : '';

                    $time_tour = get_field('time_tour', $post_id);
                    $features_tour = get_field('features_tour', $post_id);
                    $price_tour = get_field('price_tour', $post_id);
                    $formatted_price = rc_format_price($price_tour);
                    $card_index++;
                    ?>
                    <div class="tours-section__card">
                        <div class="tours-section__card-thumb">
                            <?php if (!empty($thumb_url)): ?>
                                <img alt="<?= esc_attr($thumb_alt) ?>" src="<?= esc_url($thumb_url) ?>">
                            <?php endif; ?>
                            <?php if (!empty($card_badge)): ?>
                                <span class="tours-section__card-badge"><?= esc_html($card_badge) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="tours-section__card-body">
                            <?php if (!empty($time_tour)): ?>
                                <div class="tours-section__card-meta">
                                    <?php echo rc_svg_icon('clock'); ?>
                                    <span><?= esc_html($time_tour) ?></span>
                                </div>
                            <?php endif; ?>
                            <h3 class="tours-section__card-title"><?= esc_html($title) ?></h3>
                            <?php if (!empty($features_tour) && is_array($features_tour)): ?>
                                <ul class="tours-section__card-features list-unstyled">
                                    <?php foreach ($features_tour as $feature):
                                        $feature_item = isset($feature['item']) ? trim($feature['item']) : '';
                                        if (empty($feature_item)) {
                                            continue;
                                        }
                                        $feature_item = preg_replace('/\bdrop-o$/i', 'drop-off', $feature_item);
                                        ?>
                                        <li>
                                            <span class="tours-section__card-feature-icon" aria-hidden="true">
                                                <?php echo rc_svg_icon('check'); ?>
                                            </span>
                                            <span class="tours-section__card-feature-text"><?= esc_html($feature_item) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="tours-section__card-footer mt-auto d-flex align-items-center justify-content-between">
                                <div>
                                    <?php if ($formatted_price !== ''): ?>
                                        <span class="tours-section__card-price-label d-block">Starting from</span>
                                        <span class="tours-section__card-price"><?= esc_html($currency . $formatted_price) ?>
                                            <span>/ car</span></span>
                                    <?php endif; ?>
                                </div>
                                <a class="rc-button" href="<?= esc_url($permalink) ?>">
                                    Book Ride
                                    <?php echo rc_svg_icon('arrow_next'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>

            <div class="tours-section__footer d-flex justify-content-center">
                <a href="<?php echo home_url('/da-nang-travel-car/') ?>" class="rc-button"
                    label=" View All  da nang travel car">
                    View All
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>