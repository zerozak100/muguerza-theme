<?php

class MG_Checkout {
    public function __construct() {
        add_filter( 'woocommerce_checkout_fields', array( $this, 'add_custom_fields' ) );
        add_filter( 'woocommerce_checkout_fields', array( $this, 'order_fields' ) );
        add_filter( 'woocommerce_checkout_fields', array( $this, 'add_billing_field_classes' ) );
        add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_requires_billing_fields' ), 10, 2 );

        add_filter( 'woocommerce_checkout_fields', array( $this, 'setup_required_fields' ), 99 );
        add_filter( 'woocommerce_default_address_fields', array( $this, 'setup_required_fields_2' ), 99 );

        add_action( 'wp_footer', array( $this, 'scripts' ) );
        add_action( 'wp_head', array( $this, 'styles' ) );

        add_filter( 'woocommerce_checkout_update_order_meta', array( $this, 'save_custom_fields' ) );
        add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'display_custom_fields' ), 10, 1 );

        add_filter( 'manage_edit-shop_order_columns', array( $this, 'order_custom_columns' ), 20 );
        add_action( 'manage_shop_order_posts_custom_column' , array( $this, 'order_custom_columns_content' ), 20, 2 );
        // woocommerce_billing_fields

        add_filter( 'woocommerce_checkout_get_value', array( $this, 'set_billing_requires_default_value' ), 10, 2 );
    }

    public function set_billing_requires_default_value( $value, $input ) {
        if ( 'billing_requires' === $input ) {
            return '';
        }

        return $value;
    }

    public function order_custom_columns( $columns ) {
        $columns['billing_requires'] = 'Requiere factura';
        return $columns;
    }

    public function order_custom_columns_content( $column, $post_id ) {
        if ( 'billing_requires' === $column ) {
            $billing_requires = get_post_meta( $post_id, 'billing_requires', true );
            echo $billing_requires ? 'Sí' : 'No';
        }
    }

    public function add_custom_fields( array $fields ) {
        $fields['billing']['billing_rfc'] = array(
            'required' => false,
            'type'     => 'text',
            'label'    => 'RFC',
            'class'    => array(
                'form-row-wide',
                'wc-rfc-restriction',
                'thwcfd-field-wrapper',
                'thwcfd-field-text',
            ),
        );

        $fields['billing']['billing_requires'] = array(
            'required' => false,
            'type'     => 'checkbox',
            'label'    => '¿Deseas facturar esta compra?',
            'class'    => array( 'form-row-wide' ),
        );

        return $fields;
    }

    public function save_custom_fields( $order_id ) {
        if ( $_POST['billing_rfc'] ){ 
            update_post_meta( $order_id, 'billing_rfc', sanitize_text_field( $_POST['billing_rfc'] ) );
        }

        if ( $_POST['billing_requires'] ){ 
            update_post_meta( $order_id, 'billing_requires', sanitize_text_field( $_POST['billing_requires'] ) );
        }
    }

    /**
     * @param WC_Order $order
     */
    public function display_custom_fields( $order ) {
        $id = $order->get_id();

        $rfc = get_post_meta( $id, 'billing_rfc', true );
        $billing_requires = get_post_meta( $id, 'billing_requires', true ) ? 'Sí' : 'No';

        if ( $rfc ) {
            echo '<p><strong>'.__('RFC').':</strong> ' . get_post_meta( $id, 'billing_rfc', true ) . '</p>';
        }

        echo '<p><strong>'.__('Requiere factura').':</strong> ' . $billing_requires . '</p>';
    }

    public function setup_required_fields( array $fields ) {
        $fields['billing']['billing_first_name']['required'] = true;
        $fields['billing']['billing_last_name']['required']  = true;
        $fields['billing']['billing_email']['required']      = true;
        $fields['billing']['billing_phone']['required']      = true;

        $fields['billing']['billing_requires']['required']   = false;

        $fields['billing']['billing_rfc']['required']        = true;
        $fields['billing']['billing_company']['required']    = true;
        $fields['billing']['billing_address_1']['required']  = true;
        $fields['billing']['billing_address_2']['required']  = true;
        $fields['billing']['billing_country']['required']    = true;
        $fields['billing']['billing_state']['required']      = true;
        $fields['billing']['billing_city']['required']       = true;
        $fields['billing']['billing_postcode']['required']   = true;

        return $fields;
    }

    public function setup_required_fields_2( $fields ) {
        $fields['first_name']['required'] = true;
        $fields['last_name']['required']  = true;
        $fields['email']['required']      = true;
        $fields['phone']['required']      = true;

        $fields['rfc']['required']        = true;
        $fields['company']['required']    = true;
        $fields['address_1']['required']  = true;
        $fields['address_2']['required']  = true;
        $fields['country']['required']    = true;
        $fields['state']['required']      = true;
        $fields['city']['required']       = true;
        $fields['postcode']['required']   = true;

        return $fields;
    }

    public function order_fields( array $fields ) {
        $fields['billing']['billing_first_name']['priority'] = 1;
        $fields['billing']['billing_last_name']['priority']  = 2;
        $fields['billing']['billing_email']['priority']      = 3;
        $fields['billing']['billing_phone']['priority']      = 4;
        $fields['billing']['billing_phone']['priority']      = 4;
        $fields['billing']['billing_requires']['priority']   = 5;
        $fields['billing']['billing_rfc']['priority']        = 6;
        $fields['billing']['billing_company']['priority']    = 7;
        $fields['billing']['billing_address_1']['priority']  = 7;
        $fields['billing']['billing_address_2']['priority']  = 8;
        $fields['billing']['billing_country']['priority']    = 9;
        $fields['billing']['billing_state']['priority']      = 10;
        $fields['billing']['billing_city']['priority']       = 11;
        $fields['billing']['billing_postcode']['priority']   = 12;

        return $fields;
    }

    /**
     * @param  array    $data   An array of posted data.
     * @param  WP_Error $errors Validation errors.
     */
    public function validate_requires_billing_fields( $data, $errors ) {
        $billing_requires = $data['billing_requires'];

        $fields = array(
            'billing_rfc',
            'billing_company',
            'billing_address_1',
            'billing_address_2',
            'billing_country',
            'billing_state',
            'billing_city',
            'billing_postcode',
        );

        if ( ! $billing_requires ) {
            foreach ( $fields as $field ) {
                if ( $errors->get_error_data( "{$field}_required" ) ) {
                    $errors->remove( "{$field}_required" );
                }
            }
        }
    }

    public function add_billing_field_classes( array $fields ) {
        $fields['billing']['billing_rfc']['class'][]        = 'hidden';
        $fields['billing']['billing_company']['class'][]    = 'hidden';
        $fields['billing']['billing_address_1']['class'][]  = 'hidden';
        $fields['billing']['billing_address_2']['class'][]  = 'hidden';
        $fields['billing']['billing_country']['class'][]    = 'hidden';
        $fields['billing']['billing_state']['class'][]      = 'hidden';
        $fields['billing']['billing_city']['class'][]       = 'hidden';
        $fields['billing']['billing_postcode']['class'][]   = 'hidden';

        $fields['billing']['billing_rfc']['class'][]        = 'mg_billing';
        $fields['billing']['billing_company']['class'][]    = 'mg_billing';
        $fields['billing']['billing_address_1']['class'][]  = 'mg_billing';
        $fields['billing']['billing_address_2']['class'][]  = 'mg_billing';
        $fields['billing']['billing_country']['class'][]    = 'mg_billing';
        $fields['billing']['billing_state']['class'][]      = 'mg_billing';
        $fields['billing']['billing_city']['class'][]       = 'mg_billing';
        $fields['billing']['billing_postcode']['class'][]   = 'mg_billing';

        return $fields;
    }

    public function hide_billing_fields_2( array $fields ) {
        $fields['rfc']['class'][]        = 'hidden';
        $fields['company']['class'][]    = 'hidden';
        $fields['address_1']['class'][]  = 'hidden';
        $fields['address_2']['class'][]  = 'hidden';
        $fields['country']['class'][]    = 'hidden';
        $fields['state']['class'][]      = 'hidden';
        $fields['city']['class'][]       = 'hidden';
        $fields['postcode']['class'][]   = 'hidden';

        return $fields;
    }

    public function styles() {
        if ( ! is_checkout() ) {
            return;
        }

        ?>
        <style>
            .hidden {
                display: none!important;
            }
            .display {
                display: block!important;
            }

            #billing_country_field {
                display: none!important;
            }

            /* .woocommerce form .form-row .optional{
                display: none ;
            } */
        </style>
        <?php
    }

    public function scripts() {
        if ( ! is_checkout() ) {
            return;
        }

        ?>
        <script>
            jQuery( document ).ready( function( $ ) {
                var $paymentCheckboxes = $( ".woocommerce-checkout-payment" ).find( '[name="payment_method"]');
                $paymentCheckboxes.attr('checked', false);

                $( '#billing_requires' ).change( function() {
                    var checked = $( this ).is( ':checked' );
                    if ( checked ) {
                        $( '.mg_billing' ).removeClass( 'hidden' );
                        $( '.mg_billing' ).addClass( 'display' );
                    } else {
                        $( '.mg_billing' ).removeClass( 'display' );
                        $( '.mg_billing' ).addClass( 'hidden' );
                    }
                } );
            });
        </script>
        <?php
    }
}

new MG_Checkout;
