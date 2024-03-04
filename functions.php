<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

 define( 'MG_TEMPLATES_PATH', get_stylesheet_directory() . '/templates//' );
 define( 'MG_THEME_VERSION', '2.0.7' );

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
			MG_THEME_VERSION,
		);
		wp_enqueue_script(
			'micromodal',
			'https://unpkg.com/micromodal/dist/micromodal.min.js',
			[],
			MG_THEME_VERSION,
		);
	}

	if ( is_account_page() ) {
		wp_enqueue_script(
			'mg-myaccount',
			get_stylesheet_directory_uri() . '/js/myaccount.js',
			[],
			MG_THEME_VERSION,
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

include_once __DIR__ . '/inc/class-mg-checkout.php';

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

// function mg_custom_override_checkout_fields( $fields ) {
// 	unset( $fields['billing']['billing_acuityscheduling_title'] );
//     unset( $fields['billing']['billing_acuityscheduling_date'] );
//     unset( $fields['billing']['billing_acuityscheduling_time'] );
//     unset( $fields['billing']['acuityscheduling_date_aux'] );
//     unset( $fields['billing']['acuityscheduling_date_select'] );
//     unset( $fields['billing']['acuityscheduling_time_aux'] );
//     unset( $fields['billing']['acuityscheduling_time_select'] );
//     unset( $fields['billing']['acuityscheduling_calendar_aux'] );
//     unset( $fields['billing']['acuityscheduling_appointment_aux'] );

// 	return $fields;
// }
// add_filter( 'woocommerce_checkout_fields' , 'mg_custom_override_checkout_fields' );


function my_acf_google_map_api( $api ){
    $api['key'] = MG_GOOGLE_MAP_API_KEY;
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
// add_action( 'wp_head', 'mg_load_oda_chatbot' );

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

function muguerza_get_related_product_by_tipos_servicios_terms( $terms, $product_id ) {
    return wc_get_product_term_ids( $product_id, 'tipos_servicios' );
}
add_filter('woocommerce_get_related_product_cat_terms', 'muguerza_get_related_product_by_tipos_servicios_terms', 10, 2);

remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'muguerza_checkout_after_form_fields', 'woocommerce_checkout_payment', 20 );

/**
 * WooCommerce Post Class filter.
 *
 * @since 3.6.2
 * @param array      $classes Array of CSS classes.
 * @param WC_Product $product Product object.
 */
function muguerza_set_product_loop_item_classes( $classes, $product ) {
	$mg_product = new MG_Product( $product );

	if ( $mg_product->is_especialidad() ) {
		$classes[] = 'mg-especialidad';
	}

	if ( $mg_product->is_servicio() ) {
		$classes[] = 'mg-servicio';
	}

	return $classes;
}
add_action( 'woocommerce_post_class', 'muguerza_set_product_loop_item_classes', 10, 2 );

function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
    global $woocommerce;
    extract( $_POST );
    if ( strcmp( $password, $password_confirm ) !== 0 ) {
        return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
    }

    return $reg_errors;
}
add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10,3);

function my_woocommerce_add_error( $error ) {
    return str_replace('An account is already registered with your email address. Please log in','sdfhasd il address. Please log in.',$error);    
}
add_filter( 'woocommerce_add_error', 'my_woocommerce_add_error', 99, 1 );

function muguerza_shop_pagination_args( $args ) {
	if ( wp_is_mobile() ) {
		$args['end_size'] = 1;
		$args['mid_size'] = 1;
	} else {
		$args['end_size'] = 2;
		$args['mid_size'] = 1;
	}

	return $args;
}
add_action( 'woocommerce_pagination_args', 'muguerza_shop_pagination_args' );

