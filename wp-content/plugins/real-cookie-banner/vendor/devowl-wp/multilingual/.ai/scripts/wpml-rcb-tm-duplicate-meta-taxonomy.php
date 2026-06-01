<?php
/**
 * TM duplicate after taxonomy sync: functional group, copy-once provider meta, blocker presetId.
 *
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-tm-duplicate-meta-taxonomy.php --allow-root
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
$sync = $comp->getSync();

// Mirror “taxonomy synced first” via DevOwl copy for DE groups.
$sync->startCopyProcess()->copyAll('en', ['de']);

$functionalEn = get_term_by('name', 'Functional', CookieGroup::TAXONOMY_NAME);
$essentialEn = get_term_by('name', 'Essential', CookieGroup::TAXONOMY_NAME);
$functionalDe = get_term_by('name', 'Funktional', CookieGroup::TAXONOMY_NAME);
$essentialDe = get_term_by('name', 'Essenziell', CookieGroup::TAXONOMY_NAME);

if (
    !$functionalEn instanceof WP_Term
    || !$essentialEn instanceof WP_Term
    || !$functionalDe instanceof WP_Term
    || !$essentialDe instanceof WP_Term
) {
    fwrite(STDERR, "Expected EN/DE service group terms missing\n");
    exit(1);
}

$enCookieId = (int) wp_insert_post([
    'post_type' => Cookie::CPT_NAME,
    'post_title' => 'Harness TM Gravatar',
    'post_status' => 'publish',
    'post_content' => 'Harness TM duplicate service.',
]);
$sitepress->set_element_language_details($enCookieId, 'post_' . Cookie::CPT_NAME, false, 'en');
wp_set_object_terms($enCookieId, [(int) $functionalEn->term_id], CookieGroup::TAXONOMY_NAME);
update_post_meta($enCookieId, Cookie::META_NAME_PROVIDER, 'Harness Provider EN');
update_post_meta($enCookieId, Cookie::META_NAME_PROVIDER_CONTACT_EMAIL, 'harness-en@example.com');
update_post_meta($enCookieId, Cookie::META_NAME_IS_PROVIDER_CURRENT_WEBSITE, '0');

$enBlockerId = (int) wp_insert_post([
    'post_type' => Blocker::CPT_NAME,
    'post_title' => 'Harness TM Gravatar Blocker',
    'post_status' => 'publish',
]);
$sitepress->set_element_language_details($enBlockerId, 'post_' . Blocker::CPT_NAME, false, 'en');
update_post_meta($enBlockerId, Blocker::META_NAME_SERVICES, (string) $enCookieId);
update_post_meta($enBlockerId, Blocker::META_NAME_CRITERIA, 'services');
update_post_meta($enBlockerId, Blocker::META_NAME_PRESET_ID, 'harness-gravatar-preset');

$deCookieId = (int) $sitepress->make_duplicate($enCookieId, 'de');
$deBlockerId = (int) $sitepress->make_duplicate($enBlockerId, 'de');

if ($deCookieId <= 0 || $deBlockerId <= 0) {
    fwrite(STDERR, "make_duplicate failed\n");
    exit(1);
}

$deGroupIds = wp_get_object_terms($deCookieId, CookieGroup::TAXONOMY_NAME, ['fields' => 'ids']);
$deGroupIds = is_wp_error($deGroupIds) ? [] : array_map('intval', (array) $deGroupIds);

$servicesOnDeBlocker = (string) get_post_meta($deBlockerId, Blocker::META_NAME_SERVICES, true);
$presetOnDeBlocker = (string) get_post_meta($deBlockerId, Blocker::META_NAME_PRESET_ID, true);
$providerOnDeCookie = (string) get_post_meta($deCookieId, Cookie::META_NAME_PROVIDER, true);
$emailOnDeCookie = (string) get_post_meta($deCookieId, Cookie::META_NAME_PROVIDER_CONTACT_EMAIL, true);

$groupIsFunctional = in_array((int) $functionalDe->term_id, $deGroupIds, true)
    && !in_array((int) $essentialDe->term_id, $deGroupIds, true);
$servicesIsDeCookie = $servicesOnDeBlocker === (string) $deCookieId;
$presetPresent = $presetOnDeBlocker === 'harness-gravatar-preset';
$providerPresent = $providerOnDeCookie !== '';
$emailPresent = $emailOnDeCookie !== '';

echo wp_json_encode(
    [
        'deCookieId' => $deCookieId,
        'deBlockerId' => $deBlockerId,
        'deGroupIds' => $deGroupIds,
        'functionalDeId' => (int) $functionalDe->term_id,
        'essentialDeId' => (int) $essentialDe->term_id,
        'servicesMeta' => $servicesOnDeBlocker,
        'presetId' => $presetOnDeBlocker,
        'provider' => $providerOnDeCookie,
        'providerEmail' => $emailOnDeCookie,
        'pass' => [
            'functionalGroup' => $groupIsFunctional,
            'servicesRemapped' => $servicesIsDeCookie,
            'presetId' => $presetPresent,
            'providerPresent' => $providerPresent,
            'providerEmail' => $emailPresent,
            'all' => $groupIsFunctional
                && $servicesIsDeCookie
                && $presetPresent
                && $providerPresent
                && $emailPresent,
        ],
    ],
    JSON_PRETTY_PRINT
) . "\n";

exit($groupIsFunctional && $servicesIsDeCookie && $presetPresent && $providerPresent && $emailPresent ? 0 : 1);
