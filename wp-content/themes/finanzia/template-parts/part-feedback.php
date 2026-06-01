<div class="feedback-section"  id="feedback-sec">
    <div class="feedback-section__holder">
        <div class="feedback-section__info">
            <div class="feedback-section__title">
                <?= $args['title']; ?>
            </div>
            <div class="feedback-section__text">
                <?= $args['text']; ?>
            </div>
            <div class="feedback-section__sub-title">
                <?= $args['subtitle']; ?>
            </div>
            <div class="feedback-section__text">
                <?= $args['text_2']; ?>
            </div>
            <?= do_shortcode('[contact-form-7 id="1215" title="Zavolejte zpět" html_class="contacts__form-box"]'); ?>
        </div>
        <div class="feedback-section__image">
            <?php $rand_image = $args["image"][array_rand($args["image"])]; ?>
            <img src="<?= theme()->R($rand_image['url'], 'zc=1&f=webp&w=643&h=487'); ?>"
                 width="643" height="487"
                 alt="<?= ($rand_image['alt'] ?: $rand_image['title'] . '-' . __("photo", 'finanzia')) ?>"
                 title="<?= $rand_image['title'] ?>">
        </div>
    </div>
</div>