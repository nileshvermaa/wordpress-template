<?php
/**
 * Contact section — heading, working AJAX form and details.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d            = lumen_defaults();
$email        = lumen_opt( 'lumen_email', $d['email'] );
$phone        = lumen_opt( 'lumen_phone', $d['phone'] );
$location     = lumen_opt( 'lumen_location', $d['location'] );
$availability = lumen_opt( 'lumen_availability', $d['availability'] );
?>
<section class="section contact" id="contact">
	<div class="wrap" style="max-width:52rem;">
		<p class="eyebrow reveal"><?php esc_html_e( 'Contact', 'lumen-wellness' ); ?></p>
		<h2 class="big reveal text-gradient"><?php esc_html_e( "Let's begin.", 'lumen-wellness' ); ?></h2>
		<p class="lead reveal" style="margin:0 auto;"><?php echo esc_html( $availability ); ?></p>

		<form class="lumen-form reveal" id="lumen-contact-form" style="margin-top:2.5rem;text-align:left;display:grid;gap:1rem;">
			<div style="display:grid;gap:1rem;grid-template-columns:1fr;">
				<label>
					<span class="screen-reader-text"><?php esc_html_e( 'Your name', 'lumen-wellness' ); ?></span>
					<input type="text" name="name" required placeholder="<?php esc_attr_e( 'Your name', 'lumen-wellness' ); ?>" autocomplete="name" style="width:100%;padding:0.95rem 1.1rem;border:1px solid var(--color-line);border-radius:0.9rem;background:var(--color-paper);font:inherit;color:inherit;">
				</label>
				<label>
					<span class="screen-reader-text"><?php esc_html_e( 'Your email', 'lumen-wellness' ); ?></span>
					<input type="email" name="email" required placeholder="<?php esc_attr_e( 'Your email', 'lumen-wellness' ); ?>" autocomplete="email" style="width:100%;padding:0.95rem 1.1rem;border:1px solid var(--color-line);border-radius:0.9rem;background:var(--color-paper);font:inherit;color:inherit;">
				</label>
				<label>
					<span class="screen-reader-text"><?php esc_html_e( 'Your message', 'lumen-wellness' ); ?></span>
					<textarea name="message" required rows="4" placeholder="<?php esc_attr_e( 'What would you like help with?', 'lumen-wellness' ); ?>" style="width:100%;padding:0.95rem 1.1rem;border:1px solid var(--color-line);border-radius:0.9rem;background:var(--color-paper);font:inherit;color:inherit;resize:vertical;"></textarea>
				</label>
			</div>

			<?php // Honeypot — hidden from humans, catches bots. ?>
			<input type="text" name="company" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;" />

			<div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:center;">
				<button type="submit" class="btn btn-solid"><?php esc_html_e( 'Send message', 'lumen-wellness' ); ?></button>
				<a class="btn btn-ghost" href="mailto:<?php echo esc_attr( $email ); ?>"><?php esc_html_e( 'Or email me', 'lumen-wellness' ); ?></a>
			</div>

			<p class="lumen-form-status" role="status" aria-live="polite" style="margin:0;font-size:0.9rem;"></p>
		</form>

		<div class="contact-meta reveal">
			<?php if ( $email ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
			<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
			<?php endif; ?>
			<?php if ( $location ) : ?>
				<span><?php echo esc_html( $location ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</section>
