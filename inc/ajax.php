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
    $count = !empty($tour_options) && is_array($tour_options) ? count($tour_options) : 0;

    ob_start();
    get_template_part('template-parts/single-tour/tour-options', null, ['post_id' => $post_id]);
    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success([
        'count' => $count,
        'html'  => $html,
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

    $booking_ref = 'RC-' . strtoupper(substr(uniqid(), -6));

    $booking_post_id = wp_insert_post([
        'post_title' => $booking_ref,
        'post_status' => 'publish',
        'post_type' => post_type_exists('tour_booking') ? 'tour_booking' : 'post',
    ], false);

    if ($booking_post_id) {
        update_post_meta($booking_post_id, 'customer_name', $customer_name);
        update_post_meta($booking_post_id, 'customer_email', $customer_email);
        update_post_meta($booking_post_id, 'customer_phone', $customer_phone);
        update_post_meta($booking_post_id, 'customer_pickup', $customer_pickup);
        update_post_meta($booking_post_id, 'customer_dropoff', $customer_dropoff);
        update_post_meta($booking_post_id, 'customer_country', $customer_country);
        update_post_meta($booking_post_id, 'customer_messages', $customer_messages);
        update_post_meta($booking_post_id, 'payment_method', $payment_method);
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

    wp_send_json_success([
        'message' => 'Booking successful.',
        'reference' => $booking_ref,
        'date' => $formatted_date,
        'payment_method' => $payment_method_label,
        'status' => 'Pending Confirmation',
        'country' => $customer_country
    ]);
}