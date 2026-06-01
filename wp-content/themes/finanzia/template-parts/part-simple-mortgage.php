<div class="calcs-page">
    <?php if ($args['use_the_title']) : ?>
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                    <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="1"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemscope itemtype="https://schema.org/WebPage" itemprop="item"
                   itemid="<?php echo esc_url(home_url('/calculators')); ?>"
                   href="<?php echo esc_url(home_url('/calculators')); ?>">
                    <span itemprop="name"><?php _e("Calculators", 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="2"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="3"/>
            </li>
        </ol>
    <?php endif; ?>
    <?php if ($args['use_the_title']) : ?>
        <div class="pagetitle">
            <h1><?php the_title(); ?></h1>
        </div>
    <?php else : ?>
        <div class="mortgage-rent__title">
            <?= ($args['title'] ?? __("Simple Mortgage", 'finanzia')); ?>
        </div>
    <?php endif; ?>
    <div class="mortgage-rent__text">
        <?= ($args['subtitle'] ?? __("A simple calculation of whether a mortgage or rent is more profitable for you.", 'finanzia')); ?>
    </div>
    <?php get_template_part('template-parts/part', 'simple-mortgage-calc'); ?>
</div>