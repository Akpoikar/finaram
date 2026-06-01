<div class="mortgages__section" id="top-sec">
    <div class="mortgages__info">
        <div class="mortgages__title">
            <h1><?= $args['title']; ?></h1>
        </div>
        <?php if (is_countable($args['list'])): ?>
            <ul class="top-section__list">
                <?php foreach ($args['list'] as $item) : ?>
                    <li><?= $item['list_item'] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="mortgages__triggers">
            <?php if (is_countable($args['triggers'])): ?>
                <?php foreach ($args['triggers'] as $item) : ?>
                    <div class="mortgages__triggers-box">
                        <div class="mortgages__triggers-num">
                            <?= $item['numbers']; ?>
                        </div>
                        <div class="mortgages__triggers-text">
                            <?= $item['text']; ?>
                            <!--                            <div class="calc__info-help">-->
                            <!--                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"-->
                            <!--                                     xmlns="http://www.w3.org/2000/svg">-->
                            <!--                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"-->
                            <!--                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>-->
                            <!--                                </svg>-->
                            <!--                            </div>-->
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="mortgages__image">
        <div class="mortgages__img">
            <img src="<?= theme()->R($args["image"]['url'], 'f=webp&w=658&h=468'); ?>"
                 width="542" height="388"
                 alt="<?= ($args["image"]['alt'] ?: $args["image"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                 title="<?= $args["image"]['title'] ?>">
        </div>
        <?php if (trim($args['image_text']) != '') : ?>
            <div class="mortgages__img-text">
                <?= $args['image_text']; ?>
            </div>
        <?php endif; ?>
        <?php if ($args["reviewer_photo"]) : ?>
            <div class="mortgages__rat">
                <div class="mortgages__rat-ico">
                    <img src="<?= theme()->R($args["reviewer_photo"]['url'], 'f=webp&w=87&h=87'); ?>"
                         width="87" height="87"
                         alt="<?= ($args["reviewer_photo"]['alt'] ?: $args["reviewer_photo"]['title'] . '-' . __("photo", 'finanzia')) ?>"
                         title="<?= $args["reviewer_photo"]['title'] ?>">
                </div>
                <div class="mortgages__rat-info">
                    <div class="mortgages__rat-num">
                        <?= $args['rate']; ?>
                        <img src="<?= theme()->getThemeUrl() ?>assets/data/star.svg" alt="star" title="star">
                    </div>
                    <div class="mortgages__rat-text">
                        <?= $args['review_text']; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>