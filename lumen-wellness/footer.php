<?php
/**
 * Footer — site footer, socials and closing document.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d         = lumen_defaults();
$copyright = lumen_opt( 'lumen_copyright', $d['copyright'] );
$socials   = lumen_socials();
?>
</main><!-- #main -->

<footer class="site-footer">
	<div class="wrap site-footer-inner">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $copyright ); ?>. <?php esc_html_e( 'All rights reserved.', 'lumen-wellness' ); ?></p>

		<?php if ( ! empty( $socials ) ) : ?>
			<div class="site-footer-socials">
				<?php foreach ( $socials as $s ) : ?>
					<a href="<?php echo esc_url( $s['href'] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $s['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<p class="site-footer-credit"><?php esc_html_e( 'Built with care for your wellbeing.', 'lumen-wellness' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
