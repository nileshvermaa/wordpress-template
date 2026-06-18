<?php
/**
 * Specialties (skills) section.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$groups = lumen_specialties();
if ( empty( $groups ) ) {
	return;
}
?>
<section class="section" id="specialties" style="background:var(--color-paper-soft);">
	<div class="wrap">
		<div class="reveal" style="max-width:46rem;">
			<p class="eyebrow"><?php esc_html_e( 'Specialties', 'lumen-wellness' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'What I bring to your corner', 'lumen-wellness' ); ?></h2>
		</div>

		<div class="spec-grid">
			<?php foreach ( $groups as $group ) : ?>
				<div class="spec-group reveal">
					<h3><?php echo esc_html( $group['group'] ); ?></h3>
					<ul>
						<?php foreach ( $group['items'] as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
