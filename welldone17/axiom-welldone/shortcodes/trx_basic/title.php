<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_title_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_title_theme_setup' );
	function axiom_welldone_sc_title_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_title_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('axiom_welldone_sc_title')) {	
	function axiom_welldone_sc_title($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			"uppercase" => "yes",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= axiom_welldone_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !axiom_welldone_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !axiom_welldone_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(axiom_welldone_strpos($image, 'http')===0 ? $image : axiom_welldone_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !axiom_welldone_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.($uppercase == 'yes' ? ' text_uppercase' : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($position != 'right' ? $pic : '')
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. ($position == 'right' ? $pic : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_title', 'axiom_welldone_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_title_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_title_reg_shortcodes');
	function axiom_welldone_sc_title_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'axiom-welldone') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title content", 'axiom-welldone') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title type (header level)", 'axiom-welldone') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'axiom-welldone'),
						'2' => esc_html__('Header 2', 'axiom-welldone'),
						'3' => esc_html__('Header 3', 'axiom-welldone'),
						'4' => esc_html__('Header 4', 'axiom-welldone'),
						'5' => esc_html__('Header 5', 'axiom-welldone'),
						'6' => esc_html__('Header 6', 'axiom-welldone'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title style", 'axiom-welldone') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'axiom-welldone'),
						'underline' => esc_html__('Underline', 'axiom-welldone'),
						'divider' => esc_html__('Divider', 'axiom-welldone'),
						'iconed' => esc_html__('With icon (image)', 'axiom-welldone')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title text alignment", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'axiom-welldone') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'axiom-welldone'),
						'100' => esc_html__('Thin (100)', 'axiom-welldone'),
						'300' => esc_html__('Light (300)', 'axiom-welldone'),
						'400' => esc_html__('Normal (400)', 'axiom-welldone'),
						'600' => esc_html__('Semibold (600)', 'axiom-welldone'),
						'700' => esc_html__('Bold (700)', 'axiom-welldone'),
						'900' => esc_html__('Black (900)', 'axiom-welldone')
					)
				),
				"uppercase" => array(
					"title" => esc_html__("Uppercase", "axiom-welldone"),
					"desc" => wp_kses_data( __("Transform text in uppercase", "axiom-welldone") ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				),
				"color" => array(
					"title" => esc_html__("Title color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select color for the title", 'axiom-welldone') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'axiom-welldone') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'axiom-welldone') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => axiom_welldone_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'axiom-welldone'),
						'medium' => esc_html__('Medium', 'axiom-welldone'),
						'large' => esc_html__('Large', 'axiom-welldone')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'axiom-welldone') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'axiom-welldone'),
						'left' => esc_html__('Left', 'axiom-welldone'),
						'right' => esc_html__('Right', 'axiom-welldone')
					)
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
if ( !function_exists( 'axiom_welldone_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_title_reg_shortcodes_vc');
	function axiom_welldone_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'axiom-welldone'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title content", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title type (header level)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'axiom-welldone') => '1',
						esc_html__('Header 2', 'axiom-welldone') => '2',
						esc_html__('Header 3', 'axiom-welldone') => '3',
						esc_html__('Header 4', 'axiom-welldone') => '4',
						esc_html__('Header 5', 'axiom-welldone') => '5',
						esc_html__('Header 6', 'axiom-welldone') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'axiom-welldone') => 'regular',
						esc_html__('Underline', 'axiom-welldone') => 'underline',
						esc_html__('Divider', 'axiom-welldone') => 'divider',
						esc_html__('With icon (image)', 'axiom-welldone') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title text alignment", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'axiom-welldone'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'axiom-welldone'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'axiom-welldone') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'axiom-welldone') => 'inherit',
						esc_html__('Thin (100)', 'axiom-welldone') => '100',
						esc_html__('Light (300)', 'axiom-welldone') => '300',
						esc_html__('Normal (400)', 'axiom-welldone') => '400',
						esc_html__('Semibold (600)', 'axiom-welldone') => '600',
						esc_html__('Bold (700)', 'axiom-welldone') => '700',
						esc_html__('Black (900)', 'axiom-welldone') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "uppercase",
					"heading" => esc_html__("Uppercase", "axiom-welldone"),
					"description" => wp_kses_data( __("Transform text in uppercase", "axiom-welldone") ),
					"class" => "",
					"std" => 'yes',
					"value" => array(esc_html__('Yes', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select color for the title", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'axiom-welldone') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'axiom-welldone') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'axiom-welldone'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => axiom_welldone_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'axiom-welldone') ),
					"group" => esc_html__('Icon &amp; Image', 'axiom-welldone'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'axiom-welldone') ),
					"group" => esc_html__('Icon &amp; Image', 'axiom-welldone'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'axiom-welldone') => 'small',
						esc_html__('Medium', 'axiom-welldone') => 'medium',
						esc_html__('Large', 'axiom-welldone') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'axiom-welldone') ),
					"group" => esc_html__('Icon &amp; Image', 'axiom-welldone'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'axiom-welldone') => 'top',
						esc_html__('Left', 'axiom-welldone') => 'left',
						esc_html__('Right', 'axiom-welldone') => 'right',
					),
					"type" => "dropdown"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>