<?php
$heading = get_field('heading_faq_home');
$badge = get_field('badge_faq_home');
$sub_heading = get_field('desc_faq_home');
$faqs = get_field('faqs_home');
$phone = function_exists('get_field') ? get_field('phone', 'option') : '';
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
?>
<section class="rc-section faqs-section bg-white" data-purpose="faq-section">
    <div aria-hidden="true" class="rc-section__bg faqs-section__bg d-flex align-items-center justify-content-end">
        <?php echo rc_svg_icon('faqs_bg'); ?>
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
                        <?php echo rc_svg_icon('headset'); ?>
                    </div>
                    <h3 class="h5">Need immediate assistance?</h3>
                    <p class="faqs-section__contact-desc">
                        Our 24/7 customer support team is ready to help via WhatsApp or hotline with custom
                        itineraries and quick bookings.
                    </p>
                    <div class="faqs-section__contact-meta d-flex flex-column gap-2">
                        <?php if (!empty($phone)): ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="faqs-section__dot faqs-section__dot--amber fw-bold">•</span>
                                <span>Hotline: <strong><?= esc_html($phone) ?></strong></span>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="faqs-section__dot faqs-section__dot--emerald fw-bold">•</span>
                            <span>Response time: <strong>&lt; 5 mins</strong></span>
                        </div>
                    </div>
                    <a class="rc-button"
                        href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>"
                        rel="noopener noreferrer" target="_blank"
                        aria-label="Chat with us on WhatsApp">
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
                                    <?php echo rc_svg_icon('faq_plus'); ?>
                                    <?php echo rc_svg_icon('faq_minus'); ?>
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