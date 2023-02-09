<?php

class MG_User {

    /**
     * @property WP_User $user;
     */
    protected $user;

    protected $geolocation; // ubicación actual
    protected $location; // unidad médica seleccionada

    protected $is_guest = true; // si es guest podemos manejar una clase separada a la de mg user

    public static function current() {
        $user_id = get_current_user_id();
        return new self( $user_id );
    }

    public function __construct( $user_id ) {
        $user = get_user_by( 'ID', $user_id );
        if ( $user ) {
            $this->user = $user;
            $this->is_guest = false;
            $this->geolocation = $this->get_geolocation();
            $this->location = $this->get_location();
        }
    }

    public function get_geolocation() {
        return $this->get_prop( 'mg_geolocation' );
    }

    public function get_location() {
        return $this->get_prop( 'mg_location' );
    }

    public function save_geolocation( $lat, $long ) {
        $geolocation = array(
            'lat' => $lat,
            'long' => $long,
        );

        $this->save_prop( 'mg_geolocation', $geolocation );
    }

    public function save_location( $location_id ) {
        $this->save_prop( 'mg_location', $location_id );
    }

    private function save_prop( $name, $value ) {
        if ( $this->is_guest ) {
            WC()->session->set( $name, $value );
        } else {
            update_user_meta( $this->user->ID, $name, $value );
        }
    }

    private function get_prop( $name ) {
        $prop = false;

        if ( $this->is_guest ) {
            $prop = WC()->session->get( $name );
        } else {
            $prop = get_user_meta( $this->user->ID, $name, true );
        }

        return $prop;
    }
}
