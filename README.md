# WordPress Theme Templates

Production-grade, one-page WordPress themes built for service professionals — health coaches, consultants, creatives and practitioners. Designed to be sold to clients on Hostinger (or any shared host), activated in minutes, and rebranded without touching code.

[![Lint](https://github.com/nileshvermaa/wordpress-template/actions/workflows/lint.yml/badge.svg)](https://github.com/nileshvermaa/wordpress-template/actions/workflows/lint.yml)
[![Theme Preview](https://github.com/nileshvermaa/wordpress-template/actions/workflows/preview.yml/badge.svg)](https://github.com/nileshvermaa/wordpress-template/actions/workflows/preview.yml)

**🔗 Live preview:** https://nileshvermaa.github.io/wordpress-template/

> The live preview is a **static snapshot** of the theme rendered in a real WordPress
> install by CI on every push. The design is pixel-accurate; server-side features
> (the AJAX contact form) don't submit in the snapshot — test those locally with Docker
> (see [Local development](#local-development)).

---

## Themes included

| Theme | Industry | Palette | Status |
|---|---|---|---|
| [`lumen-wellness`](lumen-wellness/) | Health, nutrition & wellness coaching | Sage / Ocean / Terracotta / Lavender / Rosewood (switchable) | ✅ v1.3.0 |
| [`lumen-wellness-yoga`](lumen-wellness-yoga/) | Yoga / breathwork studio (example variant) | Lavender | ✅ v1.0.0 |
| [`lumen-wellness-child`](lumen-wellness-child/) | Blank child theme for client customizations | — | ✅ v1.0.0 |

> **Variants are child themes, not forks.** A new niche overrides the parent's defaults, colour preset and copy through filters (see [`lumen-wellness-yoga/functions.php`](lumen-wellness-yoga/functions.php)) — one maintained codebase, many sellable demos.

### Releases

Tagging a version (`git tag v1.3.0 && git push --tags`) triggers [`release.yml`](.github/workflows/release.yml), which builds the upload-ready theme zips and attaches them to a [GitHub Release](https://github.com/nileshvermaa/wordpress-template/releases).

---

## Lumen Wellness

A poster-style, conversion-focused one-page theme for integrative health coaches, nutritionists, therapists and wellness practitioners.

### Design language

Inspired by editorial poster design — a giant display word punched through by a framed portrait, a scrolling marquee, reveal-on-scroll sections, film-grain texture, and pill-shaped CTAs. Fraunces (headings) + Mulish (body), self-hosted for speed and privacy. Switch the whole palette in one click with built-in colour presets (Sage, Ocean, Terracotta, Lavender, Rosewood) or go fully custom — all from the WordPress Customizer.

### Page sections

`Hero` → `Marquee` → `About + Stats` → `Services` → `Programs` → `Approach + Testimonials` → `Specialties` → `Contact`

### What makes it production-ready

- **Full Customizer control** — brand name, colours (5 CSS variables), hero photo, all copy, booking link, contact details and social links. Client can rebrand without a developer.
- **Working contact form** — AJAX submission, WordPress nonce validation, honeypot spam protection, delivers via `wp_mail()`.
- **SEO out of the box** — `<meta description>`, Open Graph, Twitter card tags, and a `schema.org/ProfessionalService` JSON-LD block. Steps aside automatically if Yoast SEO or Rank Math is detected.
- **Accessibility** — skip-to-content link, semantic landmark regions (`<header>`, `<main>`, `<footer>`, `<nav>`, `<section>`, `<article>`, `<figure>`), `:focus-visible` outlines, `prefers-reduced-motion` compliance, screen-reader labels on all inputs.
- **Client-editable content** — Services, Programs and Testimonials are custom post types editable from wp-admin (no ACF, no code); the one-pager falls back to demo content until they're filled in.
- **Dedicated pages** — a Services archive plus single Service/Program templates, in addition to the one-page front.
- **Lead-gen built in** — optional Calendly embed, footer newsletter opt-in (Mailchimp/ConvertKit), and a WhatsApp floating button, all toggled from the Customizer.
- **Child theme included** — `lumen-wellness-child/` for customizations that survive updates.
- **No page builder, no build step** — pure PHP + CSS + vanilla JS. Upload the zip, activate. Done.
- **Fallback templates** — `index.php`, `single.php`, `page.php`, `404.php`, `searchform.php` keep the theme functional for blog posts and inner pages.

### File structure

```
lumen-wellness/
├── style.css                    # Theme header + all styles (CSS custom properties)
├── functions.php                # Theme setup, asset loading, dynamic colour injection,
│                                #   SEO meta, AJAX contact handler
├── front-page.php               # Landing page — loads template parts in order
├── header.php                   # <head>, skip link, sticky nav
├── footer.php                   # Site footer, socials, wp_footer()
├── index.php                    # Blog / archive fallback
├── single.php                   # Single post fallback
├── page.php                     # Static page fallback
├── 404.php                      # Not-found page
├── searchform.php               # Search form
├── screenshot.png               # Theme preview in WP admin
├── readme.txt                   # WordPress.org–style readme
│
├── inc/
│   ├── template-data.php        # ★ All editable content: services, programs,
│   │                            #   testimonials, specialties, marquee words
│   └── customizer.php           # Customizer sections, settings and controls
│
├── template-parts/
│   ├── hero.php
│   ├── marquee.php
│   ├── about.php
│   ├── services.php
│   ├── programs.php
│   ├── approach.php             # How-it-works steps + testimonials
│   ├── specialties.php
│   └── contact.php              # AJAX contact form
│
└── assets/
    └── js/
        ├── theme.js             # Reveal-on-scroll, sticky nav, smooth scroll, form AJAX
        └── customize-preview.js # Live Customizer postMessage updates
```

---

## Quick deployment (Hostinger)

> Full step-by-step with screenshots guidance and troubleshooting is in [DEPLOY-GUIDE.md](DEPLOY-GUIDE.md).

1. **Buy** any Hostinger Web Hosting plan (the cheapest tier works) and add a domain.
2. **Install WordPress** from hPanel's auto-installer — it creates the database automatically.
3. **Upload the theme**: WP Admin → Appearance → Themes → Add New → Upload Theme → `lumen-wellness.zip` → Install → Activate.
4. **Rebrand**: Appearance → Customize → Lumen Wellness → set name, colours, hero photo, copy, booking link, socials → Publish.
5. **Wire up email**: install the free **WP Mail SMTP** plugin → connect your Hostinger mailbox (SMTP host: `smtp.hostinger.com`, port `465`, SSL). This is required — shared hosting blocks raw PHP mail.

The landing page is live at your domain the moment the theme is activated.

---

## Local development

Run a full, real WordPress (PHP + MySQL) on your machine with Docker — the
contact form and everything else works exactly as it will on Hostinger.

```bash
docker compose up -d
# open http://localhost:8080 and run the 2-minute WordPress install wizard
# then WP Admin → Appearance → Themes → activate "Lumen Wellness"
```

The `lumen-wellness/` folder is mounted live, so edits on disk appear on refresh.

```bash
docker compose down       # stop (keeps your data)
docker compose down -v    # stop and wipe the database for a clean start
```

## Continuous integration & preview

| Workflow | Trigger | What it does |
|---|---|---|
| [`lint.yml`](.github/workflows/lint.yml) | every push / PR | Runs `php -l` on every PHP file — catches syntax errors before they ship |
| [`preview.yml`](.github/workflows/preview.yml) | push to `main` | Boots WordPress in CI, activates the theme, crawls the homepage to static HTML, deploys to GitHub Pages |

**One-time setup to enable the live preview:**

1. Push these files to GitHub (already done if you're reading this on the repo).
2. Repo **Settings → Pages → Build and deployment → Source → GitHub Actions**.
3. The next push to `main` publishes the preview to
   `https://nileshvermaa.github.io/wordpress-template/`.

## Customizing content

### Via the WordPress Customizer (no code)

`Appearance → Customize → Lumen Wellness` exposes:

| Section | What you can change |
|---|---|
| Brand & Identity | Name, accent word (coloured part of logo), role, footer name |
| Colours | Accent, deep accent, ink, background, blush tint |
| Hero | Giant word, tagline, primary and secondary CTA labels |
| Hero photo | Upload a portrait (3:4 ratio recommended) |
| About | Intro paragraph, philosophy quote, three stats |
| Contact & Booking | Booking URL (Calendly / Google Form / `#contact`), email, phone, location, availability note |
| Social Links | Instagram, YouTube, LinkedIn, WhatsApp |

### Repeatable sections (one file, clearly labelled)

Edit [`lumen-wellness/inc/template-data.php`](lumen-wellness/inc/template-data.php) to change:

- `lumen_services()` — the service/offering cards
- `lumen_programs()` — the featured program tiles
- `lumen_steps()` — the "how it works" numbered steps
- `lumen_testimonials()` — client quotes
- `lumen_specialties()` — skills grouped by pillar
- `lumen_marquee_words()` — the scrolling ticker text

Each function has a clearly labelled array. Edit the values, save, and re-upload (or edit via hPanel File Manager — no build step needed).

### Reselling to a new client

1. Update the content in `template-data.php` for the new client.
2. Adjust colours and copy in the Customizer (or set new defaults in `inc/template-data.php`'s `lumen_defaults()` function).
3. Repackage: zip the `lumen-wellness/` folder so `style.css` is at the top level.
4. Upload to the client's WordPress install.

The theme name, Text Domain, and version can be bumped in `style.css`'s header comment for each new client if you want separate installs.

---

## Requirements

| | Minimum |
|---|---|
| WordPress | 6.0 |
| PHP | 7.4 |
| Tested up to | WordPress 6.7 |

No third-party plugins required to run. Recommended additions:

- **WP Mail SMTP** (free) — reliable email delivery from the contact form.
- **Yoast SEO** or **Rank Math** (optional) — the theme defers its own meta tags automatically if either is active.

---

## Contributing / extending

Pull requests welcome for new industry variants. When adding a new theme:

1. Create a new top-level folder (e.g. `lumen-clinic/`).
2. Keep the same file structure.
3. Add a row to the themes table in this README.
4. Include a `screenshot.png` (1200×900px).

---

## License

[GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

---

*Built with care. Designed to sell.*
