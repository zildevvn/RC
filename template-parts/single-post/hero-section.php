<?php
/**
 * Single Post - Editorial Header & Featured Image
 *
 * Follows Modern Travel Editorial guidelines:
 * - Clean breadcrumb hierarchy
 * - Prominent Category Badge
 * - Primary H1 Editorial Title
 * - Editorial Lead / Excerpt
 * - Rich Author & Metadata Bar (Author, Date, Reading Time, Comments, Social Sharing)
 * - Cinematic Featured Image with responsive sizing & caption
 *
 * @package rc
 */

$post_id       = get_the_ID();
$title         = get_the_title();
$permalink     = get_permalink();
$excerpt       = has_excerpt() ? get_the_excerpt() : '';

// Featured Image
$thumb_id      = get_post_thumbnail_id($post_id);
$thumb_url     = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt     = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
$thumb_caption = $thumb_id ? wp_get_attachment_caption($thumb_id) : '';
if (empty($thumb_alt)) {
    $thumb_alt = $title;
}

// Category
$categories = get_the_category($post_id);
$primary_cat = (!empty($categories) && !is_wp_error($categories)) ? $categories[0] : null;

// Author Data
$author_id   = get_the_author_meta('ID');
$author_name = get_the_author();
$author_url  = get_author_posts_url($author_id);
$author_avatar = get_avatar_url($author_id, ['size' => 96]);

// Dates
$published_date = get_the_date('M j, Y');
$published_iso  = get_the_date('c');
$modified_date  = get_the_modified_date('M j, Y');
$modified_iso   = get_the_modified_date('c');
$show_modified  = (get_the_modified_time('U') - get_the_time('U')) > 86400; // modified > 24h later

// Reading Time
$post_content = get_post_field('post_content', $post_id);
$word_count   = str_word_count(wp_strip_all_tags((string) $post_content));
$reading_time = max(1, (int) ceil($word_count / 200)) . ' min read';

// Comments
$comments_count = get_comments_number();

// Sharing URLs
$share_title = rawurlencode($title);
$share_url   = rawurlencode($permalink);
$whatsapp_share = 'https://api.whatsapp.com/send?text=' . $share_title . '%20' . $share_url;
$facebook_share = 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url;
$twitter_share  = 'https://twitter.com/intent/tweet?text=' . $share_title . '&url=' . $share_url;
?>

