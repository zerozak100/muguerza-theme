

function initMap() {}
window.initMap = initMap;

jQuery( function ( $ ) {
    var map;
    var marker;
    var modal;
    
    var geoLocationMap;
    var locationsMap;

    var autocomplete;

    var searchInput = 'search-geolocation';

    /**
	 * MGLocationSelector class.
	 */
	var MGLocationSelector = function() {
        this.loadModal();
        this.loadGeolocationMap();
        this.loadLocationsMap();
        this.initPlaces();
        $( '#mg-location-selector' ).change( this.onGeolocationChange.bind( this ) );
	};

    MGLocationSelector.prototype.loadModal = function() {
        modal = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['mg-location-modal'],
            onOpen: function() {
                console.log('modal open');
            },
            onClose: function() {
                console.log('modal closed');
            },
            beforeClose: function() {
                // here's goes some logic
                // e.g. save content before closing the modal
                return true; // close the modal
                return false; // nothing happens
            }
        });
        modal.setContent( DATA.modal_content );
        modal.open();
    }

    MGLocationSelector.prototype.loadMaps = function( e ) {
        var lat = -34.397;
        var long = 150.644;

        var latLng = new google.maps.LatLng( lat, long );

        var mapOptions = {
            center: latLng,
            zoom: 8,
        }

        geoLocationMap = new google.maps.Map( document.getElementById( "geoLocationMap" ), mapOptions );
        locationsMap = new google.maps.Map( document.getElementById( "locationsMap" ), mapOptions );

        marker = new google.maps.Marker({
            position: latLng,
            map: geoLocationMap,
            title: "INIT",
            animation: google.maps.Animation.DROP,
        });
    }

    MGLocationSelector.prototype.loadGeolocationMap = function() {
        var lat = -34.397;
        var long = 150.644;

        var latLng = new google.maps.LatLng( lat, long );

        var mapOptions = {
            center: latLng,
            zoom: 8,
        }

        geoLocationMap = new google.maps.Map( document.getElementById( "geolocationMap" ), mapOptions );

        marker = new google.maps.Marker({
            position: latLng,
            map: geoLocationMap,
            title: "INIT",
            animation: google.maps.Animation.DROP,
        });
    }

    MGLocationSelector.prototype.loadLocationsMap = function() {
        var lat = -34.397;
        var long = 150.644;

        var latLng = new google.maps.LatLng( lat, long );

        var mapOptions = {
            center: latLng,
            zoom: 8,
        }

        locationsMap = new google.maps.Map( document.getElementById( "locationsMap" ), mapOptions );

        // marker = new google.maps.Marker({
        //     position: latLng,
        //     map: locationsMap,
        //     title: "INIT",
        //     animation: google.maps.Animation.DROP,
        // });
    }

    MGLocationSelector.prototype.loadMap = function( map,  ) {

    }

    MGLocationSelector.prototype.initPlaces = function() {
        var config = {
            types: ['geocode'],
        }

        autocomplete = new google.maps.places.Autocomplete( document.getElementById( searchInput ), config );

        google.maps.event.addListener( autocomplete, 'place_changed', function() {
            var near_place = autocomplete.getPlace();
            console.log(near_place);
        } );
    }

    MGLocationSelector.prototype.onGeolocationChange = function( e ) {
        var lat = $( e.target ).find( ':selected' ).data( 'lat' );
        var long = $( e.target ).find( ':selected' ).data( 'long' );

        var latLng = new google.maps.LatLng( lat, long );
        marker.setPosition( latLng );
        geoLocationMap.setCenter( latLng );
        // this.updatePosition( lat, long );
    }

    MGLocationSelector.prototype.updatePosition = function( lat, long ) {
        var latLng = new google.maps.LatLng( lat, long );
        marker.setPosition( latLng );
        map.setCenter( latLng );
    }

    /**
	 * Init MGLocationSelector.
	 */
	new MGLocationSelector();
} );