<?php

class MG_Product_Archive_Especialidades extends MG_Product_Archive {

    public function filter_products( $query ) {
        $tax_query  = $query->get( 'tax_query', array( 'AND' ) );
        $meta_query = $query->get( 'meta_query', array( 'AND' ) );

        if ( ! $this->is_recommending ) {
            // https://barn2.com/blog/querying-posts-by-custom-field-acf/
            $field_value = sprintf( '^%1$s$|s:%2$u:"%1$s";', $this->unidad_id, strlen( $this->unidad_id ) );
            $meta_query[] = array(
                'key'     => 'disponibilidad_en_hospitales',
                // 'value'   => 'a:2:{i:0;i:48;i:1;i:47;}',
                'value'   => $field_value,
                'compare' => 'REGEXP',
            );
        }

        $tax_query[] = array(
            'taxonomy' => 'producto_tipo',
            'field'    => 'slug',
            'terms'    => 'especialidad',
        );

        $query->set( 'meta_query', $meta_query );
        $query->set( 'tax_query', $tax_query );
        $query->set( 's', $this->s );
    }
}
