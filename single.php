<?php
/**
 * The template for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package rc
 */

get_header();
?>
<main id="primary" class="site-main single-post-page">
    <?php while (have_posts()): the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?> itemscope itemtype="https://schema.org/BlogPosting">
            <meta itemprop="mainEntityOfPage" content="<?php the_permalink(); ?>">
            <meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('c')); ?>">
            <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">

            <?php get_template_part('template-parts/single-post/hero-section'); ?>
            <?php get_template_part('template-parts/single-post/main-section'); ?>
        </article>
    <?php endwhile; ?>

    <?php get_template_part('template-parts/single-post/related-post'); ?>
</main>
<?php
get_footer();
