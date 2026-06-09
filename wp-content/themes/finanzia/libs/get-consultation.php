<?php
/**
 * Standalone Get Consultation page helpers.
 *
 * @package Finanzia
 */

defined('ABSPATH') || exit;

/** CF7 form used in the header consultation modal (popup-consult). */
define('FINARAM_CONSULTATION_CF7_ID', 1782);

/**
 * WPML-aware CF7 form ID for the consultation form.
 *
 * @return int
 */
function finaram_get_consultation_cf7_id()
{
    $form_id = FINARAM_CONSULTATION_CF7_ID;

    if (function_exists('apply_filters')) {
        $translated = apply_filters('wpml_object_id', $form_id, 'wpcf7_contact_form', true);
        if ($translated) {
            $form_id = (int) $translated;
        }
    }

    return $form_id;
}

/**
 * Page ID for template-get-consultation.php in the current WPML language.
 *
 * @return int
 */
function finaram_get_consultation_page_id()
{
    $pages = get_posts(array(
        'post_type'        => 'page',
        'posts_per_page'   => 1,
        'meta_key'         => '_wp_page_template',
        'meta_value'       => 'template-get-consultation.php',
        'suppress_filters' => false,
        'post_status'      => 'publish',
    ));

    return ! empty($pages[0]) ? (int) $pages[0]->ID : 0;
}

/**
 * Language-aware URL for the standalone consultation page.
 *
 * @return string
 */
function finaram_get_consultation_url()
{
    $page_id = finaram_get_consultation_page_id();

    if (! $page_id) {
        return '';
    }

    return (string) get_permalink($page_id);
}

/**
 * @return bool
 */
function finaram_should_load_consultation_assets()
{
    if (is_page_template('template-get-consultation.php')) {
        return true;
    }

    return (bool) apply_filters('finaram_load_consultation_assets', false);
}

/**
 * Enqueue consultation page styles.
 */
function finaram_consultation_enqueue_assets()
{
    if (! finaram_should_load_consultation_assets()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $version   = defined('_S_VERSION') ? _S_VERSION : '1.0.0';

    wp_enqueue_style(
        'finaram-get-consultation',
        $theme_uri . '/assets/css/get-consultation.css',
        array('main-css'),
        $version
    );
}
add_action('wp_enqueue_scripts', 'finaram_consultation_enqueue_assets', 25);

/**
 * Default copy for the consultation page (overridable via $args / ACF).
 *
 * @return array<string, string>
 */
function finaram_get_consultation_defaults()
{
    return array(
        'title'       => function_exists('finaram_mae_t')
            ? finaram_mae_t('Get Mortgage Consultation', 'Získat hypoteční konzultaci')
            : __('Get Mortgage Consultation', 'finanzia'),
        'description' => function_exists('finaram_mae_t')
            ? finaram_mae_t(
                'Leave your details and we will contact you within two business days.',
                'Zanechte nám kontakt a ozveme se vám do dvou pracovních dnů.'
            )
            : __('Leave your details and we will contact you within two business days.', 'finanzia'),
    );
}

/**
 * Render the consultation form partial.
 *
 * @param array<string, string> $args Optional title, description.
 */
function finaram_render_get_consultation(array $args = array())
{
    $args = wp_parse_args($args, finaram_get_consultation_defaults());
    get_template_part('template-parts/part', 'get-consultation', $args);
}

