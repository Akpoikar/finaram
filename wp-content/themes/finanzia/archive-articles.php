<?php

get_header();
?>
    <div class="main">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                    <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="1"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php _e("Finaram blog", 'finanzia'); ?></span>
                <meta itemprop="position" content="2"/>
            </li>
        </ol>
        <div class="pagetitle">
            <?php _e("Finaram blog", 'finanzia'); ?>
        </div>
        <!--        <nav class="news-nav">-->
        <!--            <ul>-->
        <!--                <li class="active">-->
        <!--                    <a href="#">??? View all</a>-->
        <!--                </li>-->
        <!--                <li>-->
        <!--                    <a href="#">??? Updates</a>-->
        <!--                </li>-->
        <!--            </ul>-->
        <!--        </nav>-->
        <div class="news">
            <div class="news__list">
                <?php if (is_countable($wp_query->posts)): ?>
                    <?php foreach ($wp_query->posts as $item) : ?>
                        <div class="news__box">
                            <a class="news__image" href="<?= get_the_permalink($item->ID) ?>">
                                <img src="<?= theme()->R(get_the_post_thumbnail_url($item->ID, 'full'), 'f=webp&w=655&h=368') ?>"
                                     width="655" height="368"
                                     alt="<?= $item->post_title . '-' . __("photo", 'finanzia') ?>"
                                     title="<?= $item->post_title ?>">
                            </a>
                            <div class="news__info">
                                <div class="news__date">
                                    <time>
                                        <?= theme()->getDate($item->post_date, 'Y-m-d H:i:s', 'd F Y') ?>
                                    </time>
                                </div>
                                <a class="news__name"
                                   href="<?= get_the_permalink($item->ID) ?>"><?= $item->post_title ?></a>
                                <div class="news__text">
                                    <?= get_the_excerpt($item); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php $nav = get_the_posts_pagination([
                'prev_text'          => '<',
                'next_text'          => '>',
                'screen_reader_text' => '&nbsp;',
                'class'              => 'pagination', // CSS класс, добавлено в 5.5.0.
            ]);
            echo str_replace('<h2 class="screen-reader-text">&nbsp;</h2>', '', $nav);
            ?>
            <?php wp_reset_query(); ?>
        </div>
    </div>
<?php
get_footer();
