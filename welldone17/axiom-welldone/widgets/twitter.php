<?php
/**
 * Theme Widget: Twitter feed
 */

// Theme init
if (!function_exists('axiom_welldone_widget_twitter_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_widget_twitter_theme_setup', 1 );
	function axiom_welldone_widget_twitter_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_widget_twitter_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('axiom_welldone_widget_twitter_load')) {
	add_action( 'widgets_init', 'axiom_welldone_widget_twitter_load' );
	function axiom_welldone_widget_twitter_load() {
		register_widget( 'axiom_welldone_widget_twitter' );
	}
}

// Widget Class
class axiom_welldone_widget_twitter extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_twitter', 'description' => esc_html__('Last Twitter Updates. Version for new Twitter API 1.1', 'axiom-welldone') );
		parent::__construct( 'axiom_welldone_widget_twitter', esc_html__('Welldone - Twitter', 'axiom-welldone'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$twitter_username = !empty($instance['twitter_username']) ? $instance['twitter_username'] : axiom_welldone_get_theme_option('twitter_username');
		$twitter_consumer_key = !empty($instance['twitter_consumer_key']) ? $instance['twitter_consumer_key'] : axiom_welldone_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = !empty($instance['twitter_consumer_secret']) ? $instance['twitter_consumer_secret'] : axiom_welldone_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = !empty($instance['twitter_token_key']) ? $instance['twitter_token_key'] : axiom_welldone_get_theme_option('twitter_token_key');
		$twitter_token_secret = !empty($instance['twitter_token_secret']) ? $instance['twitter_token_secret'] : axiom_welldone_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, !empty($instance['twitter_count']) ? (int) $instance['twitter_count'] : (int) axiom_welldone_get_theme_option('twitter_count'));	

		if (empty($twitter_consumer_key) || empty($twitter_consumer_secret) || empty($twitter_token_key) || empty($twitter_token_secret)) return;
		
		$data = axiom_welldone_get_twitter_data(array(
			'mode'            => 'user_timeline',
			'consumer_key'    => $twitter_consumer_key,
			'consumer_secret' => $twitter_consumer_secret,
			'token'           => $twitter_token_key,
			'secret'          => $twitter_token_secret
			)
		);
		
		if (!$data || !isset($data[0]['text'])) return;
		
		$output = '<ul>';
		$cnt = 0;
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $tweet) {
				if (axiom_welldone_substr($tweet['text'], 0, 1)=='@') continue;
				$output .= '<li class="theme_text' . ($cnt==$twitter_count-1 ? ' last' : '') . '"><a href="' . esc_url('https://twitter.com/'.($twitter_username)) . '" class="username" target="_blank">@' . ($tweet['user']['screen_name']) . '</a> ' . force_balance_tags(axiom_welldone_prepare_twitter_text($tweet)) . '</li>';
				if (++$cnt >= $twitter_count) break;
			}
		}
		$output .= '</ul>';
		
		if (!empty($output)) {
	
			// Before widget (defined by themes)
			axiom_welldone_show_layout($before_widget);
			
			// Display the widget title if one was input (before and after defined by themes)
			if ($title) axiom_welldone_show_layout($title, $before_title, $after_title);
	
			axiom_welldone_show_layout($output);
			
			// After widget (defined by themes)
			axiom_welldone_show_layout($after_widget);
		}
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['twitter_consumer_key'] = strip_tags( $new_instance['twitter_consumer_key'] );
		$instance['twitter_consumer_secret'] = strip_tags( $new_instance['twitter_consumer_secret'] );
		$instance['twitter_token_key'] = strip_tags( $new_instance['twitter_token_key'] );
		$instance['twitter_token_secret'] = strip_tags( $new_instance['twitter_token_secret'] );
		$instance['twitter_count'] = strip_tags( $new_instance['twitter_count'] );
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'twitter_username' => '',
			'twitter_consumer_key' => '',
			'twitter_consumer_secret' => '',
			'twitter_token_key' => '',
			'twitter_token_secret' => '',
			'twitter_count' => ''
			)
		);
		$title = $instance['title'];
		$twitter_username = $instance['twitter_username'];
		$twitter_consumer_key = $instance['twitter_consumer_key'];
		$twitter_consumer_secret = $instance['twitter_consumer_secret'];
		$twitter_token_key = $instance['twitter_token_key'];
		$twitter_token_secret = $instance['twitter_token_secret'];
		$twitter_count = $instance['twitter_count'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_count' )); ?>"><?php esc_html_e('Tweets count:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_count' )); ?>" value="<?php echo esc_attr($twitter_count); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_username' )); ?>"><?php esc_html_e('Twitter Username:', 'axiom-welldone'); ?><br />(<?php esc_html_e('leave empty if you paste widget code', 'axiom-welldone'); ?>)</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_username' )); ?>" value="<?php echo esc_attr($twitter_username); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_key' )); ?>"><?php esc_html_e('Twitter Consumer Key:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_key' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_consumer_key' )); ?>" value="<?php echo esc_attr($twitter_consumer_key); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_secret' )); ?>"><?php esc_html_e('Twitter Consumer Secret:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_secret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_consumer_secret' )); ?>" value="<?php echo esc_attr($twitter_consumer_secret); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_token_key' )); ?>"><?php esc_html_e('Twitter Token Key:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_token_key' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_token_key' )); ?>" value="<?php echo esc_attr($twitter_token_key); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_token_secret' )); ?>"><?php esc_html_e('Twitter Token Secret:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_token_secret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_token_secret' )); ?>" value="<?php echo esc_attr($twitter_token_secret); ?>" class="widgets_param_fullwidth" />
		</p>

	<?php
	}
}



