<?php
/**
 * Footer — site footer, socials and closing document.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d              = lumen_defaults();
$copyright      = lumen_opt( 'lumen_copyright', $d['copyright'] );
$socials        = lumen_socials();
$show_disclaimer = lumen_opt( 'lumen_show_disclaimer', true );
$disclaimer     = lumen_opt( 'lumen_disclaimer_text', '' );
?>
</main><!-- #main -->

<?php if ( $show_disclaimer && $disclaimer ) : ?>
	<aside class="disclaimer-bar" role="note">
		<div class="wrap"><p><?php echo esc_html( $disclaimer ); ?></p></div>
	</aside>
<?php endif; ?>

<footer class="site-footer">
	<div class="wrap site-footer-inner">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $copyright ); ?>. <?php esc_html_e( 'All rights reserved.', 'lumen-wellness' ); ?></p>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="site-footer-legal" aria-label="<?php esc_attr_e( 'Legal', 'lumen-wellness' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</nav>
		<?php endif; ?>

		<?php if ( ! empty( $socials ) ) : ?>
			<div class="site-footer-socials">
				<?php foreach ( $socials as $s ) : ?>
					<a href="<?php echo esc_url( $s['href'] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $s['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
