<?php
/**
 * About Page - Routes Section
 * Curated Central Vietnam Horizons
 */
$route_img_1 = content_url('/uploads/2026/11/image-ha-1536x864.webp');
$route_img_2 = content_url('/uploads/2026/11/image-khai-dinh-tomb-hue-1024x768.webp');
$route_img_3 = content_url('/uploads/2026/11/img-tour-bana-003-1024x683.webp');
?>
<section class="about-routes-section ui-section">
    <div class="container">
        <!-- Section Header -->
        <div class="about-routes-header text-center">
            <span class="ui-badge about-badge">Destination Horizons</span>
            <h2 class="about-routes-title">Connecting the Wonders of Central Vietnam</h2>
            <p class="about-routes-desc">
                From sun-drenched coastal highways to UNESCO ancient towns and mountain summits, our private transfers let you discover the region at your own natural pace.
            </p>
        </div>

        <!-- Routes Grid -->
        <div class="about-routes-grid">
            <!-- Route 1 -->
            <div class="about-route-card">
                <div class="about-route-card__media">
                    <img src="<?= esc_url($route_img_1); ?>" 
                         alt="Da Nang to Hoi An Private Transfer" 
                         loading="lazy" 
                         class="about-route-card__img" />
                    <span class="about-route-card__duration">
                        <?= rc_svg_icon('clock_check'); ?>
                        ~35 Minutes
                    </span>
                </div>
                <div class="about-route-card__body">
                    <span class="about-route-card__tag">Most Popular Transfer</span>
                    <h3 class="about-route-card__title">Da Nang ⇄ Hoi An Ancient Town</h3>
                    <p class="about-route-card__text">
                        Direct hotel-to-resort connection between Da Nang International Airport, beachfront resorts, and Hoi An’s UNESCO heritage streets. Optional scenic pause at Marble Mountains.
                    </p>
                    <div class="about-route-card__footer">
                        <span class="feature-tag">Door-to-Door</span>
                        <span class="feature-tag">Luggage Handling</span>
                        <span class="feature-tag">AC Comfort</span>
                    </div>
                </div>
            </div>

            <!-- Route 2 -->
            <div class="about-route-card">
                <div class="about-route-card__media">
                    <img src="<?= esc_url($route_img_2); ?>" 
                         alt="Hai Van Pass and Hue Imperial City" 
                         loading="lazy" 
                         class="about-route-card__img" />
                    <span class="about-route-card__duration">
                        <?= rc_svg_icon('clock_check'); ?>
                        ~3.5 Hours
                    </span>
                </div>
                <div class="about-route-card__body">
                    <span class="about-route-card__tag">Top Scenic Route</span>
                    <h3 class="about-route-card__title">Hai Van Pass & Lang Co Bay to Hue</h3>
                    <p class="about-route-card__text">
                        A world-renowned coastal drive through the misty bends of the Hai Van Pass, seafood luncheons overlooking Lang Co oyster lagoons, and an easy arrival in imperial Hue.
                    </p>
                    <div class="about-route-card__footer">
                        <span class="feature-tag">Photo Stops</span>
                        <span class="feature-tag">Lagoon Views</span>
                        <span class="feature-tag">Private Highway</span>
                    </div>
                </div>
            </div>

            <!-- Route 3 -->
            <div class="about-route-card">
                <div class="about-route-card__media">
                    <img src="<?= esc_url($route_img_3); ?>" 
                         alt="Ba Na Hills Golden Bridge Day Chauffeur" 
                         loading="lazy" 
                         class="about-route-card__img" />
                    <span class="about-route-card__duration">
                        <?= rc_svg_icon('clock_check'); ?>
                        Flexible Day Trip
                    </span>
                </div>
                <div class="about-route-card__body">
                    <span class="about-route-card__tag">Day Chauffeur Standby</span>
                    <h3 class="about-route-card__title">Ba Na Hills & Golden Bridge Return</h3>
                    <p class="about-route-card__text">
                        Avoid long taxi queues and peak rideshare rates at the mountain peak. Your chauffeur drops you right at the cable car station and stands by until you are ready to return.
                    </p>
                    <div class="about-route-card__footer">
                        <span class="feature-tag">Dedicated Standby</span>
                        <span class="feature-tag">Return Included</span>
                        <span class="feature-tag">Zero Stress</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

