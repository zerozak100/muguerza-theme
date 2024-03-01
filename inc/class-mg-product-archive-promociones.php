<?php

class MG_Product_Archive_Promociones extends MG_Product_Archive_Servicios {
    public function filter_products( $query ) {
        parent::filter_products( $query );

        $query->set( 'post__in', array_merge( array( 0 ), wc_get_product_ids_on_sale() ) );
    }
}
