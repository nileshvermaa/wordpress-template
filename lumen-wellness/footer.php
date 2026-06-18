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
$news_action    = lumen_opt( 'lumen_newsletter_action', '' );
$news_text      = lumen_opt( 'lumen_newsletter_text', '' );
$whatsapp       = lumen_opt( 'lumen_social_whatsapp', '' );
?>
</main><!-- #main -->

<?php if ( $news_action ) : ?>
	<section class="newsletter" aria-label="<?php esc_attr_e( 'Newsletter signup', 'lumen-wellness' ); ?>">
		<div class="wrap newsletter-inner">
			<p class="newsletter-text"><?php echo esc_html( $news_text ); ?></p>
			<form class="newsletter-form" action="<?php echo esc_url( $news_action ); ?>" method="post" target="_blank" rel="noopener">
				<label class="screen-reader-text" for="lumen-news-email"><?php esc_html_e( 'Email address', 'lumen-wellness' ); ?></label>
				<input id="lumen-news-email" type="email" name="EMAIL" required placeholder="<?php esc_attr_e( 'you@email.com', 'lumen-wellness' ); ?>" />
				<button type="submit" class="btn btn-solid"><?php esc_html_e( 'Subscribe', 'lumen-wellness' ); ?></button>
			</form>
		</div>
	</section>
<?php endif; ?>

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

<?php if ( $whatsapp ) : ?>
	<a class="whatsapp-fab" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'lumen-wellness' ); ?>">
		<svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true" focusable="false">
			<path fill="currentColor" d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.2 1.6 6L4 29l8.2-1.6c1.7.9 3.6 1.4 5.6 1.4h.2c6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.3l-.4-.2-4.3.8.8-4.2-.3-.4c-.9-1.5-1.4-3.2-1.4-5C5.4 9.5 10.1 4.8 16 4.8c2.9 0 5.5 1.1 7.5 3.1s3.1 4.7 3.1 7.5c0 5.9-4.7 10.4-10.6 10.4zm6-7.7c-.3-.2-1.9-1-2.2-1.1-.3-.1-.5-.2-.8.2-.2.3-.8 1.1-1 1.3-.2.2-.4.2-.7.1-1.9-1-3.1-1.7-4.4-3.8-.3-.6.3-.5.9-1.7.1-.2 0-.4 0-.5-.1-.2-.8-1.8-1-2.5-.3-.6-.5-.5-.8-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.2.2 2.2 3.3 5.3 4.7 2 .8 2.7.9 3.7.8.6-.1 1.9-.8 2.2-1.5.3-.7.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/>
		</svg>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
