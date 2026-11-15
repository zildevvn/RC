<?php
$itinerary_tour = get_field('itinerary_tour');
$time_line = (!empty($itinerary_tour) && is_array($itinerary_tour) && !empty($itinerary_tour['itinerary_list']))
    ? $itinerary_tour['itinerary_list']
    : [];
$itinerary_desc = (!empty($itinerary_tour) && is_array($itinerary_tour) && !empty($itinerary_tour['itinerary_desc']))
    ? $itinerary_tour['itinerary_desc']
    : '';
$itinerary_img = (!empty($itinerary_tour) && is_array($itinerary_tour) && !empty($itinerary_tour['img']))
    ? $itinerary_tour['img']
    : '';
?>

<?php if (!empty($itinerary_desc) || !empty($time_line) || !empty($itinerary_img)): ?>
    <div id="tour-itinerary" class="tour-itinerary">
        <div class="tour-section-header">
            <span class="tour-section-header__eyebrow">
                <?php echo rc_svg_icon('map_pin_check'); ?>
                <span><?php esc_html_e('Route & Schedule', 'rc'); ?></span>
            </span>
            <h2 class="ui-heading"><?php esc_html_e('Itinerary', 'rc'); ?></h2>
        </div>

        <?php if (!empty($itinerary_desc)): ?>
            <div class="tour-itinerary__desc">
                <?= wp_kses_post($itinerary_desc); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($time_line) && is_array($time_line)): ?>
            <div class="tour-itinerary__list">
                <?php foreach ($time_line as $index => $item): ?>
                    <div class="itinerary-item">
                        <?php
                        $time = $item['time'] ?? '';
                        $title = $item['title'] ?? '';
                        $description = $item['description'] ?? '';
                        $step_num = sprintf('%02d', $index + 1);
                        ?>

                        <div class="itinerary-item__time-wrapper">
                            <?php if ($time): ?>
                                <div class="itinerary-item__time">
                                    <?= esc_html($time); ?>
                                </div>
                            <?php else: ?>
                                <div class="itinerary-item__time itinerary-item__time--step">
                                    <?php echo esc_html(sprintf(__('Stop %s', 'rc'), $step_num)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="itinerary-item__divider" aria-hidden="true">
                            <div class="itinerary-item__dot">
                                <span><?= esc_html($step_num); ?></span>
                            </div>
                            <div class="itinerary-item__line"></div>
                        </div>
                        <div class="itinerary-item__content">
                            <?php if ($title): ?>
                                <h3 class="itinerary-item__title">
                                    <?= esc_html($title); ?>
                                </h3>
                            <?php endif; ?>
                            <?php if ($description): ?>
                                <div class="itinerary-item__desc">
                                    <?= wp_kses_post($description); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($itinerary_img)):
            $img_url = is_array($itinerary_img) ? ($itinerary_img['url'] ?? '') : $itinerary_img;
            $img_alt = (is_array($itinerary_img) && !empty($itinerary_img['alt']))
                ? $itinerary_img['alt']
                : sprintf(__('Itinerary map for %s', 'rc'), get_the_title());
            if (!empty($img_url)):
                ?>
                <div class="tour-itinerary__map-frame">
                    <img src="<?= esc_url($img_url); ?>" alt="<?= esc_attr($img_alt); ?>" loading="lazy" />
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>