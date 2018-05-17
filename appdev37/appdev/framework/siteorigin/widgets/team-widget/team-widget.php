<?php

/*
Widget Name: Team
Description: Displays a list of team members created in the Team Profiles tab of the WordPress Admin.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Team_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-team",
            __("Team", "mo_theme"),
            array(
                "description" => __("Displays a list of team members entered by creating Team custom post types in the Team Profiles tab of the WordPress Admin.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "post_count" => array(
                    "type" => "number",
                    "description" => __("Number of team members to display.", "mo_theme"),
                    "label" => __("Post count", "mo_theme"),
                    "default" => 6,
                ),
                "column_count" => array(
                    "type" => "number",
                    "description" => __("Number of columns per row of team members displayed", "mo_theme"),
                    "label" => __("Column count", "mo_theme"),
                    "default" => 3,
                ),
                "member_ids" => array(
                    "type" => "text",
                    "description" => __("The comma separated list of custom post ids of the member profiles to be displayed.(optional). Example: 123,234,456,876,654,321.", "mo_theme"),
                    "label" => __("Member IDs", "mo_theme"),
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "post_count" => $instance["post_count"],
            "column_count" => $instance["column_count"],
            "member_ids" => $instance["member_ids"],
        );
    }

}
siteorigin_widget_register("mo-team", __FILE__, "MO_Team_Widget");

