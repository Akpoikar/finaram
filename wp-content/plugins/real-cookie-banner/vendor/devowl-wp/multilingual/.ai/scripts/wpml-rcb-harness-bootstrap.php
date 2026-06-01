<?php
/**
 * Shared harness bootstrap: admin context, reliable RCB reset, multilingual sync registration.
 *
 * Load via `require` from other `.ai/scripts/*.php` harnesses (not via `wp eval-file` alone).
 */
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\Reset;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Localization;

defined('ABSPATH') or die();

/**
 * Reset RCB fixture data so repeated harness runs stay isolated.
 *
 * `Reset::all()` alone can leave `rcb-cookie-group` terms without cookies on the second run
 * (groups exist → `addInitialContent()` skips default service creation). Purge orphan groups
 * and re-seed when no cookies remain.
 */
function wpml_rcb_harness_reset(): void {
    wp_set_current_user(1);
    if (!defined('WP_ADMIN')) {
        define('WP_ADMIN', true);
    }

    Reset::getInstance()->all(false);

    global $sitepress, $wpdb;
    $taxonomy = 'rcb-cookie-group';
    $comp = Core::getInstance()->getCompLanguage();
    $defaultLanguage = $comp !== null ? $comp->getDefaultLanguage() : 'en';

    if (isset($sitepress)) {
        $sitepress->switch_lang($defaultLanguage);
    }
    $defaultGroupCount = count(
        get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]) ?: []
    );

    $hasEnBanner = false;
    if (isset($sitepress)) {
        foreach (
            get_posts([
                'post_type' => Cookie::CPT_NAME,
                'posts_per_page' => -1,
                'suppress_filters' => true,
                'post_status' => 'any',
            ])
            as $post
        ) {
            if (
                $post->post_title === 'Real Cookie Banner'
                && $sitepress->get_language_for_element($post->ID, 'post_' . Cookie::CPT_NAME) === 'en'
            ) {
                $hasEnBanner = true;
                break;
            }
        }
    }

    $cookieCount = count(
        get_posts([
            'post_type' => Cookie::CPT_NAME,
            'posts_per_page' => -1,
            'suppress_filters' => true,
            'post_status' => 'any',
        ])
    );

    $needsReseed = $cookieCount < 2 || $defaultGroupCount < 4 || !$hasEnBanner;

    if (!$needsReseed) {
        wpml_rcb_harness_ensure_multilingual();
        return;
    }

    if (isset($sitepress)) {
        foreach (array_keys((array) $sitepress->get_active_languages()) as $lang) {
            $sitepress->switch_lang($lang);
            foreach (get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]) ?: [] as $term) {
                wp_delete_term($term->term_id, $taxonomy);
            }
        }
    } else {
        $termIds = $wpdb->get_col(
            $wpdb->prepare("SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s", $taxonomy)
        );
        foreach ($termIds as $termId) {
            wp_delete_term((int) $termId, $taxonomy);
        }
    }

    foreach (
        get_posts([
            'post_type' => Cookie::CPT_NAME,
            'posts_per_page' => -1,
            'suppress_filters' => true,
            'post_status' => 'any',
        ])
        as $post
    ) {
        wp_delete_post($post->ID, true);
    }

    Core::getInstance()->getActivator()->addInitialContent();
    wpml_rcb_harness_ensure_multilingual();
}

/**
 * Register RCB ↔ multilingual sync once per request (safe after `wpml_rcb_harness_reset()`).
 */
function wpml_rcb_harness_ensure_multilingual(): void {
    $comp = Core::getInstance()->getCompLanguage();
    if ($comp === null || $comp->getSync() !== null) {
        return;
    }

    Localization::multilingual();
}
