/* global jQuery:false */
/* global AXIOM_WELLDONE_STORAGE:false */


// Theme-specific first load actions
//==============================================
function axiom_welldone_theme_ready_actions() {
	"use strict";
	// Put here your init code with theme-specific actions
	// It will be called before core actions
}


// Theme-specific scroll actions
//==============================================
function axiom_welldone_theme_scroll_actions() {
	"use strict";
	// Put here your theme-specific code with scroll actions
	// It will be called when page is scrolled (before core actions)
}


// Theme-specific resize actions
//==============================================
function axiom_welldone_theme_resize_actions() {
	"use strict";
	// Put here your theme-specific code with resize actions
	// It will be called when window is resized (before core actions)
}


// Theme-specific shortcodes init
//=====================================================
function axiom_welldone_theme_sc_init(cont) {
	"use strict";
	// Put here your theme-specific code to init shortcodes
	// It will be called before core init shortcodes
	// @param cont - jQuery-container with shortcodes (init only inside this container)
}


// Theme-specific post-formats init
//=====================================================
function axiom_welldone_theme_init_post_formats() {
	"use strict";
	// Put here your theme-specific code to init post-formats
	// It will be called before core init post_formats when page is loaded or after 'Load more' or 'Infinite scroll' actions
}


// Theme-specific GoogleMap styles
//=====================================================
function axiom_welldone_theme_googlemap_styles($styles) {
	"use strict";
	// Put here your theme-specific code to add GoogleMap styles
	// It will be called before GoogleMap init when page is loaded
	$styles['greyscale'] = [
    	{ "stylers": [
        	{ "saturation": -100 }
            ]
        }
	];
	$styles['inverse'] = [
		{ "stylers": [
			{ "invert_lightness": true },
			{ "visibility": "on" }
			]
		}
	];
	$styles['dark'] = [
		{ "featureType": "landscape",
		  "stylers": [
		  	{ "invert_lightness": true },
		  	{ "saturation":-100},
		  	{ "lightness":65},
		  	{ "visibility":"on"}
		  	]
		},
		{ "featureType": "poi",
		  "stylers": [
		  	{ "saturation":-100},
		  	{ "lightness":51},
		  	{ "visibility":"simplified"}
		  	]
		},
		{ "featureType": "road.highway",
		  "stylers": [
		  	{ "saturation":-100},
		  	{ "visibility":"simplified"}
		  	]
		},
		{ "featureType": "road.arterial",
		  "stylers": [
		  	{ "saturation":-100},
		  	{ "lightness":30},
		  	{ "visibility":"on"}
		  	]
		},
		{ "featureType": "road.local",
		  "stylers": [
		  	{ "saturation":-100},
		  	{ "lightness":40},
		  	{ "visibility":"on"}
		  	]
		},
		{ "featureType": "transit",
		  "stylers": [
		  	{ "saturation":-100},
		  	{ "visibility":"simplified"}
		  	]
		},
		{ "featureType":"administrative.province",
		  "stylers": [
		  	{ "visibility":"off"}
		  	]
		},
		{ "featureType":"water",
		  "elementType": "labels",
		  "stylers": [
		  	{ "visibility":"on"},
		  	{ "lightness":-25},
		  	{ "saturation":-100}
		  	]
		},
		{ "featureType":"water",
		  "elementType":"geometry",
		  "stylers": [
		  	{ "hue":"#ffff00"},
		  	{ "lightness":-25},
		  	{ "saturation":-97}
		  	]
		}
	];
	$styles['simple'] = [
    	{ stylers: [
        	{ hue: "#00ffe6" },
            { saturation: -20 }
			]
		},
		{ featureType: "road",
          elementType: "geometry",
          stylers: [
			{ lightness: 100 },
           	{ visibility: "simplified" }
            ]
		},
		{ featureType: "road",
          elementType: "labels",
          stylers: [
          	{ visibility: "off" }
            ]
		}
	];
	return $styles;
}
