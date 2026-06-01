# WPML × Real Cookie Banner — regression repro

Location: `wordpress-packages/multilingual/.ai/references/wpml-rcb-regression-repro.md`

Target stack: https://wordpress.ci-runner-16.owlsrv.de

**Fastest entry (known stack, duplicate only):** [ci-runner-16 — TM duplicate-only speed path](#ci-runner-16--tm-duplicate-only-speed-path-3-min).

**Purpose / title asymmetry (WPML duplicate mirror):** [Regression — Purpose (`post_content`)](#regression--purpose-post_content-wpml-duplicate-mirror-vs-devowl-sync) — always run [Script A](#script-a--clean-state-30s) before reproducing.

This file documents four validated repro paths:

1. **Services (rcb-cookie)** — WPML duplicate empties `technicalDefinitions`; RCB list crashes;
   translated services must keep `rcb-cookie-group` assignment (see Phase C).
2. **Content blockers (rcb-blocker)** — WPML duplicate must remap `meta.services` to the DE cookie id;
   verify in REST and in the RCB edit UI ("Connected services").
3. **Service groups (rcb-cookie-group)** — WPML taxonomy translation "Copy to all languages" must not
   spawn duplicate terms in the source language; target-language names must be localized (not German on EN).
4. **Purpose / title (`post_content`, `post_title`)** — while `_icl_lang_duplicate_of` is set, WPML mirrors
   master edits to duplicates but not duplicate edits back to the master; DevOwl `Sync::save_post` is
   skipped on duplicate posts (see regression section). **Product rule:** purpose and title are
   **copy-once at duplication only** (per-language legal text), not kept in ongoing bidirectional sync.
5. **DE → EN duplicate — empty `rcb-cookie-group`** — after reset, delete the EN “Real Cookie Banner”
   (trash + permanent delete), duplicate the **German** source cookie to English; the new EN service has
   **no** service-group assignment in DB and in `post.php?post=<id>&action=edit` (see
   [Regression — DE → EN empty groups](#regression--de--en-duplicate-empty-service-groups)).
6. **Gravatar blocker — wrong connected service after TM duplicate** — reset → scanner Gravatar → create
   service + blocker → delete blocker in **one** language → TM duplicate blocker; DE duplicate keeps EN
   cookie id in `meta.services` (see
   [Regression — Gravatar blocker services meta](#regression--gravatar-blocker-wrong-connected-service)).

PHP warnings about decimal_point / thousands_sep on `?lang=de` admin URLs are noise — ignore them.

Second validated run (2026-05-20, cookies): ~5 min with batched `browser_run_code_unsafe`.
Blocker path (same day): ~8 min including cookie duplicate + UI create + blocker duplicate + DE edit UI.

---

## Prerequisites (once per machine / Playwright MCP session)

1. Playwright MCP needs Google Chrome on the host:
   npx playwright@latest install chrome
   Error if missing: Chromium distribution 'chrome' is not found at /opt/google/chrome/chrome

2. Traefik Basic Auth (all _.ci-runner-_.owlsrv.de hosts):
   Username: local
   Password: TRAEFIK_BASIC_AUTH_PASSWORD from dowl workspace/exposedotenv env
   (Same value as documented for ci-runner-14 in checkout parity docs if env export is blocked.)

    Embed credentials on the FIRST navigation only (Chromium caches per origin):
    https://local:<password>@wordpress.ci-runner-16.owlsrv.de/...

3. WordPress admin (CI container default install):
   User: wordpress
   Password: wordpress
   Only needed if session is fresh and Basic Auth URL did not auto-login to wp-admin.

4. Isolated Playwright MCP session = no persisted cookies; re-login each session if Basic Auth
   was not embedded in the first URL.

---

## ci-runner-16 — TM duplicate-only speed path (~3 min)

Use when the stack already has a single EN master cookie and you only need to re-run **EN → DE
Duplicate** (memory-exhausted repro).

**Always run Script A (A1 reset + A2 DE delete) before TM duplicate** — do not skip reset on
ci-runner-16. A bare `wp post delete` on the DE cookie leaves the EN master without
`rcb-cookie-group` (stale stack / manual edits). Reset restores default content so the EN
"Real Cookie Banner" is assigned to **Essential**; only then does Phase C (`rcb-cookie-group` on
the DE duplicate) mean anything.

### Pre-flight (shell — after Script A only)

Use these checks **after** Script A, not instead of it:

WordPress container name drifts; discover with:

```bash
docker ps --filter name=wordpress --format '{{.Names}}'
# example: devowl-wp_wordpress.1.grypv5gwwizc14ay1fif1jiqq
```

Inside the container (`wp … --allow-root`):

```bash
# Exactly one EN master — note id N for checkbox #rcb-cookie{N}
wp post list --post_type=rcb-cookie --format=table --fields=ID,post_title,post_status

# DE must be empty before duplicate (list can be empty with no header row)
wp post list --post_type=rcb-cookie --lang=de --format=table --fields=ID,post_title,post_status

# Fast DE removal if trash UI clicks fail (replace <id>)
wp post delete <de-post-id> --force
```

Validated snapshot (2026-05-21): EN master **459** (`Real Cookie Banner`), checkbox
**`#rcb-cookie459`**, no `rcb-cookie` rows in `lang=de` after delete.

**2026-05-21 (reset mandatory):** After Script A (`Reset::all()` + default content), EN master
**494** has group **[575]** (Essential). After DE delete + `make_duplicate(494, 'de')` → DE **497**:

- **DB** (`wp_term_relationships`): **[576]** (`Essenziell`) — correct DE term.
- **REST / `wp_get_object_terms`**: **[575]** (EN term id) — Phase C **fails** (WPML read fallback).
- Admin filter `lang=de&rcb-cookie-group=576`: sidebar shows **Essenziell (1)** but the cookie
  table row can still be empty (REST-backed list).

Skipping reset left EN with **no** group and masked the taxonomy issue entirely.

---

## Regression — DE → EN duplicate empty service groups

**Symptom (user report, ci-runner-16):** After reset, delete the English “Real Cookie Banner”
(including trash), then **Duplicate** the German source cookie to English in the Translation Dashboard,
the new EN translation opens with **no service group** — e.g.
`https://wordpress.ci-runner-16.owlsrv.de/wp-admin/post.php?post=903&action=edit` (ids drift every reset).

**Inverse of the EN → DE path** in [Script A](#script-a--clean-state-30s) / [Script B](#script-b--wpml-duplicate-45s):
there the DE duplicate should get **Essenziell**; here the **EN** duplicate should get **Essential** but ends
with **none**.

### Root cause (validated 2026-05-22)

| Layer                         | What happens                                                                                                                                                                                                                                                                        |
| ----------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Reset                         | `Reset::all()` seeds one EN master cookie assigned to the EN group term **Essential** (`term_id` drifts). `copyAll` does not always create linked DE group terms on this stack — only EN `rcb-cookie-group` rows may exist.                                                         |
| EN delete                     | Trash + **Delete permanently** on `edit.php?post_type=rcb-cookie&lang=en` removes only the EN post; the DE translation post remains (create it first with one EN → DE duplicate if the stack only has an EN master after reset).                                                    |
| DE source cookie              | Still carries the **EN** group term id on `wp_term_relationships` (e.g. Essential **812** on DE post **935**).                                                                                                                                                                      |
| `icl_make_duplicate` + DevOwl | `copyPostTaxonomies()` → `assignTranslatedPostTerms()` calls `getCurrentTermId(812, 'rcb-cookie-group', 'en')` → **812** again. Guard `resolvedTermId !== sourceTermId` fails → **no** ids to assign → `wp_set_object_terms($enPost, [], …)` **clears** groups on the EN duplicate. |

Reference: `assignTranslatedPostTerms()` in
`wordpress-packages/multilingual/src/AbstractSyncPlugin.php` (skip when resolved id equals source id).

### Script A′ — Clean state + drop EN cookie (~2 min UI)

**A′1 — Reset (RCB UI, same as Script A)**

1. `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/settings/reset`
2. Tab **Reset** → **Reset** → confirm **Reset all** (wait ~4s for default content).

CLI equivalent (only when the React reset UI is blocked — requires admin context):

```bash
wp eval 'wp_set_current_user(1); if (!defined("WP_ADMIN")) define("WP_ADMIN", true); DevOwl\RealCookieBanner\settings\Reset::getInstance()->all(false);' --allow-root
sleep 2
wp post list --post_type=rcb-cookie --format=table --fields=ID,post_title,post_status --allow-root
```

Expect **one** published “Real Cookie Banner” (EN master after reset on this stack).

**A′2 — Ensure a DE source exists (if the stack only has EN)**

If `wp post list --post_type=rcb-cookie --lang=de` is empty, run **one** EN → DE duplicate first (see
[Script B](#script-b--wpml-duplicate-45s) with `lang=en`, target German), then continue with A′3.

**A′3 — Delete EN cookie (trash + permanent; do not use `wp_delete_post` on the EN master)**

`wp_delete_post` on the EN original can remove the whole translation group. Use the EN admin list:

1. `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/edit.php?post_type=rcb-cookie&lang=en`
2. **Move “Real Cookie Banner” to the Trash** (follow the trash `href` if the row is off-screen).
3. `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/edit.php?post_status=trash&post_type=rcb-cookie&lang=en`
4. **Delete “Real Cookie Banner” permanently** (follow the delete `href`).

**Phase A′ pre-flight**

```bash
# DE-only source (replace <de-id>)
wp post list --post_type=rcb-cookie --lang=de --format=table --fields=ID,post_title,post_status --allow-root
wp eval 'var_dump(wp_get_object_terms(<de-id>, "rcb-cookie-group", ["fields"=>"ids"]));' --allow-root
# Expect non-empty (often EN term ids, e.g. [812]) — that is the bug trigger
wp post list --post_type=rcb-cookie --lang=en --format=table --fields=ID,post_title --allow-root
# Expect empty
```

Validated snapshot (2026-05-22): DE source **935**, EN master deleted; DE groups **`[812]`** (EN Essential term).

### Script B′ — TM Duplicate DE → EN (~1 min UI)

**URL:** `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=de&admin_bar=1`

| Step | Action                                                                                                                                                                           |
| ---- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1    | **Source language:** German (toolbar / `Source Language` = German).                                                                                                              |
| 2    | **Translated to:** English.                                                                                                                                                      |
| 3    | **Filter by translation status:** All translation statuses → click **`button.filter-button`** (not the generic Filter role).                                                     |
| 4    | **Cookies** section: **Expand** if collapsed.                                                                                                                                    |
| 5    | Select **exactly one** row: checkbox `#rcb-cookie<de-id>` (e.g. `#rcb-cookie935`) → wait for **“1 Cookie selected”**. Do **not** use top **Select All** (pulls in banner links). |
| 6    | Step 2: **English → Duplicate** → button **Duplicate** (exact).                                                                                                                  |
| 7    | Wait for **“has been duplicated”** (up to ~120s).                                                                                                                                |

Playwright twin of Script B (swap languages):

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    const dePosts = await page.evaluate(async () => {
        const r = await fetch("/wp-json/wp/v2/rcb-cookie?per_page=100&lang=de", { credentials: "include" });
        return await r.json();
    });
    const deId = dePosts.find((p) => p.title?.rendered === "Real Cookie Banner")?.id ?? dePosts[0]?.id;
    const checkboxId = `rcb-cookie${deId}`;
    await page.goto(`${base}/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=de&admin_bar=1`);
    await page.getByLabel("translated to:").selectOption("English");
    await page.getByLabel("Filter by translation status").selectOption("All translation statuses");
    await page.locator("button.filter-button").click();
    await page.waitForTimeout(2000);
    const section = page.getByTestId("post/rcb-cookie-section");
    if (await section.getByRole("button", { name: "Expand" }).count()) {
        await section.getByRole("button", { name: "Expand" }).click();
    }
    await page.locator(`#${checkboxId}`).evaluate((el) => {
        el.checked = true;
        el.dispatchEvent(new Event("change", { bubbles: true }));
    });
    await page.getByText("1 Cookie selected").waitFor({ timeout: 15000 });
    await page.getByTestId("translation-method-en").selectOption(["Duplicate"]);
    await page.getByRole("button", { name: "Duplicate", exact: true }).click();
    await page.getByText("has been duplicated").first().waitFor({ timeout: 120000 });
    return { deId, checkboxId };
};
```

### Phase C′ — Confirm failure (empty groups on EN duplicate)

Discover the new EN post id:

```bash
wp post list --post_type=rcb-cookie --lang=en --format=table --fields=ID,post_title,post_status --allow-root
```

**DB (authoritative — expect zero rows):**

```bash
wp eval '
global $wpdb;
$en = <en-dup-id>;
$n = $wpdb->get_var($wpdb->prepare(
  "SELECT COUNT(*) FROM {$wpdb->term_relationships} tr
   JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
   WHERE tr.object_id = %d AND tt.taxonomy = %s",
  $en,
  "rcb-cookie-group"
));
echo "rel_count=$n\n";
var_dump(wp_get_object_terms($en, "rcb-cookie-group", ["fields" => "ids"]));
' --allow-root
```

**Pass criteria for this regression (bug):** `rel_count=0` and `array(0) {}` — reproduces the bug.

**Pass criteria after fix:** `rel_count > 0`, EN duplicate has Essential (same term id as DE source when only EN group terms exist), REST `rcb-cookie-group` non-empty.

Validated run (2026-05-22, bug): `make_duplicate(935, 'en')` → EN **940**, `rel_count=0`.

CLI harness (local / container):

```bash
wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-phase-c-de-en-verify.php --allow-root
```

Expect JSON `"pass": { "all": true }` and `relCount` ≥ 1.

**UI:** `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/post.php?post=<en-dup-id>&action=edit` — RCB
service editor shows **no** service group (same as user post **903** on their run).

**Note:** If orphan DE group terms were created during earlier experiments (`821`, …), `rel_count` may be
\> 0 even though the bug still occurs in production-like reset-only stacks. Re-run Script A′ on a clean
reset before trusting Phase C′.

---

## Regression — Gravatar blocker wrong connected service

**Symptom:** After reset, scanner-driven **Gravatar** service + content blocker, delete the blocker in
**one** language (e.g. German), then **Translation Dashboard → Duplicate** the remaining-language
blocker — the new translation’s edit UI shows the **wrong** connected service (often “Real Cookie Banner”
or the EN Gravatar cookie instead of the DE Gravatar cookie).

**UI path (ci-runner-16):**

1. `#/settings/reset` → Reset all (or CLI `Reset::all()`).
2. `#/scanner` — wait until **Gravatar** appears; use **Create now** for service + blocker.
3. `#/blocker` — switch WP admin language to **German** → delete the Gravatar blocker row.
4. TM `lang=en` → **Content Blockers** section → duplicate to German (Script **CB-D** in this file).
5. `#/blocker/edit/<de-blocker-id>?lang=de` — **Connected services** tag must be **Gravatar** (DE), not EN
   default cookie.

**Authoritative check (REST / CLI):**

```bash
wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-blocker-gravatar-repro.php --allow-root
```

**Bug:** `servicesOnDeBlockerDup` === EN Gravatar cookie id (e.g. `1007`) while `deGravatarCookieId` is
`1008`. **Pass after fix:** `servicesOnDeBlockerDup` === `deGravatarCookieId`.

**Root cause (2026-05-22):** `icl_make_duplicate` SQL-copies `meta.services` unchanged. `syncPostFromExternalSource()`
remaps taxonomies and title/content only — **not** post meta. `Hooks::copy_blocker_connected_services_meta()`
runs only on `DevOwl/Multilingual/Copy/Meta/post/services` (DevOwl `filterMetaValue` path), so TM duplicate
never remaps cookie ids to the target language.

**Cursor browser note:** RCB admin on ci-runner-16 often shows REST API / adblocker errors; scanner may still
list Gravatar in notices. Use CLI harness above when the React UI cannot save.

---

### Reproduction findings (2026-05-21, reset → duplicate)

| Layer                                               | Symptom                                                                                                        | Root cause                                                                                                                                                    | Fix (multilingual package)                                                                                                                    |
| --------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| Pre-flight                                          | EN master without `rcb-cookie-group` after ad-hoc `wp post delete`                                             | Stale stack; default content not re-seeded                                                                                                                    | **Always Script A** (`Reset::all()` + A2) before TM duplicate                                                                                 |
| `edit-tags.php?taxonomy=rcb-cookie-group&lang=de`   | Duplicate group rows (8+ instead of 4)                                                                         | `CopyContent::copyAll()` calls `created_term` on existing EN terms; `copyTermToOtherLanguage` created a second DE term when WPML already linked a translation | Skip copy when `getTaxonomyTranslationIds()` already has target locale                                                                        |
| `wp_term_relationships` (DE cookie)                 | Term **576** (`Essenziell`) stored correctly                                                                   | `copyPostTaxonomies()` + `assignTranslatedPostTerms()` OK when EN master has Essential                                                                        | No change needed on write path                                                                                                                |
| REST `rcb-cookie-group` on DE cookie                | Returns EN term id **575**                                                                                     | `wp_get_object_terms` under wrong WPML language; `icl_object_id(..., true)` fallback on terms                                                                 | `getObjectTermIdsForPostLanguage()` + REST field remap; `icl_object_id(..., false)`; callers skip cross-language source-term fallbacks inline |
| DE cookie `post_content`                            | English master string after duplicate                                                                          | `syncPostFromExternalSource()` must run after `icl_make_duplicate` (taxonomies + `translateInput`)                                                            | Already in trait; verify after deploy with Phase C content check                                                                              |
| Admin filter **Essenziell**                         | Sidebar count vs empty table                                                                                   | List table REST used wrong term ids                                                                                                                           | Fixed with REST field remap above                                                                                                             |
| Purpose EN→DE, not DE→EN (while duplicate meta set) | WPML `sync_with_duplicates` on master save; `isExternalTranslationSyncDeferredForPost` blocked DevOwl on slave | Detach duplicate meta after `icl_make_duplicate`; narrow defer to creation window only (`WPML.php`)                                                           | [Purpose regression](#regression--purpose-post_content-wpml-duplicate-mirror-vs-devowl-sync)                                                  |

**Authoritative Phase C commands after Script B** (replace ids from pre-flight):

```bash
# EN master must have Essential (non-empty group ids)
wp eval 'var_dump(wp_get_object_terms(<en-id>, "rcb-cookie-group", ["fields"=>"ids"]));' --allow-root

# DE duplicate: REST must list DE term ids only (e.g. 576 not 575)
wp eval '
$req = new WP_REST_Request("GET", "/wp/v2/rcb-cookie/<de-id>");
$req->set_param("lang", "de");
$d = rest_do_request($req)->get_data();
echo json_encode($d["rcb-cookie-group"])."\n";
echo substr(strip_tags($d["content"]["rendered"] ?? ""), 0, 60)."\n";
' --allow-root

# Exactly four DE groups, no duplicate names
wp eval '
$t = get_terms(["taxonomy"=>"rcb-cookie-group","hide_empty"=>false,"lang"=>"de"]);
echo count($t).": ".implode(", ", wp_list_pluck($t, "name"))."\n";
' --allow-root
```

Pass: EN groups non-empty; DE REST groups ⊆ DE group catalogue and ≠ EN ids; DE content German lead-in; `count === 4` and no duplicate names on `edit-tags.php?taxonomy=rcb-cookie-group&lang=de`.

**Automated gate (reset → duplicate → Phase C):**

```bash
wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-phase-c-verify.php --allow-root
# exit 0 + pass.all true
```

Note: `Reset::all()` already calls `addInitialContent()` — do not invoke `addInitialContent()` again in the same request (triggers `copyAll` without re-seeding the EN cookie).

### URLs (copy-paste)

| Step                      | URL                                                                                                           |
| ------------------------- | ------------------------------------------------------------------------------------------------------------- |
| DE list (should be empty) | `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/edit.php?post_status=publish&post_type=rcb-cookie&lang=de` |
| TM duplicate              | `https://wordpress.ci-runner-16.owlsrv.de/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1`   |

Admin URL **must** keep `lang=en` on the TM page (source language), not `lang=cs` — the cookie
row is often missing when the dashboard opens in Czech.

### TM checklist (memory repro)

1. **Filters:** Source **English**, translated to **German**, translation status **All translation
   statuses** → `button.filter-button` (not role `Filter`).
2. **Cookies** section: **Expand** if collapsed (`getByTestId('post/rcb-cookie-section')`).
3. **Select exactly one row:** `#rcb-cookie{N}` via Script B (`evaluate` + `label.wpml-checkbox`
   force click) → wait **"1 Cookie selected"**.
    - **Do not** use the top **Select All** control: it also checks **Banner links** (Privacy
      Policy, Legal notice) and widens the batch sent to `send-to-translation`.
4. Open Step 2 (**Translate your content**). Set **Czech → Do nothing** when you only duplicate
   to German (avoids extra validation noise).
5. **German → Duplicate** → `getByRole('button', { name: 'Duplicate', exact: true })`.
6. **Success UI:** `has been duplicated` (up to ~120s). **Memory failure UI:** modal
   _Sending for translation failed_ — still check logs (HTTP may be 200).

### Confirm memory fatal (authoritative)

```bash
docker logs <wordpress-container> 2>&1 | grep -iE 'memory|Fatal|exhausted' | tail -10
```

Typical lines: `Allowed memory size of 134217728 bytes` (128M) or `268435456` (256M) in
`wp-includes/functions.php` / Query Monitor. Older stack fatals often show referer
`…/main.php&lang=cs` — filter log tail for `lang=en` when reproducing the EN-source path.

Network order on submit (browser devtools / MCP `browser_network_requests`):

1. `POST /wp-json/wpml/v1/send-to-translation/validate-batch-name` → 200
2. `POST /wp-json/wpml/v1/send-to-translation/validate-translation-options` → 200
3. `POST /wp-admin/admin-ajax.php?action=wpml_api_wpml_v1_send-to-translation` → 200 (UI can
   still show failure if PHP died mid-request or the JSON payload reports an error)

Post-check: `wp post list --post_type=rcb-cookie --lang=de` — new DE row only after a successful
duplicate.

### Cursor IDE browser (when Playwright MCP is unavailable)

Prefer **Script B** in `browser_run_code_unsafe` for the checkbox. If you must use
`cursor-ide-browser`:

- `browser_click` on the cookie checkbox often fails (**click intercepted** by a decorative
  `<span>` in the row). Workarounds: Playwright script B; or `browser_search` **"1 Cookie
  selected"** after a successful select; or coordinate click on the checkbox column after
  `browser_take_screenshot` + `browser_mouse_click_xy` (fragile).
- Step 2 **Duplicate** button may be below the fold: `browser_search` query **Duplicate**,
  `navigateToMatch` last match, then `browser_click` on snapshot ref `button` name **Duplicate**
  (exact).
- **Do not** rely on `has been duplicated` alone when debugging memory — open **See technical
  details** only if the failure is unknown; ignore unrelated WPML TypeError noise when the batch
  included non-cookie items.

---

## ID cheat sheet (IDs drift every reset — always re-discover via REST)

| What                                   | Run 1 (first reproduce) | Run 2 (rules-based) | How to discover fast                          |
| -------------------------------------- | ----------------------- | ------------------- | --------------------------------------------- |
| Original EN rcb-cookie post            | ~45                     | 53                  | REST: tech len > 100                          |
| Original EN (ci-runner-16, 2026-05-21) | 459                     | —                   | `wp post list --post_type=rcb-cookie`         |
| WPML-duplicated EN post (broken)       | ~48                     | 56                  | REST: meta.technicalDefinitions === ""        |
| WPML checkbox input id                 | rcb-cookie45            | rcb-cookie53        | #rcb-cookie + original post id                |
| WPML checkbox (2026-05-21)             | rcb-cookie459           | —                   | `#rcb-cookie` + EN master id                  |
| Essential cookie GROUP key             | ~25                     | 25                  | RCB notice link #/cookies/25/edit/<postId>    |
| User doc example group                 | 17                      | —                   | Environment-specific; never assume            |
| EN rcb-cookie (blocker repro)          | —                       | 142                 | REST `lang=en` after reset + cookie duplicate |
| DE rcb-cookie (blocker repro)          | —                       | 143                 | REST `lang=de`                                |
| EN rcb-blocker                         | —                       | 144                 | REST `lang=en` after UI create                |
| DE rcb-blocker                         | —                       | 145                 | REST `lang=de` after WPML duplicate           |
| WPML blocker checkbox id               | —                       | rcb-blocker144      | #rcb-blocker + EN blocker post id             |

RCB hash routes (real-cookie-banner configApp.tsx):
#/cookies/:cookieGroup → list (ListServiceRow crash)
#/cookies/:cookieGroup/edit/:cookie → edit form (usually loads)
#/cookies/:postId → WRONG segment (post id ≠ group id)
#/cookies/:postId/edit → WRONG (React Router 404 / ErrorBoundary)
#/blocker/new → template center (do not stop here for scratch create)
#/blocker/new?force=scratch → scratch create form (BlockerEditForm)
#/blocker/edit/:blockerPostId → edit form; use `lang=de` query for DE admin UI
#/blocker → list after save

---

## Fastest path — one Playwright run_code_unsafe per phase

Use browser_run_code_unsafe unless a step truly needs snapshot/refs. Host:
https://wordpress.ci-runner-16.owlsrv.de (Basic Auth already cached after first URL).

### Script A — Clean state (~30s)

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    // A1 Reset
    await page.goto(`${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/settings/reset`);
    await page.getByText("Reset all").first().waitFor({ state: "visible", timeout: 30000 });
    await page.getByRole("button", { name: "Reset" }).nth(1).click();
    await page
        .getByRole("button", { name: "Reset all" })
        .click({ timeout: 15000 })
        .catch(() => {});
    await page.waitForTimeout(4000);
    // A2 DE delete — direct href only (no viewport clicks)
    await page.goto(`${base}/wp-admin/edit.php?post_type=rcb-cookie&lang=de`);
    const trash = page.getByRole("link", { name: /Move .*Real Cookie Banner.* to the Trash/i });
    if (await trash.count()) {
        const href = await trash.getAttribute("href");
        await page.goto(href.startsWith("http") ? href : `${base}/wp-admin/${href.replace(/^\//, "")}`);
        await page.waitForTimeout(1500);
    }
    await page.goto(`${base}/wp-admin/edit.php?post_status=trash&post_type=rcb-cookie&lang=de`);
    const del = page.getByRole("link", { name: /Delete .*Real Cookie Banner.* permanently/i });
    if (await del.count()) {
        const href = await del.getAttribute("href");
        await page.goto(href.startsWith("http") ? href : `${base}/wp-admin/${href.replace(/^\//, "")}`);
        await page.waitForTimeout(1500);
    }
    const en = await page.evaluate(async () => {
        const r = await fetch("/wp-json/wp/v2/rcb-cookie?per_page=100&lang=en", { credentials: "include" });
        return (await r.json()).map((p) => p.id);
    });
    return { step: "A done", enPostIds: en };
};
```

After A: exactly one EN post (e.g. [53]).

### Script B — WPML duplicate (~45s)

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    const posts = await page.evaluate(async () => {
        const r = await fetch("/wp-json/wp/v2/rcb-cookie?per_page=100&lang=en", { credentials: "include" });
        return await r.json();
    });
    const originalId = posts[0].id; // only one post after clean state
    const checkboxId = `rcb-cookie${originalId}`;

    await page.goto(`${base}/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1`);
    const section = page.getByTestId("post/rcb-cookie-section");
    if (await section.getByRole("button", { name: "Expand" }).count()) {
        await section.getByRole("button", { name: "Expand" }).click();
    }
    await page.getByLabel("translated to:").selectOption("German");
    // CRITICAL: "Not completed" often hides the only cookie row after reset
    await page.getByLabel("Filter by translation status").selectOption("All translation statuses");
    await page.locator("button.filter-button").click(); // NOT getByRole('Filter') — clashes with Clear filters
    await page.waitForTimeout(2000);

    await page.locator(`#${checkboxId}`).waitFor({ state: "visible", timeout: 30000 });
    await page.locator(`#${checkboxId}`).evaluate((el) => {
        el.checked = true;
        el.dispatchEvent(new Event("change", { bubbles: true }));
    });
    await page
        .locator("label.wpml-checkbox")
        .filter({ has: page.locator(`#${checkboxId}`) })
        .click({ force: true });
    await page.getByText("1 Cookie selected").waitFor({ timeout: 15000 });

    // Optional when Czech row is visible: avoid "Please choose" validation noise
    const csMethod = page.getByTestId("translation-method-cs");
    if (await csMethod.count()) {
        await csMethod.selectOption(["do_nothing"]);
    }
    await page.getByTestId("translation-method-de").selectOption(["Duplicate"]);
    await page.getByRole("button", { name: "Duplicate", exact: true }).click();
    await page.getByText("has been duplicated").first().waitFor({ timeout: 120000 });

    return { step: "B done", originalId, checkboxId };
};
```

### Script C+D — Verify data + UI (~20s)

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    const api = base;
    const enPosts = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=en`, {
            credentials: "include",
        });
        const data = await r.json();
        return data.map((p) => ({
            id: p.id,
            title: p.title?.rendered,
            techLen: String(p.meta?.technicalDefinitions ?? "").length,
            groups: p.rcb_cookie_group ?? [],
        }));
    }, api);
    const dePosts = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=de`, {
            credentials: "include",
        });
        const data = await r.json();
        return data.map((p) => ({
            id: p.id,
            title: p.title?.rendered,
            techLen: String(p.meta?.technicalDefinitions ?? "").length,
            groups: p.rcb_cookie_group ?? [],
        }));
    }, api);
    const deGroupTermIds = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie-group?lang=de&per_page=100`, {
            credentials: "include",
        });
        return (await r.json()).map((t) => t.id);
    }, api);

    const broken = enPosts.filter((p) => p.techLen === 0);
    const good = enPosts.filter((p) => p.techLen > 10);
    const deWithGroup = dePosts.filter(
        (p) => p.groups.length > 0 && p.groups.every((id) => deGroupTermIds.includes(id)),
    );
    const groupId = "25"; // stable on ci-runner-16; else parse from RCB #/cookies/25/edit/… links

    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));
    page.on("console", (m) => {
        if (m.type() === "error" && /reading 'type'/.test(m.text())) errors.push(m.text().slice(0, 80));
    });
    await page.goto(
        `${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/cookies/${groupId}`,
        {
            waitUntil: "domcontentloaded",
        },
    );
    await page.waitForTimeout(12000);

    const body = await page.locator("body").innerText();
    return {
        phaseC: {
            enPosts,
            dePosts,
            deWithGroup,
            broken,
            good,
            techPass: broken.length === 0 && good.length >= 1,
            groupPass: dePosts.length >= 1 && deWithGroup.length === dePosts.length,
            pass:
                broken.length === 0 && good.length >= 1 && dePosts.length >= 1 && deWithGroup.length === dePosts.length,
        },
        phaseD: {
            consoleErrors: errors,
            visibleError: body.includes("Cannot read properties of undefined (reading 'type')"),
            pass: errors.some((e) => e.includes("reading 'type'")) || body.includes("Unexpected Application Error"),
        },
        editUrl: `#/cookies/${groupId}/edit/${broken[0]?.id}`,
    };
};
```

Pass criteria:

- phaseC.techPass === true — EN master keeps non-empty `meta.technicalDefinitions` (no empty string duplicate on EN).
- phaseC.groupPass === true — every DE cookie from Script B has at least one `rcb-cookie-group` term id that exists in `GET …/rcb-cookie-group?lang=de` (translated group, not the EN term id).
- phaseC.pass === true — both of the above.
- phaseD.pass === true (console ListServiceRow error on FIRST load; visible error boundary is optional)

---

# Content blocker reproduce — connected services after WPML duplicate

Symptom (when broken): DE content blocker still has `meta.services` pointing at the EN
`rcb-cookie` post id. RCB admin may show wrong/missing service in "Connected services" or
`nonExistingServices` warnings.

**Order is load-bearing:** duplicate the default cookie EN → DE **before** creating the blocker
and duplicating the blocker. The repair hook maps comma-separated cookie ids via
`getCurrentPostId($id, 'rcb-cookie', $targetLocale)` only when the DE translation already exists.

Validated run (2026-05-20, ci-runner-16): cookie EN 142 → DE 143; blocker EN 144 (`services=142`)
→ DE 145 (`services=143`); DE edit UI tag "Real Cookie Banner".

---

## Blocker — Playwright scripts (run after cookie Script A + B, or full clean below)

First navigation may use Basic Auth URL; **subsequent** `page.goto` must use the host **without**
`user:pass@` — otherwise `page.evaluate(() => fetch('/wp-json/…'))` throws
"Request cannot be constructed from a URL that includes credentials".

Use absolute API base inside `evaluate` when needed:
`const api = 'https://wordpress.ci-runner-16.owlsrv.de';`
`fetch(\`${api}/wp-json/wp/v2/rcb-blocker?lang=de\`, { credentials: 'include' })`

### Script CB-A — Extend clean state (blockers) (~15s)

Run cookie Script A first, then optionally remove DE blockers the same way as cookies:

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    await page.goto(`${base}/wp-admin/edit.php?post_type=rcb-blocker&lang=de`);
    const trash = page.getByRole("link", { name: /Move .* to the Trash/i });
    if (await trash.count()) {
        const href = await trash.first().getAttribute("href");
        await page.goto(href.startsWith("http") ? href : `${base}/wp-admin/${href.replace(/^\//, "")}`);
        await page.waitForTimeout(1500);
    }
    await page.goto(`${base}/wp-admin/edit.php?post_status=trash&post_type=rcb-blocker&lang=de`);
    const del = page.getByRole("link", { name: /Delete .* permanently/i });
    if (await del.count()) {
        const href = await del.first().getAttribute("href");
        await page.goto(href.startsWith("http") ? href : `${base}/wp-admin/${href.replace(/^\//, "")}`);
        await page.waitForTimeout(1500);
    }
    return { step: "CB-A done" };
};
```

### Script CB-B — WPML duplicate cookie (~45s)

Same as **Script B** above (required before blocker create).

### Script CB-C — Create EN blocker in RCB UI (~60s)

```javascript
async (page) => {
    const host = "https://wordpress.ci-runner-16.owlsrv.de";
    await page.goto(
        `${host}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/blocker/new?force=scratch`,
    );
    await page.waitForTimeout(6000);

    await page
        .locator(".rcb-antd-form-item")
        .filter({ hasText: /^Name/ })
        .locator("input")
        .first()
        .fill("RCB Service Blocker Test");
    await page
        .locator(".rcb-antd-form-item")
        .filter({ hasText: "URLs / Elements to block" })
        .locator('textarea, input[type="text"]')
        .first()
        .fill('iframe[src*="youtube.com"]');

    const combobox = page
        .locator(".rcb-antd-form-item")
        .filter({ hasText: "Connected services" })
        .getByRole("combobox");
    await combobox.click();
    await page.waitForTimeout(500);
    await combobox.fill("Real Cookie Banner");
    await page.waitForTimeout(1500);
    // Keyboard Enter alone often leaves antd form invalid — programmatic click on active option:
    await page.evaluate(() => {
        const opt = document.querySelector(
            ".rcb-antd-select-dropdown:not(.rcb-antd-select-dropdown-hidden) .rcb-antd-select-item-option-active",
        );
        if (opt) {
            opt.dispatchEvent(new MouseEvent("mousedown", { bubbles: true }));
            opt.click();
        }
    });
    await page.waitForTimeout(1000);
    const tags = await page.locator(".rcb-antd-select-selection-item").allTextContents();
    if (!tags.length) return { step: "CB-C failed", reason: "no service tag after select" };

    await page.getByRole("button", { name: "Save" }).click();
    await page.waitForTimeout(10000);

    const api = "https://wordpress.ci-runner-16.owlsrv.de";
    const data = await page.evaluate(async (apiUrl) => {
        const en = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=en&per_page=20`, { credentials: "include" })
        ).json();
        const cookies = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?lang=en&per_page=20`, { credentials: "include" })
        ).json();
        return {
            blockers: en.map((b) => ({ id: b.id, services: b.meta?.services, criteria: b.meta?.criteria })),
            cookieEnId: cookies[0]?.id,
        };
    }, api);

    const blocker = data.blockers[data.blockers.length - 1];
    return {
        step: "CB-C done",
        hash: page.url().split("#")[1],
        tags,
        blocker,
        servicesMapsToEnCookie: blocker && String(blocker.services) === String(data.cookieEnId),
    };
};
```

After CB-C: one EN `rcb-blocker`; `meta.services` equals EN cookie id; hash often `#/blocker`.

