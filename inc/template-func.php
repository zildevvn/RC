<?php

function rc_breadcrumbs($title)
{
    ?>
    <p class="rc-breadcrumbs d-flex align-items-center mb-0">
        <a href="<?= home_url() ?>">Home</a>
        <span>
            <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                xmlns="http://www.w3.org/2000/svg" color="#000000">
                <path d="M13 6L19 12L13 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round"></path>
                <path d="M5 6L11 12L5 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </span>

        <?php if (is_singular('tours')): ?>
            <a href="<?= home_url('/phu-quoc-tours/') ?>">Tours</a>
            <span>
                <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="#000000">
                    <path d="M13 6L19 12L13 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M5 6L11 12L5 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </span>
        <?php endif; ?>

        <?php if (is_singular('cars')): ?>
            <a href="<?= home_url('/car-rental-in-phu-quoc/') ?>">Cars</a>
            <span>
                <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="#000000">
                    <path d="M13 6L19 12L13 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M5 6L11 12L5 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </span>
        <?php endif; ?>

        <?php if (is_singular('post')): ?>
            <a href="<?= home_url('/phu-quoc-island-travel-guide/') ?>">Blog</a>
            <span>
                <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="#000000">
                    <path d="M13 6L19 12L13 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M5 6L11 12L5 18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </span>
        <?php endif; ?>

        <?= $title ?>
    </p>
<?php }

function rc_table_of_content_shortcode()
{
    return '<div id="rc-table-of-content"></div>';
}
add_shortcode('rc_table_of_content', 'rc_table_of_content_shortcode');