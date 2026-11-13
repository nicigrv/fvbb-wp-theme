<?php
/**
 * Förderverein Biedensand Bäder Lampertheim – Theme-Funktionen
 *
 * Eigenständiges Theme (kein Parent, kein Page-Builder).
 * Vereinsfunktionen (Cookie-Banner, Hinweis-Popup, Tabellen, Symbole,
 * Umstellung) liegen im Plugin "Förderverein Funktionen" (fvbb-core).
 *
 * @package fvbb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FVBB_THEME_VERSION', '1.0.1' );
define( 'FVBB_THEME_DIR', get_stylesheet_directory() );
define( 'FVBB_THEME_URI', get_stylesheet_directory_uri() );

require FVBB_THEME_DIR . '/inc/class-github-updater.php';
require FVBB_THEME_DIR . '/inc/icons.php';

// Updates direkt aus GitHub (Dashboard → Aktualisierungen).
new FVBB_Theme_GitHub_Updater( array(
	'type'  => 'theme',
	'repo'  => 'nicigrv/fvbb-wp-theme',
	'slug'  => basename( FVBB_THEME_DIR ),
	'asset' => 'fvbb.zip',
	'name'  => 'Förderverein Biedensand Bäder',
) );
require FVBB_THEME_DIR . '/inc/customizer.php';
require FVBB_THEME_DIR . '/inc/template-tags.php';

/* -------------------------------------------------------------------------
 * Grundeinrichtung
 * ---------------------------------------------------------------------- */
add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'fvbb', FVBB_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 440,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_editor_style( array( 'assets/css/theme.css', 'assets/css/editor.css' ) );

	// Gleicher Menü-Ort wie im alten Theme (Mesmerize) -> Menüzuordnung bleibt erhalten.
	register_nav_menus( array(
		'primary' => __( 'Hauptmenü', 'fvbb' ),
	) );
} );

/* Widget-Bereiche: gleiche IDs wie im alten Theme -> Footer-Widgets bleiben erhalten */
add_action( 'widgets_init', function () {
	$common = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);
	register_sidebar( array_merge( $common, array(
		'id'   => 'first_box_widgets',
		'name' => __( 'Footer – Spalte 1', 'fvbb' ),
	) ) );
	register_sidebar( array_merge( $common, array(
		'id'   => 'second_box_widgets',
		'name' => __( 'Footer – Spalte 2', 'fvbb' ),
	) ) );
	register_sidebar( array_merge( $common, array(
		'id'   => 'third_box_widgets',
		'name' => __( 'Footer – Spalte 3', 'fvbb' ),
	) ) );
	register_sidebar( array_merge( $common, array(
		'id'          => 'sidebar-1',
		'name'        => __( 'Blog-Seitenleiste', 'fvbb' ),
		'description' => __( 'Wird neben Beiträgen angezeigt, sobald hier ein Widget liegt.', 'fvbb' ),
	) ) );
} );

/* -------------------------------------------------------------------------
 * Styles & Skripte
 * ---------------------------------------------------------------------- */
function fvbb_asset_ver( $rel ) {
	$f = FVBB_THEME_DIR . '/' . $rel;
	return file_exists( $f ) ? (string) filemtime( $f ) : FVBB_THEME_VERSION;
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'fvbb-theme', FVBB_THEME_URI . '/assets/css/theme.css', array(), fvbb_asset_ver( 'assets/css/theme.css' ) );
	wp_enqueue_script( 'fvbb-theme', FVBB_THEME_URI . '/assets/js/theme.js', array(), fvbb_asset_ver( 'assets/js/theme.js' ), array(
		'in_footer' => true,
		'strategy'  => 'defer',
	) );
	wp_localize_script( 'fvbb-theme', 'fvbbTheme', array(
		'menuOpen'  => __( 'Menü öffnen', 'fvbb' ),
		'menuClose' => __( 'Menü schließen', 'fvbb' ),
		'subOpen'   => __( 'Untermenü öffnen', 'fvbb' ),
	) );
} );

/* Schriften vorladen (liegen im Theme, kein Google-Request) */
add_action( 'wp_head', function () {
	$base = esc_url( FVBB_THEME_URI . '/assets/fonts/' );
	echo '<link rel="preload" href="' . $base . 'mulish-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<meta name="theme-color" content="#0058A8">' . "\n";
}, 1 );

