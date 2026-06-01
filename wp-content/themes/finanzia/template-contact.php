<?php
/**
 * Template name: Contact template
 */
$option_fields = get_fields('option');

get_header();
?>
    <div class="contacts">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                    <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="1"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="2"/>
            </li>
        </ol>
        <div class="pagetitle">
            <?php the_title(); ?>
        </div>
        <div class="contacts__holder">
            <div class="contacts__info">
                <div class="contacts__info-box">
                    <div class="contacts__info-title">
                        <?php _e("Write us", 'finanzia'); ?>
                    </div>
                    <!--                    <div class="contacts__info-text">-->
                    <!--                        --><?php //_e("Ready to assist you with any queries", 'finanzia'); ?>
                    <!--                    </div>-->
                    <a class="contacts__info-email"
                       href="mailto:<?= $option_fields["contact_email"]; ?>"><?= $option_fields["contact_email"]; ?></a>
                </div>
                <div class="contacts__info-box">
                    <div class="contacts__info-title">
                        <?php _e("Call us", 'finanzia'); ?>
                    </div>
                    <!--                    <div class="contacts__info-text">-->
                    <!--                        --><?php //_e("Always here for support and questions", 'finanzia'); ?>
                    <!--                    </div>-->
                    <div class="contacts__info-address">
                        <?php _e('For mortgage inquiries:', 'finanzia'); ?>
                        <a class="contacts__info-email"
                           href="tel:<?= theme()->setMiniPhone($option_fields["contact_phone"]); ?>"><?= $option_fields["contact_phone"]; ?></a>
                    </div>
                    <div class="contacts__info-address">
                        <?php _e('For loan inquiries:', 'finanzia'); ?>
                        <a class="contacts__info-email"
                           href="tel:<?= theme()->setMiniPhone($option_fields["contact_phone_2"]); ?>"><?= $option_fields["contact_phone_2"]; ?></a>
                    </div>
                </div>
                <div class="contacts__info-box">
                    <div class="contacts__info-title">
                        <?php _e("Postal address", 'finanzia'); ?>
                    </div>
                    <!--                    <div class="contacts__info-text">-->
                    <!--                        --><?php //_e("Come say hello at our office", 'finanzia'); ?>
                    <!--                    </div>-->
                    <address class="contacts__info-address">
                        <?= $option_fields["contacts"]; ?>
                    </address>
                    <?php if ($social_menu = theme()->getMenuTree('social-menu')): ?>
                        <ul class="footer__social">
                            <?php foreach ($social_menu as $item) : ?>
                                <li>
                                    <a target="_blank" href="<?= $item['url']; ?>">
                                        <img src="<?= get_field('social_logo', $item['ID']); ?>"
                                             alt="<?= $item['post_title']; ?> - <?php _e("photo", 'finanzia'); ?>"
                                             title="<?= $item['post_title']; ?>">
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <div class="contacts__form">
                <div class="contacts__title">
                    <?php _e("Contact us", 'finanzia'); ?>
                </div>
                <?= do_shortcode('[contact-form-7 id="628" title="Spojte se s námi" html_class="contacts__form-box"]'); ?>
            </div>
        </div>
    </div>
<?php
get_footer();
