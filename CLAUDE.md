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

### Phase 1 — Harden & polish the flagship (P0–P1)

| Item | Pri | Effort | Why |
|---|---|---|---|
| **Health/legal disclaimers** — medical disclaimer, testimonial "results not typical" disclosure, privacy policy + cookie consent, terms. Add as Customizer-toggleable footer blocks / sample pages. | P0 | M | Legal protection for Nilesh and clients selling in a health niche; testimonials make unverified-claim risk real. |
| **Self-host fonts** (Inter + Syne) with `preload` + `font-display: swap`; drop the Google CDN request. | P0 | S | Faster LCP on cheap hosting + removes a GDPR third-party data transfer. |
| **Hero image as LCP** — `srcset`/responsive sizes, AVIF/WebP, explicit dimensions, `fetchpriority=high`. | P1 | M | Core Web Vitals; the hero photo is the largest paint. |
| **Accessibility pass to WCAG 2.2 AA** — colour-contrast check on the green palette, focus order, form error messaging, prefers-reduced-motion already done. | P1 | M | Quality bar + reduces client complaints. |
| **PHPCS (WordPress Coding Standards) + theme-check in CI**, alongside existing `php -l`. | P1 | M | Catches escaping/i18n/security issues automatically; marketplace-grade. |
| **More colour presets** (3–5 ready palettes: sage green, calm blue/clinic, warm terracotta, lavender/therapy) selectable in one Customizer dropdown. | P1 | M | Lets one theme serve many sub-niches; big selling point. |

### Phase 2 — Productize for resale (P1–P2)

| Item | Pri | Effort | Why |
|---|---|---|---|
| **ACF-free repeater fields** (or bundle ACF) so clients edit services / programs / testimonials from wp-admin instead of `template-data.php`. | P1 | L | The #1 thing that lets clients self-serve; raises perceived value. |
| **One-click demo content importer** — ship demo content XML + a "Import demo" button so a fresh install looks like the preview instantly. | P2 | M | Removes the empty-site problem; standard for premium themes. |
| **Child-theme support + docs** so client customizations survive updates. | P2 | S | Professional update story. |
| **Full i18n + a translation file (.pot)**; verify every string is wrapped. | P2 | M | Sell internationally; some clients are non-English. |
| **Lead-gen integrations** — Calendly embed block, Mailchimp/newsletter opt-in, WhatsApp click-to-chat, Google Maps for clinics. | P2 | M | These are what wellness clients actually ask for. |
| **Booking/CTA analytics** — lightweight event tracking (Plausible/GA4 optional toggle) on the primary CTAs. | P3 | S | Helps clients see value → renewals/referrals. |

### Phase 3 — Scale to a catalog & a business (P2–P3)

| Item | Pri | Effort | Why |
|---|---|---|---|
| **Shared core / parent theme** (or a small framework) so new niches don't copy-paste `functions.php`. Decide classic-parent vs block-theme (`theme.json`) strategy. | P2 | XL | Avoids N copies drifting; makes the 5th theme cheap to build. |
| **New niche variants** — dentist, yoga studio, physiotherapist, gym/PT, mental-health therapist — each a skin + tailored content. | P2 | L each | Expands the sellable catalog from 1 → many. |
| **Sales/landing site for the themes themselves** (could be a Next.js site on Vercel, reusing Nilesh's existing stack) with live demos and pricing tiers (template vs done-for-you setup). | P2 | L | Turns the repo into a real product line. |
| **Client onboarding/intake flow** — a form + checklist that collects brand assets, copy and colours, so setup is repeatable and fast. | P3 | M | Scales the "done-for-you" revenue without scaling hours. |
| **Versioning + release automation** — tag releases, auto-build the upload zip as a GitHub Release asset, changelog. | P3 | M | Clean handoff + update story per theme. |
| **Visual regression testing** (Playwright against the Docker/preview) to catch design breakage as themes share more code. | P3 | M | Confidence when refactoring the shared core. |

### Guiding principles for the roadmap
1. **Don't break "zero build step on the client side."** Optional tooling for the
   developer is fine; the shipped theme stays plain PHP/CSS/JS.
2. **Every new feature should be Customizer-controllable** or it doesn't help resale.
3. **Health niche = compliance matters** — keep disclaimers and privacy first-class.
4. **Performance on $2/mo hosting is a feature**, not an afterthought.
