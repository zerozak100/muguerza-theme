<?php

// function dd( $val ) {
//     echo "<pre>";
//     var_dump( $val );
//     echo "</pre>";
//     die();
// }

class Drupal_Importer {

    public function __construct() {
        add_action( 'template_redirect', array( $this, 'init' ) );
        add_action( 'wp_footer', array( $this, 'import_form' ) );
    }

    public function init() {
        if ( isset( $_POST['do_import'] ) && $_POST['do_import'] === '1' ) {
            // $data = $this->getData();
            $this->import( $this->getData() );
        }

        if ( isset( $_GET['delete_imported_data'] ) && $_GET['delete_imported_data'] === '1' ) {
            $this->deleteAllData();
        }
    }

    public function import( $data ) {
        // $data = $this->getData();
        // $data = array_slice( $data, 0, 1 );
        // dd(count($data));
        // dd($data[0]);
        foreach ( $data as $drupalProduct ) {
            $product = wp_insert_post( array(
                'post_title' => $drupalProduct['title'],
                'post_type' => 'product',
                'tax_input' => array(
                    'product_cat' => array( '212' ), // especialidades
                ),
                'meta_input' => array(
                    'drupal_product_data' => $drupalProduct,
                    'from_drupal'         => '1', 
                    // 'drupal_product_data' => $this->object_to_array($drupalProduct),
                ),
            ) );



            // update_field( 'ubicacion', 212, $product->ID );
        }
    }

    public function import_form() {
        if ( ! is_page( 'import-form' ) ) {
            return;
        }

        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="data">
            <input type="hidden" name="do_import" value="1">
            <button type="submit">Importar</button>
        </form>
        <form action="" method="GET">
            <input type="hidden" name="delete_imported_data" value="1">
            <button type="submit">Borrar datos importados</button>
        </form>
        <?php
    }

    public function getData() {
        $data = file_get_contents( $_FILES['data']['tmp_name'] );
        return json_decode( $data, true );
        // return json_decode( file_get_contents( __DIR__ . '/data.json' ) );
    }

    public function deleteAllData() {
        $args = array(
            'post_type' => 'product',
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

    public function object_to_array($obj) {
        //only process if it's an object or array being passed to the function
        if(is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach($ret as &$item) {
                //recursively process EACH element regardless of type
                $item = $this->object_to_array($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        else {
            return $obj;
        }
    }
}

$importer = new Drupal_Importer();
// $importer->import();
