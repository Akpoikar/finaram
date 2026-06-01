<?php
/**
 * Mortgage Approval Estimator — asset loading and render helpers.
 *
 * @package Finanzia
 */

defined('ABSPATH') || exit;

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

    global $post;
    if ($post instanceof WP_Post && has_shortcode($post->post_content, 'mortgage_approval_estimator')) {
        return true;
    }

    return (bool) apply_filters('finaram_load_mortgage_approval_estimator', false);
}

/**
 * Enqueue scoped CSS/JS for the estimator widget.
 */
function finaram_mortgage_approval_estimator_enqueue_assets()
{
    if (!finaram_should_load_mortgage_approval_estimator()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $version   = defined('_S_VERSION') ? _S_VERSION : '1.0.0';

    // Standalone embed page: skip heavy global theme bundles for faster iframe loads.
    if (is_page_template('template-mortgage-approval-estimator.php')) {
        wp_dequeue_style('main-css');
        wp_dequeue_script('app-js');
        wp_dequeue_script('function');
    }

    wp_enqueue_style(
        'finaram-mortgage-approval-estimator',
        $theme_uri . '/assets/css/mortgage-approval-estimator.css',
        array(),
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
            'labelApproval'  => __('Approval probability', 'finanzia'),
            'labelFinal'     => __('Your estimated approval probability', 'finanzia'),
            'ctaText'        => __('Get Mortgage Consultation', 'finanzia'),
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
        'title'       => __('How likely are you to get a mortgage?', 'finanzia'),
        'description' => __('Answer five quick questions and see your estimated approval probability. No signup required.', 'finanzia'),
        'cta_text'    => __('Get Mortgage Consultation', 'finanzia'),
        'cta_url'     => '#',
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
 * Allow embedding the standalone estimator page in iframes on partner sites.
 * Adjust frame-ancestors in CSP if your host sends a restrictive policy.
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
            <span class="mae__gauge-label"><?php esc_html_e('Approval probability', 'finanzia'); ?></span>
        </div>
    </div>
    <?php
}
