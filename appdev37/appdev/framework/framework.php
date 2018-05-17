<?php

/**
 * Main class for the Livemesh theme framework which does the orchestration.
 *
 * @package Livemesh_Framework
 */
class MO_Framework {

    protected $theme_options;
    protected $theme_extender;
    protected $layout_manager;
    protected $sidebar_manager;
    protected $context;
    protected $image_sizes;

    /* Constructor for the class */

    function __construct() {

        $this->define_constants();

        $this->init_option_tree();

        add_action('after_setup_theme', array(&$this, 'i18n'), 9);

        add_action('after_setup_theme', array(&$this, 'load_functions'), 10);

        add_action('after_setup_theme', array(&$this, 'initialize_theme'), 11);
    }

    function init_option_tree() {
        /**
         * Option Tree Settings
         * Optional: set 'ot_show_pages' filter to false.
         * This will hide the settings & documentation pages.
         */
        add_filter('ot_show_pages', '__return_true');
        //add_filter('ot_show_pages', '__return_false');

        /**
         * Optional: set 'ot_show_new_layout' filter to false.
         * This will hide the "New Layout" section on the Theme Options page.
         */
        add_filter('ot_show_new_layout', '__return_true');

        /**
         * Required: set 'ot_theme_mode' filter to true.
         */
        add_filter('ot_theme_mode', '__return_true');

        /* Do not show the options import. The settings data import will be shown though. */
        add_filter('ot_show_settings_import', '__return_false');

        /* Do not show the options export. The settings data export will be shown though. */
        add_filter('ot_show_settings_export', '__return_false');

        /* Do not show documentation for option tree */
        add_filter('ot_show_docs', '__return_false');

        /* Do not show the options UI which enables users to define new options */
        add_filter('ot_show_options_ui', '__return_false');

        /* Make sure the page sections are ordered by menu order instead of title */
        add_filter('ot_type_custom_post_type_checkbox_query', array(&$this, 'sort_page_section_selection_list'), 10, 2);

        /**
         * Required: include OptionTree.
         */
        require_once(MO_FRAMEWORK_DIR . '/option-tree/ot-loader.php');


        /**
         * Theme Options - if file exists, loads the options
         */
        include_once(MO_FRAMEWORK_DIR . '/extensions/theme-options.php');

    }

    function i18n() {

        // Make theme available for translation
        // Translations can be filed in the /languages/ directory
        load_theme_textdomain('mo_theme', get_template_directory() . '/languages');

        $locale = get_locale();
        $locale_file = get_template_directory() . "/languages/$locale.php";
        if (is_readable($locale_file))
            require_once($locale_file);

    }


    /**
     * Define framework constants
     */
    function define_constants() {

        /* Sets the path to the parent theme directory. */
        define('MO_THEME_DIR', get_template_directory());

        /* Sets the path to the parent theme directory URI. */
        define('MO_THEME_URL', get_template_directory_uri());

        /* Sets the path to the core Livemesh Framework directory. */
        define('MO_FRAMEWORK_DIR', get_template_directory() . '/framework');

        /* Sets the path to the theme scripts directory. */
        define('MO_SCRIPTS_URL', MO_THEME_URL . '/js');

        /* Sets the path to the theme third party library scripts directory. */
        define('MO_SCRIPTS_LIB_URL', MO_THEME_URL . '/js/libs');

        /* Sets the path to the theme images directory. */
        define('MO_IMAGES_URL', MO_THEME_URL . '/images');
    }

    /**
     * All the context related functions. An extensible array of key value pairs
     */
    function has_context($context_key) {
        return (isset($this->context[$context_key]));
    }

    function set_context($context_key, $context_value) {
        $this->context[$context_key] = $context_value;
    }

    function get_context($context_key) {
        if ($this->has_context($context_key))
            return $this->context[$context_key];
        return false;
    }

    function get_easy_image_name_map() {
        // See https://developer.wordpress.org/reference/functions/the_post_thumbnail/ for standard image sizes
        $easy_name_map = array('thumbnail' => 'thumbnail', 'mini' => 'medium', 'small' => 'medium', 'proportional' => 'large', 'full' => 'full', 'medium' => 'medium-thumb', 'large' => 'large-thumb', 'square' => 'square-thumb', );
        return $easy_name_map;
    }

