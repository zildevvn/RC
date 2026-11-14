<?php

/**
 * Helpers
 */

function dump($data)
{
	print "<pre style=' background: rgba(0, 0, 0, 0.1); margin-bottom: 1.618em; padding: 1.618em; overflow: auto; max-width: 100%; '>==========================\n";
	if (is_array($data)) {
		print_r($data);
	} elseif (is_object($data)) {
		var_dump($data);
	} else {
		var_dump($data);
	}
	print "===========================</pre>";
}


if (!function_exists('rc_svg_icon')) {

	/**
	 * @param $icon
	 *
	 * @return mixed|string
	 */
	function rc_svg_icon($icon)
	{
		$icons = require(__DIR__ . '/svg.php');
		return isset($icons[$icon]) ? $icons[$icon] : '';
	}
}

if (!function_exists('rc_pagination')) {
	function rc_pagination($current_page = null, $total_pages = null, $query_args = [])
	{
		global $wp_query, $wp_rewrite;

		$paged = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
		$current_page = $current_page ? max(1, intval($current_page)) : max(1, intval($paged));
		$total_pages = $total_pages ? intval($total_pages) : $wp_query->max_num_pages;

		if ($total_pages < 2) {
			return;
		}

		$pagenum_link = html_entity_decode(get_pagenum_link());
		$url_parts = explode('?', $pagenum_link);
		$existing_args = [];
		if (isset($url_parts[1])) {
			wp_parse_str($url_parts[1], $existing_args);
		}

		$merged_args = array_merge($existing_args, $query_args);
		$pagenum_link = remove_query_arg(array_keys($existing_args), $pagenum_link);
		$pagenum_link = trailingslashit($pagenum_link) . '%_%';

		$format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

		$links = paginate_links([
			'base' => $pagenum_link,
			'format' => $format,
			'current' => $current_page,
			'total' => $total_pages,
			'type' => 'list',
			'prev_text' => rc_svg_icon('arrow_prev') ? rc_svg_icon('arrow_prev') : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
			'next_text' => rc_svg_icon('arrow_next') ? rc_svg_icon('arrow_next') : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
			'add_args' => $merged_args,
		]);

		if ($links) {
			echo '<nav class="rc-pagination" aria-label="Pagination">';
			echo $links;
			echo '</nav>';
		}
	}
}




if (!function_exists('rc_split_words_preserve_html')) {
	function rc_split_words_preserve_html($html)
	{
		if (empty($html))
			return '';
		// Match HTML tags, whitespace sequences, or words
		preg_match_all('/(<[^>]+>)|(\s+)|([^<>\s]+)/', $html, $matches);

		$word_count = 0;
		foreach ($matches[0] as $token) {
			if (!preg_match('/^<[^>]+>$/', $token) && !preg_match('/^\s+$/', $token)) {
				$word_count++;
			}
		}

		$result = '';
		$current_word = 0;
		foreach ($matches[0] as $token) {
			if (preg_match('/^<[^>]+>$/', $token) || preg_match('/^\s+$/', $token)) {
				$result .= $token;
			} else {
				// Calculate reverse index so right-most words animate first
				$reverse_index = $word_count - $current_word - 1;
				$result .= '<span class="split-word" style="--word-index: ' . $reverse_index . ';">' . $token . '</span>';
				$current_word++;
			}
		}
		return $result;
	}
}

if (!function_exists('rc_load_button')) {
	function rc_load_button($btn_url, $btn_text, $btn_target = '_blank', $aria_label = '')
	{ ?>
		<a href="<?= $btn_url ?>" class="rc-button d-flex align-items-center justify-content-center" target="<?= $btn_target ?>"
			role="button" rel="noopener noreferrer" aria-label="<?= $aria_label ?>">
			<?= $btn_text ?>
			<?= rc_svg_icon('arrow_btn'); ?>
		</a>
	<?php }
}

