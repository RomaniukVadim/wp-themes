<?php
/**
 * Plugin Name: Livemesh Framework Advertisement 125 Widget
 * Plugin URI: https://www.livemeshthemes.com/
 * Description: A widget that displays the 125x125 ads (typical of ads run by buysellads).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

class MO_Advertisement_125_Widget extends WP_Widget {

    /**
     * Widget setup.
     */
    public function __construct() {

        /* Widget settings. */
        $widget_ops = array('classname' => 'advertisement-125-widget', 'description' => __('A widget that displays the contact information.', 'mo_theme'));

        /* Widget control settings. */
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'mo-advertisement-125-widget');

        /* Create the widget. */
        parent::__construct('mo-advertisement-125-widget', __('Advertisement 125 Widget', 'mo_theme'), $widget_ops, $control_ops);

        if (basename($_SERVER['PHP_SELF']) == 'widgets.php') {
            add_action('admin_print_scripts', array(&$this, 'load_script'));
        }
    }

    function load_script() {
        wp_enqueue_script('advertisement-125', MO_SCRIPTS_URL . '/admin/advertisement-125.js', array('jquery'));
    }

    /**
     * How to display the widget on the screen.
     */
    function widget($args, $instance) {
        extract($args);

        /* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title']);

        $title = $instance['title'];
        $ad_count = $instance['ad_count'];


        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        if (trim($title) != '')
            echo $before_title . $title . $after_title;

        echo '<div id="advertisement-125" class="clearfix">';

        $ad_count = (int) $instance['ad_count'];
        for ($i = 1; $i <= $ad_count; $i++) {
            $ad_link_id = $this->get_ad_link_id($i);
            $image_link_id = $this->get_image_link_id($i);
            $ad_link = $instance[$ad_link_id];
            $image_link = $instance[$image_link_id];

            if ($ad_link && $image_link)
                echo '<a title="Advertisement " href="' . $ad_link . '"><img class="thumbnail-125" src="' . $image_link . '" alt="Advertisement"></a>';
        }

        echo '</div> <!-- #advertisement-125 -->';

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    /**
     * Update the widget settings.
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['ad_count'] = strip_tags($new_instance['ad_count']);

        $ad_count = (int) $new_instance['ad_count'];
        for ($i = 1; $i <= $ad_count; $i++) {
            $ad_link_id = $this->get_ad_link_id($i);
            $image_link_id = $this->get_image_link_id($i);
            $instance[$ad_link_id] = $new_instance[$ad_link_id];
            $instance[$image_link_id] = $new_instance[$image_link_id];
        }

        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form($instance) {

        /* Set up some default widget settings. */
        $defaults = array('title' => __('Advertisement', 'mo_theme'), 'ad_count' => 6);
        $instance = wp_parse_args((array) $instance, $defaults);

        $ad_count = $instance['ad_count'];
        ?>
        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('ad_count'); ?>"><?php _e('Number of Advertisements:', 'mo_theme'); ?></label>
            <input type="text" class="smallfat advertisement_count" id="<?php echo $this->get_field_id('ad_count'); ?>" name="<?php echo $this->get_field_name('ad_count'); ?>" value="<?php echo $instance['ad_count']; ?>" />
        </p>


        <div class="advertisement_input">

            <?php
            /* Let us display not more than 10 ads as it is on BuySellAds */

            for ($i = 1; $i <= 10; $i++):

                $ad_link_id = $this->get_ad_link_id($i);
                $image_link_id = $this->get_image_link_id($i);

                if (!isset($instance[$ad_link_id]))
                    $instance[$ad_link_id] = '';
                if (!isset($instance[$image_link_id]))
                    $instance[$image_link_id] = '';

                if ($i > $ad_count)
                    echo '<div class="' . 'advertisement_' . $i . '" style="display:none">';
                else
                    echo '<div class="' . 'advertisement_' . $i . '">';
                ?>
                <p>
                    <label for="<?php echo $this->get_field_id($ad_link_id); ?>"><?php _e('Advertisement Link ', 'mo_theme') . $i; ?></label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id($ad_link_id); ?>" name="<?php echo $this->get_field_name($ad_link_id); ?>" value="<?php echo $instance[$ad_link_id]; ?>" style="width:100%;" />
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id($image_link_id); ?>"><?php _e('Image Link ', 'mo_theme') . $i; ?></label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id($image_link_id); ?>" name="<?php echo $this->get_field_name($image_link_id); ?>" value="<?php echo $instance[$image_link_id]; ?>" />
                </p>
            </div> 

        <?php endfor; ?>

        </div> <!-- advertisement_input-->

        <?php
    }

    function get_ad_link_id($index) {
        return 'ad_link' . $index;
    }

    function get_image_link_id($index) {
        return 'image_link' . $index;
    }

}
?>