<?php
/**
 * Suchformular.
 *
 * @package fvbb
 */
$fvbb_sid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $fvbb_sid ); ?>"><?php esc_html_e( 'Suche nach', 'fvbb' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $fvbb_sid ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Suchen …', 'fvbb' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	<button type="submit" class="search-submit"><?php echo fvbb_icon( 'search', 18 ); // phpcs:ignore ?><span class="screen-reader-text"><?php esc_html_e( 'Suchen', 'fvbb' ); ?></span></button>
</form>
