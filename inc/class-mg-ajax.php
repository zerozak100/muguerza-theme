<?php

class MG_Ajax {
    public function __construct(  ) {
        try {
            add_action( 'woocommerce_init', array( $this, 'enable_wc_session_cookie' ) );

            add_action( 'wp_ajax_location_selector_save', array( $this, 'location_selector_save' ) );
            add_action( 'wp_ajax_nopriv_location_selector_save', array( $this, 'location_selector_save' ) );
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

    private function handle_errors() {

    }
}

new MG_Ajax();