if (!function_exists('vm_load_button')) {
	function vm_load_button($btn_url, $btn_text, $btn_target = '_blank', $aria_label = '')
	{
		rc_load_button($btn_url, $btn_text, $btn_target, $aria_label);
	}
}

// Helper function to safely extract image data
if (!function_exists('rc_extract_gallery_image_data')) {
	function rc_extract_gallery_image_data($image)
	{
		$data = [
			'url' => '',
			'thumb' => '',
			'alt' => get_the_title() . ' - Gallery',
			'title' => ''
		];

		if (is_array($image)) {
			$data['url'] = !empty($image['url']) ? $image['url'] : '';
			$data['thumb'] = isset($image['sizes']['large']) ? $image['sizes']['large'] : $data['url'];
			if (!empty($image['alt'])) {
				$data['alt'] = $image['alt'];
			} elseif (!empty($image['title'])) {
				$data['alt'] = $image['title'];
			}
			$data['title'] = !empty($image['title']) ? $image['title'] : '';
		} elseif (is_numeric($image)) {
			$data['url'] = wp_get_attachment_url($image);
			$img_src_large = wp_get_attachment_image_src($image, 'large');
			$data['thumb'] = $img_src_large ? $img_src_large[0] : $data['url'];
			$alt = get_post_meta($image, '_wp_attachment_image_alt', true);
			if (!empty($alt)) {
				$data['alt'] = $alt;
			} else {
				$data['alt'] = get_the_title($image);
			}
			$data['title'] = get_the_title($image);
		} elseif (is_string($image)) {
			$data['url'] = $image;
			$data['thumb'] = $image;
		}

		return $data;
	}
}

if (!function_exists('rc_calculate_tour_price')) {
	/**
	 * Calculate tour price based on option and pax
	 * 
	 * @param array $selected_option
	 * @param int $total_pax
	 * @return array
	 */
	function rc_calculate_tour_price($selected_option, $adults, $children = 0, $post_id = 0)
	{
		$adults = intval($adults);
		$children = intval($children);
		$total_pax = $adults + $children;
		if ($total_pax < 1) {
			$adults = 1;
			$children = 0;
			$total_pax = 1;
		}

		// Fixed Price for Book Car option (takes priority if price field exists)
		if (isset($selected_option['price']) && $selected_option['price'] !== '') {
			$raw_price = is_array($selected_option['price']) && isset($selected_option['price']['price']) ? $selected_option['price']['price'] : $selected_option['price'];
			$fixed_price = floatval(str_replace(['₫', '$', ',', ' '], '', $raw_price));
			if ($fixed_price > 0) {
				return [
					'price_per_person' => $fixed_price,
					'child_price' => 0,
					'total_price' => $fixed_price,
					'is_price_available' => true,
					'is_car_tour' => true,
					'is_private_tour' => false
				];
			}
		}

		$private_tour = $selected_option['private_tour'] ?? false;
		$price_group = $selected_option['price_group'] ?? 0;
		$price_group_child = $selected_option['child_price_group'] ?? $selected_option['child_price_group'] ?? null;
		$price_private = $selected_option['price_private'] ?? [];

		$adult_price = 0.0;
		$child_price = 0.0;

		if (empty($private_tour)) {
			$group_price_val = is_array($price_group) && isset($price_group['price']) ? $price_group['price'] : (is_scalar($price_group) ? $price_group : 0);
			$adult_price = floatval(str_replace(['₫', '$', ',', ' '], '', $group_price_val));

			if (isset($price_group_child) && $price_group_child !== '') {
				$group_child_price_val = is_array($price_group_child) && isset($price_group_child['price']) ? $price_group_child['price'] : (is_scalar($price_group_child) ? $price_group_child : 0);
				// Handle if it's an array with an empty price string
				if (is_array($price_group_child) && isset($price_group_child['price']) && $price_group_child['price'] === '') {
					$child_price = $adult_price;
				} else {
					$child_price = floatval(str_replace(['₫', '$', ',', ' '], '', $group_child_price_val));
				}
			} else {
				$child_price = $adult_price;
			}
		} else {
			$private_price_val = 0;
			if (is_array($price_private) && !empty($price_private)) {
				$available_pax = [];
				foreach ($price_private as $p_key => $p_val) {
					if (is_array($p_val)) {
						$num = isset($p_val['pax']) ? intval($p_val['pax']) :
							(isset($p_val['guests']) ? intval($p_val['guests']) : 0);
						$price = isset($p_val['price']) ? $p_val['price'] : '';
						if ($num > 0 && $price !== '') {
							$available_pax[$num] = $price;
						}
					} else {
						$num = intval($p_key);
						if ($num > 0 && $p_val !== '') {
							$available_pax[$num] = $p_val;
						}
					}
				}
				if ($total_pax > 12 && !empty($price_private['no_limited'])) {
					$private_price_val = $price_private['no_limited'];
				} else if (!empty($available_pax)) {
					ksort($available_pax);
					$found_price = null;
					$max_pax_price = 0;
					foreach ($available_pax as $p_num => $p_val) {
						$max_pax_price = $p_val;
						if ($p_num >= $total_pax && $found_price === null) {
							$found_price = $p_val;
						}
					}
					$private_price_val = $found_price ?? $max_pax_price;
				}
			}
			$adult_price = floatval(str_replace(['₫', '$', ',', ' '], '', $private_price_val));
			$child_price = $adult_price * 0.75;
		}

		$is_price_available = ($adult_price !== 0.0);

		$is_car_tour = false;
		if ($post_id > 0 && has_term('Car Tours', 'tour_cats', $post_id)) {
			$is_car_tour = true;
		}

		if ($is_car_tour) {
			$total_price = $adult_price;
			$child_price = 0;
		} else {
			$total_price = ($adult_price * $adults) + ($child_price * $children);
		}

		return [
			'price_per_person' => $adult_price,
			'child_price' => $child_price,
			'total_price' => $total_price,
			'is_price_available' => $is_price_available,
			'is_car_tour' => $is_car_tour,
			'is_private_tour' => $private_tour
		];
	}
}

