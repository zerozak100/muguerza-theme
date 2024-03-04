<?php
/**
 * Customer on-hold order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-on-hold-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<?php 
	$pasarela = $order->get_payment_method(); 
	
?>

<p><?php printf( esc_html__( '¡Hola, %s!', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>

<?php switch($pasarela) : 
	
	case "conektacard": ?>
		<p>En este momento, tu <strong>pedido con número <?php echo $order->get_order_number(); ?></strong> ha sido recibido. Estamos corroborando la confirmación de pago con las entidades bancarias correspondientes.</p>
		<p>Una vez confirmado el pago, recibirás un correo con las próximas indicaciones. En caso de no encontrarlo, te recomendamos buscar en tu bandeja de spam o correo no deseado.</p>
		<?php break; 
		
		case "cod": ?>
			<p>En este momento, tu <strong>pedido con número <?php echo $order->get_order_number(); ?></strong> ha sido recibido. Para seguir con tu proceso, acude al hospital correspondiente para realizar tu pago. Ahí recibirás próximas indicaciones y consultar todas tus dudas.</p>
		<?php break; 
		
		case "conektaspei": ?>
		<p>En este momento, tu <strong>pedido con número <?php echo $order->get_order_number(); ?></strong> ha sido recibido. A continuación podrás encontrar la CLABE Interbancaria que te ayudará a completar el proceso de pago.</p>
		<?php
			 if (get_post_meta( $order->get_id(), 'conekta-clabe', true ) != null)
			 {
					 echo '<p><strong>'.esc_html(__('Clabe')).':</strong> '
					 . esc_html( get_post_meta( $order->get_id(), 'conekta-clabe', true ) ). '</p>';
					 echo '<p><strong>'.esc_html(__('Beneficiario')).':</strong> Conekta SPEI</p>';
					 echo '<p><strong>'.esc_html(__('Banco Receptor')).':</strong>  Sistema de Transferencias y Pagos (STP)</p>';
			 }
			 echo '<p>Una vez confirmado el pago, recibirás un correo con las próximas indicaciones. En caso de no encontrarlo, te recomendamos buscar en tu bandeja de spam o correo no deseado.</p>';
			 break;

		case "conektaoxxopay": 
				$referenciaoxxo = get_post_meta( $order->get_id(), 'conekta-referencia');
				$referenciaoxxo = $referenciaoxxo[0];?>
			 	<p>En este momento, tu <strong>pedido con número <?php echo $order->get_order_number(); ?></strong> ha sido recibido. A continuación podrás encontrar el número de referencia con el cual realizarás el pago en tu Oxxo más cercano.</p>
				<p><strong>Referencia: <?php echo $referenciaoxxo; ?></strong><br>
				<span style="font-size:14px">Recuerda que Oxxo cobra una pequeña comisión adicional para realizar el pago.</span> </p>
				<p>Una vez confirmado el pago, recibirás un correo con las próximas indicaciones. En caso de no encontrarlo, te recomendamos buscar en tu bandeja de spam o  correo no deseado.</p>

	<?php break;
		default: ?>
			<p>En este momento, tu <strong>pedido con número <?php echo $order->get_order_number(); ?></strong> ha sido recibido. Estamos corroborando la confirmación de pago con las entidades bancarias correspondientes.</p>
			<p>Una vez confirmado el pago, recibirás un correo con las próximas indicaciones. En caso de no encontrarlo, te recomendamos buscar en tu bandeja de spam o  correo no deseado.</p>
	<?php break; 
	endswitch; ?>
	
	<p>¡Gracias por confiar en nosotros! <br>
	<strong>CHRISTUS MUGUERZA</strong>.</p>

<table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"><tbody><tr style="border-collapse:collapse"><td align="center" style="padding:0;Margin:0"><table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr style="border-collapse:collapse"><td align="left" style="padding:0;Margin:0"><table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tbody><tr style="border-collapse:collapse"><td valign="top" align="center" style="padding:0;Margin:0;width:100%"><table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#ffecd1;border-radius:4px" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffecd1" role="presentation"><tbody><tr style="border-collapse:collapse"><td align="center" spellcheck="false" data-ms-editor="true" style="padding:0;Margin:0;padding-top:15px;padding-left:30px;padding-right:30px;padding-bottom:5px;"><h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:18px;font-style:normal;font-weight:normal;color:#111111;text-align:center;">¿Necesitas ayuda? Estaremos felices de ayudarte</h3>
</td></tr><tr style="border-collapse:collapse"><td esdev-links-color="#ffa73b" align="center" spellcheck="false" data-ms-editor="true" style="padding:0;Margin:0;padding-bottom:5px;padding-left:30px;padding-right:30px"><strong><span style="color:#FF8C00">Encuentra el número telefónico, dirección y correo electrónico de tu unidad preferida en nuestra página de ubicaciones.</span></strong></td>
</tr><tr style="border-collapse:collapse"><td align="center" style="padding:0;Margin:0;padding-bottom:15px"><span class="es-button-border" style="border-style:solid;border-color:#FFA73B;background:#ffa73b;border-width:0px;display:inline-block;border-radius:9px;width:auto"><a href="https://www.christusmuguerza.com.mx/ubicaciones" class="es-button es-button-1" rel="noopener" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:14px;border-style:solid;border-color:#FFA73B;border-width:5px 15px;display:inline-block;background:#FFA73B;border-radius:9px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-weight:normal;font-style:normal;line-height:17px;width:auto;text-align:center">Click aquí</a></span></td></tr>

<tr style="border-collapse:collapse"><td class="es-m-txt-l" bgcolor="#ffffff" align="left" spellcheck="false" data-ms-editor="true" style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:0px;padding-right:0px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:27px;color:#666666;font-size:18px"><b>Resumen del pedido</b></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td>
</tr></tbody></table>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
