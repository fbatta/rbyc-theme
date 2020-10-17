<?php

/**
 * RBYC Twenty Twenty Theme functions and definitions
 *
 * @package rbyc-twentytwenty
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * 
 * @author Francesco Battaglini <francesco@battaglini.net>
 */

add_action('wp_enqueue_scripts', 'twentytwenty_parent_theme_enqueue_styles');

/**
 * Enqueue scripts and styles.
 */
function twentytwenty_parent_theme_enqueue_styles()
{
	$theme_version = wp_get_theme()->get('Version');

	// parent style
	wp_enqueue_style('twentytwenty-style', get_template_directory_uri() . '/style.css');
	// ubuntu font
	wp_enqueue_style('rbyc-twentytwenty-ubuntu-font', 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap');

	// icons generated from fontello
	wp_enqueue_style(
		'rbyc-twentytwenty-fontello-icons',
		get_stylesheet_directory_uri() . '/assets/css/fontello.css',
		array(),
		$theme_version
	);
	wp_enqueue_style(
		'rbyc-twentytwenty-single-event',
		get_stylesheet_directory_uri() . '/assets/css/single-event.css',
		array(),
		$theme_version
	);
	// child theme style
	wp_enqueue_style(
		'rbyc-twentytwenty-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('twentytwenty-style', 'rbyc-twentytwenty-fontello-icons', 'rbyc-twentytwenty-ubuntu-font'),
		$theme_version
	);

	// Add output of Customizer settings as inline style.
	wp_add_inline_style('rbyc-twentytwenty-style', rbyc_get_customizer_css('front-end'));
}

/* 
 * Register a new menu location underneath the cover in the cover template
 */
add_action('init', 'rbyc_register_menu_under_cover');
function rbyc_register_menu_under_cover()
{
	register_nav_menu('rbyc-menu-under-cover', __('Menu under cover page', 'rbyc'));
}

/* 
 * This filter will update the number of items in the 'Add to cart' button at the bottom of the screen in the 'order' page
 */
add_filter('woocommerce_add_to_cart_fragments', 'rbyc_show_view_cart_button');
function rbyc_show_view_cart_button($fragments)
{
	// turn on output buffering
	ob_start();
	// get number of items in the cart
	$cart_count = WC()->cart->cart_contents_count;
	// get the cart url for the href
	$cart_url = wc_get_cart_url();
	// if there are no elements, a 'hidden' class will be added to the element
	$link_classes = $cart_count > 0 ? '' : 'hidden';
?>

	<a class="bottom-view-cart-button <?php echo esc_attr($link_classes); ?>" href="<?php echo esc_url($cart_url); ?>">
		View Cart (<?php echo esc_html($cart_count); ?>)
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
add_filter('manage_edit-product_columns', 'rbyc_modify_products_columns', 10, 1);
function rbyc_modify_products_columns($columns)
{
	unset($columns['sku']);
	return $columns;
}

/**
 * Get CSS Built from Customizer Options.
 * Build CSS reflecting colors, fonts and other options set in the Customizer, and return them for output.
 *
 * @param string $type Whether to return CSS for the "front-end", "block-editor" or "classic-editor".
 */
function rbyc_get_customizer_css($type = 'front-end')
{

	// Get variables.
	$body              = sanitize_hex_color(rbyc_get_color_for_area('content', 'text'));
	$body_default      = '#000000';
	$secondary         = sanitize_hex_color(rbyc_get_color_for_area('content', 'secondary'));
	$secondary_default = '#6d6d6d';
	$borders           = sanitize_hex_color(rbyc_get_color_for_area('content', 'borders'));
	$borders_default   = '#dcd7ca';
	// $accent            = sanitize_hex_color(rbyc_get_color_for_area('content', 'accent'));
	$accent            = '#002244';
	$accent_default    = '#002244';

	// Header.
	$header_footer_background         = sanitize_hex_color(rbyc_get_color_for_area('header-footer', 'background'));
	$header_footer_background_default = '#ffffff';

	// Cover.
	$cover         = sanitize_hex_color(get_theme_mod('cover_template_overlay_text_color'));
	$cover_default = '#ffffff';

	// Background.
	$background         = sanitize_hex_color_no_hash(get_theme_mod('background_color'));
	$background_default = 'f5efe0';

	ob_start();

	/**
	 * Note – Styles are applied in this order:
	 * 1. Element specific
	 * 2. Helper classes
	 *
	 * This enables all helper classes to overwrite base element styles,
	 * meaning that any color classes applied in the block editor will
	 * have a higher priority than the base element styles.
	 */

	// Front-End Styles.
	if ('front-end' === $type) {

		// Auto-calculated colors.
		$elements_definitions = twentytwenty_get_elements_array();
		foreach ($elements_definitions as $context => $props) {
			foreach ($props as $key => $definitions) {
				foreach ($definitions as $property => $elements) {
					/*
						 * If we don't have an elements array or it is empty
						 * then skip this iteration early;
						 */
					if (!is_array($elements) || empty($elements)) {
						continue;
					}
					$val = rbyc_get_color_for_area($context, $key);
					if ($val) {
						twentytwenty_generate_css(implode(',', $elements), $property, $val);
					}
				}
			}
		}

		if ($cover && $cover !== $cover_default) {
			twentytwenty_generate_css('.overlay-header .header-inner', 'color', $cover);
			twentytwenty_generate_css('.cover-header .entry-header *', 'color', $cover);
		}

		// Block Editor Styles.
	} elseif ('block-editor' === $type) {

		// Colors.
		// Accent color.
		if ($accent && $accent !== $accent_default) {
			twentytwenty_generate_css('.has-accent-color, .editor-styles-wrapper .editor-block-list__layout a, .editor-styles-wrapper .has-drop-cap:not(:focus)::first-letter, .editor-styles-wrapper .wp-block-button.is-style-outline .wp-block-button__link, .editor-styles-wrapper .wp-block-pullquote::before, .editor-styles-wrapper .wp-block-file .wp-block-file__textlink', 'color', $accent);
			twentytwenty_generate_css('.editor-styles-wrapper .wp-block-quote', 'border-color', $accent, '');
			twentytwenty_generate_css('.has-accent-background-color, .editor-styles-wrapper .wp-block-button__link, .editor-styles-wrapper .wp-block-file__button', 'background-color', $accent);
		}

		// Background color.
		if ($background && $background !== $background_default) {
			twentytwenty_generate_css('.editor-styles-wrapper', 'background-color', '#' . $background);
			twentytwenty_generate_css('.has-background.has-primary-background-color:not(.has-text-color),.has-background.has-primary-background-color *:not(.has-text-color),.has-background.has-accent-background-color:not(.has-text-color),.has-background.has-accent-background-color *:not(.has-text-color)', 'color', '#' . $background);
		}

		// Borders color.
		if ($borders && $borders !== $borders_default) {
			twentytwenty_generate_css('.editor-styles-wrapper .wp-block-code, .editor-styles-wrapper pre, .editor-styles-wrapper .wp-block-preformatted pre, .editor-styles-wrapper .wp-block-verse pre, .editor-styles-wrapper fieldset, .editor-styles-wrapper .wp-block-table, .editor-styles-wrapper .wp-block-table *, .editor-styles-wrapper .wp-block-table.is-style-stripes, .editor-styles-wrapper .wp-block-latest-posts.is-grid li', 'border-color', $borders);
			twentytwenty_generate_css('.editor-styles-wrapper .wp-block-table caption, .editor-styles-wrapper .wp-block-table.is-style-stripes tbody tr:nth-child(odd)', 'background-color', $borders);
		}

		// Text color.
		if ($body && $body !== $body_default) {
			twentytwenty_generate_css('body .editor-styles-wrapper, .editor-post-title__block .editor-post-title__input, .editor-post-title__block .editor-post-title__input:focus', 'color', $body);
		}

		// Secondary color.
		if ($secondary && $secondary !== $secondary_default) {
			twentytwenty_generate_css('.editor-styles-wrapper figcaption, .editor-styles-wrapper cite, .editor-styles-wrapper .wp-block-quote__citation, .editor-styles-wrapper .wp-block-quote cite, .editor-styles-wrapper .wp-block-quote footer, .editor-styles-wrapper .wp-block-pullquote__citation, .editor-styles-wrapper .wp-block-pullquote cite, .editor-styles-wrapper .wp-block-pullquote footer, .editor-styles-wrapper ul.wp-block-archives li, .editor-styles-wrapper ul.wp-block-categories li, .editor-styles-wrapper ul.wp-block-latest-posts li, .editor-styles-wrapper ul.wp-block-categories__list li, .editor-styles-wrapper .wp-block-latest-comments time, .editor-styles-wrapper .wp-block-latest-posts time', 'color', $secondary);
		}

		// Header Footer Background Color.
		if ($header_footer_background && $header_footer_background !== $header_footer_background_default) {
			twentytwenty_generate_css('.editor-styles-wrapper .wp-block-pullquote::before', 'background-color', $header_footer_background);
		}
	} elseif ('classic-editor' === $type) {

		// Colors.
		// Accent color.
		if ($accent && $accent !== $accent_default) {
			twentytwenty_generate_css('body#tinymce.wp-editor.content a, body#tinymce.wp-editor.content a:focus, body#tinymce.wp-editor.content a:hover', 'color', $accent);
			twentytwenty_generate_css('body#tinymce.wp-editor.content blockquote, body#tinymce.wp-editor.content .wp-block-quote', 'border-color', $accent, '', ' !important');
			twentytwenty_generate_css('body#tinymce.wp-editor.content button, body#tinymce.wp-editor.content .faux-button, body#tinymce.wp-editor.content .wp-block-button__link, body#tinymce.wp-editor.content .wp-block-file__button, body#tinymce.wp-editor.content input[type=\'button\'], body#tinymce.wp-editor.content input[type=\'reset\'], body#tinymce.wp-editor.content input[type=\'submit\']', 'background-color', $accent);
		}

		// Background color.
		if ($background && $background !== $background_default) {
			twentytwenty_generate_css('body#tinymce.wp-editor.content', 'background-color', '#' . $background);
		}

		// Text color.
		if ($body && $body !== $body_default) {
			twentytwenty_generate_css('body#tinymce.wp-editor.content', 'color', $body);
		}

		// Secondary color.
		if ($secondary && $secondary !== $secondary_default) {
			twentytwenty_generate_css('body#tinymce.wp-editor.content hr:not(.is-style-dots), body#tinymce.wp-editor.content cite, body#tinymce.wp-editor.content figcaption, body#tinymce.wp-editor.content .wp-caption-text, body#tinymce.wp-editor.content .wp-caption-dd, body#tinymce.wp-editor.content .gallery-caption', 'color', $secondary);
		}

		// Borders color.
		if ($borders && $borders !== $borders_default) {
			twentytwenty_generate_css('body#tinymce.wp-editor.content pre, body#tinymce.wp-editor.content hr, body#tinymce.wp-editor.content fieldset,body#tinymce.wp-editor.content input, body#tinymce.wp-editor.content textarea', 'border-color', $borders);
		}
	}

	// Return the results.
	return ob_get_clean();
}

/**
 * Get accessible color for an area.
 *
 * @since Twenty Twenty 1.0
 *
 * @param string $area The area we want to get the colors for.
 * @param string $context Can be 'text' or 'accent'.
 * @return string Returns a HEX color.
 */
function rbyc_get_color_for_area($area = 'content', $context = 'text')
{

	// Get the value from the theme-mod.
	$settings = array(
		'content'       => array(
			'text'      => '#000000',
			'accent'    => '#002244',
			'secondary' => '#6d6d6d',
			'borders'   => '#dcd7ca',
		),
		'header-footer' => array(
			'text'      => '#000000',
			'accent'    => '#002244',
			'secondary' => '#6d6d6d',
			'borders'   => '#dcd7ca',
		),
	);

	// If we have a value return it.
	if (isset($settings[$area]) && isset($settings[$area][$context])) {
		return $settings[$area][$context];
	}

	// Return false if the option doesn't exist.
	return false;
}
