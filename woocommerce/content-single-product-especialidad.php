<?php

defined( 'ABSPATH' ) || exit;

global $product;
$mg_product = new MG_Product( $product );

$secciones = array(
	'descripcion' => 'Descripción',
	'padecimientos' => 'Padecimientos',
	'procedimientos' => 'Procedimientos',
	'informacion_general' => 'Información general',
	// 'seccion_generica' => 'Sección genérica',
);

$padecimientos 		 = get_field( 'padecimientos' );
$procedimientos 	 = get_field( 'procedimientos' );
$informacion_general = get_field( 'informacion_general' );
$seccion_genericas 	 = get_field( 'seccion_generica' );

?>

<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->ID), 'single-post-thumbnail'); ?>
<div class="seccion1-producto" style="background-image:url(<?php echo $image[0]; ?>);">
    <!--div class="background-overlay"></div-->
    <div class="content-secc">
        <div class="col-sec1 info-producto">
            <h1><?php echo $product->name; ?></h1>

            <?php if ($mg_product->is_vendible()) : ?>
                <p class="price"><?php echo $product->get_price_html() ?></p>
            <?php endif; ?>


            <?php if (have_rows('hero_instrucciones')) : ?>
                <ul>
                    <?php while (have_rows('hero_instrucciones')) : the_row(); ?>
                        <li><?php the_sub_field('texto'); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>

            <p><?php the_field('hero_texto') ?></p>

            <?php if ($mg_product->is_agendable()) : ?>
                <?php MG_Booking_Form::getInstance()->showOpenButton(); ?>
            <?php endif; ?>

            <?php
            if ($mg_product->is_vendible_without_agenda()) {
                // echo '<p>Cantidad</p>';
                do_action('woocommerce_simple_add_to_cart');
            }
            ?>

        </div>
        <div class="col-sec1 formulario">
            <?php echo do_shortcode(' [contact-form-7 id="53120234051" title="Formulario especialidades"] '); ?>
        </div>
    </div>
</div>

<div class="seccion-submenu">
    <?php foreach ($secciones as $key => $label) : ?>
        <?php if (have_rows($key)) : ?>
            <?php while (have_rows($key)) : the_row();

                if (!get_sub_field('mostrar')) {
                    continue;
                }

                $id = get_sub_field('titulo') ? sanitize_title(get_sub_field('titulo')) : "$key";
            ?>
                <div class="button">
                    <a href="#<?php echo $id; ?>"><?php echo get_sub_field('titulo') ?: $label; ?></a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (have_rows('seccion_generica')) : ?>
        <?php while (have_rows('seccion_generica')) : the_row(); ?>
            <?php if (get_sub_field('mostrar')) : ?>
                <div class="button">
                    <a href="#<?php sanitize_title(the_sub_field('titulo')); ?>"><?php the_sub_field('titulo'); ?></a>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<!-- Secciones content -->

