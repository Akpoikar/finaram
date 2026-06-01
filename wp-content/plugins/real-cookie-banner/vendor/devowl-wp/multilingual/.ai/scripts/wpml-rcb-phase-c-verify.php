<?php

use DevOwl\RealCookieBanner\Core;
use DevOwl\Multilingual\AbstractSyncPlugin;/**
 * WP-CLI harness: reset RCB, seed default content, duplicate EN cookie to DE, run Phase C checks.
 *
 * Usage (inside WordPress container):
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-phase-c-verify.php --allow-root
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

foreach (get_posts(['post_type' => 'rcb-cookie', 'posts_per_page' => -1, 'suppress_filters' => true]) as $post) {
    $lang = $sitepress->get_language_for_element($post->ID, 'post_rcb-cookie');
    if ($lang !== 'en' && $post->ID !== $enMaster->ID) {
        wp_delete_post($post->ID, true);
    }
}

$comp = Core::getInstance()->getCompLanguage();
if (!$comp instanceof AbstractSyncPlugin) {
    fwrite(STDERR, "Multilingual sync plugin missing\n");
    exit(1);
}

$enGroups = $comp->getObjectTermIdsForPostLanguage($enMaster->ID, 'rcb-cookie-group', 'en');
if ($enGroups === []) {
    fwrite(STDERR, "EN master has no rcb-cookie-group\n");
    exit(1);
}

$deId = $sitepress->make_duplicate($enMaster->ID, 'de');
if (!$deId) {
    fwrite(STDERR, "make_duplicate failed\n");
    exit(1);
}

$deGroupsRest = [];
$req = new WP_REST_Request('GET', '/wp/v2/rcb-cookie/' . $deId);
$req->set_param('lang', 'de');
$data = rest_do_request($req)->get_data();
$deGroupsRest = $data['rcb-cookie-group'] ?? [];
$content = strip_tags($data['content']['rendered'] ?? '');

$deGroupsDb = $comp->getObjectTermIdsForPostLanguage($deId, 'rcb-cookie-group', 'de');
$deTerms = get_terms([
    'taxonomy' => 'rcb-cookie-group',
    'hide_empty' => false,
    'lang' => 'de',
]);
$deTermIds = is_array($deTerms) ? array_map('intval', wp_list_pluck($deTerms, 'term_id')) : [];
$deNames = is_array($deTerms) ? wp_list_pluck($deTerms, 'name') : [];

$deEssential = (int) $sitepress->get_object_id($enGroups[0], 'rcb-cookie-group', false, 'de');
$groupPass =
    $deGroupsRest !== []
    && $deGroupsDb !== []
    && !in_array($enGroups[0], $deGroupsRest, true)
    && !in_array($enGroups[0], $deGroupsDb, true)
    && ($deEssential <= 0 || in_array($deEssential, $deGroupsRest, true));

$contentPass = str_contains($content, 'bittet Website-Besucher')
    || str_contains($content, 'Website-Besucher');

$dupNames = count($deNames) !== count(array_unique($deNames));
$countPass = count($deTermIds) === 4 && !$dupNames;

$result = [
    'enMasterId' => $enMaster->ID,
    'deDuplicateId' => $deId,
    'enGroups' => $enGroups,
    'deGroupsDb' => $deGroupsDb,
    'deGroupsRest' => $deGroupsRest,
    'deEssentialExpected' => $deEssential,
    'deGroupCount' => count($deTermIds),
    'deGroupNames' => $deNames,
    'contentLead' => substr($content, 0, 80),
    'pass' => [
        'group' => $groupPass,
        'content' => $contentPass,
        'deGroupCatalog' => $countPass,
        'all' => $groupPass && $contentPass && $countPass,
    ],
];

echo wp_json_encode($result, JSON_PRETTY_PRINT) . "\n";
exit($result['pass']['all'] ? 0 : 1);
