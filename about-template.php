<?php
/**
 * Template Name: About
 * Description: Dedicated About Page introducing Rent Car Da Nang private chauffeur & car rental services.
 */
$phone = function_exists('get_field') ? get_field('phone', 'option') : '+84 901 234 567';
if (empty($phone)) {
    $phone = '+84 901 234 567';
}
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : 'https://wa.me/' . preg_replace('/\D/', '', $phone);
$cars_url = home_url('/cars/');
$contact_url = home_url('/contact/');

get_header();
?>
<main id="primary" class="site-main ui-about-page">

    <!-- ======================================================================
         1. HERO SECTION: Brand Mission & Chauffeur Excellence
         ====================================================================== -->
    <section class="ui-about-hero" data-purpose="about-hero">
        <div class="ui-about-hero__bg">
            <img src="<?= content_url('/uploads/2026/11/bg-hero-1024x683.webp'); ?>"
                alt="Rent Car Da Nang Premier Road Travel" class="ui-about-hero__bg-img" />
            <div class="ui-about-hero__scrim" aria-hidden="true"></div>
        </div>

        <div class="ui-about-hero__container container">
            <div class="ui-about-hero__content">
                <!-- Eyebrow Pill -->
                <div class="ui-about-hero__badge">
                    <span class="ui-about-hero__pulse-dot"></span>
                    <span>Rent Car Da Nang • Licensed Local Transport & Travel Partner</span>
                </div>

                <!-- Main Serif Headline -->
                <h1> <?php the_title() ?></h1>
                <!-- Body Narrative -->
                <div class="ui-about-hero__desc">
                    <?php the_content(); ?>
                </div>

                <!-- Dual Actions -->
                <div class="ui-about-hero__actions">
                    <a href="#services" class="ui-about-hero__btn ui-about-hero__btn--primary">
                        <span>Explore Our Services</span>
                        <span class="arrow">↓</span>
                    </a>
                    <a href="<?= esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer"
                        class="ui-about-hero__btn ui-about-hero__btn--ghost"
                        aria-label="Chat with our concierge on WhatsApp">
                        <svg class="icon-whatsapp" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-9 8.5 8.5 8.5 0 0 1-4.4-1.2L3 20l1.2-4.5A8.5 8.5 0 1 1 21 11.5Z" />
                            <path d="M8 10.5c.5 2 2 3.5 4 4" />
                        </svg>
                        <span>Chat via WhatsApp (24/7)</span>
                    </a>
                </div>

                <!-- Trust Strip / Quick Credentials -->
                <div class="ui-about-hero__strip">
                    <div class="ui-about-hero__metric">
                        <span class="value">10+ Years</span>
                        <span class="label">Hospitality Heritage</span>
                    </div>
                    <span class="sep" aria-hidden="true">•</span>
                    <div class="ui-about-hero__metric">
                        <span class="value">50,000+</span>
                        <span class="label">Safe Journeys Completed</span>
                    </div>
                    <span class="sep" aria-hidden="true">•</span>
                    <div class="ui-about-hero__metric">
                        <span class="value">100%</span>
                        <span class="label">Licensed &amp; Insured</span>
                    </div>
                    <span class="sep" aria-hidden="true">•</span>
                    <div class="ui-about-hero__metric ui-about-hero__metric--rating">
                        <span class="stars">★★★★★</span>
                        <span class="value">4.9/5</span>
                        <span class="label">TripAdvisor &amp; Google</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         2. BRAND STORY & HERITAGE: The Rent Car Da Nang Narrative
         ====================================================================== -->
    <section class="ui-about-story" id="story" data-purpose="brand-story">
        <div class="container">
            <div class="ui-about-story__grid">
                <!-- Left Column: Editorial Storytelling -->
                <div class="ui-about-story__content">
                    <div class="ui-about-story__header">
                        <span class="ui-about-badge">Our Story & Local Roots</span>
                        <h2>How a Local Da Nang Travel Agent Built a Better Way to Explore
                            Central Vietnam</h2>
                        <p class="ui-about-story__lead">
                            Rent Car Da Nang started over ten years ago with a simple observation right here on the
                            coast of Central Vietnam. After welcoming thousands of international visitors to our region,
                            we saw how easily a great holiday could be soured by the first 30 minutes on the road.
                            Travelers flew all the way to enjoy the beaches of Da Nang, the lantern-lit streets of Hoi
                            An, and the royal history of Hue, only to step outside the terminal into unpredictable taxi
                            meters, unexpected highway toll charges, older cars with weak air conditioning, or awkward
                            language barriers with drivers who could not find their resort
                        </p>
                    </div>

                    <div class="ui-about-story__body">
                        <p>
                            We decided to build an independent da nang travel agent that works the way travelers
                            actually wish local transport worked. Instead of acting as an online booking broker that
                            passes your reservation along to random subcontractors, Rent Car Da Nang owns, inspects, and
                            maintains every vehicle in our garage.
                        </p>
                        <p>
                            When you ride with us, you get a clean late-model car, a polite English-speaking local
                            driver who knows the roads inside out, and one clear upfront price that covers everything.
                            Whether you are catching a quick ride from the airport or spending the day winding over the
                            Hai Van Pass, our goal is to make every mile feel calm, safe, and effortless.
                        </p>
                    </div>

                    <!-- Core Philosophy Points -->
                    <div class="ui-about-story__pillars">
                        <div class="ui-about-story__pillar">
                            <div class="ui-about-story__pillar-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    <polyline points="9 12 11 14 15 10" />
                                </svg>
                            </div>
                            <div class="ui-about-story__pillar-text">
                                <h3>In-House Fleet Care</h3>
                                <p>We own and service every sedan, SUV, minivan, and limousine ourselves. Each car goes
                                    through a thorough daily safety check and cabin sanitization before pickup—never
                                    outsourced to unverified third parties.</p>
                            </div>
                        </div>

                        <div class="ui-about-story__pillar">
                            <div class="ui-about-story__pillar-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div class="ui-about-story__pillar-text">
                                <h3>Friendly Local Drivers</h3>
                                <p>Our chauffeurs are full-time local professionals who speak clear English, drive
                                    smoothly, and respect your privacy. They are happy to share favorite local coffee
                                    stops along the way or simply give you a quiet, relaxing ride.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Reassurance Seal -->
                    <div class="ui-about-story__seal">
                        <div class="seal-pill">
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span>Licensed International Travel Operator in Vietnam | 100% Comprehensive Passenger
                                Insurance Included</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Layered Visual Presentation -->
                <div class="ui-about-story__visual">
                    <div class="ui-about-visual-card">
                        <div class="ui-about-visual-card__media">
                            <img src="<?= content_url('/uploads/2026/11/img-Da-Nang-Airport-to-Hotel-1536x1152.webp'); ?>"
                                alt="Rent Car Da Nang Chauffeur &amp; Airport Meet Service" loading="lazy"
                                class="ui-about-visual-card__img" />
                            <div class="ui-about-visual-card__scrim" aria-hidden="true"></div>
                        </div>

                        <!-- Floating Badge 1: 10+ Years Excellence -->
                        <div class="ui-about-floating-badge ui-about-floating-badge--experience">
                            <div class="badge-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                            <div class="badge-content">
                                <span class="number">10+ Years</span>
                                <span class="label">Hospitality &amp; Road Excellence</span>
                            </div>
                        </div>

                        <!-- Floating Badge 2: Verified TripAdvisor Rating -->
                        <div class="ui-about-floating-badge ui-about-floating-badge--rating">
                            <div class="stars">★★★★★</div>
                            <div class="rating-text">
                                <strong>4.9 / 5.0</strong>
                                <span>50,000+ Happy Guests</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         3. SERVICE SPECTRUM: What We Offer
         ====================================================================== -->
    <section class="ui-about-services" id="services" data-purpose="services-spectrum">
        <div class="container">
            <!-- Section Header -->
            <div class="ui-about-services__header">
                <span class="ui-about-badge">Comprehensive Mobility Solutions</span>
                <h2 class="ui-about-services__title">Our Core Transportation Services</h2>
                <p class="ui-about-services__desc">
                    Tailored private transportation designed for individual travelers, couples, families, and corporate
                    groups navigating Central Vietnam.
                </p>
            </div>

            <!-- 4-Card Service Grid -->
            <div class="ui-about-services__grid">
                <!-- Service 1: Airport Transfers -->
                <div class="ui-about-service-card">
                    <div class="ui-about-service-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3z" />
                            <path
                                d="M3.055 11H5a2 2 0 0 1 2 2v1a2 2 0 0 0 2 2 2 2 0 0 1 2 2v2.945M8 3.935V5.5A2.5 2.5 0 0 0 10.5 8h.5a2 2 0 0 1 2 2 2 2 0 1 0 4 0 2 2 0 0 1 2-2h1.064M15 20.488V18a2 2 0 0 1 2-2h3.064M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                    </div>
                    <h3 class="ui-about-service-card__title">Da Nang Airport Transfers</h3>
                    <p class="ui-about-service-card__text">
                        Seamless 24/7 meet &amp; greet at Da Nang International Airport (DAD). Includes flight
                        monitoring, complimentary 60-minute wait time, and luggage handling.
                    </p>
                    <ul class="ui-about-service-card__features">
                        <li><span>✓</span> Real-time flight tracking</li>
                        <li><span>✓</span> Welcome nameboard at arrival</li>
                        <li><span>✓</span> Zero surcharge for flight delays</li>
                    </ul>
                </div>

                <!-- Service 2: Intercity & Heritage Transfers -->
                <div class="ui-about-service-card">
                    <div class="ui-about-service-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z" />
                            <path d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        </svg>
                    </div>
                    <h3 class="ui-about-service-card__title">Intercity &amp; Heritage Transfers</h3>
                    <p class="ui-about-service-card__text">
                        Direct point-to-point private transfers between Da Nang, Hoi An Ancient Town, Ba Na Hills, My
                        Son Sanctuary, and imperial Hue City.
                    </p>
                    <ul class="ui-about-service-card__features">
                        <li><span>✓</span> Door-to-door resort connection</li>
                        <li><span>✓</span> Scenic Hai Van Pass routing</li>
                        <li><span>✓</span> Express highway toll fees included</li>
                    </ul>
                </div>

                <!-- Service 3: Custom Sightseeing by the Day -->
                <div class="ui-about-service-card">
                    <div class="ui-about-service-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10" />
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
                        </svg>
                    </div>
                    <h3 class="ui-about-service-card__title">Custom Sightseeing by the Day</h3>
                    <p class="ui-about-service-card__text">
                        Explore Central Vietnam on your own schedule with a dedicated private chauffeur. Pause for
                        photos, explore local eateries, and linger wherever you like.
                    </p>
                    <ul class="ui-about-service-card__features">
                        <li><span>✓</span> Flexible multi-stop itinerary</li>
                        <li><span>✓</span> Friendly local recommendations</li>
                        <li><span>✓</span> 4-hour, 8-hour or multi-day hire</li>
                    </ul>
                </div>

                <!-- Service 4: Executive & VIP Limousine -->
                <div class="ui-about-service-card">
                    <div class="ui-about-service-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path
                                d="M8 17h8M5 11l2-5h10l2 5M4 17h-.5a1.5 1.5 0 0 1-1.5-1.5V12a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3.5a1.5 1.5 0 0 1-1.5 1.5H20M7 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                        </svg>
                    </div>
                    <h3 class="ui-about-service-card__title">Executive &amp; VIP Limousine</h3>
                    <p class="ui-about-service-card__text">
                        Premium mobility for corporate delegations, golf trips, and discerning travelers requiring DCar
                        VIP Limousines (9-11 seats) or executive SUVs.
                    </p>
                    <ul class="ui-about-service-card__features">
                        <li><span>✓</span> Reclining leather massage seats</li>
                        <li><span>✓</span> High-speed Wi-Fi &amp; USB ports</li>
                        <li><span>✓</span> Suited, top-tier chauffeur</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         4. STANDARDS: The 4 Pillars of Chauffeur & Fleet Excellence
         ====================================================================== -->
    <section class="ui-about-standards" id="standards" data-purpose="service-standards">
        <div class="container">
            <!-- Header -->
            <div class="ui-about-standards__header">
                <span class="ui-about-badge">Uncompromising Quality</span>
                <h2 class="ui-about-standards__title">The 4 Pillars That Define Our Service</h2>
                <p class="ui-about-standards__desc">
                    Every ride with Rent Car Da Nang is built upon rigorous operating standards to ensure complete
                    comfort, punctuality, and passenger peace of mind.
                </p>
            </div>

            <!-- Bento Standards Grid -->
            <div class="ui-about-standards__grid">
                <!-- Pillar 1: Modern Fleet -->
                <div class="ui-about-standard-card">
                    <div class="ui-about-standard-card__top">
                        <span class="ui-about-standard-card__num">01</span>
                        <div class="ui-about-standard-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M8 17h8M5 11l2-5h10l2 5M4 17h-.5a1.5 1.5 0 0 1-1.5-1.5V12a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3.5a1.5 1.5 0 0 1-1.5 1.5H20M7 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                            </svg>
                        </div>
                    </div>
                    <span class="ui-about-standard-card__tag">Vehicle Integrity</span>
                    <h3 class="ui-about-standard-card__title">Late-Model &amp; Deep-Cleaned Fleet</h3>
                    <p class="ui-about-standard-card__text">
                        Every Sedan (4 seats), SUV/MPV (7 seats), and Minivan (16 seats) is under 4 years old
                        (2022–2025). Thoroughly sanitized before each pickup, equipped with ice-cold air conditioning,
                        bottled spring water, and phone charging docks.
                    </p>
                    <div class="ui-about-standard-card__badge">
                        <span class="dot"></span>
                        <span>Daily 18-Point Mechanical Inspection</span>
                    </div>
                </div>

                <!-- Pillar 2: Professional Chauffeurs -->
                <div class="ui-about-standard-card">
                    <div class="ui-about-standard-card__top">
                        <span class="ui-about-standard-card__num">02</span>
                        <div class="ui-about-standard-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M12 14l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <span class="ui-about-standard-card__tag">Chauffeur Etiquette</span>
                    <h3 class="ui-about-standard-card__title">Polite English-Speaking Chauffeurs</h3>
                    <p class="ui-about-standard-card__text">
                        Our drivers are seasoned professionals vetted for road safety, neat appearance, and polite
                        English communication. They drive smoothly, obey all traffic regulations, and respect whether
                        you prefer local commentary or quiet relaxation.
                    </p>
                    <div class="ui-about-standard-card__badge">
                        <span class="dot"></span>
                        <span>Strict Background &amp; Defensive Driving Vetting</span>
                    </div>
                </div>

                <!-- Pillar 3: Transparent Pricing -->
                <div class="ui-about-standard-card">
                    <div class="ui-about-standard-card__top">
                        <span class="ui-about-standard-card__num">03</span>
                        <div class="ui-about-standard-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="5" width="20" height="14" rx="2" />
                                <line x1="2" y1="10" x2="22" y2="10" />
                            </svg>
                        </div>
                    </div>
                    <span class="ui-about-standard-card__tag">Fair &amp; Honest</span>
                    <h3 class="ui-about-standard-card__title">All-Inclusive Transparent Flat Rates</h3>
                    <p class="ui-about-standard-card__text">
                        The price quoted is the exact total you pay. Highway tolls, fuel costs, parking fees, driver
                        expenses, and passenger insurance are all included. Zero meter haggling, zero peak-hour surges,
                        and zero hidden checkout charges.
                    </p>
                    <div class="ui-about-standard-card__badge">
                        <span class="dot"></span>
                        <span>100% Fixed Rates • No Hidden Fees</span>
                    </div>
                </div>

                <!-- Pillar 4: Risk-Free Booking -->
                <div class="ui-about-standard-card">
                    <div class="ui-about-standard-card__top">
                        <span class="ui-about-standard-card__num">04</span>
                        <div class="ui-about-standard-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                <path d="M9 12l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <span class="ui-about-standard-card__tag">Peace of Mind</span>
                    <h3 class="ui-about-standard-card__title">Zero Prepayment &amp; Free Cancellation</h3>
                    <p class="ui-about-standard-card__text">
                        Reserve your vehicle in less than 2 minutes without credit card deposits. Pay your driver
                        directly in cash (VND or USD) or by credit card/transfer upon trip completion. Enjoy 100% free
                        cancellation up to 24 hours before pickup.
                    </p>
                    <div class="ui-about-standard-card__badge">
                        <span class="dot"></span>
                        <span>Free 24h Cancellation • Pay on Arrival</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         5. OPERATIONAL METRICS: Executive Performance Dashboard
         ====================================================================== -->
    <section class="ui-about-metrics" data-purpose="metrics-dashboard">
        <div class="container">
            <div class="ui-about-metrics__panel">
                <div class="ui-about-metrics__header">
                    <span class="ui-about-badge ui-about-badge--light">Verified Operational Milestones</span>
                    <h2 class="ui-about-metrics__title">Delivering Dependability on Every Road Journey</h2>
                    <p class="ui-about-metrics__desc">
                        Behind every smooth private transfer is an experienced operations team committed to precision,
                        safety, and round-the-clock responsiveness.
                    </p>
                </div>

                <div class="ui-about-metrics__grid">
                    <!-- Metric 1 -->
                    <div class="ui-about-metric-card">
                        <div class="ui-about-metric-card__value">
                            <span class="num">10</span><span class="unit">+</span>
                        </div>
                        <h3 class="ui-about-metric-card__title">Years in Operation</h3>
                        <p class="ui-about-metric-card__text">
                            A trusted brand under VM Travel with continuous operations across Central Vietnam.
                        </p>
                    </div>

                    <!-- Metric 2 -->
                    <div class="ui-about-metric-card">
                        <div class="ui-about-metric-card__value">
                            <span class="num">50k</span><span class="unit">+</span>
                        </div>
                        <h3 class="ui-about-metric-card__title">Passengers Transported</h3>
                        <p class="ui-about-metric-card__text">
                            International vacationers, families, and business executives traveling with complete
                            confidence.
                        </p>
                    </div>

                    <!-- Metric 3 -->
                    <div class="ui-about-metric-card">
                        <div class="ui-about-metric-card__value">
                            <span class="num">99.8</span><span class="unit">%</span>
                        </div>
                        <h3 class="ui-about-metric-card__title">On-Time Arrival Rate</h3>
                        <p class="ui-about-metric-card__text">
                            Tracked performance record across thousands of airport pickups and intercity transfers.
                        </p>
                    </div>

                    <!-- Metric 4 -->
                    <div class="ui-about-metric-card">
                        <div class="ui-about-metric-card__value">
                            <span class="num">&lt;5</span><span class="unit">min</span>
                        </div>
                        <h3 class="ui-about-metric-card__title">WhatsApp Response Time</h3>
                        <p class="ui-about-metric-card__text">
                            24/7 dedicated English-speaking concierge desk ready to modify or assist with your booking.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         6. HOW IT WORKS: Effortless 4-Step Booking Flow
         ====================================================================== -->
    <section class="ui-about-steps" data-purpose="how-it-works">
        <div class="container">
            <div class="ui-about-steps__header">
                <span class="ui-about-badge">Transparent &amp; Simple</span>
                <h2 class="ui-about-steps__title">How Your Journey Begins</h2>
                <p class="ui-about-steps__desc">
                    Book your private ride in under 2 minutes with zero upfront payment complications.
                </p>
            </div>

            <div class="ui-about-steps__grid">
                <!-- Step 1 -->
                <div class="ui-about-step-card">
                    <div class="ui-about-step-card__icon-wrap">
                        <div class="ui-about-step-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>
                        <span class="ui-about-step-card__step-num">01</span>
                    </div>
                    <h3 class="ui-about-step-card__title">Select Route &amp; Car</h3>
                    <p class="ui-about-step-card__text">
                        Choose your pickup location, destination, date, and preferred vehicle (4-seat Sedan, 7-seat SUV,
                        or 16-seat Minivan).
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="ui-about-step-card">
                    <div class="ui-about-step-card__icon-wrap">
                        <div class="ui-about-step-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        <span class="ui-about-step-card__step-num">02</span>
                    </div>
                    <h3 class="ui-about-step-card__title">Get Instant Confirmation</h3>
                    <p class="ui-about-step-card__text">
                        Receive a transparent all-inclusive quote. Confirm your schedule via WhatsApp or email with no
                        advance payment required.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="ui-about-step-card">
                    <div class="ui-about-step-card__icon-wrap">
                        <div class="ui-about-step-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <span class="ui-about-step-card__step-num">03</span>
                    </div>
                    <h3 class="ui-about-step-card__title">Meet Your Chauffeur</h3>
                    <p class="ui-about-step-card__text">
                        Your driver greets you punctually at the airport arrival terminal or hotel lobby holding a
                        personalized nameboard.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="ui-about-step-card">
                    <div class="ui-about-step-card__icon-wrap">
                        <div class="ui-about-step-card__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 14 14" />
                            </svg>
                        </div>
                        <span class="ui-about-step-card__step-num">04</span>
                    </div>
                    <h3 class="ui-about-step-card__title">Relax &amp; Pay Later</h3>
                    <p class="ui-about-step-card__text">
                        Enjoy a cool, tranquil ride. Pay the driver directly in cash (VND/USD) or by card/transfer upon
                        arrival at your destination.
                    </p>
                </div>
            </div>

            <!-- Quick WhatsApp Action Strip -->
            <div class="ui-about-steps__strip">
                <span class="text">Need a custom multi-day itinerary or special route request?</span>
                <a href="<?= esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" class="btn-link"
                    aria-label="Chat with our concierge on WhatsApp">
                    <span>Chat with Our Concierge</span>
                    <span class="arrow">➔</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         7. TRAVELER REPUTATION: Real Feedback from Global Guests
         ====================================================================== -->
    <section class="ui-about-reviews" data-purpose="guest-reviews">
        <div class="container">
            <div class="ui-about-reviews__header">
                <span class="ui-about-badge">Verified Traveler Experiences</span>
                <h2 class="ui-about-reviews__title">What Our Guests Say</h2>
                <div class="ui-about-reviews__summary">
                    <span class="stars">★★★★★</span>
                    <span class="text">Rated <strong>4.9/5.0</strong> based on 1,450+ verified reviews on TripAdvisor
                        &amp; Google Reviews</span>
                </div>
            </div>

            <div class="ui-about-reviews__grid">
                <!-- Review 1 -->
                <div class="ui-about-review-card">
                    <div class="ui-about-review-card__top">
                        <div class="stars">★★★★★</div>
                        <span class="source-badge">TripAdvisor</span>
                    </div>
                    <p class="ui-about-review-card__quote">
                        “We booked an SUV from Da Nang airport to Hoi An and a day trip to Ba Na Hills. Our driver, Mr.
                        Nam, was exceptionally polite, arrived 15 minutes early with a clear sign, and drove very
                        smoothly. Outstanding service!”
                    </p>
                    <div class="ui-about-review-card__author">
                        <img src="<?= content_url('/uploads/2026/11/avatar-002-300x163.jpg'); ?>"
                            alt="Michael &amp; Sarah Jenkins" class="avatar" />
                        <div class="meta">
                            <h4>Michael &amp; Sarah Jenkins</h4>
                            <span>Melbourne, Australia • Family Vacation</span>
                        </div>
                    </div>
                </div>

                <!-- Review 2 (Featured) -->
                <div class="ui-about-review-card ui-about-review-card--featured">
                    <div class="ui-about-review-card__top">
                        <div class="stars">★★★★★</div>
                        <span class="source-badge source-badge--verified">Verified Booking</span>
                    </div>
                    <p class="ui-about-review-card__quote">
                        “The coastal drive via Hai Van Pass to Hue was the highlight of our Vietnam vacation. Spotless
                        car, freezing cold AC, and our driver stopped at all the best photo viewpoints without rushing
                        us once. Worth every penny.”
                    </p>
                    <div class="ui-about-review-card__author">
                        <img src="<?= content_url('/uploads/2026/11/avatar-003-150x150.jpg'); ?>" alt="David Chen"
                            class="avatar" />
                        <div class="meta">
                            <h4>David Chen</h4>
                            <span>Singapore • Hai Van Pass Scenic Transfer</span>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="ui-about-review-card">
                    <div class="ui-about-review-card__top">
                        <div class="stars">★★★★★</div>
                        <span class="source-badge">Google Reviews</span>
                    </div>
                    <p class="ui-about-review-card__quote">
                        “Completely transparent pricing with zero surprise charges. Our flight was delayed by an hour,
                        but our driver tracked the flight and waited right outside the terminal with a warm smile.
                        Highly recommended!”
                    </p>
                    <div class="ui-about-review-card__author">
                        <div class="avatar-placeholder">EW</div>
                        <div class="meta">
                            <h4>Emma Watson-Smith</h4>
                            <span>London, UK • DAD Airport Transfer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================================
         8. CLOSING CTA: Reserve Your Chauffeur With Confidence
         ====================================================================== -->
    <section class="ui-about-cta" data-purpose="closing-cta">
        <div class="container">
            <div class="ui-about-cta__card">
                <div class="ui-about-cta__bg-glow" aria-hidden="true"></div>

                <div class="ui-about-cta__grid">
                    <div class="ui-about-cta__copy">
                        <span class="ui-about-badge ui-about-badge--gold">Ready for a Seamless Journey?</span>
                        <h2 class="ui-about-cta__title">Experience Central Vietnam With Complete Peace of Mind</h2>
                        <p class="ui-about-cta__desc">
                            No upfront deposit required • 100% free cancellation up to 24 hours • Professional
                            English-speaking local drivers waiting at your arrival.
                        </p>
                        <div class="ui-about-cta__trust-items">
                            <div class="item"><span>✓</span> Instant WhatsApp Confirmation</div>
                            <div class="item"><span>✓</span> All-Inclusive Fixed Quotes</div>
                            <div class="item"><span>✓</span> 24/7 Flight Tracking Included</div>
                        </div>
                    </div>

                    <div class="ui-about-cta__actions">
                        <a href="<?= esc_url($cars_url); ?>" class="ui-about-cta__btn ui-about-cta__btn--primary">
                            <span>Browse Vehicle Fleet</span>
                            <span class="arrow">➔</span>
                        </a>

                        <a href="<?= esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer"
                            class="ui-about-cta__btn ui-about-cta__btn--whatsapp"
                            aria-label="Chat with our team on WhatsApp">
                            <svg class="icon-whatsapp" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M21 11.5a8.38 8.38 0 0 1-9 8.5 8.5 8.5 0 0 1-4.4-1.2L3 20l1.2-4.5A8.5 8.5 0 1 1 21 11.5Z" />
                                <path d="M8 10.5c.5 2 2 3.5 4 4" />
                            </svg>
                            <span>Chat on WhatsApp (24/7)</span>
                        </a>

                        <?php if (!empty($phone)): ?>
                            <div class="ui-about-cta__hotline">
                                <span>Or call our direct 24/7 helpline:</span>
                                <a href="tel:<?= esc_attr(preg_replace('/\s+/', '', $phone)); ?>">
                                    <strong><?= esc_html($phone); ?></strong>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
<?php get_footer(); ?>