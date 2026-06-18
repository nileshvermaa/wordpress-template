<?php
/**
 * 404 template.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="section contact" style="min-height:60vh;display:flex;align-items:center;">
	<div class="wrap" style="max-width:40rem;">
		<p class="eyebrow"><?php esc_html_e( 'Error 404', 'lumen-wellness' ); ?></p>
		<h1 class="big text-gradient"><?php esc_html_e( 'Lost the path', 'lumen-wellness' ); ?></h1>
		<p class="lead" style="margin:0 auto 2rem;"><?php esc_html_e( "This page took a wellness break. Let's get you back on track.", 'lumen-wellness' ); ?></p>
		<a class="btn btn-solid" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'lumen-wellness' ); ?></a>
	</div>
</section>
<?php
get_footer();
