<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('axiom_welldone_instagram_widget_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_instagram_widget_theme_setup', 1 );
	function axiom_welldone_instagram_widget_theme_setup() {
		if (axiom_welldone_exists_instagram_widget()) {
			add_action( 'axiom_welldone_action_add_styles', 						'axiom_welldone_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'axiom_welldone_filter_importer_required_plugins',		'axiom_welldone_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'axiom_welldone_filter_required_plugins',					'axiom_welldone_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'axiom_welldone_exists_instagram_widget' ) ) {
	function axiom_welldone_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'axiom_welldone_instagram_widget_required_plugins' ) ) {
	//add_filter('axiom_welldone_filter_required_plugins',	'axiom_welldone_instagram_widget_required_plugins');
	function axiom_welldone_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', axiom_welldone_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'axiom-welldone'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'axiom_welldone_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'axiom_welldone_action_add_styles', 'axiom_welldone_instagram_widget_frontend_scripts' );
	function axiom_welldone_instagram_widget_frontend_scripts() {
		if (file_exists(axiom_welldone_get_file_dir('css/plugin.instagram-widget.css')))
			wp_enqueue_style( 'axiom_welldone-plugin.instagram-widget-style',  axiom_welldone_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'axiom_welldone_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'axiom_welldone_filter_importer_required_plugins',	'axiom_welldone_instagram_widget_importer_required_plugins', 10, 2 );
	function axiom_welldone_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (axiom_welldone_strpos($list, 'instagram_widget')!==false && !axiom_welldone_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'axiom-welldone');
		return $not_installed;
	}
}
?>