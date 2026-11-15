<?php
add_action('wp_ajax_rc_ajax_check_availability', 'rc_ajax_check_availability');
add_action('wp_ajax_nopriv_rc_ajax_check_availability', 'rc_ajax_check_availability');
function rc_ajax_check_availability()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh the page and try again.']);
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
    $adults = isset($_POST['adults']) ? intval($_POST['adults']) : 2;
    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;

    if (!$post_id || empty($date)) {
        wp_send_json_error(['message' => 'Invalid request. Please select a tour date.']);
    }

    if (strtotime($date) < strtotime(current_time('Y-m-d'))) {
        wp_send_json_error(['message' => 'Please select today’s date or a future date.']);
    }

    $total_pax = $adults + $children;
    if ($total_pax < 1 || $total_pax > 50) {
        wp_send_json_error(['message' => 'The total number of guests must be between 1 and 50.']);
    }

    global $post;
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(['message' => 'Tour not found.']);
    }
    setup_postdata($post);

    $tour_options = get_field('tour_options', $post_id);
    $filtered_options = [];

    if (!empty($tour_options) && is_array($tour_options)) {
        foreach ($tour_options as $key => $option) {
            $type = $option['type'] ?? '';
            $capacity = intval($type);

            if ($capacity <= 0 && !empty($option['pax'])) {
                $capacity = intval(preg_replace('/\D/', '', $option['pax']));
            }
            if ($capacity <= 0 && !empty($option['maximum'])) {
                $capacity = intval(preg_replace('/\D/', '', $option['maximum']));
            }

            // Filter: Capacity >= Total Guests
            if ($capacity <= 0 || $capacity >= $total_pax) {
                $filtered_options[$key] = $option;
            }
        }
    }

    $count = count($filtered_options);

    ob_start();
    get_template_part('template-parts/single-tour/tour-options', null, [
        'post_id'          => $post_id,
        'adults'           => $adults,
        'children'         => $children,
        'total_pax'        => $total_pax,
        'filtered_options' => $filtered_options,
    ]);
    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success([
        'count'        => $count,
        'html'         => $html,
        'total_guests' => $total_pax,
    ]);
}

add_action('wp_ajax_rc_ajax_process_booking', 'rc_ajax_process_booking');
add_action('wp_ajax_nopriv_rc_ajax_process_booking', 'rc_ajax_process_booking');
function rc_ajax_process_booking()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh the page and try again.']);
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $option_id = isset($_POST['option_id']) ? intval($_POST['option_id']) : -1;
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
    $adults = isset($_POST['adults']) ? intval($_POST['adults']) : 2;
    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;

    if (!$post_id || $option_id < 0 || empty($date)) {
        wp_send_json_error(['message' => 'Invalid booking details.']);
    }

    if (strtotime($date) < strtotime(current_time('Y-m-d'))) {
        wp_send_json_error(['message' => 'Please select today’s date or a future date.']);
    }

    if ($adults < 0 || $children < 0) {
        wp_send_json_error(['message' => 'Invalid participant count.']);
    }
    $total_pax = $adults + $children;
    if ($total_pax < 1 || $total_pax > 50) {
        wp_send_json_error(['message' => 'The total number of guests must be between 1 and 50.']);
    }

    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(['message' => 'Tour not found.']);
    }

    $tour_options = get_field('tour_options', $post_id);
    if (empty($tour_options) || !isset($tour_options[$option_id])) {
        wp_send_json_error(['message' => 'Invalid tour option.']);
    }

    $booking_data = [
        'tour_id' => $post_id,
        'option_id' => $option_id,
        'option_name' => $tour_options[$option_id]['name'] ?? '',
        'date' => $date,
        'adults' => $adults,
        'children' => $children,
    ];

    $token = bin2hex(random_bytes(16));
    set_transient('rc_booking_' . $token, $booking_data, HOUR_IN_SECONDS);

    wp_send_json_success([
        'redirect_url' => site_url('/checkout/?token=' . $token)
    ]);
}

