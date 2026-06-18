<?php
/**
 * Services archive — /services/.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="section page-pad">
	<div class="wrap">
		<div class="reveal" style="max-width:46rem;">
			<p class="eyebrow"><?php esc_html_e( 'Services', 'lumen-wellness' ); ?></p>
			<h1 class="section-title"><?php esc_html_e( 'Ways we can work together', 'lumen-wellness' ); ?></h1>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="svc-list">
				<?php
				while ( have_posts() ) :
					the_post();
					$period = get_post_meta( get_the_ID(), '_lumen_period', true );
					$place  = get_post_meta( get_the_ID(), '_lumen_place', true );
					$tags   = get_post_meta( get_the_ID(), '_lumen_tags', true );
					?>
					<article class="svc reveal">
						<div>
							<?php if ( $period ) : ?><div class="svc-period"><?php echo esc_html( $period ); ?></div><?php endif; ?>
							<?php if ( $place ) : ?><div class="svc-place"><?php echo esc_html( $place ); ?></div><?php endif; ?>
						</div>
						<div>
							<h2 style="margin-top:0;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), 40 ) ); ?></p>
							<?php if ( $tags ) : ?>
								<div class="svc-tags">
									<?php foreach ( array_map( 'trim', explode( ',', $tags ) ) as $tag ) : ?>
										<span class="tag"><?php echo esc_html( $tag ); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
							<p style="margin-top:1rem;"><a class="prog-cta" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'lumen-wellness' ); ?></a></p>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>
		<?php else : ?>
			<p class="lead"><?php esc_html_e( 'Services are being added soon.', 'lumen-wellness' ); ?></p>
		<?php endif; ?>

		<div class="reveal" style="margin-top:3rem;">
			<a class="btn btn-solid" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Book a free call', 'lumen-wellness' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
