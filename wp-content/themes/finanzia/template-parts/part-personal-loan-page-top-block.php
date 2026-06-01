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
        <div class="calc__section" id="loancalc1">
            <div class="calc__form">
                <div class="calc__row">
                    <div class="contacts__label">
                                <?php _ex("Loan amount — CZK", 'loans calculator > personal loan', 'finanzia'); ?>
                    </div>
                    <input type="text" name="sum" class="property-sum"
                           min="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['min'] ?>"
                           max="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['max'] ?>"
                           step="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['step'] ?>"
                           value="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['default_value'] ?>"
                           data-ir-1="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['interest_rate_1'] ?>"
                           data-ir-2="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['interest_rate_2'] ?>"
                           data-ir-3="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['interest_rate_3'] ?>"
                           data-ir-4="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['interest_rate_4'] ?>"
                           data-ir-5="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['interest_rate_5'] ?>"
                           data-ir-6="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['interest_rate_6'] ?>">
                    <div class="rangeholder">
                        <input class="form-range" type="range"
                               min="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['min'] ?>"
                               max="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['max'] ?>"
                               step="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['step'] ?>"
                               value="<?= (float)$option_fields['loans_calculator']['personalloan']['loan_amount']['default_value'] ?>">
                    </div>
                    <div class="calc__label-bottom">
                        <div class="calc__label-bottom-text">
                            <?= number_format($option_fields['loans_calculator']['personalloan']['loan_amount']['min']); ?>
                            <?php _e('CZK' , 'finanzia'); ?>
                        </div>
                        <div class="calc__label-bottom-text">
                            <?= number_format($option_fields['loans_calculator']['personalloan']['loan_amount']['max']); ?>
                            <?php _e('CZK' , 'finanzia'); ?>
                        </div>
                    </div>
                </div>
                <div class="calc__row">
                    <div class="contacts__label">
                        <?php _e("Duration — Years", 'finanzia'); ?>
                    </div>
                    <input type="number" name="sum" class="d-years js_duration"
                           min="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['min'] ?>"
                           max="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['max'] ?>"
                           step="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['step'] ?>"
                           value="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['default_value'] ?>">
                    <div class="rangeholder">
                        <input class="y-range" type="range"
                               min="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['min'] ?>"
                               max="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['max'] ?>"
                               step="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['step'] ?>"
                               value="<?= (float)$option_fields['loans_calculator']['personalloan']['duration']['default_value'] ?>">
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
                                <?php if (trim($option_fields['loans_calculator']['personalloan']['loan_amount_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['loans_calculator']['personalloan']['loan_amount_info_drop'] ?>
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
                            <?php _ex("Total payment amount", 'loans calculator result', 'finanzia'); ?>
                            <div class="calc__info-help">
                                <?php if (trim($option_fields['loans_calculator']['personalloan']['total_payment_amount_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['loans_calculator']['personalloan']['total_payment_amount_info_drop'] ?>
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
                                <?php if (trim($option_fields['loans_calculator']['personalloan']['interest_rate_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['loans_calculator']['personalloan']['interest_rate_info_drop'] ?>
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
                                <?php if (trim($option_fields['loans_calculator']['personalloan']['rpsn_info_drop']) != '') : ?>
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00065 4.51303V4.50001M8.00065 7.16668V12.5M14.6673 8.50001C14.6673 12.1819 11.6825 15.1667 8.00065 15.1667C4.31875 15.1667 1.33398 12.1819 1.33398 8.50001C1.33398 4.81811 4.31875 1.83334 8.00065 1.83334C11.6825 1.83334 14.6673 4.81811 14.6673 8.50001Z"
                                              stroke="#9F9F9F" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="calc__info-drop">
                                        <?= $option_fields['loans_calculator']['personalloan']['rpsn_info_drop']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="calc__info-result">
                            <span class="rpsn js_rpsn"></span>
                            %
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
                            <?= $option_fields['loans_calculator']['disclaimer']; ?>
                        </div>
                        <div class="calc__control">
                            <button class="calc__apply js_calc_apply js_go_aio" type="button" data-tab_name="personal_loan" data-category="Loans"
                                    name="button"><?php _e("Apply Online", 'finanzia'); ?></button>
                            <button class="calc__callback" type="button"
                                    name="button"><?php _e("Call Back", 'finanzia'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
