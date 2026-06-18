# Lumen Wellness — Deployment Guide (Hostinger + WordPress)

This guide takes you from the `lumen-wellness.zip` file to a live, branded
website on Hostinger's cheapest WordPress plan, and explains how all the pieces
connect.

---

## How the pieces fit together

```
  Domain name  ──►  Hostinger hosting  ──►  WordPress  ──►  Lumen Wellness theme
 (yourname.com)     (the server)          (the CMS)        (the design + content)
                          │
                          ├─ MySQL database  (stores pages, settings, form-free content)
                          └─ Email / SMTP     (sends contact-form messages)
```

- **Hostinger** = the computer your site runs on (plus the database).
- **WordPress** = the software that serves pages and gives you the admin panel.
- **Lumen Wellness** = the theme (the look, the layout, the sections) you upload.
- **Customizer** = where your client edits text, colours, photo and contact info.

You are *not* deploying code the way you did on Vercel. With WordPress you
**upload a theme `.zip` once**, then everything is edited live in the browser.

---

## Step 1 — Buy hosting + a domain on Hostinger

1. Go to Hostinger → choose the **Premium Web Hosting** (or Single) plan — the
   cheapest one that lists **WordPress**. The "Web Hosting" plans all run WordPress;
   you do **not** need the pricier "Cloud" tiers for this.
2. During checkout, either register a new domain or connect one you already own.
3. After payment you land in **hPanel** (Hostinger's dashboard).

## Step 2 — Install WordPress (auto-installer)

1. In **hPanel → Websites → Add Website → WordPress**, or use the **Auto Installer**.
2. Hostinger asks for a site title, admin email, admin username and password.
   **Save these** — this is your wp-admin login.
3. It creates the MySQL database for you automatically. Wait ~2 minutes.
4. Visit `https://yourdomain.com/wp-admin` and log in.

> If you bought the domain elsewhere, point its nameservers to Hostinger
> (hPanel shows the exact `ns1.dns-parking.com` style values). DNS can take a
> few hours to propagate.

## Step 3 — Upload the Lumen Wellness theme

1. In wp-admin: **Appearance → Themes → Add New → Upload Theme**.
2. Click **Choose File**, pick **`lumen-wellness.zip`**, then **Install Now**.
3. Click **Activate**.

That's it — open your domain in a new tab and you'll see the wellness landing page
with the demo content already in place.

## Step 4 — Make it the front page (usually automatic)

The theme uses `front-page.php`, so WordPress shows the landing page on the home
URL automatically. If you ever see a blog list instead:

- **Settings → Reading → Your homepage displays →** *A static page*, and pick a
  page — or set it back to *Your latest posts*; either way `front-page.php` wins.

## Step 5 — Rebrand it for the client (no code)

Go to **Appearance → Customize → Lumen Wellness**. You'll find sections for:

- **Brand & Identity** — name, accent word, role, footer name.
- **Colours** — accent, deep accent, ink, paper, blush (pick the client's palette).
- **Hero** — giant word, tagline, button labels, and the **hero photo upload**.
- **About** — intro, philosophy line, three stats.
- **Contact & Booking** — booking link (e.g. a Calendly URL or `#contact`),
  email, phone, location, availability note.
- **Social Links** — Instagram, YouTube, LinkedIn, WhatsApp.

Click **Publish**. Changes are live instantly.

### Editing the repeatable sections

Services, programs, testimonials, specialties and the marquee words are edited in
the file **`inc/template-data.php`** (clearly labelled arrays). Two ways to edit:

- **Before zipping** (recommended for you as the seller): edit the file locally,
  then re-zip.
- **After uploading**: hPanel → **File Manager** →
  `public_html/wp-content/themes/lumen-wellness/inc/template-data.php` → edit → save.

## Step 6 — Make the contact form actually deliver email

WordPress's default `wp_mail()` is unreliable on shared hosting. For dependable
delivery install a free SMTP plugin:

1. **Plugins → Add New →** search **"WP Mail SMTP"** → Install → Activate.
2. Run its setup wizard. Easiest option on Hostinger: create a mailbox in
   hPanel (**Emails**) like `hello@yourdomain.com`, then in WP Mail SMTP choose
   **Other SMTP** and enter Hostinger's mail server settings (host
   `smtp.hostinger.com`, port `465`, SSL, your mailbox user + password).
3. Send a test email from the plugin. Once it passes, the contact form on the
   site delivers to the **Contact → Email** address you set in the Customizer.

> The form already has spam protection (a nonce + a hidden honeypot field), so
> you don't need a captcha to start.

## Step 7 — Final polish

- **Settings → General** — set Site Title and Tagline (used in the browser tab/SEO).
- **Appearance → Customize → Site Identity** — upload a **Site Icon** (favicon).
- **Settings → Permalinks → Post name** — click Save (good URLs + SEO).
- **SSL** — hPanel installs a free SSL certificate automatically; confirm the
  site loads on `https://` and enable "Force HTTPS" in hPanel if offered.
- (Optional) Install **Yoast SEO** — the theme automatically steps aside and lets
  Yoast control meta tags if it detects it.

---

## Updating the design later

Re-zip the theme folder after edits and upload again via **Add New → Upload
Theme** (WordPress will offer to replace the existing copy), **or** edit files
directly in hPanel's File Manager. Customizer settings and uploaded photos are
stored in the database, so they survive theme re-uploads.

## Quick troubleshooting

| Symptom | Fix |
|---|---|
| "Stylesheet is missing" on upload | You zipped the *parent* folder. Zip so that `style.css` is at the top level inside the zip (see packaging note below). |
| Fonts look generic | The Google Fonts request was blocked; check the site isn't in offline/maintenance mode and that nothing is stripping external CSS. |
| Contact form says "sent" but no email | Finish Step 6 (WP Mail SMTP). Shared hosting blocks raw PHP mail. |
| Home page shows blog posts | Settings → Reading, or just confirm the theme is active. |
| Colours didn't change | Re-check Appearance → Customize → Colours and click Publish (hard-refresh the tab). |

---

## Packaging note (for you, the seller)

The uploaded zip must contain the theme folder with `style.css` at its root, i.e.
the zip's top level is the `lumen-wellness/` folder. The `lumen-wellness.zip` in
this directory is already built that way — just upload it as-is.
