<?php
// Add custom shortcodes to Contact Form 7 WP plugin
add_action('wpcf7_init', 'mp_cf7_custom_shortcode');

function mp_cf7_custom_shortcode()
{
  wpcf7_add_form_tag('show_ubicaciones', 'cf7_get_member_level');
}

function cf7_get_member_level()
{
  global $post;
  $mg_product = new MG_Product( $post );
  if ( $mg_product->is_especialidad() ) {
    $disponibilidad_en_hospitales = get_field('disponibilidad_en_hospitales');
    $select = '<select name="unidad">';
    if ($disponibilidad_en_hospitales != null) {
      foreach ($disponibilidad_en_hospitales as $product_cat_id) {
        $unidad = mg_get_unindad_by('product_cat', $product_cat_id);
        /**
         * Si no tiene configurado destinatarios entonces no mostrar
         */
        if (!$unidad->has_destinatarios('servicios_y_cotizaciones')) {
          continue;
        }
        $term = get_term_by('id', $product_cat_id, 'product_cat');
        $select .= '<option value="' . $unidad->post->ID . '">' . $term->name . '</option>';
      }
    }
    $select .= '</select>';
    return $select;
  } else {
    $ubicacion = get_field('ubicacion');
    
    $input_text = '';
    if($ubicacion != null) {
      
      $unidad_servicio = mg_get_unindad_by('product_cat', $ubicacion);
      /**
        * Si no tiene configurado destinatarios entonces no mostrar
      */
      if (!$unidad_servicio->has_destinatarios('servicios_y_cotizaciones')) {
        echo '<p style="color:#ffffff;">no tiene destinatario</p>';
        return false;
      }
       
      $term = get_term_by('id', $ubicacion, 'product_cat');
      $input_text .= '<input style="background-color: #fff;" disabled type="text" value="'. $term->name .'"/>';
    }
    
    return $input_text;
  }
  
}

/**
 * @param WPCF7_ContactForm $contact_form
 */
function wpcf7_do_something_else_with_the_data($contact_form)
{
  /**
   * @var WPCF7_Submission $submission
   * 
   * Submission object, that generated when the user click the submit button.
   */
  $submission = WPCF7_Submission::get_instance();

  $formulario_especialidades_id = 53120234051; // TIPO 1

  if ($submission && $formulario_especialidades_id == $contact_form->id()) {
    $posted_data = $submission->get_posted_data();

    if (empty($posted_data)) {
      return;
    }

    $unidad_id = $posted_data['unidad'];
    $unidad = new MG_Unidad($unidad_id);

    $destinatarios = $unidad->get_destinatarios('servicios_y_cotizaciones');

    $mail = $contact_form->prop('mail');
    $mail['recipient'] = implode(',', $destinatarios);

    $contact_form->set_properties(array('mail' => $mail));

    // var_dump( $contact_form->get_properties() );
    // var_dump($contact_form->prop('mail'));
    // die();
  }

  // TIPO 2
  
}
add_action('wpcf7_before_send_mail', 'wpcf7_do_something_else_with_the_data', 90, 1);
