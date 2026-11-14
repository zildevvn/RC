<?php
$heading = get_field('heading_destinations_home');
$badge = get_field('badge_destinations_home');
$sub_heading = get_field('desc_destinations_home');

$terms = get_terms([
    'taxonomy' => 'tour_locations',
    'hide_empty' => false,
    'orderby' => 'term_id',
    'order' => 'DESC',
    'number' => 5,
]);
?>
<?php if (!empty($terms) && !is_wp_error($terms)): ?>
    <section class="ui-section destinations-section" data-purpose="destinations-section">
        <div class="container">
            <!-- Section Header -->
            <div class="destinations-section__header text-center mb-12" data-aos="fade-up">
                <?php if (!empty($badge)): ?>
                    <p class="ui-badge">
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
            <div class="destinations-section__list grid grid-cols-1 md:grid-cols-12 gap-6" data-aos="fade-up">
                <?php foreach ($terms as $index => $term): ?>
                    <?php
                    if (!($term instanceof WP_Term)) {
                        continue;
                    }

                    $image = get_field('image', $term);
                    $image_url = '';
                    $image_alt = $term->name;

                    if (is_array($image)) {
                        $image_url = !empty($image['url']) ? $image['url'] : '';
                        if (!empty($image['alt'])) {
                            $image_alt = $image['alt'];
                        }
                    } elseif (is_numeric($image)) {
                        $image_url = wp_get_attachment_image_url((int) $image, 'full') ?: '';
                        $attachment_alt = get_post_meta((int) $image, '_wp_attachment_image_alt', true);
                        if (!empty($attachment_alt)) {
                            $image_alt = $attachment_alt;
                        }
                    } elseif (is_string($image)) {
                        $image_url = $image;
                    }

                    $term_link = get_term_link($term);
                    $term_url = !is_wp_error($term_link) ? $term_link : '#';

                    $card_modifier = '';
                    if ($index === 0) {
                        $card_modifier = ' destinations-section__card--col-7 md:col-span-7 h-[280px] sm:h-[320px]';
                    } elseif ($index === 1) {
                        $card_modifier = ' destinations-section__card--col-5 md:col-span-5 h-[280px] sm:h-[320px]';
                    } else {
                        $card_modifier = ' md:col-span-4 h-[260px] sm:h-[280px]';
                    }
                    ?>
                    <a href="<?= esc_url($term_url) ?>" class="destinations-section__card block relative rounded-2xl overflow-hidden cursor-pointer shadow-md group<?= esc_attr($card_modifier) ?>">
                        <?php if (!empty($image_url)): ?>
                            <img class="w-full h-full object-cover rounded-none transition-transform duration-500 group-hover:scale-105" alt="<?= esc_attr($image_alt) ?>" src="<?= esc_url($image_url) ?>">
                        <?php endif; ?>
                        <div class="destinations-section__card-overlay absolute inset-0 pointer-events-none"></div>
                        <span class="destinations-section__card-badge"><?= esc_html($term->count) ?>
                            tours</span>
                        <h3 class="destinations-section__card-title h4 mb-0 absolute bottom-6 inset-x-0 text-center">
                            <?= esc_html($term->name) ?>
                        </h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>