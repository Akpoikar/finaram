<?php
/**
 * CopyContent (new language): blocker `services` meta must point at cookies in the target language.
 *
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-copycontent-blocker-services.php --allow-root
 */
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\Core;

defined('ABSPATH') or die();

require_once __DIR__ . '/wpml-rcb-harness-bootstrap.php';

if (!class_exists('SitePress')) {
    fwrite(STDERR, "WPML not active\n");
    exit(1);
}

wpml_rcb_harness_reset();

$comp = Core::getInstance()->getCompLanguage();
$sync = $comp->getSync();
$defaultLang = $comp->getDefaultLanguage();
$targetLang = $defaultLang === 'en' ? 'de' : 'en';

if (!in_array($targetLang, $comp->getActiveLanguages(), true)) {
    fwrite(STDERR, "Target language {$targetLang} not active in WPML\n");
    exit(1);
}

// EN master cookie + blocker (Gravatar-style wiring).
$enCookieId = (int) wp_insert_post([
    'post_type' => Cookie::CPT_NAME,
    'post_title' => 'Harness CopyContent Service',
    'post_status' => 'publish',
    'post_content' => 'Harness service for CopyContent blocker test.',
]);
if ($enCookieId <= 0) {
    fwrite(STDERR, "cookie insert failed\n");
    exit(1);
}

global $sitepress;
$sitepress->set_element_language_details($enCookieId, 'post_' . Cookie::CPT_NAME, false, $defaultLang);

$enBlockerId = (int) wp_insert_post([
    'post_type' => Blocker::CPT_NAME,
    'post_title' => 'Harness CopyContent Blocker',
    'post_status' => 'publish',
]);
if ($enBlockerId <= 0) {
    fwrite(STDERR, "blocker insert failed\n");
    exit(1);
}

$sitepress->set_element_language_details($enBlockerId, 'post_' . Blocker::CPT_NAME, false, $defaultLang);
update_post_meta($enBlockerId, Blocker::META_NAME_SERVICES, (string) $enCookieId);
update_post_meta($enBlockerId, Blocker::META_NAME_CRITERIA, 'services');

// Remove target-language rows so copyAll mirrors “new language added”.
foreach ([Cookie::CPT_NAME, Blocker::CPT_NAME] as $postType) {
    foreach (
        get_posts([
            'post_type' => $postType,
            'posts_per_page' => -1,
            'post_status' => 'any',
            'suppress_filters' => true,
        ]) as $p
    ) {
        $lang = $sitepress->get_language_for_element($p->ID, 'post_' . $postType);
        if ($lang === $targetLang) {
            wp_delete_post($p->ID, true);
        }
    }
}

$sync->startCopyProcess()->copyAll($defaultLang, [$targetLang]);

$targetCookieIds = [];
foreach (
    get_posts([
        'post_type' => Cookie::CPT_NAME,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'suppress_filters' => true,
    ]) as $cookiePost
) {
    $lang = $sitepress->get_language_for_element($cookiePost->ID, 'post_' . Cookie::CPT_NAME);
    if ($lang === $targetLang) {
        $targetCookieIds[] = (int) $cookiePost->ID;
    }
}
$servicesOk = false;
$checkedBlockerId = null;
$servicesRaw = '';

foreach (
    get_posts([
        'post_type' => Blocker::CPT_NAME,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'suppress_filters' => true,
    ]) as $blocker
) {
    $lang = $sitepress->get_language_for_element($blocker->ID, 'post_' . Blocker::CPT_NAME);
    if ($lang !== $targetLang || $blocker->post_title !== 'Harness CopyContent Blocker') {
        continue;
    }
    $checkedBlockerId = (int) $blocker->ID;
    $servicesRaw = (string) get_post_meta($checkedBlockerId, Blocker::META_NAME_SERVICES, true);
    foreach (explode(',', $servicesRaw) as $part) {
        $id = (int) trim($part);
        if ($id > 0 && in_array($id, $targetCookieIds, true)) {
            $servicesOk = true;
            break 2;
        }
    }
}

echo wp_json_encode(
    [
        'defaultLang' => $defaultLang,
        'targetLang' => $targetLang,
        'targetCookieIds' => $targetCookieIds,
        'targetBlockerId' => $checkedBlockerId,
        'servicesMeta' => $servicesRaw,
        'pass' => [
            'hasTargetBlocker' => $checkedBlockerId > 0,
            'hasTargetCookie' => count($targetCookieIds) > 0,
            'servicesLinkedToTargetCookie' => $servicesOk,
            'all' => $checkedBlockerId > 0 && count($targetCookieIds) > 0 && $servicesOk,
        ],
    ],
    JSON_PRETTY_PRINT
) . "\n";

exit($servicesOk ? 0 : 1);
