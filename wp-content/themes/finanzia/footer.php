<?php $option_fields = get_fields('option'); ?>

<footer class="footer">
    <div class="footer__holder">
        <div class="footer__info">
            <div class="footer__logo">
                <img src="<?= theme()->getSecondLogo(); ?>" width="108" height="25"
                     alt="<?php bloginfo('name'); ?> - <?php _e("photo", 'finanzia'); ?>"
                     title="<?php bloginfo('name'); ?>">
            </div>
            <div class="footer__text">
                <?php _e("Ready to enhance your financial future? Start with Finaram for transparent, personalized financial solutions.", 'finanzia'); ?>
            </div>

            <?php if ($social_menu = theme()->getMenuTree('social-menu')): ?>
                <ul class="footer__social">
                    <?php foreach ($social_menu as $item) : ?>
                        <li>
                            <a target="_blank" href="<?= $item['url']; ?>">
                                <img src="<?= get_field('social_logo', $item['ID']); ?>"
                                     alt="<?= $item['title']; ?> - <?php _e("photo", 'finanzia'); ?>"
                                     title="<?= $item['title']; ?>">
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if ($footer_menu = theme()->getMenuTree('footer-menu')): ?>
            <?php foreach ($footer_menu as $parent) : ?>
                <?php if (is_array($parent['children']) && count($parent['children']) > 0): ?>
                    <div class="footer__col">
                        <div class="footer__title">
                            <?= $parent['title']; ?>
                        </div>
                        <nav class="footer__list">
                            <ul>
                                <?php foreach ($parent['children'] as $child) : ?>
                                    <li>
                                        <?php if ($child['object_id'] != get_queried_object_id()) : ?>
                                            <a href="<?= $child['url']; ?>">
                                                <?= $child['title']; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="active">
                                                <?= $child['title']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    </div>
                <?php else: ?>
                    <?php if (parse_url($parent['url'], PHP_URL_PATH) != $_SERVER['REQUEST_URI']) : ?>
                        <a href="<?= $parent['url']; ?>">
                            <?= $parent['title']; ?>
                        </a>
                    <?php else: ?>
                        <span class="active">
                            <?= $parent['title']; ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="footer__col">
            <div class="footer__title">
                <?php _e("Contacts", 'finanzia'); ?>
            </div>
            <address class="footer__address">
                <?= $option_fields["contacts"]; ?>
            </address>
        </div>
    </div>
    <div class="footer__copy">
        <div class="footer__copy-text">
            © <?= get_bloginfo('name') . '&nbsp;' . date('Y'); ?>
        </div>
        <ul class="footer__copy-list">
            <?php theme()->build_menu(array('location' => 'footer-copy')); ?>
        </ul>
    </div>
</footer>
</div>
<div class="popup popup-mortgage">
    <div class="popup__overlay">
    </div>
    <div class="popup__aside">
        <div class="popup__close">
        </div>
        <div class="popup__mortgage-title">
            <?php _e('Estimated mortgage amount:', 'finanzia'); ?>
            <span class="popup__loan-text">
                <span class="popup__loan">0</span>
                <span class="popup__cur">Kč</span>
            </span>
        </div>
        <?= do_shortcode('[contact-form-7 id="9933" title="Mám zájem" html_class="popup__aside-form"]'); ?>
    </div>
</div>
<div class="popup popup-callback">
    <div class="popup__overlay">
    </div>
    <div class="popup__holder">
        <div class="popup__close">
        </div>
        <?= do_shortcode('[contact-form-7 id="1519" title="Získat konzultaci" html_class="popup__form"]'); ?>
    </div>
</div>
<div class="popup popup-interest">
    <div class="popup__overlay">
    </div>
    <div class="popup__holder">
        <div class="popup__close">
        </div>
        <?= do_shortcode('[contact-form-7 id="3417" title="Zájem (Mortgage Simulator)" html_class="popup__form"]'); ?>
    </div>
</div>
<div class="popup popup-consult">
    <div class="popup__overlay">
    </div>
    <div class="popup__aside">
        <div class="popup__close">
        </div>
        <div class="popup__aside-title">
            <?php _e("Get Consultation", 'finanzia'); ?>
        </div>
        <?= do_shortcode('[contact-form-7 id="1782" title="Získat konzultaci" html_class="popup__aside-form"]'); ?>
    </div>
</div>

<?php wp_footer(); ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BankOrCreditUnion",
        "name": "<?= get_bloginfo('name') ?>",
        "image": "<?= home_url('/') ?>/wp-content/uploads/2023/07/favicon.png",
        "@id": "Logo",
        "url": "<?= home_url('/') ?>",
        "telephone": "<?= $option_fields['contact_phone'] ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Korunní 2569/108 101 00",
            "addressLocality": "Prague",
            "postalCode": "363 01",
            "addressCountry": "CZ"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday"
            ],
            "opens": "00:00",
            "closes": "23:59"
        }
    }
</script>
</body>

</html>