<?php if (have_rows('descripcion')) : ?>
    <?php while (have_rows('descripcion')) : the_row();
        if (!get_sub_field('mostrar')) {
            continue;
        }

        $id = get_sub_field('titulo') ? sanitize_title(get_sub_field('titulo')) : 'descripcion';
    ?>
        <div id="<?php echo $id; ?>" class="seccion-des">
            <div class="info-descripcion">
                <h2><?php echo get_sub_field('titulo') ?: 'Descripción'; ?></h2>
                <?php the_sub_field('texto'); ?>
            </div>

            <?php ?>
            <div class="imagen-descripcion">
                <?php echo wp_get_attachment_image( get_sub_field( 'imagen' ), 'full' ); ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<div class="contect-seccion">
    <?php
    if ($padecimientos != null) {
        if ($padecimientos['mostrar'] == true) {
            if ($padecimientos['titulo'] == null) {
    ?>
                <div id="padecimientos" class="seccion">
                    <h2>Padecimientos</h2>
                    <?php echo $padecimientos['texto']; ?>
                </div>
            <?php
            } else {
            ?>
                <div id="<?php echo sanitize_title($padecimientos['titulo']); ?>" class="seccion">
                    <h2><?php echo $padecimientos['titulo']; ?></h2>
                    <?php echo $padecimientos['texto']; ?>
                </div>
    <?php
            }
        }
    }
    ?>

    <?php
    if ($procedimientos != null) {
        if ($procedimientos['mostrar'] == true) {
            if ($procedimientos['titulo'] == null) {
    ?>
                <div id="procedimientos" class="seccion">
                    <h2>Procedimientos</h2>
                    <?php echo $procedimientos['texto']; ?>
                </div>
            <?php
            } else {
            ?>
                <div id="<?php echo sanitize_title($procedimientos['titulo']); ?>" class="seccion">
                    <h2><?php echo $procedimientos['titulo']; ?></h2>
                    <?php echo $procedimientos['texto']; ?>
                </div>
    <?php
            }
        }
    }
    ?>

    <?php
    if ($informacion_general != null) {
        if ($informacion_general['mostrar'] == true) {
    ?>
            <div id="informacion-general" class="seccion">
                <div class="content-option-hospital">
                    <!-- <div class="info" style="padding: 9px 15px 7px;background-color: #F2F2F2;margin-bottom: 1rem;">
                        Selecciona el hospital de tu preferencia para conocer información relacionada.
                    </div> -->
                    <div class="opciones-hospitales">
                        <p>Estás viendo: Información General.</p>

                        <!-- <select id="select-hospital">
                            <option value="principal">Todas las unidades</option>
                            <?php
                            $rows = $informacion_general['hospitales'];
                            foreach ($rows as $row) {
                                $term = get_term_by('id', $row['ubicacion'], 'product_cat');
                                //echo '<option value="' . $term->slug . '">' . $row['ubicacion'] . '</option>';
                                echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
                            }
                            ?>
                        </select> -->
                    </div>
                </div>
                <!-- <div id="principal">
                    <?php
                    if ($informacion_general['especialidad'] != null) {
                        // echo $informacion_general['especialidad'];
                    }
                    ?>
                </div> -->
                <?

                $current_unidad = mg_get_current_unidad_id();
                $found_unidad = null;

                if ( ! empty( $informacion_general['hospitales'] ) && is_array( $informacion_general['hospitales'] ) ) {
                    foreach ( $informacion_general['hospitales'] as $hospital ) {
                        if ( $hospital['unidad'] == $current_unidad ) {
                            $found_unidad = $hospital;
                            break;
                        }
                    }
                }

                if ( $found_unidad ) {
                    // $term = get_term_by( 'id', $found_unidad['ubicacion'], 'product_cat' );
                    echo '<div id="' . $current_unidad . '">' .  '<h2>' . $found_unidad['titulo'] . '</h2>' . $found_unidad['descripcion'] . '</div>';
                }

                // $rows = $informacion_general['hospitales'];
                // if ($rows != null) {
                //     foreach ($rows as $row) {
                //         $term = get_term_by('id', $row['ubicacion'], 'product_cat');
                //         echo '<div id="' . $term->slug . '" class="myDiv">' .  '<h2>' . $row['titulo'] . '</h2>' . $row['descripcion'] . '</div>';
                //     }
                // }
                ?>

            </div>
    <?php }
    } ?>

    <?php
    if ($seccion_genericas != null) {
        foreach ($seccion_genericas as $seccion_generica) {
            if ($seccion_generica['mostrar'] == true) {
                echo '<div id="' . $seccion_generica['identificador'] . '" class="seccion">';
                echo '<h2>' . $seccion_generica['titulo'] . '</h2>';
                echo $seccion_generica['contenido'];
                echo '</div>';
            }
        }
    }
    ?>
</div>

<div id="servicios-relacionados" class="seccion">
    <?php
    $product_related = $product->upsell_ids;
    if (!empty($product_related)) {
        echo '<h2>Servicios relacionados</h2>';
    }
    ?>
    <div class="content-serv-relacioandos">
        <?php

        //echo var_dump($product->upsell_ids); 

        $i = 0;
        foreach ($product_related as $valor) {
            //echo $valor . '<br>'; 
            $productr = wc_get_product($valor);
            $producto_relacionado[$i] = get_field('informacion_sobre_producto_relacionado', $valor);
            echo '<div class="servicio">';
            echo '<a href="' . $productr->get_permalink() . '">';
            echo '<img src="' . $producto_relacionado[$i]['icono'] . '" width="50">';
            echo '<h6>' . $productr->get_name() . '</h6>';
            echo $producto_relacionado[$i]['descripcion'];
            echo 'VER MAS';
            echo '</a>';
            echo '</div>';
            $i++;
        }
        //$cadena = "Esta es la cadena que quiero cambiar";

        //$cadenaConvert = strtr($cadena, " ", "_");

        //echo $cadenaConvert;

        ?>

    </div>
</div>