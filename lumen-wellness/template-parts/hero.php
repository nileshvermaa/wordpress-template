<?php
/**
 * Hero section.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d            = lumen_defaults();
$brand_name   = lumen_opt( 'lumen_brand_name', $d['brand_name'] );
$role         = lumen_opt( 'lumen_role', $d['role'] );
$tagline      = lumen_opt( 'lumen_tagline', $d['tagline'] );
$hero_word    = lumen_opt( 'lumen_hero_word', $d['hero_word'] );
$primary_cta  = lumen_opt( 'lumen_primary_cta', $d['primary_cta'] );
$secondary_cta = lumen_opt( 'lumen_secondary_cta', $d['secondary_cta'] );
$booking_url  = lumen_opt( 'lumen_booking_url', $d['booking_url'] );
$socials      = lumen_socials();
$photo_id     = absint( lumen_opt( 'lumen_hero_photo' ) );

/**
 * Tint the inner letters of the hero word with the ink colour, mirroring the
 * poster look (e.g. W E LLN E SS). We highlight every vowel.
 */
$word   = (string) $hero_word;
$marked = '';
$len    = function_exists( 'mb_strlen' ) ? mb_strlen( $word ) : strlen( $word );
for ( $i = 0; $i < $len; $i++ ) {
	$ch = function_exists( 'mb_substr' ) ? mb_substr( $word, $i, 1 ) : substr( $word, $i, 1 );
	if ( preg_match( '/[aeiouAEIOU]/', $ch ) ) {
		$marked .= '<span class="ink">' . esc_html( $ch ) . '</span>';
	} else {
		$marked .= esc_html( $ch );
	}
}
?>
<section class="hero" id="home">
	<span class="hero-wash a" aria-hidden="true"></span>
	<span class="hero-wash b" aria-hidden="true"></span>

	<p class="hero-credit">&copy;<?php echo esc_html( gmdate( 'Y' ) ); ?></p>

	<div class="hero-stage">
		<div class="hero-headline-row">
			<h1 class="hero-word"><?php echo wp_kses( $marked, array( 'span' => array( 'class' => array() ) ) ); ?></h1>

			<div class="hero-photo">
				<div class="hero-photo-frame">
					<?php if ( $photo_id ) : ?>
						<?php
						echo wp_get_attachment_image(
							$photo_id,
							'large',
							false,
							array(
								'alt'           => esc_attr( $brand_name ),
								'fetchpriority' => 'high',
							)
						);
						?>
					<?php else : ?>
						<div class="hero-photo-fallback" aria-hidden="true">
							<?php echo esc_html( mb_substr( $brand_name, 0, 1 ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<p class="hero-role reveal"><?php echo esc_html( $role ); ?></p>
		<p class="hero-tagline reveal"><?php echo esc_html( $tagline ); ?></p>

		<div class="hero-actions reveal">
			<a class="btn btn-solid" href="<?php echo esc_url( $booking_url ); ?>"><?php echo esc_html( $primary_cta ); ?></a>
			<a class="btn btn-ghost" href="#programs"><?php echo esc_html( $secondary_cta ); ?></a>
		</div>
	</div>

	<div class="hero-footer">
		<p>
			<?php esc_html_e( 'Presented by', 'lumen-wellness' ); ?>
			<span class="name"><?php echo esc_html( $brand_name ); ?></span>
		</p>
		<?php if ( ! empty( $socials ) ) : ?>
			<div class="hero-footer-socials">
				<?php foreach ( $socials as $s ) : ?>
					<a href="<?php echo esc_url( $s['href'] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $s['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
