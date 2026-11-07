<?php
$heading = get_field('heading_stb_home');
$badge = get_field('badge_stb_home');
$sub_heading = get_field('desc_stb_home');

?>
<section class="rc-section steps-booking-section bg-white" data-purpose="easy-steps-booking">
    <div aria-hidden="true"
        class="rc-section__bg steps-booking-section__bg d-flex align-items-center justify-content-center">
        <svg height="100%" style="opacity: 0.055;" width="100%"
            xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern height="32" id="booking-dot-grid" patternUnits="userSpaceOnUse" width="32">
                    <circle cx="3" cy="3" fill="currentColor" r="1.5"></circle>
                </pattern>
            </defs>
            <rect fill="url(#booking-dot-grid)" height="100%" width="100%"></rect>
        </svg>
    </div>
    <div class="container">
        <!-- 1. Section Header -->
        <div class="steps-booking-section__header text-center mx-auto">
            <?php if (!empty($badge)): ?>
                <p class="rc-badge">
                    <?= $badge ?>
                </p>
            <?php endif; ?>

            <h2 class="h2">
                <?= $heading ?>
            </h2>

            <?php if (!empty($sub_heading)): ?>
                <p class="ha-desc">
                    <?= $sub_heading ?>
                </p>
            <?php endif; ?>
        </div>
        <!-- 2. 4-Step Process Flow -->
        <div class="steps-booking-section__flow position-relative">
            <!-- Horizontal Dashed Connecting Line (visible on desktop) -->
            <div aria-hidden="true"
                class="steps-booking-section__line d-none d-lg-block">
                <div class="steps-booking-section__line-bar"></div>
                <svg fill="none" preserveAspectRatio="none" viewBox="0 0 800 16">
                    <line stroke="#0F4C81" stroke-dasharray="8 8" stroke-width="3" style="opacity: 0.65;" x1="0"
                        x2="800" y1="8" y2="8"></line>
                </svg>
            </div>
            <div class="steps-booking-section__grid">
                <!-- Step 1 -->
                <div class="steps-booking-section__step d-flex flex-column align-items-center text-center">
                    <div class="steps-booking-section__icon-wrap position-relative">
                        <div class="steps-booking-section__icon">
                            <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                <path
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9 14h6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <span class="steps-booking-section__num">
                            01
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6">Select Route &amp; Vehicle</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Choose your pickup point, destination, date, and preferred car type (4-seater Sedan,
                        7-seater SUV, or 16-seater Minivan).
                    </p>
                </div>
                <!-- Step 2 -->
                <div class="steps-booking-section__step d-flex flex-column align-items-center text-center">
                    <div class="steps-booking-section__icon-wrap position-relative">
                        <div class="steps-booking-section__icon steps-booking-section__icon--amber">
                            <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                <path
                                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.879 2.674.43l3.355-1.677a2.25 2.25 0 001.244-2.012V13.82c0-.596-.237-1.17-.659-1.591L11.16 3.659A2.25 2.25 0 009.568 3z"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M6 6h.008v.008H6V6z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <span class="steps-booking-section__num steps-booking-section__num--amber">
                            02
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6">Get Instant Confirmation</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Receive an upfront all-inclusive quote with no hidden fees. Confirm your booking via
                        WhatsApp or email within minutes.
                    </p>
                </div>
                <!-- Step 3 -->
                <div class="steps-booking-section__step d-flex flex-column align-items-center text-center">
                    <div class="steps-booking-section__icon-wrap position-relative">
                        <div class="steps-booking-section__icon">
                            <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                <path
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <span class="steps-booking-section__num">
                            03
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6">Meet Your Driver</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Your friendly English-speaking driver greets you on time at the airport terminal or hotel
                        lobby with a personalized nameboard.
                    </p>
                </div>
                <!-- Step 4 -->
                <div class="steps-booking-section__step d-flex flex-column align-items-center text-center">
                    <div class="steps-booking-section__icon-wrap position-relative">
                        <div class="steps-booking-section__icon steps-booking-section__icon--emerald">
                            <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                <path
                                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <span class="steps-booking-section__num steps-booking-section__num--emerald">
                            04
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6">Enjoy &amp; Pay Later</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Sit back and enjoy a smooth, air-conditioned ride. Pay the driver directly in cash (VND/USD)
                        or credit card upon trip completion.
                    </p>
                </div>
            </div>
        </div>
        <!-- 3. Bottom Quick Action Strip -->
        <div
            class="steps-booking-section__cta d-flex flex-column flex-sm-row align-items-center justify-content-between mx-auto">
            <span class="steps-booking-section__cta-text text-center text-sm-start">
                Need custom multi-day itinerary or special route?
            </span>
            <a class="steps-booking-section__cta-btn pill-shaped d-inline-flex align-items-center flex-shrink-0"
                href="https://wa.me/" rel="noopener noreferrer" target="_blank">
                <span>Chat on WhatsApp Now</span>
                <span class="fw-bold">➔</span>
            </a>
        </div>
    </div>
</section>
<!-- END: Easy Steps For Booking Section -->