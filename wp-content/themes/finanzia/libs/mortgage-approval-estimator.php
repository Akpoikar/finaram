<?php
/**
 * Mortgage Approval Estimator — asset loading and render helpers.
 *
 * @package Finanzia
 */

defined('ABSPATH') || exit;

/**
 * @return bool
 */
function finaram_mae_is_czech()
{
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE === 'cs';
    }

    $locale = function_exists('determine_locale') ? determine_locale() : get_locale();

    return strpos((string) $locale, 'cs') === 0;
}

/**
 * Bilingual string (Czech / English) for estimator UI.
 *
 * @param string $en
 * @param string $cs
 * @return string
 */
function finaram_mae_t($en, $cs)
{
    return finaram_mae_is_czech() ? $cs : $en;
}

/**
 * @param int $step 1–5
 * @return string
 */
function finaram_mae_step_label($step)
{
    if (finaram_mae_is_czech()) {
        return sprintf('Krok %d z %d', $step, 5);
    }

    return sprintf('Step %d of %d', $step, 5);
}

/**
 * Questionnaire copy for JS (value keys are language-neutral).
 *
 * @return array<int, array<string, mixed>>
 */
function finaram_mae_get_questions()
{
    return array(
        array(
            'id'       => 'ltv',
            'label'    => finaram_mae_step_label(1),
            'question' => finaram_mae_t(
                'How much of the property price will you finance with a mortgage?',
                'Jakou část ceny nemovitosti budete financovat hypotékou?'
            ),
            'options'  => array(
                array('value' => 'ltv_80', 'label' => finaram_mae_t('Up to 80%', 'Do 80 %')),
                array('value' => 'ltv_80_90', 'label' => finaram_mae_t('80–90%', '80–90 %')),
                array('value' => 'ltv_90', 'label' => finaram_mae_t('90%', '90 %')),
            ),
        ),
        array(
            'id'       => 'age',
            'label'    => finaram_mae_step_label(2),
            'question' => finaram_mae_t('What is your age?', 'Kolik je vám let?'),
            'options'  => array(
                array('value' => 'age_under_36', 'label' => finaram_mae_t('Under 36', 'Do 36 let')),
                array('value' => 'age_36_plus', 'label' => finaram_mae_t('36+', '36+')),
            ),
        ),
        array(
            'id'       => 'citizenship',
            'label'    => finaram_mae_step_label(3),
            'question' => finaram_mae_t('What is your citizenship?', 'Jaké je vaše občanství?'),
            'options'  => array(
                array('value' => 'citizenship_cz', 'label' => finaram_mae_t('Czech Republic', 'Česká republika')),
                array('value' => 'citizenship_eu', 'label' => finaram_mae_t('EU country', 'Země EU')),
                array('value' => 'citizenship_non_eu', 'label' => finaram_mae_t('Non-EU country', 'Země mimo EU')),
                array('value' => 'citizenship_high_risk', 'label' => finaram_mae_t('High-risk country', 'Rizikové země')),
            ),
        ),
        array(
            'id'       => 'residency',
            'label'    => finaram_mae_step_label(4),
            'question' => finaram_mae_t(
                'What is your residency status in the Czech Republic?',
                'Jaký je váš pobytový status v České republice?'
            ),
            'options'  => array(
                array('value' => 'residency_cz_citizen', 'label' => finaram_mae_t('Czech citizen', 'Občan ČR')),
                array('value' => 'residency_permanent', 'label' => finaram_mae_t('Permanent residence', 'Trvalý pobyt')),
                array('value' => 'residency_long_term', 'label' => finaram_mae_t('Long-term residence', 'Dlouhodobý pobyt')),
                array('value' => 'residency_temporary', 'label' => finaram_mae_t('Temporary residence', 'Dočasný pobyt')),
                array(
                    'value' => 'residency_none',
                    'label' => finaram_mae_t('No residence in Czech Republic', 'Bez pobytu v České republice'),
                ),
            ),
        ),
        array(
            'id'       => 'income',
            'label'    => finaram_mae_step_label(5),
            'question' => finaram_mae_t('What is your main source of income?', 'Jaký je váš hlavní zdroj příjmů?'),
            'options'  => array(
                array('value' => 'income_cz_employment', 'label' => finaram_mae_t('CZ full-time employment', 'HPP v ČR')),
                array('value' => 'income_osvc', 'label' => finaram_mae_t('OSVČ / Self-employed', 'OSVČ')),
                array('value' => 'income_company_owner', 'label' => finaram_mae_t('Company owner', 'Jednatel / majitel firmy')),
                array('value' => 'income_rental', 'label' => finaram_mae_t('Rental income', 'Příjem z pronájmu')),
                array('value' => 'income_eu_salary', 'label' => finaram_mae_t('EU salary abroad', 'Plat ze zahraničí (EU)')),
                array('value' => 'income_non_eu_salary', 'label' => finaram_mae_t('Non-EU salary', 'Plat ze zahraničí (mimo EU)')),
                array('value' => 'income_business_abroad', 'label' => finaram_mae_t('Business income abroad', 'Podnikání v zahraničí')),
            ),
        ),
    );
}

