<?php
/**
 * Template name: Watchdog template
 */
$fields = get_fields();

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
        <div class="watchdog-text">
            <?php the_content(); ?>
        </div>
        <div class="contacts__holder watchdog">
            <div class="contacts__form">
                <?php if (isset($_SESSION['watchdog-ok'])) : ?>
                    <div class="infobox" style="color: white; text-align: center;">
                        <p><?php _e("Your alert has been created.", 'finanzia'); ?></p>
                    </div>
                <?php endif;
                unset($_SESSION['watchdog-ok']); ?>
                <div class="contacts__title">
                    <?php _e("Fill out the form", 'finanzia'); ?>
                </div>
                <form id="watchdog-form" class="contacts__form-box" method="post">
                    <?php wp_nonce_field('watchdog-form-action', 'watchdog-form-field'); ?>
                    <div class="contacts__row">
                        <div class="contacts__label">
                            <?php _e("Credit type", 'finanzia'); ?>
                        </div>
                        <select class="select credit-type" name="watchdog[credit_type]" required>
                            <option value=""><?php _e("Select credit type", 'finanzia'); ?></option>
                            <option value="Mortgage"><?php _ex("Mortgage", 'watchdog', 'finanzia'); ?></option>
                            <option value="Loan"><?php _ex("Loan", 'watchdog', 'finanzia'); ?></option>
                            <!--                            <option value="credit_card">-->
                            <?php //_e("Credit card", 'finanzia'); ?><!--</option>-->
                        </select>
                        <div class="contacts__add-mortgate">
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="new_mortgage" required>
                                <span class="contacts__add-text"><?php _e("New mortgage", 'finanzia'); ?></span>
                            </label>
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="refinancing" required>
                                <span class="contacts__add-text"><?php _e("Refinancing", 'finanzia'); ?></span>
                            </label>
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="american_mortgage" required>
                                <span class="contacts__add-text"><?php _e("American mortgage", 'finanzia'); ?></span>
                            </label>
                        </div>
                        <div class="contacts__add-loan">
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="personal_loan" required>
                                <span class="contacts__add-text"><?php _e("Personal loan", 'finanzia'); ?></span>
                            </label>
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="auto_loan" required>
                                <span class="contacts__add-text"><?php _e("Auto loan", 'finanzia'); ?></span>
                            </label>
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="refinancing_consolidation"
                                       required>
                                <span class="contacts__add-text"><?php _e("Refinancing & Consolidation", 'finanzia'); ?></span>
                            </label>
                            <label class="contacts__add-item">
                                <input type="radio" name="watchdog[credit_type]" value="housing_renovation" required>
                                <span class="contacts__add-text"><?php _e("Housing & Renovation", 'finanzia'); ?></span>
                            </label>
                        </div>
                    </div>
                    <div class="contacts__row">
                        <div class="contacts__label">
                            <?php _e("Limit rate %", 'finanzia'); ?>
                        </div>
                        <input type="number" name="watchdog[rate]" class="field-sum" min="1" max="20" step="0.1"
                               value="3" required>
                        <div class="rangeholder">
                            <input id="whatrange" type="range" min="1" max="20" step="0.1" value="4">
                        </div>
                    </div>
                    <div class="contacts__row">
                        <div class="contacts__label">
                            <?php _e("Full name", 'finanzia'); ?>
                        </div>
                        <input type="text" name="watchdog[name]"
                               placeholder="<?php _e("Enter your full name", 'finanzia'); ?>" value="" required>
                    </div>
                    <div class="contacts__row">
                        <div class="contacts__label">
                            <?php _e("Email", 'finanzia'); ?>
                        </div>
                        <input type="email" name="watchdog[email]"
                               placeholder="<?php _e("Enter your email", 'finanzia'); ?>" value="" required>
                    </div>
                    <div class="contacts__row">
                        <div class="contacts__label">
                            <?php _e("Phone number", 'finanzia'); ?>
                        </div>
                        <input class="tel-input" type="tel" name="watchdog[phone]" value="" required>
                    </div>
                    <div class="contacts__row">
                        <div class="big-form__check-box" style="color: #9f9f9f !important;">
                            <label class="big-form__check">
                                <input id="watchdog-acceptance" type="checkbox" name="watchdog[agreement]" value="Yes" required>
                                <span class="big-form__check-ico"></span>
                            </label>
                            <?php _e("I agree to the", 'finanzia'); ?>&nbsp;
                            <a target="_blank"
                               href="<?= get_the_permalink(1792); ?>"><?php _e("terms and conditions", 'finanzia'); ?></a>
                            &nbsp;<?php _e("and", 'finanzia'); ?>&nbsp;
                            <a target="_blank"
                               href="<?= get_privacy_policy_url(); ?>"><?php _e("privacy policy", 'finanzia'); ?></a>.
                        </div>
                    </div>
                    <div class="contacts__buttons">
                        <input id="watchdog-submit" class="contacts__btn" type="submit" value="<?php _e("Create Alert", 'finanzia'); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php

get_footer();
