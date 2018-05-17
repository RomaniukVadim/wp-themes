<?php

/**
 * Portfolio Admin Manager - Handle the custom post types and admin functions for portfolio items
 *
 *
 * @package Appdev
 */
class MO_Portfolio_Admin
{

    private static $instance;

    /**
     * Constructor method for the MO_Portfolio_Admin class.
     *

     */
    private function __construct()
    {
        
    }

    /**
     * Constructor method for the MO_Portfolio_Admin class.
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
     * Init method for the MO_Portfolio_Admin class.
     * Called during theme setup.
     *

     */
    function initialize()
    {

        add_action('add_meta_boxes', array($this, 'add_portfolio_meta_boxes'));
        add_action('save_post', array(&$this, 'save_portfolio'));

        // Manage columns displayed in the edit screen for the Portfolio Type - Is this required?
        // TODO - Probably useless. Think about removing. Codex does not list it either
        add_filter("manage_portfolio_posts_columns", array($this, "change_portfolio_columns"));

        // Provide data for the columns of portfolio custom post type.
        add_action("manage_posts_custom_column", array($this, "custom_portfolio_columns"), 10, 2);

        //Manage column headers for columns displayed in the posts overview sceen. Different from above in the 
        // sense that this applies to list instead of single custom post edit window. 
        add_filter('manage_edit-portfolio_columns', array($this, 'edit_portfolio_columns'));

    }

    function add_portfolio_meta_boxes()
    {

        add_meta_box(
            'portfolio_box', __('Portfolio Information', 'mo_theme'), array($this, 'render_portfolio_metabox'), 'portfolio', 'normal', 'high'
        );
    }

    function edit_portfolio_columns($columns)
    {

        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Portfolio Name', 'mo_theme'),
            'portfolio_image' => __('Portfolio Image', 'mo_theme'),
            'portfolio_link' => __('Portfolio Link', 'mo_theme'),
            'portfolio_client' => __('Portfolio Client', 'mo_theme'),
            'portfolio_author' => __('Portfolio Author', 'mo_theme')
        );

        $columns = array_merge($new_columns, $columns);

        return $columns;
    }

    // TODO- Probably useless. Remove if so. 
    // Change the columns for the edit CPT screen
    function change_portfolio_columns($columns)
    {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Portfolio Name', 'mo_theme'),
            'portfolio_image' => __('Portfolio Image', 'mo_theme'),
            'portfolio_link' => __('Portfolio Link', 'mo_theme'),
            'portfolio_client' => __('Portfolio Client', 'mo_theme'),
            'portfolio_author' => __('Portfolio Author', 'mo_theme')
        );

        $columns = array_merge($new_columns, $columns);

        return $columns;
    }

    // Change only the portfolio link attributes, rest like date, title etc. will take the default values
    function custom_portfolio_columns($column, $post_id)
    {
        switch ($column) {
            case "portfolio_link":
                $portfolio_link = get_post_meta($post_id, '_portfolio_link_field', true);
                echo $portfolio_link;
                break;
            case "portfolio_image":
                mo_thumbnail(array('image_size' => 'mini', 'wrapper' => false, 'image_alt' => get_the_title(), 'size' => 'thumbnail'));
                break;
            case "portfolio_client":
                $portfolio_client = get_post_meta($post_id, '_portfolio_client_field', true);
                echo $portfolio_client;
                break;
            case "portfolio_author":
                $portfolio_author = get_post_meta($post_id, '_portfolio_author_field', true);
                echo $portfolio_author;
                break;
            case "portfolio_date":
                $portfolio_date = get_post_meta($post_id, '_portfolio_date_field', true);
                echo $portfolio_date;
                break;
        }
    }

    function render_portfolio_metabox($post)
    {

        $portfolio_link = get_post_meta($post->ID, '_portfolio_link_field', true);
        $portfolio_author = get_post_meta($post->ID, '_portfolio_author_field', true);
        $portfolio_client = get_post_meta($post->ID, '_portfolio_client_field', true);
        $portfolio_date = get_post_meta($post->ID, '_portfolio_date_field', true);

        $portfolio_info = get_post_meta($post->ID, '_portfolio_info_field', true);
        ?>
    <input type="hidden" name="portfolio_noncename" id="portfolio_noncename"
           value="<?php echo wp_create_nonce('portfolio_' . $post->ID); ?>"/>
    <p>
            <label for="portfolio_link"><?php echo __('Project URL:', 'mo_theme'); ?></label><br>
        <input id="portfolio_link" name="portfolio_link" type="text" value="<?php echo $portfolio_link;?>"/>
    </p>
    <p>
            <label for="portfolio_author"><?php echo __('Author:', 'mo_theme'); ?></label><br>
        <input id="portfolio_author" name="portfolio_author" type="text" value="<?php echo $portfolio_author;?>"/>
    </p>
    <p>
            <label for="portfolio_client"><?php echo __('Client:', 'mo_theme'); ?></label><br>
        <input id="portfolio_client" name="portfolio_client" type="text" value="<?php echo $portfolio_client;?>"/>
    </p>
    <p>
            <label for="portfolio_date"><?php echo __('Project Date:', 'mo_theme'); ?></label><br>
        <input id="portfolio_date" name="portfolio_date" type="text" value="<?php echo $portfolio_date;?>"/>
    </p>
    <p>
            <label
                for="portfolio_info"><?php echo __('Additional Project Notes (HTML accepted):', 'mo_theme'); ?></label><br>
            <textarea rows="8" cols="85" id="portfolio_info"
                      name="portfolio_info"><?php echo $portfolio_info; ?></textarea>
    </p>

    <?php
    }

    function save_portfolio($post_id)
    {

        if (!isset($_POST['portfolio_noncename'])) {
            return $post_id;
        }

        // verify this came from the our screen and with proper authorization.
        if (!wp_verify_nonce($_POST['portfolio_noncename'], 'portfolio_' . $post_id)) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;


        if (!current_user_can('edit_post', $post_id))
            return $post_id;


        $post = get_post($post_id);
        if ($post->post_type == 'portfolio') {
            //Save the value to a custom field for the post
            update_post_meta($post_id, '_portfolio_link_field', esc_attr($_POST['portfolio_link']));
            update_post_meta($post_id, '_portfolio_author_field', esc_attr($_POST['portfolio_author']));
            update_post_meta($post_id, '_portfolio_client_field', esc_attr($_POST['portfolio_client']));
            update_post_meta($post_id, '_portfolio_date_field', esc_attr($_POST['portfolio_date']));
            update_post_meta($post_id, '_portfolio_info_field', esc_attr($_POST['portfolio_info']));
        }
        return $post_id;
    }

}