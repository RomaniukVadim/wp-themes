<?php

/*
Widget Name: Divider
Description: Draws a line or a divider of various kinds depending on the type of divider chosen.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Divider_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-divider",
            __("Divider", "mo_theme"),
            array(
                "description" => __("Draws a line or a divider of various kinds depending on the type of divider chosen.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "type" => array(
                    "type" => "select",
                    "description" => __("Type of the divider to be created.", "mo_theme"),
                    "label" => __("Type", "mo_theme"),
                    "options" => array(
                        "divider" => __("Divider", "mo_theme"),
                        "divider_line" => __("Divider Line", "mo_theme"),
                        "divider_space" => __("Divider Space", "mo_theme"),
                        "divider_fancy" => __("Divider Fancy", "mo_theme"),
                    ),
                    "default" => "divider",
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __("Inline CSS styling applied for the div element created (optional)", "mo_theme"),
                    "label" => __("Style", "mo_theme"),
                    "default" => "",
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "type" => $instance["type"],
            "style" => $instance["style"],
        );
    }

}
siteorigin_widget_register("mo-divider", __FILE__, "MO_Divider_Widget");