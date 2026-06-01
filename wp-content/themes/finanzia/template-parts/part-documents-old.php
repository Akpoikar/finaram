<div class="docs" id="doc-sec">
    <div class="docs__holder">
        <div class="docs__title">
            <?= $args['title']; ?>
        </div>
        <div class="docs__text">
            <?= $args['text']; ?>
        </div>
        <?php if (is_countable($args['documents'])): ?>
            <ul class="docs__list">
                <?php foreach ($args['documents'] as $item) : ?>
                    <li>
                        <a download href="<?= $item['file']['url']; ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.99479 18.3307H14.9948C15.9153 18.3307 16.6615 17.5846 16.6615 16.6641V8.18775C16.6615 7.74572 16.4859 7.3218 16.1733 7.00924L11.3163 2.15222C11.0037 1.83965 10.5798 1.66406 10.1378 1.66406H4.99479C4.07432 1.66406 3.32812 2.41025 3.32812 3.33073V16.6641C3.32812 17.5846 4.07432 18.3307 4.99479 18.3307Z"
                                      stroke="#DD6B3A" stroke-width="1.25" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                                <path d="M10.8281 2.08594V7.5026H15.8281" stroke="#DD6B3A" stroke-width="1.25"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.66406 14.1641H12.4974" stroke="#DD6B3A" stroke-width="1.25"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.66406 10.8359H12.4974" stroke="#DD6B3A" stroke-width="1.25"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.66406 7.5H7.4974" stroke="#DD6B3A" stroke-width="1.25"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                            <?php if ($item['file_name']): ?>
                                <?= $item['file_name']; ?>
                            <?php else: ?>
                                <?= $item['file']['title']; ?>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>