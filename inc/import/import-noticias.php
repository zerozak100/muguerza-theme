<?php

class MG_Noticias_Import {

    public function import( array $data ) {
        foreach ( $data as $drupalNoticia ) {

            $tags = $this->createTags( $drupalNoticia );
            $categories = $this->createCategories( $drupalNoticia );

            $noticiaId = wp_insert_post( array(
                'post_type'    => 'post',
                'post_status'  => 'publish',
                'post_title'   => $drupalNoticia['title'],
                'post_content' => $drupalNoticia['field_descripcion'],
                'post_excerpt' => $drupalNoticia['field_descripcion_corta'],
                'tax_input' => array(
                    'post_tag' => $tags, // especialidades
                    'category' => $categories,
                ),
                'meta_input' => array(
                    'dp_noticia_data' => $drupalNoticia,
                    'from_drupal'         => '1', 
                ),
            ) );

            $this->saveMainImage( $drupalNoticia['field_imagen_principal'], $noticiaId );
            $this->saveImages( $drupalNoticia['field_noticias_imagenes'], $noticiaId );
        }
    }

    public function createCategories( $data ) {
        $category = array();
        if ( $data['field_noticia_categoria'] ) {
            $categoryName = $data['field_noticia_categoria']['name'];
            if ( ! term_exists( $categoryName, 'category' ) ) {
                $term = wp_insert_term( $categoryName, 'category' );
                $category[] = (string) $term['term_id'];
            } else {
                $term = get_term_by( 'name', $categoryName, 'category' );
                $category[] = (string) $term->term_id;
            }
        }
        return $category;
    }

    public function createTags( $data ) {
        $termIds = array();

        if ( isset( $data['field_etiquetas'] ) && is_array( $data['field_etiquetas'] ) ) {
            $tags = $data['field_etiquetas'];
            foreach ( $tags as $tag ) {
                if ( ! term_exists( $tag['name'], 'post_tag' ) ) {
                    $term = wp_insert_term( $tag['name'], 'post_tag' );
                    $termIds[] = (string) $term['term_id'];
                } else {
                    $term = get_term_by( 'name', $tag['name'], 'category' );
                    $termIds[] = (string) $term->term_id;
                }
            }
        }

        return $termIds;
    }

    public function saveImages( $images, $postId ) {
        $ids = array();
        foreach ( $images as $url ) {
            $image_id = media_sideload_image( $url, 0, null, 'id' );
            $ids[] = $image_id;
        }

        update_post_meta( $postId, 'field_noticias_imagenes', $ids );
    }

    public function saveMainImage( $url, $postId ) {
        $image_id = media_sideload_image( $url, 0, null, 'id' );
        set_post_thumbnail( $postId, $image_id );
    }

    public function deleteAllData() {
        $args = array(
            'post_type'   => 'post',
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
