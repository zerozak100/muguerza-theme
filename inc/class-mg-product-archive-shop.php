<?php

class MG_Product_Archive_Shop extends MG_Product_Archive {

    /**
     * https://wordpress.stackexchange.com/questions/289284/tax-query-and-or-meta-query
     * 
     * Para cambiar AND por OR en la query de meta_query y tax_query
     */
    public function f1_egpaf_meta_or_tax( $where, WP_Query $q ) {
        // Get query vars.
        $tax_args = isset( $q->query_vars['tax_query'] ) ? $q->query_vars['tax_query'] : null;
        $meta_args = isset( $q->query_vars['meta_query'] ) ? $q->query_vars['meta_query'] : null;
        $meta_or_tax = isset( $q->query_vars['_meta_or_tax'] ) ? wp_validate_boolean( $q->query_vars['_meta_or_tax'] ) : false;

        // Construct the "tax OR meta" query.
        if ( $meta_or_tax && is_array( $tax_args ) && is_array( $meta_args ) ) {
            global $wpdb;
            // Primary id column.
            $field = 'ID';
            // Tax query.
            $sql_tax  = get_tax_sql( $tax_args, $wpdb->posts, $field );
            // Meta query.
            $sql_meta = get_meta_sql( $meta_args, 'post', $wpdb->posts, $field );
            // Modify the 'where' part.
            if ( isset( $sql_meta['where'] ) && isset( $sql_tax['where'] ) ) {
                $where  = str_replace(
                [ $sql_meta['where'], $sql_tax['where'] ],
                '',
                $where
                );
                $where .= sprintf(
                ' AND ( %s OR  %s ) ',
                substr( trim( $sql_meta['where'] ), 4 ),
                substr( trim( $sql_tax['where'] ), 4 )
                );
            }
        }

        return $where;
    }

    public function filter_products( $query ) {
        $tax_query  = $query->get( 'tax_query', array( 'relation' => 'OR' ) );
        $meta_query = $query->get( 'meta_query', array( 'relation' => 'OR' ) );

        if ( ! $this->is_recommending ) {
            $tax_query[] = array(
                'taxonomy' => 'mg_unidad',
                'field'     => 'term_id',
                'terms'    => $this->unidad->mg_unidad->term_id,
            );

            // $tax_query[] = array(
            //     'taxonomy' => 'product_cat',
            //     'field'    => 'term_id',
            //     'terms'    => $this->unidad_id,
            // );
    
            // $field_value = sprintf( '^%1$s$|s:%2$u:"%1$s";',  $this->unidad_id, strlen(  $this->unidad_id ) );
            // $meta_query[] = array(
            //     'key'     => 'disponibilidad_en_hospitales',
            //     // 'value'   => 'a:2:{i:0;i:48;i:1;i:47;}',
            //     'value'   => $field_value,
            //     'compare' => 'REGEXP',
            // );

            // add_filter( 'posts_where', array( $this, 'f1_egpaf_meta_or_tax' ), PHP_INT_MAX, 2 );
            // $query->set( '_meta_or_tax', true );
        }

        $query->set( 'meta_query', $meta_query );
        $query->set( 'tax_query', $tax_query );
        $query->set( 's', $this->s );        
    }

}
