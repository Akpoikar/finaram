<?php

/*
 * Plugin Name: Calculator
 * Description: Plugin for calculators functionality.
 * Author: MAS group
 * Author URI: https://masgroup.agency/
 * Text Domain: calculator
 * Domain Path: /languages/
 */

define('CALCULATOR_VERSION', '0.1');

define('CALCULATOR_PLUGIN', __FILE__);

define('CALCULATOR_PLUGIN_BASENAME',
    plugin_basename(CALCULATOR_PLUGIN)
);

define('CALCULATOR_PLUGIN_NAME',
    trim(dirname(CALCULATOR_PLUGIN_BASENAME), '/')
);

define('CALCULATOR_PLUGIN_DIR',
    untrailingslashit(dirname(CALCULATOR_PLUGIN))
);

if (!defined('CALCULATOR_MOVE_TRASH_DAYS')) {
    define('CALCULATOR_MOVE_TRASH_DAYS', 30);
}

define('CALCULATOR_PLUGIN_URL',
    untrailingslashit(plugins_url('', CALCULATOR_PLUGIN))
);


// Add Calculator class
include_once CALCULATOR_PLUGIN_DIR . '/capabilities.php';
include_once CALCULATOR_PLUGIN_DIR . '/class.calculator.php';

$calculator = new Calculator();

function calculator_translate($key)
{
    $arr = [
        '_mini_calc_credit_type'          => __('Credit type', 'calculator'),
        '_mini_calc_currency'             => __('Currency', 'calculator'),
        '_mini_calc_currency_rate'        => __('Currency rate', 'calculator'),
        '_mini_calc_loan_amount'          => __('Loan amount', 'calculator'),
        '_mini_calc_total_payment_amount' => __('Total payment', 'calculator'),
        '_mini_calc_interest_rate'        => __('Interest rate', 'calculator'),
        '_mini_calc_rpsn'                 => __('RPSN', 'calculator'),
        '_mini_calc_insurance_payment'    => __('Insurance payment', 'calculator'),
        '_mini_calc_monthly_payment'      => __('Monthly payment', 'calculator'),

        'full_time_employee' => __('Full-time employee', 'calculator'),
        'self_employed'      => __('Self-employed', 'calculator'),
        'rental_income'      => __('Rental income', 'calculator'),
        'income_from_abroad' => __('Income from abroad', 'calculator'),

        'original_mortgage_contract'                          => __('Original Mortgage Contract', 'calculator'),
        'identification_documents'                            => __('Identification documents', 'calculator'),
        'mortgage_bank_statement'                             => __('Mortgage Bank Statement', 'calculator'),
        'bank_statement'                                      => __('Bank Statements', 'calculator'),
        'income_certificate'                                  => __('Income Certificate', 'calculator'),
        'optional_documents'                                  => __('Optional Documents', 'calculator'),
        'recent_contract_changes'                             => __('Any recent amendments related to your work contract (if available)', 'calculator'),
        'personal_tax_returns_year_before_last'               => __('Personal Tax Returns', 'calculator') . ' ' . date("Y", strtotime("-2 year")),
        'personal_tax_returns_last_year'                      => __('Personal Tax Returns', 'calculator') . ' ' . date("Y", strtotime("-1 year")),
        'confirmation_tax_return_submission_year_before_last' => __('Confirmation of Tax Return Submission', 'calculator') . ' ' . date("Y", strtotime("-2 year")),
        'confirmation_tax_return_submission_last_year'        => __('Confirmation of Tax Return Submission', 'calculator') . ' ' . date("Y", strtotime("-1 year")),
        'tax_payment_confirmation_for_year_before_last'       => __('Confirmation of Tax Payment', 'calculator') . ' ' . date("Y", strtotime("-2 year")),
        'tax_payment_confirmation_for_last_year'              => __('Confirmation of Tax Payment', 'calculator') . ' ' . date("Y", strtotime("-1 year")),
        'rental_contract'                                     => __('Rental Contract', 'calculator'),
        'work_contract'                                       => __('Work Contract', 'calculator'),
        'any_recent_amendments'                               => __('Any recent amendments if applicable', 'calculator'),

        '_calc_utm_source'   => __('UTM Source', 'calculator'),
        '_calc_utm_medium'   => __('UTM Medium', 'calculator'),
        '_calc_utm_campaign' => __('UTM Campaign', 'calculator'),
        '_calc_utm_term'     => __('UTM Term', 'calculator'),
        '_calc_utm_content'  => __('UTM Content', 'calculator'),

    ];
    return $arr[$key] ?? $key;
}

function calculator_htmlize($val)
{
    $result = '';

    if (is_array($val) && count($val) > 1) {
        foreach ($val as $v) {
            $result .= sprintf('<li>%s</li>', calculator_htmlize($v));
        }

        $result = sprintf('<ul>%s</ul>', $result);
    } elseif (is_array($val) && count($val) == 1) {
        $result = esc_html(array_shift($val));
    } else {
        $result = esc_html((string)calculator_translate($val));
    }

    return apply_filters('calculator_htmlize', $result, $val);
}

function calculator_current_action()
{
    if (isset($_REQUEST['delete_all'])
        or isset($_REQUEST['delete_all2'])) {
        return 'delete_all';
    }

    if (isset($_REQUEST['action'])
        and -1 != $_REQUEST['action']) {
        return $_REQUEST['action'];
    }

    if (isset($_REQUEST['action2'])
        and -1 != $_REQUEST['action2']) {
        return $_REQUEST['action2'];
    }

    return false;
}