<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

axiom_welldone_storage_set('settings', array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc	- fast & low memory required, but .less-map, shadows & gradients not supprted
																// less		- slow, but support all features
																// no		- don't use .less, all styles stored in the theme.styles.php
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',								// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true,								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)

	'allow_profiler'	=> true,								// Allow to show theme profiler when 'debug mode' is on

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
	)
);



// Default Theme Options
if ( !function_exists( 'axiom_welldone_options_settings_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_options_settings_theme_setup', 2 );	// Priority 1 for add axiom_welldone_filter handlers
	function axiom_welldone_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'axiom_welldone_options_reset');

		// Settings 
		$socials_type = axiom_welldone_get_theme_setting('socials_type');
				
		// Prepare arrays 
		axiom_welldone_storage_set('options_params', apply_filters('axiom_welldone_filter_theme_options_params', array(
			'list_fonts'				=> array('$axiom_welldone_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$axiom_welldone_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$axiom_welldone_get_list_socials' => ''),
			'list_icons' 				=> array('$axiom_welldone_get_list_icons' => ''),
			'list_posts_types' 			=> array('$axiom_welldone_get_list_posts_types' => ''),
			'list_categories' 			=> array('$axiom_welldone_get_list_categories' => ''),
			'list_menus'				=> array('$axiom_welldone_get_list_menus' => ''),
			'list_sidebars'				=> array('$axiom_welldone_get_list_sidebars' => ''),
			'list_positions' 			=> array('$axiom_welldone_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$axiom_welldone_get_list_skins' => ''),
			'list_color_schemes'		=> array('$axiom_welldone_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$axiom_welldone_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$axiom_welldone_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$axiom_welldone_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$axiom_welldone_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$axiom_welldone_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$axiom_welldone_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$axiom_welldone_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$axiom_welldone_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$axiom_welldone_get_list_animations_out' => ''),
			'list_filters'				=> array('$axiom_welldone_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$axiom_welldone_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$axiom_welldone_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$axiom_welldone_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$axiom_welldone_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$axiom_welldone_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$axiom_welldone_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$axiom_welldone_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$axiom_welldone_get_list_yesno' => ''),
			'list_on_off' 				=> array('$axiom_welldone_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$axiom_welldone_get_list_showhide' => ''),
			'list_sorting' 				=> array('$axiom_welldone_get_list_sortings' => ''),
			'list_ordering' 			=> array('$axiom_welldone_get_list_orderings' => ''),
			'list_locations' 			=> array('$axiom_welldone_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		axiom_welldone_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'axiom-welldone'),
					"start" => "partitions",
					"override" => "category,services_group,post,page,custom",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'axiom-welldone'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select body style:', 'axiom-welldone') )
								. ' <br>' 
								. wp_kses_data( __('<b>boxed</b> - if you want use background color and/or image', 'axiom-welldone') )
								. ',<br>'
								. wp_kses_data( __('<b>wide</b> - page fill whole window with centered content', 'axiom-welldone') )
								. (axiom_welldone_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'axiom-welldone') )
									: '')
								. (axiom_welldone_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'axiom-welldone') )
									: ''),
					"info" => true,
					"override" => "category,services_group,post,page,custom",
					"std" => "wide",
					"options" => axiom_welldone_get_options_param('list_body_styles'),
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Add paddings above and below the page content', 'axiom-welldone') ),
					"override" => "post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select skin for the theme decoration', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "less",
					"options" => axiom_welldone_get_options_param('list_skins'),
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the entire page', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Color and image for the site background', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Use custom color and/or image as the site background", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Body background color',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select custom image position',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Always load background images or only for boxed body style', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'axiom-welldone'),
						'always' => esc_html__('Always', 'axiom-welldone')
					),
					"type" => "switch"
					),
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'axiom-welldone'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select desired style of the page header', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "header_1",
					"options" => axiom_welldone_get_options_param('list_header_styles'),
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select position for the top panel with logo and main menu', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'axiom-welldone'),
						'above' => esc_html__('Above slider', 'axiom-welldone'),
						'over'  => esc_html__('Over slider', 'axiom-welldone')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the top panel', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show post/page/category title', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show path to current category (post, page)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select the Main menu style and position', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select main menu for the current page',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => axiom_welldone_get_options_param('list_menus'),
					"type" => "select"),
					
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Attach main menu to top of window then page scroll down', 'axiom-welldone') ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'axiom-welldone'), 
						"none"=>esc_html__("Don't fix menu position", 'axiom-welldone')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select animation to show submenu ', 'axiom-welldone') ),
					"std" => "bounceIn",
					"type" => "select",
					"options" => axiom_welldone_get_options_param('list_animations_in')),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select animation to hide submenu ', 'axiom-welldone') ),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => axiom_welldone_get_options_param('list_animations_out')),
		
		"menu_mobile" => array( 
					"title" => esc_html__('Main menu responsive', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Allow responsive version for the main menu if window width less then this value', 'axiom-welldone') ),
					"std" => 1024,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Width for dropdown menus in main menu', 'axiom-welldone') ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select parts for the user's menu area", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show user menu area on top of page', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select user menu for the current page',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => axiom_welldone_get_options_param('list_menus'),
					"type" => "select"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show Login and Logout buttons in the user menu area', 'axiom-welldone') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show Social icons in the user menu area', 'axiom-welldone') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select or upload logos for the site's header and select it position", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Main logo image', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Main logo image used on Retina display', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Logo text - display it after logo image', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Height for the logo in the header area', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Top offset for the logo in the header area', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'axiom-welldone'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select parameters for main slider (you can override it in each category and page)', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want to show slider on each page (post)', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'axiom-welldone'),
					"desc" => wp_kses_data( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'axiom-welldone'),
						"fullwide"=>esc_html__("Fullwide", 'axiom-welldone'),
						"fullscreen"=>esc_html__("Fullscreen", 'axiom-welldone')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Slider height (in pixels) - only if slider display with fixed height.", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'axiom-welldone'),
					"desc" => wp_kses_data( __('What engine use to show slider?', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => axiom_welldone_get_options_param('list_sliders'),
					"type" => "radio"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => axiom_welldone_array_merge(array(0 => esc_html__('- Select category -', 'axiom-welldone')), axiom_welldone_get_options_param('list_categories')),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'axiom-welldone'),
					"desc" => wp_kses_data( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'axiom-welldone'),
					"desc" => wp_kses_data( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => axiom_welldone_get_options_param('list_sorting'),
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => axiom_welldone_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Interval (in ms) for slides change in slider", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Choose pagination style for the slider", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'axiom-welldone'),
						'yes'  => esc_html__('Dots', 'axiom-welldone'), 
						'over' => esc_html__('Titles', 'axiom-welldone')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Do you want to show post's title, reviews rating and description on slides in slider", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'axiom-welldone'),
						'slide' => esc_html__('Slide', 'axiom-welldone'), 
						'fixed' => esc_html__('Fixed', 'axiom-welldone')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Do you want to show post's category on slides in slider", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Do you want to show post's reviews rating on slides in slider", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'axiom-welldone'),
					"desc" => wp_kses_data( __("How many characters show in the post's description in slider. 0 - no descriptions", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'axiom-welldone'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'axiom-welldone'),
					"desc" => wp_kses_data( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'axiom-welldone') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'axiom-welldone') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show / Hide and select main sidebar', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select position for the main sidebar or hide it',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "right",
					"options" => axiom_welldone_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the main sidebar', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select main sidebar content',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => axiom_welldone_get_options_param('list_sidebars'),
					"type" => "select"),
			
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'axiom-welldone'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select components of the footer, set style and put the content for the user's footer area", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select style for the footer sidebar or hide it', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the footer', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select footer sidebar for the blog page',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => axiom_welldone_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select columns number for the footer sidebar',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Show/Hide contacts area in the footer", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show contact information area in footer: site logo, contact info and large social icons', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the contacts area', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

		'logo_footer_retina' => array(
					"title" => esc_html__('Logo image for footer for Retina', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area) used on Retina display', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Height for the logo in the footer area (in the contacts area)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
					
		"text_footer" => array(
					"title" => esc_html__('Text for footer',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Write text for footer", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"allow_html" => true,
					"type" => "textarea"),
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Show/Hide copyright area in the footer", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show area with copyright information, footer menu and small social icons in footer', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'axiom-welldone'),
						'text' => esc_html__('Text', 'axiom-welldone'),
						'menu' => esc_html__('Text and menu', 'axiom-welldone'),
						'socials' => esc_html__('Text and Social icons', 'axiom-welldone')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the copyright area', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select footer menu for the current page',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => axiom_welldone_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Copyright text to show in footer area (bottom of site)", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "Axiom &copy; 2017 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'axiom-welldone'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Animation parameters and responsive layouts for the small screens', 'axiom-welldone') ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'axiom-welldone') ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want use extended animations effects on your site?', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'animation_on_mobile' => array(
					"title" => esc_html__('Allow CSS animations on mobile', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you allow extended animations effects on mobile devices?', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'axiom-welldone'),
					"desc" => wp_kses_data( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want use responsive layouts on small screen or still use main layout?', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'page_preloader' => array(
					"title" => esc_html__('Show page preloader',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want show animated page preloader?',  'axiom-welldone') ),
					"std" => "",
					"type" => "media"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Specify additional parameters, used to load Google fonts', 'axiom-welldone') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select subset, included into used Google fonts', 'axiom-welldone') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'axiom-welldone'),
						'latin-ext' => esc_html__('Latin Extended', 'axiom-welldone'),
						'greek' => esc_html__('Greek', 'axiom-welldone'),
						'greek-ext' => esc_html__('Greek Extended', 'axiom-welldone'),
						'cyrillic' => esc_html__('Cyrillic', 'axiom-welldone'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'axiom-welldone'),
						'vietnamese' => esc_html__('Vietnamese', 'axiom-welldone')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Put here your custom CSS and JS code', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'axiom-welldone') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'axiom-welldone') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Put here your invisible html/js code: Google analitics, counters, etc',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Put here your css code to correct main theme styles',  'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'axiom-welldone'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'axiom-welldone'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select desired blog streampage parameters (you can override it in each category)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select desired blog style', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "excerpt",
					"options" => axiom_welldone_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select desired hover style (only for Blog style = Portfolio)', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => axiom_welldone_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => axiom_welldone_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select article display method: boxed or stretch', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "stretch",
					"options" => axiom_welldone_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => axiom_welldone_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('What taxonomy use for filter buttons', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => axiom_welldone_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select the desired sorting method for posts', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "date",
					"options" => axiom_welldone_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "desc",
					"options" => axiom_welldone_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'axiom-welldone'),
					"desc" => wp_kses_data( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'axiom-welldone'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'axiom-welldone'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'axiom-welldone'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select desired style for single page', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "single-standard",
					"options" => axiom_welldone_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon for output before post/category name in some layouts', 'axiom-welldone') ),
					"override" => "services_group,post,page,custom",
					"std" => "",
					"options" => axiom_welldone_get_options_param('list_icons'),
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'axiom-welldone'),
					"override" => "page,post",
					"desc" => wp_kses_data( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'axiom-welldone') ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => axiom_welldone_get_options_param('list_alter_sizes')
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show featured image (if selected) before post content on single pages", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show area with post title on single pages', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show area with post info on single pages', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show text before "Read more" tag on single pages', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show post author information block on single post page", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show tags block on single post page", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show related posts block on single post page", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'axiom-welldone'),
					"desc" => wp_kses_data( __("How many related posts showed on single post page", 'axiom-welldone') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page,custom",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'axiom-welldone'),
					"desc" => wp_kses_data( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select the desired sorting method for related posts', 'axiom-welldone') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => axiom_welldone_get_options_param('list_sorting'),
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select the desired ordering method for related posts', 'axiom-welldone') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => axiom_welldone_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
	
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'axiom-welldone'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select excluded categories, substitute parameters, etc.', 'axiom-welldone') ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select categories, which posts are exclude from blog page', 'axiom-welldone') ),
					"std" => "",
					"options" => axiom_welldone_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select type of the pagination on blog streampages', 'axiom-welldone') ),
					"std" => "pages",
					"override" => "category,services_group,page,custom",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'axiom-welldone'),
						'slider'   => esc_html__('Slider with page numbers', 'axiom-welldone'),
						'viewmore' => esc_html__('"View more" button', 'axiom-welldone'),
						'infinite' => esc_html__('Infinite scroll', 'axiom-welldone')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select counters, displayed near the post title', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "views,comments",
					"options" => axiom_welldone_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'axiom-welldone'),
					"desc" => wp_kses_data( __('What category display in announce block (over posts thumb) - original or nearest parental', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'axiom-welldone'),
						'original' => esc_html__("Original post's category", 'axiom-welldone')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show post date after N days (before - show post age)', 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),






		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'axiom-welldone'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Set up parameters to show images, galleries, audio and video posts', 'axiom-welldone') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'axiom-welldone'),
					"desc" => wp_kses_data( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'axiom-welldone') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'axiom-welldone'), 
						"2" => esc_html__("Retina", 'axiom-welldone')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Quality (1-100) to save cropped images', 'axiom-welldone') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Substitute standard Wordpress gallery with our slider on the single pages', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Maximum images number from gallery into slider', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select engine to show popup windows with images and galleries', 'axiom-welldone') ),
					"std" => "magnific",
					"options" => axiom_welldone_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Substitute audio tag with source from soundcloud to embed player', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'axiom-welldone') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'axiom-welldone'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page,custom",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Social networks list for site footer and Social widget", 'axiom-welldone') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon and write URL to your profile in desired social networks.',  'axiom-welldone') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? axiom_welldone_get_options_param('list_socials') : axiom_welldone_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show social share buttons block", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'axiom-welldone'),
						'vertical'	=> esc_html__('Vertical', 'axiom-welldone'),
						'horizontal'=> esc_html__('Horizontal', 'axiom-welldone')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show share counters after social buttons", 'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Caption for the block with social share buttons',  'axiom-welldone') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'axiom-welldone'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'axiom-welldone') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? axiom_welldone_get_options_param('list_socials') : axiom_welldone_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'axiom-welldone') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Your login (username) in Twitter',  'axiom-welldone') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Twitter API Consumer key',  'axiom-welldone') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Twitter API Consumer secret',  'axiom-welldone') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Twitter API Token key',  'axiom-welldone') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Twitter API Token secret',  'axiom-welldone') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
					
		"info_socials_4" => array(
			"title" => esc_html__('Login via Social network', 'axiom-welldone'),
			"desc" => wp_kses_data( __("Settings for the Login via Social networks", 'axiom-welldone') ),
			"type" => "info"),

		"social_login" => array(
			"title" => esc_html__('Social plugin shortcode',  'axiom-welldone'),
			"desc" => wp_kses_data( __('Social plugin shortcode like [plugin_shortcode]',  'axiom-welldone') ),
			"divider" => false,
			"std" => "",
			"type" => "text"),

		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'axiom-welldone'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Company address, phones and e-mail', 'axiom-welldone') ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'axiom-welldone'),
					"desc" => wp_kses_data( __('String with contact info in the left side of the site header', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'axiom-welldone'),
					"desc" => wp_kses_data( __('String with open hours in the site header', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'axiom-welldone'),
					"desc" => wp_kses_data( __('E-mail for send contact form and user registration data', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Company country, post code and city', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Street and house number', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Phone number', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Fax number', 'axiom-welldone') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'axiom-welldone') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Message's maxlength in the contact form shortcode", 'axiom-welldone') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'axiom-welldone'),
					"desc" => wp_kses_data( __("Message's maxlength in the comments form", 'axiom-welldone') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'axiom-welldone'),
					"desc" => wp_kses_data( __('What function use to send mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'axiom-welldone') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'axiom-welldone'),
					"desc" => wp_kses_data( __("What function use to send mail? Attention! Only wp_mail support attachment in the mail!", 'axiom-welldone') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'axiom-welldone'),
						'mail' => esc_html__('PHP mail', 'axiom-welldone')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'axiom-welldone'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Enable/disable AJAX search and output settings for it', 'axiom-welldone') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show search field in the top area and side menus', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Use incremental AJAX search for the search field in top of page', 'axiom-welldone') ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'axiom-welldone'),
					"desc" => wp_kses_data( __('The minimum length of the search string',  'axiom-welldone') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'axiom-welldone'),
					"desc" => wp_kses_data( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'axiom-welldone') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Select post types, what will be include in search results. If not selected - use all types.', 'axiom-welldone') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => axiom_welldone_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'axiom-welldone'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __('Number of the posts to show in search results',  'axiom-welldone') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'axiom-welldone'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's thumbnail in the search results", 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'axiom-welldone'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's publish date in the search results", 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'axiom-welldone'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's author in the search results", 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'axiom-welldone'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's counters (views, comments, likes) in the search results", 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'axiom-welldone'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Basic theme functionality settings', 'axiom-welldone') ),
					"type" => "info"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Allow authors to edit their posts in frontend area", 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'axiom-welldone') ),
					"std" => "yes",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'axiom-welldone'),
					"desc" => wp_kses_data( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'axiom-welldone') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'axiom-welldone'),
					"desc" => wp_kses_data( __('Allow to use Welldone Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'axiom-welldone'),
					"desc" => wp_kses_data( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'axiom-welldone') ),
					"std" => "no",
					"options" => axiom_welldone_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"info_service_2" => array(
					 "title" => esc_html__('API Keys', 'axiom-welldone'),
					 "desc" => wp_kses_data( __('API Keys for some Web services', 'axiom-welldone') ),
					 "type" => "info"),
		'api_google' => array(
					 "title" => esc_html__('Google API Key', 'axiom-welldone'),
					 "desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'axiom-welldone') ),
					 "std" => "",
					 "type" => "text")
		));

	}
}


