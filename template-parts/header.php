<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$text_support = get_field('text_support', 'option');
$phone = get_field('phone', 'option');
$email = get_field('email', 'option');
$whatsapp_url = function_exists('rc_whatsapp_url') ? rc_whatsapp_url($phone) : '';
?>

<header id="site-header" class="header-main">
    <div class="header-top-bar">
        <div class="container">
            <div class="header-top d-flex justify-content-between align-items-center gap-2">
                <div class="header-top__contacts d-flex align-items-center">
                    <?php if (!empty($phone)): ?>
                        <a href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>"
                            class="header-top__link d-flex align-items-center"
                            target="_blank" rel="noopener noreferrer"
                            aria-label="Chat with us on WhatsApp">
                            <?php echo rc_svg_icon('phone'); ?>
                            <span><?= esc_html($phone) ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($phone) && !empty($email)): ?>
                        <span class="header-top__divider" aria-hidden="true"></span>
                    <?php endif; ?>

                    <?php if (!empty($email)): ?>
                        <a href="mailto:<?= esc_attr($email) ?>" class="header-top__link d-flex align-items-center">
                            <?php echo rc_svg_icon('email'); ?>
                            <span><?= esc_html($email) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($text_support)): ?>
                    <p class="header-top__support mb-0">
                        <span class="header-top__status-dot" aria-hidden="true"></span>
                        <span><?= esc_html($text_support) ?></span>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="header-bottom-bar">
        <div class="container">
            <div class="header-bottom d-flex align-items-center justify-content-between">
                <div class="header-bottom__logo">
                    <a class="d-flex align-items-center" href="<?php echo esc_url(home_url()); ?>"
                        aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <img src="<?php echo esc_url($logo_url); ?>"
                            alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="200" height="54" />
                    </a>
                </div>

                <div class="header-bottom-right d-flex align-items-center justify-content-end">
                    <div class="header-bottom__menu d-none d-xl-block">
                        <?php if (has_nav_menu('primary-menu')): ?>
                            <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'primary-menu d-flex')) ?>
                        <?php endif; ?>
                    </div>

                    <div class="header-bottom__actions d-flex align-items-center">
                        <button id="btn_search"
                            class="header-main__search d-flex align-items-center justify-content-center"
                            aria-label="<?php esc_attr_e('Open search', 'rc'); ?>" aria-controls="search-modal"
                            aria-expanded="false" type="button">
                            <?php echo rc_svg_icon('search-icon'); ?>
                        </button>

                        <?php if (!empty($phone)): ?>
                            <a href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>"
                                class="header-main__cta d-none d-md-inline-flex align-items-center"
                                target="_blank" rel="noopener noreferrer"
                                aria-label="Chat with us on WhatsApp">
                                <?php echo rc_svg_icon('phone'); ?>
                                <span><?= esc_html($phone) ?></span>
                            </a>
                        <?php endif; ?>

                        <button id="btn_mobile_menu"
                            class="header-main__hamburger d-flex d-xl-none align-items-center justify-content-center"
                            aria-label="<?php esc_attr_e('Open menu', 'rc'); ?>" aria-controls="mobile-menu-drawer"
                            aria-expanded="false" type="button">
                            <?php echo rc_svg_icon('hamberger-icon'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mobile-menu-drawer" class="mobile-menu d-xl-none" aria-hidden="true" role="dialog" aria-modal="true"
        aria-label="<?php esc_attr_e('Mobile navigation', 'rc'); ?>">
        <div class="mobile-menu__overlay"></div>
        <div class="mobile-menu__drawer">
            <div class="mobile-menu__header d-flex align-items-center justify-content-between">
                <div class="mobile-menu__logo">
                    <a class="d-flex" href="<?php echo esc_url(home_url()); ?>"
                        aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <img src="<?php echo esc_url($logo_url); ?>"
                            alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="200" height="54" />
                    </a>
                </div>
                <button type="button" class="mobile-menu__close d-flex align-items-center justify-content-center"
                    aria-label="<?php esc_attr_e('Close menu', 'rc'); ?>">
                    <?php echo rc_svg_icon('menu-close-icon'); ?>
                </button>
            </div>

            <nav class="mobile-menu__nav" aria-label="<?php esc_attr_e('Mobile Primary Menu', 'rc'); ?>">
                <?php if (has_nav_menu('primary-menu')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'primary-menu',
                        'menu_class' => 'mobile-primary-menu',
                        'container' => false,
                    )); ?>
                <?php endif; ?>
            </nav>

            <?php if (!empty($phone) || !empty($email) || !empty($text_support)): ?>
                <div class="mobile-menu__footer">
                    <?php if (!empty($phone)): ?>
                        <a href="<?= !empty($whatsapp_url) ? esc_url($whatsapp_url) : '#' ?>"
                            class="mobile-menu__contact d-flex align-items-center"
                            target="_blank" rel="noopener noreferrer"
                            aria-label="Chat with us on WhatsApp">
                            <?php echo rc_svg_icon('phone'); ?>
                            <span><?= esc_html($phone) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($email)): ?>
                        <a href="mailto:<?= esc_attr($email) ?>" class="mobile-menu__contact d-flex align-items-center">
                            <?php echo rc_svg_icon('email'); ?>
                            <span><?= esc_html($email) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($text_support)): ?>
                        <p class="mobile-menu__support mb-0"><?= esc_html($text_support) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>