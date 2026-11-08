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

$tour_options = $current_post_id ? get_field('tour_options', $current_post_id) : get_field('tour_options');
?>

<?php if (!empty($tour_options)): ?>
    <section class="rc-section rc-tour-options vm-tour-options">
        <div class="container">
            <h2 class="rc-heading rc-tour-options__title">
                <?php printf(esc_html__('Choose from %d available options', 'rc'), count($tour_options)); ?>
            </h2>

            <div class="rc-tour-options__grid vm-tour-options__grid">
                <?php foreach ($tour_options as $key => $option): ?>
                    <?php
                    $name = $option['name'] ?? '';
                    $starting_time = $option['starting_time'] ?? '';
                    $pax = $option['pax'] ?? ($option['maximum'] ?? '');
                    $description = isset($option['description']) ? $option['description'] : '';
                    $is_selected_class = ($key === 0) ? 'is-selected' : '';

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
                            <button type="button" class="btn btn-select <?= ($key === 0) ? 'btn-select--active' : '' ?>">
                                <?= ($key === 0) ? 'Continue' : 'Select' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>