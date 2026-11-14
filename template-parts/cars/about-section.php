<?php
/**
 * Template part for displaying the About section on the Cars Template
 *
 * Dedicated to Rent Car Da Nang - Private Chauffeur & Fleet Services.
 * Supports ACF fields (hd_ab_car_tpl, badge_ab_car_tpl, description_car_tpl, image_ab_car_tpl)
 * with authentic, high-converting fallbacks.
 *
 * @package rc
 */

$heading = get_field('hd_ab_car_tpl');
$badge = get_field('badge_ab_car_tpl');
$desc = get_field('description_car_tpl');
$image = get_field('image_ab_car_tpl');
$accent_image_url = get_field('accent_image_ab_car_tpl');

// Contact & booking connections
$phone = function_exists('get_field') ? get_field('phone', 'option') : '';
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
if (empty($whatsapp_url) && !empty($phone)) {
    $clean_phone = preg_replace('/\D/', '', (string) $phone);
    $whatsapp_url = 'https://wa.me/' . $clean_phone;
}

// Resolve primary showcase image
$image_url = '';
$image_alt = '';

if (!empty($image)) {
    if (is_array($image)) {
        $image_url = $image['url'] ?? '';
        $image_alt = !empty($image['alt']) ? $image['alt'] : ($image['title'] ?? '');
    } elseif (is_numeric($image)) {
        $image_url = wp_get_attachment_image_url($image, 'large');
        $image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
    } elseif (is_string($image)) {
        $image_url = $image;
    }
}

// Fallback primary image (Authentic uploaded fleet asset)
if (empty($image_url)) {
    $image_url = content_url('/uploads/2026/11/image-Private-Car-Da-Nang-1024x692.webp');
    $image_alt = esc_attr__('Private Car Rental with Driver in Da Nang', 'rc');
}
if (empty($image_alt)) {
    $image_alt = esc_attr__('Rent Car Da Nang - Private Chauffeur & Fleet Service', 'rc');
}


