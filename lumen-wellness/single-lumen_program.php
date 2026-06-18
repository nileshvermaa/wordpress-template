<?php
/**
 * Single Program.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$platform = get_post_meta( get_the_ID(), '_lumen_platform', true );
	$cta      = get_post_meta( get_the_ID(), '_lumen_cta', true );
	$cta_url  = get_post_meta( get_the_ID(), '_lumen_cta_url', true );
	?>
	<article <?php post_class( 'wrap page-pad section' ); ?> style="max-width:48rem;">
		<header class="reveal">
			<p class="eyebrow"><?php echo esc_html( $platform ? $platform : __( 'Program', 'lumen-wellness' ) ); ?></p>
			<h1 class="section-title"><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="reveal" style="margin:2rem 0;"><?php the_post_thumbnail( 'large', array( 'style' => 'border-radius:1.25rem;width:100%;height:auto;' ) ); ?></div>
		<?php endif; ?>

		<div class="entry reveal" style="margin:1.5rem 0 0;"><?php the_content(); ?></div>

		<div class="reveal" style="margin-top:2.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
			<a class="btn btn-solid" href="<?php echo esc_url( $cta_url ? $cta_url : home_url( '/#contact' ) ); ?>"><?php echo esc_html( $cta ? $cta : __( 'Get started', 'lumen-wellness' ) ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'lumen_program' ) ); ?>"><?php esc_html_e( 'All programs', 'lumen-wellness' ); ?></a>
		</div>
	</article>
	<?php
endwhile;

get_footer();
