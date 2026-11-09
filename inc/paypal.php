<?php
/**
 * PayPal REST API Integration Helper
 * 
 * Manages PayPal authentication, order creation, capture, and verification.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Determine if PayPal Sandbox mode is active.
 * Priority: RC_PAYPAL_SANDBOX constant > ACF option 'paypal_sandbox' > default true
 * 
 * @return bool
 */
function rc_paypal_is_sandbox()
{
    if (defined('RC_PAYPAL_SANDBOX')) {
        return (bool) RC_PAYPAL_SANDBOX;
    }

    if (function_exists('get_field')) {
        $sandbox_option = get_field('paypal_sandbox', 'option');
        if ($sandbox_option !== null && $sandbox_option !== '') {
            return (bool) $sandbox_option;
        }
    }

    return true; // Default to sandbox for safety
}

/**
 * Get PayPal Client ID.
 * Priority: RC_PAYPAL_CLIENT_ID constant > ACF option 'paypal_client_id'
 * 
 * @return string
 */
function rc_paypal_get_client_id()
{
    if (defined('RC_PAYPAL_CLIENT_ID') && !empty(RC_PAYPAL_CLIENT_ID)) {
        return trim(RC_PAYPAL_CLIENT_ID);
    }

    if (function_exists('get_field')) {
        $client_id = get_field('paypal_client_id', 'option');
        if (!empty($client_id)) {
            return trim($client_id);
        }
    }

    return '';
}

/**
 * Get PayPal Client Secret.
 * Never expose this to frontend / JavaScript.
 * Priority: RC_PAYPAL_CLIENT_SECRET constant > ACF option 'paypal_client_secret'
 * 
 * @return string
 */
function rc_paypal_get_client_secret()
{
    if (defined('RC_PAYPAL_CLIENT_SECRET') && !empty(RC_PAYPAL_CLIENT_SECRET)) {
        return trim(RC_PAYPAL_CLIENT_SECRET);
    }

    if (function_exists('get_field')) {
        $client_secret = get_field('paypal_client_secret', 'option');
        if (!empty($client_secret)) {
            return trim($client_secret);
        }
    }

    return '';
}

/**
 * Get Exchange Rate for converting VND to USD.
 * Priority: RC_PAYPAL_EXCHANGE_RATE constant > ACF option 'paypal_exchange_rate' > default 25400
 * 
 * @return float
 */
function rc_paypal_get_exchange_rate()
{
    if (defined('RC_PAYPAL_EXCHANGE_RATE') && floatval(RC_PAYPAL_EXCHANGE_RATE) > 0) {
        return floatval(RC_PAYPAL_EXCHANGE_RATE);
    }

    if (function_exists('get_field')) {
        $rate = get_field('paypal_exchange_rate', 'option');
        if (!empty($rate) && floatval($rate) > 0) {
            return floatval($rate);
        }
    }

    return 25400.0;
}

/**
 * Check if PayPal has valid credentials configured.
 * 
 * @return bool
 */
function rc_paypal_is_configured()
{
    $client_id = rc_paypal_get_client_id();
    $client_secret = rc_paypal_get_client_secret();

    return (!empty($client_id) && !empty($client_secret) && $client_id !== 'sb');
}

/**
 * Get base URL for PayPal REST API.
 * 
 * @return string
 */
function rc_paypal_get_api_base_url()
{
    return rc_paypal_is_sandbox()
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';
}

/**
 * Convert site amount to PayPal-supported currency & amount.
 * PayPal REST API does not support VND directly.
 * 
 * @param float|int $amount Raw total amount in site currency
 * @return array
 */
function rc_paypal_convert_amount($amount)
{
    $site_currency = '₫';
    if (function_exists('get_field')) {
        $opt_currency = get_field('currency', 'option');
        if (!empty($opt_currency)) {
            $site_currency = $opt_currency;
        }
    }

    $num_amount = floatval($amount);
    if ($num_amount < 0) {
        $num_amount = 0;
    }

    if ($site_currency === '$' || strtoupper($site_currency) === 'USD') {
        return [
            'amount' => number_format($num_amount, 2, '.', ''),
            'currency' => 'USD',
            'is_converted' => false,
            'original_amount' => $num_amount,
            'original_currency' => 'USD',
            'exchange_rate' => 1.0,
        ];
    }

    if ($site_currency === '€' || strtoupper($site_currency) === 'EUR') {
        return [
            'amount' => number_format($num_amount, 2, '.', ''),
            'currency' => 'EUR',
            'is_converted' => false,
            'original_amount' => $num_amount,
            'original_currency' => 'EUR',
            'exchange_rate' => 1.0,
        ];
    }

    // Default: VND -> USD conversion
    $rate = rc_paypal_get_exchange_rate();
    $converted_usd = round($num_amount / $rate, 2);
    if ($converted_usd <= 0 && $num_amount > 0) {
        $converted_usd = 0.01;
    }

    return [
        'amount' => number_format($converted_usd, 2, '.', ''),
        'currency' => 'USD',
        'is_converted' => true,
        'original_amount' => $num_amount,
        'original_currency' => 'VND',
        'exchange_rate' => $rate,
    ];
}

