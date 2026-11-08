<?php
/**
 * Single Tour Gallery Section
 *
 * @package rc
 */

$gallery_tour = get_field('gallery_tour');

if (empty($gallery_tour) || !is_array($gallery_tour)) {
    return;
}

// Normalize data once and skip invalid items
$gallery_images = [];
foreach ($gallery_tour as $image) {
    $img_data = rc_extract_gallery_image_data($image);
    if (!empty($img_data['url'])) {
        $gallery_images[] = $img_data;
    }
}

if (empty($gallery_images)) {
    return;
}

$total_images = count($gallery_images);
// Cap layout class to actual number of images (1 to 5)
$layout_count = min(5, $total_images);
$remaining_images = max(0, $total_images - 5);
?>

<section class="tour-gallery" aria-label="<?php esc_attr_e('Tour Photo Gallery', 'rc'); ?>">
    <div class="container">
        <div class="tour-gallery__wrapper">
            <div class="tour-gallery__grid tour-gallery__grid--count-<?php echo esc_attr($layout_count); ?>">
                <?php foreach ($gallery_images as $i => $img_data):
                    $is_featured = ($i === 0);
                    $is_hidden_desktop = ($i >= 5);
                    $is_last_visible = ($i === 4 && $remaining_images > 0);

                    $item_classes = 'tour-gallery__item';
                    if ($is_featured) {
                        $item_classes .= ' tour-gallery__item--featured';
                    }
                    if ($is_hidden_desktop) {
                        $item_classes .= ' tour-gallery__item--hidden';
                    }
                    ?>
                    <button type="button"
                            class="<?php echo esc_attr($item_classes); ?>"
                            data-index="<?php echo $i; ?>"
                            data-full="<?php echo esc_url($img_data['url']); ?>"
                            data-alt="<?php echo esc_attr($img_data['alt']); ?>"
                            data-title="<?php echo esc_attr($img_data['title']); ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('View photo %d of %d', 'rc'), $i + 1, $total_images)); ?>">
                        <img src="<?php echo esc_url($img_data['thumb']); ?>"
                             alt="<?php echo esc_attr($img_data['alt']); ?>"
                             loading="<?php echo $is_featured ? 'eager' : 'lazy'; ?>"
                             decoding="async">

                        <?php if ($is_last_visible): ?>
                            <div class="tour-gallery__counter-badge" aria-hidden="true">
                                <span class="tour-gallery__counter-number">+<?php echo esc_html($remaining_images); ?></span>
                                <span class="tour-gallery__counter-text"><?php echo esc_html(_n('Photo', 'Photos', $remaining_images, 'rc')); ?></span>
                            </div>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php if ($total_images > 1): ?>
                <button type="button"
                        class="tour-gallery__view-all-btn"
                        data-index="0"
                        aria-label="<?php echo esc_attr(sprintf(__('View all %d photos', 'rc'), $total_images)); ?>">
                    <?php echo rc_svg_icon('gallery_image'); ?>
                    <span><?php echo esc_html(sprintf(__('View all %d photos', 'rc'), $total_images)); ?></span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="tour-gallery__lightbox"
     id="tourGalleryLightbox"
     aria-hidden="true"
     role="dialog"
     aria-modal="true"
     aria-label="<?php esc_attr_e('Image Gallery Lightbox', 'rc'); ?>">
    <div class="tour-gallery__lightbox-overlay"></div>
    <div class="tour-gallery__lightbox-content">
        <div class="tour-gallery__lightbox-header">
            <div class="tour-gallery__lightbox-counter" id="tourGalleryLightboxCounter"></div>
            <button type="button" class="tour-gallery__lightbox-close" aria-label="<?php esc_attr_e('Close gallery', 'rc'); ?>">
                <?php echo rc_svg_icon('lightbox_close'); ?>
            </button>
        </div>

        <button type="button" class="tour-gallery__lightbox-prev" aria-label="<?php esc_attr_e('Previous image', 'rc'); ?>">
            <?php echo rc_svg_icon('lightbox_prev'); ?>
        </button>

        <div class="tour-gallery__lightbox-image-container">
            <div class="tour-gallery__lightbox-spinner" aria-hidden="true"></div>
            <img src="" alt="" class="tour-gallery__lightbox-image" id="tourGalleryLightboxImg" decoding="async">
            <div class="tour-gallery__lightbox-caption" id="tourGalleryLightboxCaption"></div>
        </div>

        <button type="button" class="tour-gallery__lightbox-next" aria-label="<?php esc_attr_e('Next image', 'rc'); ?>">
            <?php echo rc_svg_icon('lightbox_next'); ?>
        </button>
    </div>
</div>
