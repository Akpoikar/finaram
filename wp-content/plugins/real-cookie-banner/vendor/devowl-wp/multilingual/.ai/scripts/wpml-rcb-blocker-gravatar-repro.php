<?php
/**
 * Repro: reset → Gravatar-like cookie+blocker → delete blocker in one language → WPML duplicate → wrong services meta.
 *
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-blocker-gravatar-repro.php --allow-root
 */
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\Core;

defined('ABSPATH') or die();

require_once __DIR__ . '/wpml-rcb-harness-bootstrap.php';

if (!class_exists('SitePress')) {
    fwrite(STDERR, "WPML not active\n");
    exit(1);
}

wpml_rcb_harness_reset();

global $sitepress;
$comp = Core::getInstance()->getCompLanguage();

$essentialId = CookieGroup::getInstance()->getEssentialGroup(true)->term_id;

// Stand-in for scanner-created Gravatar (EN master).
$enCookieId = wp_insert_post([
    'post_type' => Cookie::CPT_NAME,
    'post_title' => 'Gravatar',
    'post_status' => 'publish',
    'post_content' => 'Gravatar test service',
]);
if (is_wp_error($enCookieId) || $enCookieId <= 0) {
    fwrite(STDERR, "EN cookie insert failed\n");
    exit(1);
}
$sitepress->set_element_language_details($enCookieId, 'post_' . Cookie::CPT_NAME, false, 'en');
wp_set_object_terms($enCookieId, $essentialId, CookieGroup::TAXONOMY_NAME);

$deCookieId = $sitepress->make_duplicate($enCookieId, 'de');
if (!$deCookieId) {
    fwrite(STDERR, "cookie make_duplicate EN→DE failed\n");
    exit(1);
}

$enBlockerId = wp_insert_post([
    'post_type' => Blocker::CPT_NAME,
    'post_title' => 'Gravatar',
    'post_status' => 'publish',
]);
if (is_wp_error($enBlockerId) || $enBlockerId <= 0) {
    fwrite(STDERR, "EN blocker insert failed\n");
    exit(1);
}
$sitepress->set_element_language_details($enBlockerId, 'post_' . Blocker::CPT_NAME, false, 'en');
update_post_meta($enBlockerId, Blocker::META_NAME_SERVICES, (string) $enCookieId);
update_post_meta($enBlockerId, Blocker::META_NAME_CRITERIA, 'services');
update_post_meta($enBlockerId, Blocker::META_NAME_RULES, 'img[src*="gravatar.com"]');

// Mirror UI: delete Gravatar content blocker for German only (cookies stay in both languages).
foreach (get_posts(['post_type' => Blocker::CPT_NAME, 'posts_per_page' => -1, 'post_status' => 'any']) as $p) {
    $lang = $sitepress->get_language_for_element($p->ID, 'post_' . Blocker::CPT_NAME);
    if ($lang === 'de') {
        wp_delete_post($p->ID, true);
    }
}

$deBlockerDupId = $sitepress->make_duplicate($enBlockerId, 'de');
if (!$deBlockerDupId) {
    fwrite(STDERR, "blocker make_duplicate EN→DE failed\n");
    exit(1);
}

$servicesOnDup = get_post_meta($deBlockerDupId, Blocker::META_NAME_SERVICES, true);
$deCookies = get_posts(['post_type' => Cookie::CPT_NAME, 'lang' => 'de', 'posts_per_page' => 5, 'post_status' => 'publish']);
$deGravatar = null;
foreach ($deCookies as $p) {
    if ($p->post_title === 'Gravatar') {
        $deGravatar = $p->ID;
        break;
    }
}

$expectedDeId = $deGravatar > 0 ? (string) $deGravatar : null;
$bugWrongService = $expectedDeId !== null && (string) $servicesOnDup !== $expectedDeId;
$bugPointsToEn = (string) $servicesOnDup === (string) $enCookieId;

$result = [
    'enCookieId' => $enCookieId,
    'deGravatarCookieId' => $deGravatar,
    'enBlockerId' => $enBlockerId,
    'deBlockerDupId' => $deBlockerDupId,
    'servicesOnDeBlockerDup' => $servicesOnDup,
    'pass' => [
        'servicesIsDeGravatar' => $expectedDeId !== null && (string) $servicesOnDup === $expectedDeId,
        'reproWrongService' => $bugWrongService || ($bugPointsToEn && $expectedDeId !== null),
    ],
];

echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
exit($result['pass']['servicesIsDeGravatar'] ? 0 : 1);
