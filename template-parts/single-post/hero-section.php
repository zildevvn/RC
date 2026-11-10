<?php
$title = get_the_title();
$image = get_the_post_thumbnail_url();
rc_hero_section_shared($title, $image, '', [
    'badge'        => 'Travel Article',
    'show_ratings' => true,
]);
?>