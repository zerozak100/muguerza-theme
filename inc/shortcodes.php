<?php

function mg_home_sala_prensa_shortcode() {
    $args = array(
        'posts_per_page' => 7,
    );

    $items = array();

    $posts = get_posts( $args );
    if ( is_array( $posts ) && ! empty( $posts ) ) {
        $primary = $posts[0];
        $items = array(
            'primary' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--primary' ),
            ),
            'secondary' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--secondary' ),
            ),
            'tertiary' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--tertiary' ),
            ),
            'four' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--four', 'home-sala-prensa-item--vertical' ),
            ),
            'five' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--five', 'home-sala-prensa-item--vertical' ),
            ),
            'six' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--six', 'home-sala-prensa-item--vertical' ),
            ),
            'seven' => array(
                'post' => $primary,
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
			$ciudades = get_terms(array('taxonomy' => 'product_cat', 'parent' => 0));
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
