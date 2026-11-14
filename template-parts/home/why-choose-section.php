<?php
$heading = get_field('heading_wc_home');
$badge = get_field('badge_wc_home');
$desc = get_field('desc_wc_home');
$items = get_field('list_items_wc_home');
?>
<section class="ui-section wc-section relative overflow-hidden">
    <div aria-hidden="true" class="ui-section__bg wc-section__bg absolute inset-0 z-0 pointer-events-none select-none">
        <?php echo rc_svg_icon('wc_bg'); ?>
    </div>
    <div class="container relative z-10">
        <!-- Section Header -->
        <div class="wc-section__header text-center mx-auto" data-aos="fade-up">
            <?php if (!empty($badge)): ?>
                <p class="ui-badge"> <?= $badge ?> </p>
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
            <div class="wc-section__grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 md:mt-8 lg:mt-12" data-aos="fade-up">
                <?php foreach ($items as $item): ?>
                    <div class="wc-section__card flex flex-col justify-start p-6 rounded-2xl transition-all duration-300">
                        <div class="wc-section__card-icon w-12 h-12 rounded-xl flex items-center justify-center mb-5 shrink-0">
                            <?php if (!empty($item['icon'])): ?>
                                <img class="w-6 h-6 object-contain" src="<?= $item['icon'] ?>" alt=" icon rent car da nang for <?= $item['heading'] ?>">
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