<?php
get_header();

$fields = get_fields();
?>

    <div class="top-section">
        <div class="top-section__info">
            <div class="top-section__title">
                <?= (trim($fields['top_section_title']) != '' ? "<h1>" . $fields['top_section_title'] . "</h1>" : "") ?>
            </div>
            <?php if (is_countable($fields['top_section_list'])): ?>
                <ul class="top-section__list">
                    <?php foreach ($fields['top_section_list'] as $item) : ?>
                        <li><?= $item['top_section_list_item'] ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="top-section__buttons">
                <?php if ($fields['left_button']['left_button_url']): ?>
                    <a class="top-section__btn-1" href="<?= $fields['left_button']['left_button_url'] ?>"
                       title="<?= $fields['left_button']['left_button_title'] ?>">
                        <?= $fields['left_button']['left_button_title'] ?>
                    </a>
                <?php endif; ?>
                <?php if ($fields['right_button']['right_button_title']): ?>
                    <a class="top-section__btn-2" href="<?= $fields['right_button']['right_button_url'] ?>"
                       title="<?= $fields['right_button']['right_button_title'] ?>">
                        <?= $fields['right_button']['right_button_title'] ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <!--      <div class="top-section__image">-->
        <!--        <img src="--><?php //= theme()->getThemeUrl(); ?><!--data/top-anim-3.gif" alt="">-->
        <!--      </div>-->
        <?php if ($fields["top_section_video"]["poster"]): ?>
            <video autoplay="autoplay" loop="" muted="" playsinline="" webkit-playsinline="" preload="none" width="450"
                   height="450"
                   poster="<?= theme()->R($fields["top_section_video"]["poster"]['url'], 'f=webp&w=450'); ?>">
                <?php if ($fields["top_section_video"]["video_webm"]): ?>
                    <source src="<?= $fields["top_section_video"]["video_webm"]['url']; ?>" type="video/webm">
                <?php endif; ?>
                <?php if ($fields["top_section_video"]["video_mp4"]): ?>
                    <source src="<?= $fields["top_section_video"]["video_mp4"]['url']; ?>" type="video/mp4">
                <?php endif; ?>
            </video>
        <?php endif; ?>
    </div>
<?php if ($fields['partners_enable'] && is_countable($fields['partners_slides'])): ?>
    <div class="partners-section">
        <div class="title">
            <?= (trim($fields['partners_title']) != '' ? "<h2>" . $fields['partners_title'] . "</h2>" : "") ?>
        </div>
        <div class="sub-title">
            <?= (trim($fields['partners_subtitle']) != '' ? "<h2>" . $fields['partners_subtitle'] . "</h2>" : "") ?>
        </div>
        <div class="partners">
            <?php foreach ($fields['partners_slides'] as $item) : ?>
                <div class="partners__slide">
                    <img src="<?= theme()->R($item['url'], 'f=webp&w=212&h=42'); ?>"
                         alt="<?= ($item['alt'] ?: $item['title'] . '-' . __("photo", 'finanzia')) ?>"
                         title="<?= $item['title'] ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if ($fields['calc_enable']): ?>
    <?php if ($fields['calc_new']): ?>
        <?php get_template_part('template-parts/part', 'mortgage-calc-new', ['title' => $fields['calc_title'], 'subtitle' => $fields['calc_subtitle']]); ?>
    <?php else: ?>
        <?php get_template_part('template-parts/part', 'mortgage-calc', ['title' => $fields['calc_title'], 'subtitle' => $fields['calc_subtitle']]); ?>
    <?php endif; ?>
<?php endif; ?>
<?php if ($fields['banner_link_enable']): ?>
    <?php get_template_part('template-parts/part', 'banner-link', [
        'link'        => $fields['banner_link'],
        'title'       => $fields['banner_title'],
        'text'        => $fields['banner_text'],
        'image'       => $fields['banner_image'],
        'button_text' => $fields['banner_button_text'],
    ]); ?>
