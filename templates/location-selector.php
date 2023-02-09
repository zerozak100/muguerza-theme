<div class="mg-location-selector">
    <!-- <h3>Ubicación</h3> -->
    <div class="tabs">
        <ul id="tabs-nav">
            <li><a href="#geolocation">Mi ubicación</a></li>
            <li><a href="#locations">Ubicaciones</a></li>
        </ul>
        <div id="tabs-content">
            <div id="geolocation" class="tab-content">
                <h4>Mi ubicación</h4>
                <!-- <select name="mg-location" id="mg-location-selector">
                    <?php foreach ( MG_Location::all() as $location ) : ?>
                        <option data-lat="<?php echo $location['coords']['lat'] ?>" data-long="<?php echo $location['coords']['long'] ?>" value=""><?php echo $location['city_id'] ?></option>
                    <?php endforeach; ?>
                    <option data-lat="58.75082" data-long="15.89779" value="">AA</option>
                </select> -->
                <input type="text" id="search-geolocation" placeholder="Ingresa una dirección...">
                <div id="geolocationMap" class="map"></div>
            </div>
            <div id="locations" class="tab-content">
                <h4>Ubicaciones</h4>
                <div style="display: flex;">
                    <div id="locationsMap" class="map"></div>
                    <div style="width: 300px; text-align: center;">
                        <section>
                            <p><b>Hospital San Nicolás</b></p>
                        </section>
                        <section>
                            <p><b>Hospital San Pedro</b></p>
                        </section>
                        <section>
                            <p><b>Hospital Conchita</b></p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
