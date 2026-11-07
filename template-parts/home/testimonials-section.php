<?php
$heading = get_field('heading_tes_home');
$badge = get_field('badge_tes_home');
$testimonials = get_field('testimonials_home');
?>

<section class="rc-section testimonials-section" data-purpose="testimonials-section">
    <div class="container">
        <!-- Section Header -->
        <div class="testimonials-section__header text-center mx-auto">
            <?php if (!empty($badge)): ?>
                <p class="rc-badge">
                    <?= $badge ?>
                </p>
            <?php endif; ?>

            <h2 class="h2">
                <?= $heading ?>
            </h2>

            <div class="testimonials-section__rating d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2 mt-2">
                <div class="testimonials-section__stars fw-bold">★★★★★</div>
                <p class="testimonials-section__rating-text mb-0">
                    Rated <span class="fw-bold">4.9/5</span> based on 1,450+ reviews on TripAdvisor
                    &amp; Google Reviews
                </p>
            </div>
        </div>

        <?php if (!empty($testimonials) && is_array($testimonials)): ?>
            <!-- Testimonials Carousel -->
            <div class="testimonials-section__grid swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $key => $testimonial):
                        $name = isset($testimonial['name']) ? $testimonial['name'] : '';
                        $type = isset($testimonial['type']) ? $testimonial['type'] : '';
                        $avatar = isset($testimonial['avatar']) ? $testimonial['avatar'] : '';
                        $location = isset($testimonial['location']) ? $testimonial['location'] : '';
                        $quote = isset($testimonial['quote']) ? $testimonial['quote'] : '';
                        $avatar_alt = !empty($name) ? $name : 'Customer Avatar';
                        ?>
                        <div class="swiper-slide">
                            <div class="testimonials-section__card d-flex flex-column justify-content-between">
                                <div>
                                    <div class="testimonials-section__card-top d-flex align-items-center justify-content-between">
                                        <div class="testimonials-section__stars">★★★★★</div>
                                        <?php if (!empty($type)): ?>
                                            <div class="testimonials-section__badge d-inline-flex align-items-center">
                                                <?php echo rc_svg_icon('tripadvisor'); ?>
                                                <span><?= esc_html($type) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($quote)): ?>
                                        <p class="testimonials-section__quote fst-italic">
                                            <?= esc_html($quote) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <!-- Customer Info -->
                                <div class="testimonials-section__author d-flex align-items-center">
                                    <?php if (!empty($avatar)): ?>
                                        <img alt="<?= esc_attr($avatar_alt) ?>"
                                            class="testimonials-section__avatar"
                                            src="<?= esc_url($avatar) ?>">
                                    <?php endif; ?>
                                    <div>
                                        <?php if (!empty($name)): ?>
                                            <h4 class="testimonials-section__name fw-bold mb-0"><?= esc_html($name) ?></h4>
                                        <?php endif; ?>
                                        <?php if (!empty($location)): ?>
                                            <p class="testimonials-section__meta mb-0"><?= esc_html($location) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Carousel Controls -->
            <div class="testimonials-section__controls d-flex align-items-center justify-content-center">
                <button aria-label="Previous Testimonial"
                    class="swiper-prev"
                    type="button">
                    <?php echo rc_svg_icon('swiper_prev'); ?>
                </button>
                <div class="swiper-pagination"></div>
                <button aria-label="Next Testimonial"
                    class="swiper-next"
                    type="button">
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>