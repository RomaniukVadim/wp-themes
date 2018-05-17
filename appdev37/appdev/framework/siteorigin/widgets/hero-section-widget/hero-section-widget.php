<?php

/*
Widget Name: Hero Section
Description: Full width hero section or segment with options to set parallax background.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Hero_Section_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-hero-section",
            __("Hero Section", "mo_theme"),
            array(
                "description" => __("Full width section or segment with options to set parallax background. For best results, choose Full Width Stretched as the layout for any row containing this widget.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "id" => array(
                    "type" => "text",
                    "description" => __("The id of the wrapper HTML element created by the section", "mo_theme"),
                    "label" => __("Id", "mo_theme"),
                ),
                "class" => array(
                    "type" => "text",
                    "description" => __("The CSS class of the HTML element wrapping the content.", "mo_theme"),
                    "label" => __("Class", "mo_theme"),
                    "default" => __("dark-bg", "mo_theme"),
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __("Any inline styling you would like to apply to the section. You may want to override the default top/bottom padding, provide custom markup for backgrounds etc.", "mo_theme"),
                    "label" => __("Style", "mo_theme"),
                ),
                "text" => array(
                    "type" => "tinymce",
                    "description" => __("The hero segment content.", "mo_theme"),
                    "label" => __("Hero section content", "mo_theme"),
                    "default" => __("Hero section content goes here.", "mo_theme"),
                ),
                "background_color" => array(
                    "type" => "color",
                    "description" => __("The background color to be applied to the segment.", "mo_theme"),
                    "label" => __("Background color", "mo_theme"),
                ),
                "background_image" => array(
                    "type" => "media",
                    "library" => "image",
                    "description" => __("Background image for the section.", "mo_theme"),
                    "label" => __("Background image", "mo_theme"),
                ),
                "parallax_background" => array(
                    "type" => "checkbox",
                    "description" => __("Specify if the background is a parallax one. On mobile devices and browser window size less than 1100px, the parallax effect is disabled.", "mo_theme"),
                    "label" => __("Parallax background", "mo_theme"),
                    "default" => true,
                    'state_handler' => array(
                        'bg_type[image]' => array('show'),
                        '_else[bg_type]' => array('hide'),
                    ),
                ),
                "background_speed" => array(
                    "type" => "slider",
                    "description" => __("Speed at which the parallax background moves with user scrolling the page. If the value assigned to this property is 0, the background acts like the one whose background-attachment property is set to fixed and hence does not scroll at all. A value of 1 implies the background scrolls with the page in equal increments ( same effect as background-attachment: scroll). To obtain best results, experiment with multiple values to test the parallax effect.", "mo_theme"),
                    "label" => __("Parallax Background speed", "mo_theme"),
                    'min' => 0,
                    'max' => 100,
                    'default' => 40,
                    'state_handler' => array(
                        'bg_type[image]' => array('show'),
                        '_else[bg_type]' => array('hide'),
                    ),
                ),
                "background_pattern" => array(
                    "type" => "media",
                    "library" => "image",
                    "description" => __("As an alternative to background image option above, choose background image which acts like a pattern. This image is repeated horizontally as well as vertically to help occupy the entire segment width.", "mo_theme"),
                    "label" => __("Background pattern", "mo_theme"),
                ),

            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "id" => $instance["id"],
            "class" => $instance["class"],
            "style" => $instance["style"],
            "text" => $instance["text"],
            "background_color" => $instance["background_color"],
            "background_image" => $instance["background_image"],
            "parallax_background" => $instance["parallax_background"],
            "background_speed" => $instance["background_speed"],
            "background_pattern" => $instance["background_pattern"],
        );
    }

}

siteorigin_widget_register("mo-hero-section", __FILE__, "MO_Hero_Section_Widget");

