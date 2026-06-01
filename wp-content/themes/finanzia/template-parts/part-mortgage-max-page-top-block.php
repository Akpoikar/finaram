<?php $option_fields = get_fields('option'); ?>
<div class="calcs-page">
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
    <div class="pagetitle">
        <h1>
            <?php the_title(); ?>
        </h1>
    </div>
    <div class="calcs-page__notes">
        <!--        --><?php //= get_the_excerpt(); ?>
    </div>
    <?php get_template_part('template-parts/part', 'mortgage-max-calc'); ?>
</div>