add_action('wp_ajax_rc_ajax_submit_checkout', 'rc_ajax_submit_checkout');
add_action('wp_ajax_nopriv_rc_ajax_submit_checkout', 'rc_ajax_submit_checkout');
function rc_ajax_submit_checkout()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh the page and try again.']);
    }

    $token = isset($_POST['booking_token']) ? sanitize_text_field($_POST['booking_token']) : '';
    if (empty($token)) {
        wp_send_json_error(['message' => 'Invalid booking session.']);
    }

    $booking_data = get_transient('rc_booking_' . $token);
    if (!$booking_data) {
        wp_send_json_error(['message' => 'Your booking session has expired or is invalid.']);
    }

    $customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
    $customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
    $customer_phone = isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '';
    $customer_pickup = isset($_POST['customer_pickup']) ? sanitize_text_field($_POST['customer_pickup']) : '';
    $customer_dropoff = isset($_POST['customer_dropoff']) ? sanitize_text_field($_POST['customer_dropoff']) : '';
    $customer_country = isset($_POST['customer_country']) ? sanitize_text_field($_POST['customer_country']) : '';
    $customer_messages = isset($_POST['customer_messages']) ? sanitize_textarea_field($_POST['customer_messages']) : '';
    $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_pickup) || empty($customer_dropoff)) {
        wp_send_json_error(['message' => 'Please fill in all required fields.']);
    }

    if (!is_email($customer_email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    $tour_id = $booking_data['tour_id'];
    $option_id = $booking_data['option_id'];
    $tour = get_post($tour_id);
    if (!$tour) {
        wp_send_json_error(['message' => 'The selected tour is no longer available.']);
    }

    $tour_options = get_field('tour_options', $tour_id);
    if (empty($tour_options) || !isset($tour_options[$option_id])) {
        wp_send_json_error(['message' => 'The selected tour option is no longer available.']);
    }

    $selected_option = $tour_options[$option_id];

    $adults = intval($booking_data['adults']);
    $children = intval($booking_data['children']);
    $total_pax = $adults + $children;

    $pricing = rc_calculate_tour_price($selected_option, $adults, $children, $tour_id);
    $total_price = $pricing['total_price'];

    // Check for existing pending booking post for this token to avoid duplicates
    $existing_post_id = get_transient('rc_booking_post_' . $token);
    if ($existing_post_id && get_post_type($existing_post_id) === 'tour_booking') {
        $booking_post_id = $existing_post_id;
        $booking_ref = get_the_title($booking_post_id);
    } else {
        $booking_ref = 'RC-' . strtoupper(substr(uniqid(), -6));
        $booking_post_id = wp_insert_post([
            'post_title' => $booking_ref,
            'post_status' => 'publish',
            'post_type' => post_type_exists('tour_booking') ? 'tour_booking' : 'post',
        ], false);
    }

    if (!$booking_post_id || is_wp_error($booking_post_id)) {
        wp_send_json_error(['message' => 'Could not initialize booking. Please try again.']);
    }

    update_post_meta($booking_post_id, 'customer_name', $customer_name);
    update_post_meta($booking_post_id, 'customer_email', $customer_email);
    update_post_meta($booking_post_id, 'customer_phone', $customer_phone);
    update_post_meta($booking_post_id, 'customer_pickup', $customer_pickup);
    update_post_meta($booking_post_id, 'customer_dropoff', $customer_dropoff);
    update_post_meta($booking_post_id, 'customer_country', $customer_country);
    update_post_meta($booking_post_id, 'customer_messages', $customer_messages);
    update_post_meta($booking_post_id, 'payment_method', $payment_method);
    update_post_meta($booking_post_id, 'payment_status', 'pending');
    update_post_meta($booking_post_id, 'booking_status', 'Pending Confirmation');
    update_post_meta($booking_post_id, 'tour_id', $tour_id);
    update_post_meta($booking_post_id, 'booking_token', $token);
    update_post_meta($booking_post_id, 'option_name', $selected_option['name'] ?? '');
    update_post_meta($booking_post_id, 'date', $booking_data['date']);
    update_post_meta($booking_post_id, 'adults', $adults);
    update_post_meta($booking_post_id, 'children', $children);
    update_post_meta($booking_post_id, 'total_pax', $total_pax);
    update_post_meta($booking_post_id, 'total_price', $total_price);

    $payment_method_label = ($payment_method === 'bank_transfer') ? 'Bank Transfer' : 'Pay on Arrival';
    $formatted_date = date('F j, Y', strtotime($booking_data['date']));

    $email_sent = rc_send_booking_confirmation_email($booking_post_id);
    rc_send_internal_booking_notification($booking_post_id);

    delete_transient('rc_booking_' . $token);
    delete_transient('rc_booking_post_' . $token);

    wp_send_json_success([
        'message' => 'Booking successful.',
        'reference' => $booking_ref,
        'date' => $formatted_date,
        'payment_method' => $payment_method_label,
        'status' => 'Pending Confirmation',
        'country' => $customer_country,
        'customer_email' => $customer_email,
        'email_sent' => $email_sent,
    ]);
}

/**
 * Send booking confirmation email to customer (with idempotency and status tracking).
 *
 * @param int $booking_post_id
 * @return bool True if wp_mail() accepted the message for delivery, false otherwise.
 */
function rc_send_booking_confirmation_email($booking_post_id)
{
    $booking_post_id = intval($booking_post_id);
    if ($booking_post_id <= 0) {
        return false;
    }

    // Prevent duplicate confirmation emails for the same booking
    if (get_post_meta($booking_post_id, 'booking_email_sent', true) === '1') {
        return true;
    }

    $customer_email = sanitize_email(get_post_meta($booking_post_id, 'customer_email', true));
    if (empty($customer_email) || !is_email($customer_email)) {
        update_post_meta($booking_post_id, 'booking_email_sent', '0');
        return false;
    }

    $booking_ref = get_the_title($booking_post_id);
    $customer_name = get_post_meta($booking_post_id, 'customer_name', true);
    $tour_id = intval(get_post_meta($booking_post_id, 'tour_id', true));
    $tour_title = $tour_id ? get_the_title($tour_id) : '';
    $option_name = get_post_meta($booking_post_id, 'option_name', true);
    $raw_date = get_post_meta($booking_post_id, 'date', true);
    $formatted_date = $raw_date ? date('F j, Y', strtotime($raw_date)) : '';
    $total_pax = get_post_meta($booking_post_id, 'total_pax', true);
    $adults = get_post_meta($booking_post_id, 'adults', true);
    $children = get_post_meta($booking_post_id, 'children', true);
    $pickup = get_post_meta($booking_post_id, 'customer_pickup', true);
    $dropoff = get_post_meta($booking_post_id, 'customer_dropoff', true);
    $method = get_post_meta($booking_post_id, 'payment_method', true);
    $booking_status = get_post_meta($booking_post_id, 'booking_status', true) ?: 'Pending Confirmation';

    $method_label = ($method === 'paypal')
        ? 'PayPal'
        : (($method === 'bank_transfer') ? 'Bank Transfer' : 'Pay on Arrival');

    $site_name = wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES);
    $subject = sprintf('[%s] Booking Confirmation - %s', $site_name, $booking_ref);

    $lines = [
        sprintf('Dear %s,', $customer_name ?: 'Guest'),
        '',
        sprintf('Thank you for booking with %s. Here are your reservation details:', $site_name),
        '',
        sprintf('Booking Reference: %s', $booking_ref),
        sprintf('Tour / Service: %s', $tour_title),
        sprintf('Option: %s', $option_name),
        sprintf('Date: %s', $formatted_date),
        sprintf('Participants: %s Guests (%s Adults, %s Children)', $total_pax, $adults, $children),
        sprintf('Pick-up Location: %s', $pickup),
        sprintf('Drop-off Location: %s', $dropoff),
        sprintf('Payment Method: %s', $method_label),
        sprintf('Status: %s', $booking_status),
        '',
        'Our team will review your schedule and contact you if any additional details are needed.',
    ];

    $message = implode("\n", $lines);
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    $sent = (bool) wp_mail($customer_email, $subject, $message, $headers);

    if ($sent) {
        update_post_meta($booking_post_id, 'booking_email_sent', '1');
        update_post_meta($booking_post_id, 'booking_email_sent_at', current_time('mysql'));
    } else {
        update_post_meta($booking_post_id, 'booking_email_sent', '0');
    }

    return $sent;
}

