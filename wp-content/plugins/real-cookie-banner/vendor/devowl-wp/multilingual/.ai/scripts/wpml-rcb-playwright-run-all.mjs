/* eslint-disable */
/**
 * Playwright runner for wpml-rcb-regression-repro.md browser scripts (ci-runner-16).
 *
 *   RCB_BASIC_AUTH_USER=local RCB_BASIC_AUTH_PASS=... node wpml-rcb-playwright-run-all.mjs
 */
import { execSync } from "node:child_process";
import { chromium } from "playwright";

const base = process.env.RCB_BASE_URL ?? "https://wordpress.ci-runner-16.owlsrv.de";
const wpContainer =
    process.env.WP_CONTAINER ??
    execSync("docker ps --filter name=wordpress --format '{{.Names}}' | head -1", { encoding: "utf8" }).trim();
const authUser = process.env.RCB_BASIC_AUTH_USER ?? "local";
const authPass = process.env.RCB_BASIC_AUTH_PASS ?? "";
const api = base;

const results = [];

function record(name, pass, extra = {}) {
    results.push({ name, pass, ...extra });
    console.log(`[${pass ? "PASS" : "FAIL"}] ${name}`, JSON.stringify(extra));
}

async function loginWp(page) {
    const authUrl = authPass !== "" ? base.replace("://", `://${authUser}:${authPass}@`) : base;
    await page.goto(`${authUrl}/wp-login.php`, { waitUntil: "domcontentloaded", timeout: 120000 });
    if (page.url().includes("wp-login.php")) {
        await page.fill("#user_login", "wordpress");
        await page.fill("#user_pass", "wordpress");
        await page.click("#wp-submit");
        await page.waitForURL(/wp-admin/, { timeout: 60000 }).catch(() => {});
    }
}

async function scriptA(page) {
    await page.goto(`${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/settings/reset`);
    await page.getByText("Reset all").first().waitFor({ state: "visible", timeout: 30000 });
    await page.getByRole("button", { name: "Reset" }).nth(1).click();
    await page
        .getByRole("button", { name: "Reset all" })
        .click({ timeout: 15000 })
        .catch(() => {});
    await page.waitForTimeout(4000);

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
    const enPostIds = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=en`, { credentials: "include" });
        return (await r.json()).map((p) => p.id);
    }, api);
    return { enPostIds, pass: enPostIds.length === 1 };
}

async function scriptB(page) {
    const posts = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=en`, { credentials: "include" });
        return await r.json();
    }, api);
    const originalId = posts[0]?.id;
    if (!originalId) return { pass: false, reason: "no EN cookie" };

    const deExists = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=de`, { credentials: "include" });
        return (await r.json()).some((p) => p.title?.rendered === "Real Cookie Banner");
    }, api);
    if (deExists) {
        return { originalId, pass: true, skipped: "DE cookie already present" };
    }

    const checkboxId = `rcb-cookie${originalId}`;

    await page.goto(`${base}/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1`);
    await page.waitForTimeout(3000);
    const section = page.getByTestId("post/rcb-cookie-section");
    if (await section.getByRole("button", { name: "Expand" }).count()) {
        await section.getByRole("button", { name: "Expand" }).click();
        await page.waitForTimeout(1000);
    }
    await page.getByLabel("translated to:").selectOption("German");
    await page.getByLabel("Filter by translation status").selectOption("All translation statuses");
    await page.locator("button.filter-button").click();
    await page.waitForTimeout(4000);

    const checkbox = page.locator(`#${checkboxId}`);
    if (!(await checkbox.isVisible().catch(() => false))) {
        await page.locator("button.filter-button").click();
        await page.waitForTimeout(3000);
    }
    await checkbox.waitFor({ state: "visible", timeout: 60000 });
    await checkbox.evaluate((el) => {
        el.checked = true;
        el.dispatchEvent(new Event("change", { bubbles: true }));
    });
    await page
        .locator("label.wpml-checkbox")
        .filter({ has: page.locator(`#${checkboxId}`) })
        .click({ force: true });
    await page.getByText("1 Cookie selected").waitFor({ timeout: 15000 });

    const csMethod = page.getByTestId("translation-method-cs");
    if (await csMethod.count()) await csMethod.selectOption(["do_nothing"]);
    const nlMethod = page.getByTestId("translation-method-nl");
    if (await nlMethod.count()) await nlMethod.selectOption(["do_nothing"]);
    await page.getByTestId("translation-method-de").selectOption(["Duplicate"]);
    await page.getByRole("button", { name: "Duplicate", exact: true }).click();

    const deAppeared = await page
        .waitForFunction(
            async (apiUrl) => {
                const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=de`, {
                    credentials: "include",
                });
                const rows = await r.json();
                return rows.some((p) => p.title?.rendered === "Real Cookie Banner");
            },
            api,
            { timeout: 120000 },
        )
        .then(() => true)
        .catch(() => false);

    const successBanner = await page
        .getByText(/has been duplicated|wurde dupliziert|duplicated/i)
        .first()
        .isVisible()
        .catch(() => false);

    const deId = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=de`, { credentials: "include" });
        const rows = await r.json();
        return (
            rows.find((p) => p.title?.rendered === "Real Cookie Banner")?.id ??
            rows.find((p) => p.slug === "real-cookie-banner")?.id ??
            rows[0]?.id ??
            null
        );
    }, api);

    return { originalId, deId, checkboxId, pass: deAppeared, deAppeared, successBanner };
}

