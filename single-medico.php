<?php
get_header();
?>

<div class="container container-detalle-medico">
    <div class="medico">
        <div class="foto-medico">
            <?php 
                if ( get_field('foto_perfil', $post->ID) ) {
                    echo '<img src="' . wp_get_attachment_image_url( get_field('foto_perfil', $post->ID ) ) . '" />';
                }else{
                   echo '<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" />';
                }

                $online_consultation = get_field('consulta_online');
                if( $online_consultation ) {
                    echo '<a href="' . $online_consultation['enlace'] . '" class="button-medico">' . $online_consultation['boton_texto'] . '</a>';
                }
            ?>
            
        </div>
        <div class="info-medico">
            <h1><?php echo $post->post_title; ?></h1>
            <div class="info-medico-cols">
                <div class="col-2">
                    <p class="tarjeta-info-personal__item">
                        <strong>Especialidad : </strong>
                        <?php  
                            $specialty_ids = get_field('especialidades', $post->ID);
                            //echo var_dump($especialidad_ids) .'<br>';
                            $list = "";
                            if($specialty_ids) {
                                foreach($specialty_ids as $specialty_id) {
                                    $specialty = get_term($specialty_id);
                                    //echo var_dump($especialidad);
                                    //$especialidad->name;
                                    $list .= $specialty->name . ', ';
                                }
                                $list = rtrim ($list, ", ");
                                echo $list;
                            }
                        ?> 
                    </p>
                    <p class="tarjeta-info-personal__item">
                        <strong>Subespecialidad : </strong>
                        <?php  
                            $subspecialty_ids = get_field('subespecialidades', $post->ID);
                            //echo var_dump($especialidad_ids) .'<br>';
                            $list = "";
                            if($subspecialty_ids) {
                                foreach($subspecialty_ids as $subspecialty_id) {
                                    $subspecialty = get_term($subspecialty_id);
                                    //echo var_dump($especialidad);
                                    //$especialidad->name;
                                    $list .= $subspecialty->name . ', ';
                                }
                                $list = rtrim ($list, ", ");
                                echo $list;
                            }
                        ?>
                    </p>
                    <p class="tarjeta-info-personal__item"><strong>Cédula : </strong> <?php echo get_field('cedula', $post->ID) ?></p>
                </div>
                <div class="col-2">
                    <p class="tarjeta-info-personal__item">
                        <a href="tel:<?php echo get_field('telefono', $post->ID) ?>"><?php echo get_field('telefono', $post->ID) ?></a>
                    </p>
                    <p class="tarjeta-info-personal__item">
                        <a href="mailto:<?php echo get_field('email', $post->ID) ?>"><?php echo get_field('email', $post->ID) ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="detalle-medico">
        
        <?php
            $rows = get_field('consultorios');
            if( $rows ) {
                foreach( $rows as $row ) {
                    echo '<div class="consultorios seccion">';
                    echo '<h2>' . $row['nombre'] . '</h2>';
                    echo $row['direccion'];
                    echo '<a href="tel:' . $row['telefono'] . '"> Tel: ' . $row['telefono'] . '</a>';
                    echo '</div>';
                }
            }
        ?>
        
        <div class="informacion-personal seccion">
            <?php
                $personal_information = get_field('informacion_profesional');
                if ( $personal_information['titulo'] ) {
                    echo '<h2>' . $personal_information['titulo'] . '</h2>';
                    echo '<p>' . $personal_information['descripcion'];
                }else{
                    echo '<h2>Informacion personal</h2>';
                    echo $personal_information['descripcion'];
                }
            ?>
        </div>
    </div> 
</div>
<?php
get_footer();