/**
 * Send internal booking notification email to fixed operations recipients.
 * Independent from customer confirmation email; uses separate idempotency meta.
 *
 * @param int $booking_post_id
 * @return bool True if wp_mail() succeeded, false otherwise.
 */
function rc_send_internal_booking_notification($booking_post_id)
{
    $booking_post_id = intval($booking_post_id);
    if ($booking_post_id <= 0) {
        return false;
    }

    $booking_post = function_exists('get_post') ? get_post($booking_post_id) : null;
    if (!$booking_post) {
        return false;
    }

    // Separate idempotency check for internal notifications
    if (get_post_meta($booking_post_id, 'booking_internal_notification_sent', true) === '1') {
        return true;
    }

    // Concurrency guard to prevent duplicate sends from simultaneous requests
    $existing_lock = intval(get_post_meta($booking_post_id, 'booking_internal_notification_lock', true));
    if ($existing_lock > 0 && (time() - $existing_lock) < 60) {
        return false;
    }
    update_post_meta($booking_post_id, 'booking_internal_notification_lock', (string) time());

    $recipients = [
        'vuongdevvn0509@gmail.com',
        'rentcardana@gmail.com',
    ];

    $booking_ref = get_the_title($booking_post_id) ?: ('#' . $booking_post_id);
    $tour_id = intval(get_post_meta($booking_post_id, 'tour_id', true));
    $tour_title = $tour_id ? get_the_title($tour_id) : '';
    $option_name = (string) get_post_meta($booking_post_id, 'option_name', true);
    $raw_date = (string) get_post_meta($booking_post_id, 'date', true);
    $formatted_date = $raw_date !== '' ? date('F j, Y', strtotime($raw_date)) : '';

    $customer_name = (string) get_post_meta($booking_post_id, 'customer_name', true);
    $customer_email = sanitize_email((string) get_post_meta($booking_post_id, 'customer_email', true));
    $customer_phone = (string) get_post_meta($booking_post_id, 'customer_phone', true);
    $customer_pickup = (string) get_post_meta($booking_post_id, 'customer_pickup', true);
    $customer_dropoff = (string) get_post_meta($booking_post_id, 'customer_dropoff', true);
    $customer_country = (string) get_post_meta($booking_post_id, 'customer_country', true);
    $customer_messages = (string) get_post_meta($booking_post_id, 'customer_messages', true);

    $adults = get_post_meta($booking_post_id, 'adults', true);
    $children = get_post_meta($booking_post_id, 'children', true);
    $total_pax = get_post_meta($booking_post_id, 'total_pax', true);

    $raw_total_price = get_post_meta($booking_post_id, 'total_price', true);
    $currency_symbol = '₫';
    if (function_exists('get_field')) {
        $configured_currency = get_field('currency', 'option');
        if (!empty($configured_currency)) {
            $currency_symbol = (string) $configured_currency;
        }
    }
    $formatted_price = ($raw_total_price !== '' && is_numeric($raw_total_price) && floatval($raw_total_price) > 0)
        ? number_format(floatval($raw_total_price), 0, '.', ',') . ' ' . $currency_symbol
        : 'Contact for Price';

    $payment_method = (string) get_post_meta($booking_post_id, 'payment_method', true);
    $payment_method_label = ($payment_method === 'paypal')
        ? 'PayPal'
        : (($payment_method === 'bank_transfer') ? 'Bank Transfer' : 'Pay on Arrival');

    $payment_status = (string) get_post_meta($booking_post_id, 'payment_status', true) ?: 'pending';
    $booking_status = (string) get_post_meta($booking_post_id, 'booking_status', true) ?: 'Pending Confirmation';

    $paypal_order_id = (string) get_post_meta($booking_post_id, 'paypal_order_id', true);
    $paypal_capture_id = (string) get_post_meta($booking_post_id, 'paypal_capture_id', true);
    $paypal_amount = (string) get_post_meta($booking_post_id, 'paypal_amount', true);
    $paypal_currency = (string) get_post_meta($booking_post_id, 'paypal_currency', true);

    $site_name = function_exists('get_bloginfo')
        ? wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES)
        : 'Rent Car Da Nang';
    if ($site_name === '') {
        $site_name = 'Rent Car Da Nang';
    }

    $subject = sprintf('New Booking Received – %s', $booking_ref);

    // Plain-text fallback
    $plain_lines = [
        sprintf('NEW BOOKING RECEIVED – %s', $booking_ref),
        str_repeat('=', 44),
        sprintf('Booking Number: %s', $booking_ref),
        sprintf('Booking Status: %s', $booking_status),
        sprintf('Payment Status: %s', strtoupper($payment_status)),
        sprintf('Payment Method: %s', $payment_method_label),
        '',
        'TOUR / SERVICE DETAILS',
        str_repeat('-', 24),
        sprintf('Tour Name: %s (ID: %d)', $tour_title ?: 'N/A', $tour_id),
    ];
    if ($option_name !== '') {
        $plain_lines[] = sprintf('Tour Option: %s', $option_name);
    }
    if ($formatted_date !== '') {
        $plain_lines[] = sprintf('Booking Date: %s', $formatted_date);
    }
    if ($total_pax !== '') {
        $plain_lines[] = sprintf('Guests: %s (%s Adults, %s Children)', $total_pax, $adults !== '' ? $adults : '0', $children !== '' ? $children : '0');
    }
    $plain_lines[] = sprintf('Total Price: %s', $formatted_price);
    if ($payment_method === 'paypal' && $paypal_amount !== '' && $paypal_currency !== '') {
        $plain_lines[] = sprintf('PayPal Charged: %s %s', $paypal_amount, $paypal_currency);
    }
    if ($paypal_capture_id !== '') {
        $plain_lines[] = sprintf('PayPal Capture ID: %s', $paypal_capture_id);
    }

    $plain_lines[] = '';
    $plain_lines[] = 'CUSTOMER DETAILS';
    $plain_lines[] = str_repeat('-', 24);
    $plain_lines[] = sprintf('Customer Name: %s', $customer_name ?: 'N/A');
    $plain_lines[] = sprintf('Customer Email: %s', $customer_email ?: 'N/A');
    $plain_lines[] = sprintf('Customer Phone: %s', $customer_phone ?: 'N/A');
    if ($customer_pickup !== '') {
        $plain_lines[] = sprintf('Pick-up Location: %s', $customer_pickup);
    }
    if ($customer_dropoff !== '') {
        $plain_lines[] = sprintf('Drop-off Location: %s', $customer_dropoff);
    }
    if ($customer_country !== '') {
        $plain_lines[] = sprintf('Country: %s', $customer_country);
    }
    if ($customer_messages !== '') {
        $plain_lines[] = sprintf('Special Requests: %s', $customer_messages);
    }
    $plain_text_body = implode("\n", $plain_lines);

    // Status badge styling
    $is_paid = (strtolower($payment_status) === 'paid');
    $status_bg = $is_paid ? '#dcfce7' : '#fef3c7';
    $status_color = $is_paid ? '#166534' : '#92400e';
    $admin_edit_url = function_exists('admin_url')
        ? admin_url('post.php?post=' . $booking_post_id . '&action=edit')
        : '';

    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo esc_html($subject); ?></title>
    </head>
    <body style="margin:0;padding:24px;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#1e293b;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px;margin:0 auto;background-color:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
            <tr>
                <td style="background-color:#0f4c81;padding:24px 28px;color:#ffffff;">
                    <div style="font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#fbbf24;margin-bottom:6px;">
                        <?php echo esc_html($site_name); ?> • Internal Booking Alert
                    </div>
                    <h1 style="margin:0;font-size:22px;font-weight:800;line-height:1.3;color:#ffffff;">
                        New Booking Received – <?php echo esc_html($booking_ref); ?>
                    </h1>
                </td>
            </tr>
            <tr>
                <td style="padding:20px 28px;background-color:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="vertical-align:middle;">
                                <span style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;">Booking Number</span>
                                <strong style="font-size:18px;color:#0f4c81;"><?php echo esc_html($booking_ref); ?></strong>
                            </td>
                            <td align="right" style="vertical-align:middle;">
                                <span style="display:inline-block;padding:6px 12px;border-radius:999px;font-size:12px;font-weight:700;text-transform:uppercase;background-color:<?php echo esc_attr($status_bg); ?>;color:<?php echo esc_attr($status_color); ?>;">
                                    <?php echo esc_html($booking_status); ?> (<?php echo esc_html(strtoupper($payment_status)); ?>)
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:24px 28px;">
                    <h2 style="margin:0 0 12px;font-size:15px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#0f4c81;">
                        Tour &amp; Booking Summary
                    </h2>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;margin-bottom:24px;">
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;width:36%;font-size:14px;">Tour / Service</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;">
                                <strong><?php echo esc_html($tour_title ?: 'N/A'); ?></strong>
                                <?php if ($tour_id > 0): ?>
                                    <span style="color:#64748b;font-size:12px;">(ID: #<?php echo esc_html((string) $tour_id); ?>)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($option_name !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Tour Option</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($option_name); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($formatted_date !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Booking Date</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($formatted_date); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($total_pax !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Number of Guests</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;">
                                <?php echo esc_html((string) $total_pax); ?> Guests
                                (<?php echo esc_html((string) ($adults !== '' ? $adults : 0)); ?> Adults, <?php echo esc_html((string) ($children !== '' ? $children : 0)); ?> Children)
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Total Price</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:15px;font-weight:700;color:#0f4c81;">
                                <?php echo esc_html($formatted_price); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Payment Method</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($payment_method_label); ?></td>
                        </tr>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Payment Status</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;font-weight:600;"><?php echo esc_html(strtoupper($payment_status)); ?></td>
                        </tr>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Booking Status</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;font-weight:600;"><?php echo esc_html($booking_status); ?></td>
                        </tr>
                        <?php if ($payment_method === 'paypal' && ($paypal_order_id !== '' || $paypal_capture_id !== '')): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">PayPal Reference</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:13px;">
                                <?php if ($paypal_order_id !== ''): ?>
                                    Order ID: <code><?php echo esc_html($paypal_order_id); ?></code><br>
                                <?php endif; ?>
                                <?php if ($paypal_capture_id !== ''): ?>
                                    Capture ID: <code><?php echo esc_html($paypal_capture_id); ?></code><br>
                                <?php endif; ?>
                                <?php if ($paypal_amount !== '' && $paypal_currency !== ''): ?>
                                    Charged: <strong><?php echo esc_html($paypal_amount . ' ' . $paypal_currency); ?></strong>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <h2 style="margin:0 0 12px;font-size:15px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#0f4c81;">
                        Customer Information
                    </h2>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;width:36%;font-size:14px;">Full Name</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($customer_name ?: 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Email Address</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;">
                                <?php if ($customer_email !== ''): ?>
                                    <a href="mailto:<?php echo esc_attr($customer_email); ?>" style="color:#0f4c81;text-decoration:none;"><?php echo esc_html($customer_email); ?></a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Phone / WhatsApp</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($customer_phone ?: 'N/A'); ?></td>
                        </tr>
                        <?php if ($customer_pickup !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Pick-up Location</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($customer_pickup); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($customer_dropoff !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Drop-off Location</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($customer_dropoff); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($customer_country !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Country</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo esc_html($customer_country); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($customer_messages !== ''): ?>
                        <tr>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;background-color:#f8fafc;font-weight:600;font-size:14px;">Special Requests</td>
                            <td style="padding:9px 12px;border:1px solid #e2e8f0;font-size:14px;"><?php echo nl2br(esc_html($customer_messages)); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <?php if ($admin_edit_url !== ''): ?>
                    <div style="margin-top:22px;text-align:center;">
                        <a href="<?php echo esc_url($admin_edit_url); ?>" style="display:inline-block;padding:10px 20px;background-color:#0f4c81;color:#ffffff;text-decoration:none;border-radius:6px;font-size:13px;font-weight:700;">
                            View Booking in WordPress Admin
                        </a>
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    $html_body = ob_get_clean();

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    if ($customer_email !== '' && is_email($customer_email)) {
        $clean_reply_name = str_replace(["\r", "\n", '"'], '', $customer_name ?: 'Customer');
        $headers[] = sprintf('Reply-To: "%s" <%s>', $clean_reply_name, $customer_email);
    }

    // Attach plain-text fallback via phpmailer_init for this send
    $alt_body_hook = static function ($phpmailer) use ($plain_text_body) {
        if (is_object($phpmailer)) {
            $phpmailer->AltBody = $plain_text_body;
        }
    };
    if (function_exists('add_action')) {
        add_action('phpmailer_init', $alt_body_hook);
    }

    $sent = (bool) wp_mail($recipients, $subject, $html_body, $headers);

    if (function_exists('remove_action')) {
        remove_action('phpmailer_init', $alt_body_hook);
    }
    if (function_exists('delete_post_meta')) {
        delete_post_meta($booking_post_id, 'booking_internal_notification_lock');
    } else {
        update_post_meta($booking_post_id, 'booking_internal_notification_lock', '0');
    }

    if ($sent) {
        update_post_meta($booking_post_id, 'booking_internal_notification_sent', '1');
        update_post_meta($booking_post_id, 'booking_internal_notification_sent_at', current_time('mysql'));
    } else {
        update_post_meta($booking_post_id, 'booking_internal_notification_sent', '0');
    }

    return $sent;
}

/**
 * AJAX: Create PayPal Order
 */
add_action('wp_ajax_rc_ajax_create_paypal_order', 'rc_ajax_create_paypal_order');
add_action('wp_ajax_nopriv_rc_ajax_create_paypal_order', 'rc_ajax_create_paypal_order');
function rc_ajax_create_paypal_order()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh the page and try again.']);
    }

    $token = isset($_POST['booking_token']) ? sanitize_text_field($_POST['booking_token']) : '';
    if (empty($token)) {
        wp_send_json_error(['message' => 'Invalid booking session.']);
    }

    $booking_data = get_transient('rc_booking_' . $token);
    if (!$booking_data) {
        wp_send_json_error(['message' => 'Your booking session has expired or is invalid.']);
    }

    $customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
    $customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
    $customer_phone = isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '';
    $customer_pickup = isset($_POST['customer_pickup']) ? sanitize_text_field($_POST['customer_pickup']) : '';
    $customer_dropoff = isset($_POST['customer_dropoff']) ? sanitize_text_field($_POST['customer_dropoff']) : '';
    $customer_country = isset($_POST['customer_country']) ? sanitize_text_field($_POST['customer_country']) : '';
    $customer_messages = isset($_POST['customer_messages']) ? sanitize_textarea_field($_POST['customer_messages']) : '';

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_pickup) || empty($customer_dropoff)) {
        wp_send_json_error(['message' => 'Please fill in all required customer details.']);
    }

    if (!is_email($customer_email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    $tour_id = $booking_data['tour_id'];
    $option_id = $booking_data['option_id'];
    $tour = get_post($tour_id);
    if (!$tour) {
        wp_send_json_error(['message' => 'The selected tour is no longer available.']);
    }

    $tour_options = get_field('tour_options', $tour_id);
    if (empty($tour_options) || !isset($tour_options[$option_id])) {
        wp_send_json_error(['message' => 'The selected tour option is no longer available.']);
    }

    $selected_option = $tour_options[$option_id];
    $adults = intval($booking_data['adults']);
    $children = intval($booking_data['children']);
    $total_pax = $adults + $children;

    // Calculate server-side total price
    $pricing = rc_calculate_tour_price($selected_option, $adults, $children, $tour_id);
    $total_price = $pricing['total_price'];

    if ($total_price <= 0) {
        wp_send_json_error(['message' => 'Online pricing is not available for this option. Please select another payment method or contact us.']);
    }

    // Convert amount for PayPal (handles VND -> USD if needed)
    $amount_data = rc_paypal_convert_amount($total_price);

    // Reuse or create booking post (idempotency for retries)
    $existing_post_id = get_transient('rc_booking_post_' . $token);
    if ($existing_post_id && get_post_type($existing_post_id) === 'tour_booking') {
        $booking_post_id = $existing_post_id;
        $booking_ref = get_the_title($booking_post_id);
    } else {
        $booking_ref = 'RC-' . strtoupper(substr(uniqid(), -6));
        $booking_post_id = wp_insert_post([
            'post_title' => $booking_ref,
            'post_status' => 'publish',
            'post_type' => post_type_exists('tour_booking') ? 'tour_booking' : 'post',
        ], false);

        if ($booking_post_id) {
            set_transient('rc_booking_post_' . $token, $booking_post_id, HOUR_IN_SECONDS);
        }
    }

    if (!$booking_post_id) {
        wp_send_json_error(['message' => 'Could not initialize booking. Please try again.']);
    }

    // Update booking metadata
    update_post_meta($booking_post_id, 'customer_name', $customer_name);
    update_post_meta($booking_post_id, 'customer_email', $customer_email);
    update_post_meta($booking_post_id, 'customer_phone', $customer_phone);
    update_post_meta($booking_post_id, 'customer_pickup', $customer_pickup);
    update_post_meta($booking_post_id, 'customer_dropoff', $customer_dropoff);
    update_post_meta($booking_post_id, 'customer_country', $customer_country);
    update_post_meta($booking_post_id, 'customer_messages', $customer_messages);
    update_post_meta($booking_post_id, 'payment_method', 'paypal');
    update_post_meta($booking_post_id, 'payment_status', 'pending');
    update_post_meta($booking_post_id, 'booking_status', 'Pending Payment');
    update_post_meta($booking_post_id, 'tour_id', $tour_id);
    update_post_meta($booking_post_id, 'booking_token', $token);
    update_post_meta($booking_post_id, 'option_name', $selected_option['name'] ?? '');
    update_post_meta($booking_post_id, 'date', $booking_data['date']);
    update_post_meta($booking_post_id, 'adults', $adults);
    update_post_meta($booking_post_id, 'children', $children);
    update_post_meta($booking_post_id, 'total_pax', $total_pax);
    update_post_meta($booking_post_id, 'total_price', $total_price);

    // Save PayPal reconciliation data
    update_post_meta($booking_post_id, 'paypal_amount', $amount_data['amount']);
    update_post_meta($booking_post_id, 'paypal_currency', $amount_data['currency']);
    update_post_meta($booking_post_id, 'paypal_exchange_rate', $amount_data['exchange_rate']);
    update_post_meta($booking_post_id, 'paypal_original_amount', $amount_data['original_amount']);
    update_post_meta($booking_post_id, 'paypal_original_currency', $amount_data['original_currency']);

    // Create PayPal order via REST API
    $description = $tour->post_title . ' (' . ($selected_option['name'] ?? '') . ')';
    $order = rc_paypal_create_order($booking_ref, $amount_data, $description, $booking_post_id);

    if (is_wp_error($order)) {
        update_post_meta($booking_post_id, 'payment_status', 'failed');
        wp_send_json_error([
            'message' => 'PayPal Error: ' . $order->get_error_message()
        ]);
    }

    $order_id = $order['id'] ?? '';
    if (empty($order_id)) {
        wp_send_json_error(['message' => 'Failed to retrieve PayPal Order ID.']);
    }

    update_post_meta($booking_post_id, 'paypal_order_id', $order_id);

    wp_send_json_success([
        'orderID' => $order_id,
        'booking_id' => $booking_post_id,
        'booking_ref' => $booking_ref,
        'amount' => $amount_data['amount'],
        'currency' => $amount_data['currency'],
    ]);
}

/**
 * AJAX: Capture & Verify PayPal Order
 */
add_action('wp_ajax_rc_ajax_capture_paypal_order', 'rc_ajax_capture_paypal_order');
add_action('wp_ajax_nopriv_rc_ajax_capture_paypal_order', 'rc_ajax_capture_paypal_order');
function rc_ajax_capture_paypal_order()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh the page and try again.']);
    }

    $order_id = isset($_POST['orderID']) ? sanitize_text_field($_POST['orderID']) : '';
    $token = isset($_POST['booking_token']) ? sanitize_text_field($_POST['booking_token']) : '';
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

    if (empty($order_id)) {
        wp_send_json_error(['message' => 'Missing PayPal Order ID.']);
    }

    // Identify booking post
    $booking_post = null;
    if ($booking_id > 0) {
        $booking_post = get_post($booking_id);
    }
    if (!$booking_post && !empty($token)) {
        $transient_id = get_transient('rc_booking_post_' . $token);
        if ($transient_id) {
            $booking_post = get_post($transient_id);
        }
    }

    // Fallback search by paypal_order_id
    if (!$booking_post) {
        $search = get_posts([
            'post_type' => post_type_exists('tour_booking') ? 'tour_booking' : 'post',
            'meta_key' => 'paypal_order_id',
            'meta_value' => $order_id,
            'posts_per_page' => 1,
            'post_status' => 'any',
        ]);
        if (!empty($search)) {
            $booking_post = $search[0];
        }
    }

    if (!$booking_post) {
        wp_send_json_error(['message' => 'Associated booking record not found.']);
    }

    $booking_post_id = $booking_post->ID;

    // Idempotency: If already paid, return success immediately (retry internal notification if previously unsent)
    $current_payment_status = get_post_meta($booking_post_id, 'payment_status', true);
    if ($current_payment_status === 'paid') {
        rc_send_internal_booking_notification($booking_post_id);
        $formatted_date = date('F j, Y', strtotime(get_post_meta($booking_post_id, 'date', true)));
        wp_send_json_success([
            'message' => 'Payment already verified.',
            'reference' => get_the_title($booking_post_id),
            'date' => $formatted_date,
            'payment_method' => 'PayPal',
            'status' => 'Confirmed',
            'country' => get_post_meta($booking_post_id, 'customer_country', true),
            'customer_email' => get_post_meta($booking_post_id, 'customer_email', true),
            'email_sent' => (get_post_meta($booking_post_id, 'booking_email_sent', true) === '1'),
        ]);
    }

    // Capture payment on PayPal server-side
    $capture = rc_paypal_capture_order($order_id);

    if (is_wp_error($capture)) {
        update_post_meta($booking_post_id, 'payment_status', 'failed');
        wp_send_json_error([
            'message' => 'Capture failed: ' . $capture->get_error_message()
        ]);
    }

    $capture_status = $capture['status'] ?? '';

    // Extract primary capture details
    $purchase_units = $capture['purchase_units'] ?? [];
    $payments = $purchase_units[0]['payments'] ?? [];
    $captures_list = $payments['captures'] ?? [];
    $primary_capture = $captures_list[0] ?? [];

    $specific_status = $primary_capture['status'] ?? $capture_status;
    $captured_amount = $primary_capture['amount']['value'] ?? '';
    $captured_currency = $primary_capture['amount']['currency_code'] ?? '';
    $capture_id = $primary_capture['id'] ?? '';

    if ($specific_status !== 'COMPLETED') {
        update_post_meta($booking_post_id, 'payment_status', 'failed');
        wp_send_json_error([
            'message' => 'Payment status is ' . esc_html($specific_status) . '. It was not completed.'
        ]);
    }

    // Verify amount and currency reconciliation
    $expected_amount = get_post_meta($booking_post_id, 'paypal_amount', true);
    $expected_currency = get_post_meta($booking_post_id, 'paypal_currency', true);

    if (!empty($expected_amount) && !empty($captured_amount)) {
        if (abs(floatval($captured_amount) - floatval($expected_amount)) > 0.05) {
            update_post_meta($booking_post_id, 'payment_status', 'flagged_amount_mismatch');
            wp_send_json_error([
                'message' => 'Payment amount verification failed. Please contact support.'
            ]);
        }
    }

    if (!empty($expected_currency) && !empty($captured_currency)) {
        if (strtoupper($captured_currency) !== strtoupper($expected_currency)) {
            update_post_meta($booking_post_id, 'payment_status', 'flagged_currency_mismatch');
            wp_send_json_error([
                'message' => 'Payment currency verification failed. Please contact support.'
            ]);
        }
    }

    // Payment successfully verified! Update records
    update_post_meta($booking_post_id, 'payment_status', 'paid');
    update_post_meta($booking_post_id, 'booking_status', 'Confirmed');
    update_post_meta($booking_post_id, 'paypal_capture_id', $capture_id);
    update_post_meta($booking_post_id, 'paypal_captured_at', current_time('mysql'));

    $payer = $capture['payer'] ?? [];
    if (!empty($payer['email_address'])) {
        update_post_meta($booking_post_id, 'paypal_payer_email', sanitize_email($payer['email_address']));
    }
    if (!empty($payer['payer_id'])) {
        update_post_meta($booking_post_id, 'paypal_payer_id', sanitize_text_field($payer['payer_id']));
    }

    $email_sent = rc_send_booking_confirmation_email($booking_post_id);
    rc_send_internal_booking_notification($booking_post_id);

    // Clean up session transients
    if (!empty($token)) {
        delete_transient('rc_booking_' . $token);
        delete_transient('rc_booking_post_' . $token);
    }

    $formatted_date = date('F j, Y', strtotime(get_post_meta($booking_post_id, 'date', true)));

    wp_send_json_success([
        'message' => 'Payment successful! Your booking is confirmed.',
        'reference' => get_the_title($booking_post_id),
        'date' => $formatted_date,
        'payment_method' => 'PayPal',
        'status' => 'Confirmed',
        'country' => get_post_meta($booking_post_id, 'customer_country', true),
        'customer_email' => get_post_meta($booking_post_id, 'customer_email', true),
        'email_sent' => $email_sent,
    ]);
}