    function get_image_sizes() {

        if (!isset($this->image_sizes)) {

            $this->image_sizes = array(
                'medium-thumb' => array(550, 400), // portfolio
                'large-thumb' => array(820, 400), // blog
                'square-thumb' => array(450, 450), // team etc.
            );

        }
        return $this->image_sizes;
    }

    /**
     * Declare theme options global var for reuse everywhere - reduces db calls
     */
    function init_theme_options() {
        $this->theme_options = get_option('option_tree');
    }

    function get_theme_option($option, $default = null, $single = true) {
        /* Allow posts to override global options. Quite powerful. Use get_queried_object_id
        since we are interested in only the ID of current post/page being rendered. */
        $option_value = get_post_meta(get_queried_object_id(), $option, $single);

        if (!$option_value) {

            if (function_exists('ot_get_option')) {
                $option_value = ot_get_option($option, $default);
            }
            else {
                $option_value = get_option($option);
            }
        }

        if (isset($option_value))
            return $option_value;

        return $default;
    }

    /**
     * Include all the required functions
     *
     */
    function load_functions() {

        require_once(MO_THEME_DIR . '/woocommerce/woocommerce-integration.php');

        /* Load the utility functions. */

        require_once(MO_FRAMEWORK_DIR . '/extensions/framework-extender.php');
        include_once(MO_FRAMEWORK_DIR . '/extensions/init-options.php');
        require_once(MO_FRAMEWORK_DIR . '/extensions/loop-pagination.php');
        include_once(MO_FRAMEWORK_DIR . '/extensions/aq_resizer.php');
        include_once(MO_FRAMEWORK_DIR . '/extensions/page-builder.php');
        include_once(MO_FRAMEWORK_DIR . '/extensions/class-tgm-plugin-activation.php');
        /* The stylizer generates css based on options chosen by the user in theme options panel */
        include_once(MO_FRAMEWORK_DIR . '/extensions/stylizer.php');

        require_once(MO_FRAMEWORK_DIR . '/functions/utility-functions.php');
        require_once(MO_FRAMEWORK_DIR . '/functions/post-functions.php');
        require_once(MO_FRAMEWORK_DIR . '/functions/commenting.php');
        require_once(MO_FRAMEWORK_DIR . '/functions/thumbnail-functions.php');
        require_once(MO_FRAMEWORK_DIR . '/functions/blog-content.php');
        require_once(MO_FRAMEWORK_DIR . '/functions/breadcrumbs.php');

        require_once(MO_FRAMEWORK_DIR . '/partials/sitemap.php');

        require_once(MO_FRAMEWORK_DIR . '/presentation/layout-manager.php');
        require_once(MO_FRAMEWORK_DIR . '/presentation/custom-post-types.php');
        require_once(MO_FRAMEWORK_DIR . '/presentation/sidebar-manager.php');
        require_once(MO_FRAMEWORK_DIR . '/presentation/slider-manager.php');
        require_once(MO_FRAMEWORK_DIR . '/presentation/metabox-manager.php');


        $widgets_path = MO_FRAMEWORK_DIR . '/widgets/';

        include_once($widgets_path . 'mo-flickr-widget.php');
        include_once($widgets_path . 'mo-popular-posts-widget.php');
        include_once($widgets_path . 'mo-recent-posts-widget.php');
        include_once($widgets_path . 'mo-author-widget.php');
        include_once($widgets_path . 'mo-featured-posts-widget.php');
        include_once($widgets_path . 'mo-related-posts-widget.php');
        include_once($widgets_path . 'mo-advertisement-125-widget.php');
        include_once($widgets_path . 'mo-social-networks-widget.php');
        include_once($widgets_path . 'mo-contact-info-widget.php');

        $shortcodes_path = MO_FRAMEWORK_DIR . '/shortcodes/';

        include_once($shortcodes_path . 'typography-shortcodes.php');
        include_once($shortcodes_path . 'video-shortcodes.php');
        include_once($shortcodes_path . 'column-shortcodes.php');
        include_once($shortcodes_path . 'divider-shortcodes.php');
        include_once($shortcodes_path . 'box-shortcodes.php');
        include_once($shortcodes_path . 'location-shortcodes.php');
        include_once($shortcodes_path . 'image-shortcodes.php');
        include_once($shortcodes_path . 'tabs-shortcodes.php');
        include_once($shortcodes_path . 'posts-shortcodes.php');
        include_once($shortcodes_path . 'button-shortcodes.php');
        include_once($shortcodes_path . 'contact-shortcodes.php');
        include_once($shortcodes_path . 'social-shortcodes.php');
        include_once($shortcodes_path . 'team-shortcodes.php');
        include_once($shortcodes_path . 'testimonials-shortcodes.php');
        include_once($shortcodes_path . 'pricing-shortcodes.php');
        include_once($shortcodes_path . 'protected-content-shortcodes.php');
        include_once($shortcodes_path . 'slider-shortcodes.php');
        include_once($shortcodes_path . 'miscellaneous-shortcodes.php');

        /* Load the custom functions first to help override the default behavior of the theme
        and to help push fixes, customizations into this file */
        include_once(MO_THEME_DIR . '/custom/custom-functions.php');

    }

