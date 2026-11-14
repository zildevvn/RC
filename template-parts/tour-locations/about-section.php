<?php
/**
 * Template part for displaying the About section on Tour Locations taxonomy
 *
 * Reuses the Editorial About Section component from blog-template.php.
 * Retrieves ACF fields from the queried term (about_section_location: heading, badge, description, image)
 * with authentic fallbacks.
 *
 * @package rc
 */

$term     = get_queried_object();
$about_ss = get_field('about_section_location', $term);

$heading = !empty($about_ss['heading']) ? $about_ss['heading'] : '';
$badge   = !empty($about_ss['badge']) ? $about_ss['badge'] : (!empty($about_ss['sub_heading']) ? $about_ss['sub_heading'] : '');
$desc    = !empty($about_ss['description']) ? $about_ss['description'] : '';
$image   = !empty($about_ss['image']) ? $about_ss['image'] : [];

// Fallbacks from term object if ACF group fields are empty
$term_name = !empty($term->name) ? $term->name : '';
if (empty($heading) && !empty($term_name)) {
    $heading = sprintf(__('Discover %s with Rent Car Da Nang', 'rc'), $term_name);
}
if (empty($badge)) {
    $badge = !empty($term_name) ? sprintf(__('About %s', 'rc'), $term_name) : __('About Destination', 'rc');
}
if (empty($desc) && !empty($term->description)) {
    $desc = wpautop($term->description);
}
if (empty($image)) {
    $term_image = get_field('image', $term);
    if (!empty($term_image)) {
        $image = $term_image;
    }
}

get_template_part('template-parts/shared/about-editorial', null, [
    'heading'    => $heading,
    'badge'      => $badge,
    'desc'       => $desc,
    'image'      => $image,
    'section_id' => 'tour-location-about-section',
    'title_id'   => 'tour-location-about-title',
]);