<header class="single-post-header">
    <div class="container">
        <!-- 1. Top Breadcrumbs Navigation -->
        <nav class="single-post-header__breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumbs', 'rc'); ?>">
            <p class="ui-breadcrumbs d-flex align-items-center flex-wrap mb-0">
                <a href="<?= esc_url(home_url('/')); ?>" class="ui-breadcrumbs__link" aria-label="<?php esc_attr_e('Home', 'rc'); ?>">
                    <?php if (function_exists('rc_svg_icon')) echo rc_svg_icon('home'); ?>
                    <span><?php esc_html_e('Home', 'rc'); ?></span>
                </a>
                <span class="ui-breadcrumbs__separator">
                    <?php if (function_exists('rc_svg_icon')) echo rc_svg_icon('breadcrumb_separator'); ?>
                </span>
                <a href="<?= esc_url(home_url('/da-nang-travel-guide/')); ?>" class="ui-breadcrumbs__link">
                    <span><?php esc_html_e('Da Nang Travel Guide', 'rc'); ?></span>
                </a>
                <?php if ($primary_cat): ?>
                    <span class="ui-breadcrumbs__separator">
                        <?php if (function_exists('rc_svg_icon')) echo rc_svg_icon('breadcrumb_separator'); ?>
                    </span>
                    <a href="<?= esc_url(get_category_link($primary_cat->term_id)); ?>" class="ui-breadcrumbs__link">
                        <span><?= esc_html($primary_cat->name); ?></span>
                    </a>
                <?php endif; ?>
                <span class="ui-breadcrumbs__separator">
                    <?php if (function_exists('rc_svg_icon')) echo rc_svg_icon('breadcrumb_separator'); ?>
                </span>
                <span class="ui-breadcrumbs__current" aria-current="page"><?= esc_html($title); ?></span>
            </p>
        </nav>

        <!-- 2. Editorial Title & Lead Section -->
        <div class="single-post-header__content text-center mx-auto">
            <?php if ($primary_cat): ?>
                <div class="single-post-header__category">
                    <a href="<?= esc_url(get_category_link($primary_cat->term_id)); ?>" class="ui-badge single-post-category-pill">
                        <?= esc_html($primary_cat->name); ?>
                    </a>
                </div>
            <?php endif; ?>

            <h1 class="single-post-header__title" itemprop="headline">
                <?= esc_html($title); ?>
            </h1>

            <?php if (!empty($excerpt)): ?>
                <p class="single-post-header__lead" itemprop="description">
                    <?= esc_html($excerpt); ?>
                </p>
            <?php endif; ?>

            <!-- 3. Editorial Metadata & Sharing Row -->
            <div class="single-post-header__meta d-flex flex-wrap align-items-center justify-content-center gap-3 gap-md-4">
                <!-- Author Info -->
                <div class="single-post-meta__author d-inline-flex align-items-center gap-2">
                    <?php if (!empty($author_avatar)): ?>
                        <img src="<?= esc_url($author_avatar); ?>" alt="<?= esc_attr($author_name); ?>" class="single-post-meta__avatar" width="36" height="36" loading="eager" />
                    <?php endif; ?>
                    <span class="single-post-meta__by"><?php esc_html_e('By', 'rc'); ?></span>
                    <a href="<?= esc_url($author_url); ?>" class="single-post-meta__author-name fw-semibold" rel="author">
                        <?= esc_html($author_name); ?>
                    </a>
                </div>

                <span class="single-post-meta__dot" aria-hidden="true">•</span>

                <!-- Publish Date -->
                <div class="single-post-meta__item d-inline-flex align-items-center gap-1.5">
                    <?php if (function_exists('rc_svg_icon')) echo rc_svg_icon('calendar'); ?>
                    <time datetime="<?= esc_attr($published_iso); ?>" itemprop="datePublished">
                        <?= esc_html($published_date); ?>
                    </time>
                </div>

                <?php if ($show_modified): ?>
                    <span class="single-post-meta__dot d-none d-sm-inline" aria-hidden="true">•</span>
                    <div class="single-post-meta__item single-post-meta__updated d-none d-sm-inline-flex align-items-center gap-1">
                        <span>(<?php esc_html_e('Updated', 'rc'); ?>:</span>
                        <time datetime="<?= esc_attr($modified_iso); ?>" itemprop="dateModified">
                            <?= esc_html($modified_date); ?>
                        </time>)
                    </div>
                <?php endif; ?>

                <span class="single-post-meta__dot" aria-hidden="true">•</span>

                <!-- Reading Time -->
                <div class="single-post-meta__item d-inline-flex align-items-center gap-1.5">
                    <?php if (function_exists('rc_svg_icon')) echo rc_svg_icon('clock'); ?>
                    <span><?= esc_html($reading_time); ?></span>
                </div>

                <?php if (comments_open()): ?>
                    <span class="single-post-meta__dot d-none d-md-inline" aria-hidden="true">•</span>
                    <a href="#comments" class="single-post-meta__item single-post-meta__comments d-none d-md-inline-flex align-items-center gap-1.5">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>
                            <?php
                            if ($comments_count == 0) {
                                esc_html_e('Leave a Comment', 'rc');
                            } elseif ($comments_count == 1) {
                                esc_html_e('1 Comment', 'rc');
                            } else {
                                echo sprintf(esc_html__('%s Comments', 'rc'), number_format_i18n($comments_count));
                            }
                            ?>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. Prominent Featured Image Container -->
        <?php if (!empty($thumb_url)): ?>
            <div class="single-post-featured-image-wrap">
                <figure class="single-post-featured-image mb-0">
                    <img src="<?= esc_url($thumb_url); ?>"
                         alt="<?= esc_attr($thumb_alt); ?>"
                         class="single-post-featured-image__img img-fluid"
                         loading="eager"
                         fetchpriority="high"
                         itemprop="image" />
                    <?php if (!empty($thumb_caption)): ?>
                        <figcaption class="single-post-featured-image__caption text-center">
                            <?= esc_html($thumb_caption); ?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            </div>
        <?php endif; ?>
    </div>
</header>
