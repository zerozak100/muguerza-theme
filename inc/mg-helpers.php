<?php

function mg_get_template( $template_name, $args = array() ) {
	wc_get_template( $template_name, $args, '', MG_TEMPLATES_PATH );
}

/**
 * Distancia entre dos coordenadas
 * 
 * https://stackoverflow.com/questions/9589130/find-closest-longitude-and-latitude-in-array
 */
function mg_distance( MG_Coords $a, MG_Coords $b) {
    $theta = $a->get_longitude() - $b->get_longitude();

    $dist = sin( $a->get_latitude_rad() ) * sin( $b->get_latitude_rad() ) + cos( $a->get_latitude_rad() ) * cos( $b->get_latitude_rad() ) * cos( deg2rad( $theta ) );
    $dist = acos( $dist );
    $dist = rad2deg( $dist );

    $miles = $dist * 60 * 1.1515;

    return $miles;
}

/**
 * @param MG_Unidad[] $unidades
 * @param MG_Coords $user_coords
 * 
 * @return MG_Unidad[]
 */
function mg_order_unidades_by_distance( array $unidades, MG_Coords $user_coords ) {
	$order_unidades = new MG_Order_Unidades( $unidades, $user_coords );
	$order_unidades->order();

	return $order_unidades->get_result();
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

function mg_get_unidad_from_product_cat_id( $product_cat_id ) {
	$posts = get_posts( array(
		// 'fields' => 'ids',
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

	if ( ! empty( $posts ) ) {
		$unidad = $posts[0];

		return new MG_Unidad( $unidad->ID );
	}

	return false;
}

function mg_product_cat_id_get_unidad_id() {

}

function mg_get_product_cat_unidad_id( $product_cat_id ) {
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

	return $unidad_id;
}

function mg_get_unindad_by( $type, $value ) {
	if ( 'product_cat' === $type ) {
		return mg_get_unidad_from_product_cat_id( $value );
	}

	return false;
}

function mg_get_current_unidad_id() {
	$user 	= MG_User::current();
    $unidad = $user->get_unidad();
	return $unidad->get_id();
}

function uniqidReal($lenght = 13) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

function snakeToCamel( $input ) {
	return lcfirst( str_replace( ' ', '', ucwords( str_replace( '_', ' ', $input ) ) ) );
}

function mg_log( $log ) {
	$log = json_encode( $log );
	error_log( 'MG_LOG: ' . $log );
}

/**
 * @param int $product_id
 */
function mg_get_product_default_image_id( $product_id ) {
		$terms 		   = get_the_terms( $product_id, 'tipos_servicios' );
		$parent 	   = null;
		$child 		   = null;
		$attachment_id = null;

		if ( is_array( $terms ) && count( $terms ) > 0 ) {

			foreach ( $terms as $term ) {
				if ( $term->parent ) {
					$child = $term;
				} else {
					$parent = $term;
				}
			}
		}

		if ( $child ) {
			$attachment_id = get_field( 'product_default_image', "tipos_servicios_{$child->term_id}" );

			if ( ! $attachment_id && $parent ) {
				$attachment_id = get_field( 'product_default_image', "tipos_servicios_{$parent->term_id}" );
			}
		} else if ( $parent ) {
			$attachment_id = get_field( 'product_default_image', "tipos_servicios_{$parent->term_id}" );
		}

		return $attachment_id;
}
