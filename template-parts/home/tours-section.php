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
            <svg fill="none" preserveAspectRatio="none" viewBox="0 0 1440 900" xmlns="http://www.w3.org/2000/svg">
                <path d="M -40 160 Q 300 300 540 220 T 960 520 T 1500 360" stroke="#0F4C81" stroke-dasharray="8 8"
                    stroke-width="3" style="opacity: 0.22;"></path>
                <path d="M 60 -30 Q 240 340 640 430 T 1140 600 T 1500 820" stroke="#F59E0B" stroke-dasharray="7 7"
                    stroke-width="2.5" style="opacity: 0.28;"></path>
                <path d="M 200 850 C 450 680 750 780 1020 540 S 1380 400 1500 480" stroke="#0F4C81" stroke-dasharray="10 10"
                    stroke-width="2" style="opacity: 0.18;"></path>
                <circle cx="300" cy="285" fill="#0F4C81" r="7" style="opacity: 0.45;"></circle>
                <circle cx="300" cy="285" fill="none" r="14" stroke="#0F4C81" stroke-width="1.5" style="opacity: 0.35;">
                </circle>
                <circle cx="540" cy="220" fill="#F59E0B" r="8" style="opacity: 0.55;"></circle>
                <circle cx="540" cy="220" fill="none" r="16" stroke="#F59E0B" stroke-width="1.5" style="opacity: 0.4;">
                </circle>
                <circle cx="750" cy="370" fill="#0F4C81" r="7" style="opacity: 0.45;"></circle>
                <circle cx="960" cy="520" fill="#F59E0B" r="9" style="opacity: 0.55;"></circle>
                <circle cx="960" cy="520" fill="none" r="18" stroke="#F59E0B" stroke-width="1.5" style="opacity: 0.4;">
                </circle>
                <circle cx="1210" cy="440" fill="#0F4C81" r="7" style="opacity: 0.45;"></circle>
                <circle cx="640" cy="430" fill="#F59E0B" r="8" style="opacity: 0.5;"></circle>
                <circle cx="1140" cy="600" fill="#0F4C81" r="8" style="opacity: 0.45;"></circle>
            </svg>
        </div>
        <div class="container">
            <!-- 1. Section Header -->
            <div class="tours-section__header text-center mx-auto">
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
            <div class="tours-section__grid">
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
                                    <svg fill="none" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
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
                                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" viewBox="0 0 12 12">
                                                    <polyline points="2.5 6 5 8.5 9.5 3.5"></polyline>
                                                </svg>
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

            <div class="tours-section__footer flex justify-content-center">
                <a href="<?php echo home_url('/da-nang-travel-car/') ?>" class="rc-button"
                    label=" View All  da nang travel car">
                    View All
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>