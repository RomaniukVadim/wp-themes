<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_br_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_br_theme_setup' );
	function axiom_welldone_sc_br_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('axiom_welldone_sc_br')) {	
	function axiom_welldone_sc_br($atts, $content = null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_br", "axiom_welldone_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_br_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_br_reg_shortcodes');
	function axiom_welldone_sc_br_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'axiom-welldone'),
						'left' => esc_html__('Left', 'axiom-welldone'),
						'right' => esc_html__('Right', 'axiom-welldone'),
						'both' => esc_html__('Both', 'axiom-welldone')
					)
				)
			)
		));
	}
}
?>