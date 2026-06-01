<?php
/**
 * Verify Gravatar service template resolves the cookie id for the active _dataLocale (EN).
 *
 * Uses the Services catalog ("Cookies > Services > Hinzufügen") and `servicesCreated()` —
 * the same path as the admin UI, not the nested blocker-only preview without created posts.
 *
 * Usage:
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-template-consumer-id-check.php --allow-root
 */
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\ServiceCloudConsumer\consumer\VariableResolver;

defined('ABSPATH') or die();

require_once __DIR__ . '/wpml-rcb-harness-bootstrap.php';

if (!class_exists('SitePress')) {
    fwrite(STDERR, "WPML not active\n");
    exit(1);
}

wpml_rcb_harness_reset();
wp_rcb_invalidate_templates_cache();

global $sitepress;
$identifier = 'gravatar-avatar-images';

$findGravatarCookieId = static function (string $lang) use ($sitepress, $identifier): int {
    foreach (
        get_posts([
            'post_type' => Cookie::CPT_NAME,
            'posts_per_page' => -1,
            'suppress_filters' => true,
            'meta_query' => [
                [
                    'key' => 'presetId',
                    'value' => $identifier,
                ],
            ],
        ])
        as $post
    ) {
        if ($sitepress->get_language_for_element($post->ID, 'post_' . Cookie::CPT_NAME) === $lang) {
            return (int) $post->ID;
        }
    }

    return 0;
};

$ensureGravatarInLanguage = static function (string $lang) use (
    $sitepress,
    $identifier,
    $findGravatarCookieId
): int {
    $existing = $findGravatarCookieId($lang);
    if ($existing > 0) {
        return $existing;
    }

    $sourceLang = $lang === 'en' ? 'de' : 'en';
    $sourceId = $findGravatarCookieId($sourceLang);
    if ($sourceId > 0) {
        $duplicated = (int) $sitepress->make_duplicate($sourceId, $lang);
        if ($duplicated > 0) {
            update_post_meta($duplicated, 'presetId', $identifier);
            return $duplicated;
        }
    }

    $sitepress->switch_lang($lang);
    $_GET['_dataLocale'] = $lang === 'en' ? 'en' : 'de';

    $templates = TemplateConsumers::getCurrentServiceConsumer()->retrieveBy('identifier', $identifier);
    if (count($templates) === 0) {
        return 0;
    }

    $essential = CookieGroup::getInstance()->getEssentialGroup(true);
    if ($essential === null) {
        return 0;
    }

    TemplateConsumers::getInstance()->createFromTemplate($templates[0], [
        CookieGroup::TAXONOMY_NAME => $essential->term_id,
    ]);

    return $findGravatarCookieId($lang);
};

$enCookieId = $findGravatarCookieId('en');
$deCookieId = $findGravatarCookieId('de');
if ($enCookieId <= 0 || $deCookieId <= 0) {
    wp_rcb_invalidate_templates_cache();
    $enCookieId = $ensureGravatarInLanguage('en');
    $deCookieId = $ensureGravatarInLanguage('de');
}

if ($enCookieId <= 0 || $deCookieId <= 0) {
    fwrite(STDERR, "Could not seed Gravatar cookies (en=$enCookieId de=$deCookieId)\n");
    exit(1);
}

$resolveGravatarConsumerId = static function (string $locale) use ($identifier): ?int {
    $_GET['_dataLocale'] = $locale === 'en' ? 'en' : 'de';
    global $sitepress;
    $sitepress->switch_lang($locale);

    $consumer = TemplateConsumers::getCurrentServiceConsumer();
    $resolver = new VariableResolver($consumer);
    $resolver->add('context', TemplateConsumers::getContext());

    foreach (TemplateConsumers::getInstance()->servicesCreated($resolver) as $template) {
        if ($template->identifier === $identifier) {
            return isset($template->consumerData['id']) ? (int) $template->consumerData['id'] : null;
        }
    }

    return null;
};

$serviceIdEn = $resolveGravatarConsumerId('en');
$serviceIdDe = $resolveGravatarConsumerId('de');

$result = [
    'context' => TemplateConsumers::getContext(),
    'wpmlCurrent' => $sitepress->get_current_language(),
    'enCookieId' => $enCookieId,
    'deCookieId' => $deCookieId,
    'serviceTemplateConsumerIdEn' => $serviceIdEn,
    'serviceTemplateConsumerIdDe' => $serviceIdDe,
    'pass' => [
        'en' => $serviceIdEn === $enCookieId,
        'de' => $serviceIdDe === $deCookieId,
        'all' => $serviceIdEn === $enCookieId && $serviceIdDe === $deCookieId,
    ],
];

echo wp_json_encode($result, JSON_PRETTY_PRINT) . "\n";
exit($result['pass']['all'] ? 0 : 1);
