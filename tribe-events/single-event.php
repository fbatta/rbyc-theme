<?php

/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if (!defined('ABSPATH')) {
	die('-1');
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<!-- Notices -->
	<!-- <?php tribe_the_notices() ?> -->

	<!-- Event header -->
	<?php

	$image_url = !post_password_required() ? get_the_post_thumbnail_url(get_the_ID(), 'twentytwenty-fullscreen') : '';

	// detect if there is no finish time
	$no_finish_time = false;
	if (tribe_get_start_date(null, true, 'd/m/Y: g:i a') == tribe_get_end_date(null, true, 'd/m/Y: g:i a')) {
		$no_finish_time = true;
	}

	// Get the opacity of the color overlay.
	$color_overlay_opacity  = get_theme_mod('cover_template_overlay_opacity');
	$color_overlay_opacity  = (false === $color_overlay_opacity) ? 80 : $color_overlay_opacity;
	?>

	<div class="cover-header rbyc-event-cover-header">
		<?php echo get_the_post_thumbnail(get_the_ID(), 'twentytwenty-fullscreen'); ?>

		<div class="cover-header-inner">

			<header class="entry-header has-text-align-center" style="display: block; background: transparent">
				<div class="entry-header-inner section-inner medium">

					<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
					<?php
					$event_is_multiday = tribe_event_is_multiday();
					$event_is_all_day = tribe_event_is_all_day();

					if ($event_is_all_day && !$event_is_multiday) : ?>
						<h4><?php echo tribe_get_start_date(null, true, 'd/m/Y'); ?></h4>
					<?php elseif ($event_is_multiday) : ?>
						<h4><?php echo tribe_get_start_date(null, true, 'd/m/Y'); ?> - <?php echo tribe_get_end_date(null, true, 'd/m/Y'); ?></h4>
					<?php elseif ($no_finish_time) : ?>
						<h4><?php echo tribe_get_start_date(null, true, 'd/m/Y: g:i a'); ?></h4>
					<?php else : ?>
						<h4><?php echo tribe_get_start_date(null, true, 'd/m/Y: g:i a'); ?> - <?php echo tribe_get_end_date(null, true, 'g:i a'); ?></h4>
					<?php endif; ?>
				</div><!-- .entry-header-inner -->
			</header><!-- .entry-header -->

		</div><!-- .cover-header-inner -->
	</div><!-- .cover-header -->
	<!-- #tribe-events-header -->

	<div class="post-inner" id="post-inner">
		<?php
		$label = esc_html_x('All %s', '%s Events plural label', 'the-events-calendar');
		$events_label_plural = tribe_get_event_label_plural();
		?>
		<div class="wp-block-buttons">
			<div class="wp-block-button is-style-fill">
				<a class="wp-block-button__link" href="<?php echo esc_url(tribe_get_events_link()); ?>">&laquo; <?php printf($label, $events_label_plural); ?></a>
			</div>
		</div>
		<?php tribe_the_content(null, false, $event_id); ?>
	</div>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf(esc_html__('%s Navigation', 'the-events-calendar'), $events_label_singular); ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link('<span>&laquo;</span> %title%') ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link('%title% <span>&raquo;</span>') ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	<!-- #tribe-events-footer -->

</article><!-- #tribe-events-content -->