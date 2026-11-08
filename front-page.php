<?php
/**
 * Template Name: Homepage
 * Front Page Template
 */

get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/home/hero-section'); ?>
    <?php get_template_part('template-parts/home/service-section'); ?>
    <?php get_template_part('template-parts/home/why-choose-section'); ?>
    <?php get_template_part('template-parts/home/destinations-section'); ?>
    <?php get_template_part('template-parts/home/tours-section'); ?>
    <?php get_template_part('template-parts/home/steps-booking'); ?>
    <?php get_template_part('template-parts/home/testimonials-section'); ?>
    <?php get_template_part('template-parts/home/faqs-section'); ?>
    <?php get_template_part('template-parts/home/posts-section'); ?>
    <?php get_template_part('template-parts/home/banner-section'); ?>
</main>
<?php get_footer(); ?>