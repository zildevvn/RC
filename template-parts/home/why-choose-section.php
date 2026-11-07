<?php
$heading = get_field('heading_wc_home');
$badge = get_field('badge_wc_home');
$desc = get_field('desc_wc_home');
$items = get_field('list_items_wc_home');
?>
<section class="rc-section wc-section">
    <div aria-hidden="true" class="rc-section__bg wc-section__bg"><svg fill="none" preserveAspectRatio="none"
            stroke="currentColor" stroke-width="1.5" style="opacity: 0.22;" viewBox="0 0 1200 650">
            <path d="M-100 100 C 150 20, 350 240, 600 90 C 850 -30, 1050 210, 1350 70" stroke-dasharray="12 4">
            </path>
            <path d="M-100 160 C 180 80, 380 300, 620 150 C 860 30, 1080 260, 1350 130"></path>
            <path d="M-100 220 C 200 140, 400 360, 650 210 C 900 80, 1100 320, 1350 190"></path>
            <path d="M-100 290 C 120 200, 320 440, 580 280 C 820 160, 1020 400, 1350 260" stroke-dasharray="6 6">
            </path>
            <path d="M-100 370 C 160 270, 360 500, 620 360 C 880 230, 1080 460, 1350 340"></path>
            <path d="M-100 450 C 220 350, 420 560, 680 420 C 920 310, 1120 540, 1350 420"></path>
            <path d="M-100 530 C 250 430, 450 640, 720 500 C 960 390, 1150 600, 1350 500"></path>
            <path d="M-100 600 C 280 500, 500 700, 780 580 C 1000 460, 1200 650, 1350 570"></path>
            <circle cx="220" cy="180" r="26" stroke-width="1.5"></circle>
            <circle cx="220" cy="180" r="58" stroke-width="1.5"></circle>
            <circle cx="220" cy="180" r="96" stroke-dasharray="4 4" stroke-width="1.2"></circle>
            <circle cx="220" cy="180" r="140" stroke-width="1"></circle>
            <circle cx="980" cy="340" r="32" stroke-width="1.5"></circle>
            <circle cx="980" cy="340" r="70" stroke-width="1.5"></circle>
            <circle cx="980" cy="340" r="115" stroke-dasharray="5 5" stroke-width="1.2"></circle>
            <circle cx="980" cy="340" r="160" stroke-width="1"></circle>
            <circle cx="610" cy="490" r="22" stroke-width="1.5"></circle>
            <circle cx="610" cy="490" r="52" stroke-width="1.2"></circle>
            <circle cx="610" cy="490" r="88" stroke-dasharray="4 4" stroke-width="1"></circle>
        </svg></div>
    <div class="container">
        <!-- Section Header -->
        <div class="wc-section__header text-center mx-auto">
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
            <div class="wc-section__grid">
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