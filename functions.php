<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

 define( 'MG_TEMPLATES_PATH', get_stylesheet_directory() . '/templates//' );
 define( 'MG_THEME_VERSION', '0.0.1' );

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'default',
		get_stylesheet_directory_uri() . '/css/default.css',
		[],
		MG_THEME_VERSION,
	);

	wp_enqueue_style(
		'tingle',
		get_stylesheet_directory_uri() . '/lib/tingle/dist/tingle.min.css',
		[],
		'0.16.0',
	);

	wp_enqueue_style(
		'toastify',
		get_stylesheet_directory_uri() . '/lib/toastify-js/toastify.css',
		[],
		'1.12.0',
	);

	wp_enqueue_style(
		'tipografia-gotham',
		get_stylesheet_directory_uri() . '/css/tipografia-gotham.css',
		[],
		MG_THEME_VERSION,
	);

	wp_enqueue_style(
		'muguerza-theme',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		MG_THEME_VERSION,
	);

	wp_enqueue_script(
		'tingle',
		get_stylesheet_directory_uri() . '/lib/tingle/dist/tingle.min.js',
		[],
		'0.16.0',
	);

	wp_enqueue_script(
		'toastify',
		get_stylesheet_directory_uri() . '/lib/toastify-js/toastify.js',
		[],
		'1.12.0',
	);

	wp_enqueue_style(
		'fontawesome6',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		[],
		'6.4.0',
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

function mg_load_bootstrap() {
	wp_enqueue_style(
		'bootstrap',
		get_stylesheet_directory_uri() . '/lib/bootstrap-5.0.2-dist/css/bootstrap.min.css',
		// "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css",
		// [ 'hello-elementor', 'hello-elementor-theme-style' ],
		[],
		'5.3.0',
	);
	wp_enqueue_script(
		'bootstrap',
		// "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js",
		get_stylesheet_directory_uri() . '/lib/bootstrap-5.0.2-dist/js/bootstrap.min.js',
		[],
		'5.3.0',
	);
}
add_action( 'wp_enqueue_scripts', 'mg_load_bootstrap', 9 );

include_once 'inc/class-mg-api-membresias.php';

include_once 'inc/class-mg-user-location.php';
include_once 'inc/class-mg-product-archive.php';
include_once 'inc/class-mg-user.php';
include_once 'inc/class-mg-location.php';
include_once 'inc/class-mg-locations.php';
include_once 'inc/class-mg-ajax.php';

include_once 'inc/shortcodes.php';
include_once 'inc/mg-helpers.php';
include_once 'inc/mg-template-functions.php';
include_once 'inc/mg-template-hooks.php';
include_once 'inc/import/drupal-importer.php';

include_once __DIR__ . '/custom-functions.php';

// TODO https://www.google.com/search?q=woocommerce+filters+combinations+not+show&oq=woocommerce+filters+combinations+not+show&aqs=chrome..69i57j33i160l4.12020j0j7&sourceid=chrome&ie=UTF-8
// https://stackoverflow.com/questions/41721296/woocommerce-variations-not-filtering-out-invalid-combinations
// https://stackoverflow.com/questions/71166565/how-do-i-make-these-combination-select-filters-work-when-only-one-dropdown-is-se
// add_action('init', 'show_template');
function show_template() {
    // global $template;
    // echo basename($template);
	// $API = new MG_API_Membresias();
	// $response = $API->consultarMembresia( 'EDGAR.MONREAL950@ICLOUD.COM' );
	// echo "<pre>";
	// var_dump( $response );
	// echo "</pre>";
}

/**
 * @param string $user_login User login
 * @param WP_User $user Usuario
 */
function mg_save_membresia( $user_login, $user ) {
	$API = new MG_API_Membresias();
	$response = $API->consultarMembresia( $user->user_email );
	if ( $response ) {
		update_user_meta( $user->ID, 'mg_membresia_data', $response->data );
	}
}
// add_action( 'wp_login', 'mg_save_membresia', 10, 2 );

function mg_footerr() {
	dd( is_page( 'ubicaciones' ) );
}
// add_action( 'wp_footer', 'mg_footerr' );


function storefront_cart_link() {
	get_template_part('templates/part-header-carrito');
}

function storefront_cart_link_fragment( $fragments ) {
	global $woocommerce;

	ob_start();
	storefront_cart_link();
	$fragments['a.cart-contents'] = ob_get_clean();

	// ob_start();
	// storefront_handheld_footer_bar_cart_link();
	// $fragments['a.footer-cart-contents'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'storefront_cart_link_fragment' );

