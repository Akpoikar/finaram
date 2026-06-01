<div class="examples-table">
    <div class="examples-table__holder">
        <div class="examples-table__title">
            <?= $args['title']; ?>
        </div>
        <div class="examples-table__text">
            <?= $args['text']; ?>
        </div>
        <div class="examples-table__box">
            <div class="examples-table__top">
                <div class="examples-table__name examples-table__name--1">
                    <?= $args['table_head']['column_head_1']; ?>
                </div>
                <div class="examples-table__name examples-table__name--2">
                    <?= $args['table_head']['column_head_2']; ?>
                </div>
                <div class="examples-table__name examples-table__name--3">
                    <?= $args['table_head']['column_head_3']; ?>
                </div>
                <div class="examples-table__name examples-table__name--4">
                    <?= $args['table_head']['column_head_4']; ?>
                </div>
            </div>
            <div class="examples-table__section">
                <?php if (is_countable($args['table_row'])): ?>
                    <?php foreach ($args['table_row'] as $item) : ?>
                        <div class="examples-table__row">
                            <div class="examples-table__item examples-table__item--1">
                                <div class="examples-table__mob">
                                    <?= $args['table_head']['column_head_1']; ?>
                                </div>
                                <div class="examples-table__descr">
                                    <?= $item['column_1']; ?>
                                </div>
                            </div>
                            <div class="examples-table__item examples-table__item--2">
                                <div class="examples-table__mob">
                                    <?= $args['table_head']['column_head_2']; ?>
                                </div>
                                <div class="examples-table__descr">
                                    <?= $item['column_2']; ?>
                                </div>
                            </div>
                            <div class="examples-table__item examples-table__item--3">
                                <div class="examples-table__mob">
                                    <?= $args['table_head']['column_head_3']; ?>
                                </div>
                                <div class="examples-table__descr">
                                    <?= $item['column_3']; ?>
                                </div>
                            </div>
                            <div class="examples-table__item examples-table__item--4">
                                <div class="examples-table__mob">
                                    <?= $args['table_head']['column_head_4']; ?>
                                </div>
                                <div class="examples-table__descr">
                                    <?= $item['column_4']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (is_countable($args['triggers'])): ?>
                <div class="triggers__list">
                    <?php foreach ($args['triggers'] as $item) : ?>
                        <div class="triggers__box">
                            <div class="triggers__name">
                                <?= $item['numbers']; ?>
                            </div>
                            <div class="triggers__text">
                                <?= $item['text']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>