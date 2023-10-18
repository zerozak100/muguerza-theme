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

	wp_enqueue_script( 'wc-cart-fragments' );

	if ( is_product() ) {
		wp_enqueue_style(
			'micromodal',
			get_stylesheet_directory_uri() . '/css/micromodal.css',
			[],
			'1.0.0',
		);
		wp_enqueue_script(
			'micromodal',
			'https://unpkg.com/micromodal/dist/micromodal.min.js',
			[],
			'1.0.0'
		);
	}
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

include_once __DIR__ . '/inc/class-mg-medico-archive.php';
include_once __DIR__ . '/inc/mg-scripts.php';
include_once __DIR__ . '/inc/class-mg-cart.php';
include_once __DIR__ . '/inc/class-mg-coords.php';
include_once __DIR__ . '/inc/class-mg-order-unidades.php';
include_once 'inc/class-mg-api-membresias.php';
include_once 'inc/class-mg-product-archive.php';
include_once 'inc/class-mg-user.php';
include_once 'inc/class-mg-location.php';
// include_once 'inc/class-mg-locations.php';
include_once __DIR__ . '/inc/class-mg-unidad-selector.php';
include_once 'inc/class-mg-ajax.php';
include_once 'inc/class-mg-unidad.php';

include_once 'inc/shortcodes.php';
include_once 'inc/mg-helpers.php';
include_once 'inc/mg-template-functions.php';
include_once 'inc/mg-template-hooks.php';
include_once 'inc/import/drupal-importer.php';

// include_once 'inc/class-mg-product.php';

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
	echo "<pre>";
	// $order = wc_get_order( 53120235842 );
	// // var_dump( $order->get_meta( 'mgb_booking_data', true ) );
	// foreach( $order->get_items() as $item ) {
	// 	// new WC_Order_Item_Meta();
	// 	// var_dump( $item->get_id );
	// 	$bookable_item = new MG_Bookable_Order_Item( $item );

	// 	// $data = array(
	// 	// 	'name' => 'h',
	// 	// 	'first_last_name' => 'h',
	// 	// 	'second_last_name' => 'h',
	// 	// 	'datetime' => 'dt',
	// 	// );
	// 	// $bookable_item->saveBookings( array( $data ) );
	// 	// $bookable_item->update_meta_data( 'agenda', 'aa'  );
	// 	// $bookable_item->update_meta_data( , 'aa'  );
	// 	// $bookable_item->save();

	// 	var_dump( $bookable_item->getBookings() );
	// 	$bookings = $bookable_item->getBookings();

	// 	foreach( $bookings as $booking_id => $booking_item ) {
	// 		var_dump( $booking_item->getKey() );
	// 		var_dump( $booking_item->getLabel() );
	// 		// $bookable_item->update_meta_data( $booking_item->getKey(), $booking_item->getLabel()  );
	// 		// $bookable_item->save();
	// 	}

	// 	// $mgb_unique_id = wc_get_order_item_meta( $item->get_id(), 'mgb_unique_id', true );
	// 	// var_dump( $mgb_unique_id );
	// }

	// $data = array(
	// 	'name' => 'hola',
	// 	'name2' => 'hola2',
	// );
	// MG_Booking_Item_Session::create( 100, $data );

	// MG_Booking_Session::clean();
	// var_dump( MG_Booking_Session::getData() );

	// var_dump( get_term_by( 'name', 'Hospital Altagracia (León)', 'ubicacion' ) );

	echo "</pre>";
	
	// delete_post_meta_by_key( 'mg_location' );

}
add_action( 'wp_footer', 'mg_footerr' );


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



function mg_custom_override_checkout_fields( $fields ) {
	unset( $fields['billing']['billing_acuityscheduling_title'] );
    unset( $fields['billing']['billing_acuityscheduling_date'] );
    unset( $fields['billing']['billing_acuityscheduling_time'] );
    unset( $fields['billing']['acuityscheduling_date_aux'] );
    unset( $fields['billing']['acuityscheduling_date_select'] );
    unset( $fields['billing']['acuityscheduling_time_aux'] );
    unset( $fields['billing']['acuityscheduling_time_select'] );
    unset( $fields['billing']['acuityscheduling_calendar_aux'] );
    unset( $fields['billing']['acuityscheduling_appointment_aux'] );

	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'mg_custom_override_checkout_fields' );


