<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$terms = get_the_terms( $product->ID, 'product_cat' );
foreach ($terms as $term) {
	//echo var_dump($term);
    $product_cat_id = $term->slug;
	if ($product_cat_id == 'especialidades' ) {
		break;
	}
    //
}

	$hero = get_field('hero');
	$descripcion = get_field('descripcion');
	$padecimientos = get_field('padecimientos');
	$procedimientos = get_field('procedimientos');
	$informacion_general = get_field('informacion_general');
	$seccion_genericas = get_field('seccion_generica');
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );?>
	<div class="seccion1-producto" style="background-image:url(<?php  echo $image[0]; ?>);">
		<!--div class="background-overlay"></div-->
		<div class="content-secc">
			<div class="col-sec1 info-producto">
				<h1><?php echo $product->name; ?></h1>
				<?php 
					if ($product_cat_id != 'especialidades') {
						echo '<p class="price">' . $product->get_price_html() . '</p>';
					}
				?>
				<ul>
					<?php
						if ($hero != null ){
							$rows = $hero['instrucciones'];
						
							foreach( $rows as $row ) {
							
								echo '<li>';
									echo $row['texto'];
								echo '</li>';
							}
						}
					?>
				</ul>
				<p><?php
					if ($hero != null) {
						echo $hero['texto'];
					} 
				?></p>
				<?php 
					if ($product_cat_id != 'especialidades') {
						echo '<p>Cantidad</p>';
						do_action('woocommerce_simple_add_to_cart');
					}
				?>
			</div>
			<div class="col-sec1 formulario">
				<?php echo do_shortcode( ' [contact-form-7 id="53120231093" title="Formulario de contacto 1"] ' ); ?>
			</div>
		</div>
	</div>

	<div class="seccion-submenu">
		<?php
			//echo $descripcion['mostrar'];
			if ($descripcion != null) {
				if ($descripcion['mostrar'] == true) {
					echo '<div class="button">
						<a href="#descripcion">Descripcion</a>
					</div>';
				}
			}

			if ($padecimientos != null) {
				if ($padecimientos['mostrar'] == true) {
					echo '<div class="button">
						<a href="#padecimientos">Padecimientos</a>
					</div>';
				}
			}

			if ($procedimientos != null) {
				if ($procedimientos['mostrar'] == true) {
					echo '<div class="button">
						<a href="#procedimientos">Procedimientos</a>
					</div>';
				}
			}

			if ($informacion_general != null) {
				if ($informacion_general['mostrar'] == true) {
					echo '<div class="button">
						<a href="#informacion-general">Informacion general</a>
					</div>';
				}
			}

			if ($seccion_genericas != null){
				foreach( $seccion_genericas as $seccion_generica ) {
					if ($seccion_generica['mostrar'] == true) {
						echo '<div class="button">
							<a href="#' . $seccion_generica['identificador'] . '">'. $seccion_generica['titulo'] .'</a>
						</div>';
					}
				}
			}
			
		?>
		
	</div>
	
	<?php
		if ($descripcion != null) {
			if ($descripcion['mostrar'] == true) {
	?>
	<div id="descripcion">
		<div class="info-descripcion">
			<h2><?php echo $descripcion['titulo']; ?></h2>
			<?php echo $descripcion['texto']; ?>
			
		</div>
		<div class="imagen-descripcion">
			<?php echo wp_get_attachment_image($descripcion['imagen'], 'full'); ?>
		</div>
	</div>
	<?php }} ?>

	<div class="contect-seccion">
		<?php 
			if ($padecimientos != null) {
				if ($padecimientos['mostrar'] == true) {
		?>
		<div id="padecimientos" class="seccion">
			<h2><?php echo $padecimientos['titulo']; ?></h2>
			<?php echo $padecimientos['texto']; ?>
		</div>
		<?php }} ?>

		<?php 
			if ($procedimientos != null) {
				if ($procedimientos['mostrar'] == true) {
		?>
		<div id="procedimientos" class="seccion">
			<h2><?php echo $procedimientos['titulo']; ?></h2>
			<?php echo $procedimientos['texto']; ?>
		</div>
		<?php }} ?>

		<?php
			if ($informacion_general != null) {
				if ($informacion_general['mostrar'] == true) {
		?>
		<div id="informacion-general" class="seccion">
			<div class="content-option-hospital">
				<div class="info" style="padding: 9px 15px 7px;background-color: #F2F2F2;margin-bottom: 1rem;">
					Selecciona el hospital de tu preferencia para conocer información relacionada.
				</div>
				<div class="opciones-hospitales">
					<p>Estás viendo: Información General.</p>
						
					<select id="select-hospital">
						<option value="principal">Todas las unidades</option>
						<?php
							$rows = $informacion_general['hospitales'];
							foreach( $rows as $row ) {
								//echo $row['ubicacion'][0];
								$term = get_term_by( 'id', $row['ubicacion'][0], 'product_cat' );
								echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
								//echo '<option ' . $term->slug . '>' . $term->name .'</option';
								//echo ($row['titulo']);
								//echo ($row['descripcion']);
							}
						?>
					</select>
				</div>
			</div>
			<div id="principal">
				<?php
					if ($informacion_general['especialidad'] != null) {
						echo $informacion_general['especialidad']; 
					}
					
				?>
			</div>
			<?php
				$rows = $informacion_general['hospitales'];
				if($rows != null){
					foreach( $rows as $row ) {
						//echo $row['ubicacion'][0];
						$term = get_term_by( 'id', $row['ubicacion'][0], 'product_cat' );
						echo '<div id="' . $term->slug . '" class="myDiv">' .  '<h2>' . $row['titulo'] . '</h2>' . $row['descripcion'] . '</div>';
						//echo '<option ' . $term->slug . '>' . $term->name .'</option';
						//echo ($row['titulo']);
						//echo ($row['descripcion']);
					}
				}
				
			?>
			
		</div>
		<?php }} ?>

		<?php 
		if ($seccion_genericas != null){
			foreach( $seccion_genericas as $seccion_generica ) {
				if ($seccion_generica['mostrar'] == true) {
					echo '<div id="'.$seccion_generica['identificador'].'" class="seccion">';
					echo '<h2>' . $seccion_generica['titulo'] . '</h2>';
					echo $seccion_generica['contenido'] ;
					echo '</div>';
				}
			}
		}
		?>
	</div>

	<div id="servicios-relacionados" class="seccion">
		<?php
			$product_related = $product->upsell_ids;
			if (!empty($product_related)){
				echo '<h2>Servicios relacionados</h2>';
			}
		?>
		<div class="content-serv-relacioandos">
			<?php 
			
				//echo var_dump($product->upsell_ids); 
				
				$i=0;
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

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
