<?php
/**
 * Shared FAQ Section Component
 *
 * Reused across Homepage, Tour Listing (Cars) page, etc.
 * Retrieves content dynamically from passed $args, page-specific ACF fields, or Homepage ACF fallback.
 *
 * @package rc
 */

$page_id = !empty($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$front_page_id = (int) get_option('page_on_front');

// 1. Resolve FAQs List
$faqs = $args['faqs'] ?? null;
if (empty($faqs)) {
    // Try page-specific Cars repeater field (from group_6a5f227085a70.json)
    $faqs_car = get_field('faqs_ab_car_tpl', $page_id);
    if (!empty($faqs_car) && is_array($faqs_car)) {
        $faqs = $faqs_car;
    }
}
if (empty($faqs)) {
    // Try current page's faqs_home field
    $faqs_page = get_field('faqs_home', $page_id);
    if (!empty($faqs_page) && is_array($faqs_page)) {
        $faqs = $faqs_page;
    }
}
if (empty($faqs) && $front_page_id && $front_page_id !== $page_id) {
    // Fall back to Homepage FAQs
    $faqs_home = get_field('faqs_home', $front_page_id);
    if (!empty($faqs_home) && is_array($faqs_home)) {
        $faqs = $faqs_home;
    }
}

// 2. Resolve Heading
$heading = $args['heading'] ?? '';
if (empty($heading)) {
    $heading = get_field('heading_faq_home', $page_id);
}
if (empty($heading) && $front_page_id && $front_page_id !== $page_id) {
    $heading = get_field('heading_faq_home', $front_page_id);
}
if (empty($heading)) {
    $heading = __('Find Answers for Questions You Have.', 'rc');
}

// 3. Resolve Badge
$badge = $args['badge'] ?? '';
if (empty($badge)) {
    $badge = get_field('badge_faq_home', $page_id);
}
if (empty($badge) && $front_page_id && $front_page_id !== $page_id) {
    $badge = get_field('badge_faq_home', $front_page_id);
}
if (empty($badge)) {
    $badge = __('FAQs', 'rc');
}

// 4. Resolve Sub-heading / Description
$sub_heading = $args['sub_heading'] ?? '';
if (empty($sub_heading)) {
    $sub_heading = get_field('desc_faq_home', $page_id);
}
if (empty($sub_heading) && $front_page_id && $front_page_id !== $page_id) {
    $sub_heading = get_field('desc_faq_home', $front_page_id);
}

// 5. Contact info
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
            <div class="faqs-section__left" data-aos="fade-up">
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
                    <a class="rc-button" href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>"
                        rel="noopener noreferrer" target="_blank" aria-label="Chat with us on WhatsApp">
                        Chat with Us on WhatsApp
                        <?php echo rc_svg_icon('arrow_next'); ?>
                    </a>
                </div>
            </div>
            <!-- Right Column: Accordion List -->
            <?php if (!empty($faqs) && is_array($faqs)): ?>
                <div class="faqs-section__right rc-faqs d-flex flex-column w-100" data-aos="fade-up">
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