/**
 * Page ID for the estimator template in the current WPML language.
 *
 * @return int
 */
function finaram_mae_get_embed_page_id()
{
    $pages = get_posts(array(
        'post_type'        => 'page',
        'posts_per_page'   => 1,
        'meta_key'         => '_wp_page_template',
        'meta_value'       => 'template-mortgage-approval-estimator.php',
        'suppress_filters' => false,
        'post_status'      => 'publish',
    ));

    return ! empty($pages[0]) ? (int) $pages[0]->ID : 0;
}

/**
 * Language-aware URL for the standalone estimator page (iframe embed).
 *
 * @return string
 */
function finaram_mae_get_embed_url()
{
    $page_id = finaram_mae_get_embed_page_id();

    if (! $page_id) {
        return '';
    }

    return (string) get_permalink($page_id);
}

/**
 * Whether the estimator assets should load on the current request.
 *
 * @return bool
 */
function finaram_should_load_mortgage_approval_estimator()
{
    if (is_page_template('template-mortgage-approval-estimator.php')) {
        return true;
    }

    if (is_front_page()) {
        return true;
    }

    global $post;
    if ($post instanceof WP_Post && (
        has_shortcode($post->post_content, 'mortgage_approval_estimator')
        || has_shortcode($post->post_content, 'mortgage_approval_estimator_iframe')
    )) {
        return true;
    }

    return (bool) apply_filters('finaram_load_mortgage_approval_estimator', false);
}

/**
 * Enqueue scoped CSS/JS for the estimator widget.
 */
