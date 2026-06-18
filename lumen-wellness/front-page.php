<?php
/**
 * Front page — the full one-page wellness landing.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/marquee' );
get_template_part( 'template-parts/about' );
get_template_part( 'template-parts/services' );
get_template_part( 'template-parts/programs' );
get_template_part( 'template-parts/approach' );
get_template_part( 'template-parts/specialties' );
get_template_part( 'template-parts/contact' );

get_footer();
