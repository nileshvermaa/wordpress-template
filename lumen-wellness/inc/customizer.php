<?php
/**
 * Lumen Wellness — Customizer (Appearance → Customize).
 *
 * Every brand-facing string, colour, the hero photo and contact details are
 * exposed here so a site can be rebranded with no code. Values are sanitised
 * on save and escaped again on output.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function lumen_customize_register( $wp_customize ) {
	$d = lumen_defaults();

	/* Small helper to add a text/textarea/url/email/color setting + control. */
	$add = function ( $id, $args ) use ( $wp_customize ) {
		$type      = isset( $args['type'] ) ? $args['type'] : 'text';
		$sanitize  = isset( $args['sanitize'] ) ? $args['sanitize'] : 'sanitize_text_field';

		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $args['default'] ) ? $args['default'] : '',
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);

		if ( 'color' === $type ) {
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$id,
					array(
						'label'   => $args['label'],
						'section' => $args['section'],
					)
				)
			);
		} else {
			$wp_customize->add_control(
				$id,
				array(
					'label'       => $args['label'],
					'description' => isset( $args['description'] ) ? $args['description'] : '',
					'section'     => $args['section'],
					'type'        => $type,
				)
			);
		}
	};

	/* ── Panel ─────────────────────────────────────────────── */
	$wp_customize->add_panel(
		'lumen_panel',
		array(
			'title'    => __( 'Lumen Wellness', 'lumen-wellness' ),
			'priority' => 5,
		)
	);

	/* ── Section: Brand ────────────────────────────────────── */
	$wp_customize->add_section( 'lumen_brand', array( 'title' => __( 'Brand & Identity', 'lumen-wellness' ), 'panel' => 'lumen_panel' ) );
	$add( 'lumen_brand_name', array( 'label' => 'Brand / Name', 'section' => 'lumen_brand', 'default' => $d['brand_name'] ) );
	$add( 'lumen_brand_accent', array( 'label' => 'Accent word (coloured part of logo)', 'section' => 'lumen_brand', 'default' => $d['brand_accent'], 'description' => 'Shown in the accent colour next to the name in the nav.' ) );
	$add( 'lumen_role', array( 'label' => 'Role / Subtitle', 'section' => 'lumen_brand', 'default' => $d['role'] ) );
	$add( 'lumen_copyright', array( 'label' => 'Footer copyright name', 'section' => 'lumen_brand', 'default' => $d['copyright'] ) );

	/* ── Section: Colours ──────────────────────────────────── */
	$wp_customize->add_section( 'lumen_colors', array( 'title' => __( 'Colours', 'lumen-wellness' ), 'panel' => 'lumen_panel' ) );
	$add( 'lumen_color_accent', array( 'label' => 'Accent (primary)', 'section' => 'lumen_colors', 'type' => 'color', 'sanitize' => 'sanitize_hex_color', 'default' => $d['color_accent'] ) );
	$add( 'lumen_color_deep', array( 'label' => 'Accent (deep)', 'section' => 'lumen_colors', 'type' => 'color', 'sanitize' => 'sanitize_hex_color', 'default' => $d['color_deep'] ) );
	$add( 'lumen_color_ink', array( 'label' => 'Ink (text / dark band)', 'section' => 'lumen_colors', 'type' => 'color', 'sanitize' => 'sanitize_hex_color', 'default' => $d['color_ink'] ) );
	$add( 'lumen_color_paper', array( 'label' => 'Paper (background)', 'section' => 'lumen_colors', 'type' => 'color', 'sanitize' => 'sanitize_hex_color', 'default' => $d['color_paper'] ) );
	$add( 'lumen_color_blush', array( 'label' => 'Blush (soft tint)', 'section' => 'lumen_colors', 'type' => 'color', 'sanitize' => 'sanitize_hex_color', 'default' => $d['color_blush'] ) );

	/* ── Section: Hero ─────────────────────────────────────── */
	$wp_customize->add_section( 'lumen_hero', array( 'title' => __( 'Hero', 'lumen-wellness' ), 'panel' => 'lumen_panel' ) );
	$add( 'lumen_hero_word', array( 'label' => 'Giant hero word', 'section' => 'lumen_hero', 'default' => $d['hero_word'], 'description' => 'One word works best (e.g. WELLNESS, VITALITY, THRIVE).' ) );
	$add( 'lumen_tagline', array( 'label' => 'Tagline', 'section' => 'lumen_hero', 'type' => 'textarea', 'default' => $d['tagline'] ) );
	$add( 'lumen_primary_cta', array( 'label' => 'Primary button label', 'section' => 'lumen_hero', 'default' => $d['primary_cta'] ) );
	$add( 'lumen_secondary_cta', array( 'label' => 'Secondary button label', 'section' => 'lumen_hero', 'default' => $d['secondary_cta'] ) );

	$wp_customize->add_setting( 'lumen_hero_photo', array( 'default' => '', 'sanitize_callback' => 'absint', 'transport' => 'refresh' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'lumen_hero_photo',
			array(
				'label'       => __( 'Hero photo', 'lumen-wellness' ),
				'description' => __( 'Portrait orientation (3:4) looks best. Leave empty for a gradient placeholder.', 'lumen-wellness' ),
				'section'     => 'lumen_hero',
				'mime_type'   => 'image',
			)
		)
	);

	/* ── Section: About ────────────────────────────────────── */
	$wp_customize->add_section( 'lumen_about', array( 'title' => __( 'About', 'lumen-wellness' ), 'panel' => 'lumen_panel' ) );
	$add( 'lumen_about_intro', array( 'label' => 'Intro paragraph', 'section' => 'lumen_about', 'type' => 'textarea', 'default' => $d['about_intro'] ) );
	$add( 'lumen_about_phil', array( 'label' => 'Philosophy line', 'section' => 'lumen_about', 'type' => 'textarea', 'default' => $d['about_phil'] ) );
	$add( 'lumen_stat1_value', array( 'label' => 'Stat 1 — value', 'section' => 'lumen_about', 'default' => $d['stat1_value'] ) );
	$add( 'lumen_stat1_label', array( 'label' => 'Stat 1 — label', 'section' => 'lumen_about', 'default' => $d['stat1_label'] ) );
	$add( 'lumen_stat2_value', array( 'label' => 'Stat 2 — value', 'section' => 'lumen_about', 'default' => $d['stat2_value'] ) );
	$add( 'lumen_stat2_label', array( 'label' => 'Stat 2 — label', 'section' => 'lumen_about', 'default' => $d['stat2_label'] ) );
	$add( 'lumen_stat3_value', array( 'label' => 'Stat 3 — value', 'section' => 'lumen_about', 'default' => $d['stat3_value'] ) );
	$add( 'lumen_stat3_label', array( 'label' => 'Stat 3 — label', 'section' => 'lumen_about', 'default' => $d['stat3_label'] ) );

	/* ── Section: Contact ──────────────────────────────────── */
	$wp_customize->add_section( 'lumen_contact', array( 'title' => __( 'Contact & Booking', 'lumen-wellness' ), 'panel' => 'lumen_panel' ) );
	$add( 'lumen_booking_url', array( 'label' => 'Booking link (CTA buttons)', 'section' => 'lumen_contact', 'type' => 'url', 'sanitize' => 'esc_url_raw', 'default' => $d['booking_url'], 'description' => 'Where the “Book” buttons go. Use #contact to scroll to the form, or paste a Calendly/Google-Form link.' ) );
	$add( 'lumen_email', array( 'label' => 'Email', 'section' => 'lumen_contact', 'type' => 'email', 'sanitize' => 'sanitize_email', 'default' => $d['email'] ) );
	$add( 'lumen_phone', array( 'label' => 'Phone', 'section' => 'lumen_contact', 'default' => $d['phone'] ) );
	$add( 'lumen_location', array( 'label' => 'Location', 'section' => 'lumen_contact', 'default' => $d['location'] ) );
	$add( 'lumen_availability', array( 'label' => 'Availability note', 'section' => 'lumen_contact', 'default' => $d['availability'] ) );

	/* ── Section: Social links ─────────────────────────────── */
	$wp_customize->add_section( 'lumen_social', array( 'title' => __( 'Social Links', 'lumen-wellness' ), 'panel' => 'lumen_panel' ) );
	$add( 'lumen_social_instagram', array( 'label' => 'Instagram URL', 'section' => 'lumen_social', 'type' => 'url', 'sanitize' => 'esc_url_raw', 'default' => 'https://instagram.com/' ) );
	$add( 'lumen_social_youtube', array( 'label' => 'YouTube URL', 'section' => 'lumen_social', 'type' => 'url', 'sanitize' => 'esc_url_raw' ) );
	$add( 'lumen_social_linkedin', array( 'label' => 'LinkedIn URL', 'section' => 'lumen_social', 'type' => 'url', 'sanitize' => 'esc_url_raw' ) );
	$add( 'lumen_social_whatsapp', array( 'label' => 'WhatsApp link (https://wa.me/…)', 'section' => 'lumen_social', 'type' => 'url', 'sanitize' => 'esc_url_raw' ) );

	/* Live-preview the parts that are cheap to update. */
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->get_setting( 'lumen_brand_name' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'lumen_hero_word' )->transport   = 'postMessage';
		$wp_customize->get_setting( 'lumen_tagline' )->transport     = 'postMessage';
	}
}
add_action( 'customize_register', 'lumen_customize_register' );

/**
 * Live preview JS for postMessage settings.
 */
function lumen_customize_preview_js() {
	wp_enqueue_script(
		'lumen-customize-preview',
		get_template_directory_uri() . '/assets/js/customize-preview.js',
		array( 'customize-preview' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'customize_preview_init', 'lumen_customize_preview_js' );
