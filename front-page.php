<?php
/**
 * Startseite.
 *
 * @package fvbb
 */

if ( 'posts' === get_option( 'show_on_front' ) ) {
	get_template_part( 'index' );
	return;
}

get_header();
?>
<main id="content" class="site-main site-main--front">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="entry-content is-layout-constrained has-global-padding">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