if (!function_exists('rc_track_post_views')) {
	function rc_track_post_views()
	{
		if (!is_singular()) {
			return;
		}

		$post_id = get_queried_object_id();

		if (!$post_id) {
			return;
		}

		$count_key = 'rc_post_views_count';
		$count = (int) get_post_meta($post_id, $count_key, true);

		$count++;

		update_post_meta($post_id, $count_key, $count);
	}

	add_action('wp', 'rc_track_post_views');
}

if (!function_exists('rc_get_post_views')) {
	function rc_get_post_views($post_id)
	{
		$count_key = 'rc_post_views_count';
		$count = get_post_meta($post_id, $count_key, true);

		if ($count === '') {
			return 0;
		}

		return (int) $count;
	}
}

add_action('init', function () {
	global $currency;
	if (!isset($currency) && function_exists('get_field')) {
		$currency = get_field('currency', 'option');
	}
});

if (!function_exists('rc_format_price')) {
	/**
	 * Format a price value consistently across the theme.
	 *
	 * @param mixed $price Raw price value.
	 * @return string Formatted price string, or empty string if invalid/empty.
	 */
	function rc_format_price($price)
	{
		if ($price === null || $price === '' || $price === false) {
			return '';
		}

		if (is_string($price)) {
			$price = str_replace([',', ' '], '', trim($price));
		}

		if (!is_numeric($price)) {
			return '';
		}

		$numeric_price = (float) $price;
		$decimals = (floor($numeric_price) != $numeric_price) ? 2 : 0;

		return number_format($numeric_price, $decimals, '.', ',');
	}
}

