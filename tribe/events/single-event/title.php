<?php

/**
 * Single Event Title Template Part
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/single-event/title.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 4.7.2
 *
 */
?>

<?php

$image_url = !post_password_required() ? get_the_post_thumbnail_url(get_the_ID(), 'twentytwenty-fullscreen') : '';

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
                <?php else : ?>
                    <h4><?php echo tribe_get_start_date(null, true, 'd/m/Y: g:i a') ?> - <?php echo tribe_get_end_date(null, true, 'g:i a') ?></h4>
                <?php endif; ?>
            </div><!-- .entry-header-inner -->
        </header><!-- .entry-header -->

    </div><!-- .cover-header-inner -->
</div><!-- .cover-header -->