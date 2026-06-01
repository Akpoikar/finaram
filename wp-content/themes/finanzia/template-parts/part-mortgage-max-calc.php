<?php $option_fields = get_fields('option'); ?>

<div class="calc max-mortgage">
    <!--    <div class="title">-->
    <!--        --><?php //_ex('Maximum mortgage calculator', 'max mortgage title', 'finanzia'); ?>
    <!--    </div>-->
    <div class="calc__holder">
        <div class="calc__section" id="max-mortgage">
            <div class="calc__form">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e('Net monthly income', 'finanzia'); ?>
                        <?php if (trim($option_fields['maximum_mortgage_calculator']['net_monthly_income_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['maximum_mortgage_calculator']['net_monthly_income_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="sum" class="property-sum"
                           data-ir="<?= (float)$option_fields['maximum_mortgage_calculator']['expected_interest_rate'] ?>"
                           data-year="<?= (float)$option_fields['maximum_mortgage_calculator']['duration_of_the_mortgage'] ?>"
                           data-coeff="<?= (float)$option_fields['maximum_mortgage_calculator']['max_repayments_coefficient'] ?>"
                           min="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['min'] ?>"
                           max="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['max'] ?>"
                           step="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['step'] ?>"
                           value="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="form-range" type="range"
                               min="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['min'] ?>"
                               max="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['max'] ?>"
                               step="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['step'] ?>"
                               value="<?= (float)$option_fields['maximum_mortgage_calculator']['net_monthly_income']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e('Monthly loan payments', 'finanzia'); ?>
                        <?php if (trim($option_fields['maximum_mortgage_calculator']['monthly_loan_payments_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['maximum_mortgage_calculator']['monthly_loan_payments_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="text" name="sum" class="mounth-sum"
                           min="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['min'] ?>"
                           max="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['max'] ?>"
                           step="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['step'] ?>"
                           value="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="mounth-range" type="range"
                               min="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['min'] ?>"
                               max="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['max'] ?>"
                               step="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['step'] ?>"
                               value="<?= (float)$option_fields['maximum_mortgage_calculator']['monthly_loan_payments']['default_value'] ?>">
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
                    <div class="calc__max-title">
                        <?php _e('Maximum mortgage you may be eligible for', 'finanzia'); ?>
                        <?php if (trim($option_fields['maximum_mortgage_calculator']['max_mortgage_info_drop']) != '') : ?>
                            <div class="calc__info-help">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                          stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="calc__info-drop">
                                    <?= $option_fields['maximum_mortgage_calculator']['max_mortgage_info_drop'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="calc__max-sum">
                        <span class="loan">200,000</span>
                        <span class="currency"><?php _e('CZK', 'finanzia'); ?></span>
                    </div>
                    <div class="calc__max-notes">
                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                  stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <?= $option_fields['maximum_mortgage_calculator']['disclaimer']; ?>
                    </div>
                    <div class="calc__control">
                        <button class="calc__apply js_calc_apply js_go_aio" type="button"
                                data-tab_name="maximum_mortgage" data-category="Mortgages"
                                name="button"><?php _e("Apply Online", 'finanzia'); ?></button>
                        <button class="calc__callback" type="button"
                                name="button"><?php _e("Call Back", 'finanzia'); ?></button>
                    </div>
                    <!--                    <button class="calc__max-btn" type="button" name="button">-->
                    <!--                        --><?php //_e('Get My Offer', 'finanzia'); ?>
                    <!--                    </button>-->
                </div>
            </div>
        </div>
    </div>
</div>