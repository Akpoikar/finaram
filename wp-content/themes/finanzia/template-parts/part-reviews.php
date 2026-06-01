<?php
$query_args = [
    'post_type'        => 'reviews',
    'orderby'          => 'date',
    'order'            => 'DESC',
    'posts_per_page'   => '10',
    'suppress_filters' => false,
];
if ($args['reviews_category']) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'reviews_category',
            'field'    => 'term_id',
            'terms'    => (int)$args['reviews_category']
        ]
    ];
}
$last_reviews = get_posts($query_args);
?>
<div class="reviews">
    <div class="reviews__col">
        <div class="title">
            <?= $args['title'] ?>
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