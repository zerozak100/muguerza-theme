<?php

class MG_Medicos_Import {

    use MG_Import_Helpers;

    public $postId;
    public $data;
    private $unidadesMap;

    public function import( array $data ) {

        // $data = $this->getChunk( $data, $_POST['chunk'] ?: 1 );

        foreach ( $data as $dMedico ) {
            $postId = wp_insert_post( array(
                'post_title' => $dMedico['title'],
                'post_status' => 'publish',
                'post_type' => 'medico',
                // 'tax_input' => array(
                //     'product_cat' => array( '215' ), // especialidades
                // ),
                'meta_input' => array(
                    'dp_medico_data'    => $dMedico,
                    'from_drupal'       => '1', 
                    'can_be_sold'       => '0',
                    'is_especialidad'   => '1',
                    'drupal_medico_id'  => $dMedico['nid'],
                ),
            ) );
            $this->postId = $postId;
            $this->data = $dMedico;

            $this->uf( 'cedula', $dMedico['field_titulo_cotiza'] );
            $this->uf( 'telefono', $dMedico['field_telefono'] );
            $this->uf( 'email', $dMedico['field_email'] );
            $this->saveProfilePic();
            $this->saveUbicacion();
            $this->saveEspecialidades();
            $this->saveSubespecialidades();
            $this->saveConsultaOnline();
            $this->saveConsultorios();
            $this->saveInformacionProfesional();
        }
    }

    public function saveInformacionProfesional() {
        $informacion_profesional = array(
            'titulo' => $this->data['field_titulo_de_seccion'],
            'descripcion' => $this->data['field_descripcion_descripcion'],
        );

        $this->uf( 'informacion_profesional', $informacion_profesional );
    }

    public function saveConsultorios() {
        $this->uf( 'consultorios_seccion_titulo', $this->data['field_titulo'] );

        if ( isset( $this->data['field_traslados'] ) && ! empty( $this->data['field_traslados'] ) ) {
            $consultoriosSave = array();
            foreach ( $this->data['field_traslados'] as $id => $consultorio ) {
                $consultoriosSave[] = array(
                    'nombre'    => $consultorio['field_titulo'],
                    'direccion' => $consultorio['field_descripcion'],
                    'telefono'  => $consultorio['field_telefono'],
                );
            }
            $this->uf( 'consultorios', $consultoriosSave );
        }
    }

    public function saveConsultaOnline() {
        if ( isset( $this->data['field_url_del_boton'] ) && ! empty( $this->data['field_url_del_boton'] ) ) {
            $enlace      = $this->data['field_url_del_boton']['uri'];
            $boton_texto = $this->data['field_url_del_boton']['title'];

            $this->uf( 'consulta_online', array(
                'enlace'      => $enlace,
                'boton_texto' => $boton_texto,
            ) );
        }
    }

    public function saveUbicacion() {
        if ( isset( $this->data['field_ubicacion_ciudad'] ) && ! empty( $this->data['field_ubicacion_ciudad'] ) ) {
            $name = $this->data['field_ubicacion_ciudad']['name'];
            $term_id = $this->getUbiacionTermIdFromWp( $name );
            if ( $term_id ) {
                $this->uf( 'field_64adaac5947b9', $term_id ); // ubicación field
            }
        }
    }

    public function saveEspecialidades() {
        if ( isset( $this->data['field_especialidades'] ) && ! empty( $this->data['field_especialidades'] ) ) {
            $especialidadeIds = array();
            foreach( $this->data['field_especialidades'] as $id => $especialidad ) {
                $name = $especialidad['name'];
                $term_id = $this->getEspecialidadTermIdFromWp( $name );
                if ( $term_id ) {
                    $especialidadeIds[] = ( string ) $term_id; // Importante guardar como string para poder hacer query con REGEXP
                }
            }
            $this->uf( 'especialidades', $especialidadeIds );
        }
    }

    public function saveSubespecialidades() {
        if ( isset( $this->data['field_subespecialidad'] ) && ! empty( $this->data['field_subespecialidad'] ) ) {
            $subespecialidadeIds = array();
            foreach( $this->data['field_subespecialidad'] as $id => $subespecialidad ) {
                $name = $subespecialidad['name'];
                $term_id = $this->getSubespecialidadTermIdFromWp( $name );
                if ( $term_id ) {
                    $subespecialidadeIds[] = ( string ) $term_id; // Importante guardar como string para poder hacer query con REGEXP
                }
            }
            $this->uf( 'subespecialidades', $subespecialidadeIds );
        }
    }

    public function saveProfilePic() {
        $imageUrl = $this->data['field_image'];
        $image_id = media_sideload_image( $imageUrl, 0, null, 'id' );
        set_post_thumbnail( $this->postId, $image_id );
    }

    ////

    private function uf( $key, $value ) {
        update_field( $key, $value, $this->postId );
    }

    private function getUbiacionTermIdFromWp( $name ) {
        $term = get_term_by( 'name', $name, 'ubicacion' );
        if ( $term ) {
            return $term->term_id;
        }

        return false;
    }

    private function getEspecialidadTermIdFromWp( $name ) {
        $term = get_term_by( 'name', $name, 'especialidades' );
        if ( $term ) {
            return $term->term_id;
        }

        return false;
    }

    private function getSubespecialidadTermIdFromWp( $name ) {
        $term = get_term_by( 'name', $name, 'subespecialidades' );
        if ( $term ) {
            return $term->term_id;
        }

        return false;
    }

    public function deleteAllData() {
        $args = array(
            'post_type'   => 'medico',
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
