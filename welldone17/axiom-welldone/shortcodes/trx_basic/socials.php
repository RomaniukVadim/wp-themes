<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_socials_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_socials_theme_setup' );
	function axiom_welldone_sc_socials_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_socials_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('axiom_welldone_sc_socials')) {	
	function axiom_welldone_sc_socials($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => axiom_welldone_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		axiom_welldone_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? axiom_welldone_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) axiom_welldone_storage_set_array('sc_social_data', 'icons', $list);
		} else if (axiom_welldone_param_is_on($custom))
			$content = do_shortcode($content);
		if (axiom_welldone_storage_get_array('sc_social_data', 'icons')===false) axiom_welldone_storage_set_array('sc_social_data', 'icons', axiom_welldone_get_custom_option('social_icons'));
		$output = axiom_welldone_prepare_socials(axiom_welldone_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_socials', 'axiom_welldone_sc_socials');
}


if (!function_exists('axiom_welldone_sc_social_item')) {	
	function axiom_welldone_sc_social_item($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (empty($icon)) {
			if (!empty($name)) {
			$type = axiom_welldone_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(axiom_welldone_get_socials_dir($name.'.png')))
					$icon = axiom_welldone_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		} else if ((int) $icon > 0) {
			$attach = wp_get_attachment_image_src( $icon, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$icon = $attach[0];
		}
		if (!empty($icon) && !empty($url)) {
			if (axiom_welldone_storage_get_array('sc_social_data', 'icons')===false) axiom_welldone_storage_set_array('sc_social_data', 'icons', array());
			axiom_welldone_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	axiom_welldone_require_shortcode('trx_social_item', 'axiom_welldone_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_socials_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_socials_reg_shortcodes');
	function axiom_welldone_sc_socials_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'axiom-welldone'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'axiom-welldone') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'axiom-welldone') ),
					"value" => axiom_welldone_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'axiom-welldone'),
						'images' => esc_html__('Images', 'axiom-welldone')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Size of the icons", 'axiom-welldone') ),
					"value" => "small",
					"options" => axiom_welldone_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Shape of the icons", 'axiom-welldone') ),
					"value" => "square",
					"options" => axiom_welldone_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'axiom-welldone') ),
					"divider" => true,
					"value" => "no",
					"options" => axiom_welldone_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'axiom-welldone'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'axiom-welldone') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'axiom-welldone'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'axiom-welldone') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_socials_reg_shortcodes_vc');
	function axiom_welldone_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'axiom-welldone'),
			"description" => wp_kses_data( __("Custom social icons", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'axiom-welldone'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'axiom-welldone') ),
					"class" => "",
					"std" => axiom_welldone_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'axiom-welldone') => 'icons',
						esc_html__('Images', 'axiom-welldone') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'axiom-welldone'),
					"description" => wp_kses_data( __("Size of the icons", 'axiom-welldone') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(axiom_welldone_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'axiom-welldone'),
					"description" => wp_kses_data( __("Shape of the icons", 'axiom-welldone') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(axiom_welldone_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'axiom-welldone'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'axiom-welldone'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'axiom-welldone') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('animation'),
				axiom_welldone_get_vc_param('css'),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'axiom-welldone'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'axiom-welldone') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'axiom-welldone'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends AXIOM_WELLDONE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>