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
        if ( ! isset( $_GET['import_type'] ) ) {
            return;
        }

        if ( $_GET['import_type'] === 'news' ) {
            $importer = new \MG_Noticias_Import();
        } else {
            $importer = new \MG_Productos_Import();
        }
        
        if ( isset( $_POST['do_import'] ) && $_POST['do_import'] === '1' ) {
            $importer->import( $this->getData() );
        }

        if ( isset( $_GET['delete_imported_data'] ) && $_GET['delete_imported_data'] === '1' ) {
            $importer->deleteAllData();
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
            <div>
                <label for="">ID de categoria a asignar</label>
                <input type="text" name="cat_id" placeholder="215">
            </div>
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
}

$importer = new Drupal_Importer();
// $importer->import();
