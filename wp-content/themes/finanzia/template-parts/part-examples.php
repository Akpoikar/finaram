<?php if (is_countable($args['items'])): ?>
    <div class="examples">
        <div class="examples__title">
            <?= $args['title']; ?>
        </div>
        <div class="sub-title">
            <?= $args['subtitle']; ?>
        </div>
        <div class="examples__holder">
            <div class="examples__list">
                <?php foreach ($args['items'] as $item) : ?>
                    <div class="examples__box">
                        <div class="examples__info">
                            <div class="examples__name">
                                <?= $item['name']; ?>
                            </div>
                            <div class="examples__text">
                                <?= $item['text']; ?>
                            </div>
                        </div>
                        <div class="examples__image">
                            <img src="<?= theme()->R($item["image"]['url'], 'f=webp&w=144&h=188'); ?>" width="144"
                                 height="188"
                                 alt="<?= ($item["image"]['alt'] ?: $item["image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                                 title="<?= $item["image"]['title'] ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>