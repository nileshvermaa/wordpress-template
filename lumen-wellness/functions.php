<?php
/**
 * Lumen Wellness — theme bootstrap.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUMEN_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/template-data.php';
require_once get_template_directory() . '/inc/customizer.php';

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
	) );
}
add_action( 'after_setup_theme', 'lumen_setup' );

/**
 * Fonts + styles + scripts.
 */
function lumen_assets() {
	// Google Fonts (display: swap built in).
	wp_enqueue_style(
		'lumen-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap',
		array(),
		null
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
 * Preconnect to the font CDN for faster first paint.
 */
function lumen_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'lumen_resource_hints', 10, 2 );

/**
 * Inject Customizer colours as CSS variables. Output is escaped.
 */
function lumen_dynamic_css() {
	$d   = lumen_defaults();
	$css = sprintf(
		':root{--color-accent:%s;--color-accent-deep:%s;--color-ink:%s;--color-paper:%s;--color-blush:%s;}',
		sanitize_hex_color( lumen_opt( 'lumen_color_accent', $d['color_accent'] ) ),
		sanitize_hex_color( lumen_opt( 'lumen_color_deep', $d['color_deep'] ) ),
		sanitize_hex_color( lumen_opt( 'lumen_color_ink', $d['color_ink'] ) ),
		sanitize_hex_color( lumen_opt( 'lumen_color_paper', $d['color_paper'] ) ),
		sanitize_hex_color( lumen_opt( 'lumen_color_blush', $d['color_blush'] ) )
	);
	wp_add_inline_style( 'lumen-style', $css );
}
add_action( 'wp_enqueue_scripts', 'lumen_dynamic_css', 20 );

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
}
add_action( 'after_switch_theme', 'lumen_after_switch_theme' );
