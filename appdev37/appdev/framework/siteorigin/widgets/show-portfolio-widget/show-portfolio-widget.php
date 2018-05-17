<?php

/*
Widget Name: Show Portfolio
Description: Display portfolio in a multi-column grid, filterable by portfolio categories.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Show_Portfolio_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-show-portfolio",
            __("Show Portfolio", "mo_theme"),
            array(
                "description" => __("Display portfolio in a multi-column grid, filterable by portfolio categories.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "number_of_columns" => array(
                    "type" => "slider",
                    "description" => __("Number of portfolio items to display per row. ", "mo_theme"),
                    "label" => __("Number of columns", "mo_theme"),
                    "min" => 1,
                    "max" => 5,
                    "integer" => true,
                    "default" => 4,
                ),
                "post_count" => array(
                    "type" => "number",
                    "description" => __("Number of portfolio items to display.", "mo_theme"),
                    "label" => __("Post count", "mo_theme"),
                    "default" => 12,
                ),
                "image_size" => array(
                    "type" => "select",
                    "description" => __("Size of the image to be displayed in the portfolio.", "mo_theme"),
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
                "filterable" => array(
                    "type" => "checkbox",
                    "description" => __("The portfolio items will be filterable based on portfolio categories if checked.", "mo_theme"),
                    "label" => __("Filterable?", "mo_theme"),
                    "default" => true,
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "number_of_columns" => $instance["number_of_columns"],
            "post_count" => $instance["post_count"],
            "image_size" => $instance["image_size"],
            "filterable" => $instance["filterable"],
        );
    }

}
siteorigin_widget_register("mo-show-portfolio", __FILE__, "MO_Show_Portfolio_Widget");