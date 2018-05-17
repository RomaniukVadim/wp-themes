<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_button_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_button_theme_setup' );
	function axiom_welldone_sc_button_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_button_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('axiom_welldone_sc_button')) {	
	function axiom_welldone_sc_button($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "big",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= axiom_welldone_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (axiom_welldone_param_is_on($popup)) axiom_welldone_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (axiom_welldone_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '><div>'
			. '<span class="first">'. do_shortcode($content). '</span>'
			. '<span class="second">'. do_shortcode($content). '</span>'
			. '</div></a>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_button', 'axiom_welldone_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_button_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_button_reg_shortcodes');
	function axiom_welldone_sc_button_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Button with link", 'axiom-welldone') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Button caption", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select button's shape", 'axiom-welldone') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'axiom-welldone'),
						'round' => esc_html__('Round', 'axiom-welldone')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select button's style", 'axiom-welldone') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'axiom-welldone'),
						'border' => esc_html__('Border', 'axiom-welldone')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select button's size", 'axiom-welldone') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'big' => esc_html__('Big', 'axiom-welldone'),
						'small' => esc_html__('Small', 'axiom-welldone')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'axiom-welldone') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Any color for button's background", 'axiom-welldone') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'axiom-welldone') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("URL for link on button click", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Target for link on button click", 'axiom-welldone') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'axiom-welldone') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'axiom-welldone') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => axiom_welldone_shortcodes_width(),
				"height" => axiom_welldone_shortcodes_height(),
				"top" => axiom_welldone_get_sc_param('top'),
				"bottom" => axiom_welldone_get_sc_param('bottom'),
				"left" => axiom_welldone_get_sc_param('left'),
				"right" => axiom_welldone_get_sc_param('right'),
				"id" => axiom_welldone_get_sc_param('id'),
				"class" => axiom_welldone_get_sc_param('class'),
				"animation" => axiom_welldone_get_sc_param('animation'),
				"css" => axiom_welldone_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_button_reg_shortcodes_vc');
	function axiom_welldone_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'axiom-welldone'),
			"description" => wp_kses_data( __("Button with link", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'axiom-welldone'),
					"description" => wp_kses_data( __("Button caption", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select button's shape", 'axiom-welldone') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'axiom-welldone') => 'square',
						esc_html__('Round', 'axiom-welldone') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select button's style", 'axiom-welldone') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'axiom-welldone') => 'filled',
						esc_html__('Border', 'axiom-welldone') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select button's size", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Big', 'axiom-welldone') => 'big',
						esc_html__('Small', 'axiom-welldone') => 'small'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any color for button's caption", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any color for button's background", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("URL for the link on button click", 'axiom-welldone') ),
					"class" => "",
					"group" => esc_html__('Link', 'axiom-welldone'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'axiom-welldone'),
					"description" => wp_kses_data( __("Target for the link on button click", 'axiom-welldone') ),
					"class" => "",
					"group" => esc_html__('Link', 'axiom-welldone'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'axiom-welldone'),
					"description" => wp_kses_data( __("Open link target in popup window", 'axiom-welldone') ),
					"class" => "",
					"group" => esc_html__('Link', 'axiom-welldone'),
					"value" => array(esc_html__('Open in popup', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'axiom-welldone'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'axiom-welldone') ),
					"class" => "",
					"group" => esc_html__('Link', 'axiom-welldone'),
					"value" => "",
					"type" => "textfield"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('animation'),
				axiom_welldone_get_vc_param('css'),
				axiom_welldone_vc_width(),
				axiom_welldone_vc_height(),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>