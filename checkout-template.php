<?php
/**
 * Template Name: Checkout
 */
get_header();

$token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
$booking_data = $token ? get_transient('rc_booking_' . $token) : false;

$error_msg = '';
$tour = null;
$selected_option = null;
$total_price = 0;
$price_per_person = 0;
$total_pax = 0;

global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}
$curr_symbol = !empty($currency) ? $currency : '₫';

if (!$booking_data) {
    $error_msg = 'Your booking session has expired or is invalid. Please return to the tours page and try again.';
} else {
    // Validate again and recalculate
    $tour_id = $booking_data['tour_id'];
    $option_id = $booking_data['option_id'];
    $tour = get_post($tour_id);

    if (!$tour) {
        $error_msg = 'The selected tour is no longer available.';
    } else {
        $tour_options = get_field('tour_options', $tour_id);

        if (empty($tour_options) || !isset($tour_options[$option_id])) {
            $error_msg = 'The selected tour option is no longer available.';
        } else {
            $selected_option = $tour_options[$option_id];

            if (isset($booking_data['option_name']) && $booking_data['option_name'] !== ($selected_option['name'] ?? '')) {
                $error_msg = 'Tour option đã thay đổi, vui lòng chọn lại.';
            } else {
                // Recalculate price server-side
                $adults = intval($booking_data['adults']);
                $children = intval($booking_data['children']);
                $total_pax = $adults + $children;

                $pricing = rc_calculate_tour_price($selected_option, $adults, $children, $tour_id);
                $price_per_person = $pricing['price_per_person'];
                $total_price = $pricing['total_price'];
                $is_price_available = $pricing['is_price_available'];
            }
        }
    }
}
?>
<main id="primary" class="site-main rc-checkout-page">
    <div class="rc-checkout-banner">
        <div class="container">
            <h1 class="rc-checkout-banner__title">Complete Your Booking</h1>
            <p class="rc-checkout-banner__subtitle">Please review your tour itinerary and enter your details to finalize your reservation.</p>
        </div>
    </div>

    <div class="rc-checkout-wrap">
        <div class="container">
            <?php if (!empty($error_msg)): ?>
                <div class="rc-checkout-error">
                    <div class="rc-checkout-error__icon">
                        <?php echo rc_svg_icon('alert_circle'); ?>
                    </div>
                    <h3>Session Expired or Tour Not Found</h3>
                    <p><?= esc_html($error_msg) ?></p>
                    <a href="<?= site_url('/phu-quoc-tours') ?>" class="rc-button">
                        <span>Return to Tours</span>
                        <?php echo rc_svg_icon('button_arrow'); ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4 g-xl-5">
                    <!-- Left Column: Customer Form -->
                    <div class="col-lg-7 col-xl-8">
                        <div id="rc-booking-success" style="display: none;">
                            <div class="success-icon">
                                <?php echo rc_svg_icon('check_circle'); ?>
                            </div>
                            <h2>Your order was submitted successfully!</h2>
                            <p class="booking-email-notice">Booking details have been sent to: <b id="booking-email"></b></p>

                            <div class="booking-confirmation-summary">
                                <div class="summary-item">
                                    <span class="label">Booking Number</span>
                                    <strong class="value" id="booking-ref"></strong>
                                </div>
                                <div class="summary-item">
                                    <span class="label">Booking Date</span>
                                    <strong class="value" id="booking-date"></strong>
                                </div>
                                <div class="summary-item">
                                    <span class="label">Payment Method</span>
                                    <strong class="value" id="booking-payment"></strong>
                                </div>
                                <div class="summary-item" id="summary-item-country" style="display: none;">
                                    <span class="label">Country</span>
                                    <strong class="value" id="booking-country"></strong>
                                </div>
                                <div class="summary-item">
                                    <span class="label">Status</span>
                                    <strong class="value badge-status" id="booking-status">Pending Confirmation</strong>
                                </div>
                            </div>

                            <div class="success-actions">
                                <a href="<?= site_url('/phu-quoc-tours') ?>" class="rc-button">
                                    <span>Browse More Tours</span>
                                    <?php echo rc_svg_icon('button_arrow'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="checkout-form-box" id="rc-checkout-form-wrapper">
                            <div class="checkout-section-header">
                                <span class="section-badge">1</span>
                                <h2>Customer Details</h2>
                            </div>

                            <form id="rc-checkout-form" class="rc-form-booking" novalidate>
                                <input type="hidden" name="booking_token" value="<?= esc_attr($token) ?>">

                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <label for="rc-customer-name">Full Name <span class="required">*</span></label>
                                        <input type="text" id="rc-customer-name" name="customer_name" class="form-control" placeholder="Enter your full name" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="rc-customer-email">Email Address <span class="required">*</span></label>
                                        <input type="email" id="rc-customer-email" name="customer_email" class="form-control" placeholder="Enter your email" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <label for="rc-customer-phone">Phone / WhatsApp <span class="required">*</span></label>
                                        <input type="tel" id="rc-customer-phone" name="customer_phone" class="form-control" placeholder="Enter phone number" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="rc-customer-pickup">Pick-up Location <span class="required">*</span></label>
                                        <input type="text" id="rc-customer-pickup" name="customer_pickup" class="form-control" placeholder="Hotel name or address" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <label for="rc-customer-dropoff">Drop-off Location <span class="required">*</span></label>
                                        <input type="text" id="rc-customer-dropoff" name="customer_dropoff" class="form-control" placeholder="Hotel name or address" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="rc-customer-country">Country</label>
                                        <input type="text" id="rc-customer-country" name="customer_country" class="form-control" placeholder="Enter your country">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="rc-customer-messages">Special Requests / Messages</label>
                                    <textarea id="rc-customer-messages" name="customer_messages" class="form-control"
                                        placeholder="Any dietary requirements, flight schedule, or special notes?"></textarea>
                                </div>

                                <div class="checkout-section-header mt-4">
                                    <span class="section-badge">2</span>
                                    <h2>Payment Method</h2>
                                </div>

                                <div class="form-group payment-method-group">
                                    <div class="payment-method-options">
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="pay_on_arrival" checked>
                                            <div class="payment-option__radio-indicator"></div>
                                            <div class="payment-option__info">
                                                <strong class="payment-option__title">Pay on Arrival</strong>
                                                <span class="payment-option__desc">Pay cash or card to driver upon pick-up</span>
                                            </div>
                                        </label>
                                        <label class="payment-option">
                                            <input type="radio" name="payment_method" value="bank_transfer">
                                            <div class="payment-option__radio-indicator"></div>
                                            <div class="payment-option__info">
                                                <strong class="payment-option__title">Bank Transfer</strong>
                                                <span class="payment-option__desc">Fast domestic bank transfer or QR payment</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group terms-group mt-3">
                                    <label class="terms-label">
                                        <input type="checkbox" name="terms_conditions" required>
                                        <span>I have read and accept the terms and conditions and cancellation policy. <span class="required">*</span></span>
                                    </label>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <button type="submit" class="btn-submit-booking rc-button">
                                    <span>CONFIRM BOOKING</span>
                                    <?php echo rc_svg_icon('button_arrow'); ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Summary -->
                    <div class="col-lg-5 col-xl-4 mt-5 mt-lg-0">
                        <div class="checkout-summary-box ticket-cut-indicator">
                            <div class="summary-header">
                                <?php if (has_post_thumbnail($tour->ID)): ?>
                                    <div class="summary-thumb-wrapper">
                                        <?= get_the_post_thumbnail($tour->ID, 'large', ['class' => 'summary-thumb']) ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="summary-tour-title"><?= esc_html($tour->post_title) ?></h3>
                            </div>

                            <ul class="summary-details">
                                <li>
                                    <div class="summary-icon">
                                        <?php echo rc_svg_icon('check'); ?>
                                    </div>
                                    <div>
                                        <span class="detail-label">Tour Option</span>
                                        <strong class="detail-value"><?= esc_html($selected_option['name']) ?></strong>
                                    </div>
                                </li>
                                <li>
                                    <div class="summary-icon">
                                        <?php echo rc_svg_icon('calendar'); ?>
                                    </div>
                                    <div>
                                        <span class="detail-label">Date & Time</span>
                                        <strong class="detail-value"><?= esc_html($booking_data['date']) ?> • <?= esc_html($selected_option['starting_time']) ?></strong>
                                    </div>
                                </li>
                                <li>
                                    <div class="summary-icon">
                                        <?php echo rc_svg_icon('users'); ?>
                                    </div>
                                    <div>
                                        <span class="detail-label">Participants</span>
                                        <strong class="detail-value"><?= esc_html($total_pax) ?> Guests (<?= esc_html($booking_data['adults']) ?> Adults, <?= esc_html($booking_data['children']) ?> Children)</strong>
                                    </div>
                                </li>
                            </ul>

                            <div class="summary-divider"></div>

                            <div class="summary-pricing">
                                <h4 class="pricing-title">Price Breakdown</h4>

                                <?php if (!$is_price_available): ?>
                                    <div class="price-contact-alert">
                                        <?php echo rc_svg_icon('alert_circle'); ?>
                                        <p><strong>Note:</strong> Pricing for this tour requires direct contact. Please leave your details and we will quote you shortly.</p>
                                    </div>
                                <?php else: ?>
                                    <ul class="price-list">
                                        <?php if (!empty($pricing['is_car_tour'])): ?>
                                            <li>
                                                <span>Vehicle Price</span>
                                                <strong><?= number_format($price_per_person, 0, '.', ',') ?> <span class="curr"><?= esc_html($curr_symbol) ?></span></strong>
                                            </li>
                                        <?php elseif (!empty($pricing['is_private_tour'])): ?>
                                            <li>
                                                <span>Private Tour Package</span>
                                                <strong><?= number_format($price_per_person, 0, '.', ',') ?> <span class="curr"><?= esc_html($curr_symbol) ?></span></strong>
                                            </li>
                                        <?php else: ?>
                                            <?php if ($adults > 0): ?>
                                                <li>
                                                    <span>Adults (× <?= esc_html($adults) ?>)</span>
                                                    <strong><?= number_format($price_per_person, 0, '.', ',') ?> <span class="curr"><?= esc_html($curr_symbol) ?></span></strong>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($children > 0): ?>
                                                <li>
                                                    <span>Children (× <?= esc_html($children) ?>)</span>
                                                    <strong><?= number_format($pricing['child_price'], 0, '.', ',') ?> <span class="curr"><?= esc_html($curr_symbol) ?></span></strong>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </ul>
                                    <div class="total-price-box">
                                        <span class="total-label">Total Price</span>
                                        <strong class="total-amount">
                                            <?= number_format($total_price, 0, '.', ',') ?>
                                            <span class="curr-symbol"><?= esc_html($curr_symbol) ?></span>
                                        </strong>
                                    </div>
                                    <div class="summary-guarantees">
                                        <div class="guarantee-item">
                                            <?php echo rc_svg_icon('clock'); ?>
                                            <span>Free cancellation up to 24 hours</span>
                                        </div>
                                        <div class="guarantee-item">
                                            <?php echo rc_svg_icon('check'); ?>
                                            <span>Instant confirmation</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>