/**
 * Obtain an OAuth2 Access Token from PayPal REST API.
 * Uses transient caching to minimize roundtrips.
 * 
 * @return string|WP_Error Access token string or WP_Error on failure.
 */
function rc_paypal_get_access_token()
{
    $client_id = rc_paypal_get_client_id();
    $client_secret = rc_paypal_get_client_secret();

    if (empty($client_id) || empty($client_secret)) {
        return new WP_Error('paypal_missing_credentials', 'PayPal credentials are not configured.');
    }

    $cache_key = 'rc_paypal_token_' . md5($client_id . (rc_paypal_is_sandbox() ? '1' : '0'));
    $cached_token = get_transient($cache_key);
    if (!empty($cached_token)) {
        return $cached_token;
    }

    $url = rc_paypal_get_api_base_url() . '/v1/oauth2/token';
    $auth = base64_encode($client_id . ':' . $client_secret);

    $response = wp_remote_post($url, [
        'timeout' => 20,
        'headers' => [
            'Authorization' => 'Basic ' . $auth,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'Accept-Language' => 'en_US',
        ],
        'body' => 'grant_type=client_credentials',
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code !== 200 || empty($body['access_token'])) {
        $error_desc = isset($body['error_description']) ? $body['error_description'] : 'Failed to authenticate with PayPal.';
        return new WP_Error('paypal_auth_failed', $error_desc, $body);
    }

    $access_token = $body['access_token'];
    $expires_in = isset($body['expires_in']) ? intval($body['expires_in']) : 3600;
    // Cache slightly less than expires_in to be safe
    set_transient($cache_key, $access_token, max(60, $expires_in - 300));

    return $access_token;
}

/**
 * Create a PayPal Order (REST API v2).
 * 
 * @param string $reference Booking reference (e.g. RC-ABC123)
 * @param array $amount_data Result of rc_paypal_convert_amount()
 * @param string $description Order description
 * @param int $booking_id WordPress Post ID of tour_booking
 * @return array|WP_Error PayPal order data array or WP_Error.
 */
function rc_paypal_create_order($reference, $amount_data, $description = '', $booking_id = 0)
{
    $token = rc_paypal_get_access_token();
    if (is_wp_error($token)) {
        return $token;
    }

    $url = rc_paypal_get_api_base_url() . '/v2/checkout/orders';

    $payload = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'reference_id' => sanitize_text_field($reference),
                'description' => mb_substr(sanitize_text_field($description), 0, 127),
                'custom_id' => strval($booking_id),
                'amount' => [
                    'currency_code' => $amount_data['currency'],
                    'value' => $amount_data['amount'],
                ],
            ],
        ],
        'application_context' => [
            'brand_name' => get_bloginfo('name'),
            'landing_page' => 'NO_PREFERENCE',
            'user_action' => 'PAY_NOW',
        ],
    ];

    $response = wp_remote_post($url, [
        'timeout' => 25,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => 'order_' . $reference . '_' . md5($reference . $amount_data['amount']),
        ],
        'body' => wp_json_encode($payload),
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code !== 201 && $code !== 200) {
        $message = 'PayPal order creation failed.';
        if (!empty($body['details']) && is_array($body['details'])) {
            $message = $body['details'][0]['description'] ?? $body['message'] ?? $message;
        } elseif (!empty($body['message'])) {
            $message = $body['message'];
        }
        return new WP_Error('paypal_create_order_failed', $message, $body);
    }

    return $body;
}

/**
 * Capture a PayPal Order (REST API v2).
 * 
 * @param string $order_id PayPal Order ID
 * @return array|WP_Error Captured order data or WP_Error.
 */
function rc_paypal_capture_order($order_id)
{
    $token = rc_paypal_get_access_token();
    if (is_wp_error($token)) {
        return $token;
    }

    $order_id = sanitize_text_field($order_id);
    $url = rc_paypal_get_api_base_url() . '/v2/checkout/orders/' . $order_id . '/capture';

    $response = wp_remote_post($url, [
        'timeout' => 30,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => 'capture_' . $order_id,
        ],
        'body' => '{}',
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code !== 201 && $code !== 200) {
        $message = 'PayPal payment capture failed.';
        if (!empty($body['details']) && is_array($body['details'])) {
            $message = $body['details'][0]['description'] ?? $body['message'] ?? $message;
        } elseif (!empty($body['message'])) {
            $message = $body['message'];
        }
        return new WP_Error('paypal_capture_failed', $message, $body);
    }

    return $body;
}
