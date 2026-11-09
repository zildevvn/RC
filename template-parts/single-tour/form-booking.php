<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$price_tour = get_field('price_tour');
$formatted_price = rc_format_price($price_tour);
?>
<div id="form-booking-tour" class="rc-form-booking">
    <div class="rc-form-booking__header">
        <div class="price">
            <?php if ($formatted_price !== ''): ?>
                <span class="label">FROM</span>
                <span class="value">
                    <?php if (!empty($currency)): ?>
                        <span class="mb-0"><?= esc_html($currency) ?></span>
                    <?php endif; ?>
                    <?= esc_html($formatted_price) ?>
                </span>
                <?php
                $is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
                if (!$is_car_tour):
                    ?>
                    <span class="unit">/ person</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="rating">
            <?php if (function_exists('kk_star_ratings')): ?>
                <span class="text">
                    <?php echo kk_star_ratings(); ?>
                </span>
            <?php else: ?>
                <span class="stars">★★★★★</span>
                <?php
                $post_id = get_the_ID();
                $views = function_exists('rc_get_post_views') ? rc_get_post_views($post_id) : 0;
                $display_views = $views > 0 ? number_format($views) : 128;
                ?>
                <span class="text">4.9 (
                    <?php echo esc_html($display_views); ?> reviews)
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="rc-form-booking__body">
        <div class="form-group">
            <label>Date Visit</label>
            <input type="date" id="rc-date-visit" name="date_visit" class="form-control"
                value="<?php echo esc_attr(current_time('Y-m-d')); ?>"
                min="<?php echo esc_attr(current_time('Y-m-d')); ?>">
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label>Adults</label>
                <div class="quantity-selector">
                    <button class="qty-btn" type="button">-</button>
                    <input type="number" name="adults" value="2" min="1" max="50">
                    <button class="qty-btn" type="button">+</button>
                </div>
            </div>
            <div class="form-group half">
                <label>Children <small>(4-11 yrs)</small></label>
                <div class="quantity-selector">
                    <button class="qty-btn" type="button">-</button>
                    <input type="number" name="children" value="0" min="0" max="50">
                    <button class="qty-btn" type="button">+</button>
                </div>
            </div>
        </div>

        <div class="rc-form-error"
            style="display: none; color: #dc3545; font-size: 13px; font-weight: 500; margin-bottom: 15px; padding: 10px; background-color: #f8d7da; border-radius: 6px; border: 1px solid #f5c6cb;">
        </div>
        <button id="rc-btn-check-availability" class="rc-button rc-btn-check-availability d-flex align-items-center justify-content-center">
            Check Availability

            <?php echo rc_svg_icon('button_arrow'); ?>
        </button>
    </div>

    <div class="rc-form-booking__footer">
        <div class="feature-line">
            <?php echo rc_svg_icon('clock'); ?>
            Free cancellation up to 24 hours
        </div>
        <div class="feature-line">
            <?php echo rc_svg_icon('check'); ?>
            Instant confirmation
        </div>
    </div>
</div>