/* Emoji-Skripte von WordPress weglassen (Emojis funktionieren trotzdem nativ) */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
} );

/* -------------------------------------------------------------------------
 * Block-Stile (im Editor unter "Stile" auswählbar)
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_block_style( 'core/heading', array(
		'name'  => 'fvbb-wave',
		'label' => __( 'Mit Welle', 'fvbb' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'fvbb-card',
		'label' => __( 'Karte', 'fvbb' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'fvbb-box',
		'label' => __( 'Info-Box (Download/Kontakt)', 'fvbb' ),
	) );
	register_block_style( 'core/image', array(
		'name'  => 'fvbb-photo',
		'label' => __( 'Foto mit Schatten', 'fvbb' ),
	) );
	register_block_style( 'core/image', array(
		'name'  => 'fvbb-portrait',
		'label' => __( 'Porträt (rund)', 'fvbb' ),
	) );
	register_block_style( 'core/table', array(
		'name'  => 'fvbb-table',
		'label' => __( 'Vereinstabelle', 'fvbb' ),
	) );
	foreach ( array(
		'fvbb-mail'     => __( 'Mit Mail-Symbol', 'fvbb' ),
		'fvbb-download' => __( 'Mit Download-Symbol', 'fvbb' ),
		'fvbb-link'     => __( 'Mit Link-Symbol', 'fvbb' ),
		'fvbb-light'    => __( 'Hell', 'fvbb' ),
		'fvbb-sun'      => __( 'Sonnengelb', 'fvbb' ),
	) as $name => $label ) {
		register_block_style( 'core/button', array( 'name' => $name, 'label' => $label ) );
	}
	register_block_style( 'core/group', array(
		'name'  => 'fvbb-person',
		'label' => __( 'Person (Vorstand)', 'fvbb' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'fvbb-sunbox',
		'label' => __( 'Hinweis (Sonnengelb)', 'fvbb' ),
	) );
} );

/* Eigene Muster-Kategorie */
add_action( 'init', function () {
	register_block_pattern_category( 'fvbb', array( 'label' => __( 'Förderverein', 'fvbb' ) ) );
} );

/* -------------------------------------------------------------------------
 * Kleinigkeiten
 * ---------------------------------------------------------------------- */
add_filter( 'excerpt_length', function () {
	return 24;
}, 20 );

add_filter( 'excerpt_more', function () {
	return '…';
} );

/* Body-Klassen für Layout */
add_filter( 'body_class', function ( $classes ) {
	$classes[] = fvbb_is_front_hero() ? 'has-front-hero' : 'has-page-hero';
	if ( is_singular() && has_blocks() ) {
		$classes[] = 'has-blocks';
	}
	return $classes;
} );

/* Leere Absätze aus alten Inhalten (z. B. "<p></p>") nicht als Abstand rendern */
add_filter( 'render_block_core/paragraph', function ( $html ) {
	if ( preg_match( '#^\s*<p[^>]*>\s*(&nbsp;|\s)*</p>\s*$#u', $html ) ) {
		return '';
	}
	return $html;
} );

/* Externe Links in Navigation sicher öffnen */
add_filter( 'nav_menu_link_attributes', function ( $atts ) {
	if ( ! empty( $atts['target'] ) && '_blank' === $atts['target'] ) {
		$atts['rel'] = 'noopener';
	}
	return $atts;
} );


/* "Jetzt Mitglied werden!" im Menü als gelben Button hervorheben */
add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) {
	if ( 0 === (int) $depth && isset( $args->theme_location ) && 'primary' === $args->theme_location
		&& ( false !== stripos( (string) $item->url, 'mitgliedsantrag' ) || false !== stripos( (string) $item->title, 'mitglied werden' ) ) ) {
		$classes[] = 'menu-item-cta';
	}
	return $classes;
}, 10, 4 );

/* Startseite mit Karten, die ins Kopfbild ragen: dem Kopfbild unten mehr Platz geben */
add_filter( 'body_class', function ( $classes ) {
	if ( is_front_page() && is_page() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post && false !== strpos( $post->post_content, 'fvbb-overlap' ) ) {
			$classes[] = 'has-fvbb-overlap';
		}
	}
	return $classes;
} );
