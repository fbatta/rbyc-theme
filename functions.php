<?php
/**
 * RBYC Twenty Twenty Theme functions and definitions
 *
 * @package rbyc-twentytwenty
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * 
 * @author Francesco Battaglini <francesco@battaglini.net>
 */

add_action( 'wp_enqueue_scripts', 'twentytwenty_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function twentytwenty_parent_theme_enqueue_styles() {
	// parent style
	wp_enqueue_style( 'twentytwenty-style', get_template_directory_uri() . '/style.css' );
	// ubuntu font
	wp_enqueue_style( 'rbyc-twentytwenty-ubuntu-font', 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap' );
	// icons generated from fontello
	wp_enqueue_style( 'rbyc-twentytwenty-fontello-icons',
		get_stylesheet_directory_uri() . '/assets/css/fontello.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
	// child theme style
	wp_enqueue_style( 'rbyc-twentytwenty-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'twentytwenty-style', 'rbyc-twentytwenty-fontello-icons', 'rbyc-twentytwenty-ubuntu-font' ),
		wp_get_theme()->get( 'Version' )
	);
}

/* 
 * Register a new menu location underneath the cover in the cover template
 */
add_action( 'init', 'rbyc_register_menu_under_cover' );
function rbyc_register_menu_under_cover() {
	register_nav_menu( 'rbyc-menu-under-cover', __( 'Menu under cover page', 'rbyc' ) );
}

/* 
 * This filter will update the number of items in the 'Add to cart' button at the bottom of the screen in the 'order' page
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'rbyc_show_view_cart_button' );
function rbyc_show_view_cart_button( $fragments ) {
	// turn on output buffering
	ob_start();
	// get number of items in the cart
	$cart_count = WC()->cart->cart_contents_count;
	// get the cart url for the href
	$cart_url = wc_get_cart_url();
	// if there are no elements, a 'hidden' class will be added to the element
	$link_classes = $cart_count > 0 ? '' : 'hidden';
	?>

	<a class="bottom-view-cart-button <?php echo esc_attr( $link_classes ); ?>" href="<?php echo esc_url( $cart_url ); ?>">
		View Cart (<?php echo esc_html( $cart_count ); ?>)
	</a>

	<?php

	// add a new fragment with the contents of the output buffer
	$fragments['a.bottom-view-cart-button'] = ob_get_clean();
	// return all fragments
	return $fragments;
}

/* 
 * Disable certain columns in the 'Products' admin page
 */
add_filter( 'manage_edit-product_columns', 'rbyc_modify_products_columns', 10, 1 );
function rbyc_modify_products_columns( $columns ) {
	unset( $columns[ 'sku' ] );
	return $columns;
}