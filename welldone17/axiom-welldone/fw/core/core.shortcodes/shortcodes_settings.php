<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'axiom_welldone_shortcodes_is_used' ) ) {
	function axiom_welldone_shortcodes_is_used() {
		return axiom_welldone_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page']=='vc-roles')			// VC Role Manager
			|| (function_exists('axiom_welldone_vc_is_frontend') && axiom_welldone_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'axiom_welldone_shortcodes_width' ) ) {
	function axiom_welldone_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'axiom-welldone'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'axiom_welldone_shortcodes_height' ) ) {
	function axiom_welldone_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Width and height of the element", 'axiom-welldone') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'axiom_welldone_get_sc_param' ) ) {
	function axiom_welldone_get_sc_param($prm) {
		return axiom_welldone_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'axiom_welldone_set_sc_param' ) ) {
	function axiom_welldone_set_sc_param($prm, $val) {
		axiom_welldone_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'axiom_welldone_sc_map' ) ) {
	function axiom_welldone_sc_map($sc_name, $sc_settings) {
		axiom_welldone_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'axiom_welldone_sc_map_after' ) ) {
	function axiom_welldone_sc_map_after($after, $sc_name, $sc_settings='') {
		axiom_welldone_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'axiom_welldone_sc_map_before' ) ) {
	function axiom_welldone_sc_map_before($before, $sc_name, $sc_settings='') {
		axiom_welldone_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'axiom_welldone_compare_sc_title' ) ) {
	function axiom_welldone_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_welldone_shortcodes_settings_theme_setup' ) ) {
//	if ( axiom_welldone_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'axiom_welldone_action_after_init_theme', 'axiom_welldone_shortcodes_settings_theme_setup' );
	function axiom_welldone_shortcodes_settings_theme_setup() {
		if (axiom_welldone_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = axiom_welldone_storage_get('registered_templates');
			ksort($tmp);
			axiom_welldone_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			axiom_welldone_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'axiom-welldone'),
					"desc" => wp_kses_data( __("ID for current element", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'axiom-welldone'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'axiom-welldone'),
					'ol'	=> esc_html__('Ordered', 'axiom-welldone'),
					'iconed'=> esc_html__('Iconed', 'axiom-welldone')
				),

				'yes_no'	=> axiom_welldone_get_list_yesno(),
				'on_off'	=> axiom_welldone_get_list_onoff(),
				'dir' 		=> axiom_welldone_get_list_directions(),
				'align'		=> axiom_welldone_get_list_alignments(),
				'float'		=> axiom_welldone_get_list_floats(),
				'hpos'		=> axiom_welldone_get_list_hpos(),
				'show_hide'	=> axiom_welldone_get_list_showhide(),
				'sorting' 	=> axiom_welldone_get_list_sortings(),
				'ordering' 	=> axiom_welldone_get_list_orderings(),
				'shapes'	=> axiom_welldone_get_list_shapes(),
				'sizes'		=> axiom_welldone_get_list_sizes(),
				'sliders'	=> axiom_welldone_get_list_sliders(),
				'controls'	=> axiom_welldone_get_list_controls(),

				// alternative case to refresh categories list:
				// hook add_action( 'wp_ajax_vc_edit_form', 'your func name' )
				// and return js-code with jQuery.post action 'axiom_welldone_admin_change_post_type'
				'categories'=> is_admin() && axiom_welldone_get_value_gp('action')=='vc_edit_form' && substr(axiom_welldone_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
								? axiom_welldone_get_list_terms(false, axiom_welldone_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
								: axiom_welldone_get_list_categories(),

				'columns'	=> axiom_welldone_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), axiom_welldone_get_list_images(AXIOM_WELLDONE_FW_DIR."/images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), axiom_welldone_get_list_icons()),
				'locations'	=> axiom_welldone_get_list_dedicated_locations(),
				'filters'	=> axiom_welldone_get_list_portfolio_filters(),
				'formats'	=> axiom_welldone_get_list_post_formats_filters(),
				'hovers'	=> axiom_welldone_get_list_hovers(true),
				'hovers_dir'=> axiom_welldone_get_list_hovers_directions(true),
				'schemes'	=> axiom_welldone_get_list_color_schemes(true),
				'animations'		=> axiom_welldone_get_list_animations_in(),
				'margins' 			=> axiom_welldone_get_list_margins(true),
				'blogger_styles'	=> axiom_welldone_get_list_templates_blogger(),
				'forms'				=> axiom_welldone_get_list_templates_forms(),
				'posts_types'		=> axiom_welldone_get_list_posts_types(),
				'googlemap_styles'	=> axiom_welldone_get_list_googlemap_styles(),
				'field_types'		=> axiom_welldone_get_list_field_types(),
				'label_positions'	=> axiom_welldone_get_list_label_positions()
				)
			);

			// Common params
			axiom_welldone_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'axiom-welldone'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'axiom-welldone') ),
				"value" => "none",
				"type" => "select",
				"options" => axiom_welldone_get_sc_param('animations')
				)
			);
			axiom_welldone_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'axiom-welldone'),
				"divider" => true,
				"value" => "",
				"type" => "text"
				)
			);
			axiom_welldone_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'axiom-welldone'),
				"value" => "",
				"type" => "text"
				)
			);
			axiom_welldone_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'axiom-welldone'),
				"value" => "",
				"type" => "text"
				)
			);
			axiom_welldone_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'axiom-welldone'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'axiom-welldone') ),
				"value" => "",
				"type" => "text"
				)
			);

			axiom_welldone_storage_set('sc_params', apply_filters('axiom_welldone_filter_shortcodes_params', axiom_welldone_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			axiom_welldone_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('axiom_welldone_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = axiom_welldone_storage_get('shortcodes');
			uasort($tmp, 'axiom_welldone_compare_sc_title');
			axiom_welldone_storage_set('shortcodes', $tmp);
		}
	}
}
?>