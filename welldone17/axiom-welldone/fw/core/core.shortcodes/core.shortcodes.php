<?php
/**
 * Axiom Welldone Framework: shortcodes manipulations
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiom_welldone_sc_theme_setup')) {
	add_action( 'axiom_welldone_action_init_theme', 'axiom_welldone_sc_theme_setup', 1 );
	function axiom_welldone_sc_theme_setup() {
		// Add sc stylesheets
		add_action('axiom_welldone_action_add_styles', 'axiom_welldone_sc_add_styles', 1);
	}
}

if (!function_exists('axiom_welldone_sc_theme_setup2')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_theme_setup2' );
	function axiom_welldone_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'axiom_welldone_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('axiom_welldone_sc_prepare_content')) axiom_welldone_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('axiom_welldone_shortcode_output', 'axiom_welldone_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'axiom_welldone_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'axiom_welldone_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'axiom_welldone_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'axiom_welldone_sc_add_styles' ) ) {
	//add_action('axiom_welldone_action_add_styles', 'axiom_welldone_sc_add_styles', 1);
	function axiom_welldone_sc_add_styles() {
		// Shortcodes
		wp_enqueue_style( 'axiom_welldone-shortcodes-style',	axiom_welldone_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'axiom_welldone_sc_add_scripts' ) ) {
	//add_filter('axiom_welldone_shortcode_output', 'axiom_welldone_sc_add_scripts', 10, 4);
	function axiom_welldone_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (axiom_welldone_storage_empty('shortcodes_scripts_added')) {
			axiom_welldone_storage_set('shortcodes_scripts_added', true);
			wp_enqueue_script( 'axiom_welldone-shortcodes-script', axiom_welldone_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('axiom_welldone_sc_prepare_content')) {
	function axiom_welldone_sc_prepare_content() {
		if (function_exists('axiom_welldone_sc_clear_around')) {
			$filters = array(
				array('axiom-welldone', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('axiom_welldone_exists_woocommerce') && axiom_welldone_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'axiom_welldone_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('axiom_welldone_sc_excerpt_shortcodes')) {
	//add_filter('the_excerpt', 'axiom_welldone_sc_excerpt_shortcodes');
	function axiom_welldone_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('axiom_welldone_sc_clear_around')) {
	function axiom_welldone_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Axiom Welldone shortcodes load scripts
if (!function_exists('axiom_welldone_sc_load_scripts')) {
	function axiom_welldone_sc_load_scripts() {
		static $loaded = false;
		if (!$loaded) {
			wp_enqueue_script( 'axiom_welldone-shortcodes_admin-script', axiom_welldone_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
			wp_enqueue_script( 'axiom_welldone-selection-script',  axiom_welldone_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
			wp_localize_script( 'axiom_welldone-shortcodes_admin-script', 'AXIOM_WELLDONE_SHORTCODES_DATA', axiom_welldone_storage_get('shortcodes') );
			$loaded = true;
		}
	}
}

// Axiom Welldone shortcodes prepare scripts
if (!function_exists('axiom_welldone_sc_prepare_scripts')) {
	function axiom_welldone_sc_prepare_scripts() {
		static $prepared = false;
		if (!$prepared) {
			axiom_welldone_storage_set_array('js_vars', 'shortcodes_cp', is_admin() ? (!axiom_welldone_storage_empty('to_colorpicker') ? axiom_welldone_storage_get('to_colorpicker') : 'wp') : 'custom');	// wp | tiny | custom
			$prepared = true;
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('axiom_welldone_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','axiom_welldone_sc_selector_add_in_toolbar', 11);
	function axiom_welldone_sc_selector_add_in_toolbar(){

		if ( !axiom_welldone_options_is_used() ) return;

		axiom_welldone_sc_load_scripts();
		axiom_welldone_sc_prepare_scripts();

		$shortcodes = axiom_welldone_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'axiom-welldone').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		axiom_welldone_show_layout($shortcodes_list);
	}
}

// Axiom Welldone shortcodes builder settings
require_once AXIOM_WELLDONE_FW_PATH . 'core/core.shortcodes/shortcodes_settings.php';

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once AXIOM_WELLDONE_FW_PATH . 'core/core.shortcodes/shortcodes_vc.php';
}

// Axiom Welldone shortcodes implementation
// Using get_template_part(), because shortcodes can be replaced in the child theme
get_template_part('shortcodes/trx_basic/anchor');
get_template_part('shortcodes/trx_basic/audio');
get_template_part('shortcodes/trx_basic/blogger');
get_template_part('shortcodes/trx_basic/br');
get_template_part('shortcodes/trx_basic/call_to_action');
get_template_part('shortcodes/trx_basic/chat');
get_template_part('shortcodes/trx_basic/columns');
get_template_part('shortcodes/trx_basic/content');
get_template_part('shortcodes/trx_basic/form');
get_template_part('shortcodes/trx_basic/googlemap');
get_template_part('shortcodes/trx_basic/image');
get_template_part('shortcodes/trx_basic/infobox');
get_template_part('shortcodes/trx_basic/line');
get_template_part('shortcodes/trx_basic/list');
get_template_part('shortcodes/trx_basic/promo');
get_template_part('shortcodes/trx_basic/quote');
get_template_part('shortcodes/trx_basic/section');
get_template_part('shortcodes/trx_basic/sidebar');
get_template_part('shortcodes/trx_basic/skills');
get_template_part('shortcodes/trx_basic/slider');
get_template_part('shortcodes/trx_basic/socials');
get_template_part('shortcodes/trx_basic/table');
get_template_part('shortcodes/trx_basic/title');
get_template_part('shortcodes/trx_basic/twitter');
get_template_part('shortcodes/trx_basic/video');

get_template_part('shortcodes/trx_optional/accordion');
get_template_part('shortcodes/trx_optional/button');
get_template_part('shortcodes/trx_optional/countdown');
get_template_part('shortcodes/trx_optional/dropcaps');
get_template_part('shortcodes/trx_optional/emailer');
get_template_part('shortcodes/trx_optional/highlight');
get_template_part('shortcodes/trx_optional/icon');
get_template_part('shortcodes/trx_optional/popup');
get_template_part('shortcodes/trx_optional/search');
get_template_part('shortcodes/trx_optional/tabs');
get_template_part('shortcodes/trx_optional/toggles');
get_template_part('shortcodes/trx_optional/tooltip');
?>