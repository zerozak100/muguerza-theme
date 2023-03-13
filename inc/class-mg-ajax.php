<?php

class MG_Ajax {
    public function __construct(  ) {
        try {
            add_action( 'woocommerce_init', array( $this, 'enable_wc_session_cookie' ) );

            add_action( 'wp_ajax_location_selector_save', array( $this, 'location_selector_save' ) );
            add_action( 'wp_ajax_nopriv_location_selector_save', array( $this, 'location_selector_save' ) );
            
            add_action( 'wp_ajax_order_nearest_locations', array( $this, 'order_nearest_locations' ) );
            add_action( 'wp_ajax_nopriv_order_nearest_locations', array( $this, 'order_nearest_locations' ) );
        } catch (Exception $e) {
            $this->handle_errors();
        }
    }

    public function enable_wc_session_cookie() {
        if( is_admin() ) {
            return;
        }

        if ( isset( WC()->session ) && ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true ); 
        }
    }

    public function location_selector_save() {
        $lat = $_POST[ 'geolocation_lat' ];
        $lng = $_POST[ 'geolocation_lng' ];
        $id  = $_POST[ 'location' ];
        $address = $_POST[ 'geolocation_address' ];
        
        $user = MG_User::current();

        $user->save_geolocation( $lat, $lng );
        $user->save_location( $id );
        $user->save_address( $address );

        wp_send_json( true );        
        wp_die();
    }

    public function order_nearest_locations() {
        $lat = $_POST[ 'lat' ];
        $lng = $_POST[ 'lng' ];

        $user_coords = array(
            'lat' => (float) $lat,
            'lng' => (float) $lng,
        );

        $ordered = mg_get_locations_in_order( MG_Location::all(), $user_coords );

        wp_send_json( $ordered );
        wp_die();
    }

    private function handle_errors() {

    }
}

new MG_Ajax();
