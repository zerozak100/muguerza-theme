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
        'cities' => $cities,
        'locations' => $locations,
    ) );
}

function muguerza_product_ver_mas() {
    global $product;

    printf( '<a class="" href="%s">Ver más</a>', esc_url( $product->get_permalink() ) );
}