<?php

class MG_Medico_Archive {

    /**
     * @return WP_Term[]
     */
    public static function get_especialidades() {
        $args = array(
            'taxonomy' => 'especialidades',
        );

        $especialidades = get_terms( $args );

        return $especialidades;
    }

    /**
     * @return WP_Term[]
     */
    public static function get_ubicaciones() {
        $args = array(
            'taxonomy' => 'ubicacion',
        );

        $ubicaciones = get_terms( $args );

        return $ubicaciones;
    }

    /**
     * @return string
     */
    public static function get_medico_especialidades_formatted( WP_Post $medico ) {
        $especialidad_ids    = get_field( 'especialidades', $medico->ID );
        $especialidades_name = array();

        if( $especialidad_ids ) {
            foreach( $especialidad_ids as $especialidad_id ) {
                $especialidad = get_term( $especialidad_id );
                $especialidades_name[] = $especialidad->name;
            }
        }

        return implode( ',', $especialidades_name );
    }

    public static function get_medico_ubicacion_name( WP_Post $medico ) {
        $ubicacion_id = get_field('ubicacion', $medico->ID);
        $ubicacion    = get_term($ubicacion_id);
        return $ubicacion->name; 
    }

    public function __construct() {
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
        add_filter( 'query_vars', array( $this, 'query_vars' ) );
    }

    public function pre_get_posts( WP_Query $query ) {
        if ( ! $query->is_main_query() || ! is_post_type_archive( 'medico' ) ) {
            return;
        }

        $ubicacion    = get_query_var( 'mg_ubicacion' );
        $especialidad = get_query_var( 'mg_especialidad' );

        $tax_query = array( 'relation' => 'AND' );

        if ( $ubicacion ) {
            $tax_query[] = array(
                'taxonomy' => 'ubicacion',
                'field'    => 'term_id',
                'terms'    => $ubicacion,
            );
        }

        if ( $especialidad ) {
            $tax_query[] = array(
                'taxonomy' => 'especialidades',
                'field'    => 'term_id',
                'terms'    => $especialidad,
            );
        }

        $query->set( 'tax_query', $tax_query );
    }

    public function query_vars( $vars ) {
        $vars[] = 'mg_ubicacion';
        $vars[] = 'mg_especialidad';

        return $vars;
    }
}

new MG_Medico_Archive();
