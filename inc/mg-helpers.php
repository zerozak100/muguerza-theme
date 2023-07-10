<?php

function mg_get_template( $template_name, $args = array() ) {
	wc_get_template( $template_name, $args, '', MG_TEMPLATES_PATH );
}

function mg_get_saved_location_name() {
	$user = MG_User::current();

	$location_id = $user->get_location();

	$location = MG_Location::get_location( $location_id );

	$name = '';

	if ( $location ) {
		$name = $location[ 'name' ];
	}

	return $name;
}

function mg_get_saved_address() {
	$user = MG_User::current();
	$address = $user->get_address();
	return $address;
}

function mg_get_location_coords( $location_id ) {
	$location = MG_Location::get_location( $location_id );

	if ( $location ) {
		return $location['coords'];
	}

	return false;
}

function mg_get_saved_geolocation_coords() {
	$user = MG_User::current();
	$coords = $user->get_geolocation();
	if ( ! $coords ) {
		false;
	}
	return $coords;
}

// https://stackoverflow.com/questions/9589130/find-closest-longitude-and-latitude-in-array
function distance($a, $b)
{
    list($lat1, $lon1) = $a;
    list($lat2, $lon2) = $b;

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return $miles;
}

/**
 * Return key of ordered locations
 */
function mg_get_locations_in_order( $locations, $user_coords ) {
	$distances = array_map(function($item) use($user_coords) {
        return distance(array($item['coords']['lat'], $item['coords']['lng']), array($user_coords['lat'], $user_coords['lng']));
    }, $locations);
    asort($distances);
	return array_keys($distances);
}

function mg_get_product_unidad_term( $product ) {
	$proudct_unidad = false;

	// if ( gettype( $product ) === 'integer' ) {
	// 	$product = wc_get_product( $product );
	// }

	if ( $product instanceof WC_Product ) {
		$unidades = wp_get_post_terms( $product->get_id(), 'product_cat' );
		if ( ! empty( $unidades ) ) {
			$proudct_unidad = $unidades[0];
		}
	}

	return $proudct_unidad;
}

function d( $var ) {
	echo "<pre>";
	var_dump( $var );
	echo "</pre>";
}

function dd( $var ) {
	d( $var );
}

function mg_get_unidad_from_product_cat_id( $product_cat_id ) {
	$posts = get_posts( array(
		'post_type' => 'unidad',
		'posts_per_page' => 1,
		// 'fields' => 'ids',
		'meta_query' => array(
			'AND',
			array(
				'key' => 'ubicacion',
				'value' => $product_cat_id,
			),
		),
	) );

	if ( ! empty( $posts ) ) {
		$unidad = $posts[0];

		return new MG_Unidad( $unidad->ID );
	}

	return false;
}

function mg_get_unindad_by( $type, $value ) {
	if ( 'product_cat' === $type ) {
		return mg_get_unidad_from_product_cat_id( $value );
	}

	return false;
}

