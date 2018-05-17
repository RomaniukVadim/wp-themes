<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_anchor_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_anchor_theme_setup' );
	function axiom_welldone_sc_anchor_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_anchor_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('axiom_welldone_sc_anchor')) {	
	function axiom_welldone_sc_anchor($atts, $content = null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(axiom_welldone_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (axiom_welldone_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_anchor", "axiom_welldone_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_anchor_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_anchor_reg_shortcodes');
	function axiom_welldone_sc_anchor_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'axiom-welldone') ),
					"value" => "no",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				),
				"id" => axiom_welldone_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_anchor_reg_shortcodes_vc');
	function axiom_welldone_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'axiom-welldone'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'axiom-welldone'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				axiom_welldone_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>