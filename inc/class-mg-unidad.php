<?php

class MG_Unidad implements JsonSerializable {

    public $post;
    public $destinatarios_by_form = array();
    public $acf_fields;
    const FORM_TYPES = array(
        'servicios_y_cotizaciones',
        'informacion_general',
        'quejas_sugerencias_y_felicitaciones',
    );

    /**
     * @var WP_Term
     */
    public $product_cat_city;

    /**
     * @var WP_Term
     */
    public $product_cat_unit;

    public $data = array(
        'id'                  => '',
        'name'                => '',
        'location'            => '',
        'product_cat_city_id' => '',
        'product_cat_unit_id' => '',
    );

    /**
     * @return MG_Unidad[]
     */
    public static function all() {
        $args = array(
            'post_type'      => 'unidad',
            'posts_per_page' => -1,
        );

        $posts       = get_posts( $args );
        $mg_unidades = array();

        foreach ( $posts as $post ) {
            $mg_unidades[] = new self( $post );
        }

        return $mg_unidades;
    }

    /**
     * @return MG_Unidad[]
     */
    public static function withLocation() {
        $args = array(
            'post_type'      => 'unidad',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'ubicacion_mapa',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key'     => 'ubicacion_mapa',
                    'value'   => '',
                    'compare' => '!=',
                ),
            ),
        );

        $posts       = get_posts( $args );
        $mg_unidades = array();

        foreach ( $posts as $post ) {
            $mg_unidades[] = new self( $post );
        }

        return $mg_unidades;
    }

    public static function from_product_cat( $product_cat_id ) {
        $unidad_id = false;

        $unidades_ids = get_posts( array(
            'fields' 		 => 'ids',
            'post_type' 	 => 'unidad',
            'posts_per_page' => 1,
            'meta_query' 	 => array(
                'AND',
                array(
                    'key'   => 'ubicacion',
                    'value' => $product_cat_id,
                ),
            ),
        ) );

        if ( $unidades_ids ) {
            $unidad_id = $unidades_ids[0];
        }

        return new self( $unidad_id );
    }

    public function __construct( $post = 0 ) {
        if ( $post instanceof WP_Post ) {
            $this->post = $post;
        } else if ( is_numeric( $post ) && $post > 0 ) {
            $this->post = get_post( $post );
        }

        if ( $this->post ) {
            $this->load_data();
        }
    }

    private function load_data() {
        $this->acf_fields = get_fields( $this->post->ID );
        $this->set_destinatarios();
        $this->set_product_cat_terms();

        $this->data = array(
            'id'                  => $this->post->ID,
            'name'                => $this->post->post_title,
            'location'            => $this->acf_fields['ubicacion_mapa'] ? MG_Location::from_acf( $this->acf_fields['ubicacion_mapa'] ) : '',
            'product_cat_city_id' => $this->product_cat_city ? $this->product_cat_city->term_id : '',
            'product_cat_unit_id' => $this->product_cat_unit ? $this->product_cat_unit->term_id : '',
            'destinatarios'       => $this->destinatarios_by_form,
        );
    }

    private function set_product_cat_terms() {
        $product_cat_unit_id    = $this->acf_fields['ubicacion'];
        $product_cat_unit       = get_term( $product_cat_unit_id, 'product_cat' );
        $this->product_cat_unit = $product_cat_unit;

        if ( ! is_wp_error( $product_cat_unit ) ) {
            $product_cat_city_id    = $product_cat_unit->parent;
            $product_cat_city       = get_term( $product_cat_city_id, 'product_cat' );
            $this->product_cat_city = $product_cat_city;
        }

        // echo "<pre>";
        // var_dump( $this->post->post_title );
        // var_dump( $this->acf_fields['ubicacion'] );
        // var_dump( $this->acf_fields );
        // var_dump( $this->product_cat_unit );
        // var_dump( $this->product_cat_city );
        // echo "</pre>";
    }

    public function set_destinatarios() {
        if ( isset( $this->acf_fields['destinatarios_formularios'] ) ) {
            foreach ( $this->acf_fields['destinatarios_formularios'] as $form_type => $destinatarios ) {
                if ( ! empty( $destinatarios ) ) {
                    $this->destinatarios_by_form[ $form_type ] = array_column( $destinatarios, 'email' );
                }
            }
        }
    }

    public function has_destinatarios( $form_type ) {
        if (
            isset( $this->destinatarios_by_form[ $form_type ] ) 
            && is_array( $this->destinatarios_by_form[ $form_type ] )
            && ! empty( $this->destinatarios_by_form[ $form_type ] )
        ) {
            return true;
        }

        return false;
    }

    public function get_destinatarios( $form_type ) {
        if ( isset( $this->destinatarios_by_form[ $form_type ] ) ) {
            return $this->destinatarios_by_form[ $form_type ];
        }

        return array();
    }

    public function get_product_cat_city_id() {
        return $this->get_prop( 'product_cat_city_id' );
    }

    public function get_product_cat_unit_id() {
        return $this->get_prop( 'product_cat_unit_id' );
    }

    /**
     * @return MG_Location
     */
    public function get_location() {
        return $this->get_prop( 'location' );
    }

    public function get_id() {
        return $this->get_prop( 'id' );
    }

    public function get_name() {
        return $this->get_prop( 'name' );
    }

    public function get_data() {
        return $this->data;
    }

    protected function get_prop( $key ) {
        return isset( $this->data[ $key ] ) ? $this->data[ $key ] : '';
    }

    public function jsonSerialize() {
        return $this->data;
        // return array(
        //     'id'    => $this->get_id(),
        //     'name'  => $this->get_name(),
        //     'product_cat_city_id' => $this->get_product_cat_city_id(),
        //     'product_cat_unit_id' => $this->get_product_cat_unit_id(),
        //     'location' => $this->get_location,

        //     // 'city_id' => 15, // Monterrey
        //     // 'unit_id' => 47, // Hospital Alta Especialidad

        //     // 'coords' => array(
        //     //     'lat' => $this->get_latitude(),
        //     //     'lng' => $this->get_longitude(),
        //     // ),
        //     // 'address' => $this->get_longitude(),
        //     // 'state' => 'Nuevo León',
        // );
    }

}

add_action( 'wp_footer', function() {
    // $unidad = new MG_Unidad( 53120233115 );

    // echo "<pre>";
    // var_dump( json_encode( $unidad ) );
    // var_dump( $unidad->get_data() );
    // echo "</pre>";
    // die();
} );

