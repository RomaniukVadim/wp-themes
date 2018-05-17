<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_promo_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_promo_theme_setup' );
	function axiom_welldone_sc_promo_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_promo_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_promo_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('axiom_welldone_sc_promo')) {	
	function axiom_welldone_sc_promo($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"align" => "none",
			"image" => "",
			"bg_color" => "",
			"icon" => "",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Read more', 'axiom-welldone'),
			"link2" => '',
			"link2_caption" => '',
			"url" => "",
			"position" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		
		$width  = axiom_welldone_prepare_css_value($width);
		$height = axiom_welldone_prepare_css_value($height);
		
		$css .= ($css ? ' ;' : '') . axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= axiom_welldone_get_css_dimensions_from_values($width,$height);
		$css .= ($image ? 'background: url('.$image.');' : '');
		$css .= ($bg_color ? 'background-color: '.$bg_color.';' : '');
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_promo_buttons sc_item_buttons">'
							. (!empty($link) 
								? '<div class="sc_promo_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" size="small"]'.esc_html($link_caption).'[/trx_button]').'</div>' 
								: '')
							. (!empty($link2) && $style==2 
								? '<div class="sc_promo_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" size="small"]'.esc_html($link2_caption).'[/trx_button]').'</div>' 
								: '')
							. '</div>'
						: '');
						
		$output = '<div '.(!empty($url) ? 'data-href="'.esc_url($url).'"' : '') 
					. ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_promo' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($position && $style==1 ? ' sc_promo_position_' . esc_attr($position) : '') 
						. ($scheme && !axiom_welldone_param_is_off($scheme) && !axiom_welldone_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
					. ($css ? 'style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_promo_inner '.($style ? ' sc_promo_style_' . esc_attr($style) : '').'">'
						. (!empty($icon) && $style==5 ? '<div class="sc_promo_icon '.esc_attr($icon).'"></div>' : '')
						. '<div class="sc_promo_content">'
							. (!empty($subtitle) && $style!=4 && $style!=5 ? '<h6 class="sc_promo_subtitle">' . trim(axiom_welldone_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_promo_title">' . trim(axiom_welldone_strmacros($title)) . '</h2>' : '')
							. (!empty($description) && $style!=1 ? '<div class="sc_promo_descr">' . trim(axiom_welldone_strmacros($description)) . '</div>' : '')
							. ($style==1 || $style==2 || $style==3 ? $buttons : '')
						. '</div>'
					. '</div>'
				.'</div>';
	
	
	
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_promo', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_promo', 'axiom_welldone_sc_promo');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_promo_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_promo_reg_shortcodes');
	function axiom_welldone_sc_promo_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_promo", array(
			"title" => esc_html__("Promo", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert promo diagramm in your page (post)", 'axiom-welldone') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select style to display block", 'axiom-welldone') ),
					"value" => "1",
					"type" => "checklist",
					"options" => axiom_welldone_get_list_styles(1, 5)
				),
				"align" => array(
					"title" => esc_html__("Alignment of the promo block", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('float')
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select the promo image from the library for this section", 'axiom-welldone') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select background color for the promo", 'axiom-welldone') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Promo icon',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Select icon from Fontello icons set",  'axiom-welldone') ),
					"dependency" => array(
						'style' => array(5)
					),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"position" => array(
					"title" => esc_html__('Content position', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select content position", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "top_left",
					"type" => "checklist",
					"options" => array(
						'top_left' => esc_html__('Top Left', 'axiom-welldone'),
						'top_right' => esc_html__('Top Right', 'axiom-welldone'),
						'bottom_right' => esc_html__('Bottom Right', 'axiom-welldone'),
						'bottom_left' => esc_html__('Bottom Left', 'axiom-welldone')
					)
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'axiom-welldone') ),
					"divider" => true,
					"dependency" => array(
						'style' => array(1,2,3)
					),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title for the block", 'axiom-welldone') ),
					"value" => "",
					"type" => "textarea"
				),
				"description" => array(
					"title" => esc_html__("Description", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Short description for the block", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array(2,3,4,5),
					),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array(1,2,3),
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array(1,2,3),
					),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array(2)
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array(2)
					),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("Link", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Link of the promo block", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"options" => axiom_welldone_get_sc_param('schemes')
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
if ( !function_exists( 'axiom_welldone_sc_promo_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_promo_reg_shortcodes_vc');
	function axiom_welldone_sc_promo_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_promo",
			"name" => esc_html__("Promo", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert promo block", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_promo',
			"class" => "trx_sc_single trx_sc_promo",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block's style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select style to display this block", 'axiom-welldone') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(axiom_welldone_get_list_styles(1, 5)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the promo block", 'axiom-welldone'),
					"description" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'axiom-welldone') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(axiom_welldone_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select the promo image from the library for this section", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select background color for the promo", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Promo icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select promo icon from Fontello icons set (if style=iconed)", 'axiom-welldone') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('5')
					),
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Content position", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select content position", 'axiom-welldone') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('Top Left', 'axiom-welldone') => 'top_left',
						esc_html__('Top Right', 'axiom-welldone') => 'top_right',
						esc_html__('Bottom Right', 'axiom-welldone') => 'bottom_right',
						esc_html__('Bottom Left', 'axiom-welldone') => 'bottom_left'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'axiom-welldone'),
					"description" => wp_kses_data( __("Subtitle for the block", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1','2','3')
					),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title for the block", 'axiom-welldone') ),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'axiom-welldone'),
					"description" => wp_kses_data( __("Description for the block", 'axiom-welldone') ),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3','4','5')
					),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'axiom-welldone') ),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1','2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'axiom-welldone'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'axiom-welldone') ),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1','2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'axiom-welldone') ),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'axiom-welldone'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'axiom-welldone') ),
					"group" => esc_html__('Captions', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Link", 'axiom-welldone'),
					"description" => wp_kses_data( __("Link of the promo block", 'axiom-welldone') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('schemes')),
					"type" => "dropdown"
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Promo extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>