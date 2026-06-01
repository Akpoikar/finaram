<?php $option_fields = get_fields('option'); ?>
<div class="calc__holder">
    <div class="mortgage-rent__box">
        <div class="mortgage-rent__sub-title">
            <?php _ex("Mortgage", 'mortgage vs rent row title', 'finanzia'); ?>
        </div>
        <div class="mortgage-rent__form">
            <div class="mortgage-rent__col">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Monthly mortgage payment — CZK", 'finanzia'); ?>
                        <div class="calc__info-help">
                            <?php if (trim($option_fields['mortgage_vs_rent_calculator']['monthly_repayments_info_drop']) != '') : ?>
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_rent_calculator']['monthly_repayments_info_drop'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="text" name="mpay" class="m-pay"
                           min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="pay-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['monthly_repayments']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _ex("Mortgage duration — Years", 'mortgage vs rent field', 'finanzia'); ?>
                        <div class="calc__info-help">
                            <?php if (trim($option_fields['mortgage_vs_rent_calculator']['mortgage_duration_info_drop']) != '') : ?>
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_rent_calculator']['mortgage_duration_info_drop'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="text" name="mduration" class="m-duration"
                           min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="m-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['mortgage_duration']['default_value'] ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mortgage-rent__box">
        <div class="mortgage-rent__sub-title">
            <?php _ex("Rent", 'mortgage vs rent row title', 'finanzia'); ?>
        </div>
        <div class="mortgage-rent__form">
            <div class="mortgage-rent__col">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _ex("Starting rent — CZK", 'mortgage vs rent field', 'finanzia'); ?>
                        <div class="calc__info-help">
                            <?php if (trim($option_fields['mortgage_vs_rent_calculator']['starting_rent_info_drop']) != '') : ?>
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_rent_calculator']['starting_rent_info_drop'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="text" name="mduration" class="r-start"
                           min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="r-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['starting_rent']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Rent increase — % (optional)", 'finanzia'); ?>
                        <div class="calc__info-help">
                            <?php if (trim($option_fields['mortgage_vs_rent_calculator']['rent_increase_info_drop']) != '') : ?>
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_rent_calculator']['rent_increase_info_drop'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="text" name="mpay" class="increase-input"
                           min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="increase-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_rent_calculator']['rent_increase']['default_value'] ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mortgage-rent__info">
        <div class="mortgage-rent__info-title">
            <?php _ex("Delta", 'mortgage vs rent row title', 'finanzia'); ?>
        </div>
        <div class="mortgage-rent__section">
            <div class="mortgage-rent__info-box">
                <div class="mortgage-rent__info-row">
                    <div class="mortgage-rent__info-small-t">
                        <?php _e("Monthly repayments", 'finanzia'); ?>
                        <!--<span><?php _e("(average rent throughout the term)", 'finanzia'); ?></span>-->
                    </div>
                    <div class="mortgage-rent__info-col">
                        <div class="mortgage-rent__result">
                            <div class="mortgage-rent__info-name">
                                <?php _e("Mortgage", 'finanzia'); ?>
                            </div>
                            <div class="mortgage-rent__result" id="mortgage-result-1">
                                0
                            </div>
                        </div>
                    </div>
                    <div class="mortgage-rent__info-col">
                        <div class="mortgage-rent__result">
                            <div class="mortgage-rent__info-name">
                                <?php _e("Rent", 'finanzia'); ?>
                            </div>
                            <div class="mortgage-rent__result" id="rent-result-1">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mortgage-rent__info-row">
                    <div class="mortgage-rent__info-small-t">
                        <?php _ex("Total payment", 'mortgage vs rent result', 'finanzia'); ?>
                    </div>
                    <div class="mortgage-rent__info-col">
                        <div class="mortgage-rent__result">
                            <div class="mortgage-rent__result" id="mortgage-result-2">
                                0
                            </div>
                        </div>
                    </div>
                    <div class="mortgage-rent__info-col">
                        <div class="mortgage-rent__result">
                            <div class="mortgage-rent__result" id="rent-result-2">
                                0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mortgage-rent__benefit">
                <div class="mortgage-rent__benefit-result">
                    <div class="mortgage-rent__info-name" data-good="<?php _e("Your gain", 'finanzia'); ?>"
                         data-bad="<?php _e("Your loss", 'finanzia'); ?>">
                        <?php _e("Your gain", 'finanzia'); ?>
                    </div>
                    <div class="mortgage-rent__result" id="benefit-result-1">
                        0
                    </div>
                    <div class="mortgage-rent__result" id="benefit-result-2">
                        0
                    </div>
                </div>
                <div class="mortgage-rent__info-image">
                    <div class="mortgage-rent__info-notes">
                        <?php _ex("Mortgage", 'mortgage vs rent row title', 'finanzia'); ?>
                    </div>
                    <div class="mortgage-rent__info-text" data-good="<?php _e("is a good choice", 'finanzia'); ?>"
                         data-bad="<?php _e("is a bad choice", 'finanzia'); ?>">
                    </div>
                    <div class="mortgage-rent__info-img"
                         data-good="<?= theme()->getThemeUrl(); ?>/assets/images/good.png"
                         data-bad="<?= theme()->getThemeUrl(); ?>/assets/images/bad.png">
                        <img src="" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mortgage-rent__bottom">
<!--        <div class="calc__control">-->
            <button class="calc__apply js_calc_apply js_go_aio" type="button" data-tab_name="rent_mortgage"
                    data-category="Mortgages"
                    name="button"><?php _e("Apply Online", 'finanzia'); ?></button>
            <button class="calc__callback" type="button"
                    name="button"><?php _e("Call Back", 'finanzia'); ?></button>
<!--        </div>-->
        <!--        <a class="mortgage-rent__btn" href="-->
        <?php //= get_permalink(get_page_by_path('calculators')); ?><!--">-->
        <!--            --><?php //_ex("Go to mortgages Calculators", 'mortgage vs rent button', 'finanzia'); ?>
        <!--        </a>-->
        <!--        <button class="calc__callback" type="button"-->
        <!--                name="button">--><?php //_ex("Submit Your Interest", 'mortgage vs rent button', 'finanzia'); ?>
        <!--        </button>-->
    </div>
</div>
