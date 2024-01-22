<?php

function mg_home_sala_prensa_shortcode() {
    $args = array(
        'posts_per_page' => 7,
        'category' => 622,
		'category__not_in' => 650,
    );

    $items = array();

    $posts = get_posts( $args );
    if ( is_array( $posts ) && ! empty( $posts ) ) {
        $primary = $posts;
        $items = array(
            'primary' => array(
                'post' => $primary[0],
                'classes' => array( 'home-sala-prensa-item--primary' ),
            ),
            'secondary' => array(
                'post' => $primary[1],
                'classes' => array( 'home-sala-prensa-item--secondary' ),
            ),
            'tertiary' => array(
                'post' => $primary[2],
                'classes' => array( 'home-sala-prensa-item--tertiary' ),
            ),
            'four' => array(
                'post' => $primary[3],
                'classes' => array( 'home-sala-prensa-item--four', 'home-sala-prensa-item--vertical' ),
            ),
            'five' => array(
                'post' => $primary[4],
                'classes' => array( 'home-sala-prensa-item--five', 'home-sala-prensa-item--vertical' ),
            ),
            'six' => array(
                'post' => $primary[5],
                'classes' => array( 'home-sala-prensa-item--six', 'home-sala-prensa-item--vertical' ),
            ),
            'seven' => array(
                'post' => $primary[6],
                'classes' => array( 'home-sala-prensa-item--seven', 'home-sala-prensa-item--vertical' ),
            ),
        );
    }
    ob_start();
    mg_get_template( 'home/sala-prensa.php', array( 'items' => $items ) );
    // include_once MG_TEMPLATES_PATH . '/home-sala-prensa.php';
    return ob_get_clean();
}
add_shortcode( 'mg-home-sala-prensa', 'mg_home_sala_prensa_shortcode' );

function hospitales(){
	ob_start();
	?>
	<?php
		/*$args = array(
			'numberposts'   => -1,
			'post_type'     => 'page',
			'meta_key'      => 'ubicaciones',
			'meta_value'    => 'opcion1'
		);*/
		$ubicaciones = $_GET["uid"];
		//var_dump( $ubicaciones );
		if($ubicaciones[0] == ' ' || $ubicaciones == ' ' || $ubicaciones == null){
			$ubicaciones = array(); 
			$mypages = get_pages(array('meta_key' => 'ubicacion', 'meta_value' => '' , 'sort_order' => 'desc' ));
			$posts = get_posts( array(
                'post_type' => 'page',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'AND',
                    array(
                        'key' => 'ubicacion',
                        'value' => '',
                        'compare' => '!='
                    ),
                ),
            ) );
            $mypages = $posts;
		}else {
			$mypages = get_pages(array('meta_key' => 'ubicacion', 'meta_value' => $ubicaciones , 'sort_order' => 'desc' ));
		}
	//print_r($ubicaciones);
		
		
	
		echo '<div class="filtro">';
			
			//$field = get_field_object('ubicacion');
			$tax = array(
                'taxonomy' => 'product_cat',
                'parent' => 0,
                'hide_empty' => 0,
            );
            $ciudades = get_categories($tax);
			//$colors = $field['choices'];
	
			//print_r( $ciudades );
	
			if( $ciudades ){
			$i = 0;
				echo '<form id="formfiltro" action="" method="GET" >';
				echo '<ul>';
				//echo '<li><input type="text" class="checkbox" placeholder="Buscar" name="uidte[]"/></li>';
				echo '<li class="catName">ubicaciones</li>';
				echo '<li><input type="radio" class="checkbox" name="uid[]" value=" "/>Todas</li>';
				
				foreach( $ciudades as $ciudad ){
					//echo '<li> <input type="checkbox" class="checkbox'.$i.'" name="ubicaciones[]" value="'. $color .'"/> ' . $color . '</li>';
					//in_array( ID, UBICACIONES[] ) ? CHECKED : '';
					// echo '<li> <input type="checkbox" class="checkbox'.$i.'" name="ubicaciones[]" value="'. $color .'" '. in_array($color, $ubicaciones) ? "checked" : "" .'/> ' . $color . '</li>';
					printf(
						'<li><input type="radio" class="checkbox" name="uid[]" value="%s" %s />%s</li>',
						$ciudad->term_id,
						in_array($ciudad->term_id, $ubicaciones) ? "checked" : "",
						$ciudad->name,
					);
					$i++;
				}
			echo '</ul>';
				echo '</form>';
			}
		echo '</div>';
		echo '<div class="container-hospitales">';
		foreach( $mypages as $page ) {
			echo '<div class="hospital">';
			$url = wp_get_attachment_url( get_post_thumbnail_id($page->ID) );
			//echo $url;
			echo '<img src="' . $url . '">';
			echo '<p>' . $page->post_title . '</p>';
			echo '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">VER MÁS</a>';
			echo '</div>';
		}	
		echo '</div>';
		// query
		//$the_query = new WP_Query( $args );
	
		

	?>
	<?php
	return ob_get_clean();
}
add_shortcode('hospitales', 'hospitales');

function productos_sh( $atts ) {
    $a = shortcode_atts( 
        array(
            'tipo_producto' => 'Servicio',
            'tipo_unidad'   => 'Asistencia Médica Inmediata 7-24',
            'include'       => '',
        ), 
        $atts 
    );

    $args = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'posts_per_page'        => 8,
    );

    global $post;
    $mg_unidad_id = get_post_meta( $post->ID, 'unidad', true );
    $unidad       = MG_Unidad::from_mg_unidad_id( $mg_unidad_id );
    $f            = $unidad->acf_fields;
    
    if ( isset( $f['productos'] ) ) {
        $p = $f['productos'];
        if ( $a['tipo_producto'] === 'Servicio' && isset( $p['servicios'] ) && is_array( $p['servicios'] ) ) {
            $args['post__in'] = $p['servicios'];
        } else if ( $a['tipo_producto'] === 'Especialidad' && isset( $p['especialidades'] ) && is_array( $p['especialidades'] ) ) {
            $args['post__in'] = $p['especialidades'];
        }
    } else if ( $a['include'] ) {
        $args['post__in'] = array_map( fn( $id ) => ( int ) $id, explode( ',', $a['include'] ) );
    } else {
        $args = array_merge( $args, array(
            'tax_query'        => array( 
                array(
                    'taxonomy' => 'producto_tipo',
                    'field'    => 'name', // can be 'term_id', 'slug' or 'name'
                    'terms'    => $a['tipo_producto'],
                ), 
                array(
                    'taxonomy' => 'mg_unidad',
                    'field'    => 'name', // can be 'term_id', 'slug' or 'name'
                    'terms'    => $a['tipo_unidad'],
                ),
            )
        ) );
    }

    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="carrusel-productos">
    <ul class="products">
    
    <?php
    if ( $query->have_posts() ):        
        while( $query->have_posts() ): 
            $query->the_post();
            //var_dump($query->post);
    ?>
            <li class="product">
                <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $query->post->id) ); ?>" />
                <?php echo $query->post->post_title; ?>
                <div class="mg-product-item-footer">
                    <?php
                        $product = wc_get_product( $query->post->ID ); 
                        
                        echo $product->get_price_html();
                    ?>
                    <a href="<?php echo esc_url( get_permalink( $query->post->id ) ); ?>">Ver más</a>
                </div>
            </li>
    <?php
        //
        endwhile;
        
    endif;
    echo '</ul>';
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('productos', 'productos_sh');