// Fallback copy defaults
$badge = !empty($badge) ? $badge : __('Private Fleet & Chauffeur Service', 'rc');
$heading = !empty($heading) ? $heading : __('Your Trusted Private Car & Driver Service in Central Vietnam', 'rc');
?>
<section class="ui-section cars-about-section" id="about-car-section" aria-labelledby="cars-about-title">
    <div class="container">
        <div class="cars-about-section__grid">

            <!-- Visual Column (Asymmetric Editorial Composition) -->
            <div class="cars-about-section__visual">
                <div class="cars-about-visual">
                    <!-- Main Showcase Frame -->
                    <div class="cars-about-visual__primary-frame">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"
                            class="cars-about-visual__img" loading="lazy" width="720" height="486" />
                        <div class="cars-about-visual__scrim" aria-hidden="true"></div>

                        <!-- Floating Fleet Quality Pill -->
                        <div class="cars-about-badge-pill" aria-label="Fleet Highlights">
                            <span class="cars-about-badge-pill__icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2" />
                                    <circle cx="7" cy="17" r="2" />
                                    <path d="M9 17h6" />
                                    <circle cx="17" cy="17" r="2" />
                                </svg>
                            </span>
                            <div class="cars-about-badge-pill__text">
                                <strong
                                    class="cars-about-badge-pill__title"><?php esc_html_e('Late-Model Fleet', 'rc'); ?></strong>
                                <span
                                    class="cars-about-badge-pill__subtitle"><?php esc_html_e('Sedan • SUV • Minivan', 'rc'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Overlapping Secondary Visual (Airport Transfer / Greeting) -->
                    <div class="cars-about-visual__accent-card">
                        <div class="cars-about-visual__accent-media">
                            <img src="<?php echo esc_url($accent_image_url['url']); ?>"
                                alt="<?php echo esc_attr($accent_image_url['alt']); ?>"
                                class="cars-about-visual__accent-img" loading="lazy" width="300" height="225" />
                        </div>
                        <div class="cars-about-visual__accent-body">
                            <span
                                class="cars-about-visual__accent-tag"><?php esc_html_e('Airport Meet & Greet', 'rc'); ?></span>
                            <p class="cars-about-visual__accent-text">
                                <?php esc_html_e('Flight tracking & personalized arrival nameboard', 'rc'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Floating Credibility / Rating Card -->
                    <div class="cars-about-trust-badge" aria-label="Customer Satisfaction">
                        <div class="cars-about-trust-badge__rating">
                            <span class="cars-about-trust-badge__score">4.9</span>
                            <div class="cars-about-trust-badge__stars" aria-hidden="true">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            </div>
                        </div>
                        <div class="cars-about-trust-badge__info">
                            <strong
                                class="cars-about-trust-badge__title"><?php esc_html_e('Top-Rated Chauffeur', 'rc'); ?></strong>
                            <span
                                class="cars-about-trust-badge__subtitle"><?php esc_html_e('50,000+ Happy Travelers in Central Vietnam', 'rc'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Column (Story, Highlights & CTAs) -->
            <div class="cars-about-section__content">
                <div class="cars-about-header">
                    <span class="ui-badge cars-about-header__badge">
                        <?php echo esc_html($badge); ?>
                    </span>
                    <h2 id="cars-about-title" class="cars-about-header__title">
                        <?php echo esc_html($heading); ?>
                    </h2>
                </div>

                <div class="cars-about-body">
                    <?php if (!empty($desc)): ?>
                        <div class="cars-about-body__prose">
                            <?php echo $desc; ?>
                        </div>
                    <?php else: ?>
                        <div class="cars-about-body__prose">
                            <p class="cars-about-body__lead">
                                <?php esc_html_e('Explore Da Nang, Hoi An Ancient Town, Ba Na Hills, and Hue in supreme comfort with our private chauffeur services. Skip crowded tour buses, unpredictable taxis, and language hurdles.', 'rc'); ?>
                            </p>
                            <p class="cars-about-body__text">
                                <?php esc_html_e('We provide well-maintained, late-model air-conditioned vehicles driven by professional, courteous, English-speaking local drivers. Whether you need a seamless Da Nang Airport transfer or a full-day custom itinerary, we guarantee transparent flat pricing with all highway tolls and parking included—no surprises.', 'rc'); ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- 4 Core Value Pillars (2x2 Grid) -->
                    <div class="cars-about-features" role="list">
                        <!-- Pillar 1: English Drivers -->
                        <div class="cars-about-feature" role="listitem">
                            <div class="cars-about-feature__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div class="cars-about-feature__content">
                                <h3 class="cars-about-feature__title">
                                    <?php esc_html_e('Courteous English Drivers', 'rc'); ?>
                                </h3>
                                <p class="cars-about-feature__desc">
                                    <?php esc_html_e('Punctual, vetted drivers who speak English, assist with baggage, and know local routes.', 'rc'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Pillar 2: Modern Fleet -->
                        <div class="cars-about-feature" role="listitem">
                            <div class="cars-about-feature__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 16c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z" />
                                    <path d="M2 17h20" />
                                    <path d="m18 10-2-6H8L6 10" />
                                    <path d="M4 10v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7" />
                                    <circle cx="7" cy="17" r="2" />
                                    <circle cx="17" cy="17" r="2" />
                                </svg>
                            </div>
                            <div class="cars-about-feature__content">
                                <h3 class="cars-about-feature__title">
                                    <?php esc_html_e('Clean & Inspected Fleet', 'rc'); ?>
                                </h3>
                                <p class="cars-about-feature__desc">
                                    <?php esc_html_e('4-seat sedans, 7-seat SUVs, and 16-seat vans deep-cleaned daily with ice-cold air conditioning.', 'rc'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Pillar 3: All-Inclusive Pricing -->
                        <div class="cars-about-feature" role="listitem">
                            <div class="cars-about-feature__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                    <path d="M6 15h2" />
                                    <path d="M10 15h2" />
                                </svg>
                            </div>
                            <div class="cars-about-feature__content">
                                <h3 class="cars-about-feature__title">
                                    <?php esc_html_e('Transparent All-In Pricing', 'rc'); ?>
                                </h3>
                                <p class="cars-about-feature__desc">
                                    <?php esc_html_e('Upfront flat rates including tolls, airport parking, petrol, and driver expenses. Zero hidden fees.', 'rc'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Pillar 4: Zero Deposit & Flexibility -->
                        <div class="cars-about-feature" role="listitem">
                            <div class="cars-about-feature__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div class="cars-about-feature__content">
                                <h3 class="cars-about-feature__title"><?php esc_html_e('Zero-Deposit Booking', 'rc'); ?>
                                </h3>
                                <p class="cars-about-feature__desc">
                                    <?php esc_html_e('Reserve in 60 seconds with no upfront deposit. Free cancellation up to 24 hours before pickup.', 'rc'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>