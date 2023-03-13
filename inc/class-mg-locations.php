<?php

class MG_Locations
{
    /** @property MG_User $user */
    public $user; // puede ser un usuario invitado (sin cuenta)
    public function __construct()
    {
        // add_action( 'init', array( $this, 'check_location' ) );
        add_action( 'init', array( $this, 'load_modal_selector' ) );
        // var_dump( MG_User::current() );
        $this->user = MG_User::current();
        // var_dump( $this->user->get_address() );
        add_action( 'wp_footer', function() {
            // $user = MG_User::current();
            // echo "<pre>";
            // echo var_dump( $user->get_geolocation() );
            // echo var_dump( $user->get_location() );
            // echo var_dump( $user->get_address() );
            // echo "</pre>";
        } );
    }

    public function load_modal_selector()
    {
        wp_enqueue_script( 'mg-location-selector', get_stylesheet_directory_uri() . '/js/mg-location-selector.js', false, MG_THEME_VERSION, true );
        wp_enqueue_script( 'mg-tabs', get_stylesheet_directory_uri() . '/js/mg-tabs.js', false, MG_THEME_VERSION, true );
        wp_enqueue_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDLHEgck-NyHg9QBswGn2ayg65BiIo7kMo&callback=initMap&v=weekly&libraries=places', [], false, true );

        ob_start();
        mg_get_template( 'location-selector.php' );
        $modal_content = ob_get_clean();

        
        $data = array(
            'modal_content'  => $modal_content,
            'locations'      => MG_Location::all(),
            'ajaxurl'        => admin_url( 'admin-ajax.php' ),
            'needs_location' => $this->needs_location(),
            // 'location_id' => $this->user->get_location(),
            // 'location_coords' => mg_get_location_coords(),
        );

        $location_id = $this->user->get_location();

        if ( $location_id ) {
            $data['location_id']     = $location_id;
            $data['location_coords'] = mg_get_location_coords( $location_id );
            $selectedLocation = MG_Location::get_location( $location_id );
            if ( $selectedLocation ) {
                $data['selectedLocation'] = $selectedLocation;
            }
        }

        $saved_address = mg_get_saved_address();
        if ( $saved_address ) {
            $data['selectedGeolocation'] = array(
                'formatted_address' => mg_get_saved_address(),
                'coords'            => $this->user->get_geolocation(),
            );
        }
        
        wp_localize_script( 'mg-location-selector', 'DATA', $data );
    }

    // public function check_location()
    // {
    //     if ( is_admin() ) {
    //         return;
    //     }

    //     $user = MG_User::current(); // puede ser un usuario invitado (sin cuenta)
    //     $user_location = $user->get_location();
    //     if ( ! $user_location ) {
    //         add_action( 'wp_footer', array( $this, 'ask_for_location' ) );
    //     }
    // }

    public function needs_location()
    {
        if ( is_admin() ) {
            return false;
        }

        $user_location = $this->user->get_location();
        if ( ! $user_location ) {
            return true;
        }

        return false;
    }

    // public static function get_locations() {
    //     return array(
    //         'l1' => array(
    //             'city_id' => 15, // Monterrey
    //             'unit_id' => 62, // Hospital San Nicolás
    //         ),
    //     );
    // }

    // public static function location_exists( $location_id ) {
    //     $locations = self::get_locations();
    //     if ( isset ( $locations[ $location_id ] ) ) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}

new MG_Locations();
