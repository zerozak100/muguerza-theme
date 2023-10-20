

function initMap() {}
window.initMap = initMap;

class GoogleAddressParser {

    constructor(address_components) {
        this.address = {};
        this.address_components = address_components;
        this.parseAddress();
    }

    parseAddress() {
        if (!Array.isArray(this.address_components)) {
            throw Error('Address Components is not an array');
        }

        if (!this.address_components.length) {
            throw Error('Address Components is empty');
        }

        for (let i = 0; i < this.address_components.length; i++) {
            const component = this.address_components[i];

            if (this.isStreetNumber(component)) {
                this.address.street_number = component.long_name;
            }

            if (this.isStreetName(component)) {
                this.address.street_name = component.long_name;
            }

            if (this.isCity(component)) {
                this.address.city = component.long_name;
            }

            if (this.isCountry(component)) {
                this.address.country = component.long_name;
                this.address.country_short = component.short_name;
            }

            if (this.isState(component)) {
                this.address.state = component.long_name;
                this.address.state_short = component.short_name;
            }

            if (this.isPostalCode(component)) {
                this.address.postal_code = component.long_name;
            }
        }
    }

    isStreetNumber(component) {
        return component.types.includes('street_number');
    }

    isStreetName(component) {
        return component.types.includes('route');
    }

    isCity(component) {
        return component.types.includes('locality');
    }

    isState(component) {
        return component.types.includes('administrative_area_level_1');
    }

    isCountry(component) {
        return component.types.includes('country');
    }

    isPostalCode(component) {
        return component.types.includes('postal_code');
    }

    result() {
        return this.address;
    }
}

class MG_Api {
    constructor( baseUrl ) {
        this.baseUrl = baseUrl;
    }

    /**
     * 
     * @param {String} lat 
     * @param {String} lng 
     * @returns {Response}
     */
    async geocodeInverse( lat, lng ) {
        const params = new URLSearchParams({
            latlng: `${lat},${lng}`,
            key: 'AIzaSyDLHEgck-NyHg9QBswGn2ayg65BiIo7kMo',
        });

        const url = 'https://maps.googleapis.com/maps/api/geocode/json?' + params;
        const response = await fetch(url);
        
        if (response.status >= 200 && response.status < 400) {
            const payload = await response.json();
            const { results } = payload;
            if (results && results.length) {
                return results[0];
            }
        }

        return false;
    }

    /**
     * 
     * @param {String} lat Latitutde
     * @param {String} lng Longitude
     * @returns {(Array|boolean)}
     */
    async getUnidadesOrderedByNearest(lat, lng) {
        const body = {
            action: 'order_unidades_by_distance',
            lat,
            lng,
        };
        const url = this.baseUrl;
        const response = await fetch(url, {
            method: 'POST',
            body: new URLSearchParams(body),
        });

        if (response.status >= 200 && response.status < 400) {
            const payload = await response.json();
            return payload;
        }

        return false;
    }

    /**
     * 
     * @param {Number} unidad_id ID de la unidad
     * @param {MG_Location} location Ubicaciòn del usuario
     * @returns 
     */
    async unidadSelectorSave( unidadId, location ) {
        const body = {
            action: 'unidad_selector_save',
            unidad_id: unidadId,
            location: JSON.stringify( location ),
        };

        const response = await fetch(this.baseUrl, {
            method: 'POST',
            // headers: { 'Content-Type': 'application/json' },
            // headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams( body ),
        });

        if (response.status >= 200 && response.status < 400) {
            const payload = await response.json();
            return payload;
        }

        return false;
    }
}

class MG_Location {
    constructor( data ) {
        // console.log('mg location constrcutor: ', data);
        this.address = data.address;
        this.lat = parseFloat( data.lat );
        this.lng = parseFloat( data.lng );
        this.name = data.name;
        this.zoom = data.zoom;
        this.place_id = data.place_id;
        this.street_number = data.street_number;
        this.street_name = data.street_name;
        this.city = data.city;
        this.state = data.state;
        this.state_short = data.state_short;
        this.post_code = data.post_code;
        this.country = data.country;
        this.country_short = data.country_short;
    }

