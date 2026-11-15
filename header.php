<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <meta name="google-site-verification" content="qwN9omocrfcaegsJm2o714T7TfY7zubl9ghNA7DAcSM" />

    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-MZJGL94T"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
    </script>
    <?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php do_action('rc_hook_header'); ?>