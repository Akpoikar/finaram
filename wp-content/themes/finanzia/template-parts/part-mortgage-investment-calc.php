<?php $option_fields = get_fields('option'); ?>

<div class="calc__holder">
    <div class="mortgage-invest__box">
        <div class="mortgage-invest__form">
            <div class="mortgage-invest__col">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Property price — CZK", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['property_price_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['property_price_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="pprice" class="p-price"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="p-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['property_price']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Down payment — %", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['down_payment_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['down_payment_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="dpay" class="d-pay"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="d-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['down_payment']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Interest rate — %", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['interest_rate_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['interest_rate_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="ir-select" class="ir-select"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="ir-select-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['interest_rate']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Monthly Mortgage Payment", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="cyear" class="invest-monthly"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="monthly-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['monthly_mortgage_payment']['default_value'] ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mortgage-invest__box">
        <div class="mortgage-invest__form">
            <div class="mortgage-invest__col">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Initial Rent Payment — CZK", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['initial_rent_payment_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['initial_rent_payment_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="srent" class="s-rent"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="s-rent-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['initial_rent_payment']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Rent increase — % (optional)", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['rent_increase_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['rent_increase_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="rentinc" class="rentinc"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="rentinc-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['rent_increase']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Expected property increase — %", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['expected_property_increase_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['expected_property_increase_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="inc" class="inc"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['expected_property_increase']['max'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['expected_property_increase']['default_value'] ?>">
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Comparison year", 'finanzia'); ?>
                        <?php if (trim($option_fields['mortgage_vs_investment_calculator']['comparison_year_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['mortgage_vs_investment_calculator']['comparison_year_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="cyear" class="c-year"
                           min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['min'] ?>"
                           max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['max'] ?>"
                           step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['step'] ?>"
                           value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="c-range" type="range"
                               min="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['min'] ?>"
                               max="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['max'] ?>"
                               step="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['step'] ?>"
                               value="<?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['default_value'] ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mortgage-invest__info">
        <div class="mortgage-invest__section">
            <div class="mortgage-invest__info-box">
                <div class="mortgage-invest__info-row">
                    <div class="mortgage-invest__info-small-t">
                        <?php _e("After", 'finanzia'); ?>
                        <strong id="mortgage-invest__info-year">
                            <?= (float)$option_fields['mortgage_vs_investment_calculator']['comparison_year']['default_value'] ?>
                        </strong>
                        <?php _e("years", 'finanzia'); ?>
                    </div>
                    <div class="mortgage-invest__info-col">
                        <div class="mortgage-invest__result">
                            <div id="mortgage-invest__info-1" class="mortgage-invest__info-name"
                                 data-bad="<?php _e("Your net loss with a mortgage is", 'finanzia'); ?>"
                                 data-good="<?php _e("Your net gain with a mortgage is", 'finanzia'); ?>">
                            </div>
                            <div class="mortgage-invest__result" id="invest-result-1">
                                0
                            </div>
                        </div>
                    </div>
                    <div class="mortgage-invest__info-col">
                        <div class="mortgage-invest__result">
                            <div class="mortgage-invest__info-name">
                                <?php _e("Your net loss with rent payments is", 'finanzia'); ?>
                            </div>
                            <div class="mortgage-invest__result" id="invest-result-2">
                                0
                            </div>
                        </div>
                    </div>
                    <div class="mortgage-invest__info-col">
                        <div class="mortgage-invest__result">
                            <div class="mortgage-invest__info-name" id="invest-result-text"
                                 data-good="<?php _e("By choosing a mortgage, you save", 'finanzia'); ?>"
                                 data-bad="<?php _e("Choosing to rent saves you", 'finanzia'); ?>">
                            </div>
                            <div class="mortgage-invest__result" id="invest-result-3">
                                0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mortgage-invest__benefit">
                <div class="mortgage-invest__benefit-result">
                    <div class="mortgage-invest__info-name">
                        <?php _e("Is getting a mortgage a good idea?", 'finanzia'); ?>
                    </div>
                    <div class="mortgage-invest__result-w"
                         data-good="<?php _e("Yes", 'finanzia'); ?>"
                         data-bad="<?php _e("No", 'finanzia'); ?>">
                    </div>
                </div>
                <div class="mortgage-invest__info-image">
                    <div class="mortgage-invest__info-img"
                         data-good="<?= theme()->getThemeUrl(); ?>/assets/images/good.png"
                         data-bad="<?= theme()->getThemeUrl(); ?>/assets/images/bad.png">
                        <img src="" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mortgage-invest__bottom">
        <button class="calc__apply js_calc_apply js_go_aio" type="button" data-tab_name="investment_mortgage" data-category="Mortgages"
                name="button"><?php _e("Apply Online", 'finanzia'); ?></button>
        <button class="calc__callback" type="button"
                name="button"><?php _e("Call Back", 'finanzia'); ?></button>
<!--        <a class="mortgage-invest__btn" href="--><?php //= get_permalink(get_page_by_path('calculators')); ?><!--">-->
<!--            --><?php //_e("Go to mortgages Calculators", 'finanzia'); ?>
<!--        </a>-->
<!--        <button class="calc__callback" type="button" name="button">--><?php //_e("Call Back", 'finanzia'); ?><!--</button>-->
    </div>
</div>