<?php

get_header();

$fields = get_fields();

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
                <a class="ajax-link" itemscope itemtype="https://schema.org/WebPage" itemprop="item"
                   itemid="<?php echo get_post_type_archive_link('articles'); ?>"
                   href="<?php echo get_post_type_archive_link('articles'); ?>">
                    <span itemprop="name"><?php _e("Blog", 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="2"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="3"/>
            </li>
        </ol>
        <h1 class="pagetitle">
            <?php the_title(); ?>
        </h1>
        <div class="news__date">
            <time>
                <?= theme()->getDate(get_the_date('Y-m-d'), 'Y-m-d', 'd F Y') ?>
            </time>
        </div>
        <div class="news-inner">
            <div class="news-inner__image">
                <img src="<?= theme()->R(get_the_post_thumbnail_url(get_the_ID(), 'full'), 'f=webp&w=1330&h=748') ?>"
                     width="1330" height="748"
                     alt="<?php the_title() . '-' . __("photo", 'finanzia') ?>"
                     title="<?php the_title(); ?>">
            </div>
            <div class="news-inner__text">
                <?php the_content(); ?>
            </div>
            <?php if (is_countable($fields['news_gallery'])): ?>
                <div class="news-gallery">
                    <?php foreach ($fields['news_gallery'] as $item) : ?>
                        <div class="news-gallery__slide">
                            <img src="<?= theme()->R($item['url'], 'f=webp&w=1330&h=748'); ?>"
                                 width="1330" height="748"
                                 alt="<?= ($item['alt'] ?: $item['title'] . '-' . __("photo", 'finanzia')) ?>"
                                 title="<?= $item['title'] ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
<?php if (is_countable($fields['news_read_next'])): ?>
    <div class="news news-slider">
        <div class="title">
            <?php _e("Read next", 'finanzia'); ?>
        </div>
        <div class="news__list">
            <?php foreach ($fields['news_read_next'] as $item) : ?>
                <div class="news__box">
                    <a class="news__image" href="<?= get_the_permalink($item->ID) ?>">
                        <img src="<?= theme()->R(get_the_post_thumbnail_url($item->ID, 'full'), 'f=webp&w=520&h=308') ?>"
                             width="520" height="308"
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
        </div>
        <div class="news__more">
            <a class="news__btn"
               href="<?php echo get_post_type_archive_link('articles'); ?>"><?php _e("More Articles", 'finanzia'); ?></a>
        </div>
    </div>
<?php endif; ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?= home_url() . $_SERVER['REQUEST_URI'] ?>"
            },
            "headline": "<?php the_title() ?>",
            "description": "<?= get_the_excerpt(); ?>",
            "image": "<?= theme()->R(get_the_post_thumbnail_url(get_the_ID(), 'full'), 'f=webp&w=1330&h=748') ?>",
            "author": {
                "@type": "Person",
                "name": ""
            },
            "publisher": {
                "@type": "Organization",
                "name": "",
                "logo": {
                    "@type": "ImageObject",
                    "url": ""
                }
            },
            "datePublished": "<?= get_the_date('Y-m-d'); ?>",
            "dateModified": "<?= get_the_modified_date('Y-m-d'); ?>"
        }
    </script>
<?php
get_footer();
