<?php

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.1.33');
}

require_once __DIR__ . '/libs/theme.php';
require_once __DIR__ . '/libs/debug.php';
require_once __DIR__ . '/libs/svg_fix.php';

require_once __DIR__ . '/libs/front.php';

// add phpThumb
require_once __DIR__ . '/libs/phpThumb/phpthumb.class.php';

// Settings for theme
require_once __DIR__ . '/libs/settings.php';

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

add_filter('wpcf7_form_tag_data_option', function ($n, $options, $args) {
    if (in_array('list_of_questions', $options)) {
        $option_fields = get_fields('option');
        return array_column($option_fields['list_of_questions'], 'question');
    }
    return null;
}, 10, 3);

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
