<?php
$heading = get_field('heading_wc_home');
$badge = get_field('badge_wc_home');
$desc = get_field('desc_wc_home');
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="ui-section posts-section" data-purpose="blog-section">
        <div class="container">
            <!-- Section Header -->
            <div class="posts-section__header flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12"
                data-aos="fade-up">
                <div>
                    <?php if (!empty($badge)): ?>
                        <p class="ui-badge"> <?= $badge ?> </p>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="h2">
                            <?= $heading ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($desc)): ?>
                        <p class="ha-desc"> <?= $desc ?> </p>
                    <?php endif; ?>
                </div>
                <a class="ui-button" href="<?php echo home_url('/da-nang-travel-guide') ?>">
                    View All Articles
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </a>
            </div>
            <!-- 3-Card Article Grid -->
            <div class="posts-section__grid grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up">
                <?php
                $badge_modifiers = array(
                    '',
                    ' posts-section__badge--amber',
                    ' posts-section__badge--emerald',
                );
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

                    $categories = get_the_category($post_id);
                    $cat_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
                    $badge_modifier = $badge_modifiers[$card_index % count($badge_modifiers)];

                    $post_date = get_the_date('M j, Y', $post_id);
                    $post_content = get_post_field('post_content', $post_id);
                    $word_count = str_word_count(wp_strip_all_tags((string) $post_content));
                    $reading_time = max(1, (int) ceil($word_count / 200)) . ' min read';

                    $excerpt = trim(wp_strip_all_tags(get_the_excerpt($post_id)));
                    $card_index++;
                    ?>
                    <a href="<?= esc_url($permalink) ?>" class="posts-section__card bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 cursor-pointer transition-all duration-300 hover:shadow-xl flex flex-col group text-decoration-none">
                        <div class="posts-section__thumb aspect-[16/10] relative overflow-hidden">
                            <?php if (!empty($thumb_url)): ?>
                                <img class="w-full h-full object-cover rounded-none transition-transform duration-500 group-hover:scale-105" alt="<?= esc_attr($thumb_alt) ?>" src="<?= esc_url($thumb_url) ?>">
                            <?php endif; ?>
                            <?php if (!empty($cat_name)): ?>
                                <span class="posts-section__badge absolute top-4 left-4 font-semibold px-3 py-1 rounded-full shadow-sm<?= esc_attr($badge_modifier) ?>">
                                    <?= esc_html($cat_name) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="posts-section__meta text-xs mt-5 px-6 flex items-center gap-4">
                            <?php if (!empty($post_date)): ?>
                                <span class="inline-flex items-center gap-1">
                                    <?php echo rc_svg_icon('calendar'); ?>
                                    <?= esc_html($post_date) ?>
                                </span>
                            <?php endif; ?>
                            <span class="inline-flex items-center gap-1">
                                <?php echo rc_svg_icon('clock'); ?>
                                <?= esc_html($reading_time) ?>
                            </span>
                        </div>
                        <?php if (!empty($title)): ?>
                            <h3 class="posts-section__title px-6 mt-2 mb-0 line-clamp-2">
                                <?= esc_html($title) ?>
                            </h3>
                        <?php endif; ?>
                        <?php if (!empty($excerpt)): ?>
                            <p class="posts-section__excerpt px-6 mt-3 mb-0 line-clamp-2">
                                <?= esc_html($excerpt) ?>
                            </p>
                        <?php endif; ?>
                        <div class="mt-auto">
                            <span class="posts-section__link px-6 mt-5 mb-6 inline-flex items-center gap-1">
                                Read Full Article ➔
                            </span>
                        </div>
                    </a>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>