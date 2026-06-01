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
       <div class="calc__section" id="calculator-simple">
            <div class="calc__form">
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Property price — CZK", 'finanzia'); ?>
                    </div>
                    <input type="text" name="sum" class="property-sum"
                           min="<?= (float)$option_fields['calculators']['refinancing']['property_price']['min'] ?>"
                           max="<?= (float)$option_fields['calculators']['refinancing']['property_price']['max'] ?>"
                           step="<?= (float)$option_fields['calculators']['refinancing']['property_price']['step'] ?>"
                           value="<?= (float)$option_fields['calculators']['refinancing']['property_price']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="form-range" type="range"
                               min="<?= (float)$option_fields['calculators']['refinancing']['property_price']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['refinancing']['property_price']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['refinancing']['property_price']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['refinancing']['property_price']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("The amount of mortgage left to be paid — CZK", 'finanzia'); ?>
                    </div>
                    <input type="text" name="sum" class="d-payment"
                           min="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['min'] ?>"
                           max="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['max'] ?>"
                           step="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['step'] ?>"
                           value="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="d-range" type="range"
                               min="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['refinancing']['the_amount_of_mortgage_left_to_be_paid']['default_value'] ?>">
                    </div>
                </div>
                <div class="calc__bottom">
                    <div class="calc__bottom-box">
                        <div class="contacts__label">
                            <?php _e("Duration — Years", 'finanzia'); ?>
                        </div>
                        <input type="number" name="sum" class="d-years"
                               min="<?= (float)$option_fields['calculators']['refinancing']['term']['min'] ?>"
                               max="<?= (float)$option_fields['calculators']['refinancing']['term']['max'] ?>"
                               step="<?= (float)$option_fields['calculators']['refinancing']['term']['step'] ?>"
                               value="<?= (float)$option_fields['calculators']['refinancing']['term']['default_value'] ?>">
                        <div class="rangeholder">
                            <input class="y-range" type="range"
                                   min="<?= (float)$option_fields['calculators']['refinancing']['term']['min'] ?>"
                                   max="<?= (float)$option_fields['calculators']['refinancing']['term']['max'] ?>"
                                   step="<?= (float)$option_fields['calculators']['refinancing']['term']['step'] ?>"
                                   value="<?= (float)$option_fields['calculators']['refinancing']['term']['default_value'] ?>">
                        </div>
                    </div>
                    <div class="calc__bottom-box">
                        <div class="contacts__label">
                            <?php _e("Fixation — Years", 'finanzia'); ?>
                        </div>
                        <select id="selectOptionsimple" class="select fix-year" name="">
                            <?php if (is_countable($option_fields['calculators']['refinancing']['fixation'])): ?>
                                <?php foreach ($option_fields['calculators']['refinancing']['fixation'] as $item) : ?>
                                    <option value="<?= $item['value'] ?>"><?= $item['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="calc__check">
                    <label class="calc__check-box">
                        <input class="check" type="checkbox" name=""
                               value="<?= $option_fields['calculators']['refinancing']['insurance_payment_value'] ?>">
                                <span class="calc__check-text"><?php _ex("Insurance payment", 'Mortgages calculators > refinancing', 'finanzia'); ?>
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
                                <?php if (trim($option_fields['calculators']['refinancing']['loan_amount_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['refinancing']['loan_amount_info_drop'] ?>
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
                                <?php if (trim($option_fields['calculators']['refinancing']['you_will_pay_in_total_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['refinancing']['you_will_pay_in_total_info_drop'] ?>
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
                                <?php if (trim($option_fields['calculators']['refinancing']['annual_interest_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['refinancing']['annual_interest_info_drop'] ?>
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
                                <?php if (trim($option_fields['calculators']['refinancing']['rpsn_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['refinancing']['rpsn_info_drop'] ?>
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
                                <?php if (trim($option_fields['calculators']['refinancing']['insurance_payment_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['calculators']['refinancing']['insurance_payment_info_drop'] ?>
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
                            <button class="calc__apply js_calc_apply js_go_aio" type="button" data-tab_name="refinancing_mortgage" data-category="Mortgages"
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
        if (is_countable($option_fields['calculators']['refinancing']['fixation'])) {
            $jsonData['jsonDataSimple'][0]["id"]   = "option_1";
            $jsonData['jsonDataSimple'][0]["info"] = [];
            foreach ($option_fields['calculators']['refinancing']['fixation'] as $item) {
                $jsonData['jsonDataSimple'][0]["info"][] = ["value" => $item['value'], "bank_value" => $item['bank_name_for_insurance_payment'], "label" => $item['name']];
            }
        }
        if (is_countable($option_fields['calculators']['refinancing']['fixation'])) {
            $jsonData['jsonDataWithInsurancePaymentSimple'][0]["id"]   = "option_1";
            $jsonData['jsonDataWithInsurancePaymentSimple'][0]["info"] = [];
            foreach ($option_fields['calculators']['refinancing']['fixation'] as $item) {
                $jsonData['jsonDataWithInsurancePaymentSimple'][0]["info"][] = ["value" => $item['value_with_insurance_payment'], "bank_value" => $item['bank_name_for_insurance_payment'], "li_rate" => $banks_arr[$item['bank_name_for_insurance_payment']], "label" => $item['name']];
            }
        }
        ?>
        <script>
            var jsonDataSimple                     = <?=json_encode($jsonData['jsonDataSimple'])?>;
            var jsonDataWithInsurancePaymentSimple = <?=json_encode($jsonData['jsonDataWithInsurancePaymentSimple'])?>;
        </script>
    </div>
</div>
