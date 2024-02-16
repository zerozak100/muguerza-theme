<?php

defined( 'ABSPATH' ) || exit;

global $product;
//echo var_dump($product);
//$id_prodyct_type = echo get_field('producto_tipo', $product->ID);
//get_term_by('id', $id_prodyct_type, 'producto_tipo');
$mg_product = new MG_Product( $product );

?>

<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->ID), 'single-post-thumbnail'); ?>
<div class="cont-servicio" style="display: flex; gap: 40px; padding: 20px;">
    <div class="image-producto" style="width: 40%;">
        <img src="<?php echo $image[0] ?>" />
    </div>
    <div class="informacion" style="width: 60%;">
        <h1><?php echo $product->name; ?></h1>
        
        <style>
            .cont-servicio .price span {
                color: #671d75;
                font-size: 30px;
            }
        </style>
        <div class="cont-price-count">

            <?php if ( $mg_product->is_vendible() ) : ?>
                <p class="price"><?php echo $product->get_price_html(); ?></p>
            <?php endif; ?>

            <?php if ( $mg_product->is_agendable() ) : ?>
                <?php MG_Booking_Form::getInstance()->showOpenButton(); ?>
            <?php endif; ?>

            <?php
                if ( $mg_product->is_vendible_without_agenda() ) {
                    do_action('woocommerce_simple_add_to_cart');
                }
            ?>

        </div>
		
		<div class="categorias">
			<?php 
				//var_dump ( get_field('tipo_servicio', $product->ID) ); 
				echo 'Categorias: ';
				$category_ids = get_field('tipo_servicio', $product->ID);
				foreach($category_ids as $index => $category_id) {
					$term = get_term( $category_id );
					echo $term->name;
					if($index != count($category_ids) - 1) {
						echo ", ";
					}
				}
			?>
		</div>

        <ul class="tabs">
            <li class="tab-link current" data-tab="tab-1">Descripción</li>
            <li class="tab-link" data-tab="tab-2">Indicaciones</li>
        </ul>
        <div id="tab-1" class="tab-content current">
            <p><?php echo $product->description; ?></p>
        </div>
        <div id="tab-2" class="tab-content">
            <p><?php echo $product->short_description; ?></p>
        </div>
    </div>
</div>


<!--div class="seccion1-producto" style="background-image:url(<?php echo $image[0]; ?>);">
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
            <?php //echo do_shortcode(' [contact-form-7 id="53120234051" title="Formulario especialidades"] '); ?>
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


<div id="descripcion" class="seccion-servicio">
    <div class="info-descripcion">
        <h2>Descripción</h2>
        <p><?php echo $product->short_description; ?></p>
    </div>
</div>

<div id="descripcion-larga" class="seccion-servicio">
    <h2>Descripción larga</h2>
    <p><?php echo $product->description; ?></p>
</div-->
<?php

$args = array(
    'posts_per_page' => 4,
    'columns'        => 4,
    'orderby'        => 'rand', // @codingStandardsIgnoreLine.
);
woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
?>
