<div class="how" id="how-sec">
    <div class="title">
        <h2><?= $args['title'] ?></h2>
    </div>
    <div class="sub-title">
        <?= $args['subtitle'] ?>
    </div>
    <div class="how__holder">
        <div class="how__images">
            <?php if (is_countable($args['steps'])): ?>
                <?php foreach ($args['steps'] as $item) : ?>
                    <div class="how__images-box">
                        <img src="<?= theme()->R($item["image"]['url'], 'f=webp&w=658&h=468'); ?>" width="658"
                             height="468"
                             alt="<?= ($item["image"]['alt'] ?: $item["image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                             title="<?= $item["image"]['title'] ?>">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <ul class="how__list">
            <?php if (is_countable($args['steps'])): ?>
                <?php foreach ($args['steps'] as $item) : ?>
                    <div class="how__box">
                        <div class="how__number">
                            <?= $item['number'] ?>
                        </div>
                        <div class="how__name">
                            <h3><?= $item['name'] ?></h3>
                        </div>
                        <div class="how__text">
                            <?= $item['text'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>