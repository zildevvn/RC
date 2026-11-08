<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$about_text = get_field('about_text_ft', 'option');
$address = get_field('address', 'option');
$email = get_field('email', 'option');
$phone = get_field('phone', 'option');
$copyright = get_field('copyright_ft', 'option');
$socials = get_field('socials', 'option');
$bg_footer = get_field('background_image', 'option');
?>

<footer class="main-footer" data-purpose="site-footer">
    <div class="container">
        <div class="main-footer__grid">
            <div class="d-flex flex-column">
                <a class="d-flex align-items-center gap-3 mb-4 text-decoration-none" href="#">
                    <div class="main-footer__logo-badge d-flex align-items-center justify-content-center">
                        <svg fill="none" viewBox="0 0 48 48">
                            <circle class="opacity-40" cx="24" cy="24" r="22" stroke="currentColor"
                                stroke-dasharray="2 2" stroke-width="1.5"></circle>
                            <path d="M12 28C16 25 24 25 28 28C32 31 36 29 40 27" stroke="white" stroke-linecap="round"
                                stroke-width="2"></path>
                            <path class="opacity-90" d="M16 24L19 18H29L32 24H16Z" fill="white"></path>
                            <circle cx="20" cy="24" fill="#0F4C81" r="2.5"></circle>
                            <circle cx="28" cy="24" fill="#0F4C81" r="2.5"></circle>
                            <path d="M20 14L28 10L26 14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5"></path>
                        </svg>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="main-footer__brand-title">Rent Car <span class="main-footer__brand-accent">Da Nang</span></span>
                        <span class="main-footer__brand-sub">rentcardanang.com</span>
                    </div>
                </a>
                <p class="main-footer__about">A proud brand of VM Travel. Providing premier
                    private chauffeur car rental services across Da Nang, Hoi An, Ba Na Hills, and Central Vietnam with modern
                    vehicles and English-speaking drivers.</p>
                <div class="d-flex align-items-center gap-3">
                    <a aria-label="Facebook" class="main-footer__social-link d-flex align-items-center justify-content-center" href="#">
                        <svg viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                        </svg>
                    </a>
                    <a aria-label="X Twitter" class="main-footer__social-link d-flex align-items-center justify-content-center" href="#">
                        <svg viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                        </svg>
                    </a>
                    <a aria-label="TripAdvisor" class="main-footer__social-link d-flex align-items-center justify-content-center" href="#">
                        <svg viewBox="0 0 24 24">
                            <circle cx="6.5" cy="13.5" r="2.5"></circle>
                            <circle cx="17.5" cy="13.5" r="2.5"></circle>
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8 0-1.85.63-3.55 1.69-4.9L12 13.5l6.31-6.4C19.37 8.45 20 10.15 20 12c0 4.41-3.59 8-8 8z"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <h3 class="main-footer__heading">Popular Routes</h3>
                <ul class="main-footer__links list-unstyled">
                    <li><a class="main-footer__link" href="#">Da Nang Airport to Hoi An</a></li>
                    <li><a class="main-footer__link" href="#">Da Nang to Ba Na Hills &amp; Golden Bridge</a></li>
                    <li><a class="main-footer__link" href="#">Da Nang to Hue via Hai Van Pass</a></li>
                    <li><a class="main-footer__link" href="#">Da Nang City Tour &amp; Marble Mountains</a></li>
                    <li><a class="main-footer__link" href="#">Hoi An to My Son Sanctuary</a></li>
                    <li><a class="main-footer__link" href="#">Da Nang to InterContinental Son Tra</a></li>
                </ul>
            </div>
            <div>
                <h3 class="main-footer__heading">Help &amp; Information</h3>
                <ul class="main-footer__links list-unstyled">
                    <li><a class="main-footer__link" href="#about">About VM Travel</a></li>
                    <li><a class="main-footer__link" href="#">Our Modern Fleet &amp; Pricing</a></li>
                    <li><a class="main-footer__link" href="#">Airport Pickup Guide (DAD)</a></li>
                    <li><a class="main-footer__link" href="#">Cancellation &amp; Refund Policy</a></li>
                    <li><a class="main-footer__link" href="#faq">Frequently Asked Questions</a></li>
                    <li><a class="main-footer__link" href="#contact">Customer Support 24/7</a></li>
                </ul>
            </div>
            <div>
                <h3 class="main-footer__heading">Contact &amp; Bookings</h3>
                <div class="main-footer__contact">
                    <div class="d-flex align-items-start gap-2">
                        <svg fill="none" stroke-width="2" viewBox="0 0 24 24" class="mt-1">
                            <path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path>
                        </svg>
                        <span>Da Nang Office: An Thuong Area, Ngu Hanh Son, Da Nang, Vietnam</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <svg fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <a class="main-footer__contact-link fw-bold" href="tel:+84820334533">+84 820 334 533</a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <svg fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                        </svg>
                        <span>booking@rentcardanang.com</span>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="main-footer__payment-title d-block mb-2">Secure Payment Accepted</span>
                    <div class="main-footer__payment-badges d-flex align-items-center gap-2 flex-wrap">
                        <span class="main-footer__payment-badge">Cash VND/USD</span>
                        <span class="main-footer__payment-badge">Visa / Master</span>
                        <span class="main-footer__payment-badge">Bank Transfer</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-footer__bottom d-flex flex-column flex-sm-row align-items-center justify-content-between">
            <p>© 2026 Rent Car Da Nang — A Member of VM Travel Group. All rights reserved.</p>
            <div class="main-footer__bottom-links d-flex align-items-center">
                <a class="main-footer__bottom-link" href="#">Privacy Policy</a>
                <a class="main-footer__bottom-link" href="#">Terms of Service</a>
                <a class="main-footer__bottom-link" href="#">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
