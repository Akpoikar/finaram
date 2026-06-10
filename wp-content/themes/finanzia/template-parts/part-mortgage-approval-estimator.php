<?php
/**
 * Template part: Mortgage Approval Estimator widget.
 *
 * Reusable partial — pass $args for headline, description, and CTA.
 *
 * @package Finanzia
 *
 * @var array $args {
 *     @type string $title
 *     @type string $description
 *     @type string $description_footer
 *     @type string $cta_text
 *     @type string $cta_url
 *     @type string $cta_class Extra classes for CTA (e.g. popup trigger hooks).
 * }
 */

defined('ABSPATH') || exit;

$args = wp_parse_args(
    $args ?? array(),
    finaram_mortgage_approval_estimator_defaults()
);

$title              = esc_html($args['title']);
$description        = esc_html($args['description']);
$description_footer = esc_html($args['description_footer'] ?? '');
$cta_text           = esc_html($args['cta_text']);
$cta_url     = esc_url($args['cta_url']);
$cta_class   = esc_attr($args['cta_class']);
?>

<section
    class="mae"
    id="mae-widget"
    data-mae-widget
    aria-label="<?php echo esc_attr(finaram_mae_t('Mortgage approval estimator', 'Odhad schválení hypotéky')); ?>"
>
    <div class="mae__inner">
        <div class="mae__layout">
            <div class="mae__main">
                <header class="mae__header">
                    <?php if ($title) : ?>
                        <h1 class="mae__title"><?php echo $title; ?></h1>
                    <?php endif; ?>
                    <?php if ($description) : ?>
                        <p class="mae__description"><?php echo $description; ?></p>
                    <?php endif; ?>
                    <?php if ($description_footer) : ?>
                        <p class="mae__description-footer"><?php echo $description_footer; ?></p>
                    <?php endif; ?>
                </header>

                <?php
                // Gauge markup is duplicated for mobile (between header & questions) and desktop (sidebar).
                // JS syncs both instances.
                ?>
                <div class="mae__gauge mae__gauge--mobile" data-mae-gauge-mobile aria-hidden="false">
                    <?php finaram_mae_render_gauge_svg(); ?>
                </div>

                <div class="mae__questionnaire" data-mae-questionnaire>
                    <div class="mae__progress" aria-hidden="true">
                        <span class="mae__progress-bar" data-mae-progress-bar></span>
                    </div>

                    <div class="mae__steps" data-mae-steps></div>

                    <div class="mae__final" data-mae-final hidden>
                        <p class="mae__final-label"><?php echo esc_html(finaram_mae_t('Your estimated approval probability', 'Vaše odhadovaná pravděpodobnost schválení')); ?></p>
                        <p class="mae__final-score" data-mae-final-score>50%</p>
                        <a
                            href="<?php echo $cta_url; ?>"
                            class="<?php echo $cta_class; ?>"
                            data-mae-cta
                        ><?php echo $cta_text; ?></a>
                    </div>
                </div>
            </div>

            <aside class="mae__aside" aria-label="<?php echo esc_attr(finaram_mae_t('Approval probability indicator', 'Ukazatel pravděpodobnosti schválení')); ?>">
                <div class="mae__gauge mae__gauge--desktop" data-mae-gauge-desktop>
                    <?php finaram_mae_render_gauge_svg(); ?>
                </div>
            </aside>
        </div>
    </div>
</section>
