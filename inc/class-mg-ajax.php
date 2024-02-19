<?php

class MG_Ajax {
    public function __construct(  ) {
        try {
            add_action( 'woocommerce_init', array( $this, 'enable_wc_session_cookie' ) );

            add_action( 'wp_ajax_unidad_selector_save', array( $this, 'unidad_selector_save' ) );
            add_action( 'wp_ajax_nopriv_unidad_selector_save', array( $this, 'unidad_selector_save' ) );

            add_action( 'wp_ajax_unidad_selector_save_user_unidad', array( $this, 'unidad_selector_save_user_unidad' ) );
            add_action( 'wp_ajax_nopriv_unidad_selector_save_user_unidad', array( $this, 'unidad_selector_save_user_unidad' ) );

            add_action( 'wp_ajax_unidad_selector_save_user_location', array( $this, 'unidad_selector_save_user_location' ) );
            add_action( 'wp_ajax_nopriv_unidad_selector_save_user_location', array( $this, 'unidad_selector_save_user_location' ) );
            
            add_action( 'wp_ajax_order_unidades_by_distance', array( $this, 'order_unidades_by_distance' ) );
            add_action( 'wp_ajax_nopriv_order_unidades_by_distance', array( $this, 'order_unidades_by_distance' ) );
        } catch (Exception $e) {
            $this->handle_errors();
        }
    }

    public function unidad_selector_save_user_unidad() {
        $unidad_id = sanitize_text_field( $_POST['unidad_id'] );

        if ( $unidad_id ) {
            $user = MG_User::current();
            $user->save_unidad( $unidad_id );

            wp_send_json( array(
                'ok' => true,
                'message' => 'OK',
                'payload' => $user,
            ) );
        } else {
            wp_send_json( array(
                'ok' => false,
                'message' => 'Unidad ID es requerido',
            ) );
        }

        wp_die();
    }

    public function unidad_selector_save_user_location() {
        $location_data = $_POST['location'];


    }

    public function enable_wc_session_cookie() {
        if( is_admin() ) {
            return;
        }

        if ( isset( WC()->session ) && ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true ); 
        }
    }

    /**
     * Guardar ubicación del usuario y unidad seleccionada
     */
    public function unidad_selector_save() {
        $unidad_id     = sanitize_text_field( $_POST['unidad_id'] );
        $location_data = json_decode( stripslashes( $_POST['location'] ), true );

        $location = new MG_Location( $location_data );
        
        $user = MG_User::current();
        $user->save_location( $location );
        $user->save_unidad( $unidad_id );

        WC()->cart->empty_cart();
        MG_Booking_Session::clean();

        wp_send_json( true );
        wp_die();
    }

    public function order_unidades_by_distance() {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];

        $user_coords = new MG_Coords( $lat, $lng );

        $ordered = mg_order_unidades_by_distance( MG_Unidad::withLocation(), $user_coords );

        wp_send_json( $ordered );
        wp_die();
    }

    private function handle_errors() {

    }
}

new MG_Ajax();
