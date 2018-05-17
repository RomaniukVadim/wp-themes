<?php

class Shortcode_Helper {
    var $pluginname = 'shortcodepanel';
    var $path = '';
    var $internalVersion = 100;

    public function __construct() {

        // Set path to editor_plugin.js
        $this->path = get_template_directory_uri() . '/framework/admin/tinymce/';

        // Modify the version when tinyMCE plugins are changed.
        //add_filter('tiny_mce_version', array(&$this, 'change_tinymce_version'));

        // init process for button control
        add_action('init', array(&$this, 'addbuttons'));

        // We can attach it to 'admin_print_footer_scripts' (for admin-only) or 'wp_footer' (for front-end only)
        add_action('admin_print_footer_scripts', array(&$this, '_add_my_quicktags'));

    }


    function _add_my_quicktags()
    {  /* Add custom Quicktag buttons to the editor Wordpress ver. 3.3 and above only
             *
             * Params for this are:
             * - Button HTML ID (required)
             * - Button display, value="" attribute (required)
             * - Opening Tag (required)
             * - Closing Tag (required)
             * - Access key, accesskey="" attribute for the button (optional)
             * - Title, title="" attribute (optional)
             * - Priority/position on bar, 1-9 = first, 11-19 = second, 21-29 = third, etc. (optional)
             */
    ?>
        <script type="text/javascript">


            if ( typeof(QTags) != 'undefined' && QTags.addButton )  {
                QTags.addButton('segment', 'segment', '[segment id="" class="" style="" background_image="http://example.com/x.png" background_pattern="http://example.com/y.png" background_color="#eaeaea" parallax_background="true" background_speed="0.5"]', '[/segment]');
                QTags.addButton( 'two_col', 'two-columns', '[one_half]Replace with your content[/one_half][one_half_last]Replace with your content[/one_half_last]', '');
                QTags.addButton( 'three_col', 'three-columns', '[one_third]Replace with your content[/one_third][one_third]Replace with your content[/one_third][one_third_last]Replace with your content[/one_third_last]', '');
                QTags.addButton( 'four_col', 'four-columns', '[one_fourth]Replace with your content[/one_fourth][one_fourth]Replace with your content[/one_fourth][one_fourth]Replace with your content[/one_fourth][one_fourth_last]Replace with your content[/one_fourth_last]', '');
            }
        </script>
    <?php
    }

    function addbuttons() {

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        // Add only in Rich Editor mode
        if (get_user_option('rich_editing') == 'true') {
            $svr_uri = $_SERVER['REQUEST_URI'];
            if (strstr($svr_uri, 'post.php') || strstr($svr_uri, 'post-new.php') || strstr($svr_uri, 'page.php') || strstr($svr_uri, 'page-new.php')) {
                add_filter("mce_external_plugins", array(&$this, 'add_tinymce_plugin'), 5);
                add_filter('mce_buttons', array(&$this, 'register_button'), 5);
                add_filter('mce_external_languages', array(&$this, 'add_tinymce_langs_path'));
            }
        }
    }


    function register_button($buttons) {
        array_push($buttons, 'separator', $this->pluginname);
        return $buttons;
    }

    function add_tinymce_plugin($plugin_array) {

        global $tinymce_version;

        $svr_uri = $_SERVER['REQUEST_URI'];
        if (strstr($svr_uri, 'post.php') || strstr($svr_uri, 'post-new.php') || strstr($svr_uri, 'page.php') || strstr($svr_uri, 'page-new.php')) {
            if (version_compare($tinymce_version, '400', '<')) {
                $plugin_array[$this->pluginname] = $this->path . 'editor_plugin.js';
            }
            else {
                $plugin_array[$this->pluginname] = $this->path . 'plugin.js';
            }
        }

        return $plugin_array;
    }

    function add_tinymce_langs_path($plugin_array) {
        // Load the TinyMCE language file
        $plugin_array[$this->pluginname] = $this->path . 'langs.php';
        return $plugin_array;
    }


    /**
     * add_nextgen_button::change_tinymce_version()
     * A different version will rebuild the cache
     *
     * @return $version
     */
    function change_tinymce_version($version) {
        $version = $version + $this->internalVersion;
        return $version;
    }

} // end class Shortcode_Helper

?>