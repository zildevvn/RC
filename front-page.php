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



    <section class="relative bg-white py-24 px-6 overflow-hidden" data-purpose="faq-section"
        style="background-color: rgb(255, 255, 255);">
        <div aria-hidden="true"
            class="absolute inset-0 pointer-events-none select-none z-0 overflow-hidden flex items-center justify-end pr-4 lg:pr-12">
            <svg class="w-[420px] h-[420px] text-[#0F4C81]" fill="none" stroke="currentColor" style="opacity: 0.15;"
                viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                <circle cx="200" cy="200" r="185" stroke-width="2.5"></circle>
                <circle cx="200" cy="200" r="172" stroke-dasharray="3 3" stroke-width="1.5"></circle>
                <circle cx="200" cy="200" r="150" stroke-width="1.5"></circle>
                <circle cx="200" cy="200" r="115" stroke-dasharray="4 6" stroke-width="1.2"></circle>
                <circle cx="200" cy="200" r="80" stroke-width="1.5"></circle>
                <circle cx="200" cy="200" r="32" stroke-width="1.5"></circle>
                <line stroke-width="2" x1="200" x2="200" y1="10" y2="390"></line>
                <line stroke-width="2" x1="10" x2="390" y1="200" y2="200"></line>
                <line stroke-dasharray="5 5" stroke-width="1.2" x1="65" x2="335" y1="65" y2="335"></line>
                <line stroke-dasharray="5 5" stroke-width="1.2" x1="65" x2="335" y1="335" y2="65"></line>
                <polygon fill="currentColor" points="200,24 212,185 200,200"></polygon>
                <polygon fill="none" points="200,24 188,185 200,200" stroke-width="1.5"></polygon>
                <polygon fill="currentColor" points="200,376 188,215 200,200"></polygon>
                <polygon fill="none" points="200,376 212,215 200,200" stroke-width="1.5"></polygon>
                <polygon fill="currentColor" points="376,200 215,188 200,200"></polygon>
                <polygon fill="none" points="376,200 215,212 200,200" stroke-width="1.5"></polygon>
                <polygon fill="currentColor" points="24,200 185,212 200,200"></polygon>
                <polygon fill="none" points="24,200 185,188 200,200" stroke-width="1.5"></polygon>
                <polygon fill="currentColor" points="324,76 210,190 200,200" style="opacity: 0.6;"></polygon>
                <polygon fill="currentColor" points="76,324 190,210 200,200" style="opacity: 0.6;"></polygon>
                <polygon fill="currentColor" points="324,324 210,210 200,200" style="opacity: 0.6;"></polygon>
                <polygon fill="currentColor" points="76,76 190,190 200,200" style="opacity: 0.6;"></polygon><text
                    fill="currentColor" font-family="sans-serif" font-size="20" font-weight="bold" stroke="none"
                    text-anchor="middle" x="200" y="46">N</text><text fill="currentColor" font-family="sans-serif"
                    font-size="20" font-weight="bold" stroke="none" text-anchor="middle" x="200" y="370">S</text><text
                    fill="currentColor" font-family="sans-serif" font-size="20" font-weight="bold" stroke="none"
                    text-anchor="middle" x="370" y="207">E</text><text fill="currentColor" font-family="sans-serif"
                    font-size="20" font-weight="bold" stroke="none" text-anchor="middle" x="30" y="207">W</text>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <!-- Left Column: Sticky/Prominent Info & Help Card -->
                <div class="lg:col-span-5 flex flex-col items-start">
                    <span
                        class="text-xs font-bold uppercase tracking-widest text-[#0F4C81] bg-blue-50 px-3.5 py-1.5 rounded-full inline-block mb-3 border border-blue-100">
                        COMMON INQUIRIES
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-4 tracking-tight"
                        style="font-family: &quot;Cormorant Garamond&quot;, serif; color: rgb(25, 35, 43);">
                        Frequently Asked Questions
                    </h2>
                    <p class="text-slate-600 text-base leading-relaxed mb-8">
                        Find quick answers to common questions about our private car rental, drivers, pricing, and
                        booking policies.
                    </p>
                    <!-- Still have questions? Contact Card -->
                    <div class="w-full bg-slate-50 border border-slate-200/90 p-7 rounded-2xl shadow-sm">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100/60 text-[#0F4C81] flex items-center justify-center mb-5 shrink-0">
                            <svg class="w-6 h-6 stroke-current" fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                                <path
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75v3.75a3 3 0 01-3 3h-1.5a1.5 1.5 0 01-1.5-1.5v-3a1.5 1.5 0 011.5-1.5H21A8.25 8.25 0 003.75 12h3.75a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5H6a3 3 0 01-3-3V12z"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg mb-2">Need immediate assistance?</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            Our 24/7 customer support team is ready to help via WhatsApp or hotline with custom
                            itineraries and quick bookings.
                        </p>
                        <div class="space-y-2 mb-6 text-xs text-slate-600 font-medium">
                            <div class="flex items-center gap-2">
                                <span class="text-amber-500 font-bold">•</span>
                                <span class="">Hotline: <strong class="text-slate-900">+84 820 334 533</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-emerald-600 font-bold">•</span>
                                <span class="">Response time: <strong class="text-slate-900">&lt; 5 mins</strong></span>
                            </div>
                        </div>
                        <a class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-colors text-sm"
                            href="https://wa.me/" rel="noopener noreferrer" target="_blank">
                            <span class="">Chat with Us on WhatsApp</span>
                            <span class="font-bold">➔</span>
                        </a>
                    </div>
                </div>
                <!-- Right Column: Accordion List -->
                <div class="lg:col-span-7 flex flex-col w-full">
                    <!-- Accordion Item 1 (Active / Expanded) -->
                    <div
                        class="rounded-2xl border border-blue-400/80 bg-blue-50/20 shadow-md ring-1 ring-blue-500/10 mb-4 overflow-hidden transition-all duration-200">
                        <button
                            class="w-full p-6 text-left flex items-center justify-between gap-4 cursor-pointer focus:outline-none"
                            type="button">
                            <span class="font-bold text-base md:text-lg text-[#0F4C81]">
                                Are fuel, road tolls, and parking fees included in the price?
                            </span>
                            <span
                                class="shrink-0 w-8 h-8 rounded-full bg-[#0F4C81] text-white flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M5 12h14" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </button>
                        <div class="px-6 pb-6 pt-0 border-t border-blue-100/60 mt-2">
                            <p class="text-slate-600 text-sm md:text-base leading-relaxed pt-3">
                                Yes, 100% all-inclusive. All quoted prices for our private transfers and day tours
                                include driver fees, modern air-conditioned vehicle, gasoline, highway tolls, and
                                airport parking. There are no hidden surcharges or unexpected extras.
                            </p>
                        </div>
                    </div>
                    <!-- Accordion Item 2 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white mb-4 shadow-sm overflow-hidden hover:border-slate-300 transition-all duration-200">
                        <button
                            class="w-full p-6 text-left flex items-center justify-between gap-4 cursor-pointer focus:outline-none"
                            type="button">
                            <span class="font-bold text-base md:text-lg text-slate-900">
                                How will I meet my driver at Da Nang International Airport (DAD)?
                            </span>
                            <span
                                class="shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <div class="px-6 pb-6 pt-0 border-t border-slate-100 mt-2">
                            <p class="text-slate-600 text-sm md:text-base leading-relaxed pt-3">
                                Your driver will monitor your flight number in real-time and wait at the designated
                                arrival hall (Column 3-4 for Domestic, or Gate A/B for International) holding a
                                personalized sign with your full name. You will also receive the driver's phone number
                                and car plate via WhatsApp before landing.
                            </p>
                        </div>
                    </div>
                    <!-- Accordion Item 3 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white mb-4 shadow-sm overflow-hidden hover:border-slate-300 transition-all duration-200">
                        <button
                            class="w-full p-6 text-left flex items-center justify-between gap-4 cursor-pointer focus:outline-none"
                            type="button">
                            <span class="font-bold text-base md:text-lg text-slate-900">
                                What happens if my flight is delayed or rescheduled?
                            </span>
                            <span
                                class="shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <div class="px-6 pb-6 pt-0 border-t border-slate-100 mt-2">
                            <p class="text-slate-600 text-sm md:text-base leading-relaxed pt-3">
                                No worries at all! We track all inbound flights continuously. Even if your flight is
                                delayed by several hours, your pickup schedule automatically adjusts at zero extra cost,
                                and free 60-minute waiting time is included after touch-down.
                            </p>
                        </div>
                    </div>
                    <!-- Accordion Item 4 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white mb-4 shadow-sm overflow-hidden hover:border-slate-300 transition-all duration-200">
                        <button
                            class="w-full p-6 text-left flex items-center justify-between gap-4 cursor-pointer focus:outline-none"
                            type="button">
                            <span class="font-bold text-base md:text-lg text-slate-900">
                                Can we customize stops along the route (e.g., Marble Mountains or Hai Van Pass)?
                            </span>
                            <span
                                class="shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <div class="px-6 pb-6 pt-0 border-t border-slate-100 mt-2">
                            <p class="text-slate-600 text-sm md:text-base leading-relaxed pt-3">
                                Absolutely. Our private transfers give you complete freedom. You can easily add scenic
                                detours like the Marble Mountains, Linh Ung Pagoda, or photo stops at Lap An Lagoon.
                                Just inform us during booking or let your driver know.
                            </p>
                        </div>
                    </div>
                    <!-- Accordion Item 5 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white mb-4 shadow-sm overflow-hidden hover:border-slate-300 transition-all duration-200">
                        <button
                            class="w-full p-6 text-left flex items-center justify-between gap-4 cursor-pointer focus:outline-none"
                            type="button">
                            <span class="font-bold text-base md:text-lg text-slate-900">
                                What is your cancellation and payment policy?
                            </span>
                            <span
                                class="shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <div class="px-6 pb-6 pt-0 border-t border-slate-100 mt-2">
                            <p class="text-slate-600 text-sm md:text-base leading-relaxed pt-3">
                                We offer 100% free cancellation up to 24 hours prior to your scheduled pickup. Best of
                                all, you don't need to pay in advance—simply pay the driver directly in cash (VND or
                                USD) or by credit card upon trip completion.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Find Answers for Questions (Interactive FAQ) -->
    <!-- BEGIN: Section 10: Explore Latest News (Travel Insights & Blog Articles) -->
    <section class="relative bg-slate-50 py-24 px-6" data-purpose="blog-section"
        style="background-color: rgb(242, 243, 245);">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <span
                        class="text-xs font-semibold tracking-widest uppercase text-[#0F4C81] bg-blue-100/60 px-3.5 py-1 rounded-full inline-block">
                        TRAVEL GUIDES &amp; INSIGHTS
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3 tracking-tight"
                        style="font-family: &quot;Cormorant Garamond&quot;, serif; color: rgb(25, 35, 43);">
                        Explore Latest News &amp; Guides
                    </h2>
                    <p class="text-slate-500 max-w-2xl mt-2 text-base leading-relaxed">
                        Tips, itineraries, and local insights to make your journey through Da Nang and Central Vietnam
                        unforgettable.
                    </p>
                </div>
                <a class="text-[#0F4C81] font-semibold text-sm hover:underline flex items-center gap-1.5 transition-all mt-4 md:mt-0"
                    href="#blog">
                    <span class="">View All Articles</span>
                    <span class="">➔</span>
                </a>
            </div>
            <!-- 3-Card Article Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 (Hoi An Night Travel Guide) -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col group cursor-pointer">
                    <div class="relative overflow-hidden aspect-[16/10]">
                        <img alt="Hoi An Lantern River Canal"
                            class="group-hover:scale-105 transition-transform duration-500 w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuD22TFL5relu_2YiPytrPwMcqrtnkPrkjsNX8fzh9YyGKa8HTFtxrTP3EvmV9tL_LhYVdZREGecgM0XEZhhVewfX8y15cizvYgpkpiKytnfg_Rax1ly9jrCrRcqf8HaJMeD5HGr9l_1x8JuI4yrfzhMz2ZR8pggNJeijLY_DGUR_6wrqdbYADn33lkrEZfCvdwYTzqxRDjFmtBtkP38cxR_kI443SnG06YCgEBrIVc">
                        <span
                            class="absolute top-4 left-4 bg-[#0F4C81] text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                            Travel Guide
                        </span>
                    </div>
                    <div class="text-xs text-slate-400 mt-5 px-6 flex items-center gap-4">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 stroke-current" fill="none" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                <line x1="3" x2="21" y1="10" y2="10"></line>
                            </svg>
                            Oct 15, 2026
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 stroke-current" fill="none" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            5 min read
                        </span>
                    </div>
                    <h3
                        class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors mt-2 px-6 line-clamp-2">
                        How to Travel from Da Nang to Hoi An: Private Car vs Grab vs Bus
                    </h3>
                    <p class="text-slate-500 text-sm mt-3 px-6 leading-relaxed line-clamp-2">
                        A complete comparison of costs, travel times, and convenience to help you pick the best ride for
                        your family.
                    </p>
                    <div class="mt-auto">
                        <span
                            class="text-blue-600 font-semibold text-sm mt-5 mb-6 px-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Read Full Article ➔
                        </span>
                    </div>
                </div>
                <!-- Card 2 (Scenic Coastal Drive Guide) -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col group cursor-pointer">
                    <div class="relative overflow-hidden aspect-[16/10]">
                        <img alt="Hai Van Pass Overlooking Lang Co Bay"
                            class="group-hover:scale-105 transition-transform duration-500 w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1chHjhzlf7S64ngkrhaD_glRXJONhk0s0nIPICh5ocO4sXHFgQ0I_XVikFsG_bWoG_8faDoV3fcM7AZ465SWgeWHPBCExObejGVzrIsr8G7AFddJ3lXKm7vPbWQAJPhafGz13XPCLygmOgOqGBoIXaufhf7rdQT3z4bQk5ScSW3_Xh0C5KDTGYkc6VdpsqMW143fUciTVF9iJfWeNSmTejLDZnI4V7eCWjBMQKHo">
                        <span
                            class="absolute top-4 left-4 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                            Road Trip
                        </span>
                    </div>
                    <div class="text-xs text-slate-400 mt-5 px-6 flex items-center gap-4">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 stroke-current" fill="none" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                <line x1="3" x2="21" y1="10" y2="10"></line>
                            </svg>
                            Oct 20, 2026
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 stroke-current" fill="none" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            7 min read
                        </span>
                    </div>
                    <h3
                        class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors mt-2 px-6 line-clamp-2">
                        Top 5 Must-Visit Scenic Stops Along the Hai Van Pass Route
                    </h3>
                    <p class="text-slate-500 text-sm mt-3 px-6 leading-relaxed line-clamp-2">
                        From historic French bunkers to secret seafood cafes on Lap An Lagoon, don't miss these scenic
                        highlights.
                    </p>
                    <div class="mt-auto">
                        <span
                            class="text-blue-600 font-semibold text-sm mt-5 mb-6 px-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Read Full Article ➔
                        </span>
                    </div>
                </div>
                <!-- Card 3 (Travel Tips & Budget Guide) -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col group cursor-pointer">
                    <div class="relative overflow-hidden aspect-[16/10]">
                        <img alt="Modern Car at Da Nang Airport"
                            class="group-hover:scale-105 transition-transform duration-500 w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjAg20z3lonnXH_Ch-H0R8rLXzbxXrIVx1aQoW2dkw6NLhAaxttvTSsDLGbu1zcR_ip8pjtVN_pR0tcmeiS6pv9Lp4LCtFAEW1uW9R3ufpsfUZJymf5M4rAToWRgtx2YFrhRghqOx8L4h12B4f8g9pjFF7_Ed2qslvDvWg3scWS3evZl_-MChv3WlL1SIkI_0KL2P6RxlitNR7IX1YXYkcK2ZoU05GRK0yie5HVVc">
                        <span
                            class="absolute top-4 left-4 bg-emerald-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                            Car Rental Tips
                        </span>
                    </div>
                    <div class="text-xs text-slate-400 mt-5 px-6 flex items-center gap-4">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 stroke-current" fill="none" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect height="18" rx="2" ry="2" width="18" x="3" y="4"></rect>
                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                <line x1="3" x2="21" y1="10" y2="10"></line>
                            </svg>
                            Oct 24, 2026
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 stroke-current" fill="none" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            4 min read
                        </span>
                    </div>
                    <h3
                        class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors mt-2 px-6 line-clamp-2">
                        Everything You Need to Know About Renting a Private Car in Da Nang
                    </h3>
                    <p class="text-slate-500 text-sm mt-3 px-6 leading-relaxed line-clamp-2">
                        Understanding transparent pricing, driver tipping culture, child seat availability, and free
                        airport waiting times.
                    </p>
                    <div class="mt-auto">
                        <span
                            class="text-blue-600 font-semibold text-sm mt-5 mb-6 px-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Read Full Article ➔
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- BEGIN: Final Call to Action Banner -->
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