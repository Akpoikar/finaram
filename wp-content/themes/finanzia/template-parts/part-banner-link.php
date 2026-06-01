<div class="custom-prm-holder">
    <a href="<?= $args['link']; ?>" class="custom-prm">
        <div class="custom-prm__info">
            <div class="custom-prm__title">
                <?= $args['title']; ?>
            </div>
            <div class="custom-prm__text">
                <?= $args['text']; ?>
            </div>
        </div>
        <div class="custom-prm__img">
            <img src="<?= theme()->R($args["image"]['url'], 'f=webp&w=310&h=219'); ?>"
                 alt="<?= ($args["image"]['alt'] ?: $args["image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                 title="<?= $args["image"]['title'] ?>">
        </div>
        <div class="custom-prm__btn">
            <?= $args['button_text']; ?>
        </div>
    </a>
</div>