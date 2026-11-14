<?php
/**
 * Tour Locations Taxonomy - FAQs Section
 *
 * Standardizes the FAQs section in `taxonomy-tour_locations.php` by reusing
 * the project-standard FAQ component (`template-parts/home/faqs-section`).
 *
 * Guarantees 100% component structure, layout, typography, accordion styling,
 * ARIA accessibility, and interaction behavior consistency with the rest of the site.
 *
 * Content is strictly derived from the current taxonomy term's ACF repeater `faqs_list_location`.
 *
 * @package rc
 */

$term = get_queried_object();
if (!$term || !isset($term->term_id)) {
    return;
}

// 1. Retrieve taxonomy-specific FAQs from ACF field `faqs_list_location`
$faqs = get_field('faqs_list_location', $term);

// 2. Validate: preserve existing conditional rendering (only output when valid FAQs exist)
if (empty($faqs) || !is_array($faqs)) {
    return;
}

$valid_faqs = array_filter($faqs, function ($item) {
    $q = isset($item['question']) ? trim((string) $item['question']) : '';
    $a = isset($item['answer']) ? trim((string) $item['answer']) : (isset($item['anwer']) ? trim((string) $item['anwer']) : '');
    return ($q !== '' && $a !== '');
});

if (empty($valid_faqs)) {
    return;
}

// 3. Resolve Taxonomy-Specific Headings & Badge
$term_name = !empty($term->name) ? $term->name : '';

$badge = get_field('badge_faq_location', $term);
if (empty($badge)) {
    $badge = !empty($term_name)
        ? sprintf(__('FAQs • %s', 'rc'), $term_name)
        : __('Frequently Asked Questions', 'rc');
}

$heading = get_field('heading_faq_location', $term);
if (empty($heading)) {
    $heading = !empty($term_name)
        ? sprintf(__('Frequently Asked Questions', 'rc'), '')
        : __('Find Answers for Questions You Have.', 'rc');
}

$sub_heading = get_field('desc_faq_location', $term);
if (empty($sub_heading)) {
    $sub_heading = !empty($term_name)
        ? sprintf(__('Find quick answers regarding travel itineraries, private car transfers, and sightseeing advice in %s.', 'rc'), $term_name)
        : __('Find quick answers regarding travel itineraries, private car transfers, and sightseeing advice.', 'rc');
}

// 4. Delegate rendering to the project-wide standard FAQ component
get_template_part('template-parts/home/faqs-section', null, [
    'faqs' => $valid_faqs,
    'badge' => $badge,
    'heading' => $heading,
    'sub_heading' => $sub_heading,
    'post_id' => 0, // Ensure term FAQs are not overridden by post/homepage fallbacks
]);
