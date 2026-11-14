<?php
/**
 * About Page - Story Section
 * Template Part for Rent Car Da Nang brand narrative & editorial showcase
 */
$main_image = content_url('/uploads/2026/11/img-Da-Nang-Airport-to-Hotel-1536x1152.webp');
?>
<section class="about-story-section ui-section">
    <div class="container">
        <div class="about-story-grid">
            <!-- Left Column: Editorial Brand Story -->
            <div class="about-story-content">
                <div class="about-story-header">
                    <span class="ui-badge about-badge">Our Heritage & Vision</span>
                    <h2 class="about-story-title">Crafting First-Class Road Travel in Central Vietnam</h2>
                    <p class="about-story-lead">
                        Founded as an executive mobility division under <strong>VM Travel</strong> — a respected hospitality and destination leader — <strong>Rent Car Da Nang</strong> was created to deliver an uncompromising standard of private road transportation.
                    </p>
                </div>

                <div class="about-story-body">
                    <p>
                        We recognized a persistent challenge for travelers arriving in Da Nang, Hoi An, and Hue: inconsistent vehicle standards, language barriers with metered street taxis, and unexpected surcharges on long-distance routes. 
                    </p>
                    <p>
                        Our answer is simple: complete transparency, pristine late-model vehicles, and courteous English-speaking drivers who prioritize your safety, privacy, and peace of mind at every turn.
                    </p>
                </div>

                <!-- Philosophy Mini-Pillars -->
                <div class="about-philosophy-grid">
                    <div class="about-philosophy-item">
                        <div class="about-philosophy-item__icon">
                            <?= rc_svg_icon('shield_check'); ?>
                        </div>
                        <div class="about-philosophy-item__content">
                            <h4>Direct Fleet Ownership</h4>
                            <p>We own, maintain, and inspect our entire fleet under strict safety standards. No third-party unregulated subcontracts.</p>
                        </div>
                    </div>

                    <div class="about-philosophy-item">
                        <div class="about-philosophy-item__icon">
                            <?= rc_svg_icon('step_driver'); ?>
                        </div>
                        <div class="about-philosophy-item__content">
                            <h4>Chauffeur Excellence</h4>
                            <p>Our drivers are licensed, background-checked hospitality ambassadors who speak English and respect your personal space.</p>
                        </div>
                    </div>
                </div>

                <!-- Reassurance Stamp -->
                <div class="about-story-stamp">
                    <div class="about-stamp-pill">
                        <?= rc_svg_icon('check_circle'); ?>
                        <span>Licensed International Tour & Mobility Operator • 100% Comprehensive Passenger Insurance</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Layered Visual Showcase -->
            <div class="about-story-visual">
                <div class="about-visual-card">
                    <div class="about-visual-card__media">
                        <img src="<?= esc_url($main_image); ?>" 
                             alt="Rent Car Da Nang Premier Chauffeur Fleet" 
                             loading="lazy" 
                             class="about-visual-card__img" />
                        <div class="about-visual-card__scrim"></div>
                    </div>

                    <!-- Floating Milestone Badge (Bottom) -->
                    <div class="about-floating-badge about-floating-badge--milestone">
                        <div class="about-floating-badge__icon">
                            <?= rc_svg_icon('clock_check'); ?>
                        </div>
                        <div class="about-floating-badge__info">
                            <span class="number">10+ Years</span>
                            <span class="label">Hospitality & Mobility Excellence</span>
                        </div>
                    </div>

                    <!-- Floating Rating Badge (Top Right) -->
                    <div class="about-floating-badge about-floating-badge--rating">
                        <div class="about-floating-badge__stars">
                            ★★★★★
                        </div>
                        <div class="about-floating-badge__rating-text">
                            <strong>4.9 / 5.0</strong>
                            <span>50,000+ Happy Guests</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

