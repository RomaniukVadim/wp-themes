<?php
if (!function_exists('axiom_welldone_theme_shortcodes_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_theme_shortcodes_setup', 1 );
	function axiom_welldone_theme_shortcodes_setup() {
		add_filter('axiom_welldone_filter_googlemap_styles', 'axiom_welldone_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'axiom_welldone_theme_shortcodes_googlemap_styles' ) ) {
	function axiom_welldone_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'axiom-welldone');
		$list['greyscale']	= esc_html__('Greyscale', 'axiom-welldone');
		$list['inverse']	= esc_html__('Inverse', 'axiom-welldone');
		$list['dark']		= esc_html__('Dark', 'axiom-welldone');
		return $list;
	}
}
?>