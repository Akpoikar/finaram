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

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                                                          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T4SFWNGZ');</script>
    <!-- End Google Tag Manager -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600&display=swap" rel="stylesheet">

    <?php wp_head(); ?>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T4SFWNGZ"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="wrapper">
    <header class="header">
        <div class="header__holder">
            <?php if (is_front_page()): ?>
                <a class="logo" href="javascript:void(0);">
                    <img src="<?= theme()->getMainLogo(); ?>"
                         alt="<?php bloginfo('name'); ?> - <?php _e("photo", 'finanzia'); ?>"
                         title="<?php bloginfo('name'); ?>">
                </a>
            <?php else: ?>
                <a class="logo" href="<?= home_url(); ?>">
                    <img src="<?= theme()->getMainLogo(); ?>"
                         alt="<?php bloginfo('name'); ?> - <?php _e("photo", 'finanzia'); ?>"
                         title="<?php bloginfo('name'); ?>">
                </a>
            <?php endif; ?>
            <div class="header__controls">
                <nav class="nav">
                    <ul>
                        <?php if ($header_menu = theme()->getMenuTree('header-menu')): ?>
                            <?php foreach ($header_menu as $grandparent) : ?>
                                <?php if (isset($grandparent['children']) && is_array($grandparent['children']) && count($grandparent['children']) > 0): ?>

                                    <li class="menu-item-has-children">
                                        <?php if (parse_url($grandparent['url'], PHP_URL_PATH) != $_SERVER['REQUEST_URI']) : ?>
                                            <a href="<?= $grandparent['url']; ?>"><?= $grandparent['title']; ?></a>
                                            <div class="menu-item-mob-opener"></div>
                                        <?php else: ?>
                                            <span class="active"><?= $grandparent['title']; ?></span>
                                            <div class="menu-item-mob-opener"></div>
                                        <?php endif; ?>
                                        <div class="nav__drop">
                                            <ul>
                                                <?php foreach ($grandparent['children'] as $parent) : ?>
                                                    <li>
                                                        <?php if (parse_url($parent['url'], PHP_URL_PATH) != $_SERVER['REQUEST_URI']) : ?>
                                                            <a href="<?= $parent['url']; ?>"><?= $parent['title']; ?></a>
                                                        <?php else: ?>
                                                            <span class="active"><?= $parent['title']; ?></span>
                                                        <?php endif; ?>
                                                        <?php if (isset($parent['children']) && is_array($parent['children']) && count($parent['children']) > 0): ?>
                                                            <ul>
                                                                <?php foreach ($parent['children'] as $child) : ?>
                                                                    <li>
                                                                        <?php if (parse_url($child['url'], PHP_URL_PATH) != $_SERVER['REQUEST_URI']) : ?>
                                                                            <a href="<?= $child['url']; ?>"><?= $child['title']; ?></a>
                                                                        <?php else: ?>
                                                                            <span class="active"><?= $child['title']; ?></span>
                                                                        <?php endif; ?>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php if ($grandparent['fields'] = get_fields($grandparent['ID'])): ?>
                                                <div class="nav__drop-info">
                                                    <div class="nav__drop-image">
                                                        <img src="<?= theme()->R($grandparent['fields']['header_menu_image'], 'f=webp&q=100&w=644&h=348'); ?>"
<!--                                                             width="322" height="174"-->
                                                             alt="<?= $grandparent['fields']['header_menu_title']; ?>"
                                                             title="<?= $grandparent['fields']['header_menu_title']; ?> - <?php _e("photo", 'finanzia'); ?>">
                                                    </div>
                                                    <div class="nav__drop-title">
                                                        <?= $grandparent['fields']['header_menu_title']; ?>
                                                    </div>
                                                    <div class="nav__drop-text">
                                                        <?= $grandparent['fields']['header_menu_text']; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <?php if (parse_url($grandparent['url'], PHP_URL_PATH) != $_SERVER['REQUEST_URI']) : ?>
                                            <a href="<?= $grandparent['url']; ?>"><?= $grandparent['title']; ?></a>
                                        <?php else: ?>
                                            <span class="active"><?= $grandparent['title']; ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="header__right">
                    <?php if (count($allowedLanguages = theme()->getAllowedLanguages()) > 1): ?>
                        <div class="lang">
                            <?php foreach ($allowedLanguages as $language) : ?>
                                <?php if ($language['active']): ?>
                                    <div class="lang__active">
                                        <img src="<?= $language['country_flag_url']; ?>"
                                             alt="<?= __("flag", 'finanzia') . ' ' . $language['code']; ?> - <?php _e("icon", 'finanzia'); ?>"
                                             title="<?= __("flag", 'finanzia') . ' ' . $language['code']; ?>">
                                        <?= $language['translated_name']; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <ul class="lang__list">
                                <?php foreach ($allowedLanguages as $language) : ?>
                                    <?php if (!$language['active']): ?>
                                        <li>
                                            <a href="<?= $language['url']; ?>">
                                                <img src="<?= $language['country_flag_url']; ?>"
                                                     alt="<?= __("flag", 'finanzia') . ' ' . $language['code']; ?> - <?php _e("icon", 'finanzia'); ?>"
                                                     title="<?= __("flag", 'finanzia') . ' ' . $language['code']; ?>">
                                                <?= $language['translated_name']; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="callback">
                        <?php _e("Get Consultation", 'finanzia'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-opener">
            <div class="mobile-opener__line-1"></div>
            <div class="mobile-opener__line-2"></div>
        </div>
    </header>
