<?php

class MG_Locations
{
    public function __construct()
    {
        add_action( 'init', array( $this, 'check_location' ) );
    }

    public function check_location()
    {
        if ( is_admin() ) {
            return;
        }

        $user = MG_User::current(); // puede ser un usuario invitado (sin cuenta)
        $user_location = $user->get_location();
        if ( ! $user_location ) {
            wp_enqueue_script( 'mg-location-selector', get_stylesheet_directory_uri() . '/js/mg-location-selector.js', false, MG_THEME_VERSION, true );
            wp_enqueue_script( 'mg-tabs', get_stylesheet_directory_uri() . '/js/mg-tabs.js', false, MG_THEME_VERSION, true );
            wp_enqueue_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDLHEgck-NyHg9QBswGn2ayg65BiIo7kMo&callback=initMap&v=weekly&libraries=places', [], false, true );
            add_action( 'wp_footer', array( $this, 'ask_for_location' ) );
        }
    }

    public function ask_for_location()
    {
        ob_start();
        mg_get_template( 'location-selector.php' );
        $modal_content = ob_get_clean();
        
        $data = array(
            'modal_content' => $modal_content,
            'locations' => MG_Location::all(),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        );

        wp_localize_script( 'mg-location-selector', 'DATA', $data );
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
