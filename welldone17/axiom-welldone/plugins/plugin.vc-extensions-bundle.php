<?php
/* VC Extensions support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('axiom_welldone_vc_extensions_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_vc_extensions_theme_setup', 1 );
	function axiom_welldone_vc_extensions_theme_setup() {
		if (is_admin()) {
			add_filter( 'axiom_welldone_filter_importer_required_plugins',		'axiom_welldone_vc_extensions_importer_required_plugins', 10, 2 );
			add_filter( 'axiom_welldone_filter_required_plugins',				'axiom_welldone_vc_extensions_required_plugins' );
		}
	}
}

// Check if VC Extensions installed and activated
if ( !function_exists( 'axiom_welldone_exists_vc_extensions' ) ) {
	function axiom_welldone_exists_vc_extensions() {
        return class_exists('Vc_Manager') && class_exists('VC_Extensions_CQBundle');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'axiom_welldone_vc_extensions_required_plugins' ) ) {
	//add_filter('axiom_welldone_filter_required_plugins',	'axiom_welldone_vc_extensions_required_plugins');
	function axiom_welldone_vc_extensions_required_plugins($list=array()) {
		if (in_array('vc_extensions', axiom_welldone_storage_get('required_plugins'))) {
			$path = axiom_welldone_get_file_dir('plugins/install/vc-extensions-bundle.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Visual Composer Extensions Bundle', 'axiom-welldone'),
					'slug' 		=> 'vc_extensions',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// One-click import support
//------------------------------------------------------------------------

// Check VC Extensions in the required plugins
if ( !function_exists( 'axiom_welldone_vc_extensions_importer_required_plugins' ) ) {
	//add_filter( 'axiom_welldone_filter_importer_required_plugins',	'axiom_welldone_vc_extensions_importer_required_plugins', 10, 2 );
	function axiom_welldone_vc_extensions_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('vc_extensions', axiom_welldone_storage_get('required_plugins')) && !axiom_welldone_exists_vc_extensions() )
		if (axiom_welldone_strpos($list, 'vc_extensions')!==false && !axiom_welldone_exists_vc_extensions() )
			$not_installed .= '<br>'.esc_html__('VC Extensions', 'axiom-welldone');
		return $not_installed;
	}
}
?>