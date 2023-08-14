<?php

/** @var MG_Unidad_Selector */
$unidad_selector = $unidad_selector;

$user = MG_User::current();

$unidad = $user->get_unidad();
$location = $user->get_location();

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
                        <?php $unidad_selector->display_unidades(); ?>
                    </div>
                </div>
                <div class="mg-location-selector__info">
                    <p><b>Unidad seleccionada:</b> <span class="mg-location-selector__selected"><?php echo esc_html( $unidad->get_name() ); ?></span></p>
                    <p><b>Mi ubicación:</b> <span class="mg-location-selector__my-location"><?php echo esc_html( $location->get_address() ); ?></span></p>
                </div>
                <div class="mg-location-selector__actions">
                    <button class="mg-location-selector__accept-btn">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

</div>
