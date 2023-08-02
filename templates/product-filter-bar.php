<?php

$s               = ! empty( $_GET[ 's' ] ) ? $_GET[ 's' ] : '';
$city_id         = ! empty( $_GET[ 'city_id' ] ) ? $_GET[ 'city_id' ] : '';
$location_id     = ! empty( $_GET[ 'location_id' ] ) ? $_GET[ 'location_id' ] : '';
$service_type_id = ! empty( $_GET[ 'service_type_id' ] ) ? $_GET[ 'service_type_id' ] : '';


/**
 * @var WP_Term[] $service_types
 */
$service_types = $service_types;

?>

<form class="mg-product-filter-bar">
    <?php if ( MG_Product_Archive::is_page( 'servicios' ) ) : ?>
        <select class="mg-product-filter-bar__filter" name="service_type_id" id="service_type_id">
            <option selected disabled value="">Tipo de Servicio</option>
            <?php foreach ( $service_types as $service_type ): ?>
                <option <?php echo $service_type_id == $service_type->term_id ? 'selected' : ''; ?> value="<?php echo esc_attr( $service_type->term_id ); ?>"><?php echo esc_html( $service_type->name ); ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
    <!-- <select class="mg-product-filter-bar__filter" name="city_id" id="city_id">
        <option selected disabled value="">Ciudad</option>
        <?php foreach ( $cities as $city ): ?>
            <option <?php echo $city_id == $city->term_id ? 'selected' : ''; ?> value="<?php echo esc_attr( $city->term_id ); ?>"><?php echo esc_html( $city->name ); ?></option>
        <?php endforeach; ?>
    </select> -->
    <?php if ( ! empty( $city_id ) ): ?>
        <!-- <select class="mg-product-filter-bar__filter" name="location_id" id="location_id">
            <option selected disabled value="">Ubicación</option>
            <?php foreach ( $locations as $location ): ?>
                <option <?php echo $location_id == $location->term_id ? 'selected' : ''; ?> value="<?php echo esc_attr( $location->term_id ); ?>"><?php echo esc_html( $location->name ); ?></option>
            <?php endforeach; ?>
        </select> -->
    <?php endif; ?>
    <input class="mg-product-filter-bar__filter" type="text" placeholder="Buscar..." name="s" value="<?php echo esc_attr( $s ); ?>">
    <button class="mg-product-filter-bar__submit icon-search" type="submit"></button>
</form>

<script>
    jQuery(document).ready(function ($) {
        $("#city_id").change(function() {
            $("#location_id").val('');
            $("#location_id").html('<option selected disabled value="">Ubicación</option>')
        });
    });
</script>
