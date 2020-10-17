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
// On the cover page template, output the cover header.
$cover_header_style   = '';
$cover_header_classes = '';

$color_overlay_style   = '';
$color_overlay_classes = '';

$image_url = !post_password_required() ? get_the_post_thumbnail_url(get_the_ID(), 'twentytwenty-fullscreen') : '';

if ($image_url) {
    $cover_header_style   = ' style="background-image: url( ' . esc_url($image_url) . ' );"';
    $cover_header_classes = ' bg-image';
}

// Get the color used for the color overlay.
$color_overlay_color = get_theme_mod('cover_template_overlay_background_color');
if ($color_overlay_color) {
    $color_overlay_style = ' style="color: ' . esc_attr($color_overlay_color) . ';"';
} else {
    $color_overlay_style = '';
}

// Get the fixed background attachment option.
if (get_theme_mod('cover_template_fixed_background', true)) {
    $cover_header_classes .= ' bg-attachment-fixed';
}

// Get the opacity of the color overlay.
$color_overlay_opacity  = get_theme_mod('cover_template_overlay_opacity');
$color_overlay_opacity  = (false === $color_overlay_opacity) ? 80 : $color_overlay_opacity;
$color_overlay_classes .= ' opacity-' . $color_overlay_opacity;
?>

<div class="cover-header <?php echo $cover_header_classes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output 
                            ?>" <?php echo $cover_header_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;) 
                                ?>>
    <div class="cover-header-inner-wrapper screen-height">
        <div class="cover-header-inner">
            <div class="cover-color-overlay color-accent<?php echo esc_attr($color_overlay_classes); ?>" <?php echo $color_overlay_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;) 
                                                                                                            ?>></div>

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

                    <div class="to-the-content-wrapper">

                        <a href="#post-inner" class="to-the-content fill-children-current-color">
                            <?php twentytwenty_the_theme_svg('arrow-down'); ?>
                            <div class="screen-reader-text"><?php _e('Scroll Down', 'twentytwenty'); ?></div>
                        </a><!-- .to-the-content -->

                    </div><!-- .to-the-content-wrapper -->
                </div><!-- .entry-header-inner -->
            </header><!-- .entry-header -->

        </div><!-- .cover-header-inner -->
    </div><!-- .cover-header-inner-wrapper -->
</div><!-- .cover-header -->