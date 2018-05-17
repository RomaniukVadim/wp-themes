<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_icon_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_icon_theme_setup' );
	function axiom_welldone_sc_icon_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_icon_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('axiom_welldone_sc_icon')) {	
	function axiom_welldone_sc_icon($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !axiom_welldone_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(axiom_welldone_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || axiom_welldone_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !axiom_welldone_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_icon', 'axiom_welldone_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_icon_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_icon_reg_shortcodes');
	function axiom_welldone_sc_icon_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert icon", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Icon's color", 'axiom-welldone') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'axiom-welldone') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'axiom-welldone'),
						'round' => esc_html__('Round', 'axiom-welldone'),
						'square' => esc_html__('Square', 'axiom-welldone')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Icon's background color", 'axiom-welldone') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Icon's font size", 'axiom-welldone') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Icon font weight", 'axiom-welldone') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'axiom-welldone'),
						'300' => esc_html__('Light (300)', 'axiom-welldone'),
						'400' => esc_html__('Normal (400)', 'axiom-welldone'),
						'700' => esc_html__('Bold (700)', 'axiom-welldone')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Icon text alignment", 'axiom-welldone') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"top" => axiom_welldone_get_sc_param('top'),
				"bottom" => axiom_welldone_get_sc_param('bottom'),
				"left" => axiom_welldone_get_sc_param('left'),
				"right" => axiom_welldone_get_sc_param('right'),
				"id" => axiom_welldone_get_sc_param('id'),
				"class" => axiom_welldone_get_sc_param('class'),
				"css" => axiom_welldone_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_icon_reg_shortcodes_vc');
	function axiom_welldone_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert the icon", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Icon's color", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Background color for the icon", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'axiom-welldone'),
					"description" => wp_kses_data( __("Shape of the icon background", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'axiom-welldone') => 'none',
						esc_html__('Round', 'axiom-welldone') => 'round',
						esc_html__('Square', 'axiom-welldone') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'axiom-welldone'),
					"description" => wp_kses_data( __("Icon's font size", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'axiom-welldone'),
					"description" => wp_kses_data( __("Icon's font weight", 'axiom-welldone') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'axiom-welldone') => 'inherit',
						esc_html__('Thin (100)', 'axiom-welldone') => '100',
						esc_html__('Light (300)', 'axiom-welldone') => '300',
						esc_html__('Normal (400)', 'axiom-welldone') => '400',
						esc_html__('Bold (700)', 'axiom-welldone') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('css'),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>