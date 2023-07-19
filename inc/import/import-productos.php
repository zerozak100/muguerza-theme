<?php

// Importar unicamente especialidades
class MG_Productos_Import {

    public $postId;
    public $data;
    private $unidadesMap;

    public function import( array $data ) {
        foreach ( $data as $drupalProduct ) {
            $postId = wp_insert_post( array(
                'post_title' => $drupalProduct['title'],
                'post_status' => 'publish',
                'post_type' => 'product',
                'tax_input' => array(
                    // 'product_cat' => array( '215' ), // especialidades
                    'producto_tipo' => array( '561' ), // especialidad
                ),
                'meta_input' => array(
                    'dp_product_data'   => $drupalProduct,
                    'from_drupal'       => '1', 
                    // 'can_be_sold'       => '0',
                    // 'is_especialidad'   => '1',
                    'drupal_product_id' => $drupalProduct['product_id'],
                    // 'dp_product_data' => $this->object_to_array($drupalProduct),
                ),
            ) );

            $this->postId = $postId;
            $this->data = $drupalProduct;

            // $this->uf( 'vendible', false );
            // $this->uf( 'agendable', false );

            $this->saveHero();
            $this->saveDescripcion();
            $this->saveUnidades();
            $this->saveProcedimientos();
            $this->saveInformacionGeneral();
            $this->saveInfoProductoRelacionado();
        }
    }