<?php endif; ?>
<?php if ($fields['how_enable']): ?>
    <div class="how">
        <div class="title">
            <?= (trim($fields['how_title']) != '' ? "<h2>" . $fields['how_title'] . "</h2>" : "") ?>
        </div>
        <div class="sub-title">
            <?= (trim($fields['how_subtitle']) != '' ? "<h2>" . $fields['how_subtitle'] . "</h2>" : "") ?>
        </div>
        <div class="how__holder">
            <div class="how__images">
                <?php if (is_countable($fields['how_steps'])): ?>
                    <?php foreach ($fields['how_steps'] as $item) : ?>
                        <div class="how__images-box">
                            <img src="<?= theme()->R($item["how_image"]['url'], 'f=webp&w=658&h=468'); ?>" width="658"
                                 height="468"
                                 alt="<?= ($item["how_image"]['alt'] ?: $item["how_image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                                 title="<?= $item["how_image"]['title'] ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <ul class="how__list">
                <?php if (is_countable($fields['how_steps'])): ?>
                    <?php foreach ($fields['how_steps'] as $item) : ?>
                        <div class="how__box">
                            <div class="how__number">
                                <?= $item['how_number'] ?>
                            </div>
                            <div class="how__name">
                                <?= $item['how_name'] ?>
                            </div>
                            <div class="how__text">
                                <?= $item['how_text'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
<?php if ($fields['why_enable']): ?>
    <div class="why">
        <div class="title">
            <?= (trim($fields['why_title']) != '' ? "<h2>" . $fields['why_title'] . "</h2>" : "") ?>
        </div>
        <div class="sub-title">
            <?= $fields['why_subtitle'] ?>
        </div>
        <div class="why__section">
            <div class="why__holder">
                <div class="why__three-columns">
                    <div class="why__column">
                        <div class="why__label">
                            <?= $fields['why_label'] ?>
                        </div>
                        <div class="why__logo">
                            <img src="<?= $fields['why_logo']; ?>" width="171" height="34"
                                 alt="<?= $fields['why_title'] . '-' . __("photo", 'finanzia') ?>"
                                 title="<?= $fields['why_title'] ?>">
                        </div>
                        <div class="why__col">
                            <?php if (is_countable($fields['why_center_column'])): ?>
                                <?php foreach ($fields['why_center_column'] as $item) : ?>
                                    <div class="why__trigger">
                                        <div class="why__trigger-name">
                                            <?= $item['name'] ?>
                                        </div>
                                        <div class="why__trigger-text">
                                            <?= $item['text'] ?>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="why__list">
                            <?php if (is_countable($fields['why_list'])): ?>
                                <?php foreach ($fields['why_list'] as $item) : ?>
                                    <?php switch ($item["center_column"]["yesno_icon"]):
                                        case 1: ?>
                                            <div class="why__row">
                                                <div class="why__options no">
                                                    <img src="<?= theme()->getThemeUrl() ?>assets/data/no.svg"
                                                         width="24"
                                                         height="24" alt="ico" title="ico">
                                                    <?= $item['center_column']['text'] ?>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php case 2: ?>
                                            <div class="why__row">
                                                <div class="why__options yes">
                                                    <img src="<?= theme()->getThemeUrl() ?>assets/data/yes.svg"
                                                         width="24"
                                                         height="24" alt="ico" title="ico">
                                                    <?= $item['center_column']['text'] ?>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php endswitch; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="why__column">
                        <div class="why__col">
                            <div class="why__title">
                                <?= $fields['why_left_column_title'] ?>
                            </div>
                        </div>
                        <div class="why__list">
                            <?php if (is_countable($fields['why_list'])): ?>
                                <?php foreach ($fields['why_list'] as $item) : ?>
                                    <?php switch ($item["left_column"]["yesno_icon"]):
                                        case 1: ?>
                                            <div class="why__row">
                                                <div class="why__options no">
                                                    <img src="<?= theme()->getThemeUrl() ?>assets/data/no.svg"
                                                         width="24"
                                                         height="24" alt="ico" title="ico">
                                                    <?= $item['left_column']['text'] ?>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php case 2: ?>
                                            <div class="why__row">
                                                <div class="why__options yes">
                                                    <img src="<?= theme()->getThemeUrl() ?>assets/data/yes.svg"
                                                         width="24"
                                                         height="24" alt="ico" title="ico">
                                                    <?= $item['left_column']['text'] ?>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php endswitch; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="why__column">
                        <div class="why__col">
                            <div class="why__title">
                                <?= $fields['why_right_column_title'] ?>
                            </div>
                        </div>
                        <div class="why__list">
                            <?php if (is_countable($fields['why_list'])): ?>
                                <?php foreach ($fields['why_list'] as $item) : ?>
                                    <?php switch ($item["right_column"]["yesno_icon"]):
                                        case 1: ?>
                                            <div class="why__row">
                                                <div class="why__options no">
                                                    <img src="<?= theme()->getThemeUrl() ?>assets/data/no.svg"
                                                         width="24"
                                                         height="24" alt="ico" title="ico">
                                                    <?= $item['right_column']['text'] ?>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php case 2: ?>
                                            <div class="why__row">
                                                <div class="why__options yes">
                                                    <img src="<?= theme()->getThemeUrl() ?>assets/data/yes.svg"
                                                         width="24"
                                                         height="24" alt="ico" title="ico">
                                                    <?= $item['right_column']['text'] ?>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php endswitch; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="why__buttons">
                    <div class="why__more" data-open="<?php _e("Show more benefits", 'finanzia'); ?>"
                         data-close="<?php _e("Close more benefits", 'finanzia'); ?>">
                        <?php _e("Show more benefits", 'finanzia'); ?>
                    </div>
<!--                    <button class="why__calc" type="button" name="button"> --><?php //= $fields['button_title'] ?><!--</button>-->
                    <a class="why__calc" href="<?= esc_url(home_url('/calculators')); ?>"><?= $fields['button_title'] ?></a>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($fields['win_enable']): ?>
    <div class="triggers">
        <div class="title">
            <?= (trim($fields['win_title']) != '' ? "<h2>" . $fields['win_title'] . "</h2>" : "") ?>
        </div>
        <div class="sub-title">
            <?= $fields['win_subtitle'] ?>
        </div>
        <div class="triggers__list">
            <?php if (is_countable($fields['win_list'])): ?>
                <?php foreach ($fields['win_list'] as $item) : ?>
                    <div class="triggers__box">
                        <div class="triggers__name">
                            <?= $item['name'] ?>
                        </div>
                        <div class="triggers__text">
                            <?= $item['text'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if ($fields['reviews_enable']): ?>
    <?php
    $last_reviews = get_posts([
        'post_type'        => 'reviews',
        'orderby'          => 'date',
        'order'            => 'DESC',
        'posts_per_page'   => '10',
        'suppress_filters' => false,
    ]);
    ?>
    <div class="reviews">
        <div class="reviews__col">
            <div class="title">
                <?= (trim($fields['reviews_title']) != '' ? "<h2>" . $fields['reviews_title'] . "</h2>" : "") ?>
            </div>
            <div class="reviews__control">
            </div>
        </div>
        <div class="reviews__slider">
            <?php if (is_countable($last_reviews)): ?>
                <?php foreach ($last_reviews as $item) : ?>
                    <?php $item->fields = get_fields($item->ID) ?>
                    <div class="reviews__slide">
                        <div class="reviews__slide-box">
                            <div class="reviews__top">
                                <div class="reviews__image">
                                    <img src="<?= theme()->R(get_the_post_thumbnail_url($item->ID, 'full'), 'f=webp&w=72&h=72') ?>"
                                         width="72" height="72"
                                         alt="<?= $item->post_title . '-' . __("photo", 'finanzia') ?>"
                                         title="<?= $item->post_title ?>">
                                </div>
                                <div class="reviews__top-box">
                                    <div class="reviews__name">
                                        <?= $item->post_title ?>
                                    </div>
                                    <div class="reviews__position">
                                        <?= $item->fields['position'] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="reviews__text">
                                <?= $item->post_content ?>
                            </div>
                            <div class="reviews__description">
                                <div class="reviews__item-list">
                                    <div class="reviews__item">
                                        <div class="reviews__item-name">
                                            <?php _e("Amount", 'finanzia'); ?>
                                        </div>
                                        <div class="reviews__item-descr big">
                                            <?= $item->fields['amount'] ?>
                                        </div>
                                    </div>
                                    <div class="reviews__item">
                                        <div class="reviews__item-name">
                                            <?php _e("Monthly Repayment", 'finanzia'); ?>
                                        </div>
                                        <div class="reviews__item-descr big">
                                            <?= $item->fields['repayment'] ?>
                                        </div>
                                    </div>
                                    <?php if (trim($item->fields['fixation']) != ''): ?>
                                        <div class="reviews__item">
                                            <div class="reviews__item-name">
                                                <?php _e("Fixation", 'finanzia'); ?>
                                            </div>
                                            <div class="reviews__item-descr">
                                                <?= $item->fields['fixation'] ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="reviews__item">
                                        <div class="reviews__item-name">
                                            <?php _e("Loan Term", 'finanzia'); ?>
                                        </div>
                                        <div class="reviews__item-descr">
                                            <?= $item->fields['maturity'] ?>
                                        </div>
                                    </div>
                                    <div class="reviews__item">
                                        <div class="reviews__item-name">
                                            <?php _e("Interest Rate", 'finanzia'); ?>
                                        </div>
                                        <div class="reviews__item-descr">
                                            <?= $item->fields['interest_rate'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if ($fields['news_enable']): ?>

    <?php
    $last_news = get_posts([
        'post_type'        => 'articles',
        'orderby'          => 'date',
        'order'            => 'DESC',
        'posts_per_page'   => '3',
        'suppress_filters' => false,
    ]);
    ?>
    <?php if (is_countable($last_news)): ?>
        <div class="news news-slider">
            <div class="title">
                <?= (trim($fields['news_title']) != '' ? "<h2>" . $fields['news_title'] . "</h2>" : "") ?>
            </div>
            <div class="news__list">
                <?php foreach ($last_news as $item) : ?>
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
<?php endif; ?>
    <div class="main-text">
        <?php the_content(); ?>
    </div>
<?php
get_footer();
