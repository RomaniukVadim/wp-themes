<?php
/**
 * Plugin Name: Livemesh Framework Social Media Widget
 * Plugin URI: https://www.livemeshthemes.com/
 * Description: A widget that displays the social networks information
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

class MO_Social_Networks_Widget extends WP_Widget {

    /**
     * Widget setup.
     */
    public function __construct() {

        /* Widget settings. */
        $widget_ops = array('classname' => 'social-networks-widget', 'description' => __('A widget that displays the social network information for the website.', 'mo_theme'));

        /* Widget control settings. */
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'mo-social-networks-widget');

        /* Create the widget. */
        parent::__construct('mo-social-networks-widget', __('Social Networks Widget', 'mo_theme'), $widget_ops, $control_ops);
    }

    /**
     * How to display the widget on the screen.
     */
    function widget($args, $instance) {
        extract($args);

        $facebook = $instance['facebook'];
        $twitter = $instance['twitter'];
        $linkedin = $instance['linkedin'];
        $youtube = $instance['youtube'];
        $flickr = $instance['flickr'];
        $googleplus = $instance['googleplus'];
        $rss = $instance['rss'];

        $title = apply_filters('widget_title', $instance['title']);

        $title = $instance['title'];

        echo $before_widget;

        if (trim($title) != '')
            echo $before_title . $title . $after_title;

        echo '<ul class="social-list clearfix">';

        if (!empty($facebook))
            echo '<li><a class="facebook" href="' . $facebook . '" target="_blank" title="Follow us on Facebook">Facebook</a></li>';
        if (!empty($twitter))
            echo '<li><a class="twitter" href="' . $twitter . '" target="_blank" title="Subscribe to our Twitter Feed">Twitter</a></li>';
        if (!empty($flickr))
            echo '<li><a class="flickr" href="' . $flickr . '" target="_blank" title="View Flickr Portfolio">Flickr</a></li>';
        if (!empty($youtube))
            echo '<li><a class="youtube" href="' . $youtube . '" target="_blank" title="Subscribe to our YouTube channel">YoutTube</a></li>';
        if (!empty($linkedin))
            echo '<li><a class="linkedin" href="' . $linkedin . '" target="_blank" title="View LinkedIn Profile">LinkedIn</a></li>';
        if (!empty($googleplus))
            echo '<li><a class="googleplus" href="' . $googleplus . '" target="_blank" title="Follow us on Google Plus">Google+</a></li>';

        if (!empty($rss))
            echo '<li><a class="rss" href="' . $rss . '" target="_blank" title="Subscribe to our RSS Feed">RSS</a></li>';

        echo '</ul>';

        echo $after_widget;
    }

    /**
     * Update the widget settings.
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);

        $instance['facebook'] = $new_instance['facebook'];
        $instance['twitter'] = $new_instance['twitter'];
        $instance['linkedin'] = $new_instance['linkedin'];
        $instance['flickr'] = $new_instance['flickr'];
        $instance['youtube'] = $new_instance['youtube'];
        $instance['googleplus'] = $new_instance['googleplus'];

        $instance['rss'] = $new_instance['rss'];

        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form($instance) {

        $defaults = array('title' => __('Find us online', 'mo_theme'), 'facebook' => '', 'twitter' => '', 'linkedin' => '', 'flickr' => '', 'youtube' => '', 'googleplus' => '', 'rss' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>


        <p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook URL:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" value="<?php echo $instance['facebook']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter URL:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" value="<?php echo $instance['twitter']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn URL:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" value="<?php echo $instance['linkedin']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube URL:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" value="<?php echo $instance['youtube']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr URL:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" value="<?php echo $instance['flickr']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+ URL:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" value="<?php echo $instance['googleplus']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS Feed URL <small>(leave blank to use default RSS feed URL)</small>:', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" value="<?php echo $instance['rss']; ?>" />
        </p>

        <?php
    }

}
?>