// Update all temporary vars (start with $axiom_welldone_) in the Theme Options with actual lists
if ( !function_exists( 'axiom_welldone_options_settings_theme_setup2' ) ) {
	add_action( 'axiom_welldone_action_after_init_theme', 'axiom_welldone_options_settings_theme_setup2', 1 );
	function axiom_welldone_options_settings_theme_setup2() {
		if (axiom_welldone_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = axiom_welldone_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$axiom_welldone_';
				$prefix_len = axiom_welldone_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (axiom_welldone_substr($k1, 0, $prefix_len) == $prefix || axiom_welldone_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = axiom_welldone_substr(axiom_welldone_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = axiom_welldone_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = axiom_welldone_array_merge($tmp[$k]['options'], $list_func == 'axiom_welldone_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'axiom-welldone'), $list_func));
								}
							}
						}
					}
				}
				axiom_welldone_storage_set('options', $tmp);
			}
		}
	}
}



// Reset old Theme Options while theme first run
if ( !function_exists( 'axiom_welldone_options_reset' ) ) {
	//add_action('after_switch_theme', 'axiom_welldone_options_reset');
	function axiom_welldone_options_reset($clear=true) {
		$theme_slug = str_replace(' ', '_', trim(axiom_welldone_strtolower(get_stylesheet())));
		$option_name = axiom_welldone_storage_get('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query( $wpdb->prepare(
										"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
										axiom_welldone_storage_get('options_prefix').'_%'
										)
							);
				// Add Templates Options
				$txt = axiom_welldone_fgc(axiom_welldone_storage_get('demo_data_url') . 'default/templates_options.txt');
				if (!empty($txt)) {
					$data = axiom_welldone_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = axiom_welldone_replace_uploads_url(axiom_welldone_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>