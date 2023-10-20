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
      $user   = MG_User::current();
      $unidad = $user->get_unidad();

      $mg_product = new MG_Product( $product_id );

      // dd( $unidad->get_id(), $mg_product->get_unidad_id() );

      if ( $unidad->get_id() !== $mg_product->get_unidad_id() ) {
        $passed = false;
        wc_add_notice( __( 'Lo sentimos, el producto no pertenece a la unidad actual seleccionada.', 'categoria' ), 'error' );
      }

      return $passed;
    }

    /**
     * Todos los productos deben tener la misma unidad
     */
    public function validate_cart_items_same_unit( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
        $product = new MG_Product( $product_id );

        if ( ! $product->is_servicio() ) {
          return $passed;
        }

        $unidad_id = $product->get_unidad_id();

        if ( ! $unidad_id ) {
          wc_add_notice( __( 'El producto no cuenta con unidad asignada.', 'categoria' ), 'error' );
          return false;
        }

        foreach ( WC()->cart->get_cart() as $item) {
          $cart_item_product    = new MG_Product( $item['data'] );
          $cart_item_unidad_id  = $cart_item_product->get_unidad_id();

          if ( $unidad_id !== $cart_item_unidad_id ) {
            $passed = false;
            wc_add_notice( __( 'Lo sentimos, solo puedes agregar productos de la misma unidad.', 'categoria' ), 'error' );
          }
        }

        return $passed;
      }
}

new MG_Cart();
