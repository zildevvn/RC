<?php
$title = get_the_title();
if (empty($title) || $title === 'About') {
    $title = 'About Rent Car Da Nang';
}
$image = get_the_post_thumbnail_url();
$desc = function_exists('get_field') ? get_field('desc_hero_ab_tpl') : '';
if (empty($desc)) {
    $desc = 'Discover our commitment to premier mobility, professional English-speaking chauffeurs, and effortless private journeys across Central Vietnam.';
}
rc_hero_section_shared($title, $image, $desc, [
    'badge' => 'About Our Company',
]);
