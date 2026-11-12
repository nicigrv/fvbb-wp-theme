<?php
/**
 * Footer.
 *
 * @package fvbb
 */
?>
<svg class="wave-band" viewBox="0 0 1440 54" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="currentColor" d="M0 30c140-22 260-26 380-12s250 30 380 22 240-34 380-36 200 14 300 22v28H0z"/></svg>
<footer class="site-footer">
	<div class="site-footer__inner">
		<div class="footer-brand">
			<p class="footer-brand__name"><?php echo esc_html( fvbb_opt( 'footer_brand' ) ); ?></p>
			<?php fvbb_social_links(); ?>
		</div>
		<?php
		$fvbb_has_widgets = is_active_sidebar( 'first_box_widgets' ) || is_active_sidebar( 'second_box_widgets' ) || is_active_sidebar( 'third_box_widgets' );
		if ( ! $fvbb_has_widgets ) {
			fvbb_footer_fallback();
		}
		?>
		<?php foreach ( array( 'first_box_widgets', 'second_box_widgets', 'third_box_widgets' ) as $sidebar ) : ?>
			<?php if ( is_active_sidebar( $sidebar ) ) : ?>
				<div class="footer-col">
					<?php dynamic_sidebar( $sidebar ); ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<div class="site-footer__bottom">
		<p class="copyright"><?php echo fvbb_copyright(); // phpcs:ignore ?></p>
		<?php do_action( 'fvbb_footer_bottom' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
