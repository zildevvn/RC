<?php
$heading = get_field('heading_tes_home');
$badge = get_field('badge_tes_home');
$testimonials = get_field('testimonials_home');
?>

<section class="ui-section testimonials-section" data-purpose="testimonials-section">
    <div class="container">
        <!-- Section Header -->
        <div class="testimonials-section__header max-w-2xl mb-6 lg:mb-8 text-center mx-auto" data-aos="fade-up">
            <?php if (!empty($badge)): ?>
                <p class="ui-badge">
                    <?= $badge ?>
                </p>
            <?php endif; ?>

            <h2 class="h2">
                <?= $heading ?>
            </h2>

            <div
                class="testimonials-section__rating flex flex-col sm:flex-row items-center justify-center gap-2 mt-2">
                <div class="testimonials-section__stars fw-bold">★★★★★</div>
                <p class="testimonials-section__rating-text mb-0">
                    Rated <span class="fw-bold">4.9/5</span> based on 1,450+ reviews on TripAdvisor
                    &amp; Google Reviews
                </p>
            </div>
        </div>

        <?php if (!empty($testimonials) && is_array($testimonials)): ?>
            <!-- Testimonials Carousel -->
            <div class="testimonials-section__grid swiper py-1 px-0.5 mb-6 lg:mb-8" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $key => $testimonial):
                        $name = isset($testimonial['name']) ? $testimonial['name'] : '';
                        $type = isset($testimonial['type']) ? $testimonial['type'] : '';
                        $avatar = isset($testimonial['avatar']) ? $testimonial['avatar'] : '';
                        $location = isset($testimonial['location']) ? $testimonial['location'] : '';
                        $quote = isset($testimonial['quote']) ? $testimonial['quote'] : '';
                        $avatar_alt = !empty($name) ? $name : 'Customer Avatar';
                        ?>
                        <div class="swiper-slide h-auto flex">
                            <div class="testimonials-section__card w-full h-full p-8 rounded-2xl flex flex-col justify-between transition-all duration-300">
                                <div>
                                    <div
                                        class="testimonials-section__card-top flex items-center justify-between mb-5">
                                        <div class="testimonials-section__stars">★★★★★</div>
                                        <?php if (!empty($type)): ?>
                                            <div class="testimonials-section__badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                <?php echo rc_svg_icon('tripadvisor'); ?>
                                                <span><?= esc_html($type) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($quote)): ?>
                                        <p class="testimonials-section__quote fst-italic mb-8">
                                            <?= esc_html($quote) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <!-- Customer Info -->
                                <div class="testimonials-section__author flex items-center gap-3.5 pt-5 border-t border-slate-100">
                                    <?php if (!empty($avatar)): ?>
                                        <img alt="<?= esc_attr($avatar_alt) ?>" class="testimonials-section__avatar w-12 h-12 rounded-full object-cover shrink-0"
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
            <div class="testimonials-section__controls flex items-center justify-center gap-5">
                <button aria-label="Previous Testimonial" class="swiper-prev" type="button">
                    <?php echo rc_svg_icon('swiper_prev'); ?>
                </button>
                <div class="swiper-pagination"></div>
                <button aria-label="Next Testimonial" class="swiper-next" type="button">
                    <?php echo rc_svg_icon('arrow_next'); ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>