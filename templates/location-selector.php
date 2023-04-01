<?php
$user = MG_User::current();

$selected_location = mg_get_saved_location_name() ?: 'Ninguna';
$saved_address = mg_get_saved_address() ?: 'Ninguna';
$user_coords = mg_get_saved_geolocation_coords();

$locations = MG_Location::all();
$locations_keys = array();

if ( $user_coords ) {
    $locations_keys = mg_get_locations_in_order( $locations, $user_coords );
} else {
    $locations_keys = array_keys( $locations );
}

?>
<div class="mg-location-selector">
    <div class="tabs">
        <ul id="tabs-nav">
            <li><a href="#locations">Ubicaciones</a></li>
        </ul>
        <div id="tabs-content">
            <div id="locations" class="tab-content">
                <div class="mg-location-selector__autocomplete">
                    <input type="text" id="search-geolocation" placeholder="Ingresa tu ubicación actual...">
                    <button class="mg-location-selector__geolocate-btn">Mi ubicación</button>
                </div>
                <div class="mg-location-selector__wrapper">
                    <div id="locationsMap" class="map"></div>
                    <div class="mg-location-selector__list">
                        <?php foreach ( $locations_keys as $key ) : ?>
                            <section class="mg-location-selector__list-item mg-location-item" data-lat="<?php echo $locations[$key]['coords']['lat'] ?>" data-lng="<?php echo $locations[$key]['coords']['lng'] ?>" data-id="<?php echo $locations[$key]['id'] ?>">
                                <p class="mg-location-item__name"><?php echo $locations[$key]['name']; ?></p>
                                <p class="mg-location-item__address"><?php echo $locations[$key]['address']; ?></p>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mg-location-selector__info">
                    <p><b>Ubicación seleccionada:</b> <span class="mg-location-selector__selected"><?php echo esc_html( $selected_location ); ?></span></p>
                    <p><b>Mi ubicación:</b> <span class="mg-location-selector__my-location"><?php echo esc_html( $saved_address ); ?></span></p>
                </div>
                <div class="mg-location-selector__actions">
                    <button class="mg-location-selector__accept-btn">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

</div>