    static fromGeocode( result ) {
        const { geometry, name, zoom, formatted_address, address_components, place_id } = result;
        const { location } = geometry;
        const { lat, lng } = location;
        const address = new GoogleAddressParser( address_components ).result();

        const data = {
            address: formatted_address,
            lat,
            lng,
            name: name || '',
            zoom: zoom || '',
            place_id: place_id,
            street_number: address.street_number,
            street_name: address.street_name,
            city: address.city,
            state: address.state,
            state_short: address.state_short,
            post_code: address.postal_code,
            country: address.country,
            country_short: address.country_short,
        };

        return new this( data );
    }

    static fromPlaceResult( placeResult ) {
        const { name, zoom, formatted_address, address_components, place_id, geometry } = placeResult;
        const { location } = geometry;
        const address = new GoogleAddressParser( address_components ).result();

        const data = {
            address: formatted_address,
            lat: location.lat(),
            lng: location.lng(),
            name: name || '',
            zoom: zoom || '',
            place_id: place_id,
            street_number: address.street_number,
            street_name: address.street_name,
            city: address.city,
            state: address.state,
            state_short: address.state_short,
            post_code: address.postal_code,
            country: address.country,
            country_short: address.country_short,
        };

        return new this( data );
    }

    getLatLng() {
        return new google.maps.LatLng( this.lat, this.lng );
    }
}
class MG_User {
    constructor( data ) {
        this.id = data.id;
        this.name = data.name;
        this.last_name = data.last_name;
        this.fullname = data.fullname;
        this.is_guest = data.is_guest;

        this.setUnidad( data.unidad );
        this.setLocation( data.location );
    }

    /**
     * 
     * @param {PlaceResult} placeResult 
     * 
     * https://developers.google.com/maps/documentation/javascript/reference/places-service?hl=es-419#PlaceResult
     */
    setLocationFromPlaceResult( placeResult ) {
        this.location = MG_Location.fromPlaceResult( placeResult );
    }

    setLocationFromGeocode( result ) {
        this.location = MG_Location.fromGeocode( result );
    }

    setLocation( data ) {
        this.location = new MG_Location( data );
    }

    setUnidad( data ) {
        this.unidad = new MG_Unidad( data );
    }

    /**
     * @return {MG_Location}
     */
    getLocation() {
        return this.location;
    }

    /**
     * @return {MG_Unidad}
     */
    getUnidad() {
        return this.unidad;
    }

    hasUnidad() {
        return Boolean( this.unidad.id );
    }

    hasLocation() {
        return Boolean( this.location.place_id );
    }
}

class MG_Unidad {
    constructor( data ) {
        this.id = data.id;
        this.name = data.name;
        this.location = new MG_Location( data.location );
        this.product_cat_city_id = data.product_cat_city_id;
        this.product_cat_unit_id = data.product_cat_unit_id;
    }

    /**
     * 
     * @returns {MG_Location}
     */
    getLocation() {
        return this.location;
    }
}

