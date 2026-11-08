<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$booking_form = get_field('form_booking', 'option');
$included_tour = get_field('included_tour');
$excluded_tour = get_field('excluded_tour');
$itinerary_tour = get_field('itinerary_tour');
$highlights = get_field('highlights_tour');
$price_group = get_field('price_for_group_tour');
$price_group_child = get_field('child_price_group');
$price_private = get_field('price_for_private_tour');
$gallery_tour = get_field('gallery_tour');
$price_tour = get_field('price_tour');
$formatted_price = rc_format_price($price_tour);
$faqs_tour = get_field('faqs_list_tour');
?>
<section class="rc-section main-section">
    <div class="container">
        <div class="main-section-wrap">
            <div class="main-section-content">
                <?php
                // Build array of menu items to display dynamically
                $nav_items = [];
                $nav_items['tour-overview'] = ['label' => __('Overview', 'rc'), 'show' => true];
                $nav_items['tour-highlights'] = ['label' => __('Highlights', 'rc'), 'show' => !empty($highlights) || !empty($gallery_tour)];
                $nav_items['tour-inclusions'] = ['label' => __('Inclusions', 'rc'), 'show' => (!empty($included_tour) || !empty($excluded_tour))];
                $nav_items['tour-prices'] = ['label' => __('Prices', 'rc'), 'show' => (!empty($price_group) || !empty($price_private))];
                $nav_items['tour-itinerary'] = ['label' => __('Itinerary', 'rc'), 'show' => !empty($itinerary_tour)];
                $nav_items['tour-review'] = ['label' => __('Reviews', 'rc'), 'show' => (comments_open() || get_comments_number() > 0)];
                $nav_items['tour-faqs'] = ['label' => __('FAQs', 'rc'), 'show' => !empty($faqs_tour)];
                ?>

                <!-- Anchor Navigation Menu -->
                <nav class="anchor-nav" id="anchor-nav" aria-label="<?php esc_attr_e('Tour navigation', 'rc'); ?>">
                    <div class="anchor-nav__inner">
                        <ul class="anchor-nav__list">
                            <?php foreach ($nav_items as $id => $item):
                                if (!$item['show'])
                                    continue;
                                ?>
                                <li class="anchor-nav__item">
                                    <a href="#<?php echo esc_attr($id); ?>" class="anchor-nav__link">
                                        <?php echo esc_html($item['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </nav>

                <div class="main-section-content-wrapper">
                    <div id="tour-overview" class="tour-overview">
                        <h2 class="rc-heading ">Overview Tour</h2>
                        <div class="tour-overview__content">
                            <?php the_content() ?>
                        </div>
                    </div>

                    <?php if (!empty($highlights)): ?>
                        <div id="tour-highlights" class="tour-highlights">
                            <h2 class="rc-heading ">Tour Highlights</h2>
                            <div class="tour-highlights__content">
                                <?= !empty($highlights) ? $highlights : '' ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($included_tour) || !empty($excluded_tour)): ?>
                        <div id="tour-inclusions" class="tour-inclusions">
                            <div class="tour-inclusions__wrapper">
                                <?php if (!empty($included_tour)): ?>
                                    <div class="inclusion-card card-included">
                                        <h3>
                                            <?php echo rc_svg_icon('check_circle'); ?>
                                            What's Included
                                        </h3>
                                        <ul>
                                            <?php foreach ($included_tour as $index => $item):
                                                $text = is_array($item) ? current($item) : $item;
                                                if (empty($text))
                                                    continue;
                                                ?>
                                                <li>
                                                    <?php echo rc_svg_icon('check_check'); ?>
                                                    <span>
                                                        <?php echo esc_html($text); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($excluded_tour)): ?>
                                    <div class="inclusion-card card-excluded">
                                        <h3>
                                            <?php echo rc_svg_icon('x_circle'); ?>
                                            Not Included
                                        </h3>
                                        <ul>
                                            <?php foreach ($excluded_tour as $index => $item):
                                                $text = is_array($item) ? current($item) : $item;
                                                if (empty($text))
                                                    continue;
                                                ?>
                                                <li>
                                                    <?php echo rc_svg_icon('x_mark'); ?>
                                                    <span>
                                                        <?php echo esc_html($text); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php
                    get_template_part('template-parts/single-tour/itinerary-section');
                    get_template_part('template-parts/single-tour/review-section');
                    // get_template_part('template-parts/single-tour/faqs-section');
                    ?>
                </div>
            </div>

            <div class="main-section__sidebar">
                <?php get_template_part('template-parts/single-tour/form-booking'); ?>
            </div>

            <div class="main-section__sidebar-mobile">
                <div class="main-section__sidebar-mobile-inner">

                    <div class="price">
                        <?php if ($formatted_price !== ''): ?>
                            <span class="label">From:</span>
                            <span class="value">
                                <?php if (!empty($currency)): ?>
                                    <span class="mb-0"><?= esc_html($currency) ?></span>
                                <?php endif; ?>
                                <?= esc_html($formatted_price) ?>
                            </span>
                            <?php
                            $is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
                            if (!$is_car_tour):
                                ?>
                                <span class="unit">/ person</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <a href="#form-booking-tour" class="rc-button d-flex align-items-center justify-content-center"
                        aria-label="button Check Availability">
                        Check Availability
                        <?php echo rc_svg_icon('button_arrow'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>