<?php

// TODO en cada request checar si existe una ubicación disponible pedirle que seleccione ubicación
class MG_User_Location {
    public function __construct() {
        add_action( 'init', array( $this, 'ask_for_location' ) );
        // despues de registrarse que se guarde la ubicacion seleccionada
    }

    public function ask_for_location() {
        $location_id = self::get();

        if ( ! $location_id ) {
            self::save( 'l1' );
        }
    }

    public static function save( $location ) {
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'mg_location', $location );
        } else {
            WC()->session->set( 'mg_location', $location );
        }
    }

    public static function get() {
        $location = false;

        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $location = get_user_meta( $user_id, 'mg_location', true );
        } else {
            $location = WC()->session->get( 'mg_location' );
        }

        return $location;
    }
}

new MG_User_Location();
