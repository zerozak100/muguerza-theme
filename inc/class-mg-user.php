<?php

class MG_User implements JsonSerializable {

    /**
     * @property WP_User $user;
     */
    protected $user;

    protected $geolocation; // ubicación actual
    protected $location; // unidad médica seleccionada

    protected $is_guest = true; // si es guest podemos manejar una clase separada a la de mg user

    /**
     * @var array $data
     */
    protected $data = array(
        'id'        => '',
        'name'      => '',
        'last_name' => '',
        'fullname'  => '',
        'unidad'    => '',
        'location'  => '',
        'is_guest'  => '',
    );

    public static function current() {
        $user_id = get_current_user_id();
        return new self( $user_id );
    }

    public function __construct( $user = false ) {
        if ( $user instanceof WP_User ) {
            $this->user = $user;
        } else {
            $this->user = get_user_by( 'ID', $user );
        }

        $this->load_data();
    }

    public function load_data() {
        $this->is_guest = ! boolval( $this->user );

        if ( $this->is_guest ) {
            $unidad_id      = WC()->session->get( 'mg_unidad' );
            $location_data  = WC()->session->get( 'mg_location', array() );
        } else {
            $unidad_id      = get_user_meta( $this->user->ID, 'mg_unidad', true ) ?: 0;
            $location_data  = get_user_meta( $this->user->ID, 'mg_location', true ) ?: array();
        }

        $unidad   = new MG_Unidad( $unidad_id );
        $location = new MG_Location( $location_data );

        $this->data = array(
            'id'        => $this->user ? $this->user->ID : '',
            'name'      => $this->user ? $this->user->first_name : '',
            'last_name' => $this->user ? $this->user->last_name : '',
            'fullname'  => $this->user ? sprintf( '%s %s', $this->user->first_name, $this->user->last_name ) : '',
            'unidad'    => $unidad,
            'location'  => $location,
            'is_guest'  => $this->is_guest,
        );
    }

    /**
     * @return MG_Location
     */
    public function get_location() {
        return $this->get_prop( 'location' );
    }

    /**
     * @return MG_Unidad
     */
    public function get_unidad() {
        return $this->get_prop( 'unidad' );
    }

    /**
     * @param MG_Location $location
     */
    public function save_location( MG_Location $location ) {
        $this->save_prop( 'mg_location', $location->get_data() );
        $this->data['location'] = new MG_Location( $location->get_data() );
    }

    public function save_unidad( $unidad_id ) {
        $this->save_prop( 'mg_unidad', $unidad_id );
        $this->data['unidad'] = new MG_Unidad( $unidad_id );

    }

    private function save_prop( $name, $value ) {
        if ( $this->is_guest ) {
            WC()->session->set( $name, $value );
        } else {
            update_user_meta( $this->user->ID, $name, $value );
        }
    }

    private function get_prop( $name ) {
        return $this->data[ $name ];
    }

    public function jsonSerialize() {
        return $this->data;
    }
}
