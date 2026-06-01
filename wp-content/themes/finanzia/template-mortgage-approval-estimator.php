<?php
/**
 * Template Name: Mortgage Approval Estimator
 *
 * Standalone page for the estimator widget (iframe-friendly).
 * Minimal document shell — no site header/footer navigation.
 *
 * @package Finanzia
 */

defined('ABSPATH') || exit;

if (! function_exists('finaram_render_mortgage_approval_estimator')) {
    status_header(500);
    nocache_headers();
    wp_die(
        esc_html__(
            'Mortgage estimator is not installed correctly. Upload libs/mortgage-approval-estimator.php and ensure functions.php includes it, then clear cache.',
            'finanzia'
        ),
        esc_html__('Estimator setup required', 'finanzia'),
        array('response' => 500)
    );
}

$fields = function_exists('get_fields') ? get_fields() : array();
if (! is_array($fields)) {
    $fields = array();
}

$args = array();

if (! empty($fields['mae_title'])) {
    $args['title'] = $fields['mae_title'];
}
if (! empty($fields['mae_description'])) {
    $args['description'] = $fields['mae_description'];
}
if (! empty($fields['mae_cta_text'])) {
    $args['cta_text'] = $fields['mae_cta_text'];
}
if (! empty($fields['mae_cta_url'])) {
    $args['cta_url'] = $fields['mae_cta_url'];
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <?php wp_head(); ?>
</head>
<body <?php body_class('mae-embed-page'); ?>>
<?php wp_body_open(); ?>

<main class="mae-embed-page__main">
    <?php finaram_render_mortgage_approval_estimator($args); ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