function my_acf_google_map_api( $api ){
    $api['key'] = 'AIzaSyDLHEgck-NyHg9QBswGn2ayg65BiIo7kMo';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');


function shutdown() {
    $e = error_get_last();
    if ($e['type'] === E_ERROR) {

        // = format
            $msg = isset($e['message']) ? $e['message'] : '';
            $msg = str_replace(array('Uncaught Exception:','Stack trace:')
                          ,array('<b>Uncaught Exception:</b><br />',
                                    '<b>Stack trace:</b>'),$msg);
            $msg = preg_replace('/[a-z0-9_\-]*\.php/i','$1<u>$0</u>',$msg);
            $msg = preg_replace('/[0-9]/i','$1<em>$0</em>',$msg);
            $msg = preg_replace('/[\(\)#\[\]\':]/i','$1<ss>$0</ss>',$msg);

        // = render
        echo "<style>u{color:#ed6;text-decoration:none;}"
            ."b{color:#ddd;letter-spacing:1px;}"
            ."body{font-family:monospace;}"
            ."em{color:#cfc;font-style:normal;}ss{color:white;}"
            ."h2{letter-spacing:1px;font-size:1.5rem;color:#b8b;margin-top:0;}"
            ."br{margin-bottom:1.8rem;}"
            ."div{margin:3rem auto;line-height:1.4em;padding:2rem;"
            ."background-color:rgba(255,255,255,0.1);font-size:1.1rem;"
            ."border-radius:0.5rem;max-width:1000px;}"
            ."</style>"
            ."\n<body style='background-color:#101;color:#bab;'>"
            ."\n<div>"
            ."\n    <h2>Fatal PHP error</h2>"
            ."\n    <div>".nl2br($msg)."</div>"
            ."\n</div></body>";
    }
}

register_shutdown_function('shutdown');

function mg_load_oda_chatbot() {
	?>
	<script src="<?php echo get_stylesheet_directory_uri() . '/lib/chatbot-oda-23.08/settings.js'; ?>"></script>
   	<script src="<?php echo get_stylesheet_directory_uri() . '/lib/chatbot-oda-23.08/web-sdk.js'; ?>" onload="initSdk()"></script>
	<?php
}
add_action( 'wp_head', 'mg_load_oda_chatbot' );

/**
 * @param string $image Image
 * @param WC_Product $product Product
 * @param string $size Size
 * @param array $attr Attr
 * @param bool $placeholder Placeholder
 */
function mg_set_product_default_img_by_tipo_de_servicio( $image, $product, $size, $attr, $placeholder ) {
	if ( ! $product->get_image_id() ) {
		$attachment_id = mg_get_product_default_image_id( $product->get_id() );
		if ( $attachment_id ) {
			return wp_get_attachment_image( $attachment_id, $size, false, $attr );
		}
	}

	return $image;
}
add_filter( 'woocommerce_product_get_image', 'mg_set_product_default_img_by_tipo_de_servicio', 10, 5 );
// add_filter( 'woocommerce_placeholder_img', 'mg_set_default_img_by_tipo_de_servicio' );

/**
 * @param int $thumbnail_id
 * @param WP_Post $post
 */
function mg_product_thumbnail_id_set_default_image( $thumbnail_id, $post ) {
	if ( 'product' !== $post->post_type ) {
		return $thumbnail_id;
	}

	$attachment_id = mg_get_product_default_image_id( $post->ID );

	if ( $attachment_id ) {
		return $attachment_id;
	}

	return $thumbnail_id;
}
add_filter( 'post_thumbnail_id', 'mg_product_thumbnail_id_set_default_image', 10, 2 );
