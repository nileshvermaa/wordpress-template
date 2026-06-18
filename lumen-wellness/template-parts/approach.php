<?php
/**
 * Approach (dark band) + testimonials.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps        = lumen_steps();
$testimonials = lumen_testimonials();
?>
<section class="section" id="approach">
	<div class="wrap">
		<?php if ( ! empty( $steps ) ) : ?>
			<div class="band reveal">
				<p class="eyebrow"><?php esc_html_e( 'The approach', 'lumen-wellness' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Simple, kind, and built to last', 'lumen-wellness' ); ?></h2>
				<div class="steps">
					<?php foreach ( $steps as $step ) : ?>
						<div class="step">
							<div class="step-num"><?php echo esc_html( $step['num'] ); ?></div>
							<h3><?php echo esc_html( $step['title'] ); ?></h3>
							<p><?php echo esc_html( $step['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $testimonials ) ) : ?>
			<div class="reveal" style="max-width:46rem;margin-top:5rem;">
				<p class="eyebrow"><?php esc_html_e( 'Client stories', 'lumen-wellness' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Real people, real change', 'lumen-wellness' ); ?></h2>
			</div>
			<div class="quote-grid">
				<?php foreach ( $testimonials as $t ) : ?>
					<figure class="quote reveal">
						<blockquote><p><?php echo esc_html( $t['quote'] ); ?></p></blockquote>
						<figcaption class="who"><?php echo esc_html( $t['name'] ); ?><span><?php echo esc_html( $t['meta'] ); ?></span></figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
