<?php
/**
 * Template part for displaying the global Book Car modal
 *
 * @package rc
 */

$form_car = function_exists('get_field') ? get_field('form_book_car', 'option') : '';
$form_car = is_string($form_car) ? trim($form_car) : '';
?>
<!-- Book Car Modal -->
<div id="book-car-modal" class="book-car-modal" aria-hidden="true" role="dialog" aria-modal="true"
    aria-labelledby="book-car-modal-title">
    <div class="book-car-modal__overlay"></div>
    <div class="book-car-modal__content" role="document">
        <div class="book-car-modal__header">
            <div class="book-car-modal__heading">
                <span class="book-car-modal__badge">
                    <?php esc_html_e('Instant Booking', 'rc'); ?>
                </span>
                <h2 id="book-car-modal-title" class="book-car-modal__title">
                    <?php esc_html_e('Book Your Private Car', 'rc'); ?>
                </h2>
                <p class="book-car-modal__subtitle">
                    <?php esc_html_e('Fill in your trip details below and our team will confirm your ride promptly.', 'rc'); ?>
                </p>
            </div>
            <button class="book-car-modal__close" aria-label="<?php esc_attr_e('Close booking modal', 'rc'); ?>"
                type="button">
                <?php echo rc_svg_icon('menu-close-icon'); ?>
            </button>
        </div>
        <div class="book-car-modal__body">
            <?php if (!empty($form_car)): ?>
                <div class="book-car-modal__form">
                    <?php echo do_shortcode($form_car); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

