<?php
/**
 * Template Name: Startseite (volle Breite)
 *
 * Gleicher Pfad wie im alten Theme, damit die Zuordnung erhalten bleibt.
 *
 * @package fvbb
 */

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
