<?php

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.1.45');
}

require_once __DIR__ . '/libs/theme.php';
require_once __DIR__ . '/libs/debug.php';
require_once __DIR__ . '/libs/svg_fix.php';

require_once __DIR__ . '/libs/front.php';

// add phpThumb
require_once __DIR__ . '/libs/phpThumb/phpthumb.class.php';

// Settings for theme
require_once __DIR__ . '/libs/settings.php';

// Standalone Get Consultation page
require_once __DIR__ . '/libs/get-consultation.php';

// Mortgage approval estimator widget
require_once __DIR__ . '/libs/mortgage-approval-estimator.php';

// Post types
require_once __DIR__ . '/libs/calcpage.php';
require_once __DIR__ . '/libs/articles.php';
require_once __DIR__ . '/libs/mortgages.php';
require_once __DIR__ . '/libs/loans.php';
require_once __DIR__ . '/libs/cards.php';
require_once __DIR__ . '/libs/reviews.php';
require_once __DIR__ . '/libs/login.php';

add_action('admin_init', 'remove_read_subscriber');
function remove_read_subscriber()
{
    $role = get_role('subscriber');
    $role->remove_cap('read');
}

/*test emails*/
//add_action('init', function () {
//
//
//    if ($_GET['test_send'] == 1234) {
////        $to                 = 'kavaleryan.sergei@gmail.com';
//        $to = 'dimaartlemon@gmail.com';
////        $to                 = 'a.gorban.work@gmail.com';
//        $qwe                = [
//            'watchdog',
////            'login_code',
////            'step_one_approved',
////            'step_one_denied',
////            'step_two_approved',
////            'step_two_completed',
////            'step_three_completed',
//        ];
//        $data_array['name'] = 'Some Name';
//        $data_array['code'] = '1234';
//        foreach ($qwe as $item) {
//            if ((int)get_option('test_mail_' . $item) < (int)$_GET['tsn']) {
//                sendMessage($to, $data_array, $item, 'cs');
//                sendMessage($to, $data_array, $item, 'en');
//                update_option('test_mail_' . $item, (int)$_GET['tsn']);
//            }
//        }
//    }
//});

if (defined('ICL_SITEPRESS_VERSION')) {
    // fix root lang WPML
    add_action('init', function () {
        global $sitepress;
        $sitepress->switch_lang(ICL_LANGUAGE_CODE); // to get_posts need add 'suppress_filters' => false,
    });
}


add_filter('site_transient_update_plugins', 'disable_plugin_updates');
function disable_plugin_updates($value)
{
    unset($value->response['calculator-form-data/calculator-form-data.php']);
    unset($value->response['watchdog/watchdog.php']);
    return $value;
}

if (!current_user_can('manage_options')) {
    add_filter('show_admin_bar', '__return_false');
}

add_action('wp_logout', 'redirect_after_logout');
function redirect_after_logout()
{
    if (!current_user_can('manage_options')) {
        wp_redirect(home_url());
        exit();
    }
}

add_shortcode('mortgage_calc_new', 'add_mortgage_calc_new_shortcode');

function add_mortgage_calc_new_shortcode()
{
    ob_start();
    get_template_part('template-parts/part', 'mortgage-calc-new');
    return ob_get_clean();
}

add_shortcode('mortgage_calc', 'add_mortgage_calc_shortcode');

function add_mortgage_calc_shortcode()
{
    ob_start();
    get_template_part('template-parts/part', 'mortgage-calc');
    return ob_get_clean();
}

add_shortcode('max_mortgage_calc', 'add_max_mortgage_calc_shortcode');

function add_max_mortgage_calc_shortcode()
{
    ob_start();
    get_template_part('template-parts/part', 'mortgage-max-calc');
    return ob_get_clean();
}

add_shortcode('simple_mortgage_calc', 'add_simple_mortgage_calc_shortcode');

function add_simple_mortgage_calc_shortcode()
{
    ob_start();
    get_template_part('template-parts/part', 'simple-mortgage-calc');
    return ob_get_clean();
}

add_shortcode('mortgage_rent_calc', 'add_mortgage_rent_calc_shortcode');

function add_mortgage_rent_calc_shortcode()
{
    ob_start();
//    get_template_part('template-parts/part', 'mortgage-rent-calc');
    get_template_part('template-parts/part', 'rent');
    return ob_get_clean();
}

add_shortcode('mortgage_investment_calc', 'add_mortgage_investment_calc_shortcode');

function add_mortgage_investment_calc_shortcode()
{
    ob_start();
//    get_template_part('template-parts/part', 'mortgage-investment-calc');
    get_template_part('template-parts/part', 'investment');
    return ob_get_clean();
}