jQuery( function ( $ ) {
    /**
	 * MGLocationSelector class.
	 */
	var MGLocationSelector = function() {
        this.API = new MG_Api( DATA.ajaxurl );

        needsUnidad = DATA.needs_unidad;

        this.loadModal();
        this.loadUnidadesMap();
        this.initPlaces();
        this.setMarkers();

        $( '.mg-location-item' ).click( this.onUnidadSelected.bind( this ) );
        $( '.mg-location-selector__geolocate-btn' ).click( this.getCurrentPosition.bind( this ) );
        $( '.mg-location-selector__accept-btn' ).click( this.save.bind( this ) );
        $( '.otras-tiendas' ).click( function() {
            this.modal.open();
        }.bind( this ) );

        if ( needsUnidad ) {
            this.modal.open();
        }

        if ( ! DATA.unidad && ! needsUnidad ) {
            this.autosaveNearestUnidad();
        }
        
        this.user = new MG_User( DATA.user );

        if ( this.user.hasLocation() ) {
            const location = this.user.getLocation();
            const { address } = location;

            $( '#' + this.searchInput ).val( address );
        }
	};

    MGLocationSelector.prototype.locationsMap = null;
    MGLocationSelector.prototype.modal = null;
    MGLocationSelector.prototype.autocomplete = null;
    MGLocationSelector.prototype.searchInput = 'search-geolocation';
    /**
     * @type {MG_Api}
     */
    MGLocationSelector.prototype.API = null;

    /**
     * Unidad seleccionada
     * @type {MG_Unidad}
     */
    MGLocationSelector.prototype.unidad = null;

    /**
     * Usuario actual
     * @type {MG_User}
     */
    MGLocationSelector.prototype.user = null;

    /**
     * Ubicación seleccionada del usuario
     * @type {String}
     */
    MGLocationSelector.prototype.location = null;

    var needsUnidad = false;

    MGLocationSelector.prototype.toast = function( message, intent ) {
        var color = {
            success: 'green',
            danger: 'red',
        };

        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "bottom", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: color[ intent ],
            },
        }).showToast();
    }

    MGLocationSelector.prototype.save = async function( e ) {
        if ( ! this.user.hasUnidad() ) {
            this.toast( 'Es necesario seleccionar una unidad', 'danger' );
            return;
        }
        if ( ! this.user.hasLocation() ) {
            this.toast( 'Es necesario seleccionar ubicación actual', 'danger' );
            return;
        }

        try {
            this.setLoading( true );

            const location = this.user.getLocation();
            const unidad = this.user.getUnidad();

            const response = await this.API.unidadSelectorSave( unidad.id, location );

            if ( response ) {
                this.toast( 'Ubicación guardada', 'success' );
                needsUnidad = false;
                this.modal.close();
                window.location.reload();
            } else {
                this.toast( 'Error al guardar ubicación', 'danger' );
            }
        } catch( e ) {
            console.error( e );
            this.toast( 'Error al guardar ubicación', 'danger' );
        } finally {
            this.setLoading( false );
        }
    };

    MGLocationSelector.prototype.getCurrentPosition = async function() {
        /**
         * @this MGLocationSelector
         */
        async function success( pos ) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            try {
                const result = await this.API.geocodeInverse( lat, lng );

                this.user.setLocationFromGeocode( result );

                const location = this.user.getLocation();

                console.log( 'getCurrentPosition: ', location );
                console.log( location );

                $( '#' + this.searchInput ).val( location.address );
                $( '.mg-location-selector__my-location' ).html( location.address );

                const latLng = new google.maps.LatLng( location.lat, location.lng );
                this.locationsMap.setCenter( latLng );
                this.locationsMap.setZoom( 15 );
            } catch (e) {
                console.log(e);
                this.toast( 'Error al obtener dirección de la ubicación actual', 'danger' );
            } finally {
                this.setLoading( false );
            }
        }

        /**
         * @this MGLocationSelector
         */
        function error( err ) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
            this.toast( 'Error al obtener ubicación actual', 'danger' );
            this.setLoading( false );
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
        };

        this.setLoading( true );
        navigator.geolocation.getCurrentPosition( success.bind( this ), error.bind( this ), options );
    };

    MGLocationSelector.prototype.onUnidadSelected = function( e ) {
        var lat = $( e.currentTarget ).data( 'lat' );
        var lng = $( e.currentTarget ).data( 'lng' );
        var id = $( e.currentTarget ).data( 'id' );

        var latLng = new google.maps.LatLng( lat, lng );
        // marker.setPosition( latLng );
        this.locationsMap.setCenter( latLng );
        this.locationsMap.setZoom( 15 );

        this.user.setUnidad( DATA.unidades_by_id[ id ] );

        const unidad = this.user.getUnidad();

        $( '.mg-location-selector__selected' ).html( unidad.name );
    };

    MGLocationSelector.prototype.setMarkers = function() {
        /**
         * @type {MG_Unidad[]}
         */
        const unidades = DATA.unidades_ids.map( id => new MG_Unidad( DATA.unidades_by_id[ id ] ) );

        var markers = unidades.map(({ location }) => {
            const { lat, lng } = location;
            const myLatLng = { lat, lng };
            return new google.maps.Marker({
                position: myLatLng,
                map: this.locationsMap,
                title: name,
            });
        });
    }

    MGLocationSelector.prototype.loadModal = function() {
        var config = {
            footer: false,
            stickyFooter: false,
            // closeMethods: [],
            closeMethods: ['overlay', 'button', 'escape'],
            // closeLabel: "Close",
            cssClass: ['mg-location-modal'],
            onOpen: function() {
            },
            onClose: function() {
            },
            beforeClose: function() {
                return ! needsUnidad;
            }.bind( this )
        };

        if ( ! needsUnidad ) {
            config.closeMethods = ['overlay', 'button', 'escape'];
        }

        this.modal = new tingle.modal(config);
        this.modal.setContent( DATA.modal_content );
    }

    MGLocationSelector.prototype.loadUnidadesMap = function() {
        var lat;
        var lng;
        var zoom;

        if ( DATA.unidad ) {
            lat = DATA.unidad.location.lat;
            lng = DATA.unidad.location.lng;
            zoom = 15;
        } else {
            lat = 23.87168642809773;
            lng = -102.06577277533593;
            zoom = 4.5;
        }

        var latLng = new google.maps.LatLng( lat, lng );

        var mapOptions = {
            center: latLng,
            zoom,
        }

        this.locationsMap = new google.maps.Map( document.getElementById( "locationsMap" ), mapOptions );

        // marker = new google.maps.Marker({
        //     position: latLng,
        //     map: locationsMap,
        //     title: "INIT",
        //     animation: google.maps.Animation.DROP,
        // });
    }

    MGLocationSelector.prototype.initPlaces = function() {
        const config = {
            types: ['geocode'],
        }

        this.autocomplete = new google.maps.places.Autocomplete( document.getElementById( this.searchInput ), config );

        /**
         * @this MGLocationSelector
         */
        async function placeChanged() {
            this.user.setLocationFromPlaceResult( this.autocomplete.getPlace() );

            const location = this.user.getLocation();

            this.locationsMap.setCenter( location.getLatLng() );
            this.locationsMap.setZoom( 15 );

            $( '.mg-location-selector__my-location' ).html( location.address );
    
            this.orderUnidades( location.lat, location.lng );
        }

        google.maps.event.addListener( this.autocomplete, 'place_changed', placeChanged.bind( this ) );
    }

    MGLocationSelector.prototype.setLoading = function( val ) {
        $( '.mg-location-selector__accept-btn' ).prop( 'disabled', val );
        $( '.mg-location-selector__geolocate-btn' ).prop( 'disabled', val );
        $( '#search-geolocation' ).prop( 'disabled', val );
        if ( val === true ) {
        } else {
        }
     }
    
    MGLocationSelector.prototype.orderUnidades = async function( lat, lng ) {
        try {
            this.setLoading( true );
            const orderedUnidades = await this.API.getUnidadesOrderedByNearest( lat, lng );
            if ( ! orderedUnidades ) {
                throw "Error al ordenar ubicaciones";
            }
            this.orderUnidadesInDom( orderedUnidades );
            this.toast( 'Ubicaciones ordenadas', 'success' );
        } catch (e) {
            this.toast( 'Error al ordenar ubicaciones', 'danger' );
        } finally {
            this.setLoading( false );
        }
    }

    MGLocationSelector.prototype.autosaveNearestUnidad = async function() {
        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };
    
        /**
         * @this MGLocationSelector
         */
        const success = async function( pos ) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            const result = await this.API.geocodeInverse( lat, lng );
            const orderedUnidades = await this.API.getUnidadesOrderedByNearest( lat, lng );
            this.orderUnidadesInDom( orderedUnidades );

            const location = MG_Location.fromGeocode( result );
            const nearestUnidad = new MG_Unidad( orderedUnidades[0] );

            const response = await this.API.unidadSelectorSave( nearestUnidad.id, location );

            if ( response !== false ) {
                window.location.reload();
            }
        }
        
        navigator.geolocation.getCurrentPosition( success.bind( this ), undefined, options );   
    }

    MGLocationSelector.prototype.orderUnidadesInDom = async function( orderedUnidades ) {
        const $wrapper = $('.mg-location-selector__list');
        orderedUnidades.forEach(( unidad ) => {
            const item = $('.mg-location-item[data-id="'+unidad.id+'"]')
            $wrapper.append(item);
        });
    }

    /**
	 * Init MGLocationSelector.
	 */
	new MGLocationSelector();
} );