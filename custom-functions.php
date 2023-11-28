<?php


function muguerza_add_cf7_form_tags() {
  wpcf7_add_form_tag( 'mg_unidades', 'mg_cf7_unidades_tag' );

  wpcf7_add_form_tag( 'mg_page_unidad_id_hidden', 'mg_cf7_page_unidad_id_hidden_tag' ); // para páginas de las unidades
  wpcf7_add_form_tag( 'mg_current_unidad_id_hidden', 'mg_cf7_current_unidad_id_hidden_tag' ); // para cualquier página
  wpcf7_add_form_tag( 'mg_current_unidad_name_hidden', 'mg_cf7_current_unidad_name_hidden_tag' );
}
add_action('wpcf7_init', 'muguerza_add_cf7_form_tags');

function mg_cf7_page_unidad_id_hidden_tag() {
  global $post;

  if ( 'page' === $post->post_type ) {
    $mg_unidad_id = get_field( 'unidad', $post->ID );
    if ( $mg_unidad_id ) {
      $unidad         = MG_Unidad::from_mg_unidad_id( $mg_unidad_id );
      $unidad_id      = $unidad->get_id();
    }
  }

  if ( ! $unidad_id ) {
    throw new Exception( 'No se encontró unidad_id' );
  }

  return sprintf( '<input type="hidden" name="unidad_id" value="%s" />', $unidad_id );
}

function mg_cf7_current_unidad_id_hidden_tag() {
  $unidad_id = mg_get_current_unidad_id();

  if ( ! $unidad_id ) {
    throw new Exception( 'No se encontró unidad_id' );
  }

  return sprintf( '<input type="hidden" name="unidad_id" value="%s" />', $unidad_id );
}

function mg_cf7_current_unidad_name_hidden_tag() {
  $unidad_id = mg_get_current_unidad_id();

  if ( ! $unidad_id ) {
    throw new Exception( 'No se encontró unidad_id' );
  }

  $unidad = new MG_Unidad( $unidad_id );

  return sprintf( '<input type="hidden" name="unidad_name" value="%s" />', $unidad->get_name() );
}

function mg_cf7_unidades_tag() {
  global $post;

  $mg_product = new MG_Product( $post );

  $tag = '';

  if ( $mg_product->is_especialidad() ) {
    $unidades = get_field( 'unidad' ); // taxonomy mg_unidad

    if ( is_array( $unidades ) ) {
      
      $tag = '<select name="unidad">';

      foreach ( $unidades as $mg_unidad_id ) {
        $unidad = MG_Unidad::from_mg_unidad_id( $mg_unidad_id );

        if ( ! $unidad->has_destinatarios( 'servicios_y_cotizaciones' ) ) {
          continue;
        }

        $option = sprintf( '<option value="%s">%s</option>', $unidad->get_id(), $unidad->get_name() );

        $tag .= $option;
      }

      $tag .= '</select>';
    }
  }
  
  // else {
  //   $product_cat_id = get_field( 'ubicacion' );

  //   if( $product_cat_id ) {
      
  //     $unidad = MG_Unidad::from_product_cat( $product_cat_id );

  //     /**
  //     * Si no tiene configurado destinatarios entonces no mostrar
  //     */
  //     if ( ! $unidad->has_destinatarios('servicios_y_cotizaciones' ) ) {
  //       return '<p style="color:#ffffff;">No tiene destinatarios</p>';
  //     }

  //     $tag = sprintf( '<input style="background-color: #fff;" disabled type="text" value="%s" />', $unidad->get_name() );
  //   }
  // }

  return $tag;
}

/**
 * @param WPCF7_ContactForm $contact_form
 */
function muguerza_cf7_handle_destinatarios( $contact_form ) {
  /**
   * @var WPCF7_Submission $submission
   * 
   * Submission object, that generated when the user click the submit button.
   */
  $submission = WPCF7_Submission::get_instance();

  if ( ! $submission ) {
    return;
  }

  $posted_data = $submission->get_posted_data();

  if ( empty( $posted_data ) ) {
    return;
  }

  $unidad_id = $posted_data['unidad_id'];

  if ( ! $unidad_id ) {
    return;
  }

  $tipo_1 = 53120234051;
  $tipo_2 = array( 53120248515, 53120248514 );

  $destinatarios = array();

  $form_id    = $contact_form->id();
  $unidad = new MG_Unidad( $unidad_id );

  if ( $tipo_1 == $form_id ) {
    // TODO cambiar cuando sea un producto de maternidad
    $destinatarios = $unidad->get_destinatarios( 'servicios_y_cotizaciones' );
  }

  if ( in_array( $form_id, $tipo_2 ) ) {
    $asunto = $posted_data['asunto'][0];

    if ( "Tengo una queja, comentario o felicitación" === $asunto ){
      $destinatarios = $unidad->get_destinatarios( 'quejas_sugerencias_y_felicitaciones' );
    }else{
      $destinatarios = $unidad->get_destinatarios( 'informacion_general' );
    }
  }

  $mail = $contact_form->prop( 'mail' );
  $mail['recipient'] = implode( ',', $destinatarios );

  $contact_form->set_properties( array( 'mail' => $mail ) );
}
add_action('wpcf7_before_send_mail', 'muguerza_cf7_handle_destinatarios', 90, 1);