function sendMessage($to, $data_array, $name_template, $lang = ICL_LANGUAGE_CODE)
{

    add_filter('wp_mail_content_type', function () {
        return 'text/html';
    });

    /* from address */
    add_filter('wp_mail_from', function ($email) {
        return get_field('contact_email', 'option');
    });

    /* from name*/
    add_filter('wp_mail_from_name', function ($name) {
        return get_bloginfo('name');
    });

    $in = array();

    add_filter('acf/settings/current_language', fn() => $lang, 100);
    $option = get_field('mail_templates', 'option');
    remove_filter('acf/settings/current_language', fn() => $lang, 100);

    $subject = $option[$name_template]['subject'];
    $message = $option[$name_template]['body_html'];
    $meta    = $option['meta']['body_html'];

    foreach (array_keys($data_array) as $key) {
        $in[] = '[' . $key . ']';
    }

    $subject = str_replace($in, array_values($data_array), $subject);

    $message = str_replace($in, array_values($data_array), $message);
    //    $message = nl2br($message);
    $message = str_replace('[body]', $message, $meta);
    $message = str_replace('[theme_dir]', theme()->getThemeUrl(), $message);
    $message = str_replace('[home_url]', home_url(), $message);

    $mail = wp_mail($to, $subject, $message);
    return $mail;
}

function get_actual_user_calc($user_id = null)
{
    $user_id = $user_id ?? get_current_user_id();

    $args = [
        'post_type'      => Calculator::post_type,
        'posts_per_page' => -1,
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => '_calc_user',
                'value'   => $user_id,
                'compare' => '=',
                'type'    => 'NUMERIC',
            ],
            [
                'key'     => '_calc_status',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ],
        ],
    ];

    $user_dashboards_query = new WP_Query($args);

    if (is_countable($user_dashboards_query->posts) && count($user_dashboards_query->posts) > 0) {
        foreach ($user_dashboards_query->posts as &$post) {
            $post->_calc_status = get_post_meta($post->ID, '_calc_status', true);
        }
        $st = 12;
        do {
            $items = array_filter($user_dashboards_query->posts, function ($item) use ($st) {
                return $item->_calc_status == $st;
            });
            $st--;
        } while (empty($items) && $st > 0);

        return array_shift($items);
    }
    return false;
}

/*
 * mortgages
 * 17048    - step aio page
 * 1374     - step 1 page
 * 1450     - wait page
 * 1479     - step 2 page
 * 1450     - wait page
 * 1487     - step 3 page
 * 1450     - finish
 *
 * loans
 * 20023    - step aio page  // TODO change ID
 * 20017    - documents page // TODO change ID
 * 1912     - step 1 page
 * 1450     - wait page
 * 1917     - step 2 page
 * 1450     - finish
 *
 * */
function get_form_page_url($calc_status, $slug)
{
    $arr = [
        'mortgages' => [
            1 => get_permalink(17048), // step aio page
            2 => get_permalink(1450),  // finish
            3 => get_permalink(1450),  // finish
            4 => get_permalink(1450),  // finish
            5 => get_permalink(1450),  // finish
            6 => get_permalink(1450),  // finish
            8 => get_permalink(1450),  // finish
            9 => get_permalink(1450),  // finish
        ],
        'loans'     => [
            1  => get_permalink(20023),  // step aio page
            2  => get_permalink(1450),   // finish
            3  => get_permalink(1450),   // finish
            4  => get_permalink(1450),   // finish
            5  => get_permalink(1450),   // finish
            6  => get_permalink(1450),   // finish
            8  => get_permalink(1450),   // finish
            9  => get_permalink(1450),   // finish
            10 => get_permalink(20017),  // documents page
            11 => get_permalink(1450),   // finish
        ],
        'cards'     => [
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
        ],
    ];
    return $arr[$slug][$calc_status] ?? home_url();
}

function validate_and_sanitize_text($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = validate_and_sanitize_text($value);
        }
    } else {
        $data = sanitize_text_field($data);
    }
    return $data;
}

/**
 * Normalize CF7 select option values for consistent render + validation.
 */
function finaram_normalize_cf7_option($value)
{
    return html_entity_decode(
        trim(wp_strip_all_tags((string) $value)),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );
}

/**
 * WPML language code for a CF7 form (REST submit often runs in default language).
 *
 * @param WPCF7_ContactForm|null $contact_form
 * @return string
 */
