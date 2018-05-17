<?php

/**
 * Slider Admin Manager - Handle the custom post types and admin functions for sliders
 *
 *
 * @package Appdev
 */
class MO_Slider_Admin
{

    private static $instance;

    /**
     * Constructor method for the MO_Slider_Admin class.
     *

     */
    private function __construct()
    {
        
    }

    /**
     * Constructor method for the MO_Slider_Admin class.
     *

     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Prevent cloning of this singleton
     *

     */
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    /**
     * Init method for the MO_Slider_Admin class.
     * Called during theme setup.
     *

     */
    function initialize()
    {

        add_action('add_meta_boxes', array($this, 'add_showcase_slide_meta_boxes'));

        add_action('save_post', array(&$this, 'save_showcase_slide'));

        // Manage columns displayed in the edit screen for the Slider Slide Type - Is this required? 
        // TODO - Probably useless. Think about removing. Codex does not list it either
        add_filter("manage_showcase_slide_posts_columns", array($this, "change_showcase_slide_columns"));

        // Provide data for the columns of custom post type slider slide. 
        add_action("manage_posts_custom_column", array($this, "custom_showcase_slide_columns"), 10, 2);

        //Manage column headers for columns displayed in the posts overview sceen. Different from above in the 
        // sense that this applies to list instead of single custom post edit window. 
        add_filter('manage_edit-showcase_slide_columns', array($this, 'edit_showcase_slide_columns'));
    }

    /* ------------------------------------------ Showcase Slider ------------------------------------------------------------------ */

    function add_showcase_slide_meta_boxes()
    {

        add_meta_box(
            'showcase_slide_box', __('Showcase Slide Information', 'mo_theme'), array($this, 'render_showcase_slide_metabox'), 'showcase_slide', 'normal', 'high'
        );
    }

    function edit_showcase_slide_columns($columns)
    {

        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Showcase Slide Name', 'mo_theme'),
            'showcase_slide_link' => __('Showcase Slide Link', 'mo_theme')
        );

        $columns = array_merge($new_columns, $columns);

        return $columns;
    }

// TODO- Probably useless. Remove if so.
// Change the columns for the edit CPT screen
    function change_showcase_slide_columns($columns)
    {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Showcase Slide Name', 'mo_theme'),
            'showcase_slide_link' => __('Showcase Slide Link', 'mo_theme')
        );

        $columns = array_merge($new_columns, $columns);

        return $columns;
    }

// Change only the showcase_slide link attributes, rest like date, title etc. will take the default values
    function custom_showcase_slide_columns($column, $post_id)
    {
        switch ($column) {
            case "showcase_slide_link":
                $showcase_slide_link = get_post_meta($post_id, '_slide_link_field', true);
                echo $showcase_slide_link;
                break;
        }
    }

    function render_showcase_slide_metabox($post)
    {
        $showcase_slide_link = get_post_meta($post->ID, '_slide_link_field', true);

        $showcase_slide_info = get_post_meta($post->ID, '_slide_info_field', true);
        ?>
    <input type="hidden" name="showcase_slide_noncename" id="showcase_slide_noncename"
           value="<?php echo wp_create_nonce('showcase_slide_' . $post->ID); ?>"/>
    <p>
        <label for="showcase_slide_link"><?php echo __('Showcase Slider Item Link:', 'mo_theme'); ?></label><br>
        <input id="showcase_slide_link" name="showcase_slide_link" type="text"
               value="<?php echo $showcase_slide_link;?>"/>
    </p>
    <p>
        <label for="showcase_slide_info"><?php echo __('Showcase Slide Information (HTML accepted):', 'mo_theme'); ?></label><br>
        <textarea rows="6" cols="65" id="showcase_slide_info"
                  name="showcase_slide_info"><?php echo $showcase_slide_info; ?></textarea>
    </p>

    <?php
    }

    function save_showcase_slide($post_id)
    {
        if (!isset($_POST['showcase_slide_noncename'])) {
            return $post_id;
        }

        // verify this came from the our screen and with proper authorization.
        if (!wp_verify_nonce($_POST['showcase_slide_noncename'], 'showcase_slide_' . $post_id)) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;


        if (!current_user_can('edit_post', $post_id))
            return $post_id;


        $post = get_post($post_id);
        if ($post->post_type == 'showcase_slide') {
            //Save the value to a custom field for the post
            update_post_meta($post_id, '_slide_link_field', esc_attr($_POST['showcase_slide_link']));
            update_post_meta($post_id, '_slide_info_field', esc_attr($_POST['showcase_slide_info']));
        }
        return $post_id;
    }

}