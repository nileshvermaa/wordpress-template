=== Lumen Wellness ===
Contributors: nileshverma
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A poster-style, one-page theme for health, wellness and coaching brands.

== Description ==

Lumen Wellness is a conversion-focused, single-page theme for nutritionists,
health coaches, therapists, clinics and wellness practitioners. It ships with:

* A poster-style hero with a giant headline word and a framed portrait.
* About, Services, Programs, Approach, Testimonials, Specialties and Contact sections.
* A working contact form (AJAX, nonce + honeypot spam protection, emails via wp_mail).
* Full Customizer control: brand name, colours, hero photo, all key copy, contact
  details and social links — no code required.
* SEO out of the box: meta description, Open Graph/Twitter tags and schema.org
  JSON-LD (ProfessionalService). Defers to Yoast/Rank Math if installed.
* Accessibility: skip link, semantic landmarks, focus states, reduced-motion support.
* No page builder and no build step — pure PHP/CSS/vanilla JS.

== Installation ==

1. In wp-admin go to Appearance → Themes → Add New → Upload Theme.
2. Choose lumen-wellness.zip and click Install Now, then Activate.
3. Go to Appearance → Customize → Lumen Wellness to set your brand, colours,
   hero photo, copy and contact details.
4. The landing page renders automatically on your site's front page.

== Editing repeatable content ==

Services, programs, testimonials, specialties and the marquee words live in
inc/template-data.php — edit the clearly-labelled arrays there (one block each).

== Changelog ==

= 1.4.0 =
* Mobile/tablet navigation: accessible hamburger menu (aria-expanded, Esc to close, closes on link tap/resize) — previously there was no nav below 900px.
* Responsive hardening: fluid hero word (clamp) and photo, dynamic viewport height (svh), smoother 1→2→3 column grids for tablets, tighter spacing on small phones.
* Fixed potential horizontal overflow (images now height:auto; Calendly embed is fluid, not min-width:320px).

= 1.3.1 =
* Update demo brand identity to “Shivani Kamra Wellness” (refreshed screenshot). Brand name is editable in the Customizer.

= 1.3.0 =
* Niche-variant system: brand defaults, default colour preset and marquee are now filterable, so a new niche is a tiny child theme (see the included Lumen Yoga example) instead of a duplicated codebase.
* Analytics: optional Plausible (cookieless) and Google Analytics 4 fields in the Customizer; snippets skip admins and the Customizer preview.

= 1.2.0 =
* Client-editable content via custom post types: Services, Programs, Testimonials — edit from wp-admin, no code. The front page uses them when present and falls back to demo content otherwise.
* Editable example content is seeded on activation so wp-admin isn't empty.
* New pages: a Services archive (/services/) and single Service/Program templates.
* Lead-gen: optional Calendly embed under the contact form, footer newsletter opt-in (Mailchimp/ConvertKit), and a WhatsApp floating button.
* Child theme starter included for safe customizations.
* Translation template (.pot) added.

= 1.1.0 =
* New self-hosted font system: Fraunces (headings) + Mulish (body), preloaded — no third-party font request.
* Readability pass: larger body text, looser line-height, optical-sizing, refined heading tracking.
* WCAG AA contrast fixes for muted text and small accent labels.
* Colour presets: Sage, Ocean, Terracotta, Lavender, Rosewood, or Custom — switch the whole palette in one click.
* Health/legal pages auto-created on activation: Medical Disclaimer, Privacy Policy, Terms (+ a Footer Legal menu).
* New site-wide wellness disclaimer bar (toggle + text in the Customizer).

= 1.0.0 =
* Initial release.

== Credits ==

* Fonts: Fraunces and Mulish (SIL Open Font License), self-hosted with the theme.