function finaram_mortgage_approval_estimator_enqueue_assets()
{
    if (! finaram_should_load_mortgage_approval_estimator()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $version   = defined('_S_VERSION') ? _S_VERSION : '1.0.0';

    if (is_page_template('template-mortgage-approval-estimator.php')) {
        wp_dequeue_style('main-css');
        wp_dequeue_script('app-js');
        wp_dequeue_script('function');
    }

    wp_enqueue_style(
        'finaram-mortgage-approval-estimator',
        $theme_uri . '/assets/css/mortgage-approval-estimator.css',
        array('main-css'),
        $version
    );

    wp_enqueue_script(
        'finaram-mortgage-approval-estimator',
        $theme_uri . '/assets/js/mortgage-approval-estimator.js',
        array(),
        $version,
        true
    );

    wp_localize_script(
        'finaram-mortgage-approval-estimator',
        'finaramMaeConfig',
        array(
            'baseScore'      => 50,
            'minScore'       => 0,
            'maxScore'       => 97,
            'stepCount'      => 5,
            'lang'           => finaram_mae_is_czech() ? 'cs' : 'en',
            'labelApproval'  => finaram_mae_t('Approval probability', 'Pravděpodobnost schválení'),
            'labelFinal'     => finaram_mae_t(
                'Your estimated approval probability',
                'Vaše odhadovaná pravděpodobnost schválení'
            ),
            'labelBack'      => finaram_mae_t('Back', 'Zpět'),
            'ctaText'        => finaram_mae_t('Get Mortgage Consultation', 'Získat hypoteční konzultaci'),
            'embedUrl'         => finaram_mae_get_embed_url(),
            'consultationUrl'  => function_exists('finaram_get_consultation_url') ? finaram_get_consultation_url() : '',
            'questions'        => finaram_mae_get_questions(),
        )
    );
}
add_action('wp_enqueue_scripts', 'finaram_mortgage_approval_estimator_enqueue_assets', 25);

/**
 * Default copy for the widget (overridable via $args).
 *
 * @return array<string, string>
 */
function finaram_mortgage_approval_estimator_defaults()
{
    return array(
        'title'       => finaram_mae_t(
            'How likely are you to get a mortgage?',
            'Jaká je vaše šance na získání hypotéky?'
        ),
        'description' => finaram_mae_t(
            'Answer five quick questions and see your estimated approval probability.',
            'Odpovězte na pět krátkých otázek a zjistěte odhad pravděpodobnosti schválení hypotéky.'
        ),
        'description_footer' => finaram_mae_t(
            'No signup required.',
            'Bez registrace.'
        ),
        'cta_text'    => finaram_mae_t('Get Mortgage Consultation', 'Získat hypoteční konzultaci'),
        'cta_url'     => function_exists('finaram_get_consultation_url') && finaram_get_consultation_url()
            ? finaram_get_consultation_url()
            : '#',
        'cta_class'   => 'mae__cta js-mae-cta',
    );
}

/**
 * Render the estimator partial (ensures assets are queued via wp_enqueue_scripts).
 *
 * @param array<string, mixed> $args Optional overrides: title, description, cta_text, cta_url, cta_class.
 */
function finaram_render_mortgage_approval_estimator(array $args = array())
{
    $args = wp_parse_args($args, finaram_mortgage_approval_estimator_defaults());
    get_template_part('template-parts/part', 'mortgage-approval-estimator', $args);
}

/**
 * Shortcode: [mortgage_approval_estimator]
 */
function finaram_mortgage_approval_estimator_shortcode($atts)
{
    $atts = shortcode_atts(finaram_mortgage_approval_estimator_defaults(), $atts, 'mortgage_approval_estimator');

    ob_start();
    finaram_render_mortgage_approval_estimator($atts);
    return ob_get_clean();
}
add_shortcode('mortgage_approval_estimator', 'finaram_mortgage_approval_estimator_shortcode');

/**
 * Shortcode: [mortgage_approval_estimator_iframe height="720"]
 * Embeds the language-matched standalone estimator page.
 */
function finaram_mortgage_approval_estimator_iframe_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'height' => '720',
        'title'  => finaram_mae_t('Mortgage approval estimator', 'Odhad schválení hypotéky'),
    ), $atts, 'mortgage_approval_estimator_iframe');

    $url = finaram_mae_get_embed_url();
    if (! $url) {
        return '';
    }

    return sprintf(
        '<iframe class="mae-embed-iframe" src="%s" width="100%%" height="%s" style="border:0;" loading="lazy" title="%s"></iframe>',
        esc_url($url),
        esc_attr($atts['height']),
        esc_attr($atts['title'])
    );
}
add_shortcode('mortgage_approval_estimator_iframe', 'finaram_mortgage_approval_estimator_iframe_shortcode');

/**
 * Allow embedding the standalone estimator page in iframes on partner sites.
 */
function finaram_mae_allow_iframe_embedding()
{
    if (! is_page_template('template-mortgage-approval-estimator.php')) {
        return;
    }

    header_remove('X-Frame-Options');
}
add_action('send_headers', 'finaram_mae_allow_iframe_embedding', 20);

/**
 * SVG circular progress markup (used in mobile + desktop gauge slots).
 */
function finaram_mae_render_gauge_svg()
{
    ?>
    <div class="mae__gauge-wrap">
        <svg class="mae__gauge-svg" viewBox="0 0 200 200" role="img" aria-hidden="true">
            <circle class="mae__gauge-track" cx="100" cy="100" r="88" fill="none" stroke-width="12"/>
            <circle
                class="mae__gauge-fill"
                data-mae-gauge-fill
                cx="100"
                cy="100"
                r="88"
                fill="none"
                stroke-width="12"
                stroke-linecap="round"
                transform="rotate(-90 100 100)"
            />
        </svg>
        <div class="mae__gauge-text">
            <span class="mae__gauge-value" data-mae-gauge-value>50</span><span class="mae__gauge-percent">%</span>
            <span class="mae__gauge-label"><?php echo esc_html(finaram_mae_t('Approval probability', 'Pravděpodobnost schválení')); ?></span>
        </div>
    </div>
    <?php
}
