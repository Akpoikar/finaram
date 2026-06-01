<?php

use DevOwl\RealCookieBanner\Core;/**
 * CG-TT smoke harness (run via `wp eval-file` inside the WordPress container).
 * Simulates WPML "Copy to all languages" via `WPML_Terms_Translations::create_new_term`.
 *
 * Path: wordpress-packages/multilingual/.ai/scripts/cg-tt-wpml-smoke.php
 */
defined('ABSPATH') or die();

require_once __DIR__ . '/wpml-rcb-harness-bootstrap.php';

$taxonomy = 'rcb-cookie-group';
global $sitepress;

if (!class_exists('WPML_Terms_Translations')) {
    echo wp_json_encode(['error' => 'WPML not loaded'], JSON_PRETTY_PRINT) . "\n";
    exit(1);
}

wpml_rcb_harness_reset();

$compLanguage = Core::getInstance()->getCompLanguage();
$defaultLanguage = $compLanguage->getDefaultLanguage();

$sitepress->switch_lang($defaultLanguage);
$setupDefaultCount = count(get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]) ?: []);
$sitepress->switch_lang('de');
$setupDeCount = count(get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]) ?: []);
if ($setupDefaultCount < 4 || $setupDeCount < 4) {
    echo wp_json_encode(
        [
            'error' => 'setup_failed',
            'setupDefaultCount' => $setupDefaultCount,
            'setupDeCount' => $setupDeCount,
            'defaultLanguage' => $defaultLanguage,
        ],
        JSON_PRETTY_PRINT
    ) . "\n";
    exit(1);
}

$elementType = 'tax_' . $taxonomy;

$fetchByLang = static function (string $lang) use ($taxonomy): array {
    global $sitepress;
    $sitepress->switch_lang($lang);
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (!is_array($terms)) {
        return [];
    }
    $rows = [];
    foreach ($terms as $term) {
        if (!$term instanceof WP_Term) {
            continue;
        }
        $rows[] = ['id' => $term->term_id, 'name' => $term->name];
    }
    $sitepress->switch_lang($sitepress->get_default_language());
    return $rows;
};

// CG-A: delete EN groups (WPML language context, same as admin edit-tags.php?lang=en)
$sitepress->switch_lang('en');
foreach (get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]) ?: [] as $term) {
    if ($term instanceof WP_Term) {
        wp_delete_term($term->term_id, $taxonomy);
    }
}

$enAfterDelete = count($fetchByLang('en'));

// CG-B: copy each source-language original to EN (like WPML "Copy to all languages")
$rowCount = 0;
$activeLanguages = array_keys((array) $sitepress->get_active_languages());
$sitepress->switch_lang('de');
foreach (get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]) ?: [] as $term) {
    if (!$term instanceof WP_Term) {
        continue;
    }
    $details = apply_filters('wpml_element_language_details', null, [
        'element_id' => $term->term_taxonomy_id,
        'element_type' => $elementType,
    ]);
    if (!is_object($details) || !empty($details->source_language_code)) {
        continue;
    }

    $sourceLocale = $details->language_code ?? '';
    $trid = (int) ($details->trid ?? 0);
    if ($sourceLocale === '' || $trid <= 0) {
        continue;
    }

    $translations = $sitepress->get_element_translations($trid, $elementType);
    if (!is_array($translations)) {
        continue;
    }

    foreach ($activeLanguages as $targetLocale) {
        if ($targetLocale === $sourceLocale) {
            continue;
        }
        if (isset($translations[$targetLocale]) && !empty($translations[$targetLocale]->element_id)) {
            continue;
        }

        WPML_Terms_Translations::create_new_term([
            'taxonomy' => $taxonomy,
            'lang_code' => $targetLocale,
            'term' => $term->name,
            'trid' => $trid,
            'description' => $term->description,
            'overwrite' => true,
        ]);
        ++$rowCount;
    }
}

// CG-C: verify
$en = $fetchByLang('en');
$de = $fetchByLang('de');
$enNames = array_column($en, 'name');
$deNames = array_column($de, 'name');
$dup = static function (array $names): bool {
    return count($names) !== count(array_unique($names));
};
$enExpected = ['Essential', 'Functional', 'Marketing', 'Statistics', 'Statistic'];
$enHasEnglishDefaults = count(array_intersect($enExpected, $enNames)) >= 4;

$cgPass =
    count($en) === 4
    && count($de) === 4
    && !$dup($enNames)
    && !$dup($deNames)
    && $enHasEnglishDefaults;

$result = [
    'CG-A' => ['enAfterDelete' => $enAfterDelete, 'pass' => $enAfterDelete === 0],
    'CG-B' => ['rowCount' => $rowCount],
    'CG-C' => [
        'enCount' => count($en),
        'deCount' => count($de),
        'dupEn' => $dup($enNames),
        'dupDe' => $dup($deNames),
        'enNames' => $enNames,
        'deNames' => $deNames,
        'enHasEnglishDefaults' => $enHasEnglishDefaults,
        'pass' => $cgPass,
    ],
];

echo wp_json_encode($result, JSON_PRETTY_PRINT) . "\n";
exit($cgPass && $enAfterDelete === 0 ? 0 : 1);
