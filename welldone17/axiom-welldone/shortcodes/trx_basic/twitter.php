<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_twitter_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_twitter_theme_setup' );
	function axiom_welldone_sc_twitter_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_twitter_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_twitter_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

if (!function_exists('axiom_welldone_sc_twitter')) {	
	function axiom_welldone_sc_twitter($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		$twitter_username = $user ? $user : axiom_welldone_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : axiom_welldone_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : axiom_welldone_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : axiom_welldone_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : axiom_welldone_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(axiom_welldone_get_theme_option('twitter_count')));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && axiom_welldone_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = axiom_welldone_get_scheme_color('bg');
			$rgb = axiom_welldone_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = axiom_welldone_get_css_dimensions_from_values($width);
		$hs = axiom_welldone_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = axiom_welldone_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				axiom_welldone_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || axiom_welldone_strlen($bg_texture)>2 || ($scheme && !axiom_welldone_param_is_off($scheme) && !axiom_welldone_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !axiom_welldone_param_is_off($scheme) && !axiom_welldone_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
								. ($align && $align!='none' && !axiom_welldone_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (axiom_welldone_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !axiom_welldone_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
								. '<div class="sc_slider_swiper sc_slider_nopagination swiper-slider-container'
										. (axiom_welldone_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
										. (axiom_welldone_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && axiom_welldone_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && axiom_welldone_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. '>'
									. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (axiom_welldone_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(axiom_welldone_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
						. '</div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || axiom_welldone_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_twitter', 'axiom_welldone_sc_twitter');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_twitter_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_twitter_reg_shortcodes');
	function axiom_welldone_sc_twitter_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_twitter", array(
			"title" => esc_html__("Twitter", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert twitter feed into post (page)", 'axiom-welldone') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"user" => array(
					"title" => esc_html__("Twitter Username", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"consumer_key" => array(
					"title" => esc_html__("Consumer Key", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Consumer Key from the twitter account", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"consumer_secret" => array(
					"title" => esc_html__("Consumer Secret", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Consumer Secret from the twitter account", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"token_key" => array(
					"title" => esc_html__("Token Key", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Token Key from the twitter account", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"token_secret" => array(
					"title" => esc_html__("Token Secret", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Token Secret from the twitter account", 'axiom-welldone') ),
					"value" => "",
					"type" => "text"
				),
				"count" => array(
					"title" => esc_html__("Tweets number", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Tweets number to show", 'axiom-welldone') ),
					"divider" => true,
					"value" => 3,
					"max" => 20,
					"min" => 1,
					"type" => "spinner"
				),
				"controls" => array(
					"title" => esc_html__("Show arrows", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Show control buttons", 'axiom-welldone') ),
					"value" => "yes",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				),
				"interval" => array(
					"title" => esc_html__("Tweets change interval", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'axiom-welldone') ),
					"value" => 7000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Alignment of the tweets block", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('align')
				),
				"autoheight" => array(
					"title" => esc_html__("Autoheight", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'axiom-welldone') ),
					"value" => "yes",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"options" => axiom_welldone_get_sc_param('schemes')
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Any background color for this section", 'axiom-welldone') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'axiom-welldone') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'axiom-welldone') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'axiom-welldone') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
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
if ( !function_exists( 'axiom_welldone_sc_twitter_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_twitter_reg_shortcodes_vc');
	function axiom_welldone_sc_twitter_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_twitter",
			"name" => esc_html__("Twitter", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert twitter feed into post (page)", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_twitter',
			"class" => "trx_sc_single trx_sc_twitter",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "user",
					"heading" => esc_html__("Twitter Username", 'axiom-welldone'),
					"description" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_key",
					"heading" => esc_html__("Consumer Key", 'axiom-welldone'),
					"description" => wp_kses_data( __("Consumer Key from the twitter account", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_secret",
					"heading" => esc_html__("Consumer Secret", 'axiom-welldone'),
					"description" => wp_kses_data( __("Consumer Secret from the twitter account", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_key",
					"heading" => esc_html__("Token Key", 'axiom-welldone'),
					"description" => wp_kses_data( __("Token Key from the twitter account", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_secret",
					"heading" => esc_html__("Token Secret", 'axiom-welldone'),
					"description" => wp_kses_data( __("Token Secret from the twitter account", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Tweets number", 'axiom-welldone'),
					"description" => wp_kses_data( __("Number tweets to show", 'axiom-welldone') ),
					"class" => "",
					"divider" => true,
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Show arrows", 'axiom-welldone'),
					"description" => wp_kses_data( __("Show control buttons", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('yes_no')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Tweets change interval", 'axiom-welldone'),
					"description" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'axiom-welldone') ),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Alignment of the tweets block", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'axiom-welldone'),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'axiom-welldone') ),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'axiom-welldone') ),
					"group" => esc_html__('Colors and Images', 'axiom-welldone'),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Any background color for this section", 'axiom-welldone') ),
					"group" => esc_html__('Colors and Images', 'axiom-welldone'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'axiom-welldone') ),
					"group" => esc_html__('Colors and Images', 'axiom-welldone'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'axiom-welldone'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'axiom-welldone') ),
					"group" => esc_html__('Colors and Images', 'axiom-welldone'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'axiom-welldone'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'axiom-welldone') ),
					"group" => esc_html__('Colors and Images', 'axiom-welldone'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
		
		class WPBakeryShortCode_Trx_Twitter extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>