### Script CB-D — WPML duplicate blocker (~45s)

```javascript
async (page) => {
    const host = "https://wordpress.ci-runner-16.owlsrv.de";
    const api = "https://wordpress.ci-runner-16.owlsrv.de";

    const enBlockers = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=en&per_page=20`, { credentials: "include" });
        return await r.json();
    }, api);
    const blockerEnId = enBlockers[enBlockers.length - 1].id;
    const checkboxId = `rcb-blocker${blockerEnId}`;

    await page.goto(`${host}/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1`);
    await page.waitForTimeout(2000);

    const section = page.getByTestId("post/rcb-blocker-section");
    if (await section.getByRole("button", { name: "Expand" }).count()) {
        await section.getByRole("button", { name: "Expand" }).click();
    }
    await page.getByLabel("translated to:").selectOption("German");
    await page.getByLabel("Filter by translation status").selectOption("All translation statuses");
    await page.locator("button.filter-button").click();
    await page.waitForTimeout(2500);

    await page.locator(`#${checkboxId}`).waitFor({ state: "visible", timeout: 30000 });
    await page.locator(`#${checkboxId}`).evaluate((el) => {
        el.checked = true;
        el.dispatchEvent(new Event("change", { bubbles: true }));
    });
    await page
        .locator("label.wpml-checkbox")
        .filter({ has: page.locator(`#${checkboxId}`) })
        .click({ force: true });
    await page.getByText(/1 .*selected/i).waitFor({ timeout: 15000 });

    await page.getByTestId("translation-method-de").selectOption(["Duplicate"]);
    await page.getByRole("button", { name: "Duplicate", exact: true }).click();
    await page.waitForTimeout(8000);

    const result = await page.evaluate(async (apiUrl) => {
        const en = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=en&per_page=20`, { credentials: "include" })
        ).json();
        const de = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=de&per_page=20`, { credentials: "include" })
        ).json();
        const cookieDe = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?lang=de&per_page=20`, { credentials: "include" })
        ).json();
        const deBlocker = de[de.length - 1];
        const cookieDeId = cookieDe[0]?.id;
        return {
            blockerEnId: en[en.length - 1]?.id,
            blockerDe: deBlocker ? { id: deBlocker.id, services: deBlocker.meta?.services } : null,
            cookieDeId,
            pass: deBlocker && String(deBlocker.meta?.services) === String(cookieDeId),
        };
    }, api);

    return { step: "CB-D done", checkboxId, ...result };
};
```

