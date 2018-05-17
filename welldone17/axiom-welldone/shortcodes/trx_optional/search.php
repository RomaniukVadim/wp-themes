<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_search_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_search_theme_setup' );
	function axiom_welldone_sc_search_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_search_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('axiom_welldone_sc_search')) {	
	function axiom_welldone_sc_search($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'axiom-welldone'),
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
		if (empty($ajax)) $ajax = axiom_welldone_get_theme_option('use_ajax_search');
		// Load core messages
		axiom_welldone_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (axiom_welldone_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'axiom-welldone') : esc_attr__('Start search', 'axiom-welldone')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !axiom_welldone_param_is_off($scheme) && !axiom_welldone_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_search', 'axiom_welldone_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_search_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_search_reg_shortcodes');
	function axiom_welldone_sc_search_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Show search form", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select style to display search field", 'axiom-welldone') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'axiom-welldone'),
						"rounded" => esc_html__('Rounded', 'axiom-welldone')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select search field initial state", 'axiom-welldone') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'axiom-welldone'),
						"opened" => esc_html__('Opened', 'axiom-welldone'),
						"closed" => esc_html__('Closed', 'axiom-welldone')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'axiom-welldone') ),
					"value" => esc_html__("Search &hellip;", 'axiom-welldone'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'axiom-welldone') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_search_reg_shortcodes_vc');
	function axiom_welldone_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert search form", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select style to display search field", 'axiom-welldone') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'axiom-welldone') => "regular",
						esc_html__('Flat', 'axiom-welldone') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select search field initial state", 'axiom-welldone') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'axiom-welldone')  => "fixed",
						esc_html__('Opened', 'axiom-welldone') => "opened",
						esc_html__('Closed', 'axiom-welldone') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'axiom-welldone'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'axiom-welldone'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'axiom-welldone') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'axiom-welldone') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>