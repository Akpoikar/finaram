<div class="why"  id="why-sec">
    <div class="title">
        <?= $args['title'] ?>
    </div>
    <div class="sub-title">
        <?= $args['subtitle'] ?>
    </div>
    <div class="why__section">
        <div class="why__holder">
            <div class="why__three-columns">
                <div class="why__column">
                    <div class="why__label">
                        <?= $args['label'] ?>
                    </div>
                    <div class="why__logo">
                        <img src="<?= $args['logo']; ?>" width="171" height="34"
                             alt="<?= $args['title'] . '-' . __("photo", 'finanzia') ?>"
                             title="<?= $args['title'] ?>">
                    </div>
                    <div class="why__col">
                        <?php if (is_countable($args['center_column'])): ?>
                            <?php foreach ($args['center_column'] as $item) : ?>
                                <div class="why__trigger">
                                    <div class="why__trigger-name">
                                        <?= $item['name'] ?>
                                    </div>
                                    <div class="why__trigger-text">
                                        <?= $item['text'] ?>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="why__list">
                        <?php if (is_countable($args['list'])): ?>
                            <?php foreach ($args['list'] as $item) : ?>
                                <?php switch ($item["center_column"]["yesno_icon"]):
                                    case 1: ?>
                                        <div class="why__row">
                                            <div class="why__options no">
                                                <img src="<?= theme()->getThemeUrl() ?>assets/data/no.svg"
                                                     width="24"
                                                     height="24" alt="ico" title="ico">
                                                <?= $item['center_column']['text'] ?>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php case 2: ?>
                                        <div class="why__row">
                                            <div class="why__options yes">
                                                <img src="<?= theme()->getThemeUrl() ?>assets/data/yes.svg"
                                                     width="24"
                                                     height="24" alt="ico" title="ico">
                                                <?= $item['center_column']['text'] ?>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php endswitch; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="why__column">
                    <div class="why__col">
                        <div class="why__title">
                            <?= $args['left_column_title'] ?>
                        </div>
                    </div>
                    <div class="why__list">
                        <?php if (is_countable($args['list'])): ?>
                            <?php foreach ($args['list'] as $item) : ?>
                                <?php switch ($item["left_column"]["yesno_icon"]):
                                    case 1: ?>
                                        <div class="why__row">
                                            <div class="why__options no">
                                                <img src="<?= theme()->getThemeUrl() ?>assets/data/no.svg"
                                                     width="24"
                                                     height="24" alt="ico" title="ico">
                                                <?= $item['left_column']['text'] ?>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php case 2: ?>
                                        <div class="why__row">
                                            <div class="why__options yes">
                                                <img src="<?= theme()->getThemeUrl() ?>assets/data/yes.svg"
                                                     width="24"
                                                     height="24" alt="ico" title="ico">
                                                <?= $item['left_column']['text'] ?>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php endswitch; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="why__column">
                    <div class="why__col">
                        <div class="why__title">
                            <?= $args['right_column_title'] ?>
                        </div>
                    </div>
                    <div class="why__list">
                        <?php if (is_countable($args['list'])): ?>
                            <?php foreach ($args['list'] as $item) : ?>
                                <?php switch ($item["right_column"]["yesno_icon"]):
                                    case 1: ?>
                                        <div class="why__row">
                                            <div class="why__options no">
                                                <img src="<?= theme()->getThemeUrl() ?>assets/data/no.svg"
                                                     width="24"
                                                     height="24" alt="ico" title="ico">
                                                <?= $item['right_column']['text'] ?>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php case 2: ?>
                                        <div class="why__row">
                                            <div class="why__options yes">
                                                <img src="<?= theme()->getThemeUrl() ?>assets/data/yes.svg"
                                                     width="24"
                                                     height="24" alt="ico" title="ico">
                                                <?= $item['right_column']['text'] ?>
                                            </div>
                                        </div>
                                        <?php break; ?>
                                    <?php endswitch; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="why__buttons">
                <div class="why__more" data-open="<?php _e("Show more benefits", 'finanzia'); ?>"
                     data-close="<?php _e("Close more benefits", 'finanzia'); ?>">
                    <?php _e("Show more benefits", 'finanzia'); ?>
                </div>
<!--                <button class="why__calc" type="button" name="button"> --><?php //= $args['button_title'] ?><!-- </button>-->
                <a class="why__calc" href="<?= esc_url(home_url('/calculators')); ?>"><?= $args['button_title'] ?></a>
            </div>
        </div>
    </div>
</div>