<?php

class MG_Productos_Import {

    public $postId;
    public $data;
    private $unidadesMap;

    public function import( array $data ) {
        foreach ( $data as $drupalProduct ) {
            $postId = wp_insert_post( array(
                'post_title' => $drupalProduct['title'],
                'post_type' => 'product',
                'tax_input' => array(
                    'product_cat' => array( '215' ), // especialidades
                ),
                'meta_input' => array(
                    'dp_product_data'   => $drupalProduct,
                    'from_drupal'       => '1', 
                    'can_be_sold'       => '0',
                    'is_especialidad'   => '1',
                    'drupal_product_id' => $drupalProduct['product_id'],
                    // 'dp_product_data' => $this->object_to_array($drupalProduct),
                ),
            ) );

            $this->postId = $postId;
            $this->data = $drupalProduct;

            $this->saveHero();
            $this->saveDescripcion();
            $this->saveUnidades();
            $this->saveInformacionGeneral();
            $this->saveInfoProductoRelacionado();
            // $this->saveProductosRelacionados();
            // update_field( 'ubicacion', 212, $product->ID );
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
        $this->uf( 'hero_instrucciones', $instruccionesSave );

        /**
         * Texto
         */
        $text = $this->data['field_texto_sobre_el_boton'];
        $this->uf( 'hero_texto', $text );
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
        $this->uf( 'descripcion_texto', $text );

        /**
         * Descripción imagen
         */
        $imageUrl = $fieldDescription['field_imagen'];
        $imageId = media_sideload_image( $imageUrl, 0, null, 'id' );
        $this->uf( 'descripcion_imagen', $imageId );

        /**
         * Descripción título
         */
        $titulo = $fieldDescription['field_titulo'];
        $this->uf( 'descripcion_titulo', $titulo );
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
        $this->uf( 'procedimientos_texto', $texto );

        /**
         * Procedimientos titulo
         */
        $titulo = $fieldProcedimientos['field_titulo'];
        $this->uf( 'procedimientos_titulo', $titulo );
    }

    // TODO especialidad
    public function saveInformacionGeneral() {

        if ( ! isset( $this->data['field_online_result'] ) || empty( $this->data['field_online_result'] ) ) {
            return;
        }

        $fieldOnlineResults = $this->data['field_online_result'];

        $informacionGeneralSave = array();
        foreach ( $fieldOnlineResults as $result ) {
            $data = array(
                'titulo' => $result['field_titulo'],
                'descripcion' => $result['field_descripcion'],
            );
            
            if ( isset( $drupalUnidad['nid'] ) && $drupalUnidad['nid'] ) {
                $unidadIdWp = $this->getUnidadIdFromWp( $drupalUnidad['nid'] );
                $productCatId = get_field( 'ubicacion', $unidadIdWp );
                if ( $productCatId ) {
                    $data['ubicacion'] = $productCatId;
                }
            }

            $informacionGeneralSave[] = $data;
        }

        $this->uf( 'informacion_general_hospitales', $informacionGeneralSave );
    }

    public function saveInfoProductoRelacionado() {
        // if ( ! isset( $this->data['field_servicios_relacionados'] ) || empty( $this->data['field_servicios_relacionados'] ) ) {
        //     return;
        // }
        // $fieldServiciosRelacionados = $this->data['field_servicios_relacionados'];

        /**
         * Icono
         */
        $icono = $this->data['field_icono'];
        $this->uf( 'informacion_sobre_producto_relacionado_icono', $icono );

        /**
         * Descripción
         */
        $descripcion = $this->data['field_small_description'];
        $this->uf( 'informacion_sobre_producto_relacionado_descripcion', $descripcion );
    }

    // TODO
    public function saveProductosRelacionados() {
        if ( ! isset( $this->data['field_servicios_relacionados'] ) || empty( $this->data['field_servicios_relacionados'] ) ) {
            return;
        }
        $fieldServiciosRelacionados = $this->data['field_servicios_relacionados'];

        $upsellsSave = array();
        foreach ( $fieldServiciosRelacionados as $drupalProducto ) {
            get_posts( array(
                'post_type' => 'product',
                'post_status' => 'any',
                'post_title' => $drupalProducto['title'],
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'from_drupal',
                        'value' => '1',
                        'compare' => '=', // use drupal_product_id
                    ),
                ),
                'posts_per_page' => -1,
            ) );
        }

        $product = new WC_Product( $this->postId );
        $product->$product->set_upsell_ids();
    }

    ////

    private function uf( $key, $value ) {
        update_field( $key, $value, $this->postId );
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
