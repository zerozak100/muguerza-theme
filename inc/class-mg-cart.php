<?php

class MG_Cart {
    public static function init() {
        new self();
    }

    public function __construct() {
        add_action( 'woocommerce_add_to_cart_validation', array( $this, 'validate_cart_items_same_unit' ), 10, 5 );
        add_action( 'woocommerce_add_to_cart_validation', array( $this, 'validate_product_in_unit' ), 10, 5 );
    }

    /**
     * Validar que el producto a agregar coincida con la unidad seleccionada
     */
    public function validate_product_in_unit( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
      if ( ! mg_product_in_unidad( $product_id ) ) {
        $passed = false;
        wc_add_notice( __( 'Lo sentimos, el producto no pertenece a la unidad actual seleccionada.', 'categoria' ), 'error' );
      }

      return $passed;
    }

    /**
     * Todos los productos deben tener la misma unidad
     */
    public function validate_cart_items_same_unit( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
        $product_to_add = new MG_Product( $product_id );

        if ( ! $product_to_add->is_servicio() ) {
          return $passed;
        }

        $product_to_add_unidad = $product_to_add->get_unidad();

        if ( ! $product_to_add_unidad->get_id() ) {
          wc_add_notice( __( 'El producto no cuenta con unidad asignada.', 'categoria' ), 'error' );
          return false;
        }

        foreach ( WC()->cart->get_cart() as $item) {
          $product_in_cart         = new MG_Product( $item['data'] );
          $product_in_cart_unidad  = $product_in_cart->get_unidad();

          if ( $product_to_add_unidad->get_id() !== $product_in_cart_unidad->get_id() ) {
            $passed = false;
            wc_add_notice( __( 'Lo sentimos, solo puedes agregar productos de la misma unidad.', 'categoria' ), 'error' );
          }

          break;
        }

        return $passed;
      }
}

new MG_Cart();
