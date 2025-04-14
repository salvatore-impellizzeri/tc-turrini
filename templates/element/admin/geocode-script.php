<?php
// ! Add this snippet to edit.php
// ? Tip you can find Form input method for backend map in FormHelper
// $coords = !empty($item->geocode) ? $item->geocode : null;
// $this->element('admin/geocode-script', array('coords' => $coords));

use Cake\Core\Configure;

$coordinates = !empty($coords) ? explode(',', $coords) : array(0,0);
$latitude = !empty($coordinates[0]) ? trim($coordinates[0]) : Configure::read('Setup.geocodemap.latitude');
$longitude = !empty($coordinates[1]) ? trim($coordinates[1]) : Configure::read('Setup.geocodemap.longitude');

echo $this->Html->script('https://maps.googleapis.com/maps/api/js?v=3&key=' . Configure::read('Setup.googlemapskey'));
echo $this->Html->scriptStart(['block' => 'scriptBottom']) ?>

	var geocoder;
	var map;
	var marker;
	var formName = 'formMap';
	var formLatitudeField = 'latitude';
	var formLongitudeField = 'longitude';
	var formSearchLocation = 'maps-location-search';
	var formErrorText = 'gmaps-error';
	var existingLat = <?php echo $latitude ?>;
	var existingLng = <?php echo $longitude ?>;


	// Initialize Google Maps
	function initialize_google_maps(){

		var zoomLevel = 13;
		var latlng = new google.maps.LatLng(existingLat,existingLng,true);

		// Set the options for the map
		var options = {
			zoom: zoomLevel,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		// Create the Google Maps Object
		map = new google.maps.Map(document.getElementById("map-canvas"), options);

		// Create Google Geocode Object that will let us do lat/lng lookups based on address or location name
		geocoder = new google.maps.Geocoder();

		// Aggiunge il marker sulla mappa
		marker = new google.maps.Marker({
			map: map,
			draggable: true,
			position: latlng
		});

		// Si attiva quando il market viene spostato e rilasciato
		google.maps.event.addListener(marker, 'dragend', function() {
			update_ui('', marker.getPosition(), true);
		});

		// Si attiva quando il market viene rilasciato con il click sulla mappa
		google.maps.event.addListener(map, 'click', function(event) {
		    marker.setPosition(event.latLng);
		    update_ui(event.latLng.lat() + ', ' + event.latLng.lng(), event.latLng, true);
		});

		// Si attiva utilizzando lo zoom sulla mappa
		google.maps.event.addListener(map, 'zoom_changed', function() {
			zoomChangeBoundsListener =
			    google.maps.event.addListener(map, 'bounds_changed', function(event) {
			        if (this.getZoom() > 15 && this.initialZoom == true) {
			            // Change max/min zoom here
			            this.setZoom(15);
			            this.initialZoom = false;
			        }
			    google.maps.event.removeListener(zoomChangeBoundsListener);
			});
		});

	  map.initialZoom = true;

	}

	// Aggiorna il campo input del Geocode con latitudine e longitudine
	function update_ui( address, latLng, plot ) {
		$('[data-geocode-value]').val(latLng.lat()+','+latLng.lng());
	}

	// Si attiva effettuando il cambio indirizzo
	$('#GeocodeAddress').change(function(){
		geocodeAddress($('#GeocodeAddress').val(), geocoder, map);
	});

	// aggiorna la mappa e il campo input del Geocode con i risultati della ricerca
	function geocodeAddress(address, geocoder, resultsMap) {
		geocoder.geocode({'address': address}, function(results, status) {
		  if (status === 'OK') {
			$('[data-geocode-value]').val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
			var latlng = new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng(),true);
			marker.setPosition(latlng);
			map.panTo(latlng);
		  } else {
			//aladmert('Impossibile trovare l\'indirizzo selezionato');
			$('#geocodeaddress').val('');
		  }
		});
	}

	if( $('#map-canvas').length) {
		initialize_google_maps();
	};

<?php echo $this->Html->scriptEnd()  ?>
