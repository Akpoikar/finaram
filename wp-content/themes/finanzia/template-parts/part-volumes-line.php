<div class="mortgages-top">
    <?php if (is_countable($args['items'])): ?>
        <?php foreach ($args['items'] as $item) : ?>
            <div class="mortgages-top__box">
                <div class="mortgages-top__name">
                    <?= $item['name']; ?>
                </div>
                <div class="mortgages-top__text">
                    <?= $item['content']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>