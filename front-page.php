<?php
/**
 * Template Name: Homepage
 * Front Page Template
 */

get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/home/hero-section'); ?>
    <?php get_template_part('template-parts/home/service-section'); ?>
    <?php get_template_part('template-parts/home/why-choose-section'); ?>
    <?php get_template_part('template-parts/home/destinations-section'); ?>
    <?php get_template_part('template-parts/home/tours-section'); ?>
    <?php get_template_part('template-parts/home/steps-booking'); ?>
    <?php get_template_part('template-parts/home/testimonials-section'); ?>
    <?php get_template_part('template-parts/home/faqs-section'); ?>
    <?php get_template_part('template-parts/home/posts-section'); ?>




    <section class="relative bg-white py-16 px-6" data-purpose="final-cta-section" style="background-color: #FFFFFF;">
        <div class="max-w-7xl mx-auto">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl py-16 px-8 md:px-16 bg-gradient-to-r from-black/90 to-transparent"
                style="background: linear-gradient(135deg, #0A3258 0%, #0F4C81 100%);">
                <div aria-hidden="true" class="absolute inset-0 pointer-events-none select-none z-0 overflow-hidden">
                    <svg class="w-full h-full text-cyan-200" fill="none" preserveAspectRatio="none"
                        style="opacity: 0.25;" viewBox="0 0 1000 400" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 250 430 C 480 310, 680 160, 1050 120" stroke="#FFFFFF" stroke-width="3.5"></path>
                        <path d="M 310 430 C 530 320, 730 170, 1050 150" stroke="#38BDF8" stroke-width="2.5"></path>
                        <path d="M 380 430 C 590 330, 780 180, 1050 180" stroke="#F59E0B" stroke-dasharray="10 10"
                            stroke-width="2.5"></path>
                        <path d="M 450 430 C 650 340, 830 190, 1050 210" stroke="#FFFFFF" stroke-width="1.8"></path>
                        <path d="M 180 -40 C 380 70, 680 270, 1050 270" stroke="#38BDF8" stroke-width="3"></path>
                        <path d="M 120 -40 C 320 80, 620 290, 1050 300" stroke="#FFFFFF" stroke-dasharray="8 8"
                            stroke-width="2"></path>
                        <polygon fill="url(#cta-headlight-beam)" points="1000,120 400,280 400,340 1000,220"
                            style="opacity: 0.5;"></polygon>
                        <defs>
                            <linearGradient id="cta-headlight-beam" x1="1" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.6"></stop>
                                <stop offset="60%" stop-color="#FFFFFF" stop-opacity="0.2"></stop>
                                <stop offset="100%" stop-color="#38BDF8" stop-opacity="0"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <!-- Subtle decorative accent glows -->
                <div class="absolute -right-2.5 -top-2.5 w-72 h-72 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"
                    style="filter: blur(60px);"></div>
                <div class="absolute -left-10 -bottom-10 w-64 h-64 rounded-full bg-blue-400/10 blur-2xl pointer-events-none"
                    style="filter: blur(50px);"></div>
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Left Side: Copy and Value Propositions -->
                    <div class="lg:col-span-7 flex flex-col items-start">
                        <div
                            class="text-amber-400 font-semibold tracking-widest text-xs uppercase mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span class="">READY FOR A HASSLE-FREE TRIP?</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white leading-tight tracking-tight"
                            style="font-family: &quot;Cormorant Garamond&quot;, serif;">
                            Book Your Private Da Nang Ride in Just a Few Clicks
                        </h2>
                        <p class="text-slate-200 text-base mt-4 max-w-xl leading-relaxed">
                            No prepayment required • Free cancellation up to 24 hours • Professional English-speaking
                            drivers waiting at your arrival.
                        </p>
                        <!-- Trust Badges Row -->
                        <div class="text-white/90 text-sm mt-6 flex flex-wrap gap-x-6 gap-y-2">
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-amber-400 font-bold text-base">✓</span>
                                <span class="">Instant Confirmation</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-amber-400 font-bold text-base">✓</span>
                                <span class="">Fixed All-Inclusive Rates</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-amber-400 font-bold text-base">✓</span>
                                <span class="">24/7 Flight Tracking</span>
                            </div>
                        </div>
                    </div>
                    <!-- Right Side: Dual CTA Buttons and Direct Helpline -->
                    <div class="lg:col-span-5 flex flex-col justify-center w-full">
                        <div class="flex flex-col gap-4 w-full max-w-md mx-auto lg:mr-0">
                            <!-- Primary Action Button -->
                            <a class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-4 rounded-xl shadow-lg transition-all text-center flex items-center justify-center gap-2 text-base cursor-pointer"
                                href="#book" style="background-color: rgb(223, 161, 40);">
                                <span class="">Book Your Car Now</span>
                                <span class="font-bold">➔</span>
                            </a>
                            <!-- WhatsApp Action Button -->
                            <a class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg transition-all text-center flex items-center justify-center gap-2 text-base cursor-pointer"
                                href="https://wa.me/" rel="noopener noreferrer" style="background-color: #25D366;"
                                target="_blank">
                                <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path
                                        d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.668-.699c.969.54 1.761.82 2.79.82 3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.766-5.766zm9.969 5.766c0 5.503-4.468 9.969-9.969 9.969-1.758 0-3.414-.462-4.858-1.267l-5.173 1.36 1.385-5.048c-.901-1.488-1.423-3.23-1.423-5.014 0-5.503 4.468-9.969 9.969-9.969 5.501 0 10.069 4.466 10.069 9.969z">
                                    </path>
                                </svg>
                                <span class="">Chat on WhatsApp</span>
                            </a>
                            <!-- Helpline -->
                            <div
                                class="text-xs text-slate-300 text-center mt-2 flex items-center justify-center gap-1.5 flex-wrap">
                                <span class="">Or call our 24/7 hotline directly:</span>
                                <a class="font-bold text-white hover:text-amber-400 inline-flex items-center gap-1 transition-colors"
                                    href="tel:+84820334533">
                                    <svg class="w-3.5 h-3.5 stroke-current text-amber-400" fill="none" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span class="">+84 820 334 533</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>