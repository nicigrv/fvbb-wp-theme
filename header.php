<?php
/**
 * Kopfbereich.
 *
 * @package fvbb
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Zum Inhalt springen', 'fvbb' ); ?></a>

<header class="site-header" id="site-header">
	<div class="site-header__inner">
		<div class="site-brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				printf( '<a class="site-brand__text" href="%s" rel="home">%s</a>', esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) );
			}
			?>
		</div>

		<nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Hauptmenü', 'fvbb' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'menu',
				'menu_id'        => 'primary-menu',
				'depth'          => 4,
				'fallback_cb'    => 'wp_page_menu',
			) );

			$cta_label = trim( (string) fvbb_opt( 'header_cta_label' ) );
			$cta_url   = trim( (string) fvbb_opt( 'header_cta_url' ) );
			if ( $cta_label && $cta_url ) {
				printf( '<a class="header-cta header-cta--mobile" href="%s">%s</a>', esc_url( $cta_url ), esc_html( $cta_label ) );
			}
			?>
		</nav>

		<?php if ( $cta_label && $cta_url ) : ?>
			<a class="header-cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
		<?php endif; ?>

		<button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false">
			<span class="nav-toggle__open"><?php echo fvbb_icon( 'menu', 24 ); // phpcs:ignore ?></span>
			<span class="nav-toggle__close"><?php echo fvbb_icon( 'close', 24 ); // phpcs:ignore ?></span>
			<span class="screen-reader-text nav-toggle__label"><?php esc_html_e( 'Menü öffnen', 'fvbb' ); ?></span>
		</button>
	</div>
</header>

<?php
$fvbb_dim = max( 20, min( 90, (int) fvbb_opt( 'overlay_opacity' ) ) ) / 100;

if ( fvbb_is_front_hero() ) :
	$b1l = trim( (string) fvbb_opt( 'hero_btn1_label' ) );
	$b1u = trim( (string) fvbb_opt( 'hero_btn1_url' ) );
	$b2l = trim( (string) fvbb_opt( 'hero_btn2_label' ) );
	$b2u = trim( (string) fvbb_opt( 'hero_btn2_url' ) );
	$txt = trim( (string) fvbb_opt( 'hero_text' ) );
	?>
	<section class="front-hero" style="--hero-dim: <?php echo esc_attr( $fvbb_dim ); ?>">
		<?php fvbb_header_media( true ); ?>
		<div class="front-hero__shade" aria-hidden="true"></div>
		<div class="front-hero__content">
			<h1 class="front-hero__title"><?php echo esc_html( fvbb_opt( 'hero_title' ) ); ?></h1>
			<span class="sun-wave" aria-hidden="true"><?php fvbb_sun_wave(); ?></span>
			<?php if ( $txt ) : ?>
				<p class="front-hero__text"><?php echo esc_html( $txt ); ?></p>
			<?php endif; ?>
			<?php if ( ( $b1l && $b1u ) || ( $b2l && $b2u ) ) : ?>
				<div class="front-hero__actions">
					<?php if ( $b1l && $b1u ) : ?>
						<a class="hero-btn hero-btn--primary" href="<?php echo esc_url( $b1u ); ?>"<?php echo 0 === strpos( $b1u, 'http' ) && false === strpos( $b1u, wp_parse_url( home_url(), PHP_URL_HOST ) ) ? ' target="_blank" rel="noopener"' : ''; ?>>
							<?php echo false !== strpos( $b1u, 'instagram' ) ? fvbb_icon( 'instagram', 20 ) : ''; // phpcs:ignore ?>
							<?php echo esc_html( $b1l ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $b2l && $b2u ) : ?>
						<a class="hero-btn hero-btn--ghost" href="<?php echo esc_url( $b2u ); ?>"><?php echo esc_html( $b2l ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php fvbb_wave(); ?>
	</section>
<?php else : ?>
	<section class="page-hero" style="--hero-dim: <?php echo esc_attr( $fvbb_dim ); ?>">
		<?php fvbb_header_media( false ); ?>
		<div class="front-hero__shade" aria-hidden="true"></div>
		<div class="page-hero__content">
			<?php fvbb_parent_link(); ?>
			<h1 class="page-hero__title"><?php echo esc_html( fvbb_page_heading() ); ?></h1>
			<?php if ( is_singular( 'post' ) ) : ?>
				<p class="page-hero__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
			<?php elseif ( ( is_category() || is_tag() ) && term_description() ) : ?>
				<div class="page-hero__meta"><?php echo wp_kses_post( term_description() ); ?></div>
			<?php endif; ?>
		</div>
		<?php fvbb_wave(); ?>
	</section>
<?php endif; ?>
