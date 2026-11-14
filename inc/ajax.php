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

    if ($booking_post_id) {
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
    }

    $payment_method_label = ($payment_method === 'bank_transfer') ? 'Bank Transfer' : 'Pay on Arrival';
    $formatted_date = date('F j, Y', strtotime($booking_data['date']));

    delete_transient('rc_booking_' . $token);
    delete_transient('rc_booking_post_' . $token);

    wp_send_json_success([
        'message' => 'Booking successful.',
        'reference' => $booking_ref,
        'date' => $formatted_date,
        'payment_method' => $payment_method_label,
        'status' => 'Pending Confirmation',
        'country' => $customer_country
    ]);
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

    // Idempotency: If already paid, return success immediately
    $current_payment_status = get_post_meta($booking_post_id, 'payment_status', true);
    if ($current_payment_status === 'paid') {
        $formatted_date = date('F j, Y', strtotime(get_post_meta($booking_post_id, 'date', true)));
        wp_send_json_success([
            'message' => 'Payment already verified.',
            'reference' => get_the_title($booking_post_id),
            'date' => $formatted_date,
            'payment_method' => 'PayPal',
            'status' => 'Confirmed',
            'country' => get_post_meta($booking_post_id, 'customer_country', true),
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