<?php

class MG_Unidad {

    public $post;
    public $destinatarios_by_form = array();
    public $acf_fields;
    public $form_types = array(
        'servicios_y_cotizaciones',
    );

    public function __construct( $post_id ) {
        $this->post = get_post( $post_id );
        $this->load_acf_fields();
        $this->set_destinatarios();
    }

    public function load_acf_fields() {
        $this->acf_fields = get_fields( $this->post->ID );
    }

    public function set_destinatarios() {
        if ( isset( $this->acf_fields['destinatarios_formularios'] ) ) {
            foreach ( $this->acf_fields['destinatarios_formularios'] as $form_type => $destinatarios ) {
                $this->destinatarios_by_form[$form_type] = array_column( $destinatarios, 'email' );
            }
        }
    }

    public function has_destinatarios( $form_type ) {
        if (
            isset( $this->destinatarios_by_form[$form_type] ) 
            && is_array( $this->destinatarios_by_form[$form_type] )
            && ! empty( $this->destinatarios_by_form[$form_type] )
        ) {
            return true;
        }

        return false;
    }

    public function get_destinatarios( $form_type ) {
        if ( isset( $this->destinatarios_by_form[$form_type] ) ) {
            return $this->destinatarios_by_form[$form_type];
        }

        return array();
    }
}