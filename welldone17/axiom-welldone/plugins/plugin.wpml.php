<?php
/* WPML support functions
------------------------------------------------------------------------------- */

// Check if WPML installed and activated
if ( !function_exists( 'axiom_welldone_exists_wpml' ) ) {
	function axiom_welldone_exists_wpml() {
		return defined('ICL_SITEPRESS_VERSION') && class_exists('sitepress');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'axiom_welldone_wpml_required_plugins' ) ) {
	//add_filter('axiom_welldone_filter_required_plugins',	'axiom_welldone_wpml_required_plugins');
	function axiom_welldone_wpml_required_plugins($list=array()) {
		if (in_array('wpml', axiom_welldone_storage_get('required_plugins'))) {
			$path = axiom_welldone_get_file_dir('plugins/install/wpml.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPML', 'axiom-welldone'),
					'slug' 		=> 'wpml',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>