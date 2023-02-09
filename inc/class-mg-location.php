<?php

class MG_Location {
    protected $data = array();

    /**
     * @return MG_Location | false
     */
    public static function find( $location_id ) {
        $locations = self::all();
        if ( isset ( $locations[ $location_id ] ) ) {
            return new self( $location_id );
        }
        return false;
    }

    public static function all() {
        return array(
            'l1' => array(
                'city_id' => 15, // Monterrey
                'unit_id' => 62, // Hospital San Nicolás
                'coords' => array(
                    'lat' => '40.97079',
                    'long' => '72.51374',
                ),
            ),
        );
    }

    public static function location_exists( $location_id ) {
        $locations = self::all();
        if ( isset ( $locations[ $location_id ] ) ) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_location( $location_id ) {
        $locations = self::all();
        return $locations[ $location_id ];
    }

    public function __construct( $location_id ) {
        if ( ! self::location_exists( $location_id ) ) {
            throw new Exception( 'Ubicación no existe' );
        }

        $this->data = self::get_location( $location_id );
    }

    public function get_city_id() {
        return $this->data[ 'city_id' ];
    }

    public function get_unit_id() {
        return $this->data[ 'unit_id' ];
    }
}