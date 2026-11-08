<?php
$heading = get_field('heading_wc_home');
$badge = get_field('badge_wc_home');
$desc = get_field('desc_wc_home');
$items = get_field('list_items_wc_home');
?>
<section class="rc-section wc-section">
    <div aria-hidden="true" class="rc-section__bg wc-section__bg">
        <?php echo rc_svg_icon('wc_bg'); ?>
    </div>
    <div class="container">
        <!-- Section Header -->
        <div class="wc-section__header text-center mx-auto" data-aos="fade-up">
            <?php if (!empty($badge)): ?>
                <p class="rc-badge"> <?= $badge ?> </p>
            <?php endif; ?>

            <?php if (!empty($heading)): ?>
                <h2 class="h2">
                    <?= $heading ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($desc)): ?>
                <p class="ha-desc"> <?= $desc ?> </p>
            <?php endif; ?>
        </div>

        <?php if (!empty($items)): ?>
            <div class="wc-section__grid" data-aos="fade-up">
                <?php foreach ($items as $item): ?>
                    <div class="wc-section__card">
                        <div class="wc-section__card-icon">
                            <?php if (!empty($item['icon'])): ?>
                                <img src="<?= $item['icon'] ?>" alt=" icon rent car da nang for <?= $item['heading'] ?>">
                            <?php endif; ?>
                        </div>

                        <h3 class="wc-section__card-title h5 mb-3">
                            <?= $item['heading'] ?>
                        </h3>

                        <p class="wc-section__card-desc mb-0">
                            <?= $item['desc'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>