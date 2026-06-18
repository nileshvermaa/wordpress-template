<?php
/**
 * About section.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d     = lumen_defaults();
$intro = lumen_opt( 'lumen_about_intro', $d['about_intro'] );
$phil  = lumen_opt( 'lumen_about_phil', $d['about_phil'] );

$stats = array(
	array( lumen_opt( 'lumen_stat1_value', $d['stat1_value'] ), lumen_opt( 'lumen_stat1_label', $d['stat1_label'] ) ),
	array( lumen_opt( 'lumen_stat2_value', $d['stat2_value'] ), lumen_opt( 'lumen_stat2_label', $d['stat2_label'] ) ),
	array( lumen_opt( 'lumen_stat3_value', $d['stat3_value'] ), lumen_opt( 'lumen_stat3_label', $d['stat3_label'] ) ),
);
?>
<section class="section" id="about">
	<div class="wrap about-grid">
		<div class="reveal">
			<p class="eyebrow"><?php esc_html_e( 'About', 'lumen-wellness' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Care that meets you where you are.', 'lumen-wellness' ); ?></h2>
		</div>

		<div class="about-body reveal">
			<p><?php echo esc_html( $intro ); ?></p>
			<p class="philosophy"><?php echo esc_html( $phil ); ?></p>

			<div class="stats">
				<?php foreach ( $stats as $stat ) : ?>
					<div>
						<div class="stat-value"><?php echo esc_html( $stat[0] ); ?></div>
						<div class="stat-label"><?php echo esc_html( $stat[1] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
