<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$price_tour = get_field('price_tour');
$formatted_price = rc_format_price($price_tour);
?>
<div id="form-booking-tour" class="ui-form-booking">
    <div class="ui-form-booking__header">
        <div class="ui-form-booking__price price">
            <?php if ($formatted_price !== ''): ?>
                <span class="ui-form-booking__price-label label">FROM</span>
                <span class="ui-form-booking__price-value value">
                    <?php if (!empty($currency)): ?>
                        <span class="ui-form-booking__price-currency mb-0"><?= esc_html($currency) ?></span>
                    <?php endif; ?>
                    <?= esc_html($formatted_price) ?>
                </span>

                <span class="ui-form-booking__price-unit unit">/ car</span>

            <?php endif; ?>
        </div>
        <div class="ui-form-booking__rating rating">
            <?php if (function_exists('kk_star_ratings')): ?>
                <span class="ui-form-booking__rating-text text">
                    <?php echo kk_star_ratings(); ?>
                </span>
            <?php else: ?>
                <span class="ui-form-booking__rating-stars stars">★★★★★</span>
                <?php
                $post_id = get_the_ID();
                $views = function_exists('rc_get_post_views') ? rc_get_post_views($post_id) : 0;
                $display_views = $views > 0 ? number_format($views) : 128;
                ?>
                <span class="ui-form-booking__rating-text text">4.9 <span
                        class="ui-form-booking__rating-count count">(<?= esc_html($display_views); ?> reviews)</span></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="ui-form-booking__body">
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

        <div class="ui-form-error"
            style="display: none; color: #dc3545; font-size: 13px; font-weight: 500; margin-bottom: 15px; padding: 10px; background-color: #f8d7da; border-radius: 6px; border: 1px solid #f5c6cb;">
        </div>
        <button id="rc-btn-check-availability"
            class="ui-button ui-btn-check-availability d-flex align-items-center justify-content-center">
            Check Availability

            <?php echo rc_svg_icon('button_arrow'); ?>
        </button>
    </div>

    <div class="ui-form-booking__footer">
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