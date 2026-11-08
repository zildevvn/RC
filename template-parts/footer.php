<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$about_text = get_field('about_text_ft', 'option');
$address = get_field('address', 'option');
$email = get_field('email', 'option');
$phone = get_field('phone', 'option');
$copyright = get_field('copyright_ft', 'option');
?>

<footer class="main-footer"
    data-purpose="site-footer">
    <div class="container">
        <div class="main-footer__grid">
            <!-- Col 1: Brand/Logo (lg:col-span-4) -->
            <div class="main-footer__col main-footer__col--brand">
                <?php if (!empty($logo_url)): ?>
                    <a class="main-footer__logo"
                        href="<?php echo esc_url(home_url('/')); ?>"
                        aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                            width="200" height="54" />
                    </a>
                <?php endif; ?>
                <?php if (!empty($about_text)): ?>
                    <p class="main-footer__about">
                        <?php echo wp_kses_post($about_text); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Col 2: Tours (lg:col-span-2) -->
            <div class="main-footer__col main-footer__col--tours">
                <h3 class="main-footer__heading">
                    Tours
                </h3>
                <?php if (has_nav_menu('tours-menu')): ?>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'tours-menu',
                        'container' => false,
                        'menu_class' => 'main-footer__links',
                        'fallback_cb' => false,
                    ));
                    ?>
                <?php endif; ?>
            </div>

            <!-- Col 3: Company (lg:col-span-2) -->
            <div class="main-footer__col main-footer__col--company">
                <h3 class="main-footer__heading">
                    Company
                </h3>
                <?php if (has_nav_menu('company-menu')): ?>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'company-menu',
                        'container' => false,
                        'menu_class' => 'main-footer__links',
                        'fallback_cb' => false,
                    ));
                    ?>
                <?php endif; ?>
            </div>

            <!-- Col 4: Contact & Bookings (lg:col-span-4) -->
            <div class="main-footer__col main-footer__col--contact">
                <h3 class="main-footer__heading">
                    Contact &amp; Bookings
                </h3>
                <div class="main-footer__contact">
                    <?php if (!empty($address)): ?>
                        <div class="main-footer__contact-item main-footer__contact-item--address">
                            <span class="main-footer__contact-icon">
                                <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                            <span class="main-footer__contact-text"><?php echo esc_html($address); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($phone)): ?>
                        <?php $whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : ''; ?>
                        <div class="main-footer__contact-item">
                            <span class="main-footer__contact-icon">
                                <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                            <?php if (!empty($whatsapp_url)): ?>
                                <a class="main-footer__contact-link main-footer__contact-link--phone"
                                    href="<?php echo esc_url($whatsapp_url); ?>" target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="Chat with us on WhatsApp"><?php echo esc_html($phone); ?></a>
                            <?php else: ?>
                                <span class="main-footer__contact-link main-footer__contact-link--phone"><?php echo esc_html($phone); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($email)): ?>
                        <div class="main-footer__contact-item">
                            <span class="main-footer__contact-icon">
                                <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                            <a class="main-footer__contact-link main-footer__contact-link--email"
                                href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Secure Payment Accepted Block -->
                <div class="main-footer__payment">
                    <span class="main-footer__payment-title">
                        Secure Payment Accepted
                    </span>
                    <div class="main-footer__payment-badges">
                        <span class="main-footer__payment-badge">
                            Cash VND/USD
                        </span>
                        <span class="main-footer__payment-badge">
                            Visa / Master
                        </span>
                        <span class="main-footer__payment-badge">
                            Bank Transfer
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright Bar -->
        <div class="main-footer__bottom">
            <?php if (!empty($copyright)): ?>
                <p><?php echo wp_kses_post($copyright); ?></p>
            <?php endif; ?>
        </div>
    </div>
</footer>