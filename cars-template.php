<?php
/**
 * Template Name: Cars
 */
get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/cars/hero-section'); ?>
    <?php get_template_part('template-parts/cars/about-section'); ?>
    <?php get_template_part('template-parts/cars/filter-tours-section'); ?>
    <?php get_template_part('template-parts/cars/faqs-section'); ?>
    <?php get_template_part('template-parts/cars/banner-section'); ?>
</main>
<?php get_footer(); ?>