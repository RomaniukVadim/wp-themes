<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiom_welldone_action_skin_theme_setup')) {
	add_action( 'axiom_welldone_action_init_theme', 'axiom_welldone_action_skin_theme_setup', 1 );
	function axiom_welldone_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('axiom_welldone_filter_used_fonts',				'axiom_welldone_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('axiom_welldone_filter_list_fonts',				'axiom_welldone_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('axiom_welldone_action_add_styles',				'axiom_welldone_action_skin_add_styles');
		// Add skin inline styles
		add_filter('axiom_welldone_filter_add_styles_inline',		'axiom_welldone_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('axiom_welldone_action_add_responsive',			'axiom_welldone_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('axiom_welldone_filter_add_responsive_inline',	'axiom_welldone_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('axiom_welldone_action_add_scripts',				'axiom_welldone_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('axiom_welldone_action_add_scripts_inline',		'axiom_welldone_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('axiom_welldone_filter_compile_less',			'axiom_welldone_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		axiom_welldone_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'axiom-welldone'),

			// Accent colors
			'accent1'				=> '#FF9600',
			'accent1_hover'			=> '#411867',
			
			// Headers, text and links colors
			'text'					=> '#8D9091',
			'text_light'			=> '#ACB4B6',
			'text_dark'				=> '#3C414C',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#FCFF00',
			'inverse_dark'			=> '#282C33',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#E0E0E0',
			'bg_color'				=> '#FFFFFF',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8D9091',
			'alter_light'			=> '#E0E0E0',
			'alter_dark'			=> '#3C414C',
			'alter_link'			=> '#FF9600',
			'alter_hover'			=> '#411867',
			'alter_bd_color'		=> '#E0E0E0',
			'alter_bd_hover'		=> '#E0E0E0',
			'alter_bg_color'		=> '#FFFFFF',
			'alter_bg_hover'		=> '#FFFFFF',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		axiom_welldone_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'axiom-welldone'),

			// Accent colors
			'accent1'				=> '#FF9600',
			'accent1_hover'			=> '#411867',
			
			// Headers, text and links colors
			'text'					=> '#8D9091',
			'text_light'			=> '#ACB4B6',
			'text_dark'				=> '#3C414C',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#FCFF00',
			'inverse_dark'			=> '#282C33',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#E0E0E0',
			'bg_color'				=> '#FFFFFF',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8D9091',
			'alter_light'			=> '#E0E0E0',
			'alter_dark'			=> '#3C414C',
			'alter_link'			=> '#FF9600',
			'alter_hover'			=> '#411867',
			'alter_bd_color'		=> '#E0E0E0',
			'alter_bd_hover'		=> '#E0E0E0',
			'alter_bg_color'		=> '#FFFFFF',
			'alter_bg_hover'		=> '#FFFFFF',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		axiom_welldone_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'axiom-welldone'),

			// Accent colors
			'accent1'				=> '#FF9600',
			'accent1_hover'			=> '#FFFFFF',
			
			// Headers, text and links colors
			'text'					=> '#FFFFFF',
			'text_light'			=> '#FFFFFF',
			'text_dark'				=> '#FFFFFF',
			'inverse_text'			=> '#3C414C',
			'inverse_light'			=> '#FFFFFF',
			'inverse_dark'			=> '#FCFF00',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#FFFFFF',
			
			// Whole block border and background
			'bd_color'				=> '#2B3039',
			'bg_color'				=> '#3C414C',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8D9091',
			'alter_light'			=> '#E0E0E0',
			'alter_dark'			=> '#3C414C',
			'alter_link'			=> '#FF9600',
			'alter_hover'			=> '#411867',
			'alter_bd_color'		=> '#E0E0E0',
			'alter_bd_hover'		=> '#E0E0E0',
			'alter_bg_color'		=> '#FFFFFF',
			'alter_bg_hover'		=> '#FFFFFF',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		axiom_welldone_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0.5em'
			)
		);
		axiom_welldone_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.25em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0.65em'
			)
		);
		axiom_welldone_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.625em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0.4em'
			)
		);
		axiom_welldone_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.5em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0.4em'
			)
		);
		axiom_welldone_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0.75em'
			)
		);
		axiom_welldone_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> 'Ubuntu',
			'font-size' 	=> '0.875em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '2.1em'
			)
		);
		axiom_welldone_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> 'Hind',
			'font-size' 	=> '16px ',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.375em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		axiom_welldone_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		axiom_welldone_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.75em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		axiom_welldone_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.9375em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.35em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		axiom_welldone_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		axiom_welldone_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.375em',
			'margin-bottom'	=> '1em'
			)
		);
		axiom_welldone_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		axiom_welldone_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'axiom-welldone'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('axiom_welldone_filter_skin_used_fonts')) {
	//add_filter('axiom_welldone_filter_used_fonts', 'axiom_welldone_filter_skin_used_fonts');
	function axiom_welldone_filter_skin_used_fonts($theme_fonts) {
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('axiom_welldone_filter_skin_list_fonts')) {
	//add_filter('axiom_welldone_filter_list_fonts', 'axiom_welldone_filter_skin_list_fonts');
	function axiom_welldone_filter_skin_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => axiom_welldone_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('axiom_welldone_action_skin_add_styles')) {
	//add_action('axiom_welldone_action_add_styles', 'axiom_welldone_action_skin_add_styles');
	function axiom_welldone_action_skin_add_styles() {
		// Add stylesheet files
		wp_enqueue_style( 'axiom_welldone-skin-style', axiom_welldone_get_file_url('skin.css'), array(), null );
		if (file_exists(axiom_welldone_get_file_dir('skin.customizer.css')))
			wp_enqueue_style( 'axiom_welldone-skin-customizer-style', axiom_welldone_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('axiom_welldone_filter_skin_add_styles_inline')) {
	//add_filter('axiom_welldone_filter_add_styles_inline', 'axiom_welldone_filter_skin_add_styles_inline');
	function axiom_welldone_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = axiom_welldone_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = axiom_welldone_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('axiom_welldone_action_skin_add_responsive')) {
	//add_action('axiom_welldone_action_add_responsive', 'axiom_welldone_action_skin_add_responsive');
	function axiom_welldone_action_skin_add_responsive() {
		$suffix = axiom_welldone_param_is_off(axiom_welldone_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(axiom_welldone_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			wp_enqueue_style( 'theme-skin-responsive-style', axiom_welldone_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('axiom_welldone_filter_skin_add_responsive_inline')) {
	//add_filter('axiom_welldone_filter_add_responsive_inline', 'axiom_welldone_filter_skin_add_responsive_inline');
	function axiom_welldone_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('axiom_welldone_filter_skin_compile_less')) {
	//add_filter('axiom_welldone_filter_compile_less', 'axiom_welldone_filter_skin_compile_less');
	function axiom_welldone_filter_skin_compile_less($files) {
		if (file_exists(axiom_welldone_get_file_dir('skin.less'))) {
		 	$files[] = axiom_welldone_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('axiom_welldone_action_skin_add_scripts')) {
	//add_action('axiom_welldone_action_add_scripts', 'axiom_welldone_action_skin_add_scripts');
	function axiom_welldone_action_skin_add_scripts() {
		if (file_exists(axiom_welldone_get_file_dir('skin.js')))
			wp_enqueue_script( 'theme-skin-script', axiom_welldone_get_file_url('skin.js'), array(), null );
		if (axiom_welldone_get_theme_option('show_theme_customizer') == 'yes' && file_exists(axiom_welldone_get_file_dir('skin.customizer.js')))
			wp_enqueue_script( 'theme-skin-customizer-script', axiom_welldone_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('axiom_welldone_action_skin_add_scripts_inline')) {
	//add_action('axiom_welldone_action_add_scripts_inline', 'axiom_welldone_action_skin_add_scripts_inline');
	function axiom_welldone_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
	}
}
?>