<?php

class MG_Coords {

    protected $latitude;
    protected $longitude;

    public function __construct( $lat, $lng ) {
        $this->latitude = ( float ) $lat;
        $this->longitude = ( float ) $lng;
    }

    public function get_latitude() {
        return $this->latitude;
    }

    public function get_longitude() {
        return $this->longitude;
    }

    public function get_latitude_rad() {
        return deg2rad( $this->latitude );
    }

    public function get_longitude_rad() {
        return deg2rad( $this->longitude );
    }
}
