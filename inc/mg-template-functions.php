<?php

function muguerza_product_filter_bar() {

    $city_id         = ! empty( $_GET[ 'city_id' ] ) ? $_GET[ 'city_id' ] : '';
    $location_id     = ! empty( $_GET[ 'location_id' ] ) ? $_GET[ 'location_id' ] : '';
    $service_type_id = ! empty( $_GET[ 'service_type_id' ] ) ? $_GET[ 'service_type_id' ] : '';

    $service_types = get_terms( array(
        'taxonomy' => 'tipos_servicios',
        // 'hide_empty' => false,
    ) );

    $cities = get_terms( array(
        'taxonomy' => 'product_cat',
        // 'hide_empty' => false,
        'parent' => 0,
    ) );
    
    $locations = get_terms( array(
        'taxonomy' => 'product_cat',
        // 'hide_empty' => false,
        'parent' => $city_id,
    ) );

    mg_get_template( 'product-filter-bar.php', array(
        'service_types' => $service_types,
        // 'cities' => $cities,
        // 'locations' => $locations,
    ) );
}

function muguerza_product_footer_open() {
    echo '<div class="mg-product-item-footer">';
}

function muguerza_product_footer_close() {
    echo '</div>';
}

function muguerza_product_ver_mas() {
    global $product;

    printf( '<a class="mg-product-item-ver-mas" href="%s">Ver más</a>', esc_url( $product->get_permalink() ) );
}

function muguerza_product_precio() {
    global $product;

    $mg_product = new MG_Product( $product );

    if ( $mg_product->is_vendible() ) {
        printf( '<span class="mg-product-item-price">%s</span>', wc_price( $product->get_price() ) );
    }
}


/**
 * TODO usar abreviaturas de los hospitales
 */
function muguerza_product_unidad() {
    global $product;

    $val = MG_Product_Archive::$no_products_in_unit_found;

    if ( $val ) {
        $mg_product = new MG_Product( $product );

        if ( $mg_product->is_especialidad() ) {
            $unidades = get_field( 'disponibilidad_en_hospitales' );
            if ( ! empty( $unidades ) ) {
                $unidad = get_term_by( 'term_id', $unidades[0], 'product_cat' );
                printf( '<div class="mg-product-item-unidad">%s ...</div>', esc_html( $unidad->name ) );
            }
        } else {
            $unidad = mg_get_product_unidad_term( $product );
            printf( '<div class="mg-product-item-unidad">%s</div>', esc_html( $unidad->name ) );
        }
    }
}