/**
 * AJAX handler for filtering tours on Cars Template and elsewhere.
 * Single source of truth for tour price filtering and sorting.
 */
add_action('wp_ajax_rc_ajax_filter_tours', 'rc_ajax_filter_tours');
add_action('wp_ajax_nopriv_rc_ajax_filter_tours', 'rc_ajax_filter_tours');
function rc_ajax_filter_tours()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rc_filter_tours_nonce')) {
        wp_send_json_error(['message' => __('Security verification failed. Please refresh the page.', 'rc')]);
    }

    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    $sort = isset($_POST['sort']) ? sanitize_key($_POST['sort']) : 'recommended';

    // Parse location taxonomy terms
    $locations = [];
    if (isset($_POST['locations'])) {
        if (is_array($_POST['locations'])) {
            $locations = array_filter(array_map('intval', $_POST['locations']));
        } elseif (is_numeric($_POST['locations'])) {
            $locations = [intval($_POST['locations'])];
        }
    }

    // Parse numeric price boundaries safely
    $min_price = (isset($_POST['min_price']) && $_POST['min_price'] !== '' && is_numeric($_POST['min_price']))
        ? floatval($_POST['min_price'])
        : null;
    $max_price = (isset($_POST['max_price']) && $_POST['max_price'] !== '' && is_numeric($_POST['max_price']))
        ? floatval($_POST['max_price'])
        : null;

    if ($min_price !== null && $max_price !== null && $min_price > $max_price) {
        $temp = $min_price;
        $min_price = $max_price;
        $max_price = $temp;
    }

    $args = [
        'post_type'      => 'tours',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'paged'          => $paged,
    ];

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($locations)) {
        $args['tax_query'] = [
            [
                'taxonomy'         => 'tour_locations',
                'field'            => 'term_id',
                'terms'            => $locations,
                'operator'         => 'IN',
                'include_children' => true,
            ],
        ];
    }

    $meta_query = [];

    if ($min_price !== null && $max_price !== null) {
        $meta_query['price_clause'] = [
            'key'     => 'price_tour',
            'value'   => [$min_price, $max_price],
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        ];
    } elseif ($min_price !== null) {
        $meta_query['price_clause'] = [
            'key'     => 'price_tour',
            'value'   => $min_price,
            'type'    => 'NUMERIC',
            'compare' => '>=',
        ];
    } elseif ($max_price !== null) {
        $meta_query['price_clause'] = [
            'key'     => 'price_tour',
            'value'   => $max_price,
            'type'    => 'NUMERIC',
            'compare' => '<=',
        ];
    }

    // Apply sorting
    switch ($sort) {
        case 'price_low':
            if (!isset($meta_query['price_clause'])) {
                $meta_query['price_clause'] = [
                    'key'     => 'price_tour',
                    'compare' => 'EXISTS',
                    'type'    => 'NUMERIC',
                ];
            }
            $args['orderby'] = [
                'price_clause' => 'ASC',
                'date'         => 'DESC',
            ];
            break;

        case 'price_high':
            if (!isset($meta_query['price_clause'])) {
                $meta_query['price_clause'] = [
                    'key'     => 'price_tour',
                    'compare' => 'EXISTS',
                    'type'    => 'NUMERIC',
                ];
            }
            $args['orderby'] = [
                'price_clause' => 'DESC',
                'date'         => 'DESC',
            ];
            break;

        case 'newest':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;

        case 'oldest':
            $args['orderby'] = 'date';
            $args['order']   = 'ASC';
            break;

        case 'recommended':
        default:
            $args['orderby'] = [
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ];
            break;
    }

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            rc_item_tour(get_the_ID());
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    $pagination_html = function_exists('rc_get_ajax_pagination') 
        ? rc_get_ajax_pagination($paged, $query->max_num_pages) 
        : '';

    wp_send_json_success([
        'html'          => $html,
        'pagination'    => $pagination_html,
        'found_posts'   => $query->found_posts,
        'max_num_pages' => $query->max_num_pages,
        'current_page'  => $paged,
        'count_text'    => sprintf(
            _n('%d tour found', '%d tours found', $query->found_posts, 'rc'),
            $query->found_posts
        ),
    ]);
}

