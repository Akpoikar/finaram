<?php $option_fields = get_fields('option'); ?>
<div class="calcs-page">
    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                <span itemprop="name"><?php _e("Home", 'finanzia'); ?></span>
            </a>
            <meta itemprop="position" content="1"/>
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a class="ajax-link" itemscope itemtype="https://schema.org/WebPage" itemprop="item"
               itemid="<?php echo esc_url(home_url('/calculators')); ?>"
               href="<?php echo esc_url(home_url('/calculators')); ?>">
                <span itemprop="name"><?php _e("Calculators", 'finanzia'); ?></span>
            </a>
            <meta itemprop="position" content="2"/>
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php the_title(); ?></span>
            <meta itemprop="position" content="3"/>
        </li>
    </ol>
    <div class="pagetitle">
        <h1>
            <?php the_title(); ?>
        </h1>
    </div>
    <div class="calcs-page__notes">
        <!--        --><?php //= get_the_excerpt(); ?>
    </div>
    <div class="calc__holder">
        <div class="calc__section" id="calculator1">
            <div class="calc__form<?= ($option_fields['calculation_type'] ? ' second-calc' : '') ?>">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Property price — CZK", 'finanzia'); ?>
                    </div>
                    <input type="text" name="sum" class="property-sum"
                           min="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['min'] ?>"
                           max="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['max'] ?>"
                           step="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['step'] ?>"
                           value="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="form-range" type="range"
                               min="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['new_mortgage']['property_price']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__columns">
                    <div class="calc__col">
                        <div class="contacts__label">
                            <?php _e("Down payment — %", 'finanzia'); ?>
                        </div>
                        <input type="number" name="sum" class="d-payment"
                               min="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['default_value'] ?>">
                        <div class="rangeholder">
                            <input class="d-range" type="range"
                                   min="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['min'] ?>"
                                   max="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['max'] ?>"
                                   step="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['step'] ?>"
                                   value="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_percent']['default_value'] ?>">
                        </div>
                    </div>
                    <div class="calc__col">
                        <div class="contacts__label">
                            <?php _e("Down payment — CZK", 'finanzia'); ?>
                        </div>
                        <input class="downpay" type="text" name="sum"
                               min="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_readonly_values']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_readonly_values']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_readonly_values']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['new_mortgage']['down_payment_readonly_values']['default_value'] ?>">
                        <div class="calc__min-v">
                                    <?php _e("Min Value", 'finanzia'); ?> <?= number_format((float)$option_fields['calculators']['new_mortgage']['down_payment_readonly_values']['min'], 0, '.', ' '); ?>
                        </div>
                    </div>
                </div>
                <div class="calc__bottom">
                    <div class="calc__bottom-box">
                        <div class="contacts__label">
                            <?php _e("Duration — Years", 'finanzia'); ?>
                        </div>
                        <input type="number" name="sum" class="d-years js_duration"
                               min="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['default_value'] ?>">
                        <div class="rangeholder">
                            <input class="y-range" type="range"
                                   min="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['min'] ?>"
                                   max="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['max'] ?>"
                                   step="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['step'] ?>"
                                   value="<?= (float)$option_fields['calculators']['new_mortgage']['duration']['default_value'] ?>">
                        </div>
                    </div>
                    <div class="calc__bottom-select">
                        <div class="contacts__label">
                            <?php _e("Fixation — Years", 'finanzia'); ?>
                        </div>
                        <select id="selectOption" class="select fix-year" name="">
                            <?php if (is_countable($option_fields['calculators']['new_mortgage']['fixation'])): ?>
                                <?php foreach ($option_fields['calculators']['new_mortgage']['fixation'] as $item) : ?>
                                    <option value="<?= $item['value_over_80'] ?>"><?= $item['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="calc__bottom-select">
                        <div class="contacts__label">
                            <?php _e("Age — Years", 'finanzia'); ?>
                        </div>
                        <select class="select old" name="">
                            <?php if (
                                $option_fields['calculators']['new_mortgage']['age']['min'] > 0 &&
                                $option_fields['calculators']['new_mortgage']['age']['max'] > 0 &&
                                $option_fields['calculators']['new_mortgage']['age']['min'] < $option_fields['calculators']['new_mortgage']['age']['max']
                            ) : ?>
                                <?php for (
                                    $i = $option_fields['calculators']['new_mortgage']['age']['min'];
                                    $i <= $option_fields['calculators']['new_mortgage']['age']['max'];
                                    $i++
                                ) : ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="calc__check">
                    <label class="calc__check-box">
                        <input class="check" type="checkbox" name=""
                               value="<?= $option_fields['calculators']['new_mortgage']['insurance_payment_value'] ?>">
                                <span class="calc__check-text"><?php _ex("Insurance payment", 'Mortgages calculators > new mortgage', 'finanzia'); ?>
<!-- <span class="calc__check-sum">-->
                            <?php //= $option_fields['calculators']['new_mortgage']['insurance_payment_value'] ?><!--</span> CZK)</span>-->
                    </label>
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
                                <?php if (trim($option_fields['calculators']['new_mortgage']['loan_amount_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['new_mortgage']['loan_amount_info_drop'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="calc__info-result">
                            <span class="loan js_loan_amount"></span>
                            <span class="currency"><?php _e('CZK' , 'finanzia'); ?></span>
                        </div>
                    </div>
                    <div class="calc__info-box">
                        <div class="calc__info-name">
                            <?php _ex("Total payment amount", 'Mortgages calculators', 'finanzia'); ?>
                            <div class="calc__info-help">
                                <?php if (trim($option_fields['calculators']['new_mortgage']['total_payment_amount_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['new_mortgage']['total_payment_amount_info_drop'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="calc__info-result">
                            <span class="total js_total_payment_amount"></span>
                            <span class="currency"><?php _e('CZK' , 'finanzia'); ?></span>
                        </div>
                    </div>
                    <div class="calc__info-box">
                        <div class="calc__info-name">
                            <?php _e("Interest rate", 'finanzia'); ?>
                            <div class="calc__info-help">
                                <?php if (trim($option_fields['calculators']['new_mortgage']['interest_rate_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['new_mortgage']['interest_rate_info_drop'] ?>
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
                                <?php if (trim($option_fields['calculators']['new_mortgage']['rpsn_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['new_mortgage']['rpsn_info_drop'] ?>
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
                                    <?php _ex("Insurance payment", 'Mortgages calculators result', 'finanzia'); ?>
                            <div class="calc__info-help">
                                <?php if (trim($option_fields['calculators']['new_mortgage']['insurance_payment_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['new_mortgage']['insurance_payment_info_drop'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="calc__info-result">
                            <span class="ip js_insurance_payment"></span>
                            <span class="currency"><?php _e('CZK' , 'finanzia'); ?></span>
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
                                <span class="currency"><?php _e('CZK' , 'finanzia'); ?></span>
                            </div>
                        </div>
                        <div class="calc__new-info">
                            <?= $option_fields['calculators']['disclaimer']; ?>
                        </div>
                        <div class="calc__control">
                            <button class="calc__apply js_calc_apply js_go_aio" type="button" data-tab_name="new_mortgage" data-category="Mortgages"
                                    name="button"><?php _e("Apply Online", 'finanzia'); ?></button>
                            <button class="calc__callback" type="button"
                                    name="button"><?php _e("Call Back", 'finanzia'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $jsonData = [];
        if ($banks = $option_fields['banks']) {
            foreach ($banks as $bank) {
                $banks_arr[$bank['bank_short_name']] = array_map('floatval', explode('/', $bank['base_li_rate']));
            }
        }
        if (is_countable($option_fields['calculators']['new_mortgage']['fixation'])) {
            $jsonData['fixation'][0]["id"]   = "option_1";
            $jsonData['fixation'][0]["info"] = [];
            $jsonData['fixation'][1]["id"]   = "option_2";
            $jsonData['fixation'][1]["info"] = [];
            $jsonData['fixation'][2]["id"]   = "option_3";
            $jsonData['fixation'][2]["info"] = [];
            foreach ($option_fields['calculators']['new_mortgage']['fixation'] as $item) {
                        $jsonData['fixation'][0]["info"][] = ["value" => $item['value_under_70'], "bank_value" => '', "label" => $item['name']];
                        $jsonData['fixation'][1]["info"][] = ["value" => $item['value_under_80'], "bank_value" => '', "label" => $item['name']];
                        $jsonData['fixation'][2]["info"][] = ["value" => $item['value_over_80'], "bank_value" => '', "label" => $item['name']];
            }
        }
        if (is_countable($option_fields['calculators']['new_mortgage']['fixation_with_insurance_payment'])) {
            $jsonData['fixation_with_insurance_payment'][0]["id"]   = "option_1";
            $jsonData['fixation_with_insurance_payment'][0]["info"] = [];
            $jsonData['fixation_with_insurance_payment'][1]["id"]   = "option_2";
            $jsonData['fixation_with_insurance_payment'][1]["info"] = [];
            $jsonData['fixation_with_insurance_payment'][2]["id"]   = "option_3";
            $jsonData['fixation_with_insurance_payment'][2]["info"] = [];
            foreach ($option_fields['calculators']['new_mortgage']['fixation_with_insurance_payment'] as $item) {
                $jsonData['fixation_with_insurance_payment'][0]["info"][] = ["value" => $item['value_under_70'], "bank_value" => $item['bank_value_under_70'], "li_rate" => $banks_arr[$item['bank_value_under_70']], "label" => $item['name']];
                $jsonData['fixation_with_insurance_payment'][1]["info"][] = ["value" => $item['value_under_80'], "bank_value" => $item['bank_value_under_80'], "li_rate" => $banks_arr[$item['bank_value_under_80']], "label" => $item['name']];
                $jsonData['fixation_with_insurance_payment'][2]["info"][] = ["value" => $item['value_over_80'], "bank_value" => $item['bank_value_over_80'], "li_rate" => $banks_arr[$item['bank_value_over_80']], "label" => $item['name']];
            }
        }
        ?>
        <script>
            var jsonData                     = <?=json_encode($jsonData['fixation'])?>;
            var jsonDataWithInsurancePayment = <?=json_encode($jsonData['fixation_with_insurance_payment'])?>;
        </script>
    </div>
</div>
