<?php

// class MG_Product extends WC_Product_Simple {
//     public function __construct( $product = 0 ) {
//         parent::__construct( $product );
//     }

//     public function is_especialidad() {
//         $is_especialidad = false;

//         $terms = get_the_terms( $this->get_id(), 'producto_tipo' );

//         if ( ! empty( $terms ) ) {
//             $is_especialidad = 'especialidad' === $terms[0]->slug;
//         }

//         return $is_especialidad;
//     }

//     public function is_vendible() {
//         return get_field( 'vendible', $this->get_id() );
//     }

//     public function is_agendable() {
//         return get_field( 'agendable', $this->get_id() );
//     }
// }