function finaram_cf7_get_form_language_code($contact_form = null)
{
    if ($contact_form instanceof WPCF7_ContactForm) {
        $form_id = (int) $contact_form->id();

        if ($form_id && function_exists('apply_filters')) {
            $lang = apply_filters('wpml_element_language_code', null, array(
                'element_id'   => $form_id,
                'element_type' => 'post_wpcf7_contact_form',
            ));

            if (is_string($lang) && $lang !== '') {
                return $lang;
            }
        }
    }

    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE) {
        return ICL_LANGUAGE_CODE;
    }

    if (function_exists('apply_filters')) {
        $default = apply_filters('wpml_default_language', null);
        if (is_string($default) && $default !== '') {
            return $default;
        }
    }

    return 'default';
}

/**
 * @param WPCF7_FormTag $tag
 * @return bool
 */
function finaram_cf7_tag_uses_list_of_questions($tag)
{
    return in_array('list_of_questions', (array) $tag->get_option('data'), true);
}

/**
 * @return string[]
 */
function finaram_cf7_get_registered_language_codes()
{
    $langs = array();

    if (function_exists('apply_filters')) {
        $active = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
        if (is_array($active)) {
            $langs = array_merge($langs, array_keys($active));
        }

        $default = apply_filters('wpml_default_language', null);
        if (is_string($default) && $default !== '') {
            $langs[] = $default;
        }
    }

    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE) {
        $langs[] = ICL_LANGUAGE_CODE;
    }

    $langs[] = 'default';

    return array_values(array_unique(array_filter($langs)));
}

/**
 * "What would you like to ask?" options from ACF for a specific WPML language.
 *
 * @param string|null $lang WPML language code; null = current CF7 form language.
 * @return string[]
 */
function finaram_get_cf7_list_of_questions($lang = null)
{
    static $cache = array();

    if ($lang === null) {
        $contact_form = function_exists('wpcf7_get_current_contact_form')
            ? wpcf7_get_current_contact_form()
            : null;
        $lang = finaram_cf7_get_form_language_code($contact_form);
    }

    if (isset($cache[$lang])) {
        return $cache[$lang];
    }

    $acf_lang_filter = null;
    if ($lang && $lang !== 'default') {
        $acf_lang_filter = static function () use ($lang) {
            return $lang;
        };
        add_filter('acf/settings/current_language', $acf_lang_filter, 100);
    }

    $option_fields = get_fields('option');

    if ($acf_lang_filter) {
        remove_filter('acf/settings/current_language', $acf_lang_filter, 100);
    }

    if (
        ! is_array($option_fields)
        || empty($option_fields['list_of_questions'])
        || ! is_array($option_fields['list_of_questions'])
    ) {
        $cache[$lang] = array();
        return $cache[$lang];
    }

    $questions = array();
    foreach ($option_fields['list_of_questions'] as $row) {
        if (empty($row['question'])) {
            continue;
        }
        $question = finaram_normalize_cf7_option($row['question']);
        if ($question !== '') {
            $questions[] = $question;
        }
    }

    $cache[$lang] = array_values(array_unique($questions));

    return $cache[$lang];
}

/**
 * All translated question options (fallback when REST validation language is ambiguous).
 *
 * @return string[]
 */
function finaram_cf7_get_all_languages_list_of_questions()
{
    $questions = array();

    foreach (finaram_cf7_get_registered_language_codes() as $lang) {
        $questions = array_merge($questions, finaram_get_cf7_list_of_questions($lang));
    }

    return array_values(array_unique(array_filter($questions)));
}

/**
 * Allowed values for a dynamic CF7 select using data:list_of_questions.
 *
 * @param WPCF7_FormTag          $tag
 * @param WPCF7_ContactForm|null $contact_form
 * @return string[]
 */
function finaram_cf7_get_select_accept_values($tag, $contact_form = null)
{
    $lang = finaram_cf7_get_form_language_code($contact_form);

    $values = array_merge(
        (array) $tag->values,
        finaram_get_cf7_list_of_questions($lang)
    );

    if ($tag->has_option('first_as_label')) {
        $values = array_slice($values, 1);
    }

    $values = array_map('finaram_normalize_cf7_option', $values);

    return array_values(array_filter(array_unique($values), static function ($value) {
        return $value !== '';
    }));
}

add_filter('wpcf7_form_tag_data_option', function ($n, $options, $args) {
    if (in_array('list_of_questions', (array) $options, true)) {
        $contact_form = function_exists('wpcf7_get_current_contact_form')
            ? wpcf7_get_current_contact_form()
            : null;

        return finaram_get_cf7_list_of_questions(
            finaram_cf7_get_form_language_code($contact_form)
        );
    }
    return $n;
}, 10, 3);

