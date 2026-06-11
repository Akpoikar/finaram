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
 * All CF7 post IDs for the consultation form (Czech original + every WPML translation).
 *
 * @return int[]
 */
function finaram_cf7_get_consultation_form_ids()
{
    static $ids = null;

    if (null !== $ids) {
        return $ids;
    }

    $ids = array((int) FINARAM_CONSULTATION_CF7_ID);

    if (function_exists('apply_filters')) {
        $languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
        if (is_array($languages)) {
            foreach (array_keys($languages) as $lang_code) {
                $translated = apply_filters(
                    'wpml_object_id',
                    FINARAM_CONSULTATION_CF7_ID,
                    'wpcf7_contact_form',
                    false,
                    $lang_code
                );
                if ($translated) {
                    $ids[] = (int) $translated;
                }
            }
        }
    }

    $ids = array_values(array_unique(array_filter($ids)));

    return $ids;
}

/**
 * Whether the given CF7 form is the consultation form (any WPML translation).
 *
 * @param WPCF7_ContactForm|null $contact_form
 * @return bool
 */
function finaram_cf7_is_consultation_form($contact_form)
{
    if (! $contact_form instanceof WPCF7_ContactForm) {
        return false;
    }

    return in_array((int) $contact_form->id(), finaram_cf7_get_consultation_form_ids(), true);
}

/**
 * @return bool
 */
function finaram_is_consultation_page()
{
    return is_page_template('template-get-consultation.php');
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
 * Whether an ACF question option should reveal mortgage sub-types.
 *
 * @param string $question
 * @return bool
 */
function finaram_consultation_question_triggers_mortgage($question)
{
    $value = function_exists('finaram_normalize_cf7_option')
        ? finaram_normalize_cf7_option($question)
        : trim((string) $question);

    if ($value === '') {
        return false;
    }

    $lower = mb_strtolower($value, 'UTF-8');

    if (
        preg_match('/půjčk|půjčka|\bloan\b/u', $lower)
        && ! preg_match('/hypoték|mortgage/u', $lower)
    ) {
        return false;
    }

    return (bool) preg_match('/mortgage|hypoték|hypotéka|hypotéky/u', $lower);
}

/**
 * Whether an ACF question option should reveal loan sub-types.
 *
 * @param string $question
 * @return bool
 */
function finaram_consultation_question_triggers_loan($question)
{
    $value = function_exists('finaram_normalize_cf7_option')
        ? finaram_normalize_cf7_option($question)
        : trim((string) $question);

    if ($value === '') {
        return false;
    }

    $lower = mb_strtolower($value, 'UTF-8');

    if (
        preg_match('/hypoték|mortgage/u', $lower)
        && ! preg_match('/půjčk|\bloan\b|úvěr/u', $lower)
    ) {
        return false;
    }

    return (bool) preg_match('/\bloan\b|půjčk|půjčka|úvěr/u', $lower);
}

/**
 * Exact dropdown values (per WPML language) that toggle mortgage/loan chips.
 *
 * @param string|null $lang
 * @return array{mortgage: string[], loan: string[]}
 */
function finaram_get_consultation_subtype_question_map($lang = null)
{
    if ($lang === null) {
        $lang = defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE
            ? ICL_LANGUAGE_CODE
            : 'default';
    }

    $questions = function_exists('finaram_get_cf7_list_of_questions')
        ? finaram_get_cf7_list_of_questions($lang)
        : array();

    $map = array(
        'mortgage' => array(),
        'loan'     => array(),
    );

    foreach ($questions as $question) {
        if (finaram_consultation_question_triggers_mortgage($question)) {
            $map['mortgage'][] = $question;
        } elseif (finaram_consultation_question_triggers_loan($question)) {
            $map['loan'][] = $question;
        }
    }

    $map['mortgage'] = array_values(array_unique($map['mortgage']));
    $map['loan']     = array_values(array_unique($map['loan']));

    return $map;
}

/**
 * Subtype maps for every registered WPML language (used by front-end JS).
 *
 * @return array<string, array{mortgage: string[], loan: string[]}>
 */
function finaram_get_consultation_subtype_config()
{
    $config = array();

    $langs = function_exists('finaram_cf7_get_registered_language_codes')
        ? finaram_cf7_get_registered_language_codes()
        : array('default');

    foreach ($langs as $lang) {
        $config[$lang] = finaram_get_consultation_subtype_question_map($lang);
    }

    return $config;
}

/**
 * Enqueue consultation form assets (JS site-wide for popup; CSS on standalone page).
 */
function finaram_consultation_enqueue_assets()
{
    $theme_uri = get_template_directory_uri();
    $version   = defined('_S_VERSION') ? _S_VERSION : '1.0.0';

    wp_enqueue_script(
        'finaram-get-consultation',
        $theme_uri . '/assets/js/get-consultation.js',
        array('jquery'),
        $version,
        true
    );

    wp_localize_script(
        'finaram-get-consultation',
        'finaramConsultationSubtype',
        array(
            'maps' => finaram_get_consultation_subtype_config(),
        )
    );

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

