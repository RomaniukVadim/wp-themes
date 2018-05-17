<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_toggles_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_toggles_theme_setup' );
	function axiom_welldone_sc_toggles_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_toggles_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_toggles_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('axiom_welldone_sc_toggles')) {	
	function axiom_welldone_sc_toggles($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-cancel",
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
		axiom_welldone_storage_set('sc_toggle_data', array(
			'counter' => 0,
            'show_counter' => axiom_welldone_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || axiom_welldone_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || axiom_welldone_param_is_inherit($icon_opened) ? "icon-cancel" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (axiom_welldone_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_toggles', 'axiom_welldone_sc_toggles');
}


if (!function_exists('axiom_welldone_sc_toggles_item')) {	
	function axiom_welldone_sc_toggles_item($atts, $content=null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		axiom_welldone_storage_inc_array('sc_toggle_data', 'counter');
		if (empty($icon_closed) || axiom_welldone_param_is_inherit($icon_closed)) $icon_closed = axiom_welldone_storage_get_array('sc_toggles_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || axiom_welldone_param_is_inherit($icon_opened)) $icon_opened = axiom_welldone_storage_get_array('sc_toggles_data', 'icon_opened', '', "icon-cancel");
		$css .= axiom_welldone_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(axiom_welldone_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (axiom_welldone_storage_get_array('sc_toggle_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (axiom_welldone_storage_get_array('sc_toggle_data', 'counter') == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(axiom_welldone_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!axiom_welldone_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!axiom_welldone_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. (axiom_welldone_storage_get_array('sc_toggle_data', 'show_counter') ? '<span class="sc_items_counter">'.(axiom_welldone_storage_get_array('sc_toggle_data', 'counter')).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_toggles_item', 'axiom_welldone_sc_toggles_item');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_toggles_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_toggles_reg_shortcodes');
	function axiom_welldone_sc_toggles_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_toggles", array(
			"title" => esc_html__("Toggles", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Toggles items", 'axiom-welldone') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Display counter before each toggles title", 'axiom-welldone') ),
					"value" => "off",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('on_off')
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the closed toggles item from Fontello icons set',  'axiom-welldone') ),
					"value" => "",
					"type" => "icons",
					"options" => axiom_welldone_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for the opened toggles item from Fontello icons set',  'axiom-welldone') ),
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
				"name" => "trx_toggles_item",
				"title" => esc_html__("Toggles item", 'axiom-welldone'),
				"desc" => wp_kses_data( __("Toggles item", 'axiom-welldone') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Toggles item title", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Title for current toggles item", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"open" => array(
						"title" => esc_html__("Open on show", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Open current toggles item on show", 'axiom-welldone') ),
						"value" => "no",
						"type" => "switch",
						"options" => axiom_welldone_get_sc_param('yes_no')
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'axiom-welldone'),
						"desc" => wp_kses_data( __('Select icon for the closed toggles item from Fontello icons set',  'axiom-welldone') ),
						"value" => "",
						"type" => "icons",
						"options" => axiom_welldone_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'axiom-welldone'),
						"desc" => wp_kses_data( __('Select icon for the opened toggles item from Fontello icons set',  'axiom-welldone') ),
						"value" => "",
						"type" => "icons",
						"options" => axiom_welldone_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Toggles item content", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current toggles item content", 'axiom-welldone') ),
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
if ( !function_exists( 'axiom_welldone_sc_toggles_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_toggles_reg_shortcodes_vc');
	function axiom_welldone_sc_toggles_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_toggles",
			"name" => esc_html__("Toggles", 'axiom-welldone'),
			"description" => wp_kses_data( __("Toggles items", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_toggles',
			"class" => "trx_sc_collection trx_sc_toggles",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_toggles_item'),
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'axiom-welldone'),
					"description" => wp_kses_data( __("Display counter before each toggles title", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the closed toggles item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the opened toggles item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'axiom-welldone' ) . '"][/trx_toggles_item]
				[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'axiom-welldone' ) . '"][/trx_toggles_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'axiom-welldone').'">'.esc_html__("Add item", 'axiom-welldone').'</button>
				</div>
			',
			'js_view' => 'VcTrxTogglesView'
		) );
		
		
		vc_map( array(
			"base" => "trx_toggles_item",
			"name" => esc_html__("Toggles item", 'axiom-welldone'),
			"description" => wp_kses_data( __("Single toggles item", 'axiom-welldone') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_toggles_item',
			"as_child" => array('only' => 'trx_toggles'),
			"as_parent" => array('except' => 'trx_toggles'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title for current toggles item", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Open on show", 'axiom-welldone'),
					"description" => wp_kses_data( __("Open current toggle item on show", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Opened" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the closed toggles item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon for the opened toggles item from Fontello icons set", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('css')
			),
			'js_view' => 'VcTrxTogglesTabView'
		) );
		class WPBakeryShortCode_Trx_Toggles extends AXIOM_WELLDONE_VC_ShortCodeToggles {}
		class WPBakeryShortCode_Trx_Toggles_Item extends AXIOM_WELLDONE_VC_ShortCodeTogglesItem {}
	}
}
?>