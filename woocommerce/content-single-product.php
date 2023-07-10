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

				<button data-micromodal-trigger="booking-modal" id="agendar" class="single_add_to_cart_button">Agendar</button>
			</div>
			<div class="col-sec1 formulario">
				<?php echo do_shortcode( ' [contact-form-7 id="53120234051" title="Formulario especialidades"] ' ); ?>
			</div>
		</div>
	</div>

	<div class="seccion-submenu">
		<?php
			//echo $descripcion['mostrar'];
			if ($descripcion != null) {
				if ($descripcion['mostrar'] == true) {
					if ($descripcion['titulo'] == null) {
						echo '<div class="button">
							<a href="#descripcion">Descripcion</a>
						</div>';
					}else {
						echo '<div class="button">
							<a href="#'. sanitize_title($descripcion['titulo']) .'">' . $descripcion['titulo'] . '</a>
						</div>';
					}
					
				}
			}

			if ($padecimientos != null) {
				if ($padecimientos['mostrar'] == true) {
					if ($padecimientos['titulo'] == null) {
						echo '<div class="button">
							<a href="#padecimientos">Padecimientos</a>
						</div>';
					}else {
						echo '<div class="button">
							<a href="#'. sanitize_title($padecimientos['titulo']) .'">' . $padecimientos['titulo'] . '</a>
						</div>';
					}
				}
			}

			if ($procedimientos != null) {
				if ($procedimientos['mostrar'] == true) {
					if ($procedimientos['titulo'] == null) {
						echo '<div class="button">
							<a href="#procedimientos">Procedimientos</a>
						</div>';
					}else {
						echo '<div class="button">
							<a href="#'. sanitize_title($procedimientos['titulo']) .'">' . $procedimientos['titulo'] . '</a>
						</div>';
					}	
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
							<a href="#' . sanitize_title($seccion_generica['titulo']) . '">'. $seccion_generica['titulo'] .'</a>
						</div>';
					}
				}
			}
			
		?>
		
	</div>
	
	<?php
		if ($descripcion != null) {
			if ($descripcion['mostrar'] == true) {
				if ($descripcion['titulo'] == null) {
	?>
	<div id="descripcion" class="seccion-des">
		<div class="info-descripcion">
			<h2>Descripcion</h2>
			<?php echo $descripcion['texto']; ?>
			
		</div>
		<div class="imagen-descripcion">
			<?php echo wp_get_attachment_image($descripcion['imagen'], 'full'); ?>
		</div>
	</div>
	<?php 
				}else{
	?>
					<div id="<?php echo sanitize_title($descripcion['titulo']);?>" class="seccion-des">
						<div class="info-descripcion">
							<h2><?php echo $descripcion['titulo']; ?></h2>
							<?php echo $descripcion['texto']; ?>
							
						</div>
						<div class="imagen-descripcion">
							<?php echo wp_get_attachment_image($descripcion['imagen'], 'full'); ?>
						</div>
					</div>
	<?php
				}
			}
		} 
	?>

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
					}else{
		?>
						<div id="<?php echo sanitize_title($padecimientos['titulo']);?>" class="seccion">
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
					}else{
		?>
						<div id="<?php echo sanitize_title($procedimientos['titulo']);?>" class="seccion">
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
								$term = get_term_by( 'id', $row['ubicacion'][0], 'product_cat' );
								echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
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
						$term = get_term_by( 'id', $row['ubicacion'][0], 'product_cat' );
						echo '<div id="' . $term->slug . '" class="myDiv">' .  '<h2>' . $row['titulo'] . '</h2>' . $row['descripcion'] . '</div>';
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


<!-- <link rel="stylesheet" href=""> -->
<style>
	#booking-modal .modal__container {
		max-width: 1200px;
		width: 100%;
	}

	.booking-form {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 10px;
	}

	.booking-form__field {
		margin-bottom: 10px;
	}

	#booking-modal form {
		display: inline;
	}

	/* .booking-form__field--name {
		grid-column: 1 / 3;
	} */
</style>
<div class="modal micromodal-slide" id="booking-modal" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <form method="POST" class="modal__container" role="dialog" aria-modal="true" aria-labelledby="booking-modal-title">
        <header class="modal__header">
          <h2 class="modal__title" id="booking-modal-title">
            Agendar
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="booking-modal-content">
			<?php
				$calendar = new MG_Calendar( date( 'Y-m-d' ) );
				$calendar->display();
			?>

			<div class="booking-form">
				<div class="booking-form__field booking-form__field--name">
					<label for="">Nombre</label>
					<input type="text" name="name" placeholder="Nombre">
				</div>
				<div class="booking-form__field booking-form__field--email">
					<label for="">Correo</label>
					<input type="text" name="email" placeholder="Correo">
				</div>
				<div class="booking-form__field booking-form__field--first_last_name">
					<label for="">Apellido paterno</label>
					<input type="text" name="first_last_name" placeholder="Apellidos">
				</div>
				<div class="booking-form__field booking-form__field--second_last_name">
					<label for="">Apellido materno</label>
					<input type="text" name="second_last_name" placeholder="Apellidos">
				</div>
				<div class="booking-form__field booking-form__field--phone">
					<label for="">Celular</label>
					<input type="text" name="phone" placeholder="Celular">
				</div>
				<div class="booking-form__field booking-form__field--birthdate">
					<label for="">Fecha de nacimiento</label>
					<input type="text" name="birthdate" placeholder="Fecha de nacimiento">
				</div>
				<div class="booking-form__field booking-form__field--sex">
					<label for="">Sexo</label>
					<input type="text" name="sex" placeholder="Sexo">
				</div>
				<div class="booking-form__field booking-form__field--age">
					<label for="">Edad</label>
					<input type="text" name="age" placeholder="Edad">
				</div>
				<div class="booking-form__field booking-form__field--birth_state">
					<label for="">Estado de nacimiento</label>
					<input type="text" name="birth_state" placeholder="Estado de nacimiento">
				</div>
				<div class="booking-form__field booking-form__field--curp">
					<label for="">CURP</label>
					<input type="text" name="curp" placeholder="CURP">
				</div>
			</div>
          <!-- <p>
            Try hitting the <code>tab</code> key and notice how the focus stays within the modal itself. Also, <code>esc</code> to close modal.
          </p> -->
        </main>
        <footer class="modal__footer">
			<!-- <form action="" method="POST"> -->
				<button class="modal__btn modal__btn-primary" type="submit" name="mgb-booking-save" value="1">Añadir al carrito</button>
				<input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">
				<input type="hidden" name="datetime" value="2023-03-02">
			<!-- </form> -->
          <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Cerrar</button>
        </footer>
	  </form>
    </div>
  </div>

<!-- <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script> -->
<script>
	jQuery(document).ready(function($) {
		MicroModal.init({
			onShow: modal => console.info(`${modal.id} is shown`), // [1]
			onClose: modal => console.info(`${modal.id} is hidden`), // [2]
			// openTrigger: 'data-custom-open', // [3]
			// closeTrigger: 'data-custom-close', // [4]
			// openClass: 'is-open', // [5]
			disableScroll: true, // [6]
			disableFocus: false, // [7]
			awaitOpenAnimation: false, // [8]
			awaitCloseAnimation: false, // [9]
			debugMode: true, // [10]
		});
		// MicroModal.show('modal-1')
	});
</script>
