<?php
/**
 * Shared Tour Item Component
 * Single source of truth for tour card rendering across Homepage, Cars, Search, and Related Tours.
 *
 * @package rc
 */

$post_id = isset($args['post_id']) && !empty($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
if (!$post_id) {
    return;
}

$extra_class = isset($args['extra_class']) ? (string) $args['extra_class'] : '';

$currency = get_field('currency', 'option');
$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
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
$formatted_price = function_exists('rc_format_price') ? rc_format_price($price_tour) : (is_numeric($price_tour) ? number_format((float)$price_tour) : '');
?>
<div class="tour-item<?= !empty($extra_class) ? ' ' . esc_attr($extra_class) : '' ?>">
    <div class="tour-item-thumb">
        <?php if (!empty($thumb_url)): ?>
            <img alt="<?= esc_attr($thumb_alt) ?>" src="<?= esc_url($thumb_url) ?>" loading="lazy">
        <?php endif; ?>
        <?php if (!empty($card_badge)): ?>
            <span class="tour-item-badge"><?= esc_html($card_badge) ?></span>
        <?php endif; ?>
    </div>
    <div class="tour-item-body">
        <?php if (!empty($time_tour)): ?>
            <div class="tour-item-meta">
                <?php echo function_exists('rc_svg_icon') ? rc_svg_icon('clock') : ''; ?>
                <span><?= esc_html($time_tour) ?></span>
            </div>
        <?php endif; ?>
        <h3 class="tour-item-title"><?= esc_html($title) ?></h3>
        <?php if (!empty($features_tour) && is_array($features_tour)): ?>
            <ul class="tour-item-features list-unstyled">
                <?php foreach ($features_tour as $feature):
                    $feature_item = isset($feature['item']) ? trim($feature['item']) : '';
                    if (empty($feature_item)) {
                        continue;
                    }
                    $feature_item = preg_replace('/\bdrop-o$/i', 'drop-off', $feature_item);
                    ?>
                    <li>
                        <span class="tour-item-feature-icon" aria-hidden="true">
                            <?php echo function_exists('rc_svg_icon') ? rc_svg_icon('check') : ''; ?>
                        </span>
                        <span class="tour-item-feature-text"><?= esc_html($feature_item) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="tour-item-footer mt-auto d-flex align-items-center justify-content-between">
            <div>
                <?php if ($formatted_price !== ''): ?>
                    <span class="tour-item-price-label d-block"><?php esc_html_e('Starting from', 'rc'); ?></span>
                    <span class="tour-item-price"><?= esc_html($currency . $formatted_price) ?>
                        <span>/ car</span></span>
                <?php endif; ?>
            </div>
            <a class="ui-button" href="<?= esc_url($permalink) ?>">
                <?php esc_html_e('Book Ride', 'rc'); ?>
                <?php echo function_exists('rc_svg_icon') ? rc_svg_icon('arrow_next') : ''; ?>
            </a>
        </div>
    </div>
</div>

