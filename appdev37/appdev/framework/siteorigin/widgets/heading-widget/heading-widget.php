<?php

/*
Widget Name: Heading
Description: Create headings used as introductory titles/text to the page sections.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Heading_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-heading",
            __("Heading", "mo_theme"),
            array(
                "description" => __("Create headings used as introductory titles/text to the page sections.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "class" => array(
                    "type" => "text",
                    "description" => __("Custom CSS class name to be set for the DIV element created (optional)", "mo_theme"),
                    "label" => __("Class", "mo_theme"),
                    "default" => "",
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __("Inline CSS styling applied for the DIV element created (optional)", "mo_theme"),
                    "label" => __("Style", "mo_theme"),
                    "default" => "",
                ),
                "title" => array(
                    "type" => "text",
                    "description" => __("The title of the heading.", "mo_theme"),
                    "label" => __("Title", "mo_theme"),
                    "default" => __("Heading Title", "mo_theme"),
                ),
                "separator" => array(
                    "type" => "checkbox",
                    "label" => __("Show separator after the title?", "mo_theme"),
                    "default" => true,
                ),
                "pitch_text" => array(
                    "type" => "text",
                    "description" => __("A short description or text shown below the title.", "mo_theme"),
                    "label" => __("Pitch text", "mo_theme"),
                    "default" => "",
                    'state_handler' => array(
                        'type[heading2]' => array('show'),
                        '_else[type]' => array('hide'),
                    ),
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "style" => $instance["style"],
            "class" => $instance["class"],
            "title" => $instance["title"],
            "separator" => $instance["separator"],
            "pitch_text" => $instance["pitch_text"]
        );
    }

}
siteorigin_widget_register("mo-heading", __FILE__, "MO_Heading_Widget");