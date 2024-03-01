<?php

/** @var MG_Unidad $unidad */
$unidad = $unidad;

$location = $unidad->get_location();

// var_dump( $location, $unidad->get_name(), $unidad->acf_fields );

if ( ! $location ) {
    return;
}

?>

<section class="mg-location-selector__list-item mg-location-item" data-lat="<?php echo $location->get_lat(); ?>" data-lng="<?php echo $location->get_lng(); ?>" data-id="<?php echo $unidad->get_id(); ?>">
    <p class="mg-location-item__name"><?php echo $unidad->get_name(); ?></p>
    <p class="mg-location-item__address"><?php echo $location->get_address(); ?></p>
</section>
