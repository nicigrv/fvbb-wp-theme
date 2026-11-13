<?php
/**
 * Template-Hilfsfunktionen.
 *
 * @package fvbb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Großer Kopfbereich nur auf der Startseite.
 *
 * @return bool
 */
function fvbb_is_front_hero() {
	return is_front_page() && ! is_paged();
}

/**
 * Überschrift für den Kopfbereich der Unterseiten.
 *
 * @return string
 */
function fvbb_page_heading() {
	if ( is_home() && ! is_front_page() ) {
		$id = (int) get_option( 'page_for_posts' );
		return $id ? get_the_title( $id ) : __( 'News', 'fvbb' );
	}
	if ( is_singular() ) {
		return single_post_title( '', false );
	}
	if ( is_search() ) {
		/* translators: %s: Suchbegriff */
		return sprintf( __( 'Suche: %s', 'fvbb' ), get_search_query() );
	}
	if ( is_404() ) {
		return __( 'Seite nicht gefunden', 'fvbb' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		return single_term_title( '', false );
	}
	if ( is_archive() ) {
		return wp_strip_all_tags( get_the_archive_title() );
	}
	return get_bloginfo( 'name' );
}

/**
 * Übergeordnete Seite als kleiner Rück-Link (nur bei Unterseiten).
 */
function fvbb_parent_link() {
	if ( is_page() ) {
		$parent = wp_get_post_parent_id( get_queried_object_id() );
		if ( $parent ) {
			printf(
				'<a class="page-hero__parent" href="%s">%s</a>',
				esc_url( get_permalink( $parent ) ),
				esc_html( get_the_title( $parent ) )
			);
		}
	} elseif ( is_singular( 'post' ) ) {
		$cats = fvbb_visible_categories( get_queried_object_id() );
		if ( $cats ) {
			printf(
				'<a class="page-hero__parent" href="%s">%s</a>',
				esc_url( get_category_link( $cats[0] ) ),
				esc_html( $cats[0]->name )
			);
		}
	}
}

/**
 * Slideshow-Bilder für den Kopfbereich.
 *
 * @param bool $front Startseite?
 */
function fvbb_header_media( $front ) {
	$ids = fvbb_header_image_ids( $front );
	if ( ! $ids ) {
		return;
	}
	echo '<div class="hero-media" data-slideshow="' . ( count( $ids ) > 1 ? '1' : '0' ) . '" aria-hidden="true">';
	foreach ( $ids as $i => $id ) {
		echo wp_get_attachment_image( $id, 'full', false, array(
			'class'         => 'hero-media__img' . ( 0 === $i ? ' is-active' : '' ),
			'alt'           => '',
			'loading'       => 0 === $i ? 'eager' : 'lazy',
			'fetchpriority' => 0 === $i ? 'high' : 'low',
			'sizes'         => '100vw',
			'decoding'      => 'async',
		) );
	}
	echo '</div>';
}

/**
 * Farbe des ersten Abschnitts der Seite (wenn er volle Breite und eine Hintergrundfarbe hat),
 * damit die Welle nahtlos in diesen Abschnitt übergeht.
 *
 * @return string CSS-Farbe oder ''
 */
function fvbb_first_section_color() {
	if ( ! is_singular() ) {
		return '';
	}
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || ! has_blocks( $post->post_content ) ) {
		return '';
	}
	foreach ( parse_blocks( $post->post_content ) as $b ) {
		if ( empty( $b['blockName'] ) ) {
			continue;
		}
		$a = $b['attrs'] ?? array();
		if ( ! in_array( $b['blockName'], array( 'core/group', 'core/cover', 'core/columns' ), true ) || 'full' !== ( $a['align'] ?? '' ) ) {
			return '';
		}
		if ( ! empty( $a['backgroundColor'] ) ) {
			return 'var(--wp--preset--color--' . sanitize_key( $a['backgroundColor'] ) . ')';
		}
		$bg = $a['style']['color']['background'] ?? '';
		if ( $bg && preg_match( '/^(#[0-9a-f]{3,8}|rgba?\([\d\s.,%]+\)|var\(--wp--preset--color--[a-z0-9-]+\))$/i', $bg ) ) {
			return $bg;
		}
		if ( 0 === strpos( (string) $bg, 'var:preset|color|' ) ) {
			return 'var(--wp--preset--color--' . sanitize_key( substr( $bg, 17 ) ) . ')';
		}
		return '';
	}
	return '';
}

/**
 * Wellen-Trenner unter dem Kopfbereich (in der Farbe des ersten Abschnitts).
 */
