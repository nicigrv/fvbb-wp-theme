<?php
/**
 * Customizer-Einstellungen (Design → Anpassen → "Förderverein").
 * Standardwerte werden aus den alten Einstellungen von EmpowerWP/Mesmerize übernommen,
 * damit nach dem Theme-Wechsel alles wie vorher aussieht (Logo, Kopfbild, Titel,
 * Instagram-Button, Symbole im Footer).
 *
 * @package fvbb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Einstellungen der alten Themes (EmpowerWP ist ein Child-Theme von Mesmerize).
 * WordPress speichert sie pro Theme – nach dem Wechsel liegen sie noch in der Datenbank.
 *
 * @return array
 */
function fvbb_legacy_mods() {
	static $mods = null;
	if ( null !== $mods ) {
		return $mods;
	}
	$mods = array();
	foreach ( apply_filters( 'fvbb_legacy_themes', array( 'mesmerize', 'empowerwp' ) ) as $slug ) {
		$m = get_option( 'theme_mods_' . $slug );
		if ( is_array( $m ) ) {
			$mods = array_merge( $mods, $m );
		}
	}
	return $mods;
}

/**
 * Einstellung lesen: zuerst die eigene, sonst die aus dem alten Theme.
 *
 * @param string $key     Name.
 * @param mixed  $default Rückfall.
 * @return mixed
 */
function fvbb_legacy_mod( $key, $default = '' ) {
	$own = get_theme_mod( $key, null );
	if ( null !== $own && '' !== $own && array() !== $own ) {
		return $own;
	}
	$legacy = fvbb_legacy_mods();
	return array_key_exists( $key, $legacy ) ? $legacy[ $key ] : $default;
}

/* Logo aus dem alten Theme übernehmen, solange keins gesetzt ist */
add_filter( 'theme_mod_custom_logo', function ( $value ) {
	if ( $value ) {
		return $value;
	}
	$legacy = fvbb_legacy_mods();
	return ! empty( $legacy['custom_logo'] ) ? (int) $legacy['custom_logo'] : $value;
} );

/**
 * Untertitel aus dem alten Theme – nur wenn er dort auch angezeigt wurde.
 * Mesmerize speichert einen Standard-Untertitel, blendet ihn aber aus, solange er nicht eingeschaltet ist.
 *
 * @return string
 */
function fvbb_legacy_subtitle() {
	$legacy = fvbb_legacy_mods();
	$text   = isset( $legacy['header_subtitle'] ) ? trim( wp_strip_all_tags( (string) $legacy['header_subtitle'] ) ) : '';
	if ( '' === $text ) {
		return '';
	}
	foreach ( $legacy as $key => $val ) {
		if ( preg_match( '/subtitle.*(show|enabled|visible)|(show|enabled|visible).*subtitle/', (string) $key ) ) {
			return $val ? $text : '';
		}
	}
	return ''; // Kein Schalter gefunden: wie bei Mesmerize ausgeblendet.
}

/**
 * Bild-ID aus einem Wert (ID oder URL).
 *
 * @param mixed $val Wert.
 * @return int
 */
function fvbb_image_id( $val ) {
	if ( is_array( $val ) ) {
		$val = $val['id'] ?? ( $val['url'] ?? '' );
	}
	if ( is_numeric( $val ) ) {
		return (int) $val;
	}
	if ( is_string( $val ) && '' !== $val ) {
		$id = attachment_url_to_postid( $val );
		if ( ! $id ) {
			// http/https und Größen-Suffix (-1024x768) ignorieren.
			$alt = preg_replace( '/-\d+x\d+(\.[a-z]+)$/i', '$1', set_url_scheme( $val, 'https' ) );
			$id  = attachment_url_to_postid( $alt );
			if ( ! $id ) {
				$id = attachment_url_to_postid( set_url_scheme( $alt, 'http' ) );
			}
		}
		return (int) $id;
	}
	return 0;
}

