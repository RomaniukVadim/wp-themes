<?php

/*
Widget Name: Custom Posts
Description: Display posts or custom post type instances based on user selection criteria.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Custom_Posts_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-custom-posts",
            __("Custom Posts", "mo_theme"),
            array(
                "description" => __("Display blog posts or custom post type instances based on user selection criteria.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "posts_query" => array(
                    "type" => "posts",
                    "label" => __("Posts Query", "mo_theme")
                ),
                "hide_thumbnail" => array(
                    "type" => "checkbox",
                    "description" => __("Display thumbnail or hide the same.", "mo_theme"),
                    "label" => __("Hide thumbnail", "mo_theme"),
                    "default" => false,
                ),
                "show_meta" => array(
                    "type" => "checkbox",
                    "description" => __("Display meta information like the author, date of publishing and number of comments.", "mo_theme"),
                    "label" => __("Show meta", "mo_theme"),
                    "default" => false,
                ),
                "excerpt_count" => array(
                    "type" => "number",
                    "description" => __("The total number of characters of excerpt to display.", "mo_theme"),
                    "label" => __("Excerpt count", "mo_theme"),
                    "default" => 70,
                ),
                "image_size" => array(
                    "type" => "select",
                    "description" => __("The size of the image displayed for posts list.", "mo_theme"),
                    "label" => __("Image size", "mo_theme"),
                    "options" => array(
                        "thumbnail" => __("Thumbnail", "mo_theme"),
                        "small" => __("Small", "mo_theme"),
                        "medium" => __("Medium", "mo_theme"),
                        "large" => __("Large", "mo_theme"),
                        "full" => __("Full", "mo_theme"),
                        "square" => __("Square", "mo_theme"),
                        "proportional" => __("Proportional", "mo_theme"),
                    ),
                    "default" => "small"
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "posts_query" => $instance["posts_query"],
            "hide_thumbnail" => $instance["hide_thumbnail"],
            "show_meta" => $instance["show_meta"],
            "excerpt_count" => $instance["excerpt_count"],
            "image_size" => $instance["image_size"],
        );
    }

}
siteorigin_widget_register("mo-custom-posts", __FILE__, "MO_Custom_Posts_Widget");