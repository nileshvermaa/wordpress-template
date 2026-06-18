<?php
/**
 * Single Service.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$period = get_post_meta( get_the_ID(), '_lumen_period', true );
	$place  = get_post_meta( get_the_ID(), '_lumen_place', true );
	$tags   = get_post_meta( get_the_ID(), '_lumen_tags', true );
	?>
	<article <?php post_class( 'wrap page-pad section' ); ?> style="max-width:48rem;">
		<header class="reveal">
			<p class="eyebrow"><?php esc_html_e( 'Service', 'lumen-wellness' ); ?></p>
			<h1 class="section-title" style="margin-bottom:0.75rem;"><?php the_title(); ?></h1>
			<p class="svc-place">
				<?php
				echo esc_html( trim( implode( '  ·  ', array_filter( array( $period, $place ) ) ) ) );
				?>
			</p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="reveal" style="margin:2rem 0;"><?php the_post_thumbnail( 'large', array( 'style' => 'border-radius:1.25rem;width:100%;height:auto;' ) ); ?></div>
		<?php endif; ?>

		<div class="entry reveal" style="margin:0;"><?php the_content(); ?></div>

		<?php if ( $tags ) : ?>
			<div class="svc-tags reveal" style="margin-top:1.5rem;">
				<?php foreach ( array_map( 'trim', explode( ',', $tags ) ) as $tag ) : ?>
					<span class="tag"><?php echo esc_html( $tag ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="reveal" style="margin-top:2.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
			<a class="btn btn-solid" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Book a free call', 'lumen-wellness' ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'lumen_service' ) ); ?>"><?php esc_html_e( 'All services', 'lumen-wellness' ); ?></a>
		</div>
	</article>
	<?php
endwhile;

get_footer();