/**
 * Standardwerte – teilweise aus den alten Theme-Einstellungen abgeleitet.
 *
 * @return array
 */
function fvbb_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$legacy_buttons = fvbb_legacy_mod( 'header_content_buttons', array() );
	$btn            = is_array( $legacy_buttons ) && ! empty( $legacy_buttons[0] ) ? $legacy_buttons[0] : array();

	$defaults = array(
		'hero_title'        => wp_strip_all_tags( (string) fvbb_legacy_mod( 'header_title', get_bloginfo( 'name' ) ) ),
		'hero_text'         => fvbb_legacy_subtitle(),
		'hero_btn1_label'   => isset( $btn['label'] ) ? $btn['label'] : '',
		'hero_btn1_url'     => isset( $btn['url'] ) ? $btn['url'] : '',
		'hero_btn2_label'   => '',
		'hero_btn2_url'     => '',
		'header_cta_label'  => '',
		'footer_address'    => "Förderverein Biedensand Bäder Lampertheim e.V.\nAn der Sandbeune 21b\n68623 Lampertheim",
		'footer_email'      => 'info@fv-biedensand-baeder.de',
		'header_cta_url'    => '',
		'overlay_opacity'   => 45,
		'footer_copyright'  => '&copy; {year} {blogname}.',
		'footer_brand'      => get_bloginfo( 'name' ),
	);

	// Slideshow-Bilder (Startseite: 6 Plätze, Unterseiten: 3 Plätze).
	$front = fvbb_legacy_slideshow_ids( 'header_slideshow' );
	$inner = fvbb_legacy_slideshow_ids( 'inner_header_slideshow' );
	// Einzelnes Kopfbild (EmpowerWP/Mesmerize: "Bild" statt "Slideshow").
	if ( ! $front ) {
		$front = array_filter( array( fvbb_image_id( fvbb_legacy_mod( 'header_front_page_image', '' ) ) ) );
	}
	if ( ! $inner ) {
		$inner = array_filter( array(
			fvbb_image_id( fvbb_legacy_mod( 'inner_header_front_page_image', '' ) ),
		) );
	}
	if ( ! $inner ) {
		$hd    = fvbb_legacy_mod( 'header_image_data', null );
		$inner = array_filter( array( is_object( $hd ) && ! empty( $hd->attachment_id ) ? (int) $hd->attachment_id : ( is_array( $hd ) && ! empty( $hd['attachment_id'] ) ? (int) $hd['attachment_id'] : fvbb_image_id( fvbb_legacy_mod( 'header_image', '' ) ) ) ) );
	}
	for ( $i = 1; $i <= 6; $i++ ) {
		$defaults[ 'hero_image_' . $i ] = isset( $front[ $i - 1 ] ) ? $front[ $i - 1 ] : '';
	}
	for ( $i = 1; $i <= 3; $i++ ) {
		$defaults[ 'inner_image_' . $i ] = isset( $inner[ $i - 1 ] ) ? $inner[ $i - 1 ] : '';
	}

	// Social-Links aus dem alten Footer.
	$n = 1;
	for ( $i = 0; $i <= 4 && $n <= 3; $i++ ) {
		$enabled = fvbb_legacy_mod( "footer_content_social_icon_{$i}_enabled", false );
		$link    = fvbb_legacy_mod( "footer_content_social_icon_{$i}_link", '' );
		if ( $enabled && $link && '#' !== $link ) {
			$defaults[ 'social_' . $n ] = $link;
			$n++;
		}
	}
	for ( ; $n <= 3; $n++ ) {
		$defaults[ 'social_' . $n ] = '';
	}
	// Bekannte Werte als Rückfall, falls die alten Einstellungen fehlen.
	if ( '' === $defaults['social_1'] && '' === $defaults['social_2'] ) {
		$defaults['social_1'] = 'https://www.instagram.com/fv_biedensand_baeder';
		$defaults['social_2'] = 'mailto:info@fv-biedensand-baeder.de';
	}

	return $defaults;
}

