<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_googlemap_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_googlemap_theme_setup' );
	function axiom_welldone_sc_googlemap_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_googlemap_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('axiom_welldone_sc_googlemap')) {	
	function axiom_welldone_sc_googlemap($atts, $content = null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= axiom_welldone_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = axiom_welldone_get_custom_option('googlemap_style');
		$api_key = axiom_welldone_get_theme_option('api_google');
		wp_enqueue_script( 'googlemap', axiom_welldone_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		wp_enqueue_script( 'themerex-googlemap-script', axiom_welldone_get_file_url('js/core.googlemap.js'), array(), null, true );
		axiom_welldone_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = axiom_welldone_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => axiom_welldone_get_custom_option('googlemap_title'),
				'description' => axiom_welldone_strmacros(axiom_welldone_get_custom_option('googlemap_description')),
				'latlng' => axiom_welldone_get_custom_option('googlemap_latlng'),
				'address' => axiom_welldone_get_custom_option('googlemap_address'),
				'point' => axiom_welldone_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !axiom_welldone_param_is_off($scheme) && !axiom_welldone_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(axiom_welldone_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_googlemap", "axiom_welldone_sc_googlemap");
}


if (!function_exists('axiom_welldone_sc_googlemap_marker')) {	
	function axiom_welldone_sc_googlemap_marker($atts, $content = null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		axiom_welldone_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : axiom_welldone_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	axiom_welldone_require_shortcode("trx_googlemap_marker", "axiom_welldone_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_googlemap_reg_shortcodes');
	function axiom_welldone_sc_googlemap_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'axiom-welldone') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Map zoom factor", 'axiom-welldone') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select map style", 'axiom-welldone') ),
					"value" => "default",
					"type" => "checklist",
					"options" => axiom_welldone_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"options" => axiom_welldone_get_sc_param('schemes')
				),
				"width" => axiom_welldone_shortcodes_width('100%'),
				"height" => axiom_welldone_shortcodes_height(240),
				"top" => axiom_welldone_get_sc_param('top'),
				"bottom" => axiom_welldone_get_sc_param('bottom'),
				"left" => axiom_welldone_get_sc_param('left'),
				"right" => axiom_welldone_get_sc_param('right'),
				"id" => axiom_welldone_get_sc_param('id'),
				"class" => axiom_welldone_get_sc_param('class'),
				"animation" => axiom_welldone_get_sc_param('animation'),
				"css" => axiom_welldone_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'axiom-welldone'),
				"desc" => wp_kses_data( __("Google map marker", 'axiom-welldone') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Address of this marker", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'axiom-welldone') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Title for this marker", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Description for this marker", 'axiom-welldone') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => axiom_welldone_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_googlemap_reg_shortcodes_vc');
	function axiom_welldone_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'axiom-welldone'),
					"description" => wp_kses_data( __("Map zoom factor", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Map custom style", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('animation'),
				axiom_welldone_get_vc_param('css'),
				axiom_welldone_vc_width('100%'),
				axiom_welldone_vc_height(240),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'axiom-welldone') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'axiom-welldone'),
					"description" => wp_kses_data( __("Address of this marker", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'axiom-welldone'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title for this marker", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				axiom_welldone_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends AXIOM_WELLDONE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends AXIOM_WELLDONE_VC_ShortCodeCollection {}
	}
}
?>