//campos formulario registro woocommerce
function bbloomer_extra_register_select_field() {
    $args = array('post_type' => 'unidad', 'numberposts' => -1);
    $posts = get_posts($args);
?>
    <p class="form-row form-row-wide">
        <label for="names"><?php _e( 'Nombre(s)', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="text" name="nombres_register" id="nombres_register" />
    </p>
    <p class="form-row form-row-wide">
        <label for="apellido-paterno"><?php _e( 'Apellido paterno', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="text" name="apellido_paterno_register" id="apellido_paterno_register" />
    </p>
    <p class="form-row form-row-wide">
        <label for="apellido-materno"><?php _e( 'Apellido materno', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="text" name="apellido_materno_register" id="apellido_materno_register" />
    </p>
    <p class="form-row form-row-wide">
        <label for="telefono"><?php _e( 'Teléfono', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="tel" name="telefono_register" id="telefono" />
    </p>
    <p class="form-row form-row-wide">
        <label for="find_where"><?php _e( 'Unidad médica', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <select name="unidad_medica_register" id="unidad_medica_register">
                <?php
                    foreach($posts as $post) {
                        echo '<option value="' . $post->post_name . '">' . $post->post_title . '</option>';
                    }
                ?>
        </select>
    </p>
<?php  
}
add_action( 'woocommerce_register_form', 'bbloomer_extra_register_select_field' );
//guardar datos 
function bbloomer_save_extra_register_select_field( $customer_id ) {
    if ( isset( $_POST['nombres_register'] ) && isset( $_POST['apellido_paterno_register'] ) && isset( $_POST['apellido_materno_register'] ) && isset( $_POST['telefono_register'] ) && isset( $_POST['unidad_medica_register'] ) ) {
        update_user_meta( $customer_id, 'nombres_register', $_POST['nombres_register'] );
        update_user_meta( $customer_id, 'apellido_paterno_register', $_POST['apellido_paterno_register'] );
        update_user_meta( $customer_id, 'apellido_materno_register', $_POST['apellido_materno_register'] );
        update_user_meta( $customer_id, 'telefono_register', $_POST['telefono_register'] );
        update_user_meta( $customer_id, 'unidad_medica_register', $_POST['unidad_medica_register'] );
    }
}
add_action( 'woocommerce_created_customer', 'bbloomer_save_extra_register_select_field' );
//mostrar datos en perfil
function bbloomer_show_extra_register_select_field($user){ 
    $args = array('post_type' => 'unidad', 'numberposts' => -1);
    $posts = get_posts($args);

    if (empty ($user) ) {
        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
    }
    
?>
    <p class="form-row form-row-wide">
        <label for="names"><?php _e( 'Nombre(s)', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="text" name="nombres_register" id="nombres_register" value="<?php echo get_the_author_meta( 'nombres_register', $user->ID ); ?>" />
    </p>
    <p class="form-row form-row-wide">
        <label for="apellido-paterno"><?php _e( 'Apellido paterno', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="text" name="apellido_paterno_register" id="apellido_paterno_register" value="<?php echo get_the_author_meta( 'apellido_paterno_register', $user->ID ); ?>" />
    </p>
    <p class="form-row form-row-wide">
        <label for="apellido-materno"><?php _e( 'Apellido materno', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="text" name="apellido_materno_register" id="apellido_materno_register" value="<?php echo get_the_author_meta( 'apellido_materno_register', $user->ID ); ?>" />
    </p>
    <p class="form-row form-row-wide">
        <label for="telefono"><?php _e( 'Teléfono', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <input type="tel" name="telefono_register" id="telefono" value="<?php echo get_the_author_meta( 'telefono_register', $user->ID ); ?>" />
    </p>
    <p class="form-row form-row-wide">
        <label for="find_where"><?php _e( 'Unidad médica', 'woocommerce' ); ?>  <span class="required">*</span></label>
        <select name="unidad_medica_register" id="unidad_medica_register">
                <?php
                    foreach($posts as $post) {
                        if( get_the_author_meta( 'unidad_medica_register', $user->ID ) == $post->post_name ) {
                            $selected = 'selected=selected';
                            echo '<option value="' . $post->post_name . '" ' . $selected . '>' . $post->post_title . '</option>';
                        }else{
                            echo '<option value="' . $post->post_name . '">' . $post->post_title . '</option>';
                        }
                        
                    }
                ?>
        </select>
    </p>
  
<?php
  
}
add_action( 'show_user_profile', 'bbloomer_show_extra_register_select_field', 30 );
add_action( 'edit_user_profile', 'bbloomer_show_extra_register_select_field', 30 ); 
add_action( 'woocommerce_edit_account_form', 'bbloomer_show_extra_register_select_field', 30 );
//actualizar datos en admin
function bbloomer_save_extra_register_select_field_admin( $customer_id ){
    if ( isset( $_POST['nombres_register'] ) && isset( $_POST['apellido_paterno_register'] ) && isset( $_POST['apellido_materno_register'] ) && isset( $_POST['telefono_register'] ) && isset( $_POST['unidad_medica_register'] ) ) {
        update_user_meta( $customer_id, 'nombres_register', $_POST['nombres_register'] );
        update_user_meta( $customer_id, 'apellido_paterno_register', $_POST['apellido_paterno_register'] );
        update_user_meta( $customer_id, 'apellido_materno_register', $_POST['apellido_materno_register'] );
        update_user_meta( $customer_id, 'telefono_register', $_POST['telefono_register'] );
        update_user_meta( $customer_id, 'unidad_medica_register', $_POST['unidad_medica_register'] );
    }
}
add_action( 'personal_options_update', 'bbloomer_save_extra_register_select_field_admin' );    
add_action( 'edit_user_profile_update', 'bbloomer_save_extra_register_select_field_admin' );   
add_action( 'woocommerce_save_account_details', 'bbloomer_save_extra_register_select_field_admin' );
