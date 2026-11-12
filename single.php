<?php
/**
 * Einzelner Beitrag.
 *
 * @package fvbb
 */

get_header();
?>
<main id="content" class="site-main site-main--single">
	<?php
	while ( have_posts() ) :
		the_post();
		$fvbb_has_sidebar = is_active_sidebar( 'sidebar-1' );
		?>
		<div class="single-layout<?php echo $fvbb_has_sidebar ? ' has-sidebar' : ''; ?>">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--post' ); ?>>
				<?php fvbb_post_meta(); ?>
				<?php if ( has_post_thumbnail() && false === strpos( get_the_content(), 'wp-image-' . get_post_thumbnail_id() ) ) : ?>
					<figure class="entry-thumb"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?></figure>
				<?php endif; ?>
				<div class="entry-content is-layout-constrained">
					<?php the_content(); ?>
				</div>
				<?php
				the_post_navigation( array(
					'prev_text' => '<span class="nav-label">' . esc_html__( 'Vorheriger Beitrag', 'fvbb' ) . '</span><span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-label">' . esc_html__( 'Nächster Beitrag', 'fvbb' ) . '</span><span class="nav-title">%title</span>',
				) );
				?>
			</article>
			<?php if ( $fvbb_has_sidebar ) : ?>
				<aside class="sidebar" aria-label="<?php esc_attr_e( 'Seitenleiste', 'fvbb' ); ?>">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
