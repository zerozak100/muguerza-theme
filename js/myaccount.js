jQuery(document).ready(function($) {
    /**
     * Prevent multiple form registration submits
     */
    $('.woocommerce-form-register').submit(function(event) {
        // Verificar si el formulario ya se ha enviado
        if ($(this).data('submitted')) {
            event.preventDefault();
        } else {
            // Desactivar el botón de envío y marcar el formulario como enviado
            $('.woocommerce-form-register__submit').prop('disabled', true);
            $(this).data('submitted', true);
        }
    });
});
