<?php

use DevOwl\RealCookieBanner\settings\CookieGroup;
use MatthiasWeb\Utils\Utils;
use DevOwl\RealCookieBanner\Core;
use DevOwl\Multilingual\AbstractSyncPlugin;/**
 * WP-CLI harness: reset, DE-only source with EN group term, duplicate DE → EN, assert groups on EN duplicate.
 *
 * Usage (inside WordPress container):
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-phase-c-de-en-verify.php --allow-root
 */
defined('ABSPATH') or die();

require_once __DIR__ . '/wpml-rcb-harness-bootstrap.php';

if (!class_exists('SitePress')) {
    fwrite(STDERR, "WPML not active\n");
    exit(1);
}

wpml_rcb_harness_reset();

global $sitepress;

// WPML language filters hide the EN master when admin/default lang is DE; bypass for harness discovery.
$enMaster = null;
foreach (get_posts(['post_type' => 'rcb-cookie', 'posts_per_page' => -1, 'suppress_filters' => true]) as $post) {
    $lang = $sitepress->get_language_for_element($post->ID, 'post_rcb-cookie');
    if ($post->post_title === 'Real Cookie Banner' && $lang === 'en') {
        $enMaster = $post;
        break;
    }
}
if ($enMaster === null) {
    foreach (get_posts(['post_type' => 'rcb-cookie', 'posts_per_page' => -1, 'suppress_filters' => true]) as $post) {
        if ($post->post_title === 'Real Cookie Banner') {
            $enMaster = $post;
            break;
        }
    }
}
if ($enMaster === null) {
    fwrite(STDERR, "EN Real Cookie Banner missing after reset\n");
    exit(1);
}

$enGroups = wp_get_object_terms($enMaster->ID, 'rcb-cookie-group', ['fields' => 'ids']);
if (is_wp_error($enGroups)) {
    $enGroups = [];
}
if ($enGroups === []) {
    $essential = CookieGroup::getInstance()->getEssentialGroup(true);
    if ($essential === null) {
        fwrite(STDERR, "EN master has no rcb-cookie-group and essential group is missing\n");
        exit(1);
    }
    wp_set_object_terms($enMaster->ID, (int) $essential->term_id, 'rcb-cookie-group');
    $enGroups = [(int) $essential->term_id];
}

$deId = $sitepress->make_duplicate($enMaster->ID, 'de');
if (!$deId) {
    fwrite(STDERR, "make_duplicate EN→DE failed\n");
    exit(1);
}

// Reproduce stacks where the EN translation row was removed but DE still exists: drop EN siblings
// before wiring cross-language group ids (deleting EN after wiring clears DE assignments).
foreach (get_posts(['post_type' => 'rcb-cookie', 'posts_per_page' => -1, 'suppress_filters' => true]) as $post) {
    $lang = $sitepress->get_language_for_element($post->ID, 'post_rcb-cookie');
    if ($lang === 'en' && (int) $post->ID !== (int) $deId) {
        wp_delete_post($post->ID, true);
    }
}

// Post-reset / EN-deleted stacks: DE cookie still wired to EN group term ids.
Utils::withoutFilters('set_object_terms', function () use ($deId, $enGroups) {
    wp_set_object_terms($deId, array_map('intval', $enGroups), 'rcb-cookie-group');
});

$deGroupsBefore = wp_get_object_terms($deId, 'rcb-cookie-group', ['fields' => 'ids']);
if (is_wp_error($deGroupsBefore) || $deGroupsBefore === []) {
    fwrite(STDERR, "DE source has no rcb-cookie-group before EN duplicate\n");
    exit(1);
}

$enDupId = $sitepress->make_duplicate($deId, 'en');
if (!$enDupId) {
    fwrite(STDERR, "make_duplicate DE→EN failed\n");
    exit(1);
}

global $wpdb;
$relCount = (int) $wpdb->get_var(
    $wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->term_relationships} tr
         JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
         WHERE tr.object_id = %d AND tt.taxonomy = %s",
        $enDupId,
        'rcb-cookie-group'
    )
);

$enGroupsDb = wp_get_object_terms($enDupId, 'rcb-cookie-group', ['fields' => 'ids']);
if (is_wp_error($enGroupsDb)) {
    $enGroupsDb = [];
}

$comp = Core::getInstance()->getCompLanguage();
$enGroupsLang = $comp instanceof AbstractSyncPlugin
    ? $comp->getObjectTermIdsForPostLanguage($enDupId, 'rcb-cookie-group', 'en')
    : [];

$req = new WP_REST_Request('GET', '/wp/v2/rcb-cookie/' . $enDupId);
$req->set_param('lang', 'en');
$data = rest_do_request($req)->get_data();
$enGroupsRest = $data['rcb-cookie-group'] ?? [];

$groupPass = $relCount > 0 && $enGroupsDb !== [] && $enGroupsLang !== [] && $enGroupsRest !== [];

$result = [
    'deSourceId' => $deId,
    'deGroupsBefore' => array_map('intval', (array) $deGroupsBefore),
    'enDuplicateId' => $enDupId,
    'relCount' => $relCount,
    'enGroupsDb' => array_map('intval', (array) $enGroupsDb),
    'enGroupsLang' => $enGroupsLang,
    'enGroupsRest' => $enGroupsRest,
    'pass' => ['group' => $groupPass, 'all' => $groupPass],
];

echo wp_json_encode($result, JSON_PRETTY_PRINT) . "\n";
exit($groupPass ? 0 : 1);