function fvbb_wave() {
	$color = fvbb_first_section_color();
	$style = $color ? ' style="fill:' . esc_attr( $color ) . '"' : '';
	echo '<svg class="hero-wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true" focusable="false"' . $style . '><path d="M0 60c120-26 240-38 360-30s240 40 360 40 240-36 360-44 240 10 360 26v38H0z"/></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Datum + Kategorie eines Beitrags.
 */
function fvbb_post_meta() {
	$cats = fvbb_visible_categories();
	echo '<div class="post-meta">';
	if ( $cats ) {
		printf( '<a class="pill" href="%s">%s</a>', esc_url( get_category_link( $cats[0] ) ), esc_html( $cats[0]->name ) );
	}
	printf(
		'<time datetime="%s">%s</time>',
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	echo '</div>';
}

/**
 * Footer-Copyright.
 *
 * @return string
 */
function fvbb_copyright() {
	$text = (string) fvbb_opt( 'footer_copyright' );
	$text = str_replace(
		array( '{year}', '{blogname}' ),
		array( date_i18n( 'Y' ), get_bloginfo( 'name' ) ),
		$text
	);
	return wp_kses_post( $text );
}

/**
 * Social-Links (Footer).
 */
function fvbb_social_links() {
	$out = '';
	for ( $i = 1; $i <= 3; $i++ ) {
		$url = trim( (string) fvbb_opt( 'social_' . $i ) );
		if ( ! $url ) {
			continue;
		}
		$icon  = fvbb_social_icon_name( $url );
		$label = 'mail' === $icon ? __( 'E-Mail schreiben', 'fvbb' ) : ( 'instagram' === $icon ? __( 'Der FV auf Instagram', 'fvbb' ) : $url );
		$ext   = 0 === strpos( $url, 'http' ) ? ' target="_blank" rel="noopener"' : '';
		$out  .= sprintf(
			'<a class="social-link" href="%s"%s aria-label="%s">%s</a>',
			esc_url( $url, array( 'http', 'https', 'mailto', 'tel' ) ),
			$ext,
			esc_attr( $label ),
			fvbb_icon( $icon, 22 )
		);
	}
	if ( $out ) {
		echo '<div class="social-links">' . $out . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * Beitragskarte (Blog-Übersicht).
 */
function fvbb_post_card() {
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
		<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'medium_large', array( 'alt' => '', 'loading' => 'lazy' ) );
			} else {
				echo '<span class="post-card__placeholder"></span>';
			}
			?>
		</a>
		<div class="post-card__body">
			<?php fvbb_post_meta(); ?>
			<h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
			<a class="post-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Weiterlesen', 'fvbb' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span></a>
		</div>
	</article>
	<?php
}

/**
 * Blätter-Navigation.
 */
function fvbb_pagination() {
	the_posts_pagination( array(
		'mid_size'           => 1,
		'prev_text'          => __( 'Zurück', 'fvbb' ),
		'next_text'          => __( 'Weiter', 'fvbb' ),
		'before_page_number' => '<span class="screen-reader-text">' . __( 'Seite', 'fvbb' ) . ' </span>',
	) );
}

/**
 * Sonne über einer Welle (wie im Logo) – Zierelement im Kopfbereich.
 */
function fvbb_sun_wave() {
	echo '<svg viewBox="0 0 150 26" aria-hidden="true" focusable="false">'
		. '<circle cx="18" cy="12" r="7" fill="#F8B000"/>'
		. '<path d="M2 20c12-6 24-6 36 0s24 6 36 0 24-6 36 0 24 6 36 0" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"/>'
		. '</svg>'; // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Footer ohne Widgets: Anschrift und E-Mail aus dem Customizer.
 */
function fvbb_footer_fallback() {
	$addr  = trim( (string) fvbb_opt( 'footer_address' ) );
	$email = trim( (string) fvbb_opt( 'footer_email' ) );
	if ( ! $addr && ! $email ) {
		return;
	}
	echo '<div class="footer-col"><p>';
	if ( $addr ) {
		echo nl2br( esc_html( $addr ) );
	}
	if ( $email && is_email( $email ) ) {
		echo ( $addr ? '<br>' : '' ) . '<a href="mailto:' . esc_attr( antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a>';
	}
	echo '</p></div>';
}

/**
 * Kategorien eines Beitrags ohne die Standardkategorie („Uncategorized“/„Allgemein“) –
 * die sagt nichts aus, wenn alle Beiträge darin liegen.
 *
 * @param int|null $post_id Beitrag.
 * @return WP_Term[]
 */
function fvbb_visible_categories( $post_id = null ) {
	$default = (int) get_option( 'default_category' );
	return array_values( array_filter( get_the_category( $post_id ), function ( $c ) use ( $default ) {
		return (int) $c->term_id !== $default && ! in_array( $c->slug, array( 'uncategorized', 'allgemein', 'unkategorisiert' ), true );
	} ) );
}
