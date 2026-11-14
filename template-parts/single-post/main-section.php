<?php
/**
 * Single Post - Main Section (2-Column Editorial Layout)
 *
 * Implements:
 * - Left Column:
 *   - Mobile Collapsible Table of Contents
 *   - Main Article Content (editorial typography, heading markers, callouts)
 *   - Post Tags
 *   - Bottom Social Sharing Bar & Copy Link
 *   - Editorial Author Biography Box
 *   - Previous / Next Post Navigation Cards
 *   - WordPress Comments Section
 * - Right Column (Sticky Sidebar):
 *   - Interactive Dynamic Table of Contents (Desktop)
 *   - Rent Car Da Nang Travel Concierge / Booking Box
 *   - Trending / Recent Travel Articles Widget
 *   - Optional ACF Quote Form Widget
 *
 * @package rc
 */

$post_id     = get_the_ID();
$author_id   = get_the_author_meta('ID');
$author_name = get_the_author();
$author_bio  = get_the_author_meta('description');
$author_url  = get_author_posts_url($author_id);
$author_avatar = get_avatar($author_id, 80, '', $author_name, ['class' => 'single-post-author-box__avatar']);
$author_post_count = count_user_posts($author_id, 'post', true);

// ACF Fields
$form = get_field('form_support_blog', 'option');
$phone = function_exists('get_field') ? get_field('phone', 'option') : '+84 901 234 567';
if (empty($phone)) {
    $phone = '+84 901 234 567';
}
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : 'https://wa.me/' . preg_replace('/\D/', '', $phone);
$cars_url = home_url('/cars/');
$tours_url = home_url('/da-nang-travel-car/');

// Post Navigation
$prev_post = get_previous_post();
$next_post = get_next_post();

// Sharing Data
$share_title = rawurlencode(get_the_title());
$share_url   = rawurlencode(get_permalink());
$whatsapp_share = 'https://api.whatsapp.com/send?text=' . $share_title . '%20' . $share_url;
$facebook_share = 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url;
$twitter_share  = 'https://twitter.com/intent/tweet?text=' . $share_title . '&url=' . $share_url;
?>