Pass criteria (CB-D): `pass === true` — DE blocker `meta.services` === DE cookie id (not EN id).

### Script CB-E — Verify DE edit UI (~25s)

```javascript
async (page) => {
    const host = "https://wordpress.ci-runner-16.owlsrv.de";
    const api = "https://wordpress.ci-runner-16.owlsrv.de";

    const deBlockerId = await page.evaluate(async (apiUrl) => {
        const de = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=de&per_page=20`, { credentials: "include" })
        ).json();
        return de[de.length - 1]?.id;
    }, api);

    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));

    await page.goto(
        `${host}/wp-admin/admin.php?page=real-cookie-banner-component&lang=de&admin_bar=1#/blocker/edit/${deBlockerId}`,
    );
    await page.waitForTimeout(12000);

    const selectionItems = await page.locator(".rcb-antd-select-selection-item").allTextContents();
    const errBoundary = await page.getByText(/Unexpected Application Error|Unerwarteter/i).count();

    return {
        step: "CB-E done",
        deBlockerId,
        selectionItems,
        pass: selectionItems.includes("Real Cookie Banner") && errBoundary === 0 && errors.length === 0,
        errors,
        errBoundary,
    };
};
```

Pass criteria (CB-E): `pass === true` — tag "Real Cookie Banner" visible; no error boundary.

---

## Blocker — Manual phases (MCP tool steps)

### CB-0 — Prerequisites

- Cookie Script A + B done (EN + DE "Real Cookie Banner" exist).
- Content blocker feature enabled (`#/settings` → enable blockers, or option `rcb-blocker-active`).
- If `#/blocker/new` shows only templates: use `?force=scratch` (see Script CB-C).

