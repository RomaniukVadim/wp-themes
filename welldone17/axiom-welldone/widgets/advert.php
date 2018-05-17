<?php
/**
 * Theme Widget: Advertisement
 */

// Theme init
if (!function_exists('axiom_welldone_widget_advert_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_widget_advert_theme_setup', 1 );
	function axiom_welldone_widget_advert_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_widget_advert_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('axiom_welldone_widget_advert_load')) {
	add_action( 'widgets_init', 'axiom_welldone_widget_advert_load' );
	function axiom_welldone_widget_advert_load() {
		register_widget( 'axiom_welldone_widget_advert' );
	}
}

// Widget Class
class axiom_welldone_widget_advert extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_advert', 'description' => esc_html__('Advertisement block', 'axiom-welldone') );
		parent::__construct( 'axiom_welldone_widget_advert', esc_html__('Welldone - Advertisement block', 'axiom-welldone'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		
		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$advert_image = isset($instance['advert_image']) ? $instance['advert_image'] : '';
		$advert_link = isset($instance['advert_link']) ? $instance['advert_link'] : '';
		$advert_code = isset($instance['advert_code']) ? $instance['advert_code'] : '';

		// Before widget (defined by themes)
		axiom_welldone_show_layout($before_widget);

		// Display the widget title if one was input (before and after defined by themes)
		if ($title) axiom_welldone_show_layout($title, $before_title, $after_title);
		?>			
		<div class="widget_advert_inner">
			<?php
			if ($advert_image!='') {
				if ((int) $advert_image > 0) {
					$attach = wp_get_attachment_image_src( $advert_image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$advert_image = $attach[0];
				}
				$attr = axiom_welldone_getimagesize($advert_image);
				echo (!empty($advert_link) ? '<a href="' . esc_url($advert_link) . '"' : '<span') . ' class="image_wrap"><img src="' . esc_url($advert_image) . '" alt="' . esc_attr($title) . '"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>' . (!empty($advert_link) ? '</a>': '</span>');
			}
			if ($advert_code!='') {
				echo force_balance_tags(axiom_welldone_substitute_all($advert_code));
			}
			?>
		</div>
		<?php
		
		// After widget (defined by themes)
		axiom_welldone_show_layout($after_widget);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['advert_image'] = strip_tags( $new_instance['advert_image'] );
		$instance['advert_link'] = strip_tags( $new_instance['advert_link'] );
		$instance['advert_code'] = $new_instance['advert_code'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'advert_image' => '',
			'advert_link' => '',
			'advert_code' => ''
			)
		);
		$title = $instance['title'];
		$advert_image = $instance['advert_image'];
		$advert_link = $instance['advert_link'];
		$advert_code = $instance['advert_code'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'axiom-welldone'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_image' )); ?>"><?php echo wp_kses_data( __('Image source URL:<br />(leave empty if you paste advert code)', 'axiom-welldone') ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'advert_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_image' )); ?>" value="<?php echo esc_attr($advert_image); ?>"  class="widgets_param_fullwidth widgets_param_img_selector" />
            <?php
			axiom_welldone_show_layout(axiom_welldone_show_custom_field($this->get_field_id( 'advert_media' ), array('type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'advert_image' )), null));
			if ($advert_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($advert_image); ?>"  class="widgets_param_maxwidth" alt="" />
			<?php
			}
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_link' )); ?>"><?php echo wp_kses_data( __('Image link URL:<br />(leave empty if you paste advert code)', 'axiom-welldone') ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'advert_link' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_link' )); ?>" value="<?php echo esc_attr($advert_link); ?>"  class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_code' )); ?>"><?php esc_html_e('or paste Advert Widget HTML Code:', 'axiom-welldone'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'advert_code' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_code' )); ?>" rows="5"  class="widgets_param_fullwidth"><?php echo htmlspecialchars($advert_code); ?></textarea>
		</p>
	<?php
	}
}



// trx_widget_advert
//-------------------------------------------------------------
/*
[trx_widget_banner id="unique_id" title="Widget title" fullwidth="0|1" image="image_url" link="Image_link_url" code="html & js code"]
*/
if ( !function_exists( 'axiom_welldone_sc_widget_advert' ) ) {
	function axiom_welldone_sc_widget_advert($atts, $content=null){	
		$atts = axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"image" => "",
			"link" => "",
			"code" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		extract($atts);
		$type = 'axiom_welldone_widget_advert';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$atts['advert_image'] = $image;
			$atts['advert_link'] = $link;
			$atts['advert_code'] = $code;
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_advert' 
								. (axiom_welldone_exists_visual_composer() ? ' vc_widget_advert wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, axiom_welldone_prepare_widgets_args(axiom_welldone_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_advert', 'widget_advert') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_widget_advert', $atts, $content);
	}
	axiom_welldone_require_shortcode("trx_widget_advert", "axiom_welldone_sc_widget_advert");
}


// Add [trx_widget_advert] in the VC shortcodes list
if (!function_exists('axiom_welldone_widget_advert_reg_shortcodes_vc')) {
	//add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_widget_advert_reg_shortcodes_vc');
	function axiom_welldone_widget_advert_reg_shortcodes_vc() {
		
		vc_map( array(
				"base" => "trx_widget_advert",
				"name" => esc_html__("Widget Advertisement", 'axiom-welldone'),
				"description" => wp_kses_data( __("Insert widget with advertisement banner or any HTML and/or Javascript code", 'axiom-welldone') ),
				"category" => esc_html__('Content', 'axiom-welldone'),
				"icon" => 'icon_trx_widget_advert',
				"class" => "trx_widget_advert",
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
						"param_name" => "image",
						"heading" => esc_html__("Image", 'axiom-welldone'),
						"description" => wp_kses_data( __("Select or upload image or write URL from other site for the banner (leave empty if you paste advert code)", 'axiom-welldone') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Banner's link", 'axiom-welldone'),
						"description" => wp_kses_data( __("Link URL for the banner (leave empty if you paste advert code)", 'axiom-welldone') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "code",
						"heading" => esc_html__("or paste Advert Widget HTML Code", 'axiom-welldone'),
						"description" => wp_kses_data( __("Widget's HTML and/or JS code", 'axiom-welldone') ),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					axiom_welldone_get_vc_param('id'),
					axiom_welldone_get_vc_param('class'),
					axiom_welldone_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Advert extends WPBakeryShortCode {}

	}
}
?>