<section class="ui-section single-post-body-section">
    <div class="container">
        <div class="row single-post-layout">
            <!-- ================================================================ -->
            <!-- Main Content Column (Left) -->
            <!-- ================================================================ -->
            <div class="col-lg-8 single-post-main-col">
                <!-- Mobile Table of Contents (Collapsible) -->
                <div class="ui-post-toc ui-post-toc--mobile d-lg-none mb-4" id="ui-post-toc-mobile" style="display: none;">
                    <button type="button" class="ui-post-toc__toggle d-flex align-items-center justify-content-between w-100" aria-expanded="false" aria-controls="ui-toc-mobile-list">
                        <span class="d-flex align-items-center gap-2 fw-bold">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                            <span><?php esc_html_e('Table of Contents', 'rc'); ?></span>
                        </span>
                        <svg class="ui-post-toc__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <nav class="ui-post-toc__nav" id="ui-toc-mobile-list" style="display: none;">
                        <ul class="ui-post-toc__list mb-0"></ul>
                    </nav>
                </div>

                <!-- Primary Article Content -->
                <article class="single-post-content entry-content" itemprop="articleBody">
                    <?php the_content(); ?>
                </article>

                <!-- Post Tags (if available) -->
                <?php
                $post_tags = get_the_tags();
                if (!empty($post_tags)):
                    ?>
                    <div class="single-post-tags d-flex flex-wrap align-items-center gap-2 mt-5 pt-4 border-top">
                        <span class="single-post-tags__label d-inline-flex align-items-center gap-1.5 fw-semibold text-muted">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                            <span><?php esc_html_e('Tags:', 'rc'); ?></span>
                        </span>
                        <?php foreach ($post_tags as $tag): ?>
                            <a href="<?= esc_url(get_tag_link($tag->term_id)); ?>" class="single-post-tag-pill">
                                #<?= esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Bottom Social Sharing Bar -->
                <div class="single-post-share-bar d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4 p-4 rounded-3">
                    <div class="single-post-share-bar__lead d-flex align-items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary" aria-hidden="true">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                        <span class="fw-semibold text-heading"><?php esc_html_e('Share this travel guide:', 'rc'); ?></span>
                    </div>

                    <div class="single-post-share-bar__actions d-flex align-items-center gap-2">
                        <!-- Copy Link Button -->
                        <button type="button" class="ui-share-btn ui-share-btn--copy" data-url="<?= esc_url(get_permalink()); ?>" aria-label="<?php esc_attr_e('Copy article link', 'rc'); ?>" title="<?php esc_attr_e('Copy Link', 'rc'); ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            <span class="ui-share-btn__label"><?php esc_html_e('Copy Link', 'rc'); ?></span>
                        </button>

                        <!-- WhatsApp -->
                        <a href="<?= esc_url($whatsapp_share); ?>" target="_blank" rel="noopener noreferrer" class="ui-share-btn ui-share-btn--whatsapp" aria-label="<?php esc_attr_e('Share on WhatsApp', 'rc'); ?>" title="WhatsApp">
                            <?= function_exists('rc_svg_icon') ? rc_svg_icon('whatsapp') : 'WA'; ?>
                        </a>

                        <!-- Facebook -->
                        <a href="<?= esc_url($facebook_share); ?>" target="_blank" rel="noopener noreferrer" class="ui-share-btn ui-share-btn--facebook" aria-label="<?php esc_attr_e('Share on Facebook', 'rc'); ?>" title="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>

                        <!-- Twitter / X -->
                        <a href="<?= esc_url($twitter_share); ?>" target="_blank" rel="noopener noreferrer" class="ui-share-btn ui-share-btn--twitter" aria-label="<?php esc_attr_e('Share on X', 'rc'); ?>" title="X">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Editorial Author Box -->
                <div class="single-post-author-box d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-4 mt-5 p-4 rounded-3 border">
                    <div class="single-post-author-box__avatar-wrap flex-shrink-0">
                        <?= $author_avatar; ?>
                    </div>
                    <div class="single-post-author-box__content text-center text-sm-start">
                        <span class="single-post-author-box__badge text-uppercase text-primary fw-bold">
                            <?php esc_html_e('Article Author', 'rc'); ?>
                        </span>
                        <h4 class="single-post-author-box__name fw-bold mt-1 mb-2">
                            <a href="<?= esc_url($author_url); ?>" class="text-heading text-decoration-none">
                                <?= esc_html($author_name); ?>
                            </a>
                        </h4>
                        <p class="single-post-author-box__bio mb-3 text-muted">
                            <?php
                            if (!empty($author_bio)) {
                                echo esc_html($author_bio);
                            } else {
                                esc_html_e('Local travel writer and transportation specialist at Rent Car Da Nang, sharing authentic itineraries, scenic road trips, and practical tips across Central Vietnam.', 'rc');
                            }
                            ?>
                        </p>
                        <div class="single-post-author-box__meta d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-3 text-muted small">
                            <span><?= sprintf(esc_html__('%s articles published', 'rc'), number_format_i18n($author_post_count)); ?></span>
                            <span>•</span>
                            <a href="<?= esc_url($author_url); ?>" class="text-primary fw-semibold text-decoration-none">
                                <?php esc_html_e('View all articles', 'rc'); ?> ➔
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Post Navigation Cards (Previous / Next) -->
                <?php if ($prev_post || $next_post): ?>
                    <nav class="single-post-nav row g-3 mt-4" aria-label="<?php esc_attr_e('Article Navigation', 'rc'); ?>">
                        <div class="col-sm-6">
                            <?php if ($prev_post): ?>
                                <a href="<?= esc_url(get_permalink($prev_post->ID)); ?>" class="single-post-nav-card single-post-nav-card--prev d-flex flex-column h-100 p-3 rounded-3 border text-decoration-none">
                                    <span class="single-post-nav-card__label d-inline-flex align-items-center gap-1.5 text-muted small mb-2">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                        <span><?php esc_html_e('Previous Article', 'rc'); ?></span>
                                    </span>
                                    <h5 class="single-post-nav-card__title mb-0 line-clamp-2">
                                        <?= esc_html(get_the_title($prev_post->ID)); ?>
                                    </h5>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php if ($next_post): ?>
                                <a href="<?= esc_url(get_permalink($next_post->ID)); ?>" class="single-post-nav-card single-post-nav-card--next d-flex flex-column align-items-sm-end text-sm-end h-100 p-3 rounded-3 border text-decoration-none">
                                    <span class="single-post-nav-card__label d-inline-flex align-items-center gap-1.5 text-muted small mb-2">
                                        <span><?php esc_html_e('Next Article', 'rc'); ?></span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </span>
                                    <h5 class="single-post-nav-card__title mb-0 line-clamp-2">
                                        <?= esc_html(get_the_title($next_post->ID)); ?>
                                    </h5>
                                </a>
                            <?php endif; ?>
                        </div>
                    </nav>
                <?php endif; ?>

                <!-- Comments Section -->
                <div class="single-post-comments-wrapper mt-5 pt-4 border-top">
                    <?php comments_template(); ?>
                </div>
            </div>

            <!-- ================================================================ -->
            <!-- Sticky Editorial Sidebar (Right) -->
            <!-- ================================================================ -->
            <aside class="col-lg-4 single-post-sidebar-col" aria-label="<?php esc_attr_e('Article Sidebar', 'rc'); ?>">
                <div class="single-post-sidebar sticky-top">
                    <!-- Widget 1: Interactive Table of Contents (Desktop) -->
                    <div class="sidebar-widget ui-post-toc ui-post-toc--desktop d-none d-lg-block" id="ui-post-toc-desktop" style="display: none;">
                        <div class="ui-post-toc__header d-flex align-items-center gap-2 mb-3">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary" aria-hidden="true">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                            <h4 class="widget-title mb-0"><?php esc_html_e('Table of Contents', 'rc'); ?></h4>
                        </div>
                        <nav class="ui-post-toc__nav">
                            <ul class="ui-post-toc__list mb-0"></ul>
                        </nav>
                    </div>

                    <!-- Widget 2: Rent Car Da Nang Travel Concierge Card -->
                    <div class="sidebar-widget single-post-concierge-card p-4 rounded-3">
                        <div class="single-post-concierge-card__badge mb-2">
                            <span class="ui-badge single-post-concierge-card__pill"><?php esc_html_e('Da Nang Travel Concierge', 'rc'); ?></span>
                        </div>
                        <h4 class="single-post-concierge-card__title fw-bold mb-2">
                            <?php esc_html_e('Explore Da Nang & Hoi An With Total Comfort', 'rc'); ?>
                        </h4>
                        <p class="single-post-concierge-card__desc text-muted small mb-3">
                            <?php esc_html_e('Private modern fleet with experienced English-speaking drivers. Transparent fixed prices with zero hidden surcharges.', 'rc'); ?>
                        </p>

                        <ul class="single-post-concierge-card__perks list-unstyled small mb-4">
                            <li class="d-flex align-items-center gap-2 mb-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span><?php esc_html_e('Airport pickup & hotel transfers', 'rc'); ?></span>
                            </li>
                            <li class="d-flex align-items-center gap-2 mb-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span><?php esc_html_e('Custom day trips to Hoi An & Ba Na Hills', 'rc'); ?></span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span><?php esc_html_e('Free 24h cancellation & flight delay cover', 'rc'); ?></span>
                            </li>
                        </ul>

                        <div class="single-post-concierge-card__actions d-flex flex-column gap-2">
                            <a href="<?= esc_url($tours_url); ?>" class="ui-button w-100 justify-content-center">
                                <span><?php esc_html_e('View Popular Car Tours', 'rc'); ?></span>
                                <?= function_exists('rc_svg_icon') ? rc_svg_icon('arrow_next') : '➔'; ?>
                            </a>
                            <a href="<?= esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" class="ui-button--secondary w-100 justify-content-center d-flex align-items-center gap-2">
                                <?= function_exists('rc_svg_icon') ? rc_svg_icon('whatsapp') : ''; ?>
                                <span><?php esc_html_e('Chat on WhatsApp', 'rc'); ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Widget 3: Trending / Recent Posts -->
                    <div class="sidebar-widget widget-recent-posts p-4 rounded-3 border">
                        <h4 class="widget-title mb-3 d-flex align-items-center gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary" aria-hidden="true">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span><?php esc_html_e('Latest Travel Stories', 'rc'); ?></span>
                        </h4>

                        <div class="recent-posts-list d-flex flex-column gap-4">
                            <?php
                            $recent_args = array(
                                'post_type'           => 'post',
                                'posts_per_page'      => 4,
                                'post_status'         => 'publish',
                                'post__not_in'        => array($post_id),
                                'ignore_sticky_posts' => true
                            );
                            $recent_query = new WP_Query($recent_args);

                            if ($recent_query->have_posts()):
                                $badge_modifiers = array(
                                    '',
                                    ' posts-section__badge--amber',
                                    ' posts-section__badge--emerald',
                                );
                                $card_index = 0;
                                while ($recent_query->have_posts()):
                                    $recent_query->the_post();
                                    $r_post_id    = get_the_ID();
                                    $r_title      = get_the_title();
                                    $r_permalink  = get_permalink();
                                    $r_thumb_id   = get_post_thumbnail_id($r_post_id);
                                    $r_thumb      = get_the_post_thumbnail_url($r_post_id, 'large');
                                    if (empty($r_thumb)) {
                                        $r_thumb = content_url('/uploads/2026/11/image-Da-Nang-Travel-Guide.webp');
                                    }
                                    $r_thumb_alt  = $r_thumb_id ? get_post_meta($r_thumb_id, '_wp_attachment_image_alt', true) : '';
                                    if (empty($r_thumb_alt)) {
                                        $r_thumb_alt = $r_title;
                                    }
                                    $r_categories = get_the_category($r_post_id);
                                    $r_cat_name   = (!empty($r_categories) && !is_wp_error($r_categories)) ? $r_categories[0]->name : '';
                                    $r_badge_mod  = $badge_modifiers[$card_index % count($badge_modifiers)];
                                    $r_post_date  = get_the_date('M j, Y', $r_post_id);
                                    $r_content    = get_post_field('post_content', $r_post_id);
                                    $r_word_count = str_word_count(wp_strip_all_tags((string) $r_content));
                                    $r_reading    = max(1, (int) ceil($r_word_count / 200)) . ' min read';
                                    $r_excerpt    = trim(wp_strip_all_tags(get_the_excerpt($r_post_id)));
                                    if (empty($r_excerpt) && !empty($r_content)) {
                                        $r_excerpt = wp_trim_words(wp_strip_all_tags((string) $r_content), 18, '...');
                                    }
                                    $card_index++;
                                    ?>
                                    <a href="<?= esc_url($r_permalink) ?>" class="ui-post-item posts-section__card d-flex flex-column text-decoration-none">
                                        <div class="posts-section__thumb position-relative overflow-hidden">
                                            <?php if (!empty($r_thumb)): ?>
                                                <img alt="<?= esc_attr($r_thumb_alt) ?>" src="<?= esc_url($r_thumb) ?>" loading="lazy">
                                            <?php endif; ?>
                                            <?php if (!empty($r_cat_name)): ?>
                                                <span class="posts-section__badge<?= esc_attr($r_badge_mod) ?>">
                                                    <?= esc_html($r_cat_name) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="posts-section__meta d-flex align-items-center gap-4">
                                            <?php if (!empty($r_post_date)): ?>
                                                <span class="d-inline-flex align-items-center gap-1">
                                                    <?= function_exists('rc_svg_icon') ? rc_svg_icon('calendar') : ''; ?>
                                                    <?= esc_html($r_post_date) ?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="d-inline-flex align-items-center gap-1">
                                                <?= function_exists('rc_svg_icon') ? rc_svg_icon('clock') : ''; ?>
                                                <?= esc_html($r_reading) ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($r_title)): ?>
                                            <h3 class="posts-section__title fw-bold mt-2 mb-0">
                                                <?= esc_html($r_title) ?>
                                            </h3>
                                        <?php endif; ?>
                                        <?php if (!empty($r_excerpt)): ?>
                                            <p class="posts-section__excerpt mt-3 mb-0">
                                                <?= esc_html($r_excerpt) ?>
                                            </p>
                                        <?php endif; ?>
                                        <div class="mt-auto">
                                            <span class="posts-section__link d-inline-flex align-items-center gap-1">
                                                Read Full Article ➔
                                            </span>
                                        </div>
                                    </a>
                                <?php endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>

                    <!-- Widget 4: Support Form (if configured in ACF) -->
                    <?php if (!empty($form)): ?>
                        <div class="sidebar-widget widget-form-support p-4 rounded-3 border">
                            <h4 class="widget-title mb-3"><?php esc_html_e('Request A Free Quote', 'rc'); ?></h4>
                            <?php echo do_shortcode($form); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>