    function initialize_theme() {

        $this->init_theme_options();

        $this->enable_theme_features();

        mo_register_custom_post_types();

        /* Initialize all the helper classes */
        $this->layout_manager = mo_get_layout_manager();
        $this->layout_manager->initialize();

        $this->slider_manager = mo_get_slider_manager();
        $this->slider_manager->initialize();

        $this->theme_extender = mo_get_framework_extender();
        $this->theme_extender->initialize();

        $this->sidebar_manager = mo_get_sidebar_manager();
        $this->sidebar_manager->initialize();

        $this->context = array(); // Will be set by pages

        $this->add_actions_filters();

        $this->remove_actions_filters();

        $this->setup_admin_features();
    }

    /**
     * Enable Admin Features.
     */
    function setup_admin_features() {

        /* Sets the path to the directory containing admin enhancements. */
        define('MO_ADMIN_DIR', get_template_directory() . '/framework/admin');

        if (is_admin()) {
            //shortcode helper plugin
            require_once(MO_ADMIN_DIR . '/tinymce/tinymce.php');

            require_once(MO_ADMIN_DIR . '/slider-admin.php');
            $slider_admin = MO_Slider_Admin::getInstance();
            $slider_admin->initialize();

            require_once(MO_ADMIN_DIR . '/portfolio-admin.php');
            $portfolio_admin = MO_Portfolio_Admin::getInstance();
            $portfolio_admin->initialize();

            // Call now to initialize shortcode helper button on edit window
            $tinymce_button = new Shortcode_Helper ();
        }
    }

    function sanitize_admin_bar() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('themes');
        $wp_admin_bar->remove_menu('customize');

        $my_theme = wp_get_theme()->get('Name');
        $target_url = is_admin() ? home_url() : admin_url();

        $wp_admin_bar->add_node(array(
            'parent' => false,
            'id' => 'mo-theme-menu',
            'title' => __($my_theme, 'mo_theme'),
            'href' => $target_url
        ));

        $wp_admin_bar->add_node(array(
            'parent' => 'mo-theme-menu',
            'id' => 'theme-options',
            'title' => __("Theme Options", 'mo_theme'),
            'href' => admin_url('themes.php?page=ot-theme-options')
        ));

