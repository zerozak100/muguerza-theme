<?php

function mg_script_update_servicios() {
    if ( isset( $_GET['mg_script_update_servicios'] ) && '1' === $_GET['mg_script_update_servicios'] ) {
        $args = array(
            'fields' => 'ids',
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'meta_query' => array(
                'relation' => 'AND',
                // array(
                //     'key'     => 'producto_tipo',
                //     'value'   => '561',
                //     'compare' => '!=',
                // ),
                array(
                    'key'     => 'producto_tipo',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );

        $servicios = get_posts( $args );

        foreach ( $servicios as $servicio_id ) {
            $servicio_term_id = '562';
            update_field( 'producto_tipo', $servicio_term_id, $servicio_id );
        }
    }
}
add_action( 'template_redirect', 'mg_script_update_servicios' );
