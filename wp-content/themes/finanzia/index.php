<?php

get_header();

$fields = get_fields();

?>
    <div class="main">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemprop="item" href="<?= home_url();?>"><span itemprop="name"><?php _e("Home", 'finanzia'); ?></span></a>
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
        <h1 class="pagetitle">
            <?php the_title(); ?>
        </h1>
        <div class="news-inner">
            <div class="news-inner__text">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
<?php

get_footer();
