<?php
/**
 * Lumen Wellness — theme bootstrap.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUMEN_VERSION', '1.4.0' );

require_once get_template_directory() . '/inc/template-data.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/legal-content.php';
require_once get_template_directory() . '/inc/post-types.php';

/**
 * Theme supports & menus.
 */
function lumen_setup() {
	load_theme_textdomain( 'lumen-wellness', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'lumen-wellness' ),
		'footer'  => __( 'Footer Legal Menu', 'lumen-wellness' ),
	) );
}
add_action( 'after_setup_theme', 'lumen_setup' );

/**
 * Fonts + styles + scripts.
 */
function lumen_assets() {
	// Self-hosted fonts (Fraunces + Mulish) — no third-party request, GDPR-friendly.
	wp_enqueue_style(
		'lumen-fonts',
		get_template_directory_uri() . '/assets/css/fonts.css',
		array(),
		LUMEN_VERSION
	);

	wp_enqueue_style( 'lumen-style', get_stylesheet_uri(), array( 'lumen-fonts' ), LUMEN_VERSION );

	wp_enqueue_script(
		'lumen-script',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		LUMEN_VERSION,
		true
	);

	// Data the front-end JS needs (AJAX endpoint + nonce for the contact form).
	wp_localize_script( 'lumen-script', 'LUMEN', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'lumen_contact' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'lumen_assets' );

/**
 * Preload the primary (latin) font files so the LCP heading/body paint fast.
 */
function lumen_preload_fonts() {
	$base = get_template_directory_uri() . '/assets/fonts/';
	foreach ( array( 'fraunces-latin.woff2', 'mulish-latin.woff2' ) as $f ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $base . $f )
		);
	}
}
add_action( 'wp_head', 'lumen_preload_fonts', 1 );

/**
 * Convert a hex colour to an [r,g,b] array.
 *
 * @param string $hex Hex colour (#rgb or #rrggbb).
 * @return int[] [r, g, b]
 */
function lumen_hex_rgb( $hex ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		return array( 0, 0, 0 );
	}
	return array(
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	);
}

/**
 * Mix a hex colour toward white (positive) or black (negative).
 *
 * @param string $hex    Base colour.
 * @param float  $amount -1..1 (e.g. -0.05 = 5% darker).
 * @return string Hex colour.
 */
function lumen_shade( $hex, $amount ) {
	list( $r, $g, $b ) = lumen_hex_rgb( $hex );
	$target = $amount < 0 ? 0 : 255;
	$a      = abs( $amount );
	$r      = (int) round( $r + ( $target - $r ) * $a );
	$g      = (int) round( $g + ( $target - $g ) * $a );
	$b      = (int) round( $b + ( $target - $b ) * $a );
	return sprintf( '#%02x%02x%02x', $r, $g, $b );
}

/**
 * Resolve the active palette from the chosen preset (or custom pickers).
 *
 * @return array accent, deep, ink, paper, blush (all hex).
 */
function lumen_active_palette() {
	$d       = lumen_defaults();
	/** Filter the fallback preset so niche child themes can set their own. */
	$preset  = lumen_opt( 'lumen_color_preset', apply_filters( 'lumen_default_preset', 'sage' ) );
	$presets = lumen_color_presets();

	if ( 'custom' !== $preset && isset( $presets[ $preset ] ) ) {
		$p = $presets[ $preset ];
		return array(
			'accent' => $p['accent'],
			'deep'   => $p['deep'],
			'ink'    => $p['ink'],
			'paper'  => $p['paper'],
			'blush'  => $p['blush'],
		);
	}

	return array(
		'accent' => lumen_opt( 'lumen_color_accent', $d['color_accent'] ),
		'deep'   => lumen_opt( 'lumen_color_deep', $d['color_deep'] ),
		'ink'    => lumen_opt( 'lumen_color_ink', $d['color_ink'] ),
		'paper'  => lumen_opt( 'lumen_color_paper', $d['color_paper'] ),
		'blush'  => lumen_opt( 'lumen_color_blush', $d['color_blush'] ),
	);
}

/**
 * Inject the active palette as CSS variables, deriving paper-soft and the
 * hairline colour so the whole theme reflows together. Output is escaped.
 */
function lumen_dynamic_css() {
	$p = lumen_active_palette();

	$accent     = sanitize_hex_color( $p['accent'] );
	$deep       = sanitize_hex_color( $p['deep'] );
	$ink        = sanitize_hex_color( $p['ink'] );
	$paper      = sanitize_hex_color( $p['paper'] );
	$blush      = sanitize_hex_color( $p['blush'] );
	$paper_soft = lumen_shade( $paper, -0.045 );
	list( $ir, $ig, $ib ) = lumen_hex_rgb( $ink );

	$css = sprintf(
		':root{--color-accent:%s;--color-accent-deep:%s;--color-ink:%s;--color-paper:%s;--color-paper-soft:%s;--color-blush:%s;--color-line:rgba(%d,%d,%d,0.12);}',
		$accent,
		$deep,
		$ink,
		$paper,
		$paper_soft,
		$blush,
		$ir,
		$ig,
		$ib
	);
	wp_add_inline_style( 'lumen-style', $css );
}
add_action( 'wp_enqueue_scripts', 'lumen_dynamic_css', 20 );

/**
 * Render the primary navigation links — the assigned menu, or in-page anchors
 * as a fallback. Shared by the desktop bar and the mobile panel.
 */
