<?php

class MG_Product_Archive_Servicios extends MG_Product_Archive {
    public function filter_products( $query ) {

        $service_type_id = $this->get_filter( 'service_type_id' );

        $tax_query  = $query->get( 'tax_query', array( 'AND' ) );
        $meta_query = $query->get( 'meta_query', array( 'AND' ) );

        if ( ! $this->is_recommending ) {
            // $tax_query[] = array(
            //     'taxonomy' => 'product_cat',
            //     'field'     => 'term_id',
            //     'terms'    => $this->unidad->get_product_cat_unit_id(),
            // );

            $tax_query[] = array(
                'taxonomy' => 'mg_unidad',
                'field'     => 'term_id',
                'terms'    => $this->unidad->mg_unidad->term_id,
            );
        }

        if ( $service_type_id ) {
            $tax_query[] = array(
                'taxonomy' => 'tipos_servicios',
                'field'     => 'term_id',
                'terms'    => $service_type_id,
            );
        }

        $tax_query[] = array(
            'taxonomy' => 'producto_tipo',
            'field'     => 'slug',
            'terms'    => 'servicio',
        );

        $query->set( 'meta_query', $meta_query );
        $query->set( 'tax_query', $tax_query );
        $query->set( 's', $this->s );
    }
}
