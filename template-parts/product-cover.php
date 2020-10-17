<?php

/**
 * Displays the content when the cover template is used.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>

<?php
global $product;
?>

<!-- 'View Cart' button displayed at the bottom of the page in a fixed position -->
<?php
$cart_count = WC()->cart->cart_contents_count;
$cart_url = wc_get_cart_url();

$link_classes = $cart_count > 0 ? '' : 'hidden';
?>

<a class="bottom-view-cart-button <?php echo $link_classes ?>" href="<?php echo $cart_url; ?>">
    View Cart (<?php echo $cart_count ?>)
</a>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
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

                <header class="entry-header has-text-align-center">
                    <div class="entry-header-inner section-inner medium">

                        <?php

                        /**
                         * Allow child themes and plugins to filter the display of the categories in the article header.
                         *
                         * @since Twenty Twenty 1.0
                         *
                         * @param bool Whether to show the categories in article header, Default true.
                         */
                        $show_categories = apply_filters('twentytwenty_show_categories_in_entry_header', true);

                        if (true === $show_categories && has_category()) {
                        ?>

                            <div class="entry-categories">
                                <span class="screen-reader-text"><?php _e('Categories', 'twentytwenty'); ?></span>
                                <div class="entry-categories-inner">
                                    <?php the_category(' '); ?>
                                </div><!-- .entry-categories-inner -->
                            </div><!-- .entry-categories -->

                        <?php
                        }

                        // Add an extra class if the title belongs to the website's front page
                        if (is_front_page()) {
                            the_title('<h1 class="entry-title front-page-entry-title">', '</h1>');
                        } else {
                            the_title('<h1 class="entry-title">', '</h1>');
                        } ?>

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

    <div class="post-inner" id="post-inner">

        <!-- Display an under-cover menu if the relevan post meta is set -->
        <?php
        $menu_slug = get_post_meta(get_the_ID(), 'rbyc_menu_under_cover_slug', true);
        if ($menu_slug) {
        ?>
            <nav class="under-cover-menu-wrapper" aria-label="<?php esc_attr_e('Horizontal', 'twentytwenty'); ?>" role="navigation">
                <ul class="under-cover-menu primary-menu reset-list-style">

                    <?php
                    wp_nav_menu(array(
                        'menu'            =>         $menu_slug,
                        'items_wrap'     =>         '%3$s',
                        'container'        =>        '',
                    ));
                    ?>

                </ul>
            </nav>
        <?php
        }
        ?>

        <div class="entry-content">

            <?php

            $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);
            if ($short_description) : ?>
                <h2>Description</h2>
            <?php echo $short_description; // WPCS: XSS ok.
            endif;

            the_content();

            ?>

            <div class="wp-block-columns">
                <div class="wp-block-column" style="flex-basis: 33.33%;">
                    <h2>Price</h2>
                    <p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>"><?php echo $product->get_price_html(); ?></p>
                </div>
                <div class="wp-block-column" style="flex-basis: 66.66%;">
                    <h2>Add to cart</h2>

                    <?php if ($product->is_in_stock()) : ?>

                        <?php do_action('woocommerce_before_add_to_cart_form'); ?>

                        <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                            <?php
                            do_action('woocommerce_before_add_to_cart_quantity');

                            woocommerce_quantity_input(
                                array(
                                    'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                    'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                                )
                            );

                            do_action('woocommerce_after_add_to_cart_quantity');
                            ?>

                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>

                            <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                        </form>

                        <?php do_action('woocommerce_after_add_to_cart_form'); ?>

                    <?php endif; ?>
                </div>
            </div>

            <h2>Images</h2>
            <?php
            wc_get_template_part('product', 'image');
            ?>
            <h2>Related</h2>
            <?php
            wc_get_template_part('product', 'related');
            ?>

        </div><!-- .entry-content -->
        <?php
        wp_link_pages(
            array(
                'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__('Page', 'twentytwenty') . '"><span class="label">' . __('Pages:', 'twentytwenty') . '</span>',
                'after'       => '</nav>',
                'link_before' => '<span class="page-number">',
                'link_after'  => '</span>',
            )
        );

        edit_post_link();
        // Single bottom post meta.
        twentytwenty_the_post_meta(get_the_ID(), 'single-bottom');

        if (post_type_supports(get_post_type(get_the_ID()), 'author') && is_single()) {

            get_template_part('template-parts/entry-author-bio');
        }
        ?>

    </div><!-- .post-inner -->

    <?php

    /**
     *  Output comments wrapper if it's a post, or if comments are open,
     * or if there's a comment number – and check for password.
     * */
    if ((is_single() || is_page()) && (comments_open() || get_comments_number()) && !post_password_required()) {
    ?>

        <div class="comments-wrapper section-inner">

            <?php comments_template(); ?>

        </div><!-- .comments-wrapper -->

    <?php
    }
    ?>

</article><!-- .post -->