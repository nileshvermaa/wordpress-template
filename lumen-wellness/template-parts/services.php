<?php
/**
 * Services section.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = lumen_get_services();
if ( empty( $services ) ) {
	return;
}
?>
<section class="section" id="services" style="background:var(--color-paper-soft);">
	<div class="wrap">
		<div class="reveal" style="max-width:46rem;">
			<p class="eyebrow"><?php esc_html_e( 'Services', 'lumen-wellness' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Ways we can work together', 'lumen-wellness' ); ?></h2>
			<p class="lead" style="margin-top:1rem;"><?php esc_html_e( 'Every path starts with a conversation. Pick the one that fits where you are today — we tailor the rest.', 'lumen-wellness' ); ?></p>
		</div>

		<div class="svc-list">
			<?php foreach ( $services as $s ) : ?>
				<article class="svc reveal">
					<div>
						<div class="svc-period"><?php echo esc_html( $s['period'] ); ?></div>
						<div class="svc-place"><?php echo esc_html( $s['place'] ); ?></div>
					</div>
					<div>
						<h3><?php echo esc_html( $s['role'] ); ?></h3>
						<p><?php echo esc_html( $s['summary'] ); ?></p>
						<?php if ( ! empty( $s['tags'] ) ) : ?>
							<div class="svc-tags">
								<?php foreach ( $s['tags'] as $tag ) : ?>
									<span class="tag"><?php echo esc_html( $tag ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
