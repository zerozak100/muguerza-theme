<?php

defined( 'ABSPATH' ) || exit;

global $product;
//echo var_dump($product);
//$id_prodyct_type = echo get_field('producto_tipo', $product->ID);
//get_term_by('id', $id_prodyct_type, 'producto_tipo');
$mg_product = new MG_Product( $product );

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
    <div class="button">
        <a href="#descripcion">Descripción</a>
    </div>
    <div class="button">
        <a href="#descripcion-larga">Descripción larga</a>
    </div>
</div>

<!-- Secciones content -->
<div id="descripcion" class="seccion-servicio">
    <div class="info-descripcion">
        <h2>Descripción</h2>
        <p><?php echo $product->short_description; ?></p>
    </div>
</div>

<div id="descripcion-larga" class="seccion-servicio">
    <h2>Descripción larga</h2>
    <p><?php echo $product->description; ?></p>
</div>
