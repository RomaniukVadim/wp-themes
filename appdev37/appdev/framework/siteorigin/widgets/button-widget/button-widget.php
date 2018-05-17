<?php

/*
Widget Name: Button
Description: Renders a button with multiple styles.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Button_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-button",
            __("Button", "mo_theme"),
            array(
                "description" => __("Renders a button with multiple styles.", "mo_theme"),
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
                    "description" => __("The element id", "mo_theme"),
                    "label" => __("Id", "mo_theme")
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __("Inline CSS styling for the button DIV element.", "mo_theme"),
                    "label" => __("Style", "mo_theme")
                ),
                "class" => array(
                    "type" => "text",
                    "description" => __("The CSS class name for the button DIV element.", "mo_theme"),
                    "label" => __("Class", "mo_theme"),
                    "default" => "",
                ),
                "color" => array(
                    "type" => "select",
                    "description" => __("The color of the button.", "mo_theme"),
                    "label" => __("Color", "mo_theme"),
                    "options" => array(
                        "default" => __("Default", "mo_theme"),
                        "theme" => __("Theme", "mo_theme"),
                        "black" => __("Black", "mo_theme"),
                        "blue" => __("Blue", "mo_theme"),
                        "cyan" => __("Cyan", "mo_theme"),
                        "green" => __("Green", "mo_theme"),
                        "orange" => __("Orange", "mo_theme"),
                        "pink" => __("Pink", "mo_theme"),
                        "red" => __("Red", "mo_theme"),
                        "teal" => __("Teal", "mo_theme"),
                        "trans" => __("Trans", "mo_theme"),
                    )
                ),
                "type" => array(
                    "type" => "select",
                    "label" => __("Button Size", "mo_theme"),
                    "options" => array(
                        "large" => __("Large", "mo_theme"),
                        "small" => __("Small", "mo_theme"),
                        "rounded" => __("Rounded", "mo_theme"),
                    )
                ),
                "href" => array(
                    "type" => "text",
                    "description" => __("The URL to which button should point to.", "mo_theme"),
                    "label" => __("Target URL", "mo_theme"),
                    "default" => __("http://targeturl.com", "mo_theme"),
                ),
                "align" => array(
                    "type" => "select",
                    "description" => __("Alignment of the button and text alignment of the button title displayed.", "mo_theme"),
                    "label" => __("Align", "mo_theme"),
                    "options" => array(
                        "center" => __("Center", "mo_theme"),
                        "left" => __("Left", "mo_theme"),
                        "right" => __("Right", "mo_theme"),
                    )
                ),
                "target" => array(
                    "type" => "checkbox",
                    "description" => __("Open the link in new window.", "mo_theme"),
                    "label" => __("Open the link in new window", "mo_theme"),
                    "default" => true,
                ),
                "text" => array(
                    "type" => "text",
                    "description" => __("The button title or text. ", "mo_theme"),
                    "label" => __("Button Text", "mo_theme"),
                    "default" => __("Buy Now", "mo_theme"),
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "id" => $instance["id"],
            "style" => $instance["style"],
            "class" => $instance["class"],
            "color" => $instance["color"],
            "type" => $instance["type"],
            "href" => $instance["href"],
            "align" => $instance["align"],
            "target" => $instance["target"],
            "text" => $instance["text"],
        );
    }

}
siteorigin_widget_register("mo-button", __FILE__, "MO_Button_Widget");