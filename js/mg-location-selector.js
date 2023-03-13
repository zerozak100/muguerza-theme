

function initMap() {}
window.initMap = initMap;

jQuery( function ( $ ) {
    // var map;
    // var marker;
    // var modal;
    // var geoLocationMap;
    // var locationsMap;
    // var autocomplete;
    // var searchInput = 'search-geolocation';
    // var selectedLocation;

    /**
	 * MGLocationSelector class.
	 */
	var MGLocationSelector = function() {
        needsLocation = DATA.needs_location;
        this.loadModal(needsLocation);
        // this.loadGeolocationMap();
        this.loadLocationsMap();
        this.initPlaces();
        this.setMarkers();
        // $( '#mg-location-selector' ).change( this.onGeolocationChange.bind( this ) );
        $( '.mg-location-item' ).click( this.onLocationSelected.bind( this ) );
        $( '.mg-location-selector__geolocate-btn' ).click( this.geolocate.bind( this ) );
        $( '.mg-location-selector__accept-btn' ).click( this.save.bind( this ) );
        $( '.otras-tiendas' ).click( function() {
            this.modal.open();
        }.bind( this ) );
        if (needsLocation) {
            this.modal.open();
        }

        this.selectedGeolocation = DATA.selectedGeolocation;
        this.selectedLocation = DATA.selectedLocation;

        if ( this.selectedGeolocation && this.selectedGeolocation.formatted_address ) {
            $('#' + this.searchInput).val( this.selectedGeolocation.formatted_address );
        }
	};

    MGLocationSelector.prototype.locationsMap = null;
    MGLocationSelector.prototype.modal = null;
    MGLocationSelector.prototype.autocomplete = null;
    MGLocationSelector.prototype.searchInput = 'search-geolocation';
    MGLocationSelector.prototype.selectedLocation = null;
    MGLocationSelector.prototype.selectedGeolocation = null;
    var needsLocation = false;
    // MGLocationSelector.prototype.saving = false;

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

    MGLocationSelector.prototype.save = function( e ) {
        // this.saving = true;

        if ( ! this.selectedLocation ) {
            this.toast( 'Es necesario seleccionar una ubicación', 'danger' );
            return;
        }
        if ( ! this.selectedGeolocation ) {
            this.toast( 'Es necesario seleccionar ubicación actual', 'danger' );
            return;
        }

        this.setLoading( true );

        var data = {
            action: 'location_selector_save',
            location: this.selectedLocation['id'],
            geolocation_address: this.selectedGeolocation['formatted_address'],
            geolocation_lat: this.selectedGeolocation['coords']['lat'],
            geolocation_lng: this.selectedGeolocation['coords']['lng'],
        };

        $.post({
            url: DATA.ajaxurl,
            data,
        })
        .done( function( response ) {
            this.toast( 'Ubicación guardada', 'success' );
            needsLocation = false;
            this.modal.close();
            location.reload();
        }.bind( this ) )
        .fail( function() {
            this.toast( 'Error al guardar ubicación', 'danger' );
        }.bind( this ) )
        .always( function() {
            this.setLoading( true );
        }.bind( this ) );
    };

    MGLocationSelector.prototype.geolocate = function() {
        this.setLoading( true );

        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };

        var success = function( pos ) {
            const crd = pos.coords;


            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json',
                data: {
                    latlng: crd.latitude+","+crd.longitude,
                    key: 'AIzaSyDLHEgck-NyHg9QBswGn2ayg65BiIo7kMo',
                },
            })
            .done( function( response ) {
                if ( response && response.results && response.results.length) {
                    var location = response.results[0];
                    $( '#' + this.searchInput ).val( location.formatted_address );
                    $( '.mg-location-selector__my-location' ).html( location.formatted_address );
                    this.selectedGeolocation = {
                        coords: {
                            lat: crd.latitude,
                            lng: crd.longitude,
                        },
                        formatted_address: location.formatted_address,
                    };
                    this.fetchOrderedLocations( crd.latitude, crd.longitude );
                    var latLng = new google.maps.LatLng( crd.latitude, crd.longitude );
                    this.locationsMap.setCenter( latLng );
                    this.locationsMap.setZoom( 15 );
                }
            }.bind( this ) )
            .fail( function ( jqXHR, textStatus, errorThrown ) {
                this.toast( 'Error al obtener dirección de la ubicación actual', 'danger' );
            }.bind( this ) )
            .always( function (  ) {
                this.setLoading( false );
            }.bind( this ) );

        }.bind( this );

        var error = function( err ) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
            this.toast( 'Error al obtener ubicación actual', 'danger' );
            this.setLoading( false );
        }.bind( this );

        navigator.geolocation.getCurrentPosition( success, error, options );
    };

    MGLocationSelector.prototype.onLocationSelected = function( e ) {
        var lat = $( e.currentTarget ).data( 'lat' );
        var lng = $( e.currentTarget ).data( 'lng' );
        var id = $( e.currentTarget ).data( 'id' );

        var latLng = new google.maps.LatLng( lat, lng );
        // marker.setPosition( latLng );
        this.locationsMap.setCenter( latLng );
        this.locationsMap.setZoom( 15 );
        // this.updatePosition( lat, lng );

        this.selectedLocation = DATA.locations[ id ];

        $( '.mg-location-selector__selected' ).html( this.selectedLocation.name );
    };

    MGLocationSelector.prototype.setMarkers = function() {
        var locations = Object.keys(DATA.locations).map(l => DATA.locations[l]);
        var markers = locations.map(l => {
            const myLatLng = { lat: +l.coords.lat, lng: +l.coords.lng };
            return new google.maps.Marker({
                position: myLatLng,
                map: this.locationsMap,
                title: l.name,
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
                return !needsLocation;
                // here's goes some logic
                // e.g. save content before closing the modal
                return true; // close the modal
                // return false; // nothing happens
            }.bind( this )
        };
        if (!needsLocation) {
            config.closeMethods = ['overlay', 'button', 'escape'];
        }
        this.modal = new tingle.modal(config);
        this.modal.setContent( DATA.modal_content );
        // this.modal.open();
    }

    MGLocationSelector.prototype.loadLocationsMap = function() {
        var lat;
        var lng;
        var zoom;

        if ( DATA.location_coords ) {
            lat = DATA.location_coords.lat;
            lng = DATA.location_coords.lng;
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
        var config = {
            types: ['geocode'],
        }

        this.autocomplete = new google.maps.places.Autocomplete( document.getElementById( this.searchInput ), config );

        google.maps.event.addListener( this.autocomplete, 'place_changed', function() {
            var near_place = this.autocomplete.getPlace();
            this.locationsMap.setCenter( near_place.geometry.location );
            this.locationsMap.setZoom( 15 );
            $( '.mg-location-selector__my-location' ).html( near_place.formatted_address );
            this.selectedGeolocation = {
                coords: {
                    lat: near_place.geometry.location.lat(),
                    lng: near_place.geometry.location.lng(),
                },
                formatted_address: near_place.formatted_address,
            };
            this.fetchOrderedLocations( near_place.geometry.location.lat(), near_place.geometry.location.lng() );
        }.bind( this ) );
    }

    MGLocationSelector.prototype.setLoading = function( val ) {
        $( '.mg-location-selector__accept-btn' ).prop( 'disabled', val );
        $( '.mg-location-selector__geolocate-btn' ).prop( 'disabled', val );
        $( '#search-geolocation' ).prop( 'disabled', val );
        if ( val === true ) {
        } else {
        }
     }

    // MGLocationSelector.prototype.onGeolocationChange = function( e ) {
    //     var lat = $( e.target ).find( ':selected' ).data( 'lat' );
    //     var long = $( e.target ).find( ':selected' ).data( 'long' );

    //     var latLng = new google.maps.LatLng( lat, long );
    //     marker.setPosition( latLng );
    //     this.locationsMap.setCenter( latLng );
    //     // this.updatePosition( lat, long );
    // }

    // MGLocationSelector.prototype.updatePosition = function( lat, long ) {
    //     var latLng = new google.maps.LatLng( lat, long );
    //     marker.setPosition( latLng );
    //     map.setCenter( latLng );
    // }

    MGLocationSelector.prototype.fetchOrderedLocations = function( lat, lng ) {
        var data = {
            action: 'order_nearest_locations',
            lat,
            lng,
        };
        
        $.post({
            url: DATA.ajaxurl,
            data,
        })
        .done( function( response ) {
            // https://stackoverflow.com/questions/16129259/re-arranging-sorting-divs-without-re-inserting-into-dom
            this.toast( 'Ubicaciones ordenadas', 'success' );
            var $wrapper = $('.mg-location-selector__list');
            var locationIds = response;
            locationIds.forEach(function(id){
                var item = $('.mg-location-item[data-id="'+id+'"]')
                $wrapper.append(item);
            });
        }.bind( this ) )
        .fail( function() {
            this.toast( 'Error al ordenar ubicaciones', 'danger' );
        }.bind( this ) )
        .always( function() {
            // this.setLoading( true );
        }.bind( this ) );
    }

    /**
	 * Init MGLocationSelector.
	 */
	new MGLocationSelector();
} );