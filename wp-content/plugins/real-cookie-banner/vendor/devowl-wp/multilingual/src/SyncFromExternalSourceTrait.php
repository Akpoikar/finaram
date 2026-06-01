<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use WP_Post;
use WP_Term;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Apply Sync rules after an external multilingual plugin (e.g. WPML) created a translation.
 *
 * Expects `AbstractLanguagePlugin` helpers (`translateInput`, `filterMetaValue`, `withSourceTranslationCatalog`, …).
 * WPML-specific hooks and defer overrides live in `WPML`.
 * @internal
 */
trait SyncFromExternalSourceTrait
{
    /**
     * True while `syncPostFromExternalSource()` / `syncTermFromExternalSource()` write target
     * fields. Suppresses live `updated_*_meta` fan-out in `Sync` for those writes.
     */
    private $applyingExternalSourceSync = \false;
    /**
     * Gate for `Sync::save_post()`: when `true`, Sync skips post sync for this write.
     *
     * `Sync::save_post()` calls this first and returns immediately when it is `true` — no
     * `copyPostToOtherLanguage()`, no cross-language meta/column sync on that hook. The external
     * plugin already created or is creating the translation; applies sync copy rules later via
     * `syncPostFromExternalSource()` instead.
     *
     * WPML example: `true` only while `make_duplicate` runs (`duplicateCreationInProgress`).
     * After `icl_make_duplicate`, WPML duplicate meta is removed so ongoing saves use Sync.
     *
     * @param int $postId
     */
    public function isExternalTranslationSyncDeferredForPost($postId)
    {
        return \false;
    }
    /**
     * Gate for `Sync::created_term()`: when `true`, Sync skips term sync for this write.
     *
     * `Sync::created_term()` calls this first and returns immediately when it is `true` — no
     * `copyTermToOtherLanguage()` for other languages on that hook. The external plugin already
     * inserted the translated term; applies sync copy rules later via
     * `syncTermFromExternalSource()` instead.
     *
     * WPML example: Taxonomy Translation UI (e.g. Service groups → "Copy to all languages",
     * `wp_ajax_wpml_save_term`). Skipping avoids duplicate/orphan terms in the source language.
     *
     * @param int $term_id
     * @param string $taxonomy
     */
    public function isExternalTranslationSyncDeferredForTerm($term_id, $taxonomy)
    {
        return \false;
    }
    /**
     * Whether `Sync::updated_postmeta()` / `Sync::updated_term_meta()` should skip.
     *
     * See `whileApplyingExternalSourceSync()`.
     */
    public function isApplyingExternalSourceSync()
    {
        return $this->applyingExternalSourceSync;
    }
    /**
     * Run callback while external-source sync writes the target translation only (no live meta fan-out).
     *
     * @param callable $callback
     */
    protected function whileApplyingExternalSourceSync($callback)
    {
        $this->applyingExternalSourceSync = \true;
        try {
            \call_user_func($callback);
        } finally {
            $this->applyingExternalSourceSync = \false;
        }
    }
    /**
     * Run configured post meta through `filterMetaValue()` on the target translation.
     *
     * @param int $from Source post id
     * @param int $to Target post id
     * @param string[] $metaKeys Meta keys registered for copy / copy-once sync
     * @param string $locale Target language code
     * @param boolean $readValueFromTarget When true, read meta from `$to` (WPML already copied) and update only if the filter changes the value
     */
    public function syncPostMetaFromExternalSource($from, $to, $metaKeys, $locale, $readValueFromTarget = \false)
    {
        foreach ($metaKeys as $key) {
            $readFrom = $readValueFromTarget ? $to : $from;
            if (!\metadata_exists('post', $readFrom, $key)) {
                continue;
            }
            $value = \get_post_meta($readFrom, $key, \true);
            $filtered = $this->filterMetaValue('post', $from, $to, $key, $value, $readValueFromTarget ? $locale : $this->getCurrentLanguageFallback());
            if ($readValueFromTarget) {
                if ($filtered !== $value) {
                    \update_post_meta($to, $key, $filtered);
                }
            } else {
                \update_post_meta($to, $key, $filtered);
            }
        }
    }
    /**
     * Copy configured term meta from the source translation after an external plugin created the target.
     *
     * @param int $from Source term id
     * @param int $to Target term id
     * @param string[] $metaKeys Meta keys registered for copy / copy-once sync
     * @param string $locale Target language code
     */
    public function syncTermMetaFromExternalSource($from, $to, $metaKeys, $locale)
    {
        foreach ($metaKeys as $key) {
            if (!\metadata_exists('term', $from, $key)) {
                continue;
            }
            $value = \get_term_meta($from, $key, \true);
            \update_term_meta($to, $key, $this->filterMetaValue('term', $from, $to, $key, $value, $locale));
        }
    }
    /**
     * Apply sync copy (meta + term columns) after an external plugin created the translation.
     *
     * Mirrors `duplicateTerm()`: snapshot source PO catalog via `withSourceTranslationCatalog()`,
     * switch target locale, `translateInput`.
     *
     * @param int $from Source term id
     * @param int $to Target term id
     * @param string[] $metaKeys Meta keys registered for copy / copy-once sync
     * @param string $locale Target language code
     */
    public function syncTermFromExternalSource($from, $to, $metaKeys, $locale)
    {
        $master = \get_term($from);
        if (!$master instanceof WP_Term) {
            return;
        }
        $taxonomy = $master->taxonomy;
        $sourceLocale = $this->getTermLanguage($master->term_taxonomy_id, $taxonomy);
        if ($sourceLocale === '') {
            $sourceLocale = $this->getDefaultLanguage();
        }
        $this->whileApplyingExternalSourceSync(function () use($from, $to, $metaKeys, $locale, $master, $taxonomy, $sourceLocale) {
            $columnUpdates = [];
            $this->withSourceTranslationCatalog($sourceLocale, function () use($from, $to, $metaKeys, $locale, $master, &$columnUpdates) {
                $this->switchToLanguage($locale, function () use($from, $to, $metaKeys, $locale, $master, &$columnUpdates) {
                    $this->syncTermMetaFromExternalSource($from, $to, $metaKeys, $locale);
                    if ($master->name !== '') {
                        list(, $translatedName) = $this->translateInput($master->name);
                        $columnUpdates['name'] = $translatedName;
                    }
                    if ($master->description !== '') {
                        list(, $translatedDescription) = $this->translateInput($master->description);
                        $columnUpdates['description'] = $translatedDescription;
                    }
                });
            });
            $target = \get_term($to, $taxonomy);
            if (!$target instanceof WP_Term) {
                return;
            }
            $args = [];
            foreach ($columnUpdates as $column => $value) {
                if ($value !== '' && $value !== $target->{$column}) {
                    $args[$column] = $value;
                }
            }
            if ($args !== []) {
                // Run after `switchToLanguage()` restored the previous locale — adapters switch back
                // to `$locale` for writes that must persist under the translation's language context.
                $this->switch($locale);
                \wp_update_term($to, $taxonomy, $args);
            }
        });
    }
    /**
     * Apply sync copy rules after an external plugin created a post translation: taxonomies + translated columns.
     *
     * Title/content/excerpt use the same rules as `duplicatePost()` (`translateInput()` without extra
     * contexts). Post meta is not read from the master again (large payloads). Values already on `$to`
     * from the external duplicate are passed through `filterMetaValue()` when a consumer registered
     * `DevOwl/Multilingual/Copy/Meta/post/{key}`.
     *
     * @param int $from Source post id
     * @param int $to Target post id
     * @param string $locale Target language code
     * @param string[] $taxonomies Taxonomies to remap to the target locale (same as `copyPostToOtherLanguage`)
     * @param array $postConfiguration Sync options for the post type (from `Sync::getPostsConfiguration()`)
     */
    public function syncPostFromExternalSource($from, $to, $locale, $taxonomies = [], $postConfiguration = [])
    {
        $this->whileApplyingExternalSourceSync(function () use($from, $to, $locale, $taxonomies, $postConfiguration) {
            // After `icl_make_duplicate`: assign translated service groups (Essential → Essenziell).
            // Runs once per duplicate, not per meta key — avoids the old meta re-copy path on large blobs.
            if ($taxonomies !== []) {
                $this->taxonomySourceResolutionExcludePostId = (int) $to;
                try {
                    $this->copyPostTaxonomies($from, $to, $taxonomies, $locale);
                } finally {
                    $this->taxonomySourceResolutionExcludePostId = null;
                }
            }
            $master = \get_post($from);
            if (!$master instanceof WP_Post) {
                return;
            }
            $sourceLocale = $this->getPostLanguage($from);
            if ($sourceLocale === '') {
                $sourceLocale = $this->getDefaultLanguage();
            }
            $copyOnceKeys = $postConfiguration['meta']['copy-once'] ?? [];
            $copyKeys = $postConfiguration['meta']['copy'] ?? [];
            $remapMetaKeys = [];
            $translateOnceMetaKeys = [];
            $plainMetaKeys = [];
            foreach (\array_unique(\array_merge($copyKeys, $copyOnceKeys)) as $key) {
                $hasConsumer = \has_filter('DevOwl/Multilingual/Copy/Meta/post/' . $key);
                if ($hasConsumer && \in_array($key, $copyOnceKeys, \true)) {
                    $translateOnceMetaKeys[] = $key;
                } elseif ($hasConsumer) {
                    $remapMetaKeys[] = $key;
                } else {
                    $plainMetaKeys[] = $key;
                }
            }
            // Copy-once fields (provider, …): master + PO catalog + legal-text filters — not the SQL
            // copy on `$to` (wrong locale / untranslated). Plain copy keys (presetId, …): refill when WPML
            // translation emptied them. Remap keys (services): read SQL copy on `$to`, then filter.
            $this->withSourceTranslationCatalog($sourceLocale, function () use($from, $to, $locale, $master, $translateOnceMetaKeys, $plainMetaKeys) {
                $this->switchToLanguage($locale, function () use($from, $to, $locale, $master, $translateOnceMetaKeys, $plainMetaKeys) {
                    foreach ($translateOnceMetaKeys as $key) {
                        if (!\metadata_exists('post', $from, $key)) {
                            continue;
                        }
                        $value = \get_post_meta($from, $key, \true);
                        \update_post_meta($to, $key, $this->filterMetaValue('post', $from, $to, $key, $value, $locale));
                    }
                    foreach ($plainMetaKeys as $key) {
                        if (!\metadata_exists('post', $from, $key)) {
                            continue;
                        }
                        $targetValue = \get_post_meta($to, $key, \true);
                        if (\metadata_exists('post', $to, $key) && $targetValue !== '' && $targetValue !== null) {
                            continue;
                        }
                        \update_post_meta($to, $key, \get_post_meta($from, $key, \true));
                    }
                    $update = ['ID' => $to];
                    $hasChanges = \false;
                    foreach (['post_title' => $master->post_title, 'post_content' => $master->post_content, 'post_excerpt' => $master->post_excerpt] as $column => $sourceValue) {
                        if ($sourceValue === '') {
                            continue;
                        }
                        $translated = $this->translateInput($sourceValue)[1];
                        $existing = \get_post_field($column, $to);
                        // Skip `wp_update_post` when WPML already wrote the same value — duplicate
                        // requests should not reload huge `post_content` twice (memory spike on TM batch).
                        if (\is_string($existing) && $translated !== $existing) {
                            $update[$column] = $translated;
                            $hasChanges = \true;
                        }
                    }
                    if ($hasChanges) {
                        \wp_update_post($update);
                    }
                });
            });
            if ($remapMetaKeys !== []) {
                $this->syncPostMetaFromExternalSource($from, $to, $remapMetaKeys, $locale, \true);
            }
        });
    }
}
