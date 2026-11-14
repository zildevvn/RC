<?php
/**
 * Shared Editorial About Section Component
 *
 * Single source of truth for the Editorial About Section across Blog Template
 * and Tour Locations taxonomy archives.
 *
 * Designed for Rent Car Da Nang - Private Chauffeur & Mobility Services.
 * Supports dynamic content with conversion-focused, authentic fallbacks.
 *
 * @package rc
 */

$heading    = $args['heading'] ?? '';
$badge      = $args['badge'] ?? '';
$desc       = $args['desc'] ?? ($args['description'] ?? '');
$image      = $args['image'] ?? [];
$section_id = !empty($args['section_id']) ? $args['section_id'] : 'blog-about-section';
$title_id   = !empty($args['title_id']) ? $args['title_id'] : 'blog-about-title';

// Contact & booking connections
$phone = function_exists('get_field') ? get_field('phone', 'option') : '';
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
if (empty($whatsapp_url) && !empty($phone)) {
    $clean_phone  = preg_replace('/\D/', '', (string) $phone);
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
$badge   = !empty($badge) ? $badge : __('About Rent Car Da Nang', 'rc');
$heading = !empty($heading) ? $heading : __('Your Trusted Private Driver & Fleet Across Central Vietnam', 'rc');
?>

<section class="ui-section blog-about-section" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container">
        <div class="blog-about-section__grid">

            <!-- Visual Column (Editorial Layered Composition) -->
            <div class="blog-about-section__visual">
                <div class="blog-about-visual">
                    <!-- Main Showcase Frame -->
                    <div class="blog-about-visual__primary-frame">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"
                            class="blog-about-visual__img" loading="lazy" width="720" height="486" />
                        <div class="blog-about-visual__scrim" aria-hidden="true"></div>

                        <!-- Floating Driver Quality Pill -->
                        <div class="blog-about-pill" aria-label="<?php esc_attr_e('Driver Quality Rating', 'rc'); ?>">
                            <span class="blog-about-pill__icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                            </span>
                            <div class="blog-about-pill__text">
                                <strong
                                    class="blog-about-pill__title"><?php esc_html_e('4.9 / 5 Rating', 'rc'); ?></strong>
                                <span
                                    class="blog-about-pill__subtitle"><?php esc_html_e('Courteous English Drivers', 'rc'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Transparent Pricing Assurance Pill -->
                    <div class="blog-about-trust-pill" aria-label="<?php esc_attr_e('Pricing Guarantee', 'rc'); ?>">
                        <span class="blog-about-trust-pill__icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </span>
                        <div class="blog-about-trust-pill__info">
                            <strong
                                class="blog-about-trust-pill__title"><?php esc_html_e('100% Flat Pricing', 'rc'); ?></strong>
                            <span
                                class="blog-about-trust-pill__desc"><?php esc_html_e('Tolls, parking & fuel included', 'rc'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Column (Story, Highlights & CTA) -->
            <div class="blog-about-section__content">
                <div class="blog-about-header">
                    <span class="ui-badge blog-about-header__badge">
                        <?php echo esc_html($badge); ?>
                    </span>
                    <h2 id="<?php echo esc_attr($title_id); ?>" class="blog-about-header__title">
                        <?php echo esc_html($heading); ?>
                    </h2>
                </div>

                <div class="blog-about-body">
                    <?php if (!empty($desc)): ?>
                        <div class="blog-about-body__prose">
                            <?php echo $desc; ?>
                        </div>
                    <?php else: ?>
                        <div class="blog-about-body__prose">
                            <p class="blog-about-body__lead">
                                <?php esc_html_e('Experience the best of Da Nang and Central Vietnam at your own pace with our private chauffeur services. Skip the uncertainty of hailing street taxis, navigating public transport, or rigid tour bus schedules.', 'rc'); ?>
                            </p>
                            <p class="blog-about-body__text">
                                <?php esc_html_e('We provide well-maintained, late-model air-conditioned sedans, SUVs, and vans with vetted, polite, English-speaking local drivers. From on-time airport arrivals to scenic coastal journeys along the Hai Van Pass, enjoy transparent all-inclusive flat rates with zero hidden charges.', 'rc'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

