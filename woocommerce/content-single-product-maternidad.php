<?php

defined( 'ABSPATH' ) || exit;

global $product;
$mg_product = new MG_Product( $product );

/*$secciones = array(
	'descripcion' => 'Descripción',
	'padecimientos' => 'Padecimientos',
	'procedimientos' => 'Procedimientos',
	'informacion_general' => 'Información general',
	// 'seccion_generica' => 'Sección genérica',
);*/

$que_incluye 		 = get_field( 'que_incluye' );
$extras 		     = get_field( 'extras' );
$beneficios 		 = get_field( 'beneficios' );
$servicios 		     = get_field( 'servicios' );
$restricciones 		 = get_field( 'restricciones' );
$instalaciones 		 = get_field( 'instalaciones' );

$mg_unidad = get_field( 'unidad' );

$args = array(
    'numberposts' => 1,
    'post_type'   => 'unidad',
    'tax_query'   => array(
        'AND',
        array(
            'taxonomy' => 'mg_unidad',
            'terms'    => array( $mg_unidad[0] ),
            'field'    => 'term_id',
        )
    ),
  );
  
$unidades = get_posts( $args );

if ( $unidades ) {
    $unidad = new MG_Unidad( $unidades[0] );
}

$instalaciones_maternidad = count( $unidades ) ? get_field( 'instalaciones_maternidad', $unidades[0]->ID ) : false;

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

<div class="seccion-submenu menu-maternidad">
    <?php if ( $que_incluye != null ) { ?>
        <div class="button">
            <a href="#que-incluye">¿Qué incluye?</a>
        </div>
    <?php } ?>

    <?php if ( $extras != null ) { ?>
        <div class="button">
            <a href="#extras">Extras</a>
        </div>
    <?php } ?>
    
    <?php if ( $beneficios != null ) { ?>
        <div class="button">
            <a href="#beneficios">Beneficios</a>
        </div>
    <?php } ?>
    
    <?php if ( $servicios != null ) { ?>
        <div class="button">
            <a href="#servicios">Servicios</a>
        </div>
    <?php } ?>

    <?php if ( $restricciones != null ) { ?>
        <div class="button">
            <a href="#restricciones">Restricciones</a>
        </div>
    <?php } ?>

    <?php if ( $instalaciones_maternidad != null) { ?>
        <div class="button">
            <a href="#instalaciones">Instalaciones</a>
        </div>
    <?php } ?>

</div>

<!-- Secciones content -->
<div class="contect-seccion">
    <?php
    if ( $que_incluye !=null ) {
        echo '<div id="que-incluye" class="seccion">';
            echo '<h2>¿Qué incluye?</h2>';
            echo '<ul class="puntos">';
                foreach ( $que_incluye as $incluye ) {
                    echo '<li>' . $incluye['punto'] . '</li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    if ( $extras !=null ) {
        echo '<div id="extras" class="seccion">';
            echo '<h2>Extras</h2>';
            echo '<ul class="puntos">';
                foreach ( $que_incluye as $incluye ) {
                    echo '<li>' . $incluye['punto'] . '</li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    if ( $beneficios !=null ) {
        echo '<div id="beneficios" class="seccion">';
            echo '<h2>Beneficios</h2>';
            echo '<ul class="puntos">';
                foreach ( $beneficios as $beneficio ) {
                    echo '<li>' . $beneficio['punto'] . '</li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    if ( $servicios !=null ) {
        echo '<div id="servicios" class="seccion">';
            echo '<h2>Servicios</h2>';
            echo '<ul class="puntos">';
                foreach ( $servicios as $servicio ) {
                    echo '<li>' . $servicio['punto'] . '</li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    if ( $restricciones !=null ) {
        echo '<div id="restricciones" class="seccion">';
            echo '<h2>Restricciones</h2>';
            echo '<ul class="puntos">';
                foreach ( $restricciones as $restriccione ) {
                    echo '<li>' . $restriccione['punto'] . '</li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    if ( $instalaciones_maternidad !=null ) {
        echo '<div id="instalaciones" class="seccion">';
            echo '<h2>Instalaciones</h2>';
            echo '<div class="instalaciones">';
                foreach ( $instalaciones_maternidad as $imagen_mat) {
                    echo '<div class="carousel-cell"> <img src="' . $imagen_mat['imagen']['url'] . '"/> </div>';
                }
            echo '</div>';
        echo '</div>';
    }
    ?>
</div>