// trx_widget_twitter
//-------------------------------------------------------------
/*
[trx_widget_twitter id="unique_id" title="Widget title" bg_image="image_url" number="3" follow="0|1"]
*/
if ( !function_exists( 'axiom_welldone_sc_widget_twitter' ) ) {
	function axiom_welldone_sc_widget_twitter($atts, $content=null){	
		$atts = axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"count" => 2,
			"username" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		extract($atts);
		$type = 'axiom_welldone_widget_twitter';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$atts['twitter_username'] = $username;
			$atts['twitter_consumer_key'] = $consumer_key;
			$atts['twitter_consumer_secret'] = $consumer_secret;
			$atts['twitter_token_key'] = $token_key;
			$atts['twitter_token_secret'] = $token_secret;
			$atts['twitter_count'] = max(1, (int) $count);
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_twitter' 
								. (axiom_welldone_exists_visual_composer() ? ' vc_widget_twitter wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, axiom_welldone_prepare_widgets_args(axiom_welldone_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_twitter', 'widget_twitter') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_widget_twitter', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_widget_twitter", "axiom_welldone_sc_widget_twitter");
}


// Add [trx_widget_twitter] in the VC shortcodes list
if (!function_exists('axiom_welldone_widget_twitter_reg_shortcodes_vc')) {
	function axiom_welldone_widget_twitter_reg_shortcodes_vc() {
		
		vc_map( array(
				"base" => "trx_widget_twitter",
				"name" => esc_html__("Widget Twitter Feed", 'axiom-welldone'),
				"description" => wp_kses_data( __("Insert widget with Twitter feed", 'axiom-welldone') ),
				"category" => esc_html__('Content', 'axiom-welldone'),
				"icon" => 'icon_trx_widget_twitter',
				"class" => "trx_widget_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", 'axiom-welldone'),
						"description" => wp_kses_data( __("Title of the widget", 'axiom-welldone') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Tweets number", 'axiom-welldone'),
						"description" => wp_kses_data( __("Tweets number to show in the feed", 'axiom-welldone') ),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "username",
						"heading" => esc_html__("Twitter Username", 'axiom-welldone'),
						"description" => wp_kses_data( __("Twitter Username", 'axiom-welldone') ),
						"group" => esc_html__('Twitter account', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => esc_html__("Consumer Key", 'axiom-welldone'),
						"description" => wp_kses_data( __("Specify Consumer Key from Twitter application", 'axiom-welldone') ),
						"group" => esc_html__('Twitter account', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => esc_html__("Consumer Secret", 'axiom-welldone'),
						"description" => wp_kses_data( __("Specify Consumer Secret from Twitter application", 'axiom-welldone') ),
						"group" => esc_html__('Twitter account', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => esc_html__("Token Key", 'axiom-welldone'),
						"description" => wp_kses_data( __("Specify Token Key from Twitter application", 'axiom-welldone') ),
						"group" => esc_html__('Twitter account', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => esc_html__("Token Secret", 'axiom-welldone'),
						"description" => wp_kses_data( __("Specify Token Secret from Twitter application", 'axiom-welldone') ),
						"group" => esc_html__('Twitter account', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiom_welldone_get_vc_param('id'),
					axiom_welldone_get_vc_param('class'),
					axiom_welldone_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Twitter extends WPBakeryShortCode {}

	}
}
?>