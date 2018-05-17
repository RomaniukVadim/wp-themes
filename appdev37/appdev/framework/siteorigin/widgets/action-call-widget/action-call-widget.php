<?php

/*
Widget Name: Action Call
Description: Create action call sections that display text with button urging the user to take action.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Action_Call_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-action-call",
            __("Action Call", "mo_theme"),
            array(
                "description" => __("Create action call sections that display text with button urging the user to take action.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(

                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),

                "text" => array(
                    "type" => "text",
                    "description" => __("The text shown as part of action call. ", "mo_theme"),
                    "label" => __("Action Text", "mo_theme"),
                    "default" => __("Ready to get started <strong>on your project</strong>?", "mo_theme"),
                ),
                "button_url" => array(
                    "type" => "link",
                    "description" => __("The URL to which the button links to. The user navigates to this URL when the button is clicked.", "mo_theme"),
                    "label" => __("Button url", "mo_theme"),
                    "default" => __("http://example.com", "mo_theme"),
                ),
                "button_color" => array(
                    "type" => "select",
                    "description" => __("Color of the button. ", "mo_theme"),
                    "label" => __("Button Color", "mo_theme"),
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
                    ),
                    "default" => "default"
                ),
                "button_text" => array(
                    "type" => "text",
                    "description" => __("The title for the button shown as part of call for action. ", "mo_theme"),
                    "label" => __("Button text", "mo_theme"),
                    "default" => __("Purchase Now", "mo_theme"),
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "text" => $instance["text"],
            "button_url" => $instance["button_url"],
            "button_color" => $instance["button_color"],
            "button_text" => $instance["button_text"],
        );
    }

}
siteorigin_widget_register("mo-action-call", __FILE__, "MO_Action_Call_Widget");

