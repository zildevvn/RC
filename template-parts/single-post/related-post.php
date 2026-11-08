<?php

$current_post_id = get_the_ID();

$args = [
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'post__not_in' => [$current_post_id],
    'orderby' => 'rand',
];

$the_query = new WP_Query($args);

?>

<?php if ($the_query->have_posts()): ?>
    <section class="vm-section related-post-section">
        <div class="container">
            <?php vm_icon_heading() ?>

            <div class="related-post-section__header d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                <div>
                    <h2 class="vm-heading mb-2">
                        Related <span>articles</span>
                    </h2>
                    <p class="vm-sub-heading mb-0">Explore our Phu Quoc Island Tours to discover the best beaches and attractions.</p>
                </div>
                <div class="rc-related-nav d-none d-md-flex align-items-center gap-2 flex-shrink-0">
                    <button type="button" class="rc-swiper-prev swiper-prev" aria-label="Previous articles">
                        <?php echo rc_svg_icon('lightbox_prev'); ?>
                    </button>
                    <button type="button" class="rc-swiper-next swiper-next" aria-label="Next articles">
                        <?php echo rc_svg_icon('lightbox_next'); ?>
                    </button>
                </div>
            </div>

            <div class="related-post-section__list rc-related-carousel swiper">
                <div class="swiper-wrapper">
                    <?php
                    while ($the_query->have_posts()):
                        $the_query->the_post();
                        ?>
                        <div class="swiper-slide">
                            <?php vm_post_item(); ?>
                        </div>
                        <?php
                    endwhile;

                    wp_reset_postdata();
                    ?>
                </div>
                <div class="swiper-pagination rc-related-pagination d-md-none mt-3"></div>
            </div>

        </div>
    </section>
<?php endif; ?>