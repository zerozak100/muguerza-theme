<?php 
/* Template Name: Directorio telefonico */
$args = array('post_type' => 'unidad', 'numberposts' => -1);
$posts = get_posts($args);
//var_dump($posts);
get_header();
?>
<div id="banner">
    <div class="container">
        <h1 class="titulo-banner">Directorio Telefónico</h1>
        <p class="descripcion-banner">Selecciona una ubicación para consultar el directorio.</p>
        <form>
            <select id="unidad-telefono">
                <option>Perzonalizar ubicacion  </option>
                <?php
                    foreach($posts as $post) {
                        echo '<option value="' . $post->post_name . '">' . $post->post_title . '</option>';
                    }
                ?>
            </select>
        </form>
    </div>
</div>

<?php

    foreach($posts as $post) {
        $rows = get_field('directorio_telefonico', $post->ID);
        echo '<div id="' . $post->post_name . '" class="telefonos">';
        echo '<div class="container">';
        if( $rows ) {
            echo '<table class="default">
            <tr>
                <th>Departamento</th>
                <th>Telefono</th>
                <th>Whatsapp</th>
            </tr>';
            foreach( $rows as $row ) {
                echo '<tr>';
                echo '<td>'. $row['departamento'] .'</td>';
                echo '<td>'. $row['telefono'] . $row['extension'] .'</td>';
                echo '<td>'. $row['whatsapp'] .'</td>';
                echo'</tr>';
            }
            echo '</table>';
        }
        echo '</div>';
        echo '</div>';
    }
?>


<?php
get_footer();