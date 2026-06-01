<div class="faq-section" id="faq-sec">
    <div class="faq-section__title">
        <h2><?= $args['title']; ?></h2>
    </div>
    <?php if (is_countable($args['items'])): ?>
        <ul class="faq">
            <?php foreach ($args['items'] as $k => $item) : ?>
                <li<?= ($k == 0 ? ' class="active"' : '') ?>>
                    <a href="javascript:void(0);" class="opener">
                        <h3><?= $item['question']; ?></h3>
                    </a>
                    <div class="slide">
                        <?= $item['answer']; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php if (is_countable($args['items'])): ?>
    <?php
    $mainEntity = [];
    foreach ($args['items'] as $item) {
        $mainEntity[] = (object)array(
            '@type'          => 'Question',
            'name'           => $item['question'],
            'acceptedAnswer' =>
                (object)array(
                    '@type' => 'Answer',
                    'text'  => $item['answer'],
                ),
        );
    }
    ?>
    <script type="application/ld+json">
        {
            "@context":   "https://schema.org",
            "@type":      "FAQPage",
            "mainEntity": <?= json_encode($mainEntity); ?>
        }
    </script>
<?php endif; ?>