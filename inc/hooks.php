<?php

/**
 * Hooks.
 */

function imageTagForJs($response, $attachment)
{
	foreach ($response['sizes'] as $size => $datas) {
		$response['sizes'][$size]['tag'] = wp_get_attachment_image($attachment->ID, $size);
		$response['sizes'][$size]['srcset'] = wp_get_attachment_image_srcset($attachment->ID, $size);
	}
	return $response;
}
add_filter('wp_prepare_attachment_for_js', 'imageTagForJs', 10, 2);


/**
 * Allow upload json file
 */
add_filter('upload_mimes', function ($mime_types) {
	$mime_types['json'] = 'application/json'; // Adding .json extension
	$mime_types['svg'] = 'image/svg+xml';
	$mime_types['svgz'] = 'image/svg+xml';
	$mime_types['ttf'] = 'application/x-font-ttf';
	$mime_types['otf'] = 'application/x-font-opentype';
	$mime_types['woff'] = 'application/font-woff';
	$mime_types['woff2'] = 'application/font-woff2';
	return $mime_types;
}, 1);

/**
 * Header template
 * @return void
 */
add_action('rc_hook_header', 'rc_header_template');
function rc_header_template()
{
	load_template(get_template_directory() . '/template-parts/header.php', false);
}

/**
 * Footer template
 * @return void
 */
add_action('rc_hook_footer', 'rc_footer_template');
function rc_footer_template()
{
	load_template(get_template_directory() . '/template-parts/footer.php', false);
}

/**
 * Search template
 * @return void
 */
add_action('rc_hook_search', 'rc_search_template');
function rc_search_template()
{
	load_template(get_template_directory() . '/template-parts/modal-search.php', false);
}

/**
 * Book Car Modal template
 * @return void
 */
add_action('rc_hook_book_car', 'rc_book_car_modal_template');
function rc_book_car_modal_template()
{
	load_template(get_template_directory() . '/template-parts/modal-book-car.php', false);
}

/**
 * Back to top template
 * @return void
 */
add_action('rc_hook_back_to_top', 'rc_back_to_top_template');
function rc_back_to_top_template()
{
	load_template(get_template_directory() . '/template-parts/back-to-top.php', false);
}


/**
 * Post loop item template
 *
 * @param Int $post_id
 *
 * @return void
 */
add_action('rc_hook_post_loop_item', 'rc_post_loop_item_template', 20, 2);
function rc_post_loop_item_template($post_id, $index)
{
	set_query_var('post_id', $post_id);
	?>
	<article <?php post_class('col-md-4') ?>>
		<?php rc_post_item() ?>
	</article>
	<?php
}

/**
 * Force comments to be open for standard posts, tours, and cars.
 */
add_filter('comments_open', function ($open, $post_id) {
	if (in_array(get_post_type($post_id), ['post', 'tours', 'cars'])) {
		return true;
	}
	return $open;
}, 20, 2);

/**
 * Save review rating as comment meta after a comment is posted.
 */
add_action('comment_post', function ($comment_id, $comment_approved) {
	if (isset($_POST['rc_tour_rating'])) {
		$rating = intval($_POST['rc_tour_rating']);
		if ($rating >= 1 && $rating <= 5) {
			add_comment_meta($comment_id, 'rating', $rating, true);
		}
	}

	$categories = ['services', 'driver', 'experiences'];
	foreach ($categories as $cat) {
		$field = 'rc_tour_rating_' . $cat;
		if (isset($_POST[$field])) {
			$cat_rating = intval($_POST[$field]);
			if ($cat_rating >= 1 && $cat_rating <= 5) {
				add_comment_meta($comment_id, 'rating_' . $cat, $cat_rating, true);
			}
		}
	}
}, 10, 2);

/**
 * Validate that a rating is submitted for tour or car review comments.
 */