        if (current_theme_supports('single-page-site')) {
            $wp_admin_bar->add_node(array(
                'parent' => 'mo-theme-menu',
                'id' => 'page-sections',
                'title' => __("Page Sections", 'mo_theme'),
                'href' => admin_url('edit.php?post_type=page_section')
            ));
        }
        $wp_admin_bar->add_node(array(
            'parent' => 'mo-theme-menu',
            'id' => 'pages',
            'title' => __("Pages", 'mo_theme'),
            'href' => admin_url('edit.php?post_type=page')
        ));
        $wp_admin_bar->add_node(array(
            'parent' => 'mo-theme-menu',
            'id' => 'posts',
            'title' => __("Posts", 'mo_theme'),
            'href' => admin_url('edit.php?post_type=post')
        ));
        $wp_admin_bar->add_node(array(
            'parent' => 'mo-theme-menu',
            'id' => 'portfolios',
            'title' => __("Portfolio", 'mo_theme'),
            'href' => admin_url('edit.php?post_type=portfolio')
        ));
        $wp_admin_bar->add_node(array(
            'parent' => 'mo-theme-menu',
            'id' => 'media',
            'title' => __("Media", 'mo_theme'),
            'href' => admin_url('upload.php')
        ));

    }

    /**
     * Enable Theme Features.
     *
     */
    function add_actions_filters() {

        /* Load all JS required by the theme */
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_styles'));

        add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_styles'), 18);

        add_action('wp_before_admin_bar_render', array(&$this, 'sanitize_admin_bar'));

        /* Register menus. */
        add_action('init', array(&$this, 'register_menus'), 11);

        /* Register thumbnails. */
        add_action('init', array(&$this, 'register_thumbs'), 11);

        /* Register widgets. */
        add_action('widgets_init', array(&$this, 'register_widgets'), 11);

        /* Make text widgets and term descriptions shortcode aware. */
        add_filter('widget_text', 'do_shortcode');
        add_filter('term_description', 'do_shortcode');

        add_action('wp_head', array(&$this, 'mo_init_custom_css'), 15); // load as late as possible

        add_action('wp_enqueue_scripts', array(&$this, 'mo_enqueue_plugin_styles'), 12); // load after all the plugins

    }

    /**
     * Remove Filters/Actions from plugins.
     *
     */
    function remove_actions_filters() {

        /* Prevent the Post Types Order plugin from overriding theme queries */
        remove_filter('posts_orderby', 'CPTOrderPosts', 99, 2);

    }

    /* Output css as per user customization from the options panel */

    function mo_init_custom_css() {

        $output = '';

        $custom_css = mo_custom_css();

        if ($custom_css <> '') {
            $output .= $custom_css . "\n";
        }

        // Output styles
        if ($output <> '') {
            $output = "<!-- Options based styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
            echo $output;
        }

    }

    /**
     * Enable Theme Features.
     *
     */
    function enable_theme_features() {

        remove_theme_support('custom-background');
        remove_theme_support('custom-header');

        add_theme_support('post-thumbnails');

        add_theme_support('single-page-site');

        add_theme_support('title-tag');

        //add_theme_support('post-thumbnails', array('post', 'page', 'portfolio', 'showcase_slide', 'product'));

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        /* Add support for excerpts and entry-views to the 'page' post type. */
        add_post_type_support('page', array('excerpt'));

        add_theme_support( 'woocommerce' );

    }

    /**
     * Registers new widgets for the theme.
     *
     */
    function register_widgets() {

        register_widget('MO_Popular_Posts_Widget');
        register_widget('MO_Recent_Posts_Widget');
        register_widget('MO_Flickr_Widget');
        register_widget('MO_Author_Widget');
        register_widget('MO_Featured_Posts_Widget');
        register_widget('MO_Contact_Info_Widget');

        // YARPP is far more experienced and is recommended to use the same with the YARPP template provided
        //register_widget('MO_Related_Posts_Widget');

        register_widget('MO_Advertisement_125_Widget');
        register_widget('MO_Social_Networks_Widget');
    }

    /**
     * Registers new thumbnails for the theme.
     *
     */
    function register_thumbs() {

        $image_sizes = $this->get_image_sizes();

        foreach (array_keys($image_sizes) as $key) {
            add_image_size($key, $image_sizes[$key][0], $image_sizes[$key][1], true);
        }

    }

    /**
     * Registers new nav menus for the theme. By default, the framework registers the 'primary' menu only.
     *
     */
    function register_menus() {
        register_nav_menus(
            array(
                'primary' => __('Primary Menu', 'mo_theme'),
                'footer' => __('Footer Menu', 'mo_theme')
            )
        );
    }

    /**
     * Load all the damned Javascript entries for the theme
     *
     */
    function enqueue_scripts() {

        if (!is_admin()) {

            $layoutManager = mo_get_layout_manager();

            wp_enqueue_script( 'jquery-effects-core' );

            wp_enqueue_script('jquery-tools', MO_SCRIPTS_LIB_URL . '/jquery.tools.min.js', array('jquery'), '1.2.7', true);
            wp_enqueue_script('jquery-validate', MO_SCRIPTS_LIB_URL . '/jquery.validate.min.js', array('jquery'), '1.9.0', true);
            wp_enqueue_script('mo-drop-downs', MO_SCRIPTS_LIB_URL . '/drop-downs.js', array('jquery'), '1.4.8', true);
            wp_enqueue_script('jquery-fitvideos', MO_SCRIPTS_LIB_URL . '/jquery.fitvids.js', array('jquery'), '1.0', true);
            wp_enqueue_script('jquery-waypoint', MO_SCRIPTS_LIB_URL . '/waypoints.js', array('jquery'), '2.0.2', true);
            wp_enqueue_script('jquery-modernizr', MO_SCRIPTS_LIB_URL . '/modernizr.js', array('jquery'), '2.7.1', true);
            wp_enqueue_script('jquery-stellar', MO_SCRIPTS_LIB_URL . '/jquery.stellar.min.js', array('jquery'), '0.6.2', true);


            /* Slider packs */
            wp_enqueue_script('jquery-flexslider', MO_SCRIPTS_LIB_URL . '/jquery.flexslider.js', array('jquery'), '1.2', true);
            wp_enqueue_script('jquery-bxslider', MO_SCRIPTS_LIB_URL . '/jquery.bxslider.min.js', array('jquery'), '4.1', true);
            $slider_type = get_post_meta(get_the_ID(), 'mo_slider_choice', true);
            if (!empty($slider_type) && $slider_type == 'Nivo')
                wp_enqueue_script('nivo-slider', MO_SCRIPTS_LIB_URL . '/jquery.nivo.slider.pack.js', array('jquery'), '3.2', false);

            if (is_page())
                wp_enqueue_script('jquery-smooth-scroll', MO_SCRIPTS_LIB_URL . '/jquery.smooth-scroll.min.js', array('jquery'), '1.4.10', true);

            wp_enqueue_script('jquery-prettyphoto', MO_SCRIPTS_LIB_URL . '/jquery.prettyPhoto.js', array('jquery'), '3.1.6', true);


            wp_enqueue_script('isotope-js', MO_SCRIPTS_LIB_URL . '/isotope.pkgd.min.js', array('jquery'), '3.0.2', true);
            wp_enqueue_script('images-loaded', MO_SCRIPTS_LIB_URL . '/imagesloaded.pkgd.min.js', array('jquery'), '4.1.1', true);

            $ajax_portfolio = mo_get_theme_option('mo_ajax_portfolio');
            if ($layoutManager->is_portfolio_template() && $ajax_portfolio)
                wp_enqueue_script('jquery-infinitescroll', MO_SCRIPTS_LIB_URL . '/jquery.infinitescroll.min.js', array('jquery'), '2.0', true);


            $disable_smooth_scroll = mo_get_theme_option('mo_disable_smooth_scroll');
            if (empty($disable_smooth_scroll))
                wp_enqueue_script('jquery-nicescroll', MO_SCRIPTS_LIB_URL . '/sscr.min.js', array('jquery'), '1.2.1', true);

            if (is_singular())
                wp_enqueue_script("comment-reply");

            wp_enqueue_script('mo-slider-js', MO_SCRIPTS_URL . '/slider.js', array('jquery'), '1.0', true);
            wp_enqueue_script('mo-theme-js', MO_SCRIPTS_URL . '/main.js', array('jquery'), '1.0', true);

            $localized_array = array(
                'name_required' => __('Please provide your name', 'mo_theme'),
                'name_format' => __('Your name must consist of at least 5 characters', 'mo_theme'),
                'email_required' => __('Please provide a valid email address', 'mo_theme'),
                'url_required' => __('Please provide a valid URL', 'mo_theme'),
                'phone_required' => __('Minimum 5 characters required', 'mo_theme'),
                'human_check_failed' => __('The input the correct value for the equation above', 'mo_theme'),
                'message_required' => __('Please input the message', 'mo_theme'),
                'message_format' => __('Your message must be at least 15 characters long', 'mo_theme'),
                'success_message' => __('Your message has been sent. Thanks!', 'mo_theme')
            );

            $localized_array['blog_url'] = get_home_url();

            $localized_array['loading_portfolio'] = __('Loading the next set of posts...', 'mo_theme');
            $localized_array['finished_loading'] = __('No more items to load...', 'mo_theme');

            /* localized script attached to theme */
            wp_localize_script('mo-theme-js', 'mo_theme', $localized_array);

        }
    }

    function admin_enqueue_scripts($hook) {

        if (($hook == 'post.php') || ($hook == 'post-new.php') || ($hook == 'page.php') || ($hook == 'page-new.php')) {
            wp_enqueue_script('mo-admin-js', MO_SCRIPTS_URL . '/admin.js');
        }
    }

    function admin_enqueue_styles() {
        /* Register Style */
        wp_register_style('mo-admin-css', MO_THEME_URL . '/css/admin.css');
        wp_register_style('ot-custom-style', MO_THEME_URL . '/css/ot-custom-style.css', array('ot-admin-css'));

        /* Enqueue Style */
        wp_enqueue_style('ot-custom-style');
        wp_enqueue_style('mo-admin-css');
    }

    function enqueue_styles() {

        wp_register_style('pretty-photo', MO_THEME_URL . '/css/prettyPhoto.css', array(), false, 'screen');
        wp_register_style('icon-fonts', MO_THEME_URL . '/css/icon-fonts.css', array(), false, 'screen');

        wp_register_style('style-theme', get_template_directory_uri() . '/style.css', array('pretty-photo', 'icon-fonts'), false, 'all');

        wp_register_style('style-responsive', MO_THEME_URL . '/css/responsive.css', array('style-theme'), false, 'all');

        $skin_name = mo_get_theme_skin();
        $skin_stylesheet_dir_uri = get_template_directory_uri() . '/css/skins/';

        wp_register_style('style-skin-css', $skin_stylesheet_dir_uri . $skin_name . '.css', array('style-responsive'), false, 'all');

        wp_register_style('style-ie8', MO_THEME_URL . '/css/ie8.css', array('style-responsive'), false, 'screen');
        $GLOBALS['wp_styles']->add_data('style-ie8', 'conditional', 'IE 8');
        wp_enqueue_style('style-ie8');

        wp_register_style('style-ie9', MO_THEME_URL . '/css/ie9.css', array('style-responsive'), false, 'screen');
        $GLOBALS['wp_styles']->add_data('style-ie9', 'conditional', 'IE 9');
        wp_enqueue_style('style-ie9');

        wp_register_style('style-html5', 'http://html5shiv.googlecode.com/svn/trunk/html5.js', array('style-elements'), false, 'screen');
        $GLOBALS['wp_styles']->add_data('style-html5', 'conditional', 'IE 8');
        wp_enqueue_style('style-html5');

        /* The theme Custom CSS file for overriding css in a safe way */
        wp_register_style('style-custom', MO_THEME_URL . '/custom/custom.css', array('style-skin-css'), false, 'all');

        /* Enqueue all registered styles */
        wp_enqueue_style('pretty-photo');
        wp_enqueue_style('style-theme');
        wp_enqueue_style('style-responsive');
        wp_enqueue_style('style-skin-css');
        wp_enqueue_style('style-custom');

    }


    function mo_enqueue_plugin_styles() {
        wp_register_style('style-plugins', MO_THEME_URL . '/css/plugins.css', array('style-theme'), false, 'all');

        wp_enqueue_style('style-plugins'); // load the plugins css in the footer
    }

    function sort_page_section_selection_list($query_params, $field_id) {
        if ($field_id === 'mo_page_section_select_for_one_page') {
            $query_params['orderby'] = 'menu_order';
        }
        return $query_params;
    }


}