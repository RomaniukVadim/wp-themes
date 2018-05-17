<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_accordion_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_accordion_theme_setup' );
	function axiom_welldone_sc_accordion_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_accordion_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_accordion_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_accordion counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('axiom_welldone_sc_accordion')) {	
	function axiom_welldone_sc_accordion($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-cancel",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$initial = max(0, (int) $initial);
		axiom_welldone_storage_set('sc_accordion_data', array(
			'counter' => 0,
            'show_counter' => axiom_welldone_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || axiom_welldone_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || axiom_welldone_param_is_inherit($icon_opened) ? "icon-cancel" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (axiom_welldone_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_accordion', 'axiom_welldone_sc_accordion');
}


if (!function_exists('axiom_welldone_sc_accordion_item')) {	
	function axiom_welldone_sc_accordion_item($atts, $content=null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		axiom_welldone_storage_inc_array('sc_accordion_data', 'counter');
		if (empty($icon_closed) || axiom_welldone_param_is_inherit($icon_closed)) $icon_closed = axiom_welldone_storage_get_array('sc_accordion_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || axiom_welldone_param_is_inherit($icon_opened)) $icon_opened = axiom_welldone_storage_get_array('sc_accordion_data', 'icon_opened', '', "icon-cancel");
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. (axiom_welldone_storage_get_array('sc_accordion_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
				. (axiom_welldone_storage_get_array('sc_accordion_data', 'counter') == 1 ? ' first' : '') 
				. '">'
				. '<h6 class="sc_accordion_title">'
				. (!axiom_welldone_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!axiom_welldone_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. (axiom_welldone_storage_get_array('sc_accordion_data', 'show_counter') ? '<span class="sc_items_counter">'.(axiom_welldone_storage_get_array('sc_accordion_data', 'counter')).'</span>' : '')
				. ($title)
				. '</h6>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_accordion_item', 'axiom_welldone_sc_accordion_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_accordion_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_accordion_reg_shortcodes');
	function axiom_welldone_sc_accordion_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_accordion", array(
			"title" => esc_html__("Accordion", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Accordion items", 'axiom-welldone') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Display counter before each accordion title", 'axiom-welldone') ),
					"value" => "off",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('on_off')
				),
				"initial" => array(
					"title" => esc_html__("Initially opened item", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Number of initially opened item", 'axiom-welldone') ),
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the closed accordion item from Fontello icons set',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the opened accordion item from Fontello icons set',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
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
				"name" => "trx_accordion_item",
				"title" => esc_html__("Item", 'axiom-welldone'),
				"desc" => wp_kses_data( __("Accordion item", 'axiom-welldone') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Accordion item title", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Title for current accordion item", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'axiom-welldone'),
						"desc" => wp_kses_data( __('Select icon for the closed accordion item from Fontello icons set',  'axiom-welldone') ),
						"value" => "",
						"type" => "icons",
						"options" => axiom_welldone_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'axiom-welldone'),
						"desc" => wp_kses_data( __('Select icon for the opened accordion item from Fontello icons set',  'axiom-welldone') ),
						"value" => "",
						"type" => "icons",
						"options" => axiom_welldone_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Accordion item content", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current accordion item content", 'axiom-welldone') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => axiom_welldone_get_sc_param('id'),
					"class" => axiom_welldone_get_sc_param('class'),
					"css" => axiom_welldone_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_accordion_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_accordion_reg_shortcodes_vc');
	function axiom_welldone_sc_accordion_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_accordion",
			"name" => esc_html__("Accordion", 'axiom-welldone'),
			"description" => wp_kses_data( __("Accordion items", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_accordion',
			"class" => "trx_sc_collection trx_sc_accordion",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'axiom-welldone'),
					"description" => wp_kses_data( __("Display counter before each accordion title", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened item", 'axiom-welldone'),
					"description" => wp_kses_data( __("Number of initially opened item", 'axiom-welldone') ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the closed accordion item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the opened accordion item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
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
			'default_content' => '
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'axiom-welldone' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'axiom-welldone' ) . '"][/trx_accordion_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'axiom-welldone').'">'.esc_html__("Add item", 'axiom-welldone').'</button>
				</div>
			',
			'js_view' => 'VcTrxAccordionView'
		) );
		
		
		vc_map( array(
			"base" => "trx_accordion_item",
			"name" => esc_html__("Accordion item", 'axiom-welldone'),
			"description" => wp_kses_data( __("Inner accordion item", 'axiom-welldone') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_accordion_item',
			"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_accordion'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title for current accordion item", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the closed accordion item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the opened accordion item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('css')
			),
		  'js_view' => 'VcTrxAccordionTabView'
		) );

		class WPBakeryShortCode_Trx_Accordion extends AXIOM_WELLDONE_VC_ShortCodeAccordion {}
		class WPBakeryShortCode_Trx_Accordion_Item extends AXIOM_WELLDONE_VC_ShortCodeAccordionItem {}
	}
}
?>