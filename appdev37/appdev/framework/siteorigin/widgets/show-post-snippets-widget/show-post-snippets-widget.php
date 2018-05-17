<?php

/*
Widget Name: Show Post Snippets
Description: Displays the post snippets of posts or another custom post types with featured images in a grid layout.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class MO_Show_Post_Snippets_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-show-post-snippets",
            __("Show Post Snippets", "mo_theme"),
            array(
                "description" => __("Displays the post snippets of blog posts or another custom post types with featured images in a grid layout.", "mo_theme"),
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
                "class" => array(
                    "type" => "text",
                    "description" => __("The CSS class to be set for the wrapper div for the post snippets. Useful if you need to do some custom styling of our own (rounded, hexagon images etc.) for the displayed items. (optional).", "mo_theme"),
                    "label" => __("Class", "mo_theme"),
                ),
                "image_size" => array(
                    "type" => "select",
                    "description" => __("The image size to be used. ", "mo_theme"),
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
                    "default" => "medium"
                ),
                "number_of_columns" => array(
                    "type" => "slider",
                    "description" => __("Number of posts to display per row of post snippets.", "mo_theme"),
                    "label" => __("Number of columns", "mo_theme"),
                    "min" => 1,
                    "max" => 6,
                    "integer" => true,
                    "default" => 3,
                ),
                "hide_thumbnail" => array(
                    "type" => "checkbox",
                    "description" => __("Display thumbnail image or hide the same.", "mo_theme"),
                    "label" => __("Hide thumbnail?", "mo_theme"),
                    "default" => false,
                ),
                "display_title" => array(
                    "type" => "checkbox",
                    "description" => __("Specify if the title of the post or custom post type needs to be displayed below the featured image", "mo_theme"),
                    "label" => __("Display title?", "mo_theme"),
                    "default" => true,
                ),
                "display_summary" => array(
                    "type" => "checkbox",
                    "description" => __("Specify if the excerpt or summary content of the post/custom post type needs to be displayed below the featured image thumbnail.", "mo_theme"),
                    "label" => __("Display summary?", "mo_theme"),
                    "default" => true,
                ),
                "show_excerpt" => array(
                    "type" => "checkbox",
                    "description" => __("Display excerpt for the post/custom post type. Has no effect if display_summary is set to false. If show_excerpt is set to false and display_summary is set to true, the content of the post is displayed truncated by the more WordPress tag. If more tag is not specified, the entire post content is displayed.", "mo_theme"),
                    "label" => __("Show excerpt?", "mo_theme"),
                    "default" => true,
                ),
                "excerpt_count" => array(
                    "type" => "number",
                    "description" => __("Applicable only to excerpts. The excerpt displayed is truncated to the number of characters specified with this parameter.", "mo_theme"),
                    "label" => __("Excerpt count", "mo_theme"),
                    "default" => 100,
                ),
                "show_meta" => array(
                    "type" => "checkbox",
                    "description" => __("Display meta information like the author, date of publishing and number of comments.", "mo_theme"),
                    "label" => __("Show meta?", "mo_theme"),
                    "default" => false,
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "class" => $instance["class"],
            "posts_query" => $instance["posts_query"],
            "image_size" => $instance["image_size"],
            "number_of_columns" => $instance["number_of_columns"],
            "hide_thumbnail" => $instance["hide_thumbnail"],
            "display_title" => $instance["display_title"],
            "display_summary" => $instance["display_summary"],
            "show_excerpt" => $instance["show_excerpt"],
            "excerpt_count" => $instance["excerpt_count"],
            "show_meta" => $instance["show_meta"],
        );
    }

}
siteorigin_widget_register("mo-show-post-snippets", __FILE__, "MO_Show_Post_Snippets_Widget");

