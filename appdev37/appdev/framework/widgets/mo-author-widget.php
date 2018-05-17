<?php
/**
 * Plugin Name: Livemesh Framework Author Bio Widget
 * Plugin URI: https://www.livemeshthemes.com/
 * Description: A widget that displays author biographical information.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


class MO_Author_Widget extends WP_Widget {

    /**
     * Widget setup.
     */
    public function __construct() {

        /* Widget settings. */
        $widget_ops = array('classname' => 'author-widget', 'description' => __('A widget that displays the post author information.', 'mo_theme'));

        /* Widget control settings. */
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'mo-author-widget');

        /* Create the widget. */
        parent::__construct('mo-author-widget', __('Author Widget', 'mo_theme'), $widget_ops, $control_ops);
    }

    /**
     * How to display the widget on the screen.
     */
    function widget($args, $instance) {
        extract($args); // Display arguments including before_title, after_title, before_widget, and after_widget

        /* Author information does not make sense on archive and home page */
        if (!is_singular())
            return;

        global $post;

        /* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title']);

        $avatar_size = $instance['avatar_size'];

        $sidebar_name = $args['name'];

        // Can happen on an 404 page
        if (!isset($post))
            return;

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        /* Do not populate title for "after singular" sidebar in single posts */
        if (!empty($title)) {
            if ($sidebar_name != 'After Singular') {
                echo $before_title . $title . $after_title;
            }
        }

        $author = $post->post_author;

        $author_email = get_the_author_meta('user_email', $author);
        $author_name = get_the_author_meta('display_name', $author);
        $author_desc = get_the_author_meta('description', $author);

        $author_posts_url = get_author_posts_url($author);

        $avatar = get_avatar($author, $avatar_size);

        $twitter_url = get_the_author_meta('twitter', $author);
        $facebook_url = get_the_author_meta('facebook', $author);
        $linkedin_url = get_the_author_meta('linkedin', $author);
        $flickr_url = get_the_author_meta('flickr', $author);

        /* // Cannot handle conditionals for social urls with heredoc format
        echo <<<HTML
    <div id="author-widget" class="clearfix">
    <div class="avatar-wrap">
        <a href="$author_posts_url" title="Read all posts of $author_name">$avatar</a>
    </div>
    <div class='author-name'>$author_name</div>
    <div class='author-desc'>$author_desc</div>
    <ul class ='social-list clearfix'>
        <li><a class=\"facebook\" href="$twitter_url" title="Follow $author_name on Twitter">Twitter</li></a>
        <li><a class=\"facebook\" href="$facebook_url" title="Follow $author_name on Facebook">Facebook</li></a>
        <li><a class=\"facebook\" href="$linkedin_url" title="Follow $author_name on Linkedin">Linkedin</li></a>
        <li><a class=\"facebook\" href="$flickr_url" title="Follow $author_name on Flickr">Flickr</li></a>
    </ul>
    </div>
    HTML;
    */

        ?>
        <div id="author-widget" class="clearfix">

            <div class="avatar-wrap">
                <a href="<?php echo $author_posts_url; ?>"
                   title="Read all posts of <?php echo $author_name; ?>"><?php echo $avatar; ?></a>
            </div>

            <div class='author'><span class='author-name'><?php echo 'About ' . $author_name . ':'; ?></span>
                <span class='author-desc'><?php echo $author_desc ?></span></div>
            <ul class='social-list clearfix'>
                <?php
                if ($twitter_url)
                    echo "<li><a class=\"twitter\" href=\"$twitter_url\" title=\"Follow $author_name on Twitter\">Twitter</li></a>";
                if ($facebook_url)
                    echo "<li><a class=\"facebook\" href=\"$facebook_url\" title=\"Follow $author_name on Facebook\">Facebook</li></a>";
                if ($linkedin_url)
                    echo "<li><a class=\"linkedin\" href=\"$linkedin_url\" title=\"Follow $author_name on Linkedin\">Linkedin</li></a>";
                if ($flickr_url)
                    echo "<li><a class=\"flickr\" href=\"$flickr_url\" title=\"Follow $author_name on Flickr\">Flickr</li></a>";
                ?>
            </ul>
        </div>

        <?php

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    /**
     * Update the widget settings.
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        /* Strip tags for title to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags($new_instance['title']);

        $instance['avatar_size'] = $new_instance['avatar_size'];

        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form($instance) {

        /* Set up some default widget settings. */
        $defaults = array(
            'title' => '',
            'avatar_size' => '80',
        );
        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <p>
            <label
                for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:(optional)', 'mo_theme'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"/>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('avatar_size'); ?>"><?php _e('Avatar Size:', 'mo_theme'); ?></label>
            <select class="smallfat" id="<?php echo $this->get_field_id('avatar_size'); ?>"
                    name="<?php echo $this->get_field_name('avatar_size'); ?>">
                <option <?php selected($instance['avatar_size'], 64); ?>>64</option>
                <option <?php selected($instance['avatar_size'], 80); ?>>80</option>
                <option <?php selected($instance['avatar_size'], 96); ?>>96</option>
                <option <?php selected($instance['avatar_size'], 128); ?>>128</option>
            </select>
        </p>

    <?php
    }
}

?>