add_filter('preprocess_comment', function ($commentdata) {
	$post_id = isset($commentdata['comment_post_ID']) ? intval($commentdata['comment_post_ID']) : 0;
	if ($post_id && in_array(get_post_type($post_id), ['tours', 'cars'])) {

		// For tours, validate categories first and auto-calculate overall rating if missing
		if (get_post_type($post_id) === 'tours') {
			$categories = [
				'services' => __('Services', 'rc'),
				'driver' => __('Driver', 'rc'),
				'experiences' => __('Experiences', 'rc')
			];
			$sum = 0;
			foreach ($categories as $cat => $label) {
				$field = 'rc_tour_rating_' . $cat;
				if (empty($_POST[$field]) || intval($_POST[$field]) < 1) {
					wp_die(sprintf(__('Please select a star rating for %s before submitting your review.'), $label), __('Missing Rating'), ['back_link' => true, 'response' => 400]);
				}
				$sum += intval($_POST[$field]);
			}
			// Automatically calculate overall rating from categories if not provided directly
			if (empty($_POST['rc_tour_rating'])) {
				$_POST['rc_tour_rating'] = round($sum / count($categories));
			}
		}

		if (empty($_POST['rc_tour_rating']) || intval($_POST['rc_tour_rating']) < 1) {
			wp_die(__('Please select a star rating before submitting your review.'), __('Missing Rating'), ['back_link' => true, 'response' => 400]);
		}
	}
	return $commentdata;
});


add_filter('get_post_metadata', 'override_kk_star_ratings_meta', 10, 4);
function override_kk_star_ratings_meta($value, $object_id, $meta_key, $single)
{
	// Các meta keys mà KK Star Ratings sử dụng để lấy dữ liệu
	$kksr_keys = ['_kksr_casts', '_kksr_avg', '_kksr_ratings'];

	if (in_array($meta_key, $kksr_keys)) {

		// Bê nguyên logic tạo số ảo cố định theo ID bài viết của bạn vào đây
		$count = 500 + (abs(crc32((string) $object_id)) % 501);
		$rating_values = [4.8, 4.9, 5.0];
		$rating_index = abs(crc32('rating-' . $object_id)) % count($rating_values);
		$rating = $rating_values[$rating_index];

		$override_value = '';

		// Trả về dữ liệu tương ứng với từng meta key
		if ($meta_key === '_kksr_casts') {
			$override_value = $count;
		} elseif ($meta_key === '_kksr_avg') {
			$override_value = $rating;
		} elseif ($meta_key === '_kksr_ratings') {
			$override_value = $count * $rating;
		}

		// Đảm bảo trả về đúng format dữ liệu mà WordPress yêu cầu
		return $single ? $override_value : [$override_value];
	}

	// Quan trọng: Trả về null để WordPress tiếp tục lấy dữ liệu từ DB cho các post meta khác
	return null;
}



