<?php
/**
 * Beitragsübersicht, Kategorien, Archive.
 *
 * @package fvbb
 */

get_header();
?>
<main id="content" class="site-main site-main--list">
	<div class="list-wrap">
		<?php if ( is_search() ) : ?>
			<div class="search-again"><?php get_search_form(); ?></div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( is_search() && 'post' !== get_post_type() ) :
						?>
						<article <?php post_class( 'post-card post-card--plain' ); ?>>
							<div class="post-card__body">
								<h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
							</div>
						</article>
						<?php
					else :
						fvbb_post_card();
					endif;
				endwhile;
				?>
			</div>
			<?php fvbb_pagination(); ?>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Hier gibt es noch nichts', 'fvbb' ); ?></h2>
				<p><?php esc_html_e( 'Versuch es mit einem anderen Suchbegriff oder schau bei den News vorbei.', 'fvbb' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
