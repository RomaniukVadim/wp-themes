<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_columns_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_columns_theme_setup' );
	function axiom_welldone_sc_columns_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_columns_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_columns_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_columns id="unique_id" count="number"]
	[trx_column_item id="unique_id" span="2 - number_columns"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_column_item]
	[trx_column_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_column_item]
	[trx_column_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_column_item]
	[trx_column_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_column_item]
[/trx_columns]
*/

if (!function_exists('axiom_welldone_sc_columns')) {	
	function axiom_welldone_sc_columns($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			"margins" => "yes",
			"equal_height" => "no",
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
		$css .= axiom_welldone_get_css_dimensions_from_values($width, $height);
		$count = max(1, min(12, (int) $count));
		axiom_welldone_storage_set('sc_columns_data', array(
			'counter' => 1,
            'after_span2' => false,
            'after_span3' => false,
            'after_span4' => false,
            'count' => $count
            )
        );
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (axiom_welldone_param_is_on($fluid) ? 'fluid' : 'nofluid') 
					. (!empty($margins) && axiom_welldone_param_is_off($margins) ? ' no_margins' : '') 
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. (axiom_welldone_param_is_on($equal_height) ? ' equal_height' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. '>'
					. trim($content)
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_columns', 'axiom_welldone_sc_columns');
}


if (!function_exists('axiom_welldone_sc_column_item')) {	
	function axiom_welldone_sc_column_item($atts, $content=null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_tile" => "no",
			"bg_position" => "no",
			"border" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr(axiom_welldone_storage_get_array('sc_columns_data', 'count')).' sc_column_item sc_column_item_'.esc_attr(axiom_welldone_storage_get_array('sc_columns_data', 'counter')) 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (axiom_welldone_storage_get_array('sc_columns_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (axiom_welldone_storage_get_array('sc_columns_data', 'counter') == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. (axiom_welldone_storage_get_array('sc_columns_data', 'after_span2') ? ' after_span_2' : '') 
					. (axiom_welldone_storage_get_array('sc_columns_data', 'after_span3') ? ' after_span_3' : '') 
					. (axiom_welldone_storage_get_array('sc_columns_data', 'after_span4') ? ' after_span_4' : '') 
					. (axiom_welldone_param_is_on($border) ? ' sc_border' : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner '.(!empty($bg_position) ? 'bg_'.strtolower($bg_position) : '').'" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(axiom_welldone_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
							. '">' : '')
					. ($bg_color == '' && $bg_image == '' && axiom_welldone_param_is_on($border) ? '<div class="sc_column_item_inner">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. ($bg_color == '' && $bg_image == '' && axiom_welldone_param_is_on($border) ? '</div>' : '')
					. '</div>';
		axiom_welldone_storage_inc_array('sc_columns_data', 'counter', $span);
		axiom_welldone_storage_set_array('sc_columns_data', 'after_span2', $span==2);
		axiom_welldone_storage_set_array('sc_columns_data', 'after_span3', $span==3);
		axiom_welldone_storage_set_array('sc_columns_data', 'after_span4', $span==4);
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_column_item', 'axiom_welldone_sc_column_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_columns_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_columns_reg_shortcodes');
	function axiom_welldone_sc_columns_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_columns", array(
			"title" => esc_html__("Columns", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert up to 5 columns in your page (post)", 'axiom-welldone') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"equal_height" => array(
					"title" => esc_html__("Columns height", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Columns will have equal height", 'axiom-welldone') ),
					"value" => "no",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				), 
				"fluid" => array(
					"title" => esc_html__("Fluid columns", 'axiom-welldone'),
					"desc" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'axiom-welldone') ),
					"value" => "no",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				), 
				"margins" => array(
					"title" => esc_html__("Margins between columns", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Add margins between columns", 'axiom-welldone') ),
					"value" => "yes",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
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
			),
			"children" => array(
				"name" => "trx_column_item",
				"title" => esc_html__("Column", 'axiom-welldone'),
				"desc" => wp_kses_data( __("Column item", 'axiom-welldone') ),
				"container" => true,
				"params" => array(
					"span" => array(
						"title" => esc_html__("Merge columns", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Count merged columns from current", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"align" => array(
						"title" => esc_html__("Alignment", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Alignment text in the column", 'axiom-welldone') ),
						"value" => "",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => axiom_welldone_get_sc_param('align')
					),
					"border" => array(
						"title" => esc_html__("Border", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Column will have border", 'axiom-welldone') ),
						"value" => "no",
						"type" => "switch",
						"options" => axiom_welldone_get_sc_param('yes_no')
					), 
					"color" => array(
						"title" => esc_html__("Fore color", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Any color for objects in this column", 'axiom-welldone') ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Any background color for this column", 'axiom-welldone') ),
						"value" => "",
						"type" => "color"
					),
					"bg_image" => array(
						"title" => esc_html__("URL for background image file", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'axiom-welldone') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"bg_tile" => array(
						"title" => esc_html__("Tile background image", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Do you want tile background image or image cover whole column?", 'axiom-welldone') ),
						"value" => "no",
						"dependency" => array(
							'bg_image' => array('not_empty')
						),
						"type" => "switch",
						"options" => axiom_welldone_get_sc_param('yes_no')
					),
					"bg_position" => array(
						"title" => esc_html__("Background image position", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Choose background image position", 'axiom-welldone') ),
						"value" => "",
						"dependency" => array(
							'bg_image' => array('not_empty')
						),
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => axiom_welldone_get_sc_param('align')
					),
					"_content_" => array(
						"title" => esc_html__("Column item content", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current column item content", 'axiom-welldone') ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => axiom_welldone_get_sc_param('id'),
					"class" => axiom_welldone_get_sc_param('class'),
					"animation" => axiom_welldone_get_sc_param('animation'),
					"css" => axiom_welldone_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_columns_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_columns_reg_shortcodes_vc');
	function axiom_welldone_sc_columns_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_columns",
			"name" => esc_html__("Columns", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert columns with margins", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_columns',
			"class" => "trx_sc_columns",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_column_item'),
			"params" => array(
				array(
					"param_name" => "count",
					"heading" => esc_html__("Columns count", 'axiom-welldone'),
					"description" => wp_kses_data( __("Number of the columns in the container.", 'axiom-welldone') ),
					"admin_label" => true,
					"value" => "2",
					"type" => "textfield"
				),
				array(
					"param_name" => "equal_height",
					"heading" => esc_html__("Columns height", 'axiom-welldone'),
					"description" => wp_kses_data( __("Columns will have equal height", 'axiom-welldone') ),
					"value" => array(esc_html__('Equal height', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "fluid",
					"heading" => esc_html__("Fluid columns", 'axiom-welldone'),
					"description" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'axiom-welldone') ),
					"value" => array(esc_html__('Fluid columns', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "margins",
					"heading" => esc_html__("Margins between columns", 'axiom-welldone'),
					"description" => wp_kses_data( __("Add margins between columns", 'axiom-welldone') ),
					"std" => "yes",
					"value" => array(esc_html__('Disable margins between columns', 'axiom-welldone') => 'no'),
					"type" => "checkbox"
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
			'default_content' => '
				[trx_column_item][/trx_column_item]
				[trx_column_item][/trx_column_item]
			',
			'js_view' => 'VcTrxColumnsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_column_item",
			"name" => esc_html__("Column", 'axiom-welldone'),
			"description" => wp_kses_data( __("Column item", 'axiom-welldone') ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_column_item',
			"as_child" => array('only' => 'trx_columns'),
			"as_parent" => array('except' => 'trx_columns'),
			"params" => array(
				array(
					"param_name" => "span",
					"heading" => esc_html__("Merge columns", 'axiom-welldone'),
					"description" => wp_kses_data( __("Count merged columns from current", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Alignment text in the column", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "border",
					"heading" => esc_html__("Border", 'axiom-welldone'),
					"description" => wp_kses_data( __("Column will have border", 'axiom-welldone') ),
					"value" => array(esc_html__('Yes', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any color for objects in this column", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any background color for this column", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("URL for background image file", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'axiom-welldone'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole column?", 'axiom-welldone') ),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'axiom-welldone') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_position",
					"heading" => esc_html__("Background image position", 'axiom-welldone'),
					"description" => wp_kses_data( __("Choose background image position", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"value" => axiom_welldone_get_sc_param('align'),
					"type" => "dropdown"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('animation'),
				axiom_welldone_get_vc_param('css')
			),
			'js_view' => 'VcTrxColumnItemView'
		) );
		
		class WPBakeryShortCode_Trx_Columns extends AXIOM_WELLDONE_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Column_Item extends AXIOM_WELLDONE_VC_ShortCodeCollection {}
	}
}
?>