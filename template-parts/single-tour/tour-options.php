<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$current_post_id = get_the_ID();
if (!$current_post_id && !empty($args['post_id'])) {
    $current_post_id = intval($args['post_id']);
} elseif (!$current_post_id && isset($_POST['post_id'])) {
    $current_post_id = intval($_POST['post_id']);
}

// Get guest counts (Adults + Children)
$adults = isset($args['adults']) ? intval($args['adults']) : (isset($_POST['adults']) ? intval($_POST['adults']) : 2);
$children = isset($args['children']) ? intval($args['children']) : (isset($_POST['children']) ? intval($_POST['children']) : 0);
$total_pax = isset($args['total_pax']) ? intval($args['total_pax']) : ($adults + $children);

// If filtered_options passed from ajax, use it; otherwise filter all options by total guests
$tour_options = [];
if (isset($args['filtered_options']) && is_array($args['filtered_options'])) {
    $tour_options = $args['filtered_options'];
} else {
    $all_options = $current_post_id ? get_field('tour_options', $current_post_id) : get_field('tour_options');
    if (!empty($all_options) && is_array($all_options)) {
        foreach ($all_options as $key => $option) {
            $type = $option['type'] ?? '';
            $capacity = intval($type);

            if ($capacity <= 0 && !empty($option['pax'])) {
                $capacity = intval(preg_replace('/\D/', '', $option['pax']));
            }
            if ($capacity <= 0 && !empty($option['maximum'])) {
                $capacity = intval(preg_replace('/\D/', '', $option['maximum']));
            }

            // Filter: Capacity >= Total Guests
            if ($capacity <= 0 || $total_pax <= 0 || $capacity >= $total_pax) {
                $tour_options[$key] = $option;
            }
        }
    }
}
?>

<?php if (empty($tour_options)): ?>
    <section class="rc-section rc-tour-options vm-tour-options rc-tour-options--empty">
        <div class="container">
            <div class="rc-tour-options-empty vm-tour-options-empty">
                <div class="empty-icon">
                    <?php echo rc_svg_icon('alert_circle'); ?>
                </div>
                <h3>Không có option phù hợp</h3>
                <p>
                    <?php if ($total_pax > 0): ?>
                        Không tìm thấy phương tiện hoặc gói dịch vụ phù hợp cho <strong><?= esc_html($total_pax) ?> khách</strong>. Vui lòng giảm số lượng khách hoặc liên hệ trực tiếp với chúng tôi để được hỗ trợ.
                    <?php else: ?>
                        Không có tour option nào phù hợp. Vui lòng thử lại với ngày hoặc số lượng khách khác.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </section>
<?php else: ?>
    <?php
    $first_key = array_key_first($tour_options);
    $options_count = count($tour_options);
    ?>
    <section class="rc-section rc-tour-options vm-tour-options">
        <div class="container">
            <h2 class="rc-heading rc-tour-options__title">
                <?php
                if ($options_count === 1) {
                    esc_html_e('Choose from 1 available option', 'rc');
                } else {
                    printf(esc_html__('Choose from %d available options', 'rc'), $options_count);
                }
                ?>
            </h2>

            <div class="rc-tour-options__grid vm-tour-options__grid">
                <?php foreach ($tour_options as $key => $option): ?>
                    <?php
                    $name = $option['name'] ?? '';
                    $starting_time = $option['starting_time'] ?? '';
                    $pax = $option['pax'] ?? ($option['maximum'] ?? '');
                    $description = isset($option['description']) ? $option['description'] : '';
                    $is_first = ($key === $first_key);
                    $is_selected_class = $is_first ? 'is-selected' : '';
                    $type = $option['type'] ?? '';
                    // 4 : 4 seat
                    // 7 : 7 seat
                    // 16 : 16 seat
            
                    // Price logic for Book Car: fixed single price from $option['price']
                    $raw_price = $option['price'] ?? '';
                    $formatted_price = rc_format_price($raw_price);
                    $is_price_available = ($formatted_price !== '');
                    ?>
                    <div class="option-item <?= $is_selected_class ?>" data-key="<?= esc_attr($key) ?>">
                        <!-- Header -->
                        <div class="option-item__header">
                            <h3 class="h5"> <?= esc_html($name) ?> </h3>
                            <div class="option-item__radio">
                                <div class="option-item__radio-inner"></div>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if (!empty($description)): ?>
                            <div class="option-item__desc">
                                <p><?= wp_kses_post($description) ?></p>
                                <a href="#" class="option-item__read-more">Read more</a>
                            </div>
                        <?php endif; ?>

                        <!-- Benefits -->
                        <ul class="option-item__benefits" aria-label="Tour Benefits">
                            <li>
                                <?php echo rc_svg_icon('globe'); ?>
                                <span>Guide: English</span>
                            </li>

                            <?php if (!empty($pax)): ?>
                                <li class="maximum">
                                    <?php echo rc_svg_icon('users'); ?>
                                    <span>
                                        <?= esc_html($pax) ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <li>
                                <?php echo rc_svg_icon('calendar'); ?>
                                <span>Book now, pay later</span>
                            </li>
                            <li>
                                <?php echo rc_svg_icon('check'); ?>
                                <span>Cancel for free</span>
                            </li>
                        </ul>

                        <!-- Starting Time -->
                        <?php if (!empty($starting_time)): ?>
                            <div class="option-item__time">
                                <span class="option-item__time-label">Starting time</span>
                                <div class="option-item__time-chip">
                                    <?= esc_html($starting_time) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Pricing -->
                        <div class="option-item__pricing">
                            <?php if (!$is_price_available): ?>
                                <div class="option-item__price-total">Liên hệ để biết giá</div>
                            <?php else: ?>
                                <h4 class="option-item__price-total h5">
                                    <?php if (!empty($currency)): ?>
                                        <span class="h6 mb-0"><?= esc_html($currency) ?></span>
                                    <?php endif; ?>
                                    <?= esc_html($formatted_price) ?>
                                </h4>
                                <div class="option-item__price-tax">All taxes and fees included</div>
                            <?php endif; ?>
                        </div>

                        <!-- CTA -->
                        <div class="option-item__cta">
                            <button type="button" class="btn btn-select <?= $is_first ? 'btn-select--active' : '' ?>">
                                <?= $is_first ? 'Continue' : 'Select' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>