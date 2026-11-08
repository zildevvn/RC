<?php

function rc_breadcrumbs($title)
{
    ?>
    <p class="rc-breadcrumbs d-flex align-items-center flex-wrap mb-0">
        <a href="<?= home_url() ?>" class="rc-breadcrumbs__link">Home</a>
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


function rc_item_tour()
{
    $currency = get_field('currency', 'option');
    $post_id = get_the_ID();
    $title = get_the_title();
    $permalink = get_permalink();
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
    $formatted_price = rc_format_price($price_tour);
    ?>
    <div class="tour-item">
        <div class="tour-item-thumb">
            <?php if (!empty($thumb_url)): ?>
                <img alt="<?= esc_attr($thumb_alt) ?>" src="<?= esc_url($thumb_url) ?>">
            <?php endif; ?>
            <?php if (!empty($card_badge)): ?>
                <span class="tour-item-badge"><?= esc_html($card_badge) ?></span>
            <?php endif; ?>
        </div>
        <div class="tour-item-body">
            <?php if (!empty($time_tour)): ?>
                <div class="tour-item-meta">
                    <?php echo rc_svg_icon('clock'); ?>
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
                                <?php echo rc_svg_icon('check'); ?>
                            </span>
                            <span class="tour-item-feature-text"><?= esc_html($feature_item) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="tour-item-footer mt-auto d-flex align-items-center justify-content-between">
                <div>
                    <?php if ($formatted_price !== ''): ?>
                        <span class="tour-item-price-label d-block">Starting from</span>
                        <span class="tour-item-price"><?= esc_html($currency . $formatted_price) ?>
                            <span>/ car</span></span>
                    <?php endif; ?>
                </div>
                <a class="rc-button" href="<?= esc_url($permalink) ?>">
                    Book Ride
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </div>
<?php }