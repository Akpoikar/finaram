<div class="representative">
    <div class="representative__holder">
        <div class="representative__title">
            <?= $args['title']; ?>
        </div>
        <div class="sub-title">
            <?= $args['subtitle']; ?>
        </div>
        <div class="mobile-tabs-opener-2"><?= $args['tab_title_1']; ?></div>
        <ul class="representative__tabs">
            <li>
                <a href="#representative-1"><?= $args['tab_title_1']; ?></a>
            </li>
            <li>
                <a href="#representative-2"><?= $args['tab_title_2']; ?></a>
            </li>
            <li>
                <a href="#representative-3"><?= $args['tab_title_3']; ?></a>
            </li>
        </ul>
        <div class="representative__tabcontent">
            <div id="representative-1" class="tab">
                <div class="representative__list">
                    <?php if (is_countable($args['tab_1'])): ?>
                        <?php foreach ($args['tab_1'] as $item) : ?>
                            <div class="representative__item">
                                <div class="representative__name">
                                    <?= $item['title']; ?>
                                </div>
                                <div class="representative__descr">
                                    <?= $item['text']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div id="representative-2" class="tab">
                <div class="representative__list">
                    <?php if (is_countable($args['tab_2'])): ?>
                        <?php foreach ($args['tab_2'] as $item) : ?>
                            <div class="representative__item">
                                <div class="representative__name">
                                    <?= $item['title']; ?>
                                </div>
                                <div class="representative__descr">
                                    <?= $item['text']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div id="representative-3" class="tab">
                <div class="representative__list">
                    <?php if (is_countable($args['tab_3'])): ?>
                        <?php foreach ($args['tab_3'] as $item) : ?>
                            <div class="representative__item">
                                <div class="representative__name">
                                    <?= $item['title']; ?>
                                </div>
                                <div class="representative__descr">
                                    <?= $item['text']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>