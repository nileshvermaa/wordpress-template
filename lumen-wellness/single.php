<?php
/**
 * Single post template.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'wrap page-pad section' ); ?>>
		<header class="entry">
			<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
			<h1 class="section-title"><?php the_title(); ?></h1>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry"><?php the_post_thumbnail( 'large', array( 'style' => 'border-radius:1.25rem;' ) ); ?></div>
		<?php endif; ?>
		<div class="entry">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="wrap"><div class="entry">';
		comments_template();
		echo '</div></div>';
	}
endwhile;

get_footer();
