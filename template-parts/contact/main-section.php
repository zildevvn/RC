<?php
$heading = get_field('heading_contact');
$badge = get_field('badge_contact');
$sub_heading = get_field('sub_heading_contact');
$image = get_field('image_contact');
$form_contact = get_field('form_contact');
$address = get_field('address', 'option');
$email = get_field('email', 'option');
$phone = get_field('phone', 'option');

// Handle image data if provided in ACF
$image_url = '';
$image_alt = '';
if (!empty($image)) {
    if (is_array($image)) {
        $image_url = $image['url'] ?? '';
        $image_alt = !empty($image['alt']) ? $image['alt'] : (!empty($image['title']) ? $image['title'] : 'Rent Car Da Nang Fleet & Service');
    } elseif (is_numeric($image)) {
        $image_url = wp_get_attachment_url($image);
        $image_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: 'Rent Car Da Nang Fleet & Service';
    } else {
        $image_url = $image;
        $image_alt = 'Rent Car Da Nang Fleet & Service';
    }
}

$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
$clean_phone = !empty($phone) ? preg_replace('/[^0-9+]/', '', $phone) : '';
?>
<section class="rc-section contact-main-section main-section">
    <div class="container">
        <!-- 1. Section Header -->
        <?php if (!empty($heading) || !empty($badge) || !empty($sub_heading)): ?>
            <div class="contact-header-block text-center">
                <?php if (!empty($badge)): ?>
                    <span class="rc-badge contact-header-block__badge">
                        <?= esc_html($badge) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="rc-heading contact-header-block__title">
                        <?= wp_kses_post($heading) ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($sub_heading)): ?>
                    <p class="contact-header-block__desc">
                        <?= wp_kses_post($sub_heading) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- 2. TIER 1: Horizontal Quick-Contact Deck (3 Standalone Action Pillars) -->
        <div class="contact-deck-strip contact-cards">
            <!-- Channel 1: Headquarters & Fleet Depot -->
            <?php if (!empty($address)): ?>
                <div class="contact-deck-card contact-deck-card--office contact-info address">
                    <div class="contact-deck-card__top">
                        <div class="contact-deck-card__icon-badge">
                            <?= rc_svg_icon('map_pin_check'); ?>
                        </div>
                        <span class="contact-deck-card__tag">Headquarters</span>
                    </div>
                    <div class="contact-deck-card__body contact-info__content">
                        <span class="contact-deck-card__eyebrow">Visit Our Office</span>
                        <h3 class="contact-deck-card__value h5"><?= esc_html($address) ?></h3>
                    </div>
                    <div class="contact-deck-card__footer">
                        <a href="#map-section" class="contact-deck-card__action contact-deck-card__action--link">
                            <span>Locate on Map</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Channel 2: WhatsApp & 24/7 Priority Line -->
            <?php if (!empty($phone)): ?>
                <div class="contact-deck-card contact-deck-card--hotline contact-info">
                    <div class="contact-deck-card__top">
                        <div class="contact-deck-card__icon-badge contact-deck-card__icon-badge--whatsapp">
                            <?= rc_svg_icon('whatsapp'); ?>
                        </div>
                        <span class="contact-deck-card__status-dot">
                            <span class="pulse-dot"></span>
                            Online 24/7
                        </span>
                    </div>
                    <div class="contact-deck-card__body contact-info__content">
                        <span class="contact-deck-card__eyebrow">Priority Line & WhatsApp</span>
                        <h3 class="contact-deck-card__value h5"><?= esc_html($phone) ?></h3>
                    </div>
                    <div class="contact-deck-card__footer">
                        <a href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : 'tel:' . esc_attr($clean_phone) ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="contact-deck-card__action contact-deck-card__action--btn">
                            <span>Start WhatsApp Chat</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Channel 3: Official Inquiries & Quotes -->
            <?php if (!empty($email)): ?>
                <div class="contact-deck-card contact-deck-card--email contact-info">
                    <div class="contact-deck-card__top">
                        <div class="contact-deck-card__icon-badge contact-deck-card__icon-badge--email">
                            <?= rc_svg_icon('mail_check'); ?>
                        </div>
                        <span class="contact-deck-card__tag">Quick Reply</span>
                    </div>
                    <div class="contact-deck-card__body contact-info__content">
                        <span class="contact-deck-card__eyebrow">Official Email Desk</span>
                        <h3 class="contact-deck-card__value h5"><?= esc_html($email) ?></h3>
                    </div>
                    <div class="contact-deck-card__footer">
                        <a href="mailto:<?= esc_attr($email) ?>"
                           class="contact-deck-card__action contact-deck-card__action--link">
                            <span>Send an Email</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- 3. TIER 2: Executive Inquiry & Booking Suite -->
        <div class="contact-portal main-section-warp">
            <div class="contact-portal__grid">
                <!-- Portal Left: Dedicated Elevated Form Console -->
                <div class="contact-portal__form-col main-section__right">
                    <div class="contact-form-card">
                        <div class="contact-form-card__head">
                            <span class="contact-form-card__eyebrow">Booking & Rental Inquiries</span>
                            <h3 class="contact-form-card__title">Send Us a Direct Message</h3>
                            <p class="contact-form-card__subtitle">
                                Tell us your travel dates, vehicle preference, or custom itinerary. Our concierge desk will respond with an all-inclusive quote within 15 minutes.
                            </p>
                        </div>

                        <?php if (!empty($form_contact)): ?>
                            <div class="contact-form-wrapper form-wrapper">
                                <?= do_shortcode($form_contact) ?>
                            </div>
                        <?php endif; ?>

                        <div class="contact-form-card__guarantee">
                            <div class="guarantee-pill">
                                <?= rc_svg_icon('shield_check'); ?>
                                <span>100% Privacy Protected • Transparent All-Inclusive Quotes • Free Cancellation 24h Prior</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portal Right: Editorial Experience & Concierge Guarantees -->
                <div class="contact-portal__showcase-col main-section__left">
                    <div class="contact-showcase-panel main-section__left-inner">
                        <?php if (!empty($image_url)): ?>
                            <div class="contact-showcase-media main-section__left-bg">
                                <img src="<?= esc_url($image_url) ?>" alt="<?= esc_attr($image_alt) ?>" loading="lazy" />
                                <div class="contact-showcase-media__gradient"></div>
                                <div class="contact-showcase-media__badge">
                                    <span class="stars">★★★★★</span>
                                    <span>5-Star Rated Service</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="contact-showcase-content">
                            <div class="contact-showcase-content__header">
                                <span class="contact-showcase-badge">Premium Fleet & Chauffeur</span>
                                <h4 class="contact-showcase-title">Why Rent With Rent Car Da Nang?</h4>
                                <p class="contact-showcase-desc">
                                    We provide comfortable, modern sedans, SUVs, and luxury vans with licensed, courteous English-speaking drivers throughout Da Nang, Hoi An, Hue, and Central Vietnam.
                                </p>
                            </div>

                            <div class="contact-perks-list">
                                <div class="contact-perk-item">
                                    <div class="contact-perk-item__icon">
                                        <?= rc_svg_icon('clock_check'); ?>
                                    </div>
                                    <div class="contact-perk-item__text">
                                        <h5>Punctual Pickups Guaranteed</h5>
                                        <p>Flight-tracked airport meet & greet with complimentary waiting time.</p>
                                    </div>
                                </div>

                                <div class="contact-perk-item">
                                    <div class="contact-perk-item__icon">
                                        <?= rc_svg_icon('step_driver'); ?>
                                    </div>
                                    <div class="contact-perk-item__text">
                                        <h5>Professional English-Speaking Drivers</h5>
                                        <p>Experienced, friendly chauffeurs dedicated to a smooth journey.</p>
                                    </div>
                                </div>

                                <div class="contact-perk-item">
                                    <div class="contact-perk-item__icon">
                                        <?= rc_svg_icon('step_shield'); ?>
                                    </div>
                                    <div class="contact-perk-item__text">
                                        <h5>Comprehensive Insurance & Sanitised Cars</h5>
                                        <p>Fully licensed fleet with comprehensive coverage for all passengers.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-concierge-callout">
                                <div class="contact-concierge-callout__avatar">
                                    <?= rc_svg_icon('headset'); ?>
                                </div>
                                <div class="contact-concierge-callout__info">
                                    <span class="label">Need Immediate Assistance?</span>
                                    <a href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : 'tel:' . esc_attr($clean_phone) ?>" class="link">
                                        <?= !empty($phone) ? esc_html($phone) : 'Chat via WhatsApp' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
