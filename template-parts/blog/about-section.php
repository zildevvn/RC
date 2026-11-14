<?php
/**
 * Template part for displaying the About section on the Blog Template
 *
 * Designed for Rent Car Da Nang - Private Chauffeur & Mobility Services.
 * Retrieves ACF fields (hd_ab_blog_tpl, badge_ab_blog_tpl, desc_ab_blog_tpl, image_ab_blog_tpl)
 * and delegates rendering to the shared editorial about section component.
 *
 * @package rc
 */

$heading = $args['heading'] ?? get_field('hd_ab_blog_tpl');
$badge   = $args['badge']   ?? get_field('badge_ab_blog_tpl');
$desc    = $args['desc']    ?? ($args['description'] ?? get_field('desc_ab_blog_tpl'));
$image   = $args['image']   ?? get_field('image_ab_blog_tpl');

get_template_part('template-parts/shared/about-editorial', null, [
    'heading'    => $heading,
    'badge'      => $badge,
    'desc'       => $desc,
    'image'      => $image,
    'section_id' => $args['section_id'] ?? 'blog-about-section',
    'title_id'   => $args['title_id'] ?? 'blog-about-title',
]);
