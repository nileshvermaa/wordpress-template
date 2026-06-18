<?php
/**
 * Single page template.
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
			<h1 class="section-title"><?php the_title(); ?></h1>
		</header>
		<div class="entry">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