/**
 * AJAX handler for filtering blog posts
 */
add_action('wp_ajax_rc_ajax_filter_posts', 'rc_ajax_filter_posts');
add_action('wp_ajax_nopriv_rc_ajax_filter_posts', 'rc_ajax_filter_posts');
function rc_ajax_filter_posts()
{
    $nonce = $_POST['nonce'] ?? '';
    if (!wp_verify_nonce($nonce, 'rc_filter_posts_nonce') && !wp_verify_nonce($nonce, 'rc_filter_posts')) {
        wp_send_json_error(['message' => __('Security verification failed. Please refresh the page.', 'rc')]);
    }

    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($category) && $category !== 'all') {
        $field = is_numeric($category) ? 'term_id' : 'slug';
        $term_val = is_numeric($category) ? intval($category) : $category;
        $args['tax_query'] = [
            [
                'taxonomy'         => 'category',
                'field'            => $field,
                'terms'            => $term_val,
                'include_children' => true,
            ],
        ];
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            rc_post_item(get_the_ID());
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    $pagination_html = function_exists('rc_get_ajax_pagination') 
        ? rc_get_ajax_pagination($paged, $query->max_num_pages) 
        : '';

    wp_send_json_success([
        'html'          => $html,
        'pagination'    => $pagination_html,
        'found_posts'   => $query->found_posts,
        'max_num_pages' => $query->max_num_pages,
        'current_page'  => $paged,
        'count_text'    => sprintf(
            _n('%d article found', '%d articles found', $query->found_posts, 'rc'),
            $query->found_posts
        ),
    ]);
}