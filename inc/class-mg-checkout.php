<?php

class MG_Checkout {

    const FIELDS = array(
        'rfc' => array(
            'required'  => true,
            'type'      => 'text',
            'label'     => 'RFC',
            'maxlength' => 13,
            'class'    => array(
                'form-row-wide',
                'wc-rfc-restriction',
                'thwcfd-field-wrapper',
                'thwcfd-field-text',
            ),
        ),
        'billing_requires' => array(
            'required' => false,
            'type'     => 'checkbox',
            'label'    => '¿Deseas facturar esta compra?',
            'class'    => array( 'form-row-wide' ),
        ),
        'billing_heading' => array(
            'type' => 'heading',
        ),
    );


    public function __construct() {
        add_filter( 'woocommerce_billing_fields', array( $this, 'set_billing_fields' ) );

        /**
         * Checkout only
         */
        add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_set_custom_fields' ) );
        add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_order_fields' ) );
        add_filter( 'woocommerce_checkout_fields', array( $this, 'checkout_add_billing_field_classes' ), 99 );

        // add_filter( 'woocommerce_default_address_fields' );

        /**
         * Scripts
         */
        add_action( 'wp_footer', array( $this, 'scripts' ) );
        add_action( 'wp_head', array( $this, 'styles' ) );

        /**
         * Save and show custom fields
         */
        add_filter( 'woocommerce_checkout_update_order_meta', array( $this, 'save_custom_fields' ) );
        add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'admin_display_custom_fields' ), 10, 1 );
        add_filter( 'manage_edit-shop_order_columns', array( $this, 'admin_order_custom_columns' ), 20 );
        add_action( 'manage_shop_order_posts_custom_column' , array( $this, 'admin_order_custom_columns_content' ), 20, 2 );

        /**
         * Extra
         */
        add_filter( 'woocommerce_form_field_heading', array( $this, 'handle_form_field_heading' ), 10, 4 );
        add_filter( 'woocommerce_form_field', array( $this, 'remove_blank_space' ), 10, 4 );
        add_filter( 'woocommerce_checkout_get_value', array( $this, 'set_billing_requires_default_value' ), 10, 2 );
        add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_requires_billing_fields' ), 10, 2 );

    }

    /**
     * Works in both checkout and edit-adress page
     * 
     * Works only for default fields (phone, email, etc) and new fields only if there are on edit-address page
     */
    public function set_billing_fields( $fields ) {
        $fields['billing_rfc'] = self::FIELDS['rfc'];

        $fields['billing_first_name']['label'] = __('Nombre(s)', 'woocommerce');

        /**
         * Set required fields
         */
        $fields['billing_first_name']['required'] = true;
        $fields['billing_last_name']['required']  = true;
        $fields['billing_email']['required']      = true;
        $fields['billing_phone']['required']      = true;
        // $fields['billing_requires']['required']   = false;
        $fields['billing_rfc']['required']        = true;
        $fields['billing_company']['required']    = true;
        $fields['billing_address_1']['required']  = true;
        $fields['billing_address_2']['required']  = false;
        $fields['billing_country']['required']    = true;
        $fields['billing_state']['required']      = true;
        $fields['billing_city']['required']       = true;
        $fields['billing_postcode']['required']   = true;

        /**
         * Set maxlength fields
         */
        $fields['billing_first_name']['maxlength'] = 50;
        $fields['billing_last_name']['maxlength']  = 50;
        $fields['billing_email']['maxlength']      = 50;
        $fields['billing_phone']['maxlength']      = 10;
        // $fields['billing_requires']['maxlength']   = false;
        $fields['billing_rfc']['maxlength']        = 13;
        $fields['billing_company']['maxlength']    = 255;
        $fields['billing_address_1']['maxlength']  = 255;
        $fields['billing_address_2']['maxlength']  = 255;
        $fields['billing_country']['maxlength']    = 50;
        $fields['billing_state']['maxlength']      = 50;
        $fields['billing_city']['maxlength']       = 50;
        $fields['billing_postcode']['maxlength']   = 5;

        return $fields;
    }

    // =================
    // CHECKOUT
    // =================

    /**
     * Only in checkout page
     */
    public function checkout_set_custom_fields( $fields ) {
        $fields['billing']['billing_requires'] = self::FIELDS['billing_requires'];
        $fields['billing']['billing_heading'] = self::FIELDS['billing_heading'];
        return $fields;
    }

    public function checkout_order_fields( array $fields ) {
        $fields['billing']['billing_first_name']['priority'] = 1;
        $fields['billing']['billing_last_name']['priority']  = 2;
        $fields['billing']['billing_email']['priority']      = 3;
        $fields['billing']['billing_phone']['priority']      = 4;
        $fields['billing']['billing_requires']['priority']   = 5;
        $fields['billing']['billing_heading']['priority']    = 6;
        $fields['billing']['billing_rfc']['priority']        = 7;
        $fields['billing']['billing_company']['priority']    = 8;
        $fields['billing']['billing_address_1']['priority']  = 9;
        $fields['billing']['billing_address_2']['priority']  = 10;
        $fields['billing']['billing_country']['priority']    = 11;
        $fields['billing']['billing_state']['priority']      = 12;
        $fields['billing']['billing_city']['priority']       = 13;
        $fields['billing']['billing_postcode']['priority']   = 14;

        return $fields;
    }

    public function checkout_add_billing_field_classes( array $fields ) {
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

    // =================
    // SCRIPTS
    // =================

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

    // =================
    // ADMIN
    // =================

    public function admin_order_custom_columns( $columns ) {
        $columns['billing_requires'] = 'Requiere factura';
        return $columns;
    }

    public function admin_order_custom_columns_content( $column, $post_id ) {
        if ( 'billing_requires' === $column ) {
            $billing_requires = get_post_meta( $post_id, 'billing_requires', true );
            echo $billing_requires ? 'Sí' : 'No';
        }
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
    public function admin_display_custom_fields( $order ) {
        $id = $order->get_id();

        $rfc = get_post_meta( $id, 'billing_rfc', true );
        $billing_requires = get_post_meta( $id, 'billing_requires', true ) ? 'Sí' : 'No';

        if ( $rfc ) {
            echo '<p><strong>'.__('RFC').':</strong> ' . get_post_meta( $id, 'billing_rfc', true ) . '</p>';
        }

        echo '<p><strong>'.__('Requiere factura').':</strong> ' . $billing_requires . '</p>';
    }

    // =================
    // EXTRA
    // =================

    public function remove_blank_space( $field, $key, $args, $value ) {
        return str_replace( '&nbsp;', '', $field );
    }

    public function handle_form_field_heading( $field, $key, $args, $value ) {
        return '<p class="form-row form-row-first hidden mg_billing"><b>Detalles de facturación</b></p>';
    }

    public function set_billing_requires_default_value( $value, $input ) {
        if ( 'billing_requires' === $input ) {
            return '';
        }

        return $value;
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
}

new MG_Checkout;
