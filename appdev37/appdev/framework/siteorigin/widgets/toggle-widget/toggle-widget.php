<?php

/*
Widget Name: Toggle
Description: Displays a toggle box that displays content when toggle button is clicked.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Toggle_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-toggle",
            __("Toggle", "mo_theme"),
            array(
                "description" => __("Displays a toggle box with content hidden. The content is shown when the toggle is clicked.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "title" => array(
                    "type" => "text",
                    "description" => __("The title of the toggle.", "mo_theme"),
                    "label" => __("Title", "mo_theme"),
                    "default" => __("Toggle Title", "mo_theme"),
                ),
                "text" => array(
                    "type" => "tinymce",
                    "description" => __("The toggle content.", "mo_theme"),
                    "label" => __("Text", "mo_theme"),
                    "default" => __("Toggle content goes here.", "mo_theme"),
                ),
                "class" => array(
                    "type" => "text",
                    "description" => __("CSS class name to be assigned to the toggle DIV element created.", "mo_theme"),
                    "label" => __("Class", "mo_theme"),
                    "default" => "",
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "class" => $instance["class"],
            "title" => $instance["title"],
            "text" => $instance["text"],
        );
    }

}
siteorigin_widget_register("mo-toggle", __FILE__, "MO_Toggle_Widget");