### CB-1 — Create EN blocker (RCB UI)

URL:
/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/blocker/new?force=scratch

1. Name: e.g. "RCB Service Blocker Test".
2. URLs / Elements to block: e.g. `iframe[src*="youtube.com"]` (required — save fails without rules).
3. Connected services: open combobox, type "Real Cookie Banner", select active dropdown option
   (see Script CB-C evaluate click if Playwright click reports "not visible").
4. Save — button role `Save` (Ant Design form; not always `input[type=submit]`).
5. Confirm redirect to `#/blocker` and REST EN blocker with `meta.criteria === "services"`.

### CB-2 — WPML duplicate blocker

URL: /wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1

section = getByTestId('post/rcb-blocker-section')
Expand if needed.

Filters (same as cookies):
translated to: German
Filter by translation status: All translation statuses
button.filter-button

Checkbox: `rcb-blocker{N}` where N = EN blocker post id from REST.
evaluate + label.wpml-checkbox click (force: true).
Wait /1 .\*selected/i (WPML wording varies: "Cookie" vs "Content blocker").

translation-method-de → Duplicate → Duplicate button.

### CB-3 — Verify data (REST, authoritative)

GET /wp-json/wp/v2/rcb-blocker?lang=en → `services` = EN cookie id
GET /wp-json/wp/v2/rcb-blocker?lang=de → `services` = DE cookie id (must differ from EN id)

