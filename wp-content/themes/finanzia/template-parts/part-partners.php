<?php if (is_countable($args['images'])): ?>
    <div class="add-partners" id="loan-sec">
        <div class="add-partners__title">
            <h2><?= $args['title']; ?></h2>
        </div>
        <div class="sub-title">
            <?= $args['subtitle']; ?>
        </div>
        <div class="add-partners__list">
            <?php foreach ($args['images'] as $item) : ?>
                <div class="add-partners__box">
                    <img src="<?= theme()->R($item['url'], 'f=webp&w=212&h=42'); ?>"
                         alt="<?= ($item['alt'] ?: $item['title'] . '-' . __("photo", 'finanzia')) ?>"
                         title="<?= $item['title'] ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
