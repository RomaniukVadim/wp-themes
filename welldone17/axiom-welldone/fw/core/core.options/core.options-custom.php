<?php
/**
 * Axiom Welldone Framework: Theme options custom fields
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_welldone_options_custom_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_options_custom_theme_setup' );
	function axiom_welldone_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'axiom_welldone_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'axiom_welldone_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'axiom_welldone_options_custom_load_scripts');
	function axiom_welldone_options_custom_load_scripts() {
		wp_enqueue_script( 'axiom_welldone-options-custom-script',	axiom_welldone_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'axiom_welldone_show_custom_field' ) ) {
	function axiom_welldone_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(axiom_welldone_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager axiom_welldone_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'axiom-welldone') : esc_html__( 'Choose Image', 'axiom-welldone')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'axiom-welldone') : esc_html__( 'Choose Image', 'axiom-welldone')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'axiom-welldone') : esc_html__( 'Choose Image', 'axiom-welldone')) . '</a>';
				break;
		}
		return apply_filters('axiom_welldone_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>