### CB-4 — Verify DE edit UI

URL:
/wp-admin/admin.php?page=real-cookie-banner-component&lang=de&admin_bar=1#/blocker/edit/{deBlockerId}

- `.rcb-antd-select-selection-item` contains "Real Cookie Banner" (label is translated in DE UI;
  do not rely on English "Connected services" text — use selection-item locator).
- No "Unexpected Application Error" / "Unerwarteter … Fehler".

---

## Blocker — Playwright MCP tool order

1. browser_navigate (Basic Auth) → login if needed → strip creds from URL for later steps
2. browser_run_code Script A (cookies)
3. browser_run_code Script B (cookie WPML duplicate)
4. browser_run_code Script CB-C (create blocker UI)
5. browser_run_code Script CB-D (blocker WPML duplicate)
6. browser_run_code Script CB-E (DE edit UI)

Optional: Script CB-A between A and B when old DE blockers pollute the TM table.

---

## Blocker — Common failures

| Symptom                                                        | Cause                                         | Fix                                                                          |
| -------------------------------------------------------------- | --------------------------------------------- | ---------------------------------------------------------------------------- |
| `#/blocker/new` shows templates only                           | Missing `force=scratch`                       | Use `#/blocker/new?force=scratch`                                            |
| Save stays on form, validation alert                           | Missing rules and/or services                 | Fill URLs field; fix service select (CB-C click)                             |
| Service tag visible but "Please provide at least one service!" | antd value not committed                      | combobox.fill + evaluate click on `-option-active`                           |
| DE `services` still EN cookie id                               | Cookie duplicate after blocker, or repair bug | Duplicate cookie first; check multilingual repair                            |
| REST `lang=de` blockers `[]` in evaluate                       | Page URL still has `user:pass@`               | Navigate without credentials; use absolute api URL in fetch                  |
| WPML row missing                                               | Wrong filter                                  | German + All translation statuses + filter-button                            |
| CB-E timeout on "Connected services"                           | DE admin UI translated                        | Wait for `.rcb-antd-select-selection-item` or body text "Real Cookie Banner" |
| No blocker post type / empty TM section                        | Blockers not enabled                          | Enable in RCB settings (`rcb-blocker-active`)                                |

