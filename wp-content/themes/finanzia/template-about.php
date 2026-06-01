<?php
/**
 * Template name: About template
 */
$fields = get_fields();

get_header();
?>
    <div class="about">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                    <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="1"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="2"/>
            </li>
        </ol>
        <div class="pagetitle">
            <?php the_title(); ?>
        </div>
        <div class="about__image">
            <img width="1600" height="660" src="<?= theme()->R(get_the_post_thumbnail_url(), 'f=webp&w=1600&h=660'); ?>"
                 alt="<?php the_title(); ?> - <?php _e("photo", 'finanzia'); ?>" title="<?php the_title(); ?>">
        </div>
        <?php if ($fields['partners_enable'] && is_countable($fields['partners_slides'])): ?>
            <div class="partners">
                <?php foreach ($fields['partners_slides'] as $item) : ?>
                    <div class="partners__slide">
                        <img src="<?= theme()->R($item['url'], 'f=webp&w=212&h=42'); ?>"
                             alt="<?= ($item['alt'] ?: $item['title'] . '-' . __("photo", 'finanzia')) ?>"
                             title="<?= $item['title'] ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($fields['intro_enable']): ?>
            <div class="about-intro">
                <div class="about-intro__image">
                    <img src="<?= theme()->R($fields['intro_image']['url'], 'f=webp&w=739&h=526'); ?>"
                         width="739" height="526"
                         alt="<?= ($fields['intro_image']['alt'] ?: $fields['intro_image']['title'] . '-' . __("photo", 'finanzia')) ?>"
                         title="<?= $fields['intro_image']['title'] ?>">
                </div>
                <div class="about-intro__info">
                    <div class="about-intro__title">
                        <?= $fields['intro_title'] ?>
                    </div>
                    <div class="about-intro__text">
                        <?= $fields['intro_text'] ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($fields['what_enable']): ?>

            <div class="what">
                <div class="title">
                    <?= $fields['what_title'] ?>
                </div>
                <div class="what__list">
                    <?php if (is_countable($fields['what_steps'])): ?>
                        <?php foreach ($fields['what_steps'] as $item) : ?>
                            <div class="what__box">
                                <div class="what__number">
                                    <?= $item['what_number'] ?>
                                </div>
                                <div class="what__title">
                                    <?= $item['what_name'] ?>
                                </div>
                                <div class="what__text">
                                    <?= $item['what_text'] ?>
                                </div>
                                <div class="what__image">
                                    <img src="<?= theme()->R($item["what_image"]['url'], 'f=webp&w=378&h=180'); ?>"
                                         width="378" height="180"
                                         alt="<?= ($item["what_image"]['alt'] ?: $item["what_image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                                         title="<?= $item["what_image"]['title'] ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($fields['outro_enable']): ?>
            <div class="about-intro">
                <div class="about-intro__info">
                    <div class="about-intro__title">
                        <?= $fields['outro_title'] ?>
                    </div>
                    <div class="about-intro__text">
                        <?= $fields['outro_text'] ?>
                    </div>
                </div>
                <div class="about-intro__image">
                    <img src="<?= theme()->R($fields["outro_image"]['url'], 'f=webp&w=739&h=526'); ?>"
                         width="739" height="526"
                         alt="<?= ($fields["outro_image"]['alt'] ?: $fields["outro_image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                         title="<?= $fields["outro_image"]['title'] ?>">
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php

get_footer();
