<?php
$heading = get_field('heading_hero_home');
$desc = get_field('desc_hero_home');
$cards = get_field('list_cards_hero_home');
$bg_image = get_field('bg_hero_home');

$kk_count = (int) get_post_meta(get_the_ID(), '_kksr_count_default', true);
$kk_ratings = (float) get_post_meta(get_the_ID(), '_kksr_ratings_default', true);
if ($kk_count <= 0) {
    $kk_count = (int) get_post_meta(get_the_ID(), '_kksr_casts', true);
    $kk_ratings = (float) get_post_meta(get_the_ID(), '_kksr_ratings', true);
}
$kk_score = $kk_count > 0
    ? round(min($kk_ratings / $kk_count, 4.9), 1, PHP_ROUND_HALF_DOWN)
    : 0;
?>

<section class="ui-section hero-section relative flex items-center justify-center overflow-hidden pb-[calc(18%+24px)] md:pb-[150px]" data-purpose="hero-section">
    <?php if (!empty($bg_image)): ?>
        <img class="hero-section__bg absolute inset-0 z-0 w-full h-full object-cover rounded-none select-none pointer-events-none" src="<?php echo esc_url($bg_image['url']); ?>"
            alt="<?php echo esc_attr($bg_image['alt'] ?: $heading); ?>" />
    <?php endif; ?>

    <div aria-hidden="true" class="hero-section__overlay absolute inset-0 z-10 pointer-events-none"></div>

    <div class="container relative z-20">
        <div class="hero-section__grid grid grid-cols-1 lg:grid-cols-12 gap-7 sm:gap-8 md:gap-10 lg:gap-12 items-center w-full">
            <div class="hero-section__left flex flex-col items-start justify-center lg:col-span-7" data-purpose="hero-copy-column">
                <?php if (!empty($heading)): ?>
                    <h1 class="hero-section__title mt-0 mb-4 sm:mb-5 md:mb-6">
                        <?= $heading ?>
                    </h1>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <p class="hero-section__desc max-w-xl mt-0 mb-5 sm:mb-6 md:mb-8">
                        <?= $desc ?>
                    </p>
                <?php endif; ?>

                <div class="hero-section__actions flex flex-wrap items-center gap-3 sm:gap-4 mb-6 md:mb-10 w-full sm:w-auto">
                    <a class="ui-button ui-book-car-trigger" data-purpose="primary-cta-button" href="#book"
                        aria-controls="book-car-modal" aria-expanded="false">
                        <span>Book Car Now</span>
                        <?php echo rc_svg_icon('arrow_next'); ?>
                    </a>
                    <a class="ui-button--secondary" data-purpose="secondary-cta-button" href="#contact">
                        Contact Now
                    </a>
                </div>

                <div class="hero-section__trust pt-4 sm:pt-6 border-t border-white/15 w-full max-w-xl flex flex-col sm:flex-row sm:items-center gap-3 text-xs sm:text-sm">
                    <div class="hero-section__rating inline-flex items-center gap-1.5 shrink-0 flex-wrap">
                        <span class="hero-section__stars">★★★★★</span>
                        <span class="hero-section__score"> <?= $kk_score ?> /5</span>
                        <span class="hero-section__source">TripAdvisor &amp; Google</span>
                    </div>
                </div>
            </div>

            <?php if (!empty($cards)): ?>
                <div class="hero-section__right w-full lg:col-span-5" data-purpose="features-grid-column">
                    <div class="hero-section__cards grid grid-cols-2 gap-3 sm:gap-4 lg:gap-5">
                        <?php foreach ($cards as $card): ?>
                            <div class="hero-section__card p-4 sm:p-6 rounded-2xl backdrop-blur-md flex flex-col justify-between transition-all duration-300">
                                <?php if (!empty($card['icon'])): ?>
                                    <div class="hero-section__card-icon w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-3 sm:mb-4 shrink-0 transition-transform duration-300">
                                        <img class="w-full h-auto max-w-[20px] sm:max-w-[24px] rounded-none" src="<?= esc_url($card['icon']) ?>" alt="<?= esc_attr($card['heading']) ?>" />
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <?php if (!empty($card['heading'])): ?>
                                        <h3 class="hero-section__card-title h5 mb-1"><?= $card['heading'] ?></h3>
                                    <?php endif; ?>
                                    <?php if (!empty($card['sub_heading'])): ?>
                                        <p class="hero-section__card-text m-0 text-xs lg:text-sm"><?= $card['sub_heading'] ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>