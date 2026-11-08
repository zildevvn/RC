<?php
$heading = get_field('heading_stb_home');
$badge = get_field('badge_stb_home');
$sub_heading = get_field('desc_stb_home');
$phone = function_exists('get_field') ? get_field('phone', 'option') : '';
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
?>
<section class="rc-section steps-booking-section bg-white" data-purpose="easy-steps-booking">
    <div aria-hidden="true"
        class="rc-section__bg steps-booking-section__bg d-flex align-items-center justify-content-center">
        <?php echo rc_svg_icon('steps_bg'); ?>
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
                <?php echo rc_svg_icon('steps_line'); ?>
            </div>
            <div class="steps-booking-section__grid">
                <!-- Step 1 -->
                <div class="steps-booking-section__step d-flex flex-column align-items-center text-center">
                    <div class="steps-booking-section__icon-wrap position-relative">
                        <div class="steps-booking-section__icon">
                            <?php echo rc_svg_icon('step_calendar'); ?>
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
                            <?php echo rc_svg_icon('step_tag'); ?>
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
                            <?php echo rc_svg_icon('step_driver'); ?>
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
                            <?php echo rc_svg_icon('step_shield'); ?>
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
                href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>"
                rel="noopener noreferrer" target="_blank"
                aria-label="Chat with us on WhatsApp">
                <span>Chat on WhatsApp Now</span>
                <span class="fw-bold">➔</span>
            </a>
        </div>
    </div>
</section>
<!-- END: Easy Steps For Booking Section -->