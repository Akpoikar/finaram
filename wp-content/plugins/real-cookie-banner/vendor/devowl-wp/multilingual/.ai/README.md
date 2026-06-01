# `.ai/` — `@devowl-wp/multilingual`

Project-local AI artefacts for the multilingual package (WPML / PolyLang sync, term/post copy, external-source hooks).

## References

| File                                                                               | Purpose                                                                                                 |
| ---------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------- |
| [references/wpml-rcb-regression-repro.md](references/wpml-rcb-regression-repro.md) | Playwright + REST regression scripts for WPML × Real Cookie Banner (cookies, blockers, service groups). |

## Scripts (regression harnesses, not shipped code)

| File                                                         | Purpose                                                                                                |
| ------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| [scripts/cg-tt-wpml-smoke.php](scripts/cg-tt-wpml-smoke.php) | Optional `wp eval-file` smoke for CG-TT when UI/Playwright is flaky. Requires RCB + WPML in the stack. |

```bash
wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/cg-tt-wpml-smoke.php --allow-root
```
