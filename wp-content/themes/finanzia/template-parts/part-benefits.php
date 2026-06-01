<div class="benefits-section">
    <div class="benefits">
        <div class="benefits__top">
            <div class="benefits__title">
                <?= $args['title']; ?>
            </div>
            <div class="benefits__buttons">
                <div class="benefits__callback callback">
                    <?php _e("Call back", 'finanzia'); ?>
                </div>
                <?php if ($args['button_link']): ?>
                    <a class="benefits__link" href="<?= $args['button_link']; ?>"><?= $args['button_name']; ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="sub-title">
            <?= $args['subtitle']; ?>
        </div>
        <?php if (is_countable($args['benefits_list'])): ?>
            <div class="benefits__list">
                <?php foreach ($args['benefits_list'] as $item) : ?>
                    <div class="benefits__box">
                        <div class="benefits__name">
                            <?= $item['name']; ?>
                        </div>
                        <div class="benefits__text">
                            <?= $item['text']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>