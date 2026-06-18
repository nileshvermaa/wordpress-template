# CLAUDE.md

Guidance for Claude Code (and any contributor) working in this repository.

---

## Project overview

This repo is a **catalog of production-grade, sellable WordPress themes** built and
owned by **Nilesh Verma** ([@nileshvermaa](https://github.com/nileshvermaa)).

The business model: design polished, one-page WordPress themes for service
professionals, then sell/deploy them to clients who host on cheap shared plans
(primarily **Hostinger**, whose entry tiers ship WordPress by default). Themes must
activate in minutes and be fully rebrandable from the WordPress admin — no code,
no page builder, no build step on the client side.

**Flagship theme:** `lumen-wellness` — a poster-style one-pager for health,
nutrition and wellness coaches. Adapted from a Next.js portfolio design into a
classic PHP theme.

> Ownership note: this is Nilesh Verma's independent project. Do **not** introduce
> any reference to past employers or third-party company names anywhere in code,
> comments, theme headers, commit metadata, or docs. The theme `Author` is
> "Nilesh Verma"; the readme `Contributors` slug is `nileshverma`.

---

## Repository layout

```
wordpress-sites/
├── lumen-wellness/            # The flagship theme (classic PHP theme)
│   ├── style.css              # Theme header + ALL styles (CSS custom properties)
│   ├── functions.php          # Setup, asset enqueue, dynamic colours, SEO, AJAX form
│   ├── front-page.php         # Landing page — loads template parts in order
│   ├── header.php / footer.php
│   ├── index.php / single.php / page.php / 404.php / searchform.php
│   ├── inc/
│   │   ├── customizer.php      # All Customizer sections/settings/controls
│   │   └── template-data.php   # ★ Editable content arrays + lumen_defaults()
│   ├── template-parts/         # hero, marquee, about, services, programs,
│   │                           #   approach, specialties, contact
│   └── assets/js/              # theme.js (front-end), customize-preview.js
├── docker-compose.yml         # Local WordPress (PHP+MySQL) with theme live-mounted
├── DEPLOY-GUIDE.md            # Hostinger deployment walkthrough
├── README.md                  # Product/marketing-style documentation
└── .github/workflows/
    ├── lint.yml               # php -l on every PHP file
    └── preview.yml            # CI-rendered static snapshot → GitHub Pages
```

---

## How to work in this repo

### Local development
```bash
docker compose up -d        # WordPress at http://localhost:8080 (run install wizard)
docker compose down         # stop, keep data
docker compose down -v      # stop, wipe DB
```
The `lumen-wellness/` folder is live-mounted, so edits show on refresh. The AJAX
contact form works here (real PHP + MySQL) — unlike the static Pages preview.

### Package a theme for upload (Hostinger / WP Admin)
The zip must have `style.css` at its top level inside a `lumen-wellness/` folder,
and **use forward-slash paths** (Windows `Compress-Archive` writes backslashes,
which WordPress can choke on). Build it with .NET's ZipArchive forcing `/`:
```powershell
# rebuild lumen-wellness.zip with spec-compliant forward-slash entries
```
> `*.zip` is gitignored — it's a build artifact, never commit it.

### CI
- **lint.yml** runs `php -l` on all theme PHP (the local machine has no PHP, so CI
  is the syntax safety net — keep it green).
- **preview.yml** boots WordPress, activates the theme, crawls the homepage to
  static HTML, and deploys to GitHub Pages on every push to `main`.
  Live: https://nileshvermaa.github.io/wordpress-template/

---

## Conventions & standards (must follow)

- **Classic PHP theme, zero build step.** No Node/Sass/bundler required to ship.
- **Escape on output, sanitize on input.** `esc_html` / `esc_attr` / `esc_url` /
  `wp_kses` for output; `sanitize_*` + nonces for input. Never echo raw `$_POST`.
- **Guard every PHP file** with `if ( ! defined( 'ABSPATH' ) ) { exit; }`.
- **Translation-ready.** Wrap user-facing strings in `__()` / `esc_html_e()` with
  the `lumen-wellness` text domain.
- **Customizer-first.** Anything a client would reasonably want to change belongs in
  `inc/customizer.php`. Repeatable content (services, testimonials, etc.) lives in
  `inc/template-data.php` until ACF/repeaters land (see roadmap).
- **Colours flow through CSS variables** set in `:root` and overridden by the
  Customizer via `wp_add_inline_style`. Don't hardcode hex values in templates.
- **Defer to SEO plugins.** Theme outputs meta/schema only when Yoast/Rank Math is
  absent.
- **Authorship:** theme header `Author: Nilesh Verma`; commits authored as
  `Nilesh Verma <nileshvermaa@users.noreply.github.com>`.

---

## Known limitations / gotchas

- **Contact form needs SMTP.** `wp_mail()` is unreliable on shared hosting — clients
  must install WP Mail SMTP (documented in DEPLOY-GUIDE.md).
- **GitHub Pages preview is a static snapshot** — the design is accurate but the
  contact form won't submit there. Test forms via Docker.
- **Repeatable content is code-edited** today (`inc/template-data.php`). End-clients
  can't change services/testimonials from wp-admin yet → ACF roadmap item.
- **Google Fonts load from the CDN** — a performance + privacy (GDPR) concern;
  self-hosting is on the roadmap.
- **No health/legal disclaimers yet** — important for a health niche (see roadmap P1).

---

## Roadmap — planning ahead

Prioritized. **P0** = do next, **P3** = someday. Effort: S/M/L/XL. Tackle P0/P1
before onboarding paying clients.

### Phase 1 — Harden & polish the flagship (P0–P1) — ✅ DONE (v1.1.0)

| Item | Pri | Status | Notes |
|---|---|---|---|
| **Health/legal disclaimers** — medical disclaimer, testimonial disclosure, privacy + terms. | P0 | ✅ | `inc/legal-content.php` auto-creates 3 pages + a Footer Legal menu on activation; site-wide disclaimer bar with Customizer toggle/text. |
| **Self-host fonts** with `preload` + `font-display: swap`; drop the Google CDN. | P0 | ✅ | Swapped Syne/Inter → **Fraunces + Mulish**, variable woff2 in `assets/fonts/`, generated `assets/css/fonts.css`, latin files preloaded. |
| **Accessibility — WCAG AA contrast** on the palette + small labels. | P1 | ✅ | `--color-muted` darkened (~6:1); small accent labels use `--color-accent-deep`. |
| **PHPCS (WP standards) in CI** alongside `php -l`. | P1 | ✅ (advisory) | `phpcs.xml.dist` (Security + i18n + PHPCompat) + `composer.json` + `.github/workflows/phpcs.yml`. Flip `continue-on-error` to false once clean. |
| **Colour presets** selectable in one Customizer dropdown. | P1 | ✅ | 5 presets (Sage/Ocean/Terracotta/Lavender/Rosewood) + Custom; palette derives `paper-soft`/`line` so it reflows cohesively. |
| **Hero image as LCP** — responsive `srcset`, AVIF/WebP, `fetchpriority`. | P1 | ◻ deferred | `wp_get_attachment_image` already emits srcset + `fetchpriority=high`; AVIF/WebP delivery is a hosting/plugin concern — revisit in Phase 2. |

### Phase 2 — Productize for resale (P1–P2) — ✅ DONE (v1.2.0)

| Item | Pri | Status | Notes |
|---|---|---|---|
| **Client-editable content** — services / programs / testimonials from wp-admin. | P1 | ✅ | CPTs in `inc/post-types.php` (no ACF dependency) with meta boxes; getters map CPT→template shape and fall back to demo arrays; example content seeded on activation. |
| **Demo content** so a fresh install isn't empty. | P2 | ✅ | `lumen_seed_cpt_content()` seeds editable examples on activation (simpler + safer than a WXR importer). |
| **Child-theme support + docs.** | P2 | ✅ | `lumen-wellness-child/` starter (style.css + functions.php). |
| **i18n + translation template (.pot).** | P2 | ✅ (starter) | `languages/lumen-wellness.pot` header; strings already wrapped. Regenerate with `wp i18n make-pot` for full coverage. |
| **Lead-gen integrations** — Calendly, newsletter, WhatsApp. | P2 | ✅ | Calendly inline embed under the form, footer newsletter opt-in (Mailchimp/ConvertKit action URL), WhatsApp floating button — all Customizer-driven. |
| **New navigable pages** — Services archive + single Service/Program. | P2 | ✅ | `archive-lumen_service.php`, `single-lumen_service.php`, `single-lumen_program.php`. |
| **Booking/CTA analytics** (Plausible/GA4 toggle). | P3 | ◻ deferred | Revisit in Phase 3. |

### Phase 3 — Scale to a catalog & a business (P2–P3) — ✅ core done (v1.3.0)

| Item | Pri | Status | Notes |
|---|---|---|---|
| **Shared core via child themes + filters** (instead of duplicated parents). | P2 | ✅ | `lumen_defaults`, `lumen_default_preset`, `lumen_marquee_words` filters. A niche = a tiny child theme. Chose this over a separate parent/block theme — one maintained codebase, many demos. |
| **Example niche variant** to prove the model. | P2 | ✅ | `lumen-wellness-yoga/` rebrands to a yoga studio in ~60 lines of filters. |
| **Versioning + release automation.** | P3 | ✅ | `.github/workflows/release.yml` builds + attaches the theme zips to a GitHub Release on `v*` tags. |
| **Analytics** (privacy-friendly + GA4 toggle). | P3 | ✅ | Plausible + GA4 Customizer fields; snippets skip admins/preview. |
| **Sales/landing site for the themes** (Next.js on Vercel, live demos, pricing). | P2 | ◻ next | Separate project — natural next step now the catalog model exists. |
| **Client onboarding/intake flow** (brand assets, copy, colours). | P3 | ◻ next | Form + checklist to scale done-for-you setup. |
| **Visual regression testing** (Playwright vs Docker/preview). | P3 | ◻ next | Add as the catalog grows and code is shared. |

### Guiding principles for the roadmap
1. **Don't break "zero build step on the client side."** Optional tooling for the
   developer is fine; the shipped theme stays plain PHP/CSS/JS.
2. **Every new feature should be Customizer-controllable** or it doesn't help resale.
3. **Health niche = compliance matters** — keep disclaimers and privacy first-class.
4. **Performance on $2/mo hosting is a feature**, not an afterthought.
