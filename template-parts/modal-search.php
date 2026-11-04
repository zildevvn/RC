<?php
/**
 * Template part for displaying a search modal
 *
 * @package rc
 */
?>
<!-- Search Modal -->
<div id="search-modal" class="search-modal" aria-hidden="true" role="dialog" aria-modal="true"
    aria-labelledby="search-modal-title">
    <div class="search-modal__overlay"></div>
    <div class="search-modal__content" role="document">
        <div class="search-modal__header">
            <div class="search-modal__heading">
                <h2 id="search-modal-title" class="search-modal__title">
                    <?php esc_html_e('What are you looking for?', 'rc'); ?>
                </h2>
                <p class="search-modal__subtitle">
                    <?php esc_html_e('Search for tours, car rentals, destinations, and travel guides.', 'rc'); ?>
                </p>
            </div>
            <button class="search-modal__close" aria-label="<?php esc_attr_e('Close search', 'rc'); ?>" type="button">
                <?php echo rc_svg_icon('menu-close-icon'); ?>
            </button>
        </div>
        <div class="search-modal__inner">
            <form role="search" method="get" class="search-modal__form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="search-modal__field-wrapper">
                    <label for="search-modal-input" class="search-modal__label">
                        <?php esc_html_e('Search for:', 'rc'); ?>
                    </label>
                    <span class="search-modal__icon" aria-hidden="true">
                        <?php echo rc_svg_icon('search-icon'); ?>
                    </span>
                    <input type="search" id="search-modal-input" class="search-modal__input"
                        placeholder="<?php esc_attr_e('Search tours, cars, destinations...', 'rc'); ?>"
                        value="<?php echo esc_attr(get_search_query()); ?>" name="s" autocomplete="off" required />
                    <button type="submit" class="search-modal__submit">
                        <span><?php esc_html_e('Search', 'rc'); ?></span>
                    </button>
                </div>
                <span class="search-modal__info">
                    <?php esc_html_e('Press Enter to search or ESC to close.', 'rc'); ?>
                </span>
            </form>
        </div>
    </div>
</div>