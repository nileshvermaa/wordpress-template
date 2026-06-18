<?php
/**
 * Programs / showcase section.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$programs = lumen_get_programs();
if ( empty( $programs ) ) {
	return;
}
?>
<section class="section" id="programs">
	<div class="wrap">
		<div class="reveal" style="max-width:46rem;">
			<p class="eyebrow"><?php esc_html_e( 'Programs', 'lumen-wellness' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Pick a place to begin', 'lumen-wellness' ); ?></h2>
		</div>

		<div class="prog-grid">
			<?php foreach ( $programs as $p ) : ?>
				<article class="prog reveal">
					<div class="prog-index"><?php echo esc_html( $p['index'] ); ?></div>
					<div class="prog-platform"><?php echo esc_html( $p['platform'] ); ?></div>
					<h3><?php echo esc_html( $p['title'] ); ?></h3>
					<p><?php echo esc_html( $p['desc'] ); ?></p>
					<a class="prog-cta" href="<?php echo esc_url( $p['href'] ); ?>"><?php echo esc_html( $p['cta'] ); ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
