<?php

/**
 * Sidebar Manager - Manages all the sidebars for us.
 *
 *
 * @package Livemesh_Framework
 */

if (!class_exists('MO_SidebarManager')) {

    class MO_SidebarManager {

        protected static $instance;
        protected $sidebar_names = array();
        protected $sidebar_descriptions = array();
        protected $footer_sidebar_count = 0;
        protected $footer_sidebar_names = array();
        protected $footer_sidebar_descriptions = array();

        /**
         * Constructor method for the MO_SidebarManager class.
         *

         */
        protected function __construct() {

        }

        /**
         * Constructor method for the MO_SidebarManager class.
         *

         */
        public static function getInstance() {
            if (!isset(self::$instance)) {
                // Check if this is at least PHP 5.3 version
                if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                    $class = get_called_class();
                    self::$instance = new $class;
                }
                else {
                    $c = __CLASS__;
                    self::$instance = new $c;
                }
            }
            return self::$instance;
        }

        /**
         * Prevent cloning of this singleton
         *

         */
        public function __clone() {
            trigger_error('Clone is not allowed.', E_USER_ERROR);
        }

        /**
         * Init method for the MO_Slider_Manager class.
         * Called during theme setup.
         *
         */
        function initialize() {

            $this->init_sidebars();

            add_action('widgets_init', array(
                &$this,
                'register_sidebars'
            ));
        }

        function init_sidebars() {
            $this->sidebar_names = array(
                'primary-post' => __('Post Sidebar', 'mo_theme'),
                'primary-blog' => __('Blog Sidebar', 'mo_theme'),
                'primary-page' => __('Page Sidebar', 'mo_theme'),
                'after-singular-post' => __('After Singular Post', 'mo_theme'),
                'after-singular-page' => __('After Singular Page', 'mo_theme'),
                'slider-area-home' => __('Home Page Slider Area', 'mo_theme'),
                'portfolio' => __('Portfolio Area', 'mo_theme'),
                'header' => __('Header Area', 'mo_theme')
            );

            // Allow others to enhance sidebars
            $this->sidebar_names = apply_filters('mo_sidebar_names', $this->sidebar_names);

            $this->footer_sidebar_names = array(
                'footer1' => __('Footer Widget Area One', 'mo_theme'),
                'footer2' => __('Footer Widget Area Two', 'mo_theme'),
                'footer3' => __('Footer Widget Area Three', 'mo_theme'),
                'footer4' => __('Footer Widget Area Four', 'mo_theme'),
                'footer5' => __('Footer Widget Area Five', 'mo_theme'),
                'footer6' => __('Footer Widget Area Six', 'mo_theme')
            );

            $this->sidebar_descriptions = array(
                'primary-post' => __('Sidebar displayed in single post', 'mo_theme'),
                'primary-blog' => __('Sidebar displayed in post archive pages', 'mo_theme'),
                'primary-page' => __('Sidebar displayed in pages', 'mo_theme'),
                'after-singular-post' => __('Widgets placed after the single post content', 'mo_theme'),
                'after-singular-page' => __('Widgets placed after the single page content', 'mo_theme'),
                'slider-area-home' => __('Widgets to be placed in top area of Custom Home Page to help create static content when sliders are disabled', 'mo_theme'),
                'portfolio' => __('Widget content in the Portfolio pages', 'mo_theme'),
                'header' => __('Widget content in the Header area. Typically custom HTML, buttons, social icons etc.', 'mo_theme')
            );

            $this->sidebar_descriptions = apply_filters('mo_sidebar_descriptions', $this->sidebar_descriptions);

            $this->footer_sidebar_descriptions = array(
                'footer1' => __('Column 1 of Footer Widget Area', 'mo_theme'),
                'footer2' => __('Column 2 of Footer Widget Area', 'mo_theme'),
                'footer3' => __('Column 3 of Footer Widget Area', 'mo_theme'),
                'footer4' => __('Column 4 of Footer Widget Area', 'mo_theme'),
                'footer5' => __('Column 5 of Footer Widget Area', 'mo_theme'),
                'footer6' => __('Column 6 of Footer Widget Area', 'mo_theme')
            );
        }

        /**
         * Registers new sidebars for the theme.
         *
         */
        function register_sidebars() {

            //register footer sidebars
            foreach ($this->sidebar_names as $id => $name) {
                $this->register_sidebar($id, $name, $this->sidebar_descriptions[$id]);
            }

            //register footer sidebars
            foreach ($this->footer_sidebar_names as $id => $name) {
                $this->register_sidebar($id, $name, $this->footer_sidebar_descriptions[$id]);
            }

            // Get the custom sidebars defined by the users
            $sidebar_list = mo_get_theme_option('mo_sidebar_list');
            if (!empty($sidebar_list)) {
                foreach ($sidebar_list as $sidebar_item) {
                    $this->register_sidebar($sidebar_item['id'], $sidebar_item['title']);
                }
            }


        }

        function get_sidebar_element_id($sidebar_id) {
            // Go for same styling for both posts and pages for now
            $element_id = preg_replace(array(
                '/-post/',
                '/-page/',
                '/-blog/'
            ), '', $sidebar_id);
            $element_id = 'sidebar-' . $element_id;

            return $element_id;
        }

        function display_sidebar($sidebar_id, $style_class = '') {
            if (is_active_sidebar($sidebar_id)) {

                echo '<div id="' . $this->get_sidebar_element_id($sidebar_id) . '" class="sidebar clearfix ' . $style_class . '">';

                dynamic_sidebar($sidebar_id);

                echo '</div>';
            }
        }

        /* Check if any one of the six footer sidebars is active */
        function is_footer_area_active() {
            $footer_active = false;
            $count = 1;
            while (!$footer_active && $count <= 6) {
                $footer_active = is_active_sidebar('footer' . $count++);
            }
            return $footer_active;
        }

        function get_sidebar_id_suffix() {
            $suffix = 'blog'; // default

            if (is_archive() || is_404() || is_search() || is_page_template('template-blog.php'))
                $suffix = 'blog';
            elseif (is_single())
                $suffix = 'post';
            elseif (is_page())
                $suffix = 'page';


            return apply_filters('mo_sidebar_id_suffix', $suffix);
        }

        function get_primary_sidebar_id() {

            $my_sidebar_id = get_post_meta(get_queried_object_id(), 'mo_primary_sidebar_choice', true);

            if (!empty($my_sidebar_id) && $my_sidebar_id !== 'default')
                return $my_sidebar_id;
            else
                return 'primary-' . $this->get_sidebar_id_suffix();
        }

        function populate_sidebars($post_id = NULL) {

            $layout_manager = mo_get_layout_manager();

            if ($layout_manager->is_full_width_layout())
                return;


            if ($layout_manager->is_left_navigation())
                echo '<div class="sidebar-left-nav threecol">';
            elseif ($layout_manager->is_right_navigation())
                echo '<div class="sidebar-right-nav threecol last">';

            $this->display_sidebar($this->get_primary_sidebar_id(), 'fullwidth');

            echo '</div><!-- end sidebar-nav -->';


        }

        function populate_footer_sidebars() {

            $footer_column = mo_get_theme_option('mo_footer_columns', 3);
            if (is_numeric($footer_column)):
                $style_class = mo_get_column_style($footer_column);

                for ($i = 1; $i <= $footer_column; $i++) {
                    if ($i != $footer_column) {
                        $this->display_sidebar('footer' . $i, $style_class);
                    }
                    else {
                        $this->display_sidebar('footer' . $i, $style_class . ' last');
                    }
                }
            else:
                switch ($footer_column):
                    case '1 + 2(3c)':
                        ?>
                        <?php $this->display_sidebar('footer1', 'fourcol'); ?>
                        <div class="eightcol last">
                            <?php $this->display_sidebar('footer2', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer3', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer4', 'fourcol last'); ?>
                        </div>
                        <?php
                        break;
                    case '2(3c) + 1':
                        ?>
                        <div class="eightcol">
                            <?php $this->display_sidebar('footer1', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer2', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer3', 'fourcol last'); ?>
                        </div>
                        <?php $this->display_sidebar('footer4', 'fourcol last'); ?>
                        <?php
                        break;
                    case '1 + 2(4c)':
                        ?>
                        <?php $this->display_sidebar('footer1', 'fourcol'); ?>
                        <div class="eightcol last">
                            <?php $this->display_sidebar('footer2', 'threecol'); ?>
                            <?php $this->display_sidebar('footer3', 'threecol'); ?>
                            <?php $this->display_sidebar('footer4', 'threecol'); ?>
                            <?php $this->display_sidebar('footer5', 'threecol last'); ?>
                        </div>
                        <?php
                        break;
                    case '2(4c) + 1':
                        ?>
                        <div class="eightcol">
                            <?php $this->display_sidebar('footer1', 'threecol'); ?>
                            <?php $this->display_sidebar('footer2', 'threecol'); ?>
                            <?php $this->display_sidebar('footer3', 'threecol'); ?>
                            <?php $this->display_sidebar('footer4', 'threecol last'); ?>
                        </div>
                        <?php $this->display_sidebar('footer5', 'fourcol last'); ?>
                        <?php
                        break;
                    case '1 + 1(2c)':
                        ?>
                        <?php $this->display_sidebar('footer1', 'sixcol'); ?>
                        <div class="sixcol last">
                            <?php $this->display_sidebar('footer2', 'sixcol'); ?>
                            <?php $this->display_sidebar('footer3', 'sixcol last'); ?>
                        </div>
                        <?php
                        break;
                    case '1 + 1(3c)':
                        ?>
                        <?php $this->display_sidebar('footer1', 'sixcol'); ?>
                        <div class="sixcol last">
                            <?php $this->display_sidebar('footer2', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer3', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer4', 'fourcol last'); ?>
                        </div>
                        <?php
                        break;
                    case '1(2c) + 1':
                        ?>
                        <div class="sixcol">
                            <?php $this->display_sidebar('footer1', 'sixcol'); ?>
                            <?php $this->display_sidebar('footer2', 'sixcol last'); ?>
                        </div>
                        <?php $this->display_sidebar('footer3', 'sixcol last'); ?>
                        <?php
                        break;
                    case '1(3c) + 1':
                        ?>
                        <div class="sixcol">
                            <?php $this->display_sidebar('footer1', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer2', 'fourcol'); ?>
                            <?php $this->display_sidebar('footer3', 'fourcol last'); ?>
                        </div>
                        <?php $this->display_sidebar('footer4', 'sixcol last'); ?>
                        <?php
                        break;
                endswitch;
            endif;
        }

        /**
         * @param $id
         * @param $name
         */
        function register_sidebar($id, $name, $desc = '') {

            if (!empty($id)) {
                register_sidebar(array(
                    'id' => $id,
                    'name' => $name,
                    'description' => $desc,
                    'before_widget' => '<aside id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
                    'after_widget' => '</div></aside>',
                    'before_title' => '<h3 class="widget-title"><span>',
                    'after_title' => '</span></h3>'
                ));
            }

        }

    }
}

/* Avoid defining global functions here - for child theme sake */

?>