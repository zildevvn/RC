<?php

function rc_breadcrumbs($title)
{
    ?>
    <p class="rc-breadcrumbs d-flex align-items-center flex-wrap mb-0">
        <a href="<?= home_url() ?>" class="rc-breadcrumbs__link">
            <?= rc_svg_icon('home'); ?>
            <span>Home</span>
        </a>
        <span class="rc-breadcrumbs__separator">
            <?= rc_svg_icon('breadcrumb_separator'); ?>
        </span>

        <?php if (is_singular('tours')): ?>
            <a href="<?= home_url('/da-nang-travel-car/') ?>" class="rc-breadcrumbs__link">Tours</a>
            <span class="rc-breadcrumbs__separator">
                <?= rc_svg_icon('breadcrumb_separator'); ?>
            </span>
        <?php endif; ?>

        <?php if (is_singular('cars')): ?>
            <a href="<?= home_url('/car-rental-in-phu-quoc/') ?>" class="rc-breadcrumbs__link">Cars</a>
            <span class="rc-breadcrumbs__separator">
                <?= rc_svg_icon('breadcrumb_separator'); ?>
            </span>
        <?php endif; ?>

        <?php if (is_singular('post')): ?>
            <a href="<?= home_url('/phu-quoc-island-travel-guide/') ?>" class="rc-breadcrumbs__link">Blog</a>
            <span class="rc-breadcrumbs__separator">
                <?= rc_svg_icon('breadcrumb_separator'); ?>
            </span>
        <?php endif; ?>

        <span class="rc-breadcrumbs__current" aria-current="page"><?= esc_html($title) ?></span>
    </p>
<?php }

function rc_table_of_content_shortcode()
{
    return '<div id="rc-table-of-content"></div>';
}
add_shortcode('rc_table_of_content', 'rc_table_of_content_shortcode');


function header_booking_top($original_price, $discount_price)
{ ?>
    <div id="header-booking-top" class="rc-header-booking-top d-md-none">
        <div class="d-flex align-items-center justify-content-between">
            <?php if (!empty($original_price)): ?>
                <h2 class="h4 mb-0">From:
                    <?php if ($discount_price): ?>
                        <span class="car-item__price-discount">
                            <?= esc_html($discount_price) ?>$
                        </span>
                        <?php if ($original_price): ?>
                            <span class="car-item__price-original">
                                <?= esc_html($original_price) ?>$
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="car-item__price-discount">
                            <?= esc_html($original_price) ?>$
                        </span>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>

            <a href="#rc-booking-form" class="rc-button" label="Book a tour" data-scroll-target="#rc-booking-form">
                Book Now
            </a>
        </div>
    </div>
<?php }


if (!function_exists('rc_extract_gallery_image_data')) {
    function rc_extract_gallery_image_data($image)
    {
        $data = [
            'url' => '',
            'thumb' => '',
            'alt' => get_the_title() . ' - Gallery',
            'title' => ''
        ];

        if (is_array($image)) {
            $data['url'] = !empty($image['url']) ? $image['url'] : '';
            $data['thumb'] = isset($image['sizes']['large']) ? $image['sizes']['large'] : $data['url'];
            if (!empty($image['alt'])) {
                $data['alt'] = $image['alt'];
            } elseif (!empty($image['title'])) {
                $data['alt'] = $image['title'];
            }
            $data['title'] = !empty($image['title']) ? $image['title'] : '';
        } elseif (is_numeric($image)) {
            $data['url'] = wp_get_attachment_url($image);
            $img_src_large = wp_get_attachment_image_src($image, 'large');
            $data['thumb'] = $img_src_large ? $img_src_large[0] : $data['url'];
            $alt = get_post_meta($image, '_wp_attachment_image_alt', true);
            if (!empty($alt)) {
                $data['alt'] = $alt;
            } else {
                $data['alt'] = get_the_title($image);
            }
            $data['title'] = get_the_title($image);
        } elseif (is_string($image)) {
            $data['url'] = $image;
            $data['thumb'] = $image;
        }

        return $data;
    }
}


function rc_item_tour($post_id = null, $extra_class = '')
{
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    get_template_part('template-parts/shared/tour-item', null, [
        'post_id'     => $post_id,
        'extra_class' => $extra_class,
    ]);
}


