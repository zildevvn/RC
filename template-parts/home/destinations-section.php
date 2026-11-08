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
    <section class="rc-section destinations-section" data-purpose="destinations-section">
        <div class="container">
            <!-- Section Header -->
            <div class="destinations-section__header text-center" data-aos="fade-up">
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
            <div class="destinations-section__list" data-aos="fade-up">
                <?php foreach ($terms as $index => $term): ?>
                    <?php
                    if (!($term instanceof WP_Term)) {
                        continue;
                    }

                    // echo "<pre>";
                    // echo print_r($term);
                    // echo "</pre>";
            
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
                        $card_modifier = ' destinations-section__card--col-7';
                    } elseif ($index === 1) {
                        $card_modifier = ' destinations-section__card--col-5';
                    }
                    ?>
                    <a href="<?= esc_url($term_url) ?>" class="destinations-section__card<?= esc_attr($card_modifier) ?>">
                        <?php if (!empty($image_url)): ?>
                            <img alt="<?= esc_attr($image_alt) ?>" src="<?= esc_url($image_url) ?>">
                        <?php endif; ?>
                        <div class="destinations-section__card-overlay"></div>
                        <span class="destinations-section__card-badge"><?= esc_html($term->count) ?>
                            tours</span>
                        <h3 class="destinations-section__card-title h4 mb-0">
                            <?= esc_html($term->name) ?>
                        </h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>