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

<section class="rc-section hero-section" data-purpose="hero-section">
    <?php if (!empty($bg_image)): ?>
        <img class="hero-section__bg" src="<?php echo esc_url($bg_image['url']); ?>"
            alt="<?php echo esc_attr($bg_image['alt'] ?: $heading); ?>" />
    <?php endif; ?>

    <div aria-hidden="true" class="hero-section__overlay"></div>

    <div class="container">
        <div class="hero-section__grid">
            <div class="hero-section__left" data-purpose="hero-copy-column">
                <?php if (!empty($heading)): ?>
                    <h1 class="hero-section__title">
                        <?= $heading ?>
                    </h1>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <p class="hero-section__desc">
                        <?= $desc ?>
                    </p>
                <?php endif; ?>

                <div class="hero-section__actions">
                    <a class="rc-button" data-purpose="primary-cta-button" href="#book">
                        <span>Book Car Now</span>
                        <?php echo rc_svg_icon('arrow_next'); ?>
                    </a>
                    <a class="rc-button--secondary" data-purpose="secondary-cta-button" href="#contact">
                        Contact Now
                    </a>
                </div>

                <div class="hero-section__trust">
                    <div class="hero-section__rating">
                        <span class="hero-section__stars">★★★★★</span>
                        <span class="hero-section__score"> <?= $kk_score ?> /5</span>
                        <span class="hero-section__source">TripAdvisor &amp; Google</span>
                    </div>
                </div>
            </div>

            <?php if (!empty($cards)): ?>
                <div class="hero-section__right" data-purpose="features-grid-column">
                    <div class="hero-section__cards">
                        <?php foreach ($cards as $card): ?>
                            <div class="hero-section__card">
                                <?php if (!empty($card['icon'])): ?>
                                    <div class="hero-section__card-icon">
                                        <img src="<?= esc_url($card['icon']) ?>" alt="<?= esc_attr($card['heading']) ?>" />
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <?php if (!empty($card['heading'])): ?>
                                        <h3 class="hero-section__card-title h5"><?= $card['heading'] ?></h3>
                                    <?php endif; ?>
                                    <?php if (!empty($card['sub_heading'])): ?>
                                        <p class="hero-section__card-text"><?= $card['sub_heading'] ?></p>
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