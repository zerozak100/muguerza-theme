<?php

// TODO en cada request checar si existe una ubicación disponible pedirle que seleccione ubicación
class MG_User_Location {
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
