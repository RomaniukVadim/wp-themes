<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_infobox_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_infobox_theme_setup' );
	function axiom_welldone_sc_infobox_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_infobox_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('axiom_welldone_sc_infobox')) {	
	function axiom_welldone_sc_infobox($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-ok';
			else if ($style=='error')
				$icon = 'icon-cancel-1';
			else if ($style=='info')
				$icon = 'icon-info-1';
			else if ($style=='warning')
				$icon = 'icon-attention-alt';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (axiom_welldone_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !axiom_welldone_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_infobox', 'axiom_welldone_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_infobox_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_infobox_reg_shortcodes');
	function axiom_welldone_sc_infobox_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'axiom-welldone') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Infobox style", 'axiom-welldone') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'axiom-welldone'),
						'info' => esc_html__('Info', 'axiom-welldone'),
						'success' => esc_html__('Success', 'axiom-welldone'),
						'warning' => esc_html__('Warning', 'axiom-welldone'),
						'error' => esc_html__('Error', 'axiom-welldone')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'axiom-welldone') ),
					"value" => "no",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'axiom-welldone') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'axiom-welldone') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Content for infobox", 'axiom-welldone') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
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
if ( !function_exists( 'axiom_welldone_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_infobox_reg_shortcodes_vc');
	function axiom_welldone_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'axiom-welldone'),
			"description" => wp_kses_data( __("Box with info or error message", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Infobox style", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'axiom-welldone') => 'regular',
							esc_html__('Info', 'axiom-welldone') => 'info',
							esc_html__('Success', 'axiom-welldone') => 'success',
							esc_html__('Error', 'axiom-welldone') => 'error',
							esc_html__('Warning', 'axiom-welldone') => 'warning'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'axiom-welldone'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'axiom-welldone') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('animation'),
				axiom_welldone_get_vc_param('css'),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends AXIOM_WELLDONE_VC_ShortCodeContainer {}
	}
}
?>