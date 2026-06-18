<?php
/**
 * Search form.
 *
 * @package Lumen_Wellness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'lumen-wellness' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search…', 'lumen-wellness' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="btn btn-solid"><?php esc_html_e( 'Search', 'lumen-wellness' ); ?></button>
</form>
