<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_sidebar_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_sidebar_theme_setup' );
	function axiom_welldone_sc_sidebar_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_sidebar_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_sidebar_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('axiom_welldone_sc_sidebar')) {	
	function axiom_welldone_sc_sidebar($atts, $content = null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"name" => ""
		), $atts)));
		
		$sidebar_name = $name;
		$sidebar = '';
			if (is_active_sidebar($sidebar_name)) { 
				
				$sidebar = '<div class="sc_sidebar widget_area">
							<div class="widget_area_inner">
								<div class="content_wrap">
									<div class="columns_wrap">';
									ob_start();
									do_action( 'before_sidebar' );
									if ( !dynamic_sidebar($sidebar_name) ) {
										// Put here html if user no set widgets in sidebar
									}
									do_action( 'after_sidebar' );
									$out = ob_get_contents();
									ob_end_clean();
									preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out);
									$sidebar .= trim(chop(preg_replace('/(widget )/', 'widget column-1_4 ', $out)))
									.'</div>
								</div>
							</div>
						</div>';
			
		}
		return apply_filters('axiom_welldone_shortcode_output', $sidebar, 'trx_sidebar', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_sidebar", "axiom_welldone_sc_sidebar");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_sidebar_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_sidebar_reg_shortcodes');
	function axiom_welldone_sc_sidebar_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_sidebar", array(
			"title" => esc_html__("Sidebar", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert sidebar", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"name" => array(
					"title" => esc_html__("Name", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Sidebar name or id", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_sidebar_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_sidebar_reg_shortcodes_vc');
	function axiom_welldone_sc_sidebar_reg_shortcodes_vc() {
	
		$sidebars = axiom_welldone_get_list_sidebars();
		
		vc_map( array(
			"base" => "trx_sidebar",
			"name" => esc_html__("Sidebar", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert sidebar", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_sidebar',
			"class" => "trx_sc_single trx_sc_sidebar",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Name", 'axiom-welldone'),
					"description" => wp_kses_data( __("Sidebar name or id", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($sidebars),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_trx_sidebar extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>