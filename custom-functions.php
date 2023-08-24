<?php


function muguerza_add_cf7_form_tags() {
  wpcf7_add_form_tag( 'mg_unidades', 'mg_cf7_unidades_tag' );

  wpcf7_add_form_tag( 'mg_page_unidad_id_hidden', 'mg_cf7_page_unidad_id_hidden_tag' );
  wpcf7_add_form_tag( 'mg_current_unidad_id_hidden', 'mg_cf7_current_unidad_id_hidden_tag' );
  wpcf7_add_form_tag( 'mg_current_unidad_name_hidden', 'mg_cf7_current_unidad_name_hidden_tag' );
}
add_action('wpcf7_init', 'muguerza_add_cf7_form_tags');

function mg_cf7_page_unidad_id_hidden_tag() {
  global $post;

  if ( 'page' === $post->post_type ) {
    $product_cat_id = get_field( 'ubicacion', $post->ID );
    $unidad         = MG_Unidad::from_product_cat( $product_cat_id );
    $unidad_id      = $unidad->get_id();
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
    $disponibilidad_en_hospitales = get_field( 'disponibilidad_en_hospitales' );

    if ( is_array( $disponibilidad_en_hospitales ) ) {
      
      $tag = '<select name="unidad">';

      foreach ( $disponibilidad_en_hospitales as $product_cat_id ) {
        $unidad = MG_Unidad::from_product_cat( $product_cat_id );

        if ( ! $unidad->has_destinatarios( 'servicios_y_cotizaciones' ) ) {
          continue;
        }

        $option = sprintf( '<option value="%s">%s</option>', $unidad->get_id(), $unidad->get_name() );

        $tag .= $option;
      }

      $tag .= '</select>';
    }
  } else {
    $product_cat_id = get_field( 'ubicacion' );

    if( $product_cat_id ) {
      
      $unidad = MG_Unidad::from_product_cat( $product_cat_id );

      /**
      * Si no tiene configurado destinatarios entonces no mostrar
      */
      if ( ! $unidad->has_destinatarios('servicios_y_cotizaciones' ) ) {
        return '<p style="color:#ffffff;">No tiene destinatarios</p>';
      }

      $tag = sprintf( '<input style="background-color: #fff;" disabled type="text" value="%s" />', $unidad->get_name() );
    }
  }

  return $tag;
}

/**
 * @param WPCF7_ContactForm $contact_form
 */
function muguerza_cf7_handle_before_send_mail( $contact_form ) {
  /**
   * @var WPCF7_Submission $submission
   * 
   * Submission object, that generated when the user click the submit button.
   */
  $submission = WPCF7_Submission::get_instance();

  $formulario_especialidades_id = 53120234051; // TIPO 1

  if ( $submission && $formulario_especialidades_id == $contact_form->id() ) {
    $posted_data = $submission->get_posted_data();

    if ( empty( $posted_data ) ) {
      return;
    }

    $unidad_id = $posted_data['unidad_id'];
    $unidad    = new MG_Unidad( $unidad_id );

    $destinatarios = $unidad->get_destinatarios( 'servicios_y_cotizaciones' );

    $mail = $contact_form->prop( 'mail' );
    $mail['recipient'] = implode( ',', $destinatarios );

    $contact_form->set_properties( array( 'mail' => $mail ) );
  }

  // TIPO 2

  if ( true ) {

  } 
}
add_action('wpcf7_before_send_mail', 'muguerza_cf7_handle_before_send_mail', 90, 1);
