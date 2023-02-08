<?php

class MG_Locations {
    public static function get_locations() {
        return array(
            'l1' => array(
                'city_id' => 15, // Monterrey
                'unit_id' => 62, // Hospital San Nicolás
            ),
        );
    }

    public static function location_exists( $location_id ) {
        $locations = self::get_locations();
        if ( isset ( $locations[ $location_id ] ) ) {
            return true;
        } else {
            return false;
        }
    }
}