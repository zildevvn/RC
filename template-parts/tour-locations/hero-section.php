<?php
$term = get_queried_object();
$image = get_field('image', $term);
$title = !empty($term->name) ? $term->name : '';
$desc = !empty($term->description) ? $term->description : '';
rc_hero_section_shared($title, $image, $desc, [
    'badge' => 'Destinations & Locations • Central Vietnam',
]);
?>