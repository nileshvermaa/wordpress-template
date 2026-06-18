<?php
/**
 * Marquee strip.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$words = lumen_marquee_words();
if ( empty( $words ) ) {
	return;
}
// Duplicate the set so the CSS -50% loop is seamless.
$loop = array_merge( $words, $words );
?>
<div class="marquee" aria-hidden="true">
	<div class="marquee-track">
		<?php foreach ( $loop as $w ) : ?>
			<span><?php echo esc_html( $w ); ?></span>
		<?php endforeach; ?>
	</div>
</div>
