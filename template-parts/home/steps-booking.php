<?php
$heading = get_field('heading_stb_home');
$badge = get_field('badge_stb_home');
$sub_heading = get_field('desc_stb_home');
$phone = function_exists('get_field') ? get_field('phone', 'option') : '';
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
?>
<section class="ui-section steps-booking-section bg-white relative overflow-hidden" data-purpose="easy-steps-booking">
    <div aria-hidden="true"
        class="ui-section__bg steps-booking-section__bg absolute inset-0 z-0 pointer-events-none select-none flex items-center justify-center">
        <?php echo rc_svg_icon('steps_bg'); ?>
    </div>
    <div class="container relative z-10">
        <!-- 1. Section Header -->
        <div class="steps-booking-section__header max-w-2xl mb-16 text-center mx-auto" data-aos="fade-up">
            <?php if (!empty($badge)): ?>
                <p class="ui-badge">
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
        <div class="steps-booking-section__flow relative" data-aos="fade-up">
            <!-- Horizontal Dashed Connecting Line (visible on desktop) -->
            <div aria-hidden="true" class="steps-booking-section__line absolute top-7 left-[12%] right-[12%] h-1 z-0 pointer-events-none hidden lg:block">
                <div class="steps-booking-section__line-bar"></div>
                <?php echo rc_svg_icon('steps_line'); ?>
            </div>
            <div class="steps-booking-section__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6 relative z-10">
                <!-- Step 1 -->
                <div class="steps-booking-section__step flex flex-col items-center text-center group">
                    <div class="steps-booking-section__icon-wrap relative">
                        <div class="steps-booking-section__icon w-14 h-14 rounded-2xl flex items-center justify-center p-3 shadow-sm border transition-transform duration-300 group-hover:scale-105">
                            <?php echo rc_svg_icon('step_calendar'); ?>
                        </div>
                        <span class="steps-booking-section__num absolute -top-2.5 -right-2.5 w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center shadow-md">
                            01
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6 mt-5 mb-0">Select Route &amp; Vehicle</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Choose your pickup point, destination, date, and preferred car type (4-seater Sedan,
                        7-seater SUV, or 16-seater Minivan).
                    </p>
                </div>
                <!-- Step 2 -->
                <div class="steps-booking-section__step flex flex-col items-center text-center group">
                    <div class="steps-booking-section__icon-wrap relative">
                        <div class="steps-booking-section__icon steps-booking-section__icon--amber w-14 h-14 rounded-2xl flex items-center justify-center p-3 shadow-sm border transition-transform duration-300 group-hover:scale-105">
                            <?php echo rc_svg_icon('step_tag'); ?>
                        </div>
                        <span class="steps-booking-section__num steps-booking-section__num--amber absolute -top-2.5 -right-2.5 w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center shadow-md">
                            02
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6 mt-5 mb-0">Get Instant Confirmation</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Receive an upfront all-inclusive quote with no hidden fees. Confirm your booking via
                        WhatsApp or email within minutes.
                    </p>
                </div>
                <!-- Step 3 -->
                <div class="steps-booking-section__step flex flex-col items-center text-center group">
                    <div class="steps-booking-section__icon-wrap relative">
                        <div class="steps-booking-section__icon w-14 h-14 rounded-2xl flex items-center justify-center p-3 shadow-sm border transition-transform duration-300 group-hover:scale-105">
                            <?php echo rc_svg_icon('step_driver'); ?>
                        </div>
                        <span class="steps-booking-section__num absolute -top-2.5 -right-2.5 w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center shadow-md">
                            03
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6 mt-5 mb-0">Meet Your Driver</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Your friendly English-speaking driver greets you on time at the airport terminal or hotel
                        lobby with a personalized nameboard.
                    </p>
                </div>
                <!-- Step 4 -->
                <div class="steps-booking-section__step flex flex-col items-center text-center group">
                    <div class="steps-booking-section__icon-wrap relative">
                        <div class="steps-booking-section__icon steps-booking-section__icon--emerald w-14 h-14 rounded-2xl flex items-center justify-center p-3 shadow-sm border transition-transform duration-300 group-hover:scale-105">
                            <?php echo rc_svg_icon('step_shield'); ?>
                        </div>
                        <span class="steps-booking-section__num steps-booking-section__num--emerald absolute -top-2.5 -right-2.5 w-6 h-6 rounded-full text-white text-xs font-bold flex items-center justify-center shadow-md">
                            04
                        </span>
                    </div>
                    <h3 class="steps-booking-section__step-title h6 mt-5 mb-0">Enjoy &amp; Pay Later</h3>
                    <p class="steps-booking-section__step-desc mt-2 mb-0">
                        Sit back and enjoy a smooth, air-conditioned ride. Pay the driver directly in cash (VND/USD)
                        or credit card upon trip completion.
                    </p>
                </div>
            </div>
        </div>
        <!-- 3. Bottom Quick Action Strip -->
        <div
            class="steps-booking-section__cta flex flex-col sm:flex-row items-center justify-between mx-auto mt-16 rounded-2xl p-6 gap-4 max-w-2xl shadow-sm">
            <span class="steps-booking-section__cta-text text-center sm:text-start text-sm sm:text-base font-medium">
                Need custom multi-day itinerary or special route?
            </span>
            <a class="steps-booking-section__cta-btn pill-shaped inline-flex items-center gap-2 shrink-0 py-2.5 px-6 rounded-full text-sm font-medium shadow-md transition-colors"
                href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>" rel="noopener noreferrer"
                target="_blank" aria-label="Chat with us on WhatsApp">
                <span>Chat on WhatsApp Now</span>
                <span class="fw-bold">➔</span>
            </a>
        </div>
    </div>
</section>
<!-- END: Easy Steps For Booking Section -->