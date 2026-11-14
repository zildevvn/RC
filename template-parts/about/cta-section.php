<?php
/**
 * About Page - CTA Section
 * Executive Concierge Closing Call to Action
 */
$phone = function_exists('get_field') ? get_field('phone', 'option') : '+84 901 234 567';
if (empty($phone)) {
    $phone = '+84 901 234 567';
}
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : 'https://wa.me/' . preg_replace('/\D/', '', $phone);
$cars_url = home_url('/cars/');
?>
<section class="about-cta-section ui-section">
    <div class="container">
        <div class="about-cta-card">
            <div class="about-cta-card__bg-glow"></div>
            
            <div class="about-cta-card__content text-center">
                <span class="ui-badge about-badge about-badge--gold">Reserve Your Chauffeur</span>
                <h2 class="about-cta-card__title">Experience Central Vietnam With Complete Peace of Mind</h2>
                <p class="about-cta-card__desc">
                    Book in advance with instant confirmation and free 24-hour cancellation. Our English-speaking concierge team is standing by to customize your route or answer any fleet questions.
                </p>

                <div class="about-cta-card__actions">
                    <a href="<?= esc_url($cars_url); ?>" class="ui-button about-cta-btn about-cta-btn--primary">
                        <span>Browse Vehicle Fleet</span>
                        <?= rc_svg_icon('button_arrow'); ?>
                    </a>

                    <a href="<?= esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" class="about-cta-btn about-cta-btn--whatsapp">
                        <span class="pulse-dot"></span>
                        <div class="btn-icon">
                            <?= rc_svg_icon('whatsapp'); ?>
                        </div>
                        <span>Chat via WhatsApp (24/7)</span>
                    </a>
                </div>

                <div class="about-cta-card__trust">
                    <div class="trust-item">
                        <?= rc_svg_icon('shield_check'); ?>
                        <span>No Hidden Surcharges</span>
                    </div>
                    <div class="trust-item">
                        <?= rc_svg_icon('clock_check'); ?>
                        <span>Free 24h Cancellation</span>
                    </div>
                    <div class="trust-item">
                        <?= rc_svg_icon('check_check'); ?>
                        <span>Flight Delay Covered</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

