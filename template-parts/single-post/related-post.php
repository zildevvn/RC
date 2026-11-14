<?php
/**
 * Single Post - Related Posts Section
 *
 * Reuses the Homepage post-item component (`posts-section__card`) to maintain
 * 100% visual and structural consistency with the Homepage posts section.
 * Prioritizes posts from the current post's category with fallback to latest posts.
 *
 * @package rc
 */

$current_post_id = get_the_ID();
$categories      = get_the_category($current_post_id);
$category_ids    = !empty($categories) ? wp_list_pluck($categories, 'term_id') : [];

$args = [
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'post__not_in'        => [$current_post_id],
    'ignore_sticky_posts' => true,
];

if (!empty($category_ids)) {
    $args['category__in'] = $category_ids;
} else {
    $args['orderby'] = 'date';
}

$the_query = new WP_Query($args);

// Fallback to recent posts if no posts found in the same categories
if (!$the_query->have_posts() && !empty($category_ids)) {
    unset($args['category__in']);
    $args['orderby'] = 'date';
    $the_query = new WP_Query($args);
}
?>

<?php if ($the_query->have_posts()): ?>
    <section class="ui-section related-post-section" data-purpose="related-articles">
        <div class="container">
            <div class="related-post-section__header d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4 mb-md-5">
                <div>
                    <span class="ui-badge mb-2"><?php esc_html_e('Explore More', 'rc'); ?></span>
                    <h2 class="ui-heading mb-2">
                        <?php esc_html_e('Related', 'rc'); ?> <span><?php esc_html_e('Articles', 'rc'); ?></span>
                    </h2>
                    <p class="ui-sub-heading mb-0 text-muted">
                        <?php esc_html_e('Curated travel itineraries, road trip tips, and local insights for Da Nang & Central Vietnam.', 'rc'); ?>
                    </p>
                </div>

                <div class="ui-related-nav d-none d-md-flex align-items-center gap-2 flex-shrink-0">
                    <button type="button" class="ui-swiper-prev swiper-prev" aria-label="<?php esc_attr_e('Previous articles', 'rc'); ?>">
                        <?= function_exists('rc_svg_icon') ? rc_svg_icon('lightbox_prev') : '‹'; ?>
                    </button>
                    <button type="button" class="ui-swiper-next swiper-next" aria-label="<?php esc_attr_e('Next articles', 'rc'); ?>">
                        <?= function_exists('rc_svg_icon') ? rc_svg_icon('lightbox_next') : '›'; ?>
                    </button>
                </div>
            </div>

            <!-- Carousel Swiper Container -->
            <div class="related-post-section__list ui-related-carousel swiper">
                <div class="swiper-wrapper">
                    <?php
                    $badge_modifiers = array(
                        '',
                        ' posts-section__badge--amber',
                        ' posts-section__badge--emerald',
                    );
                    $card_index = 0;
                    while ($the_query->have_posts()):
                        $the_query->the_post();
                        $post_id   = get_the_ID();
                        $title     = get_the_title();
                        $permalink = get_permalink();

                        $thumb_id  = get_post_thumbnail_id($post_id);
                        $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
                        $thumb_alt = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
                        if (empty($thumb_alt)) {
                            $thumb_alt = $title;
                        }

                        $categories     = get_the_category($post_id);
                        $cat_name       = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
                        $badge_modifier = $badge_modifiers[$card_index % count($badge_modifiers)];

                        $post_date    = get_the_date('M j, Y', $post_id);
                        $post_content = get_post_field('post_content', $post_id);
                        $word_count   = str_word_count(wp_strip_all_tags((string) $post_content));
                        $reading_time = max(1, (int) ceil($word_count / 200)) . ' min read';

                        $excerpt = trim(wp_strip_all_tags(get_the_excerpt($post_id)));
                        $card_index++;
                        ?>
                        <div class="swiper-slide h-auto">
                            <a href="<?= esc_url($permalink) ?>" class="posts-section__card d-flex flex-column text-decoration-none h-100">
                                <div class="posts-section__thumb position-relative overflow-hidden">
                                    <?php if (!empty($thumb_url)): ?>
                                        <img alt="<?= esc_attr($thumb_alt) ?>" src="<?= esc_url($thumb_url) ?>" loading="lazy">
                                    <?php endif; ?>
                                    <?php if (!empty($cat_name)): ?>
                                        <span class="posts-section__badge<?= esc_attr($badge_modifier) ?>">
                                            <?= esc_html($cat_name) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="posts-section__meta d-flex align-items-center gap-4">
                                    <?php if (!empty($post_date)): ?>
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <?= function_exists('rc_svg_icon') ? rc_svg_icon('calendar') : ''; ?>
                                            <?= esc_html($post_date) ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <?= function_exists('rc_svg_icon') ? rc_svg_icon('clock') : ''; ?>
                                        <?= esc_html($reading_time) ?>
                                    </span>
                                </div>
                                <?php if (!empty($title)): ?>
                                    <h3 class="posts-section__title fw-bold mt-2 mb-0">
                                        <?= esc_html($title) ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if (!empty($excerpt)): ?>
                                    <p class="posts-section__excerpt mt-3 mb-0">
                                        <?= esc_html($excerpt) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="mt-auto">
                                    <span class="posts-section__link d-inline-flex align-items-center gap-1">
                                        Read Full Article ➔
                                    </span>
                                </div>
                            </a>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                <div class="swiper-pagination ui-related-pagination d-md-none mt-4"></div>
            </div>
        </div>
    </section>
<?php endif; ?>
