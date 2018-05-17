<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'axiom_welldone_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_theme_setup', 1 );
	function axiom_welldone_theme_setup() {

		// Register theme menus
		add_filter( 'axiom_welldone_filter_add_theme_menus',		'axiom_welldone_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'axiom_welldone_filter_add_theme_sidebars',		'axiom_welldone_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'axiom_welldone_filter_importer_options',		'axiom_welldone_set_importer_options' );

		// Add theme required plugins
		add_filter( 'axiom_welldone_filter_required_plugins',		'axiom_welldone_add_required_plugins' );

		// Init theme after WP is created
		add_action( 'wp',									'axiom_welldone_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'axiom_welldone_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',										'axiom_welldone_head_add_page_meta', 1);

		// Set list of the theme required plugins
		axiom_welldone_storage_set('required_plugins', array(
			'trx_utils',
			'visual_composer',
			'vc_extensions',
			'essgrids',
			'revslider',
			'woocommerce'
			)
		);

		// Set list of the theme required custom fonts from folder /css/font-faces
		// Attention! Font's folder must have name equal to the font's name
		axiom_welldone_storage_set('required_custom_fonts', array(
			'Amadeus'
			)
		);
		
		axiom_welldone_storage_set('demo_data_url',  AXIOM_WELLDONE_THEME_PATH . 'demo/');

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'axiom_welldone_add_theme_menus' ) ) {
	//add_filter( 'axiom_welldone_filter_add_theme_menus', 'axiom_welldone_add_theme_menus' );
	function axiom_welldone_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'axiom-welldone');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'axiom_welldone_add_theme_sidebars' ) ) {
	//add_filter( 'axiom_welldone_filter_add_theme_sidebars',	'axiom_welldone_add_theme_sidebars' );
	function axiom_welldone_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'axiom-welldone' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'axiom-welldone' )
			);
			if (function_exists('axiom_welldone_exists_woocommerce') && axiom_welldone_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'axiom-welldone' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'axiom_welldone_add_required_plugins' ) ) {
	//add_filter( 'axiom_welldone_filter_required_plugins',		'axiom_welldone_add_required_plugins' );
	function axiom_welldone_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('ThemeREX Utilities', 'axiom-welldone'),
			'version'	=> '3.0',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> axiom_welldone_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'axiom_welldone_set_importer_options' ) ) {
	//add_filter( 'axiom_welldone_filter_importer_options',	'axiom_welldone_set_importer_options' );
	function axiom_welldone_set_importer_options($options=array()) {
		if (is_array($options)) {
			// Default demo
			$options['demo_url'] = axiom_welldone_storage_get('demo_data_url');
			// Default demo
			$options['files']['default']['title'] = esc_html__('Default Demo', 'axiom-welldone');
			$options['files']['default']['domain_dev'] = esc_url(axiom_welldone_get_protocol().'://welldone.dv.axiomthemes.com');		// Developers domain
			$options['files']['default']['domain_demo']= esc_url(axiom_welldone_get_protocol().'://welldone.axiomthemes.com');		// Demo-site domain
			// If theme need more demo - just copy 'default' and change required parameter
			// For example:
			// 		$options['files']['dark_demo'] = $options['files']['default'];
			// 		$options['files']['dark_demo']['title'] = esc_html__('Dark Demo', 'axiom-welldone');
		}
		return $options;
	}
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('axiom_welldone_body_classes') ) {
	//add_filter( 'body_class', 'axiom_welldone_body_classes' );
	function axiom_welldone_body_classes( $classes ) {

		$classes[] = 'axiom_welldone_body';
		$classes[] = 'body_style_' . trim(axiom_welldone_get_custom_option('body_style'));
		$classes[] = 'body_' . (axiom_welldone_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(axiom_welldone_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(axiom_welldone_get_custom_option('article_style'));
		
		$blog_style = axiom_welldone_get_custom_option(is_singular() && !axiom_welldone_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(axiom_welldone_get_template_name($blog_style));
		
		$body_scheme = axiom_welldone_get_custom_option('body_scheme');
		if (empty($body_scheme)  || axiom_welldone_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = axiom_welldone_get_custom_option('top_panel_position');
		if (!axiom_welldone_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = axiom_welldone_get_sidebar_class();

		if (axiom_welldone_get_custom_option('show_video_bg')=='yes' && (axiom_welldone_get_custom_option('video_bg_youtube_code')!='' || axiom_welldone_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!axiom_welldone_param_is_off(axiom_welldone_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('axiom_welldone_head_add_page_meta')) {
	//add_action('wp_head', 'axiom_welldone_head_add_page_meta', 1);
	function axiom_welldone_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (axiom_welldone_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}



// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>