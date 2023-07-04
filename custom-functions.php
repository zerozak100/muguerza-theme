<?php
// Add custom shortcodes to Contact Form 7 WP plugin
add_action( 'wpcf7_init', 'mp_cf7_custom_shortcode' );

function mp_cf7_custom_shortcode(){
  wpcf7_add_form_tag( 'show_ubicaciones', 'cf7_get_member_level' );
}

function cf7_get_member_level(){
    $disponibilidad_en_hospitales = get_field('disponibilidad_en_hospitales');
	//echo var_dump($disponibilidad_en_hospitales);
  $select = '<select name="ubicaciones">';
  if ($disponibilidad_en_hospitales != null){  
    

    foreach( $disponibilidad_en_hospitales as $disponibilidad_en_hospital ) {
      $term = get_term_by( 'id', $disponibilidad_en_hospital, 'product_cat' );
      $select .= '<option value="' . $term->name . '">' . $term->name . '</option>';
    }
    
  }
  $select .= '</select>';
  return $select;
}