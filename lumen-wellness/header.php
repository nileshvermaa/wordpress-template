<?php
/**
 * Header — opening document, skip link and fixed nav.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$d           = lumen_defaults();
$brand_name  = lumen_opt( 'lumen_brand_name', $d['brand_name'] );
$brand_accent = lumen_opt( 'lumen_brand_accent', $d['brand_accent'] );
$booking_url = lumen_opt( 'lumen_booking_url', $d['booking_url'] );
$primary_cta = lumen_opt( 'lumen_primary_cta', $d['primary_cta'] );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'grain' ); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'lumen-wellness' ); ?></a>

<header class="nav" id="site-nav">
	<div class="nav-inner">
		<a class="nav-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $brand_name ); ?>">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo esc_html( $brand_name ) . ' <span>' . esc_html( $brand_accent ) . '</span>';
			}
			?>
		</a>

		<nav class="nav-links" aria-label="<?php esc_attr_e( 'Primary', 'lumen-wellness' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
			} else {
				// Default in-page anchors.
				$links = array(
					'#about'        => __( 'About', 'lumen-wellness' ),
					'#services'     => __( 'Services', 'lumen-wellness' ),
					'#programs'     => __( 'Programs', 'lumen-wellness' ),
					'#approach'     => __( 'Approach', 'lumen-wellness' ),
					'#contact'      => __( 'Contact', 'lumen-wellness' ),
				);
				foreach ( $links as $href => $label ) {
					printf( '<a href="%s">%s</a>', esc_attr( $href ), esc_html( $label ) );
				}
			}
			?>
		</nav>

		<a class="btn btn-solid nav-cta" href="<?php echo esc_url( $booking_url ); ?>"><?php echo esc_html( $primary_cta ); ?></a>
	</div>
</header>

<main id="main">
