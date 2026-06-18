<?php
/**
 * Lumen Yoga — a niche variant of Lumen Wellness.
 *
 * This whole file is the "new niche": it only filters the parent theme's
 * defaults, colour preset and marquee. Everything else (templates, CPTs,
 * legal pages, lead-gen, contact form) is inherited. This is the catalog
 * model — sell many niches from one maintained codebase.
 *
 * @package Lumen_Yoga
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load parent + child stylesheets in order.
 */
function lumen_yoga_enqueue() {
	wp_enqueue_style(
		'lumen-parent-style',
		get_template_directory_uri() . '/style.css',
		array( 'lumen-fonts' ),
		wp_get_theme( get_template() )->get( 'Version' )
	);
	wp_enqueue_style(
		'lumen-yoga-style',
		get_stylesheet_uri(),
		array( 'lumen-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'lumen_yoga_enqueue', 30 );

/**
 * Default to the calm lavender palette for this niche.
 */
add_filter( 'lumen_default_preset', function () {
	return 'lavender';
} );

/**
 * Rebrand the copy for a yoga / breathwork studio.
 *
 * @param array $defaults Parent defaults.
 * @return array
 */
add_filter( 'lumen_defaults', function ( $defaults ) {
	return array_merge( $defaults, array(
		'brand_name'    => 'Maya Iyer',
		'brand_accent'  => 'Yoga',
		'hero_word'     => 'BREATHE',
		'role'          => 'Yoga & Breathwork Teacher',
		'tagline'       => 'Move slower, breathe deeper, feel at home in your body again.',
		'availability'  => 'New term of classes now open',
		'primary_cta'   => 'Book a class',
		'secondary_cta' => 'See offerings',
		'about_intro'   => "I'm a yoga and breathwork teacher who believes the mat is a place to come home to — not to perform. For over a decade I've guided beginners and tired bodies toward strength, stillness and breath that steadies the whole day.",
		'about_phil'    => 'Yoga isn\'t about touching your toes. It\'s about what you learn on the way down.',
		'stat1_value'   => '12+', 'stat1_label' => 'Years teaching on and off the mat',
		'stat2_value'   => '4k+', 'stat2_label' => 'Classes guided',
		'stat3_value'   => '3',   'stat3_label' => 'Yoga & breathwork certifications',
		'copyright'     => 'Maya Iyer Yoga',
	) );
} );

/**
 * Yoga-flavoured marquee.
 *
 * @return string[]
 */
add_filter( 'lumen_marquee_words', function () {
	return array( 'Vinyasa Flow', 'Breathwork', 'Yin & Restore', 'Beginners Welcome', 'Mobility', 'Meditation', 'Prenatal Yoga', 'Mindful Movement' );
} );
