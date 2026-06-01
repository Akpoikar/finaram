<div class="cost" id="cost-sec">
    <div class="cost__title">
        <?= $args['title']; ?>
    </div>
    <div class="cost__text">
        <?= $args['text']; ?>
    </div>
    <div class="cost__holder">
        <?php if (is_countable($args['cost_list'])): ?>
            <ul class="cost__list">
                <?php foreach ($args['cost_list'] as $item) : ?>
                    <li class="cost__box active">
                        <div class="cost__number">
                            <?= $item['number']; ?>
                        </div>
                        <div class="cost__info">
                            <?php if (isset($args['title_link'])): ?>
                                <a href="<?= $item['title_link']; ?>" class="cost__name opener">
                                    <?= $item['title']; ?>
                                </a>
                            <?php else: ?>
                                <span class="cost__name opener">
                                    <?= $item['title']; ?>
                                </span>
                            <?php endif; ?>
                            <div class="cost__info-text slide">
                                <?= $item['text']; ?>
                            </div>
                        </div>
                        <div class="cost__free">
                            <?= $item['cost']; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="cost__images">
            <div class="cost__image">
                <?php $rand_image = $args["image"][array_rand((array)$args["image"])]; ?>
                <img src="<?= theme()->R($rand_image['url'], 'zc=1&f=webp&w=436&h=560'); ?>"
                     width="436" height="560"
                     alt="<?= ($rand_image['alt'] ?: $rand_image['title'] . '-' . __("photo", 'finanzia')) ?>"
                     title="<?= $rand_image['title'] ?>">
            </div>
            <div class="cost__image-box">
                <div class="cost__item">
                    <div class="cost__item-title">
                        <?= $args['total_title']; ?>
                    </div>
                    <div class="cost__item-text">
                        <?= $args['total_text']; ?>
                    </div>
                    <div class="cost__item-price">
                        <?= $args['total_price']; ?>
                    </div>
                </div>
                <div class="callback mortgage-rent__btn">
                    <?php _e("Call back", 'finanzia'); ?>
                </div>
            </div>
        </div>
    </div>
</div>