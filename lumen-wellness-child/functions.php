<?php
/**
 * Lumen Wellness Child — functions.
 *
 * Put client-specific PHP here. Override any parent template by copying the file
 * (e.g. template-parts/hero.php) into this child folder at the same path.
 *
 * @package Lumen_Wellness_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the parent and child stylesheets in the right order.
 */
function lumen_child_enqueue() {
	wp_enqueue_style(
		'lumen-parent-style',
		get_template_directory_uri() . '/style.css',
		array( 'lumen-fonts' ),
		wp_get_theme( get_template() )->get( 'Version' )
	);
	wp_enqueue_style(
		'lumen-child-style',
		get_stylesheet_uri(),
		array( 'lumen-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'lumen_child_enqueue', 30 );
