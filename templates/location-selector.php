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
                        <?php foreach ( MG_Location::all() as $location ) : ?>
                            <section class="mg-location-selector__list-item mg-location-item" data-lat="<?php echo $location['coords']['lat'] ?>" data-lng="<?php echo $location['coords']['lng'] ?>" data-id="<?php echo $location['id'] ?>">
                                <p class="mg-location-item__name"><?php echo $location['name']; ?></p>
                                <p class="mg-location-item__address"><?php echo $location['address']; ?></p>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mg-location-selector__info">
                    <p><b>Ubicación seleccionada:</b> <span class="mg-location-selector__selected">Ninguna</span></p>
                    <p><b>Mi ubicación:</b> <span class="mg-location-selector__my-location">Ninguna</span></p>
                </div>
                <div class="mg-location-selector__actions">
                    <button class="mg-location-selector__accept-btn">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

</div>
