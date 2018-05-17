<?php

/*
Widget Name: Pricing Plans
Description: Display the pricing table with pricing plans created in Pricing Plan tab of WordPress admin.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Pricing_Plans_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-pricing-plans",
            __("Pricing Plans", "mo_theme"),
            array(
                "description" => __("Display the pricing table with pricing plans created in Pricing Plan tab of WordPress admin.", "mo_theme"),
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
                    "description" => __("Number of pricing plans to display.", "mo_theme"),
                    "label" => __("Post count", "mo_theme"),
                    "default" => 4,
                ),
                "pricing_ids" => array(
                    "type" => "text",
                    "description" => __("Comma separated post ids for pricing plan custom post type (optional). Example: 234,235,236", "mo_theme"),
                    "label" => __("Pricing Plan IDs", "mo_theme"),
                    "default" => "",
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "post_count" => $instance["post_count"],
            "pricing_ids" => $instance["pricing_ids"],
        );
    }

}
siteorigin_widget_register("mo-pricing-plans", __FILE__, "MO_Pricing_Plans_Widget");

