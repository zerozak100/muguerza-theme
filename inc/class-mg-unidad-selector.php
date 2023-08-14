<?php

class MG_Unidad_Selector {
    /**
     * @var MG_User $user Puede ser un usuario invitado (sin cuenta)
     */
    public $user;

    private $apiKey = 'AIzaSyDLHEgck-NyHg9QBswGn2ayg65BiIo7kMo';

    public static function order_unidades_by_distance() {

    }

    public static function init() {
        new self();
    }

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'load_modal_selector' ) );
        $this->user = MG_User::current();
    }

    public function load_modal_selector() {
        if ( is_admin() ) {
            return;
        } 

        wp_enqueue_script( 'mg-location-selector', get_stylesheet_directory_uri() . '/js/mg-location-selector.js', false, MG_THEME_VERSION, true );
        wp_enqueue_script( 'mg-tabs', get_stylesheet_directory_uri() . '/js/mg-tabs.js', false, MG_THEME_VERSION, true );
        $this->load_google_maps();

        $unidades_ids   = array();
        $unidades_by_id = array();

        foreach ( MG_Unidad::withLocation() as $unidad ) {
            $unidad_id                    = $unidad->get_id();
            $unidades_ids[]               = $unidad_id;
            $unidades_by_id[ $unidad_id ] = $unidad;
        }

        
        $data = array(
            'modal_content'  => $this->get_modal_content(),
            'unidades_ids'   => $unidades_ids,
            'unidades_by_id' => $unidades_by_id,
            'ajaxurl'        => admin_url( 'admin-ajax.php' ),
            'needs_unidad'   => $this->needs_unidad(),
            'user'           => $this->user,
        );

        $user_unidad = $this->user->get_unidad();

        if ( $user_unidad->get_id() ) {
            $data['unidad'] = $user_unidad;
        }
        
        wp_localize_script( 'mg-location-selector', 'DATA', $data );
    }

    public function needs_unidad() {
        if ( is_admin() ) {
            return false;
        }

        $unidad = $this->user->get_unidad();

        if ( ! $unidad->get_id() && ( is_page( array( 'servicios', 'especialidades' ) ) || is_shop() ) ) {
            return true;
        }

        return false;
    }

    public function get_modal_content() {
        ob_start();
        mg_get_template( 'unidad-selector/modal.php', array( 'unidad_selector' => $this ) );
        return ob_get_clean();
    }

    public function display_unidades() {
        $unidades = MG_Unidad::withLocation();

        foreach ( $unidades as $unidad ) {
            mg_get_template( 'unidad-selector/list-item.php', array( 'unidad' => $unidad ) );
        }
    }

    private function load_google_maps() {
        $params = array(
            'key'       => $this->apiKey,
            'callback'  => 'initMap',
            'v'         => 'weekly',
            'libraries' => 'places',
        );

        $url = add_query_arg( $params, 'https://maps.googleapis.com/maps/api/js' );

        wp_enqueue_script( 'googlemaps', $url, [], false, true );
    }
}

add_action( 'template_redirect', array( MG_Unidad_Selector::class, 'init' ) );