    public function importProductosRelacionados( array $data ) {
        foreach ( $data as $drupalProduct ) {
            $id = $drupalProduct['product_id'];
            $posts = get_posts( array(
                'post_type' => 'product',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'AND',
                    array(
                        'key' => 'drupal_product_id',
                        'value' => $id,
                    ),
                ),
            ) );

            if ( ! empty( $posts ) ) {
                $wpProduct = $posts[0];
                $this->postId = $wpProduct->ID;
                $this->data = $drupalProduct;

                $this->saveProductosRelacionados();
            }
        }
    }

    public function saveHero() {
        /**
         * Hero image
         */
        $imageUrl = $this->data['field_main_image'];
        $image_id = media_sideload_image( $imageUrl, 0, null, 'id' );
        set_post_thumbnail( $this->postId, $image_id );

        /**
         * Instrucciones
         */
        $instrucciones = $this->data['field_introducciones'];
        $instruccionesSave = array();
        foreach ( $instrucciones as $instruccion ) {
            $instruccionesSave[] = array( 'texto' => $instruccion );
        }
        // $this->uf( 'hero_instrucciones', $instruccionesSave );

        /**
         * Texto
         */
        $text = $this->data['field_texto_sobre_el_boton'];
        // $this->uf( 'hero_texto', $text );

        // $fieldKey = $this->gfk( 'hero' );
        $data = array(
            'instrucciones' => $instruccionesSave,
            'texto'         => $text,
        );

        /**
         * Hero group
         */
        $this->uf( 'field_649b4508fbabd', $data );
    }

    public function saveDescripcion() {
        if ( ! isset( $this->data['field_description'] ) || empty( $this->data['field_description'] ) ) {
            return;
        }

        $fieldDescription = $this->data['field_description'];

        /**
         * Descripción texto
         */
        $text = $fieldDescription['field_descripcion'];
        // $this->uf( 'descripcion_texto', $text );

        /**
         * Descripción imagen
         */
        $imageUrl = $fieldDescription['field_imagen'];
        $imageId = media_sideload_image( $imageUrl, 0, null, 'id' );
        // $this->uf( 'descripcion_imagen', $imageId );

        /**
         * Descripción título
         */
        $titulo = $fieldDescription['field_titulo'];
        // $this->uf( 'descripcion_titulo', $titulo );

        // $fieldKey = $this->gfk( 'descripcion' );
        $data = array(
            'texto' => $text,
            'imagen' => $imageId,
            'titulo' => $titulo,
        );

        /**
         * Descripción group
         */
        // update_field( 'field_649b72c159e15', $data, $this->postId );
        $this->uf( 'field_649b72c159e15', $data );
    }

    public function saveUnidades() {
        if ( ! isset( $this->data['field_hospitales'] ) || empty( $this->data['field_hospitales'] ) ) {
            return;
        }

        $unidadesDrupal = $this->data['field_hospitales'];

        $unidadesSave = array(); // product_cat tax terms
        foreach ( $unidadesDrupal as $nid => $unidadDrupal ) {
            $unidadIdWp = $this->getUnidadIdFromWp( $nid );
            $productCatId = get_field( 'ubicacion', $unidadIdWp );
            // echo "<pre>";
            // var_dump( $nid );
            // var_dump( $unidadIdWp );
            // var_dump( $productCatId );
            // echo "/<pre>";
            if ( $productCatId ) {
                $unidadesSave[] = $productCatId;
            }
        }

        $this->uf( 'disponibilidad_en_hospitales', $unidadesSave );
    }

    /**
     * En bbdd se llama procedimientos
     */
    public function saveProcedimientos() {
        if ( ! isset( $this->data['field_procedimientos'] ) || empty( $this->data['field_procedimientos'] ) ) {
            return;
        }

        $fieldProcedimientos = $this->data['field_procedimientos'];

        /**
         * Procedimientos texto
         */
        $texto = $fieldProcedimientos['field_full_description'];
        // $this->uf( 'procedimientos_texto', $texto );

        /**
         * Procedimientos titulo
         */
        $titulo = $fieldProcedimientos['field_titulo'];
        // $this->uf( 'procedimientos_titulo', $titulo );


        // $fieldKey = $this->gfk( 'procedimientos' );
        $data = array(
            'texto' => $texto,
            'titulo' => $titulo,
            'mostrar' => 1,
        );

        /**
         * procedimientos group
         */
        // $this->uf( 'field_649f5ce062cf3', $data );

        /**
         * padecimientos group
         */
        $this->uf( 'field_649d9cb729107', $data );
    }

    // TODO especialidad
    public function saveInformacionGeneral() {

        if ( ! isset( $this->data['field_online_result'] ) || empty( $this->data['field_online_result'] ) ) {
            return;
        }

        $fieldOnlineResults = $this->data['field_online_result'];

        $informacionGeneralSave = array();
        foreach ( $fieldOnlineResults as $nid => $result ) {
            $data = array(
                'titulo' => $result['field_titulo'],
                'descripcion' => $result['field_descripcion'],
            );
            
            if ( isset( $result['field_ubicacion'] ) && ! empty( $result['field_ubicacion'] ) ) {
                // $field_ubicacion = $result['field_ubicacion'];
                // reset( $field_ubicacion );
                // $field_ubicacion = $field_ubicacion[0];
                $ubicacion = array_shift(array_values($result['field_ubicacion']));
                $unidadIdWp = $this->getUnidadIdFromWp( $ubicacion['nid'] );
                $productCatId = get_field( 'ubicacion', $unidadIdWp );
                if ( $productCatId ) {
                    $data['ubicacion'] = $productCatId;
                }
            }

            $informacionGeneralSave[] = $data;
        }

        $informacionGeneralEspecialidad = $this->data['field_mensaje_productos_disponib'];

        // $fieldKey = $this->gfk( 'informacion_general' ); // group
        $data = array(
            'hospitales' => $informacionGeneralSave,
            'especialidad' => $informacionGeneralEspecialidad,
        );

        /**
         * informacion_general group
         */
        $this->uf( 'field_649d9e521ffe0', $data );
        // $this->uf( 'informacion_general_hospitales', $informacionGeneralSave );
    }

    public function saveInfoProductoRelacionado() {
        // if ( ! isset( $this->data['field_servicios_relacionados'] ) || empty( $this->data['field_servicios_relacionados'] ) ) {
        //     return;
        // }
        // $fieldServiciosRelacionados = $this->data['field_servicios_relacionados'];

        /**
         * Icono
         */
        $imageUrl = $this->data['field_icono'];
        $imageId = media_sideload_image( $imageUrl, 0, null, 'id' );
        // $this->uf( 'informacion_sobre_producto_relacionado_icono', $imageId );

        /**
         * Descripción
         */
        $descripcion = $this->data['field_small_description'];
        // $this->uf( 'informacion_sobre_producto_relacionado_descripcion', $descripcion );

        // $fieldKey = $this->gfk( 'informacion_sobre_producto_relacionado' ); // group
        $data = array(
            'descripcion' => $descripcion,
            'icono'       => $imageId,
        );

        /**
         * informacion_sobre_producto_relacionado group
         */
        $this->uf( 'field_649b753ed8559', $data );
    }

    // TODO
    public function saveProductosRelacionados() {
        if ( ! isset( $this->data['field_servicios_relacionados'] ) || empty( $this->data['field_servicios_relacionados'] ) ) {
            return;
        }
        $fieldServiciosRelacionados = $this->data['field_servicios_relacionados'];

        $upsellsSave = array();
        foreach ( $fieldServiciosRelacionados as $drupalProducto ) {
            $ids = get_posts( array(
                'post_type' => 'product',
                'post_status' => 'any',
                'fields' => 'ids',
                // 'post_title' => $drupalProducto['title'],
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'from_drupal',
                        'value' => '1',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'drupal_product_id',
                        'value' => $drupalProducto['product_id'],
                    ),
                ),
                'posts_per_page' => 1,
            ) );

            if ( ! empty( $ids ) ) {
                $upsellsSave[] = $ids[0];
            }
        }

        $product = new WC_Product( $this->postId );
        $product->set_upsell_ids( $upsellsSave );
    }

    ////

    private function uf( $key, $value ) {
        update_field( $key, $value, $this->postId );
    }


    private function gfk( $key ) {
        $field = acf_maybe_get_field( $key, $this->postId, false );

        return $field['key'];
    }

    private function loadDrupalWpUnidadesMap() {
        if ( ! $this->unidadesMap ) {
            $map = file_get_contents( __DIR__ . '/drupal-wp-unidades-map.json' );
            $this->unidadesMap = json_decode( $map, true );
        }
    }

    private function getUnidadIdFromWp( $drupalUnidadId ) {
        $this->loadDrupalWpUnidadesMap();

        $key = array_search( $drupalUnidadId, array_column( $this->unidadesMap, 'Drupal ID' ) );
        if ( $key !== false ) {
            $current = $this->unidadesMap[$key];
            return $current['Wordpress ID'];
        } else {
            return false;
        }
    }

    // Only for especialidades
    private function getProductIdFromWp() {

    }

    public function deleteAllData() {
        $args = array(
            'post_type'   => 'product',
            'post_status' => 'any',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'from_drupal',
                    'value' => '1',
                    'compare' => '=',
                ),
            ),
            'posts_per_page' => -1,
        );
        $posts = get_posts( $args );
        foreach ($posts as $post) {
            wp_delete_post( $post->ID );
        }
    }
}