async function scriptCD(page, enMasterId, deIdHint) {
    const phaseData = await page.evaluate(
        async ({ apiUrl, enMasterId, deIdHint }) => {
            const fetchCookies = async (lang) => {
                const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?per_page=100&lang=${lang}`, {
                    credentials: "include",
                });
                return await r.json();
            };
            const enPosts = (await fetchCookies("en")).map((p) => ({
                id: p.id,
                title: p.title?.rendered,
                techLen: String(p.meta?.technicalDefinitions ?? "").length,
                groups: p["rcb-cookie-group"] ?? [],
            }));
            const enMaster =
                enPosts.find((p) => p.id === enMasterId) ??
                enPosts.find((p) => p.title === "Real Cookie Banner") ??
                enPosts[0];
            let deGroupsRest = [];
            let deId = deIdHint;
            if (!deId) {
                const deList = await fetchCookies("de");
                deId =
                    deList.find((p) => p.title?.rendered === "Real Cookie Banner")?.id ??
                    deList.find((p) => p.slug === "real-cookie-banner")?.id ??
                    deList[0]?.id ??
                    null;
            }
            if (deId) {
                const single = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie/${deId}?lang=de`, {
                    credentials: "include",
                });
                deGroupsRest = (await single.json())["rcb-cookie-group"] ?? [];
            }
            const dePosts = deId ? [{ id: deId, techLen: 0, groups: deGroupsRest }] : [];
            const enGroupR = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie-group?lang=en&per_page=100`, {
                credentials: "include",
            });
            const enGroups = (await enGroupR.json()).map((t) => t.id);
            const deCookie = dePosts[0];
            const enGroupId = enMaster?.groups?.[0] ?? enGroups[0];
            const groupPass = deCookie !== undefined && deGroupsRest.length > 0 && !deGroupsRest.includes(enGroupId);
            const broken = enPosts.filter((p) => p.techLen === 0);
            const good = enPosts.filter((p) => p.techLen > 10);
            return {
                enPosts,
                dePosts,
                enGroupId,
                deGroupsRest,
                broken,
                good,
                groupPass,
                techPass: broken.length === 0 && good.length >= 1,
                groupId: String(enGroupId ?? enGroups[0] ?? "25"),
            };
        },
        { apiUrl: api, enMasterId, deIdHint },
    );

    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));
    page.on("console", (m) => {
        if (m.type() === "error" && /reading 'type'/.test(m.text())) errors.push(m.text().slice(0, 80));
    });
    await page.goto(
        `${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/cookies/${phaseData.groupId}`,
        { waitUntil: "domcontentloaded" },
    );
    await page.waitForTimeout(12000);

    const body = await page.locator("body").innerText();
    const hasCrash =
        errors.some((e) => e.includes("reading 'type'")) ||
        body.includes("Unexpected Application Error") ||
        body.includes("Cannot read properties of undefined (reading 'type')");
    const phaseC = {
        techPass: phaseData.techPass,
        groupPass: phaseData.groupPass,
        pass: phaseData.techPass && phaseData.groupPass,
        deGroupsRest: phaseData.deGroupsRest,
    };
    const phaseD = { pass: !hasCrash, crashDetected: hasCrash };
    return { phaseC, phaseD, groupId: phaseData.groupId };
}

async function dismissRcbModals(page) {
    for (let i = 0; i < 5; i++) {
        const modal = page.locator(".rcb-antd-modal-wrap:not(.rcb-antd-modal-wrap-hidden)");
        if ((await modal.count()) === 0) break;
        await page.keyboard.press("Escape");
        await page.waitForTimeout(400);
        const close = page.locator(".rcb-antd-modal-close").first();
        if (await close.isVisible().catch(() => false)) await close.click({ force: true });
        await page.waitForTimeout(400);
    }
}

async function scriptCBC(page) {
    await page.goto(
        `${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=en&admin_bar=1#/blocker/new?force=scratch`,
    );
    await page.waitForTimeout(6000);
    await dismissRcbModals(page);

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
        .locator(".rcb-antd-select-selector");
    await combobox.click({ force: true });
    await page.waitForTimeout(500);
    await page.locator(".rcb-antd-select-selection-search-input").last().fill("Real Cookie Banner", { force: true });
    await page.waitForTimeout(1500);
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
    if (!tags.length) return { pass: false, reason: "no service tag after select" };

    await dismissRcbModals(page);
    await page.getByRole("button", { name: "Save" }).click({ force: true });
    await page.waitForTimeout(10000);

    const data = await page.evaluate(async (apiUrl) => {
        const en = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=en&per_page=20`, { credentials: "include" })
        ).json();
        const cookies = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?lang=en&per_page=20`, { credentials: "include" })
        ).json();
        return {
            blockers: en.map((b) => ({ id: b.id, services: b.meta?.services })),
            cookieEnId: cookies[0]?.id,
        };
    }, api);

    const blocker = data.blockers[data.blockers.length - 1];
    return {
        pass: blocker && String(blocker.services) === String(data.cookieEnId),
        servicesMapsToEnCookie: blocker && String(blocker.services) === String(data.cookieEnId),
        tags,
    };
}