/**
 * IDs aus der alten Mesmerize-Slideshow-Einstellung.
 *
 * @param string $mod Name der Einstellung.
 * @return array
 */
function fvbb_legacy_slideshow_ids( $mod ) {
	$raw = fvbb_legacy_mod( $mod, array() );
	$ids = array();
	if ( is_array( $raw ) ) {
		foreach ( $raw as $item ) {
			$val = is_array( $item ) && isset( $item['url'] ) ? $item['url'] : $item;
			if ( is_numeric( $val ) ) {
				$ids[] = (int) $val;
			} elseif ( is_string( $val ) && $val ) {
				$id = attachment_url_to_postid( $val );
				if ( $id ) {
					$ids[] = $id;
				}
			}
		}
	}
	return $ids;
}

/**
 * Einstellung lesen.
 *
 * @param string $key Schlüssel ohne Präfix.
 * @return mixed
 */
function fvbb_opt( $key ) {
	$d = fvbb_defaults();
	return get_theme_mod( 'fvbb_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/**
 * Bild-IDs für den Header.
 *
 * @param bool $front Startseite?
 * @return int[]
 */
function fvbb_header_image_ids( $front ) {
	$ids = array();
	$max = $front ? 6 : 3;
	for ( $i = 1; $i <= $max; $i++ ) {
		$id = (int) fvbb_opt( ( $front ? 'hero_image_' : 'inner_image_' ) . $i );
		if ( $id && wp_attachment_is_image( $id ) ) {
			$ids[] = $id;
		}
	}
	if ( ! $ids && ! $front ) {
		return fvbb_header_image_ids( true );
	}
	return $ids;
}

add_action( 'customize_register', function ( WP_Customize_Manager $wpc ) {
	$d = fvbb_defaults();

	$wpc->add_panel( 'fvbb', array(
		'title'    => __( 'Förderverein', 'fvbb' ),
		'priority' => 25,
	) );

	/* --- Startseite --- */
	$wpc->add_section( 'fvbb_hero', array(
		'title' => __( 'Startseite: Kopfbereich', 'fvbb' ),
		'panel' => 'fvbb',
	) );
	$text_fields = array(
		'hero_title'      => __( 'Überschrift', 'fvbb' ),
		'hero_text'       => __( 'Text unter der Überschrift (optional)', 'fvbb' ),
		'hero_btn1_label' => __( 'Button 1: Text', 'fvbb' ),
		'hero_btn1_url'   => __( 'Button 1: Link', 'fvbb' ),
		'hero_btn2_label' => __( 'Button 2: Text (optional)', 'fvbb' ),
		'hero_btn2_url'   => __( 'Button 2: Link', 'fvbb' ),
	);
	foreach ( $text_fields as $key => $label ) {
		$wpc->add_setting( 'fvbb_' . $key, array(
			'default'           => $d[ $key ],
			'sanitize_callback' => false !== strpos( $key, 'url' ) ? 'esc_url_raw' : 'sanitize_text_field',
		) );
		$wpc->add_control( 'fvbb_' . $key, array(
			'label'   => $label,
			'section' => 'fvbb_hero',
			'type'    => 'hero_text' === $key ? 'textarea' : ( false !== strpos( $key, 'url' ) ? 'url' : 'text' ),
		) );
	}
	for ( $i = 1; $i <= 6; $i++ ) {
		$wpc->add_setting( 'fvbb_hero_image_' . $i, array(
			'default'           => $d[ 'hero_image_' . $i ],
			'sanitize_callback' => 'absint',
		) );
		$wpc->add_control( new WP_Customize_Media_Control( $wpc, 'fvbb_hero_image_' . $i, array(
			/* translators: %d: Nummer */
			'label'     => sprintf( __( 'Slideshow-Bild %d', 'fvbb' ), $i ),
			'section'   => 'fvbb_hero',
			'mime_type' => 'image',
		) ) );
	}

	/* --- Unterseiten --- */
	$wpc->add_section( 'fvbb_inner', array(
		'title' => __( 'Unterseiten: Kopfbild', 'fvbb' ),
		'panel' => 'fvbb',
	) );
	for ( $i = 1; $i <= 3; $i++ ) {
		$wpc->add_setting( 'fvbb_inner_image_' . $i, array(
			'default'           => $d[ 'inner_image_' . $i ],
			'sanitize_callback' => 'absint',
		) );
		$wpc->add_control( new WP_Customize_Media_Control( $wpc, 'fvbb_inner_image_' . $i, array(
			/* translators: %d: Nummer */
			'label'     => sprintf( __( 'Bild %d', 'fvbb' ), $i ),
			'section'   => 'fvbb_inner',
			'mime_type' => 'image',
		) ) );
	}
	$wpc->add_setting( 'fvbb_overlay_opacity', array(
		'default'           => $d['overlay_opacity'],
		'sanitize_callback' => 'absint',
	) );
	$wpc->add_control( 'fvbb_overlay_opacity', array(
		'label'       => __( 'Abdunklung der Kopfbilder (%)', 'fvbb' ),
		'section'     => 'fvbb_inner',
		'type'        => 'range',
		'input_attrs' => array( 'min' => 20, 'max' => 90, 'step' => 5 ),
	) );

	/* --- Kopfzeile & Footer --- */
	$wpc->add_section( 'fvbb_footer', array(
		'title' => __( 'Kopfzeile & Footer', 'fvbb' ),
		'panel' => 'fvbb',
	) );
	$footer_fields = array(
		'header_cta_label' => array( __( 'Button oben rechts: Text (leer = ausblenden)', 'fvbb' ), 'text' ),
		'header_cta_url'   => array( __( 'Button oben rechts: Link', 'fvbb' ), 'url' ),
		'footer_brand'     => array( __( 'Footer: Vereinsname', 'fvbb' ), 'text' ),
		'footer_address'   => array( __( 'Footer: Anschrift (nur wenn die Footer-Spalten leer sind)', 'fvbb' ), 'textarea' ),
		'footer_email'     => array( __( 'Footer: E-Mail (nur wenn die Footer-Spalten leer sind)', 'fvbb' ), 'text' ),
		'footer_copyright' => array( __( 'Footer: Copyright ({year} und {blogname} werden ersetzt)', 'fvbb' ), 'text' ),
		'social_1'         => array( __( 'Social-Link 1 (z. B. Instagram)', 'fvbb' ), 'text' ),
		'social_2'         => array( __( 'Social-Link 2 (z. B. mailto:info@fv-biedensand-baeder.de)', 'fvbb' ), 'text' ),
		'social_3'         => array( __( 'Social-Link 3', 'fvbb' ), 'text' ),
	);
	foreach ( $footer_fields as $key => $conf ) {
		$wpc->add_setting( 'fvbb_' . $key, array(
			'default'           => $d[ $key ],
			'sanitize_callback' => 'url' === $conf[1] ? 'esc_url_raw' : ( 0 === strpos( $key, 'social_' ) ? 'fvbb_sanitize_link' : 'wp_kses_post' ),
		) );
		$wpc->add_control( 'fvbb_' . $key, array(
			'label'   => $conf[0],
			'section' => 'fvbb_footer',
			'type'    => 'url' === $conf[1] ? 'url' : ( 'textarea' === $conf[1] ? 'textarea' : 'text' ),
		) );
	}
} );

/**
 * Links inkl. mailto:/tel: erlauben.
 *
 * @param string $v Wert.
 * @return string
 */
function fvbb_sanitize_link( $v ) {
	return esc_url_raw( trim( $v ), array( 'http', 'https', 'mailto', 'tel' ) );
}