---

## Phase A — Clean state (manual / MCP tool steps)

### A1 — Reset Real Cookie Banner

URL:
/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/settings/reset

1. Wait for "Reset all".
2. Click getByRole('button', { name: 'Reset' }).nth(1) — second Reset = "Reset all" row.
3. Click popover getByRole('button', { name: 'Reset all' }) — required second confirm.
4. Wait ~4s; hash may disappear from URL — OK.

### A2 — Remove German copy (if present)

Prefer direct navigation to trash/delete hrefs (always works; no scroll issues).

After A: REST shows exactly one EN rcb-cookie.

---

## Phase B — WPML duplicate

URL: /wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1

### B1 — Cookies accordion

section = getByTestId('post/rcb-cookie-section')
If "Expand" visible → click. If already "Collapse" → skip.

### B2 — Filters (both are required)

translated to: German ← Step 2 never appears with "All languages"
Filter by translation status: All translation statuses ← NOT "Not completed" after reset
Click: page.locator('button.filter-button') ← avoids strict-mode clash with "Clear filters"

Symptom if wrong filter: checkbox "Select Real Cookie Banner" never appears (30s timeout).

### B3 — Select cookie (reliable sequence)

1. GET /wp-json/wp/v2/rcb-cookie?lang=en → original post id N
2. Checkbox DOM id is always: rcb-cookie{N} (e.g. rcb-cookie53)
3. evaluate: checked=true + change event on #rcb-cookie{N}
4. click label.wpml-checkbox filtered by that input (force: true)
5. Wait "1 Cookie selected" + Step 2 visible

evaluate-only check often fails WPML React state — always add label click.

### B4 — Duplicate

If the Czech target row is shown: `translation-method-cs` → **Do nothing** (DE-only repro).
`translation-method-de` → **Duplicate**
`getByRole('button', { name: 'Duplicate', exact: true }).click()`
Wait `has been duplicated` (or failure modal + `docker logs` grep for memory — see
**ci-runner-16 — TM duplicate-only speed path**)

Row status becomes "German: duplicate" (still EN post in DB — data bug).

---

## Phase C — Verify data (authoritative; do this before UI)

Run after Script B. WPML creates the DE post in `lang=de` (not a second EN row).

### `meta.technicalDefinitions`

`GET /wp-json/wp/v2/rcb-cookie?per_page=100&lang=en`

- Exactly one EN post (the master from Script A).
- `meta.technicalDefinitions` is a non-empty JSON string (`techLen > 10`).

Historical bug (pre-fix): a second EN duplicate with `technicalDefinitions === ""` — see Script C+D `broken` / `good` split if reproducing the old failure.

### `rcb-cookie-group` on translated services

`GET /wp-json/wp/v2/rcb-cookie?per_page=100&lang=de`

For each DE cookie created in Script B:

- `rcb-cookie-group` (REST) is a **non-empty** array of term ids.
- Every id must be a **German** group term: each value appears in `GET /wp-json/wp/v2/rcb-cookie-group?lang=de&per_page=100`. Must **not** be the EN master's term id (WPML `icl_object_id` fallback when no translation exists).

Prerequisites:

- Master EN cookie already has a group (default content after reset assigns Essential).
- DE group terms exist. After reset, default groups are per language; when adding a **new** language later, run Script CG-B before Script B.

Optional admin spot-check: `edit.php?post_type=rcb-cookie&lang=de` — Service groups column filled (not empty).

### Translated purpose text (`post_content`) — one-time copy at duplicate

`GET /wp-json/wp/v2/rcb-cookie?per_page=100&lang=de` — for the DE duplicate of "Real Cookie Banner":

- `content.rendered` must be **German** (starts with e.g. `Real Cookie Banner bittet Website-Besucher…`), not the English master string (`…asks website visitors…`).
- The external duplicate keeps the master HTML verbatim; multilingual applies `translateInput()` inside `syncPostFromExternalSource()` (same as `duplicatePost()`). RCB purpose strings resolve via PO/msgid like the internal copy path; product-specific contexts (e.g. `legal-text` on meta) stay in `DevOwl/Multilingual/Copy/Meta/*` filters.
- **Not** an ongoing sync target: RCB stores purpose in `post_content` and name in `post_title` (see `CookieModel.setPurpose` → `content.raw`). `Cookie::SYNC_OPTIONS['data']` only lists `menu_order`. Meta uses `copy` / `copy-once`; purpose/title are **not** in those lists — they must be translated **once** when the translation post is created (internal `copyPostToOtherLanguage` / external `syncPostFromExternalSource`), then left independent per language.

---

## Regression — Purpose (`post_content`): WPML duplicate mirror vs DevOwl sync

**Symptom (reported 2026-05-22):** Editing **Purpose** on the EN "Real Cookie Banner" service updates DE; editing Purpose on DE does **not** update EN.

Example admin URLs (group term ids in hash; post ids from REST after reset):

- EN: `…/admin.php?page=real-cookie-banner-component&lang=en#/cookies/<enGroupId>/edit/<enPostId>`
- DE: `…/admin.php?page=real-cookie-banner-component&lang=de#/cookies/<deGroupId>/edit/<dePostId>`

### Prerequisites — mandatory reset

Do **not** repro on stale duplicate pairs left from manual `wp post delete` or old TM runs.

