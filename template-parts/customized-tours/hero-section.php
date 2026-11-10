<?php
$title = get_the_title();
$image = get_the_post_thumbnail_url();
$desc = get_the_content();
rc_hero_section_shared($title, $image, $desc, [
    'button_url'  => '#form-customize-tour',
    'button_text' => 'Customize Tour Now',
    'badge'       => 'Tailor-Made Itinerary',
]);
?>
