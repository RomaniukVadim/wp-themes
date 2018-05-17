<?php

/*
Widget Name: Tabs
Description: Display tabbed content.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Tabs_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-tabs",
            __("Tabs", "mo_theme"),
            array(
                "description" => __("Display tabbed content.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),

            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'mo_theme'),
                ),

                'tabs' => array(
                    'type' => 'repeater',
                    'label' => __('Tabs', 'mo_theme'),
                    'item_name' => __('Tab', 'mo_theme'),
                    'item_label' => array(
                        'selector' => "[id*='tabs-title']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(

                        'title' => array(
                            'type' => 'text',
                            'label' => __('Tab Title', 'mo_theme'),
                            'description' => __('The title for the tab navigation.', 'mo_theme'),
                        ),

                        "text" => array(
                            "type" => "tinymce",
                            "description" => __("The tab content.", "mo_theme"),
                            "label" => __("Text", "mo_theme"),
                            "default" => __("Tab content goes here.", "mo_theme"),
                        ),
                    )
                ),

            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "title" => $instance["title"],
            'tabs' => !empty($instance['tabs']) ? $instance['tabs'] : array(),
        );
    }

}
siteorigin_widget_register("mo-tabs", __FILE__, "MO_Tabs_Widget");

