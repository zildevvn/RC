<?php
/**
 * Shared Post Item Component
 * Single source of truth for blog post cards across Blog listing, Homepage, Search, and AJAX responses.
 *
 * @package rc
 */

$post_id = isset($args['post_id']) && !empty($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
if (!$post_id) {
    return;
}

$extra_class = isset($args['extra_class']) ? (string) $args['extra_class'] : '';

$title     = get_the_title($post_id);
$permalink = get_permalink($post_id);

// Featured Image
$thumb_id  = get_post_thumbnail_id($post_id);
$thumb_url = get_the_post_thumbnail_url($post_id, 'large');
$thumb_alt = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
if (empty($thumb_alt)) {
    $thumb_alt = $title;
}
if (empty($thumb_url)) {
    $thumb_url = content_url('/uploads/2026/11/image-Da-Nang-Travel-Guide.webp');
}

// Category
$categories = get_the_category($post_id);
$cat_name   = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
$cat_id     = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->term_id : 0;

// Date & Reading Time
$post_date    = get_the_date('M j, Y', $post_id);
$post_content = get_post_field('post_content', $post_id);
$word_count   = str_word_count(wp_strip_all_tags((string) $post_content));
$reading_time = max(1, (int) ceil($word_count / 200)) . ' min read';

// Excerpt
$excerpt = trim(wp_strip_all_tags(get_the_excerpt($post_id)));
if (empty($excerpt) && !empty($post_content)) {
    $excerpt = wp_trim_words(wp_strip_all_tags((string) $post_content), 18, '...');
}
?>

<article class="ui-post-item<?= !empty($extra_class) ? ' ' . esc_attr($extra_class) : '' ?>" data-post-id="<?= esc_attr($post_id) ?>">
    <div class="ui-post-item__thumb">
        <a href="<?= esc_url($permalink) ?>" tabindex="-1" aria-hidden="true" class="ui-post-item__thumb-link">
            <?php if (!empty($thumb_url)): ?>
                <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr($thumb_alt) ?>" loading="lazy" width="600" height="375">
            <?php endif; ?>
        </a>
        <?php if (!empty($cat_name)): ?>
            <span class="ui-post-item__category" data-cat-id="<?= esc_attr($cat_id) ?>">
                <?= esc_html($cat_name) ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="ui-post-item__body">
        <div class="ui-post-item__meta">
            <?php if (!empty($post_date)): ?>
                <span class="ui-post-item__meta-item">
                    <?php if (function_exists('rc_svg_icon')): ?>
                        <?= rc_svg_icon('calendar'); ?>
                    <?php endif; ?>
                    <time datetime="<?= esc_attr(get_the_date('c', $post_id)) ?>"><?= esc_html($post_date) ?></time>
                </span>
            <?php endif; ?>
            <span class="ui-post-item__meta-item">
                <?php if (function_exists('rc_svg_icon')): ?>
                    <?= rc_svg_icon('clock'); ?>
                <?php endif; ?>
                <span><?= esc_html($reading_time) ?></span>
            </span>
        </div>

        <h3 class="ui-post-item__title">
            <a href="<?= esc_url($permalink) ?>">
                <?= esc_html($title) ?>
            </a>
        </h3>

        <?php if (!empty($excerpt)): ?>
            <p class="ui-post-item__excerpt">
                <?= esc_html($excerpt) ?>
            </p>
        <?php endif; ?>

        <div class="ui-post-item__footer">
            <a href="<?= esc_url($permalink) ?>" class="ui-post-item__link">
                <span><?php esc_html_e('Read Full Article', 'rc'); ?></span>
                <?php if (function_exists('rc_svg_icon') && rc_svg_icon('arrow_next')): ?>
                    <?= rc_svg_icon('arrow_next'); ?>
                <?php else: ?>
                    <span aria-hidden="true">➔</span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</article>

