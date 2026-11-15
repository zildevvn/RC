<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$price_tour = get_field('price_tour');
$formatted_price = rc_format_price($price_tour);
?>
<div id="form-booking-tour" class="ui-form-booking">
    <div class="ui-form-booking__top-badge">
        <?php echo rc_svg_icon('shield_check'); ?>
        <span><?php esc_html_e('Best Price Guarantee', 'rc'); ?></span>
    </div>

    <div class="ui-form-booking__header">
        <div class="ui-form-booking__price price">
            <?php if ($formatted_price !== ''): ?>
                <span class="ui-form-booking__price-label label">FROM</span>
                <div class="ui-form-booking__price-main">
                    <span class="ui-form-booking__price-value value">
                        <?php if (!empty($currency)): ?>
                            <span class="ui-form-booking__price-currency mb-0"><?= esc_html($currency) ?></span>
                        <?php endif; ?>
                        <?= esc_html($formatted_price) ?>
                    </span>
                    <span class="ui-form-booking__price-unit unit">/ car</span>
                </div>
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
            <label for="rc-date-visit">
                <?php echo rc_svg_icon('calendar'); ?>
                <span>Date Visit</span>
            </label>
            <input type="date" id="rc-date-visit" name="date_visit" class="form-control"
                value="<?php echo esc_attr(current_time('Y-m-d')); ?>"
                min="<?php echo esc_attr(current_time('Y-m-d')); ?>">
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label>Adults <small>(12+ yrs)</small></label>
                <div class="quantity-selector">
                    <button class="qty-btn" type="button" aria-label="Decrease adults">-</button>
                    <input type="number" name="adults" value="2" min="1" max="50" aria-label="Number of adults">
                    <button class="qty-btn" type="button" aria-label="Increase adults">+</button>
                </div>
            </div>
            <div class="form-group half">
                <label>Children <small>(4-11 yrs)</small></label>
                <div class="quantity-selector">
                    <button class="qty-btn" type="button" aria-label="Decrease children">-</button>
                    <input type="number" name="children" value="0" min="0" max="50" aria-label="Number of children">
                    <button class="qty-btn" type="button" aria-label="Increase children">+</button>
                </div>
            </div>
        </div>

        <div class="ui-form-error" style="display: none;"></div>

        <button id="rc-btn-check-availability"
            class="ui-button ui-btn-check-availability d-flex align-items-center justify-content-center">
            Find Car
            <?php echo rc_svg_icon('button_arrow'); ?>
        </button>
    </div>

    <div class="ui-form-booking__footer">
        <div class="feature-line">
            <?php echo rc_svg_icon('clock_check'); ?>
            <span>Free cancellation up to 24 hours</span>
        </div>
        <div class="feature-line">
            <?php echo rc_svg_icon('check_circle'); ?>
            <span>Instant confirmation &amp; local support</span>
        </div>
    </div>
</div>