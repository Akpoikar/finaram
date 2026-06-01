<?php
$calcpages = get_posts([
    'post_type'        => 'calcpages',
    'orderby'          => 'date',
    'order'            => 'ASC',
    'posts_per_page'   => '-1',
    'suppress_filters' => false,
]);

?>
<div class="calcs-page">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
            </a>
            <meta itemprop="position" content="1"/>
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?= $args['title']; ?></span>
            <meta itemprop="position" content="2"/>
        </li>
    </ol>
    <div class="pagetitle">
        <?= (trim($args['title']) != '' ? "<h1>" . $args['title'] . "</h1>" : "") ?>
    </div>
    <?php if (is_countable($calcpages)): ?>
        <div class="calcs-list">
            <?php foreach ($calcpages as $item) : ?>
                <a class="calcs-list__box" href="<?= get_the_permalink($item->ID) ?>">
                    <div class="calcs-list__info">
                        <div class="calcs-list__title">
                            <h2><?= $item->post_title ?></h2>
                        </div>
                        <div class="calcs-list__text">
                            <?= get_the_excerpt($item); ?>
                        </div>
                        <div class="calcs-list__btn">
                            <?php _e("Calculate", 'finanzia'); ?>
                        </div>
                    </div>
                    <div class="calcs-list__image">
                        <img src="<?= theme()->R(get_the_post_thumbnail_url($item->ID, 'full'), 'f=webp&w=321&h=180') ?>"
                             width="321" height="180"
                             alt="<?= $item->post_title . '-' . __("photo", 'finanzia') ?>"
                             title="<?= $item->post_title ?>">
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>