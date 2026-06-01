<?php $option_fields = get_fields('option'); ?>
<div class="calc add-calc">
    <div class="calc__holder">
        <div class="js_tab" data-tab_name="simple_mortgage" data-category="Mortgages">
            <div class="calc__section" id="add-calc">
                <div class="calc__form">
                    <div class="calc__row">
                        <div class="contacts__label">
                            <?php _e("Property price — CZK", 'finanzia'); ?>
                        </div>
                        <input type="text" name="sum" class="property-sum"
                               min="<?= (float)$option_fields['simple_mortgage']['property_price']['min'] ?>"
                               max="<?= (float)$option_fields['simple_mortgage']['property_price']['max'] ?>"
                               step="<?= (float)$option_fields['simple_mortgage']['property_price']['step'] ?>"
                               value="<?= (float)$option_fields['simple_mortgage']['property_price']['default_value'] ?>">
                        <div class="rangeholder">
                            <input class="form-range" type="range"
                                   min="<?= (float)$option_fields['simple_mortgage']['property_price']['min'] ?>"
                                   max="<?= (float)$option_fields['simple_mortgage']['property_price']['max'] ?>"
                                   step="<?= (float)$option_fields['simple_mortgage']['property_price']['step'] ?>"
                                   value="<?= (float)$option_fields['simple_mortgage']['property_price']['default_value'] ?>">
                        </div>
                    </div>
                    <div class="calc__columns">
                        <div class="calc__col">
                            <div class="contacts__label">
                                <?php _e("Down payment — %", 'finanzia'); ?>
                            </div>
                            <input type="number" name="sum" class="d-payment"
                                   min="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['min'] ?>"
                                   max="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['max'] ?>"
                                   step="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['step'] ?>"
                                   value="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['default_value'] ?>">
                            <div class="rangeholder">
                                <input class="d-range" type="range"
                                       min="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['min'] ?>"
                                       max="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['max'] ?>"
                                       step="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['step'] ?>"
                                       value="<?= (float)$option_fields['simple_mortgage']['down_payment_percent']['default_value'] ?>">
                            </div>
                        </div>
                        <div class="calc__col">
                            <div class="contacts__label">
                                <?php _e("Down payment — CZK", 'finanzia'); ?>
                            </div>
                            <input class="downpay" type="text" name="sum"
                                   min="<?= (float)$option_fields['simple_mortgage']['down_payment_readonly_values']['min'] ?>"
                                   max="<?= (float)$option_fields['simple_mortgage']['down_payment_readonly_values']['max'] ?>"
                                   step="<?= (float)$option_fields['simple_mortgage']['down_payment_readonly_values']['step'] ?>"
                                   value="<?= (float)$option_fields['simple_mortgage']['down_payment_readonly_values']['default_value'] ?>">
                            <div class="calc__min-v">
                                <?php _e("Min Value", 'finanzia'); ?> <?= (float)$option_fields['simple_mortgage']['down_payment_readonly_values']['min'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="calc__bottom">
                        <div class="calc__bottom-box">
                            <div class="contacts__label">
                                <?php _e("Duration — Years", 'finanzia'); ?>
                            </div>
                            <input type="number" name="sum" class="d-years js_duration"
                                   min="<?= (float)$option_fields['simple_mortgage']['duration']['min'] ?>"
                                   max="<?= (float)$option_fields['simple_mortgage']['duration']['max'] ?>"
                                   step="<?= (float)$option_fields['simple_mortgage']['duration']['step'] ?>"
                                   value="<?= (float)$option_fields['simple_mortgage']['duration']['default_value'] ?>">
                            <div class="rangeholder">
                                <input class="y-range" type="range"
                                       min="<?= (float)$option_fields['simple_mortgage']['duration']['min'] ?>"
                                       max="<?= (float)$option_fields['simple_mortgage']['duration']['max'] ?>"
                                       step="<?= (float)$option_fields['simple_mortgage']['duration']['step'] ?>"
                                       value="<?= (float)$option_fields['simple_mortgage']['duration']['default_value'] ?>">
                            </div>
                        </div>
                        <div class="calc__bottom-select">
                            <div class="contacts__label">
                                <?php _e("Interest rate — %", 'finanzia'); ?>
                            </div>
                            <input class="fix-year" type="text"
                                   min="<?= (float)$option_fields['simple_mortgage']['interest_rate']['min'] ?>"
                                   max="<?= (float)$option_fields['simple_mortgage']['interest_rate']['max'] ?>"
                                   step="<?= (float)$option_fields['simple_mortgage']['interest_rate']['step'] ?>"
                                   value="<?= (float)$option_fields['simple_mortgage']['interest_rate']['default_value'] ?>">
                        </div>
                        <div class="calc__bottom-select">
                            <div class="contacts__label">
                                <?php _ex("Insurance payment", 'Simple mortgage field', 'finanzia'); ?>
                            </div>
                            <input class="check" type="text"
                                   min="<?= (float)$option_fields['simple_mortgage']['insurance_payment']['min'] ?>"
                                   max="<?= (float)$option_fields['simple_mortgage']['insurance_payment']['max'] ?>"
                                   step="<?= (float)$option_fields['simple_mortgage']['insurance_payment']['step'] ?>"
                                   value="<?= (float)$option_fields['simple_mortgage']['insurance_payment']['default_value'] ?>">
                        </div>
                    </div>
                </div>
                <div class="calc__info">
                    <div class="calc__buttons js_currency">
                        <button class="calc__btn-1 active" data-cur="<?= $option_fields['currency_rates']['czk'] ?>"
                                data-cur_name="czk"
                                type="button" name="button">
                            <img src="<?= theme()->getThemeUrl() ?>assets/data/calc-flag-1.svg"
                                 alt="calc-flag-1" title="calc-flag-1">
                            CZK
                        </button>
                        <button class="calc__btn-2" data-cur="<?= $option_fields['currency_rates']['usd'] ?>"
                                data-cur_name="usd"
                                type="button" name="button">
                            <img src="<?= theme()->getThemeUrl() ?>assets/data/calc-flag-2.svg"
                                 alt="calc-flag-2" title="calc-flag-2">
                            USD
                        </button>
                        <button class="calc__btn-3" data-cur="<?= $option_fields['currency_rates']['eur'] ?>"
                                data-cur_name="eur"
                                type="button" name="button">
                            <img src="<?= theme()->getThemeUrl() ?>assets/data/calc-flag-3.svg"
                                 alt="calc-flag-3" title="calc-flag-3">
                            EUR
                        </button>
                    </div>
                    <div class="calc__info-list">
                        <div class="calc__info-box">
                            <div class="calc__info-name">
                                <?php _e("Loan amount", 'finanzia'); ?>
                                <div class="calc__info-help">
                                    <?php if (trim($option_fields['simple_mortgage']['loan_amount_info_drop']) != '') : ?>
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                                  stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="calc__info-drop">
                                            <?= $option_fields['simple_mortgage']['loan_amount_info_drop'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="calc__info-result">
                                <span class="loan js_loan_amount"></span>
                                <span class="currency"><?php _e('CZK', 'finanzia'); ?></span>
                            </div>
                        </div>
                        <div class="calc__info-box">
                            <div class="calc__info-name">
                                <?php _ex("Total payment amount", 'simple mortgage result', 'finanzia'); ?>
                                <div class="calc__info-help">
                                    <?php if (trim($option_fields['simple_mortgage']['total_payment_amount_info_drop']) != '') : ?>
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                                  stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="calc__info-drop">
                                            <?= $option_fields['simple_mortgage']['total_payment_amount_info_drop'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="calc__info-result">
                                <span class="total js_total_payment_amount"></span>
                                <span class="currency"><?php _e('CZK', 'finanzia'); ?></span>
                            </div>
                        </div>
                        <div class="calc__info-box">
                            <div class="calc__info-name">
                                <?php _e("Interest rate", 'finanzia'); ?>
                                <div class="calc__info-help">
                                    <?php if (trim($option_fields['simple_mortgage']['interest_rate_info_drop']) != '') : ?>
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                                  stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="calc__info-drop">
                                            <?= $option_fields['simple_mortgage']['interest_rate_info_drop'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="calc__info-result">
                                <span class="rate js_interest_rate"></span>
                                %
                            </div>
                        </div>
                        <div class="calc__info-box">
                            <div class="calc__info-name">
                                <?php _e("RPSN", 'finanzia'); ?>
                                <div class="calc__info-help">
                                    <?php if (trim($option_fields['simple_mortgage']['rpsn_info_drop']) != '') : ?>
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                                  stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="calc__info-drop">
                                            <?= $option_fields['simple_mortgage']['rpsn_info_drop'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="calc__info-result">
                                <span class="rpsn js_rpsn"></span>
                                %
                            </div>
                        </div>
                        <div class="calc__info-box">
                            <div class="calc__info-name">
                                <?php _ex("Insurance payment", 'Simple mortgage result', 'finanzia'); ?>
                                <div class="calc__info-help">
                                    <?php if (trim($option_fields['simple_mortgage']['insurance_payment_info_drop']) != '') : ?>
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                                  stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="calc__info-drop">
                                            <?= $option_fields['simple_mortgage']['insurance_payment_info_drop'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="calc__info-result">
                                <span class="ip js_insurance_payment"></span>
                                <span class="currency"><?php _e('CZK', 'finanzia'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="calc__fixed">
                        <div class="calc__fixed-box">
                            <div class="calc__total">
                                <div class="calc__total-name">
                                    <?php _e("Monthly payment", 'finanzia'); ?>
                                </div>
                                <div class="calc__total-num">
                                    <span class="maintotal js_monthly_payment"></span>
                                    <span class="currency"><?php _e('CZK', 'finanzia'); ?></span>
                                </div>
                            </div>
                            <div class="calc__new-info">
                                <?= $option_fields['calculators']['disclaimer']; ?>
                            </div>
                            <div class="calc__control">
                                <button class="calc__apply js_calc_apply js_go_aio" type="button"
                                        name="button">
                                    <?php _e("Apply Online", 'finanzia'); ?></button>
                                <button class="calc__callback" type="button"
                                        name="button">
                                    <?php _e("Call Back", 'finanzia'); ?></button>
                                <!--                                <button class="calc__interest" type="button"-->
                                <!--                                        name="button">-->
                                <?php //_e("Submit Your Interest", 'finanzia'); ?><!--</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>