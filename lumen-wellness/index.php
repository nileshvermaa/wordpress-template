<?php
/**
 * Fallback template — blog index & archives.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="wrap page-pad section">
	<?php if ( have_posts() ) : ?>
		<header class="entry">
			<p class="eyebrow"><?php esc_html_e( 'Journal', 'lumen-wellness' ); ?></p>
			<h1 class="section-title"><?php echo esc_html( get_the_archive_title() ? wp_strip_all_tags( get_the_archive_title() ) : get_bloginfo( 'name' ) ); ?></h1>
		</header>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'entry' ); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p class="svc-place"><?php echo esc_html( get_the_date() ); ?></p>
				<div><?php the_excerpt(); ?></div>
				<a class="prog-cta" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'lumen-wellness' ); ?></a>
			</article>
			<?php
		endwhile;

		the_posts_pagination( array( 'mid_size' => 1 ) );
	else :
		?>
		<div class="entry">
			<h1 class="section-title"><?php esc_html_e( 'Nothing here yet', 'lumen-wellness' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Check back soon.', 'lumen-wellness' ); ?></p>
		</div>
	<?php endif; ?>
</div>
<?php
get_footer();
