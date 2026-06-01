<?php
$post_types_names = [
    'mortgages' => __("Mortgages", 'finanzia'),
    'loans'     => __("Loans", 'finanzia'),
    'cards'     => __("Credit Cards", 'finanzia'),
    'calcpages' => __("Calculators", 'finanzia'),
];

$post_types_urls = [
    'mortgages' => get_the_permalink(1320), // en id 1321
    'loans'     => get_the_permalink(1325), // en id 1326
    'cards'     => get_the_permalink(1330), // en id 1331
    'calcpages' => get_the_permalink(1756), // en id 1760
];
$post_type       = get_post_type();
?>
<div class="breadcrumbs">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
            </a>
            <meta itemprop="position" content="1"/>
        </li>
        <?php if ($post_type != 'page') : ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemscope itemtype="https://schema.org/WebPage" itemprop="item"
                   itemid="<?= $post_types_urls[$post_type]; ?>"
                   href="<?= $post_types_urls[$post_type]; ?>">
                    <span itemprop="name"><?= $post_types_names[$post_type]; ?></span>
                </a>
                <meta itemprop="position" content="2"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="3"/>
            </li>
        <?php else: ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="2"/>
            </li>
        <?php endif; ?>
    </ol>
</div>