// add_action('init', 'block_vn_except_danang');
function block_vn_except_danang()
{
	// 1. Bỏ qua trang quản trị admin và các bot tìm kiếm (Google, Bing...)
	if (is_admin() || (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT']))) {
		return;
	}

	$visitor_ip = $_SERVER['REMOTE_ADDR'];

	// 2. Chặn thẳng IP Wi-Fi công ty (Thay bằng IP công ty của bạn)
	$company_ips = array('113.161.72.105');
	if (in_array($visitor_ip, $company_ips)) {
		wp_redirect('https://www.google.com', 302);
		exit;
	}

	// 3. Kiểm tra Cookie để không gọi API nhiều lần gây chậm web
	if (isset($_COOKIE['geo_verified']) && $_COOKIE['geo_verified'] === 'allowed') {
		return;
	}

	// 4. Tra cứu vị trí IP (chỉ chạy 1 lần đầu tiên khách vào web)
	$response = wp_remote_get("http://ip-api.com/json/{$visitor_ip}?fields=status,countryCode,city", array('timeout' => 2));
	if (!is_wp_error($response)) {
		$data = json_decode(wp_remote_retrieve_body($response), true);
		if (isset($data['status']) && $data['status'] === 'success') {
			// Nếu ở Việt Nam nhưng thành phố KHÔNG phải Đà Nẵng -> Chuyển hướng sang Google
			if ($data['countryCode'] === 'VN' && stripos($data['city'], 'Da Nang') === false) {
				wp_redirect('https://www.google.com', 302);
				exit;
			}
			// Lưu cookie hợp lệ trong 24 giờ
			setcookie('geo_verified', 'allowed', time() + 86400, '/');
		}
	}
}

/**
 * Get Ninja Forms phone-based email routing configuration.
 *
 * Scoped strictly to:
 * - Form ID 1: Contact Me
 * - Form ID 2: Book Car
 *
 * @return array<int, array{phone_field_id: int, phone_field: string, vn_recipient: string}>
 */
function rc_get_nf_phone_routing_config()
{
	return array(
		1 => array(
			'phone_field_id' => 17,
			'phone_field'    => 'your_phone_whatsapp_1794240866187',
			'vn_recipient'   => 'phaothu591997@gmail.com',
		),
		2 => array(
			'phone_field_id' => 14,
			'phone_field'    => 'whats_app_1794103710633',
			'vn_recipient'   => 'phaothu591997@gmail.com',
		),
	);
}

/**
 * Extract the submitted phone field value from Ninja Forms submission data.
 *
 * @param array $fields Submitted fields from $form_data['fields'].
 * @param array $form_config Routing configuration for the current form.
 * @return string Raw phone field value or empty string if not found.
 */
function rc_extract_nf_submission_phone($fields, $form_config)
{
	if (!is_array($fields)) {
		return '';
	}

	$target_key = isset($form_config['phone_field']) ? (string) $form_config['phone_field'] : '';
	$target_id = isset($form_config['phone_field_id']) ? intval($form_config['phone_field_id']) : 0;

	foreach ($fields as $field_id => $field) {
		if (!is_array($field)) {
			continue;
		}

		$current_key = isset($field['key']) ? (string) $field['key'] : '';
		$current_id = isset($field['id']) ? intval($field['id']) : intval($field_id);

		if (($target_key !== '' && $current_key === $target_key) || ($target_id > 0 && $current_id === $target_id)) {
			return isset($field['value']) && is_scalar($field['value']) ? (string) $field['value'] : '';
		}
	}

	return '';
}

/**
 * Route admin email notifications to the Vietnam-specific inbox when a Vietnamese
 * phone number is submitted on Ninja Forms ID 1 (Contact Me) or 2 (Book Car).
 *
 * Preserves customer confirmation emails, CC/BCC, subjects, bodies, and all other forms.
 *
 * @param array $actions    Ninja Forms submission actions.
 * @param array $form_cache Cached form definition.
 * @param array $form_data  Submitted form data.
 * @return array
 */
add_filter('ninja_forms_submission_actions', 'rc_route_ninja_forms_vn_email_notification', 10, 3);
function rc_route_ninja_forms_vn_email_notification($actions, $form_cache, $form_data)
{
	if (!is_array($actions)) {
		return $actions;
	}

	$form_id = 0;
	if (is_array($form_data) && isset($form_data['id'])) {
		$form_id = intval($form_data['id']);
	} elseif (is_array($form_cache) && isset($form_cache['id'])) {
		$form_id = intval($form_cache['id']);
	}

	$config = rc_get_nf_phone_routing_config();
	if (!isset($config[$form_id])) {
		return $actions;
	}

	$form_config = $config[$form_id];
	$submitted_fields = (is_array($form_data) && isset($form_data['fields'])) ? $form_data['fields'] : array();
	$phone_value = rc_extract_nf_submission_phone($submitted_fields, $form_config);

	if (!rc_is_vietnam_phone_number($phone_value)) {
		return $actions;
	}

	foreach ($actions as $index => $action) {
		if (!is_array($action) || !isset($action['settings']) || !is_array($action['settings'])) {
			continue;
		}

		$settings = $action['settings'];
		if (($settings['type'] ?? '') !== 'email' || empty($settings['active'])) {
			continue;
		}

		$to = isset($settings['to']) ? trim((string) $settings['to']) : '';
		$label = isset($settings['label']) ? (string) $settings['label'] : '';

		// Never alter customer confirmation emails (e.g. to={field:email} or Confirmation label)
		if (strpos($to, '{field:') !== false || stripos($label, 'confirmation') !== false) {
			continue;
		}

		$actions[$index]['settings']['to'] = $form_config['vn_recipient'];
	}

	return $actions;
}