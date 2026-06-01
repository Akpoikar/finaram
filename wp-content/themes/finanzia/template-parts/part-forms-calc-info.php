<?php
$option_fields       = $args['option_fields'];
$calc                = $args['calc'];
if ($calc) {
    $calc->currency      = 'CZK';                                                      // потому что все переводим в кроны
    $calc->currency_rate = (get_post_meta($calc->ID, '_mini_calc_currency_rate', true)); // потому что все переводим в кроны
}
?>
<div class="calc__info">
    <div class="calc__info-list">
        <div class="calc__info-box">
            <div class="calc__info-name">
                <?php _e("Type", 'finanzia'); ?>
            </div>
            <div class="calc__info-result">
                <?php echo Calculator::translate_credit_type(get_post_meta($calc->ID, '_mini_calc_credit_type', true)); ?>
            </div>
        </div>
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
                <span class="loan"><?= number_format(get_post_meta($calc->ID, '_mini_calc_loan_amount', true) / $calc->currency_rate); ?></span>
                <span class="currency"><?php _e('CZK' , 'finanzia'); ?></span>
            </div>
        </div>
        <div class="calc__info-box">
            <div class="calc__info-name">
                <?php _e("Total payment amount", 'finanzia'); ?>
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
                <span class="total"><?= number_format(get_post_meta($calc->ID, '_mini_calc_total_payment_amount', true) / $calc->currency_rate); ?></span>
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
                <span class="rate"><?= get_post_meta($calc->ID, '_mini_calc_interest_rate', true); ?></span>
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
                <span class="rpsn"><?= get_post_meta($calc->ID, '_mini_calc_rpsn', true); ?></span>
                %
            </div>
        </div>
    </div>
    <div class="calc__total">
        <div class="calc__total-name">
            <?php _e("Monthly payment", 'finanzia'); ?>
        </div>
        <div class="calc__total-num">
            <span class="maintotal"><?= number_format(get_post_meta($calc->ID, '_mini_calc_monthly_payment', true) / $calc->currency_rate); ?></span>
            <span class="currency"><?php _e('CZK' , 'finanzia'); ?></span>
        </div>
    </div>
    <?php if (!!$insurance_payment = get_post_meta($calc->ID, '_mini_calc_insurance_payment', true)): ?>
        <div class="calc__info-box">
            <div class="calc__info-name">
                <svg class="ins-arrow" width="16" height="17" viewBox="0 0 16 17" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 7.16675L13.3333 10.5001L10 13.8334" stroke="#9F9F9F"
                          stroke-linecap="square" stroke-linejoin="round"/>
                    <path d="M2.66699 3.16675V7.83341C2.66699 8.54066 2.94794 9.21894 3.44804 9.71903C3.94814 10.2191 4.62641 10.5001 5.33366 10.5001H12.667"
                          stroke="#9F9F9F" stroke-linecap="square" stroke-linejoin="round"/>
                </svg>
                <?php _e("Insurance (included)", 'finanzia'); ?>
            </div>
            <div class="calc__info-result">
                <?= number_format($insurance_payment / $calc->currency_rate); ?> <?php _e('CZK' , 'finanzia'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>