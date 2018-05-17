<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_audio_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_audio_theme_setup' );
	function axiom_welldone_sc_audio_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_audio_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_audio_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_audio url="http://Axiom Welldone.themerex.dnw/wp-content/uploads/2014/12/Dream-Music-Relax.mp3" image="http://Axiom Welldone.themerex.dnw/wp-content/uploads/2014/10/post_audio.jpg" title="Insert Audio Title Here" author="Lily Hunter" controls="show" autoplay="off"]
*/

if (!function_exists('axiom_welldone_sc_audio')) {	
	function axiom_welldone_sc_audio($atts, $content = null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (axiom_welldone_param_is_on($controls) ? ' controls="controls"' : '')
			. (axiom_welldone_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( axiom_welldone_get_custom_option('substitute_audio')=='no') {
			if (axiom_welldone_param_is_on($frame)) {
				$audio = axiom_welldone_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = axiom_welldone_substitute_audio($audio, false);
			}
		}
		if (axiom_welldone_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('axiom_welldone_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_audio", "axiom_welldone_sc_audio");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_audio_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_audio_reg_shortcodes');
	function axiom_welldone_sc_audio_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_audio", array(
			"title" => esc_html__("Audio", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert audio player", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for audio file", 'axiom-welldone'),
					"desc" => wp_kses_data( __("URL for audio file", 'axiom-welldone') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose audio', 'axiom-welldone'),
						'action' => 'media_upload',
						'type' => 'audio',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose audio file', 'axiom-welldone'),
							'update' => esc_html__('Select audio file', 'axiom-welldone')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'axiom-welldone') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"title" => array(
					"title" => esc_html__("Title", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Title of the audio file", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"author" => array(
					"title" => esc_html__("Author", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Author of the audio file", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Show controls", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Show controls in audio player", 'axiom-welldone') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('show_hide')
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay audio", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Autoplay audio on page load", 'axiom-welldone') ),
					"value" => "off",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select block alignment", 'axiom-welldone') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('align')
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
if ( !function_exists( 'axiom_welldone_sc_audio_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_audio_reg_shortcodes_vc');
	function axiom_welldone_sc_audio_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_audio",
			"name" => esc_html__("Audio", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert audio player", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_audio',
			"class" => "trx_sc_single trx_sc_audio",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for audio file", 'axiom-welldone'),
					"description" => wp_kses_data( __("Put here URL for audio file", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title of the audio file", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'axiom-welldone'),
					"description" => wp_kses_data( __("Author of the audio file", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'axiom-welldone'),
					"description" => wp_kses_data( __("Show/hide controls", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Hide controls" => "hide" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay", 'axiom-welldone'),
					"description" => wp_kses_data( __("Autoplay audio on page load", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select block alignment", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('align')),
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
			),
		) );
		
		class WPBakeryShortCode_Trx_Audio extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>