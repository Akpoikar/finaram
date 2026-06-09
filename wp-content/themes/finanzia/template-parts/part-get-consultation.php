<?php
/**
 * Template part: Get Consultation form.
 *
 * @package Finanzia
 *
 * @var array $args {
 *     @type string $title
 *     @type string $description
 * }
 */

defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? array(), finaram_get_consultation_defaults());

$title       = esc_html($args['title']);
$description = esc_html($args['description']);
$cf7_id      = finaram_get_consultation_cf7_id();
?>

<section class="consult-page" aria-label="<?php echo esc_attr($title); ?>">
    <div class="consult-page__layout">
        <div class="consult-page__panel">
            <?php if ($title) : ?>
                <h1 class="consult-page__title"><?php echo $title; ?></h1>
            <?php endif; ?>

            <?php if ($description) : ?>
                <p class="consult-page__description"><?php echo $description; ?></p>
            <?php endif; ?>

            <div class="consult-page__form">
                <?php
                echo do_shortcode(
                    sprintf(
                        '[contact-form-7 id="%d" html_class="popup__aside-form consult-page__cf7"]',
                        $cf7_id
                    )
                );
                ?>
            </div>
        </div>
    </div>
</section>
