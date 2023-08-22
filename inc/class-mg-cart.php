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
     * Todos los productos deben tener la misma sucursal
     */
    public function validate_cart_items_same_unit( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
        // Get the list of cities for the item to be added
        $product_cities = get_the_terms( $product_id, 'product_cat' );
        $categorias_array = array_map( function( $categoria ) { return $categoria->slug; }, $product_cities );
        // Get the cart items
        $item_in_cart = WC()->cart->get_cart();
        // Check for each item if the city is different
        foreach ($item_in_cart as $item){
          $categorias = get_the_terms( $item['product_id'], 'product_cat' );
          foreach ($categorias as $categoria){
            if(!in_array($categoria->slug, $categorias_array)){
              $passed = false;
              wc_add_notice( __( 'Lo sentimos, solo puedes agregar productos de la misma unidad.', 'categoria' ), 'error' );
            }
          }
        }
        return $passed;
      }
}

new MG_Cart();
