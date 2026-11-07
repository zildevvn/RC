<?php
$heading = get_field('heading_faq_home');
$badge = get_field('badge_faq_home');
$sub_heading = get_field('desc_faq_home');
$faqs = get_field('faqs_home');
?>
<section class="rc-section faqs-section bg-white" data-purpose="faq-section">
    <div aria-hidden="true" class="rc-section__bg faqs-section__bg d-flex align-items-center justify-content-end">
        <svg fill="none" stroke="currentColor" style="opacity: 0.15;" viewBox="0 0 400 400"
            xmlns="http://www.w3.org/2000/svg">
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
    <div class="container">
        <div class="faqs-section__grid">
            <!-- Left Column: Sticky/Prominent Info & Help Card -->
            <div class="faqs-section__left">
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
                <!-- Still have questions? Contact Card -->
                <div class="faqs-section__contact w-100">
                    <div
                        class="faqs-section__contact-icon d-flex align-items-center justify-content-center flex-shrink-0">
                        <svg fill="none" stroke-width="1.8" viewBox="0 0 24 24">
                            <path
                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75v3.75a3 3 0 01-3 3h-1.5a1.5 1.5 0 01-1.5-1.5v-3a1.5 1.5 0 011.5-1.5H21A8.25 8.25 0 003.75 12h3.75a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5H6a3 3 0 01-3-3V12z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h3 class="h5">Need immediate assistance?</h3>
                    <p class="faqs-section__contact-desc">
                        Our 24/7 customer support team is ready to help via WhatsApp or hotline with custom
                        itineraries and quick bookings.
                    </p>
                    <div class="faqs-section__contact-meta d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="faqs-section__dot faqs-section__dot--amber fw-bold">•</span>
                            <span>Hotline: <strong>+84 820 334 533</strong></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="faqs-section__dot faqs-section__dot--emerald fw-bold">•</span>
                            <span>Response time: <strong>&lt; 5 mins</strong></span>
                        </div>
                    </div>
                    <a class="rc-button" href="https://wa.me/" rel="noopener noreferrer" target="_blank">
                        Chat with Us on WhatsApp
                        <?php echo rc_svg_icon('arrow_next'); ?>
                    </a>
                </div>
            </div>
            <!-- Right Column: Accordion List -->
            <?php if (!empty($faqs) && is_array($faqs)): ?>
                <div class="faqs-section__right rc-faqs d-flex flex-column w-100">
                    <?php
                    $faq_index = 0;
                    foreach ($faqs as $key => $value):
                        $question = isset($value['question']) ? trim((string) $value['question']) : '';
                        $answer = isset($value['answer']) && $value['answer'] !== '' ? $value['answer'] : (isset($value['anwer']) ? $value['anwer'] : '');
                        $answer = is_string($answer) ? trim($answer) : '';

                        if ($question === '' || $answer === '') {
                            continue;
                        }

                        $is_active = ($faq_index === 0);
                        $faq_index++;
                        ?>
                        <div class="rc-faqs__item<?= $is_active ? ' is-active' : '' ?>">
                            <button aria-expanded="<?= $is_active ? 'true' : 'false' ?>"
                                class="rc-faqs__question w-100 text-start d-flex align-items-center justify-content-between"
                                type="button">
                                <span class="rc-faqs__question-text">
                                    <?= esc_html($question) ?>
                                </span>
                                <span aria-hidden="true"
                                    class="rc-faqs__toggle d-flex align-items-center justify-content-center flex-shrink-0">
                                    <svg class="rc-faqs__icon-plus" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <svg class="rc-faqs__icon-minus" fill="none" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M5 12h14" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </button>
                            <div class="rc-faqs__answer">
                                <div class="rc-faqs__answer-inner">
                                    <?= wp_kses_post(wpautop($answer)) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>