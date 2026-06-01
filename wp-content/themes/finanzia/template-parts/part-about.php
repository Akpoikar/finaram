<div class="about-mortgage-section">
    <div class="about-mortgage" id="about-sec">
        <div class="about-mortgage__title">
            <?= $args['title']; ?>
        </div>
        <div class="about-mortgage__box">
            <div class="about-mortgage__info">
                <div class="about-mortgage__info-title">
                    <h2><?= $args['subtitle_1']; ?></h2>
                </div>
                <div class="about-mortgage__info-text">
                    <?= $args['content_1']; ?>
                </div>
            </div>
<!--            <div class="about-mortgage__image">-->
<!--                <img src="--><?php //= theme()->R($args["image"]['url'], 'f=webp&w=530&h=377'); ?><!--"-->
<!--                     width="530" height="377"-->
<!--                     alt="--><?php //= ($args["image"]['alt'] ?: $args["image"]['title'] . '-' . __("photo", 'finanzia')) ?><!--"-->
<!--                     title="--><?php //= $args["image"]['title'] ?><!--">-->
<!--            </div>-->
        </div>
        <div class="about-mortgage__add">
            <div class="about-mortgage__info-title">
                <h2><?= $args['subtitle_2']; ?></h2>
            </div>
            <div class="about-mortgage__info-text">
                <?= $args['content_2']; ?>
            </div>
        </div>
        <?php if (is_countable($args['info_blocks'])): ?>
            <div class="about-mortgage__add-list">
                <?php foreach ($args['info_blocks'] as $item) : ?>
                    <div class="about-mortgage__add-box">
                        <div class="about-mortgage__add-ico">
                            <img src="<?= $item['image']['url'] ?>"
                                 alt="<?= ($item['image']['alt'] ?: $item['image']['title'] . '-' . __("photo", 'finanzia')) ?>"
                                 title="<?= $item['image']['title'] ?>">
                        </div>
                        <div class="about-mortgage__add-info">
                            <div class="about-mortgage__add-title">
                                <h3><?= $item['name']; ?></h3>
                            </div>
                            <div class="about-mortgage__add-text">
                                <?= $item['text']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="about-mortgage__info-text">
            <?= $args['content_3']; ?>
        </div>
    </div>
</div>