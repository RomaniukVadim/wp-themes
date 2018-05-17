<?php

/*
Widget Name: Contact Form
Description: Display a simple contact form.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Contact_Form_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-contact-form",
            __("Contact Form", "mo_theme"),
            array(
                "description" => __("Display a simple contact form.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "class" => array(
                    "type" => "text",
                    "description" => __("Custom CSS class name to be set for the div element created (optional)", "mo_theme"),
                    "label" => __("Class", "mo_theme"),
                    "default" => "",
                ),
                "mail_to" => array(
                    "type" => "text",
                    "description" => __("The recipient email where all form submissions will be received.", "mo_theme"),
                    "label" => __("Mail to", "mo_theme"),
                    "default" => __("receipient@mydomain.com", "mo_theme"),
                ),
                "phone" => array(
                    "type" => "checkbox",
                    "description" => __("Request for phone number of the user? A phone number field is displayed.", "mo_theme"),
                    "label" => __("Phone", "mo_theme"),
                    "default" => true,
                ),
                "web_url" => array(
                    "type" => "checkbox",
                    "description" => __("Whether the user should be requested for Web URL via an input field?", "mo_theme"),
                    "label" => __("Web url", "mo_theme"),
                    "default" => true,
                ),
                "subject" => array(
                    "type" => "checkbox",
                    "description" => __("A mail subject field is displayed if the value is checked.", "mo_theme"),
                    "label" => __("Subject", "mo_theme"),
                    "default" => true,
                ),
                "button_color" => array(
                    "type" => "select",
                    "description" => __("Color of the submit button.", "mo_theme"),
                    "label" => __("Button color", "mo_theme"),
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
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "class" => $instance["class"],
            "mail_to" => $instance["mail_to"],
            "phone" => $instance["phone"],
            "web_url" => $instance["web_url"],
            "subject" => $instance["subject"],
            "button_color" => $instance["button_color"],
        );
    }

}
siteorigin_widget_register("mo-contact-form", __FILE__, "MO_Contact_Form_Widget");

