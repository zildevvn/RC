<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}
// ₫ : VND
// $ : USD
// € : EUR

$heading = get_field('heading_tours_home');
$badge = get_field('badge_tours_home');
$sub_heading = get_field('desc_tours_home');

$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 6,
    'post_status' => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="ui-section tours-section" data-purpose="popular-tours-transfers">
        <div aria-hidden="true" class="ui-section__bg tours-section__bg">
            <div class="tours-section__bg-dots"></div>
            <?php echo rc_svg_icon('tours_bg'); ?>
        </div>
        <div class="container">
            <!-- 1. Section Header -->
            <div class="tours-section__header text-center mx-auto" data-aos="fade-up">
                <?php if (!empty($badge)): ?>
                    <p class="ui-badge">
                        <?= $badge ?>
                    </p>
                <?php endif; ?>

                <h2 class="h2">
                    <?= $heading ?>
                </h2>

                <?php if (!empty($sub_heading)): ?>
                    <p class="ha-desc">
                        <?= $sub_heading ?>
                    </p>
                <?php endif; ?>
            </div>
            <!-- 3. 6 Tour Cards Grid -->
            <div class="tours-section__grid" data-aos="fade-up">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    rc_item_tour(get_the_ID(), 'tours-section__card');
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <div class="tours-section__footer d-flex justify-content-center">
                <a href="<?php echo home_url('/da-nang-travel-car/') ?>" class="ui-button"
                    label=" View All  da nang travel car">
                    View All
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>