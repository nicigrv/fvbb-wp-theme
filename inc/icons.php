<?php
/**
 * Kleine SVG-Icons (inline, keine Icon-Schrift nötig).
 *
 * @package fvbb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gibt ein Icon als SVG zurück.
 *
 * @param string $name  Icon-Name.
 * @param int    $size  Größe in px.
 * @return string
 */
function fvbb_icon( $name, $size = 20 ) {
	$paths = array(
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6L6 18"/>',
		'chevron'   => '<path d="M6 9l6 6 6-6"/>',
		'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M4 7l8 6 8-6"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/>',
		'search'    => '<circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/>',
		'calendar'  => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
		'file'      => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/>',
		'download'  => '<path d="M12 4v11M7 10l5 5 5-5M5 20h14"/>',
		'link'      => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
		'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
		'external'  => '<path d="M14 4h6v6M20 4l-9 9M18 14v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4"/>',
		'chat'      => '<path d="M4 5h16v11H9l-5 4z"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="fvbb-icon fvbb-icon--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$paths[ $name ]
	);
}

/**
 * Icon für einen Social-Link anhand der URL bzw. des alten Font-Awesome-Namens.
 *
 * @param string $url  Link.
 * @param string $hint Alter Icon-Name (z. B. fa-instagram).
 * @return string
 */
function fvbb_social_icon_name( $url, $hint = '' ) {
	if ( 0 === strpos( $url, 'mailto:' ) || false !== strpos( $hint, 'envelope' ) ) {
		return 'mail';
	}
	if ( 0 === strpos( $url, 'tel:' ) || false !== strpos( $hint, 'phone' ) ) {
		return 'phone';
	}
	if ( false !== strpos( $url, 'instagram' ) || false !== strpos( $hint, 'instagram' ) ) {
		return 'instagram';
	}
	return 'external';
}