function rc_hero_section_shared($title = '', $image = '', $desc = '', $args = [])
{
    $badge = $args['badge'] ?? '';
    $button_url = $args['button_url'] ?? '';
    $button_text = $args['button_text'] ?? '';
    $button_target = $args['button_target'] ?? '_self';
    $custom_class = $args['custom_class'] ?? '';
    $show_ratings = isset($args['show_ratings']) ? (bool) $args['show_ratings'] : is_singular('post');

    // ------------------------------------------------------------------
    // Resolve Image URL, Attachment ID & Alt Text from WordPress Media
    // ------------------------------------------------------------------
    $attachment_id = !empty($args['image_id']) ? intval($args['image_id']) : 0;
    $alt = !empty($args['alt']) ? trim($args['alt']) : '';

    // Handle array from ACF or attachment meta
    if (is_array($image)) {
        if (!$attachment_id && !empty($image['ID'])) {
            $attachment_id = intval($image['ID']);
        } elseif (!$attachment_id && !empty($image['id'])) {
            $attachment_id = intval($image['id']);
        }
        if (empty($alt) && !empty($image['alt'])) {
            $alt = trim($image['alt']);
        }
        $image = $image['url'] ?? '';
    } elseif (is_numeric($image) && intval($image) > 0) {
        $attachment_id = intval($image);
        if (function_exists('wp_get_attachment_url')) {
            $image = wp_get_attachment_url($attachment_id);
        }
    }

    // Auto-detect post thumbnail ID if $image matches current post thumbnail
    if (!$attachment_id && !empty($image) && function_exists('get_post_thumbnail_id')) {
        $post_thumb_id = get_post_thumbnail_id();
        if ($post_thumb_id) {
            $post_thumb_url = function_exists('get_the_post_thumbnail_url') ? get_the_post_thumbnail_url() : '';
            if ($image === $post_thumb_url) {
                $attachment_id = $post_thumb_id;
            }
        }
    }

    // Try finding attachment ID by URL if still unknown
    if (!$attachment_id && !empty($image) && is_string($image) && function_exists('attachment_url_to_postid')) {
        $attachment_id = attachment_url_to_postid($image);
    }

    // Priority: Fetch alt text from WordPress Media Library
    if (empty($alt) && $attachment_id > 0) {
        $media_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        if (!empty($media_alt)) {
            $alt = trim($media_alt);
        }
    }

    // Fallback according to theme convention (post/page title or attachment title)
    if (empty($alt)) {
        if (!empty($title)) {
            $alt = trim(strip_tags($title));
        } elseif ($attachment_id > 0 && function_exists('get_the_title')) {
            $alt = trim(get_the_title($attachment_id));
        } elseif (function_exists('get_the_title')) {
            $alt = trim(get_the_title());
        }
    }

    $has_image = !empty($image);
    ?>
    <section
        class="rc-section hero-section-shared<?= $has_image ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?><?= !empty($custom_class) ? ' ' . esc_attr($custom_class) : ''; ?>">
        <!-- Background Layer with Multi-Depth Overlay -->
        <div class="hero-section-shared__bg rc-section__bg">
            <?php if ($has_image): ?>
                <img src="<?= esc_url($image); ?>" alt="<?= esc_attr($alt); ?>" class="hero-section-shared__img"
                    loading="eager" />
            <?php endif; ?>
            <div class="hero-section-shared__overlay"></div>
            <div class="hero-section-shared__glow"></div>
            <div class="hero-section-shared__motif" aria-hidden="true">
                <?= rc_svg_icon('hero_pattern'); ?>
            </div>
        </div>

        <div class="container hero-section-shared__container">
            <div class="hero-section-shared__box">
                <!-- Meta Bar: Breadcrumbs Pill & Optional Trust Badge -->
                <div class="hero-section-shared__meta-bar">
                    <div class="hero-section-shared__breadcrumbs-pill">
                        <?php rc_breadcrumbs($title); ?>
                    </div>

                    <?php if (!empty($badge)): ?>
                        <div class="hero-section-shared__pill-badge">
                            <?= rc_svg_icon('sparkle'); ?>
                            <span><?= esc_html($badge); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Main Heading -->
                <h1 class="rc-heading hero-section-shared__title">
                    <?= wp_kses_post($title); ?>
                </h1>

                <!-- Subtitle / Description -->
                <?php if (!empty($desc)): ?>
                    <div class="hero-section-shared__desc">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php endif; ?>

                <!-- Optional Action CTA Button -->
                <?php if (!empty($button_url) && !empty($button_text)): ?>
                    <div class="hero-section-shared__actions">
                        <a href="<?= esc_url($button_url); ?>" class="rc-button rc-button--secondary hero-section-shared__cta"
                            target="<?= esc_attr($button_target); ?>">
                            <span><?= esc_html($button_text); ?></span>
                            <?= rc_svg_icon('arrow_next'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Optional Ratings -->
                <?php if ($show_ratings && function_exists('kk_star_ratings')): ?>
                    <div class="hero-section-shared__ratings">
                        <?= kk_star_ratings(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Architectural Bottom Accent -->
        <div class="hero-section-shared__bottom-accent" aria-hidden="true"></div>
    </section>
<?php }

if (!function_exists('vm_hero_section_shared')) {
    function vm_hero_section_shared($title = '', $image = '', $desc = '', $args = [])
    {
        rc_hero_section_shared($title, $image, $desc, $args);
    }
}

if (!function_exists('vm_breadcrumbs')) {
    function vm_breadcrumbs($title)
    {
        rc_breadcrumbs($title);
    }
}