if (!function_exists('rc_whatsapp_url')) {
	/**
	 * Generate a standardized WhatsApp chat URL from a phone number or ACF 'phone' option.
	 *
	 * @param string|null $phone Optional phone number string. If null, retrieves from ACF 'phone' option.
	 * @return string Valid WhatsApp URL (e.g. 'https://wa.me/84820334533') or empty string if invalid/empty.
	 */
	function rc_whatsapp_url($phone = null)
	{
		if ($phone === null && function_exists('get_field')) {
			$phone = get_field('phone', 'option');
		}

		if (empty($phone) || !is_string($phone)) {
			return '';
		}

		// Strip all non-digit characters (+, spaces, hyphens, parentheses, etc.)
		$digits = preg_replace('/\D+/', '', $phone);
		if (empty($digits)) {
			return '';
		}

		// Normalize domestic numbers (leading 0) to Vietnamese country code (84)
		if (strpos($digits, '0') === 0) {
			$digits = '84' . substr($digits, 1);
		}

		return 'https://wa.me/' . $digits;
	}
}

if (!function_exists('rc_get_ajax_pagination')) {
	/**
	 * Generate accessible AJAX pagination HTML.
	 *
	 * @param int $current_page Current active page number.
	 * @param int $total_pages Total number of pages.
	 * @return string HTML pagination markup.
	 */
	function rc_get_ajax_pagination($current_page = 1, $total_pages = 1)
	{
		$current_page = max(1, intval($current_page));
		$total_pages = intval($total_pages);

		if ($total_pages <= 1) {
			return '';
		}

		$output = '<nav class="rc-pagination rc-ajax-pagination" aria-label="' . esc_attr__('Tours Pagination', 'rc') . '">';
		$output .= '<ul class="page-numbers">';

		// Previous page button
		if ($current_page > 1) {
			$prev_page = $current_page - 1;
			$prev_icon = function_exists('rc_svg_icon') && rc_svg_icon('arrow_prev') ? rc_svg_icon('arrow_prev') : '&laquo;';
			$output .= '<li><a href="#" class="prev page-numbers" data-page="' . esc_attr($prev_page) . '" aria-label="' . esc_attr__('Previous page', 'rc') . '">' . $prev_icon . '</a></li>';
		}

		// Page numbers calculation with sliding window
		$start_page = max(1, $current_page - 2);
		$end_page = min($total_pages, $current_page + 2);

		if ($start_page > 1) {
			$output .= '<li><a href="#" class="page-numbers" data-page="1">1</a></li>';
			if ($start_page > 2) {
				$output .= '<li><span class="page-numbers dots">&hellip;</span></li>';
			}
		}

		for ($i = $start_page; $i <= $end_page; $i++) {
			if ($i === $current_page) {
				$output .= '<li><span aria-current="page" class="page-numbers current">' . esc_html($i) . '</span></li>';
			} else {
				$output .= '<li><a href="#" class="page-numbers" data-page="' . esc_attr($i) . '">' . esc_html($i) . '</a></li>';
			}
		}

		if ($end_page < $total_pages) {
			if ($end_page < $total_pages - 1) {
				$output .= '<li><span class="page-numbers dots">&hellip;</span></li>';
			}
			$output .= '<li><a href="#" class="page-numbers" data-page="' . esc_attr($total_pages) . '">' . esc_html($total_pages) . '</a></li>';
		}

		// Next page button
		if ($current_page < $total_pages) {
			$next_page = $current_page + 1;
			$next_icon = function_exists('rc_svg_icon') && rc_svg_icon('arrow_next') ? rc_svg_icon('arrow_next') : '&raquo;';
			$output .= '<li><a href="#" class="next page-numbers" data-page="' . esc_attr($next_page) . '" aria-label="' . esc_attr__('Next page', 'rc') . '">' . $next_icon . '</a></li>';
		}

		$output .= '</ul>';
		$output .= '</nav>';

		return $output;
	}
}