/**
 * CF7 enum validation can miss ACF-driven options (WPML / dynamic data).
 * Replace enum rules for list_of_questions selects with the live option list.
 */
add_action('wpcf7_swv_create_schema', function ($schema, $contact_form) {
    $dynamic_tags = array();

    foreach ($contact_form->scan_form_tags(array('basetype' => array('select'))) as $tag) {
        if (finaram_cf7_tag_uses_list_of_questions($tag)) {
            $dynamic_tags[$tag->name] = $tag;
        }
    }

    if (empty($dynamic_tags)) {
        return;
    }

    $reflection = new ReflectionClass($schema);
    $rules_prop = $reflection->getProperty('rules');
    $rules_prop->setAccessible(true);
    $rules      = $rules_prop->getValue($schema);

    $filtered_rules = array();
    foreach ($rules as $rule) {
        if ($rule instanceof \Contactable\SWV\EnumRule) {
            $field = $rule->get_property('field');
            if (isset($dynamic_tags[$field])) {
                continue;
            }
        }
        $filtered_rules[] = $rule;
    }

    foreach ($dynamic_tags as $name => $tag) {
        $accept = finaram_cf7_get_select_accept_values($tag, $contact_form);

        if (empty($accept)) {
            $accept = finaram_cf7_get_all_languages_list_of_questions();
        }

        if (empty($accept)) {
            continue;
        }

        $filtered_rules[] = wpcf7_swv_create_rule('enum', array(
            'field'  => $name,
            'accept' => $accept,
            'error'  => $contact_form->filter_message(
                __('Undefined value was submitted through this field.', 'contact-form-7')
            ),
        ));
    }

    $rules_prop->setValue($schema, $filtered_rules);
}, 25, 2);

/**
 * Fallback: if enum validation still rejects a valid ACF option, clear the error.
 *
 * @param WPCF7_Validation $result
 * @param WPCF7_FormTag    $tag
 * @return WPCF7_Validation
 */
function finaram_cf7_clear_select_validation_if_allowed($result, $tag)
{
    if (empty($tag->name) || $result->is_valid($tag->name)) {
        return $result;
    }

    if (! finaram_cf7_tag_uses_list_of_questions($tag)) {
        return $result;
    }

    if (! isset($_POST[$tag->name])) {
        return $result;
    }

    $contact_form = function_exists('wpcf7_get_current_contact_form')
        ? wpcf7_get_current_contact_form()
        : null;

    $submitted = finaram_normalize_cf7_option(wp_unslash($_POST[$tag->name]));
    $accept    = finaram_cf7_get_select_accept_values($tag, $contact_form);

    if (empty($accept)) {
        $accept = finaram_cf7_get_all_languages_list_of_questions();
    }

    if ($submitted === '' || ! in_array($submitted, $accept, true)) {
        return $result;
    }

    $reflection = new ReflectionClass($result);
    $property   = $reflection->getProperty('invalid_fields');
    $property->setAccessible(true);
    $invalid    = $property->getValue($result);
    unset($invalid[$tag->name]);
    $property->setValue($result, $invalid);

    return $result;
}
add_filter('wpcf7_validate_select', 'finaram_cf7_clear_select_validation_if_allowed', 30, 2);
add_filter('wpcf7_validate_select*', 'finaram_cf7_clear_select_validation_if_allowed', 30, 2);

add_filter('wpcf7_form_hidden_fields', 'cf7_add_extras');

function cf7_add_extras($fields)
{
    $post = get_post();
    return array_merge($fields, array(
            'post-id'    => $post->ID ?? null,
            'post-title' => $post->post_title ?? null
        )
    );
}

add_action('template_redirect', 'calculator_form_template_redirect');
function calculator_form_template_redirect()
{
    if (is_page_template([
        'template-loans-form-step-one.php',
        'template-loans-form-step-two.php',
        'template-mortgages-form-step-one.php',
        'template-mortgages-form-step-two.php',
        'template-mortgages-form-step-three.php',

        'template-login.php',

        'template-mortgages-aio-form.php',

        'template-loans-aio-form.php',
        'template-loans-form-step-doc.php',

        'template-thank-you-page.php',
    ])) {
        if (is_user_logged_in()) {
            $calc     = get_actual_user_calc();
            $category = get_the_terms($calc->ID, Calculator::taxonomy);
            if ($category) {
                $url = get_form_page_url($calc->_calc_status, $category[0]->slug);
            } else {
                $url = home_url();
            }
        } else {
            $url = esc_url(home_url('/login/'));
        }
        if (parse_url($url, PHP_URL_PATH) != $_SERVER['REQUEST_URI']) {
            wp_redirect($url);
            exit();
        }
    }
}