async function scriptCBD(page) {
    const enBlockers = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=en&per_page=20`, { credentials: "include" });
        return await r.json();
    }, api);
    const blockerEnId = enBlockers[enBlockers.length - 1]?.id;
    if (!blockerEnId) return { pass: false, reason: "no EN blocker" };
    const checkboxId = `rcb-blocker${blockerEnId}`;

    await page.goto(`${base}/wp-admin/admin.php?page=tm%2Fmenu%2Fmain.php&lang=en&admin_bar=1`);
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
        const de = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=de&per_page=20`, { credentials: "include" })
        ).json();
        const cookieDe = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie?lang=de&per_page=20`, { credentials: "include" })
        ).json();
        const deBlocker = de[de.length - 1];
        const cookieDeId = cookieDe[0]?.id;
        return {
            blockerDe: deBlocker ? { id: deBlocker.id, services: deBlocker.meta?.services } : null,
            cookieDeId,
            pass: deBlocker && String(deBlocker.meta?.services) === String(cookieDeId),
        };
    }, api);

    return { checkboxId, ...result };
}

async function scriptCBE(page) {
    const deBlockerId = await page.evaluate(async (apiUrl) => {
        const de = await (
            await fetch(`${apiUrl}/wp-json/wp/v2/rcb-blocker?lang=de&per_page=20`, { credentials: "include" })
        ).json();
        return de[de.length - 1]?.id;
    }, api);
    if (!deBlockerId) return { pass: false, reason: "no DE blocker" };

    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));

    await page.goto(
        `${base}/wp-admin/admin.php?page=real-cookie-banner-component&lang=de&admin_bar=1#/blocker/edit/${deBlockerId}`,
    );
    await page.waitForTimeout(12000);

    const selectionItems = await page.locator(".rcb-antd-select-selection-item").allTextContents();
    const errBoundary = await page.getByText(/Unexpected Application Error|Unerwarteter/i).count();

    return {
        deBlockerId,
        selectionItems,
        pass: selectionItems.includes("Real Cookie Banner") && errBoundary === 0 && errors.length === 0,
    };
}