1. Run **[Script A](#script-a--clean-state-30s)** (A1 reset + A2 delete all DE `rcb-cookie` rows).
2. Run **[Script B](#script-b--wpml-duplicate-45s)** (TM Duplicate EN → DE) **or** `wpml-rcb-phase-c-verify.php` (reset + `make_duplicate` in one shot).
3. Discover current ids (they drift every reset):

```bash
wp post list --post_type=rcb-cookie --format=table --fields=ID,post_title,post_status
wp post list --post_type=rcb-cookie --lang=de --format=table --fields=ID,post_title,post_status
```

Validated asymmetry run (2026-05-22, **after Script A + existing DE duplicate** on ci-runner-16): EN master **823**, DE **824**, EN group **672**, DE group **673**. Re-run Script A+B before trusting these ids.

### Reproduction — REST (same payload as RCB "Save")

Run from a logged-in `wp-admin` page (`window.wpApiSettings.nonce`) or equivalent authenticated REST client. Use host **without** `user:pass@` in `fetch` URLs (credentials break relative `/wp-json` from `evaluate`).

```javascript
// Paste in browser devtools on https://wordpress.ci-runner-16.owlsrv.de/wp-admin/ (after login)
(async () => {
    const api = "https://wordpress.ci-runner-16.owlsrv.de";
    const nonce = window.wpApiSettings?.nonce;
    const headers = { "X-WP-Nonce": nonce, "Content-Type": "application/json" };
    const pick = (list) =>
        (Array.isArray(list) ? list : []).find((p) => (p.title?.rendered || "").includes("Real Cookie Banner"));
    const en = pick(
        await (
            await fetch(`${api}/wp-json/wp/v2/rcb-cookie?lang=en&per_page=100`, { credentials: "include", headers })
        ).json(),
    );
    const de = pick(
        await (
            await fetch(`${api}/wp-json/wp/v2/rcb-cookie?lang=de&per_page=100`, { credentials: "include", headers })
        ).json(),
    );
    if (!en || !de) return { error: "missing Real Cookie Banner post", enId: en?.id, deId: de?.id };

    const markerEn = `SYNC-TEST-EN-${Date.now()}`;
    await fetch(`${api}/wp-json/wp/v2/rcb-cookie/${en.id}?lang=en`, {
        method: "PATCH",
        headers,
        credentials: "include",
        body: JSON.stringify({ content: { raw: markerEn } }),
    });
    const deAfterEn = await (
        await fetch(`${api}/wp-json/wp/v2/rcb-cookie/${de.id}?lang=de`, { credentials: "include", headers })
    ).json();

    const markerDe = `SYNC-TEST-DE-${Date.now()}`;
    await fetch(`${api}/wp-json/wp/v2/rcb-cookie/${de.id}?lang=de`, {
        method: "PATCH",
        headers,
        credentials: "include",
        body: JSON.stringify({ content: { raw: markerDe } }),
    });
    const enAfterDe = await (
        await fetch(`${api}/wp-json/wp/v2/rcb-cookie/${en.id}?lang=en`, { credentials: "include", headers })
    ).json();

    return {
        enId: en.id,
        deId: de.id,
        deSyncedFromEn: String(deAfterEn?.content?.raw || deAfterEn?.content?.rendered || "").includes(markerEn),
        enSyncedFromDe: String(enAfterDe?.content?.raw || enAfterDe?.content?.rendered || "").includes(markerDe),
        deAfterEnSnippet: (deAfterEn?.content?.raw || deAfterEn?.content?.rendered || "").slice(0, 80),
        enAfterDeSnippet: (enAfterDe?.content?.raw || enAfterDe?.content?.rendered || "").slice(0, 80),
    };
})();
```

**Expected on current code (regression):** `deSyncedFromEn: true`, `enSyncedFromDe: false`.

| Step                   | Action           | Observed (2026-05-22)                                         |
| ---------------------- | ---------------- | ------------------------------------------------------------- |
| PATCH EN `content.raw` | `SYNC-TEST-EN-…` | DE **824** contains the EN marker (WPML duplicate mirror)     |
| PATCH DE `content.raw` | `SYNC-TEST-DE-…` | EN **823** still shows the EN marker; DE marker **not** on EN |

### Why it happens (WPML core + multilingual package)

| Mechanism                                                                      | Direction                                                          | What it syncs                                                                                                |
| ------------------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ |
| `_icl_lang_duplicate_of` + `WPML_Post_Synchronization::sync_with_duplicates()` | Master → duplicates only                                           | Re-runs `make_duplicate()` on master save (overwrites duplicate `post_title`, `post_content`, meta SQL copy) |
| `WPML::isExternalTranslationSyncDeferredForPost()` when duplicate meta is set  | Blocks DevOwl                                                      | `Sync::save_post()` returns immediately — no cross-language meta/column sync from duplicate edits            |
| DevOwl `Sync::save_post()` update branch                                       | Would be bidirectional **only for** `SYNC_OPTIONS['data']` columns | RCB cookies: **`menu_order` only** — not `post_content` / `post_title`                                       |

WPML marks duplicates in `WPML_Post_Duplication::run_wpml_actions()`:

- `update_post_meta( $id, '_icl_lang_duplicate_of', $master_post->ID );`
- TM status `ICL_TM_DUPLICATE`
- Hook `icl_make_duplicate` (DevOwl hooks here with `syncPostFromExternalSource()`)

On every master save, `WPML_Post_Translation::after_save_post()` calls `sync_with_duplicates( $post_id )` (see `wordpress-svn/sitepress-multilingual-cms/inc/post-translation/wpml-post-translation.class.php`).

### Product intent (RCB) — not a missing bidirectional Purpose sync

Purpose and service **name** are **language-specific legal copy**. They must be:

- **Copied once** when a translation is created (with `translateInput()` / PO msgid — same as `duplicatePost()` and `syncPostFromExternalSource()`).
- **Not** kept in lockstep afterward (unlike `menu_order` or `copy` meta keys).

So the fix is **not** to add `post_content` / `post_title` to ongoing `Sync::save_post` data sync. After duplication, editors expect independent DE/EN purpose text.

### Fix (`wordpress-packages/multilingual/src/WPML.php`)

1. `icl_make_duplicate` @20: `syncPostFromExternalSource()` then `delete_post_meta( $duplicate_id, '_icl_lang_duplicate_of' )`.
2. `isExternalTranslationSyncDeferredForPost()`: only `duplicateCreationInProgress` (no permanent defer via duplicate meta).

Purpose/title remain copy-once via `syncPostFromExternalSource`; no ongoing `post_content` / `post_title` in `Sync::save_post` data sync.

### `reset_duplicate_flag()` — official API?

| API                                                                                        | Stable for third-party / DevOwl?                                                                                                                                                                              | Role                                                                                                                         |
| ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| Filter `wpml_copy_post_to_language` (`$post_id`, `$target_language`, `$mark_as_duplicate`) | **Yes** — registered WPML API hook; deleting duplicate meta when `$mark_as_duplicate` is false is the supported detach pattern                                                                                | Prefer documenting this for "copy to language without slave mirror"                                                          |
| `delete_post_meta( $id, '_icl_lang_duplicate_of' )`                                        | **Yes** — same meta WPML uses; also what `WPML_Translate_Independently` AJAX does                                                                                                                             | Minimal detach                                                                                                               |
| `wpml_load_core_tm()->reset_duplicate_flag( $post_id )`                                    | **No** — public PHP method on `TranslationManagement`, but **internal** TM UI/basket helper (updates `icl_translation_status`, deletes duplicate meta). Not equivalent to a documented filter/action contract | Optional cosmetic: TM dashboard may show "complete" instead of "duplicate"; call only if you accept coupling to TM internals |

For DevOwl automation after `icl_make_duplicate`, **`delete_post_meta` (or `wpml_copy_post_to_language` with `$mark_as_duplicate = false`) is sufficient**; `reset_duplicate_flag` is optional TM polish, not required for correct RCB behaviour.

### Verify after fix

After Script A + Script B (fresh duplicate):

1. Re-run the REST script above → `deSyncedFromEn: false` and `enSyncedFromDe: false` after independent patches (each side keeps its own marker).
2. Phase C content check still passes: DE duplicate still has **German** purpose lead-in immediately after duplicate (one-time `translateInput`, not English master string).
3. `wp eval-file …/wpml-rcb-phase-c-verify.php --allow-root` → `pass.all: true`.

---

## Phase D — Verify UI crash

/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/cookies/25

Register pageerror + console listeners BEFORE goto.

FIRST navigation to #/cookies/<groupId> after duplicate: - Console: TypeError Cannot read properties of undefined (reading 'type') at ListServiceRow - UI may show "Unexpected Application Error!" — not guaranteed on reload

Second visit to same hash may show no error (React error boundary / cache) — do not treat as fix.

Edit URL for broken post: #/cookies/25/edit/<brokenPostId> (e.g. …/edit/56)

---

## Playwright MCP tool order — cookies (if not using run_code blocks)

1. browser_navigate (Basic Auth) → A1 reset (2 clicks)
2. browser_run_code Script A
3. browser_run_code Script B
4. browser_run_code Script C+D
   Skip: browser_snapshot unless debugging; skip screenshot unless reporting.

For content blockers, see **Blocker — Playwright MCP tool order** above.

---

## Common failures — cookies (learned the hard way)

| Symptom                           | Cause                                             | Fix                                                                                  |
| --------------------------------- | ------------------------------------------------- | ------------------------------------------------------------------------------------ |
| No cookie row in WPML             | Filter "Not completed"                            | Use "All translation statuses" + Filter                                              |
| strict mode on Filter click       | Two buttons match "Filter"                        | button.filter-button                                                                 |
| Step 2 / Duplicate missing        | translated to still "All languages"               | selectOption German + select cookie                                                  |
| Checkbox timeout                  | Wrong id or row hidden                            | REST → rcb-cookie{postId}                                                            |
| Step 2 missing after evaluate     | WPML React ignored DOM                            | Label click after evaluate                                                           |
| No Duplicate button               | Step 2 not rendered                               | B2 + B3 checklist                                                                    |
| #/cookies/48 crash wrong          | Used post id as group                             | Use #/cookies/25                                                                     |
| Phase D false negative on reload  | Error only on first render                        | pageerror on first goto; trust Phase C                                               |
| browser_click timeout on trash    | Element off-screen                                | Open trash/delete href directly                                                      |
| Reset incomplete                  | Only one Reset click                              | Popover "Reset all" is mandatory                                                     |
| Empty Service groups column (DE)  | Taxonomy not remapped on duplicate                | Deploy multilingual fix; run CG-B before B if new lang; re-duplicate                 |
| `rcb-cookie-group` is `[]` (DE)   | `get_the_terms()` in target locale                | `copyPostTaxonomies()` reads terms in **source** locale; re-duplicate after deploy   |
| DE group ids are EN term ids      | No translated group term in WPML                  | Copy groups in Taxonomy Translation first; then TM Duplicate again                   |
| TM **Select All** (top)           | Selects Cookies + Banner links                    | Select only `#rcb-cookie{N}`; wait "1 Cookie selected"                               |
| Cookie row missing on TM          | Dashboard opened as `lang=cs`                     | TM URL must use `lang=en`; source filter English                                     |
| Checkbox click intercepted        | Decorative `<span>` over input                    | Script B: `evaluate` + `label.wpml-checkbox` force; avoid bare `browser_click` ref   |
| DE trash/delete click timeout     | Row off-screen in list table                      | Follow trash/delete `href` (Script A) or `wp post delete <id> --force`               |
| UI failed but ajax 200            | Fatal logged to stderr, empty JSON                | `docker logs … \| grep -i memory`; do not trust status code alone                    |
| No memory line on EN repro        | Fatals only in older `lang=cs` runs               | Grep logs with referer `lang=en`; re-submit after single-cookie selection            |
| EN Purpose change updates DE only | `_icl_lang_duplicate_of` + `sync_with_duplicates` | Detach meta after `icl_make_duplicate`; do not add bidirectional `post_content` sync |
| DE Purpose change ignored on EN   | DevOwl defers `save_post` on duplicate posts      | Same detach; confirm independent copy per language after fix                         |

---

## Optional artifacts

wpml-rcb-regression-repro.md — this file
.ai/scripts/cg-tt-wpml-smoke.php — WP-CLI CG-TT harness (see below)
rcb-bug-reproduced.png — UI error boundary (first run)
rcb-bug-reproduced-2.png — tabs view (second run; console still had error)
.playwright-mcp/console-\*.log — full ListServiceRow stack traces

---

## Service groups — WPML taxonomy "Copy to all languages" (CG-TT)

Validated run (2026-05-20): ~6 min with Script CG-A + CG-B + CG-C (batched run_code_unsafe).

Symptom before fix: after deleting EN groups and using WPML Taxonomy Translation → "Copy to all languages",
REST `GET /wp-json/wp/v2/rcb-cookie-group?lang=en` returns 8+ terms (duplicate German names in EN) and RCB
`#/cookies/<groupId>` shows duplicate tabs. Root cause: `Sync::created_term` ran during `wp_ajax_wpml_save_term`
and called `copyTermToOtherLanguage` for every other language on top of WPML's own insert.

Fix: `wordpress-packages/multilingual` — skip `created_term` sync when WPML saves a term translation; run
`syncTermFromExternalSource()` on `created_term` priority 20 via `syncTermAfterWpmlTranslation()` (reads
`wpml_element_language_details` + TRID, no `$_POST`). `wp_ajax_wpml_save_term` only sets/clears defer flag.

### CG-0 — Prerequisites

Same as global Prerequisites. Default install has DE + EN; four default groups exist per language after reset.

WPML Taxonomy Translation URL (select taxonomy in UI or append query):
/wp-admin/admin.php?page=sitepress-multilingual-cms%2Fmenu%2Ftaxonomy-translation.php&taxonomy=rcb-cookie-group

Delete EN groups (if reproducing from scratch):
/wp-admin/edit-tags.php?taxonomy=rcb-cookie-group&lang=en
Open each row's **Delete** link directly (`a.delete-tag` or `a.submitdelete`; off-screen rows timeout on click).

RCB service list hashes (group id drifts — discover via REST or notice link after reset):
#/cookies/<cookieGroupTermId> e.g. …#/cookies/154 (DE), …#/cookies/165 (EN) — environment-specific

### Script CG-A — Clean state + delete EN groups (~45s)

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    await page.goto(`${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/settings/reset`);
    await page.getByRole("button", { name: "Reset" }).nth(1).click();
    await page.getByRole("button", { name: "Reset all" }).click();
    await page.waitForTimeout(4000);

    await page.goto(`${base}/wp-admin/edit-tags.php?taxonomy=rcb-cookie-group&lang=en`);
    const deleteHrefs = await page.$$eval("a.delete-tag, a.submitdelete", (as) => as.map((a) => a.href));
    for (const href of deleteHrefs) {
        await page.goto(href);
        await page.waitForTimeout(500);
    }

    const enAfterDelete = await page.evaluate(async (api) => {
        const r = await fetch(`${api}?lang=en&per_page=100`, { credentials: "include" });
        return (await r.json()).length;
    }, `${base}/wp-json/wp/v2/rcb-cookie-group`);

    return { step: "CG-A", enAfterDelete, pass: enAfterDelete === 0 };
};
```

Pass: `enAfterDelete === 0`.

### Script CG-B — WPML copy all languages per row (~90s)

WPML renders "Copy to all languages" as `.js-copy-to-all-langs` per row; viewport/scroll breaks plain clicks —
use `evaluate` to click each visible link and confirm the dialog.

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    const url = `${base}/wp-admin/admin.php?page=sitepress-multilingual-cms%2Fmenu%2Ftaxonomy-translation.php&taxonomy=rcb-cookie-group`;
    await page.goto(url);
    await page.waitForTimeout(2000);

    const rowCount = await page.locator(".js-copy-to-all-langs").count();
    for (let i = 0; i < rowCount; i++) {
        await page.evaluate((idx) => {
            const links = document.querySelectorAll(".js-copy-to-all-langs");
            links[idx]?.click();
        }, i);
        await page.waitForTimeout(500);
        const ok = page
            .locator(".ui-dialog-buttonpane button")
            .filter({ hasText: /OK|Yes|Copy/i })
            .first();
        if (await ok.isVisible().catch(() => false)) await ok.click();
        await page.waitForTimeout(1500);
    }

    return { step: "CG-B", rowCount };
};
```

Run once per default group row (4× after reset). If dialog button text differs, snapshot once and adjust selector.

### Script CG-C — Verify REST + duplicate names (~15s)

```javascript
async (page) => {
    const base = "https://wordpress.ci-runner-16.owlsrv.de";
    const api = `${base}/wp-json/wp/v2/rcb-cookie-group`;
    const result = await page.evaluate(async (apiUrl) => {
        const fetchLang = async (lang) => {
            const r = await fetch(`${apiUrl}?lang=${lang}&per_page=100`, { credentials: "include" });
            return await r.json();
        };
        const en = await fetchLang("en");
        const de = await fetchLang("de");
        const names = (rows) => rows.map((t) => t.name);
        const dup = (rows) => names(rows).length !== new Set(names(rows)).size;
        const enExpected = ["Essential", "Functional", "Statistics", "Marketing"];
        const enHasEnglishDefaults = enExpected.every((n) => names(en).includes(n));
        return {
            enCount: en.length,
            deCount: de.length,
            dupEn: dup(en),
            dupDe: dup(de),
            enNames: names(en),
            deNames: names(de),
            enHasEnglishDefaults,
            pass: en.length === 4 && de.length === 4 && !dup(en) && !dup(de) && enHasEnglishDefaults,
        };
    }, api);
    return { step: "CG-C", ...result };
};
```

Pass criteria (authoritative):

- `enCount === 4` and `deCount === 4`
- `dupEn === false` and `dupDe === false`
- `enHasEnglishDefaults === true` (EN labels, not German copies in EN)

Optional UI spot-check: open `#/cookies/<deGroupId>` and `#/cookies/<enGroupId>` — four group tabs each, not eight.

### CG — WP-CLI smoke (optional, no Playwright)

Inside the WordPress container (path may vary; adjust if bind-mount differs):

```bash
wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/cg-tt-wpml-smoke.php --allow-root
# Cookie duplicate + Essenziell assignment (broader than CG-only):
wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-phase-c-verify.php --allow-root
```

Runs reset + `Localization::multilingual()`, CG-A/B/C via `WPML_Terms_Translations::create_new_term`. JSON output with `CG-C.pass`.

### CG — Common failures

| Symptom                | Cause                                 | Fix                                                                                |
| ---------------------- | ------------------------------------- | ---------------------------------------------------------------------------------- |
| 8 EN terms after copy  | `created_term` sync still runs        | Deploy multilingual fix; defer + `created_term` @20 `syncTermAfterWpmlTranslation` |
| EN names still German  | Source MO missing or string not in PO | Install language pack; msgid resolve walks source + default + active locales       |
| Copy link no-op        | Element off-screen                    | `evaluate` click by index (Script CG-B)                                            |
| `enAfterDelete > 0`    | Delete links not followed             | Direct `delete-tag` / `submitdelete` href navigation (CG-A)                        |
| Wrong taxonomy in WPML | Picked wrong taxonomy dropdown        | `&taxonomy=rcb-cookie-group` or "Service groups"                                   |

---

### TM duplicate — PHP memory exhausted (128MB)

**Symptom:** Fatal in `wpml-page-builders/.../class-wpml-pb-package-strings-resave.php` when
duplicating from Translation Management. UI: modal _Sending for translation failed_ (technical
details may be empty or unrelated — trust container logs).

**Cause:** `icl_make_duplicate` ran full `syncPostFromExternalSource()` (meta re-copy + PO catalog
snapshot + `translateInput` + `wp_update_post`) although WPML already duplicates post meta and
`wpml_duplicate_generic_string` preserves configured copy keys. RCB cookies carry large JSON meta;
re-copying plus Page Builder resave exceeds 128MB. Stacked `set_object_terms` closures from
`copyPostTaxonomies()` made the same request worse.

**Repro guardrails:** EN source + DE target + single cookie checkbox + `lang=en` TM URL (see speed
path above). `admin-ajax.php?action=wpml_api_wpml_v1_send-to-translation` may return **HTTP 200**
while PHP still fatals — grep `docker logs` for `Allowed memory size`.

**Fix:** On WPML duplicate, remap taxonomies only (no meta re-copy) and translate `post_title` /
`post_content` via `translateInput()` (same as `duplicatePost()`). Meta stays on the WPML duplicate +
generic-string filter. Deploy multilingual, then re-duplicate.

---

## Out of scope for reproduce (fix phase)

- wordpress-svn/sitepress-multilingual-cms — reference only, no edits
- Fix: wordpress-packages/multilingual (WPML duplicate / meta copy / term save) and/or RCB ListServiceRow
  guard when technicalDefinitions is ""
