<?php 
$args = array('post_type' => 'medico', 'posts_per_page' => 10, 'paged' => 10);

get_header();
?>
<div id="banner">
    <div class="container">
        <h1 class="titulo-banner">Directorio médico</h1>
        <form>
            <select id="unidad-telefono">
                <option>Perzonalizar ubicacion  </option>
                <?php
                    $posts = new WP_Query($args);
                    if ( $posts->have_posts() ) {
                        while ( $posts->have_posts() ) {
                            $posts->the_post();
                            echo '<option>' .  $posts->post->post_title . '</option>';
                        }
                        
                    }
                    wp_reset_postdata();
                ?>
            </select>
        </form>
    </div>
</div>

<div class="content-medicos">
    <div class="container">
        <div class="table">
            <div class="tableRow">
                <div class="tableCell">Medico</div>
                <div class="tableCell">Especialidad</div>
                <div class="tableCell">Ubicacion</div>
            </div>
            <?php
                $posts = new WP_Query($args);
                //var_dump($posts);
                set_query_var( 'is_recommending', false );
                wc_set_loop_prop( 'is_search', $posts->is_search() );
                wc_set_loop_prop( 'total', $posts->found_posts );
                wc_set_loop_prop( 'is_filtered', is_filtered() );
                wc_set_loop_prop( 'total_pages', $posts->max_num_pages );
                wc_set_loop_prop( 'per_page', $posts->get( 'posts_per_page' ) );
                wc_set_loop_prop( 'current_page', max( 1, $posts->get( 'paged', 1 ) ) );
                
                if ( $posts->have_posts() ) {
                    while ( $posts->have_posts() ) {
                        $posts->the_post();
                        echo '<a class="tableRow" href="'. get_permalink($posts->post->ID) .'">';

                        echo '<div class="tableCell">'.  $posts->post->post_title . '</div>';
                        echo '<div class="tableCell">';
                            $especialidad_ids = get_field('especialidades', $posts->post->ID);
                            //echo var_dump($especialidad_ids) .'<br>';
                            $lista = "";
                            if($especialidad_ids) {
                                foreach($especialidad_ids as $especialidad_id) {
                                    $especialidad = get_term($especialidad_id);
                                    //echo var_dump($especialidad);
                                    //$especialidad->name;
                                    $lista .= $especialidad->name . ', ';
                                }
                                $lista = rtrim ($lista, ", ");
                                echo $lista;
                            }
                        echo '</div>';
                        echo '<div class="tableCell">';
                            $ubicacion_id = get_field('ubicacion', $posts->post->ID);
                            $ubicacion = get_term($ubicacion_id);
                            echo $ubicacion->name; 
                        echo '</div>';
                        //echo var_dump(get_field('especialidades',$post->ID)) . '<br>';
                            
                        echo'</a>';
                    }
                        
                }
                wp_reset_postdata();
            ?>
        </div>
        <?php woocommerce_pagination(); ?>
    </div>
</div>
<?php
get_footer();