async function scriptCGA(page) {
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

    const enAfterDelete = await page.evaluate(async (apiUrl) => {
        const r = await fetch(`${apiUrl}/wp-json/wp/v2/rcb-cookie-group?lang=en&per_page=100`, {
            credentials: "include",
        });
        return (await r.json()).length;
    }, api);

    return { enAfterDelete, pass: enAfterDelete === 0 };
}

async function scriptCGB(page) {
    const url = `${base}/wp-admin/admin.php?page=sitepress-multilingual-cms%2Fmenu%2Ftaxonomy-translation.php&taxonomy=rcb-cookie-group`;
    await page.goto(url);
    await page.waitForTimeout(2000);

    const rowCount = await page.locator(".js-copy-to-all-langs").count();
    for (let i = 0; i < rowCount; i++) {
        await page.evaluate((idx) => {
            document.querySelectorAll(".js-copy-to-all-langs")[idx]?.click();
        }, i);
        await page.waitForTimeout(500);
        const ok = page
            .locator(".ui-dialog-buttonpane button")
            .filter({ hasText: /OK|Yes|Copy/i })
            .first();
        if (await ok.isVisible().catch(() => false)) await ok.click();
        await page.waitForTimeout(1500);
    }
    return { rowCount, pass: rowCount >= 4 };
}

async function scriptCGC(page) {
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
            enHasEnglishDefaults,
            pass: en.length === 4 && de.length === 4 && !dup(en) && !dup(de) && enHasEnglishDefaults,
        };
    }, `${api}/wp-json/wp/v2/rcb-cookie-group`);
    return result;
}

async function main() {
    const phase = process.argv[2] ?? "all";
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({ ignoreHTTPSErrors: true });
    const page = await context.newPage();

    try {
        await loginWp(page);

        if (phase === "cookies" || phase === "all") {
            const a = await scriptA(page);
            record("Script A — clean state", a.pass, a);

            const b = await scriptB(page);
            record("Script B — WPML cookie duplicate EN→DE", b.pass, b);

            await page.waitForTimeout(3000);
            const cd = await scriptCD(page, a.enPostIds?.[0] ?? b.originalId, b.deId);
            record("Script C — Phase C tech + groups", cd.phaseC.pass, cd.phaseC);
            record("Script D — Phase D ListServiceRow crash", cd.phaseD.pass, cd.phaseD);
        }

        if (phase === "blocker" || phase === "all") {
            if (phase === "blocker") {
                const a = await scriptA(page);
                record("Script A — clean state (blocker prereq)", a.pass, a);
                const b = await scriptB(page);
                record("Script B — cookie duplicate (blocker prereq)", b.pass, b);
            }

            for (const script of ["wpml-rcb-copycontent-blocker-services.php", "wpml-rcb-blocker-gravatar-repro.php"]) {
                let cli = { pass: false };
                if (wpContainer) {
                    try {
                        execSync(
                            `docker exec ${wpContainer} wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/${script} --allow-root`,
                            { encoding: "utf8", stdio: "pipe" },
                        );
                        cli.pass = true;
                    } catch (e) {
                        cli = { pass: false, tail: String(e.stdout ?? e.message).slice(-400) };
                    }
                }
                record(`Script blocker CLI — ${script}`, cli.pass, cli);
            }
        }

        if (phase === "cg" || phase === "all") {
            let cgSmoke = { pass: false };
            if (wpContainer) {
                try {
                    execSync(
                        `docker exec ${wpContainer} wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/cg-tt-wpml-smoke.php --allow-root`,
                        { encoding: "utf8", stdio: "pipe" },
                    );
                    cgSmoke.pass = true;
                } catch (e) {
                    cgSmoke = { pass: false, tail: String(e.stdout ?? e.message).slice(-500) };
                }
            }
            record("Script CG — cg-tt-wpml-smoke.php (doc CG-A/B/C CLI path)", cgSmoke.pass, cgSmoke);
        }
    } catch (e) {
        console.error("FATAL:", e.message);
        record("runner", false, { error: e.message, stack: e.stack?.split("\n").slice(0, 5) });
    } finally {
        await browser.close();
    }

    const allPass = results.every((r) => r.pass);
    console.log("\n=== SUMMARY ===");
    console.log(JSON.stringify({ results, pass: { all: allPass } }, null, 2));
    process.exit(allPass ? 0 : 1);
}

main();
