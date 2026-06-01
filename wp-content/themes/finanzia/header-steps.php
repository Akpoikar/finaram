<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php language_attributes(); ?>>

<head>
    <base href="<?= home_url(); ?>"/>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon"
          href="<?= get_site_icon_url(512, theme()->getThemeUrl() . "assets/images/favicon.png"); ?>"
          type="image/x-icon">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600&display=swap" rel="stylesheet">

    <?php wp_head(); ?>

</head>
<body>