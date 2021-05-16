<?php

/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>
<footer id="site-footer" role="contentinfo" class="header-footer-group">

	<div class="section-inner">

		<div class="footer-credits">

			<p class="footer-copyright">&copy;
				<?php
				echo date_i18n(
					/* translators: Copyright date format, see https://www.php.net/date */
					_x('Y', 'copyright date format', 'twentytwenty')
				);
				?>
				<a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
				<span style="font-weight: normal;"> - Phone 9592 3092</span>
			</p><!-- .footer-copyright -->

		</div><!-- .footer-credits -->

		<a class="to-the-top" href="#site-header">
			<span class="to-the-top-long">
				<?php
				/* translators: %s: HTML character for up arrow. */
				printf(__('To the top %s', 'twentytwenty'), '<span class="arrow" aria-hidden="true">&uarr;</span>');
				?>
			</span><!-- .to-the-top-long -->
			<span class="to-the-top-short">
				<?php
				/* translators: %s: HTML character for up arrow. */
				printf(__('Up %s', 'twentytwenty'), '<span class="arrow" aria-hidden="true">&uarr;</span>');
				?>
			</span><!-- .to-the-top-short -->
		</a><!-- .to-the-top -->

	</div><!-- .section-inner -->

</footer><!-- #site-footer -->

<?php wp_footer(); ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119001328-2"></script>
<script>
	window.dataLayer = window.dataLayer || [];

	function gtag() {
		dataLayer.push(arguments);
	}
	gtag('js', new Date());

	gtag('config', 'UA-119001328-2');
</script>
</body>

</html>