<?php
/**
 * Seite.
 *
 * @package fvbb
 */

get_header();
?>
<main id="content" class="site-main site-main--page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
			<div class="entry-content is-layout-constrained has-global-padding">
				<?php
				the_content();
				wp_link_pages( array(
					'before' => '<nav class="page-links">' . esc_html__( 'Seiten:', 'fvbb' ),
					'after'  => '</nav>',
				) );
				?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
