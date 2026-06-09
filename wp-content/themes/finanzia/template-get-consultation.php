<?php
/**
 * Template Name: Get Consultation
 *
 * Dedicated consultation page with full site header/footer.
 *
 * @package Finanzia
 */

defined('ABSPATH') || exit;

$fields = function_exists('get_fields') ? get_fields() : array();
if (! is_array($fields)) {
    $fields = array();
}

$args = finaram_get_consultation_defaults();

if (! empty($fields['consult_title'])) {
    $args['title'] = $fields['consult_title'];
}
if (! empty($fields['consult_description'])) {
    $args['description'] = $fields['consult_description'];
}

get_header();
?>

    <div class="consult-page-wrap">
        <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="ajax-link" itemprop="item" href="<?= home_url(); ?>">
                    <span itemprop="name"><?php _e('Home', 'finanzia'); ?></span>
                </a>
                <meta itemprop="position" content="1"/>
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="2"/>
            </li>
        </ol>

        <?php finaram_render_get_consultation($args); ?>
    </div>

<?php
get_footer();