function lumen_nav_links() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'fallback_cb'    => false,
		) );
		return;
	}
	$links = array(
		'#about'    => __( 'About', 'lumen-wellness' ),
		'#services' => __( 'Services', 'lumen-wellness' ),
		'#programs' => __( 'Programs', 'lumen-wellness' ),
		'#approach' => __( 'Approach', 'lumen-wellness' ),
		'#contact'  => __( 'Contact', 'lumen-wellness' ),
	);
	foreach ( $links as $href => $label ) {
		printf( '<a href="%s">%s</a>', esc_attr( $href ), esc_html( $label ) );
	}
}

/**
 * SEO: meta description + Open Graph + Twitter + schema.org JSON-LD.
 * Skips output if an SEO plugin (Yoast / Rank Math) is active.
 */
function lumen_head_meta() {
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return;
	}

	$d           = lumen_defaults();
	$name        = lumen_opt( 'lumen_brand_name', $d['brand_name'] );
	$role        = lumen_opt( 'lumen_role', $d['role'] );
	$tagline     = lumen_opt( 'lumen_tagline', $d['tagline'] );
	$description  = wp_strip_all_tags( $tagline );
	$url         = home_url( '/' );
	$image       = '';

	$photo_id = absint( lumen_opt( 'lumen_hero_photo' ) );
	if ( $photo_id ) {
		$src = wp_get_attachment_image_src( $photo_id, 'large' );
		if ( $src ) {
			$image = $src[0];
		}
	}

	echo "\n<!-- Lumen Wellness SEO -->\n";
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $name . ' — ' . $role ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}
	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );

	// JSON-LD: a wellness professional and their service.
	$sameas = array();
	foreach ( lumen_socials() as $s ) {
		$sameas[] = $s['href'];
	}
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'ProfessionalService',
		'name'     => $name,
		'description' => $description,
		'url'      => $url,
		'image'    => $image ? $image : null,
		'email'    => lumen_opt( 'lumen_email', $d['email'] ),
		'telephone' => lumen_opt( 'lumen_phone', $d['phone'] ),
		'areaServed' => lumen_opt( 'lumen_location', $d['location'] ),
		'priceRange' => '$$',
		'founder'  => array(
			'@type'    => 'Person',
			'name'     => $name,
			'jobTitle' => $role,
		),
		'sameAs'   => array_values( array_filter( $sameas ) ),
	);
	$schema = array_filter( $schema );

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . "</script>\n";
}
add_action( 'wp_head', 'lumen_head_meta', 5 );

/**
 * Output analytics snippets if configured. Plausible is cookieless/privacy-first;
 * GA4 is optional. Skips inside the Customizer preview and for logged-in admins.
 */
function lumen_analytics() {
	if ( is_customize_preview() || current_user_can( 'manage_options' ) ) {
		return;
	}

	$plausible = trim( (string) lumen_opt( 'lumen_plausible_domain', '' ) );
	if ( $plausible ) {
		$domain = preg_replace( '#^https?://#', '', $plausible );
		$domain = trim( $domain, '/' );
		printf(
			'<script defer data-domain="%s" src="https://plausible.io/js/script.js"></script>' . "\n",
			esc_attr( $domain )
		);
	}

	$ga4 = trim( (string) lumen_opt( 'lumen_ga4_id', '' ) );
	if ( $ga4 && preg_match( '/^G-[A-Z0-9]+$/i', $ga4 ) ) {
		printf( '<script async src="https://www.googletagmanager.com/gtag/js?id=%s"></script>' . "\n", esc_attr( $ga4 ) );
		printf(
			'<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config",%s);</script>' . "\n",
			wp_json_encode( $ga4 )
		);
	}
}
add_action( 'wp_head', 'lumen_analytics', 20 );

/* ============================================================
   CONTACT FORM — AJAX handler, nonce + honeypot protected.
   ============================================================ */
function lumen_handle_contact() {
	check_ajax_referer( 'lumen_contact', 'nonce' );

	// Honeypot: real users leave this empty.
	if ( ! empty( $_POST['company'] ) ) {
		wp_send_json_success( array( 'message' => __( 'Thanks! Your message has been sent.', 'lumen-wellness' ) ) );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $name || '' === $message || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter your name, a valid email and a message.', 'lumen-wellness' ) ), 400 );
	}

	$to      = lumen_opt( 'lumen_email', lumen_defaults()['email'] );
	$to      = is_email( $to ) ? $to : get_option( 'admin_email' );
	$subject = sprintf( '[%s] New enquiry from %s', wp_specialchars_decode( get_bloginfo( 'name' ) ), $name );
	$body    = "Name: {$name}\nEmail: {$email}\n\n{$message}\n";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => __( 'Thank you — your message is on its way. I\'ll reply within 1–2 business days.', 'lumen-wellness' ) ) );
	}
	wp_send_json_error( array( 'message' => __( 'Sorry, something went wrong. Please email me directly.', 'lumen-wellness' ) ), 500 );
}
add_action( 'wp_ajax_lumen_contact', 'lumen_handle_contact' );
add_action( 'wp_ajax_nopriv_lumen_contact', 'lumen_handle_contact' );

/**
 * Use the bundled landing page as the site front page automatically on
 * first activation, so the buyer sees the design immediately.
 */
function lumen_after_switch_theme() {
	if ( 'posts' === get_option( 'show_on_front' ) ) {
		// front-page.php already takes priority; nothing required, but we keep
		// reading settings sane for users who later add a static page.
		update_option( 'posts_per_page', 9 );
	}

	// Scaffold the health/legal pages + footer menu (runs once).
	lumen_create_legal_pages();

	// Seed editable example Services / Programs / Testimonials (runs once),
	// and flag rewrite rules to flush on the next init so CPT archives resolve.
	lumen_seed_cpt_content();
	update_option( 'lumen_flush_needed', 1 );
}
add_action( 'after_switch_theme', 'lumen_after_switch_theme' );
