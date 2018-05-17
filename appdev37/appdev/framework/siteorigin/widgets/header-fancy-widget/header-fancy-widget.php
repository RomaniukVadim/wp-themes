<?php

/*
Widget Name: Header fancy
Description: Draws a nice looking header with a title displayed in the center and with a line dividing the content.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Header_Fancy_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-header-fancy",
            __("Header fancy", "mo_theme"),
            array(
                "description" => __("Draws a nice looking header with a title displayed in the center and with a line dividing the content.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "id" => array(
                    "type" => "text",
                    "description" => __("The id to be set for the div element created (optional).", "mo_theme"),
                    "label" => __("ID", "mo_theme"),
                    "default" => "",
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __("Inline CSS styling applied for the div element created (optional)", "mo_theme"),
                    "label" => __("Style", "mo_theme"),
                    "default" => "",
                ),
                "text" => array(
                    "type" => "text",
                    "description" => __("The text to be displayed in the center of the header.", "mo_theme"),
                    "label" => __("Text", "mo_theme"),
                    "default" => __("Pricing FAQ", "mo_theme"),
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "id" => $instance["id"],
            "style" => $instance["style"],
            "text" => $instance["text"],
        );
    }

}
siteorigin_widget_register("mo-header-fancy", __FILE__, "MO_Header_Fancy_Widget");