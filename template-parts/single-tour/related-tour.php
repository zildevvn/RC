<?php

$current_post_id = get_the_ID();

$current_tour_cats = wp_get_post_terms(
    $current_post_id,
    'tour_cats',
    [
        'fields' => 'names',
    ]
);

$args = [
    'post_type' => 'tours',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'post__not_in' => [$current_post_id],
    'orderby' => 'rand',
];

if (!is_wp_error($current_tour_cats) && !empty($current_tour_cats)) {
    $current_tour_cat_ids = wp_get_post_terms(
        $current_post_id,
        'tour_cats',
        [
            'fields' => 'ids',
        ]
    );

    $args['tax_query'] = [
        [
            'taxonomy' => 'tour_cats',
            'field' => 'term_id',
            'terms' => $current_tour_cat_ids,
        ],
    ];
}

$the_query = new WP_Query($args);

$heading = 'Other Tours';

if (is_array($current_tour_cats) && !empty($current_tour_cats)) {
    $current_tour_cats = array_filter(array_map('trim', $current_tour_cats));

    if (!empty($current_tour_cats)) {
        $heading = 'Other <span>' . implode(' & ', $current_tour_cats) . '</span>';
    }
}
?>

<?php if ($the_query->have_posts()): ?>
    <section class="rc-section related-tour-section">
        <div class="container">

            <div
                class="related-tour-section__header d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                <div>
                    <h2 class="rc-heading mb-2">
                        <?php echo $heading; ?>
                    </h2>
                    <p class="rc-sub-heading mb-0">Explore with Rent Car Da Nang and discover the region's best beaches and
                        iconic attractions.</p>
                </div>
                <div class="rc-related-nav d-none d-md-flex align-items-center gap-2 flex-shrink-0">
                    <button type="button" class="rc-swiper-prev swiper-prev" aria-label="Previous tours">
                        <?php echo rc_svg_icon('lightbox_prev'); ?>
                    </button>
                    <button type="button" class="rc-swiper-next swiper-next" aria-label="Next tours">
                        <?php echo rc_svg_icon('lightbox_next'); ?>
                    </button>
                </div>
            </div>

            <div class="related-tour-section__list rc-related-carousel swiper">
                <div class="swiper-wrapper">
                    <?php
                    while ($the_query->have_posts()):
                        $the_query->the_post();
                        ?>
                        <div class="swiper-slide">
                            <?php rc_item_tour(); ?>
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