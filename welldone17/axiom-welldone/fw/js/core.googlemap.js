function axiom_welldone_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof AXIOM_WELLDONE_STORAGE['googlemap_init_obj'] == 'undefined') axiom_welldone_googlemap_init_styles();
	AXIOM_WELLDONE_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: AXIOM_WELLDONE_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		axiom_welldone_googlemap_create(id);

	} catch (e) {
		
		dcl(AXIOM_WELLDONE_STORAGE['strings']['googlemap_not_avail']);

	};
}

function axiom_welldone_googlemap_create(id) {
	"use strict";

	// Create map
	AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].dom, AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers)
		AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	axiom_welldone_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].map)
			AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].map.setCenter(AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function axiom_welldone_googlemap_add_markers(id) {
	"use strict";
	for (var i in AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'].geocoder == '') AXIOM_WELLDONE_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			AXIOM_WELLDONE_STORAGE['googlemap_init_obj'].geocoder.geocode({address: AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						axiom_welldone_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(AXIOM_WELLDONE_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].title;
			AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].map.setCenter(AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].map,
								AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			AXIOM_WELLDONE_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function axiom_welldone_googlemap_refresh() {
	"use strict";
	for (var id in AXIOM_WELLDONE_STORAGE['googlemap_init_obj']) {
		axiom_welldone_googlemap_create(id);
	}
}

function axiom_welldone_googlemap_init_styles() {
	// Init Google map
	AXIOM_WELLDONE_STORAGE['googlemap_init_obj'] = {};
	AXIOM_WELLDONE_STORAGE['googlemap_styles'] = {
		'default': [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]
	};
	if (window.axiom_welldone_theme_googlemap_styles!==undefined)
		AXIOM_WELLDONE_STORAGE['googlemap_styles'] = axiom_welldone_theme_googlemap_styles(AXIOM_WELLDONE_STORAGE['googlemap_styles']);
}