<?php

class MG_Location implements JsonSerializable {

    protected $address;

    protected $lat;

    protected $lng;

    protected $name;

    protected $zoom;

    protected $place_id;

    protected $street_number;

    protected $street_name;

    protected $city;

    protected $state;

    protected $state_short;

    protected $post_code;

    protected $country;

    protected $country_short;

    protected $data = array(
        'address'       => '',
        'lat'           => '',
        'lng'           => '',
        'name'          => '',
        'zoom'          => '',
        'place_id'      => '',
        'street_number' => '',
        'street_name'   => '',
        'city'          => '',
        'state'         => '',
        'state_short'   => '',
        'post_code'     => '',
        'country'       => '',
        'country_short' => '',
    );

    public static function from_acf( array $data ) {
        return new self( $data );
    }

    public function __construct( array $data ) {
        $this->data = array_intersect_key( $data, $this->data );
    }

    public function get_address() {
        return $this->get_prop( 'address' );
    }

    public function get_lat() {
        return $this->get_prop( 'lat' );
    }

    public function get_lng() {
        return $this->get_prop( 'lng' );
    }

    public function get_coords() {
        return new MG_Coords( $this->get_lat(), $this->get_lng() );
    }

    public function get_data() {
        return $this->data;
    }

    public function get_prop( $name ) {
        return $this->data[ $name ];
    }

    public function jsonSerialize() {
        return $this->data;
    }
}