<?php

/*
Widget Name: Smartphone Slider
Description: Create a smartphone slider
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_SmartPhone_Slider_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-smartphone-slider",
            __("Smartphone Slider", "mo_theme"),
            array(
                "description" => __("Create a image slider part of a container that looks like a smartphone", "mo_theme"),
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
                    "description" => __("The ID for the slider container DIV element", "mo_theme"),
                    "label" => __("ID", "mo_theme"),
                    "default" => "",
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __("The inline CSS applied to the slider container DIV element", "mo_theme"),
                    "label" => __("Style", "mo_theme"),
                    "default" => "",
                ),
                "device" => array(
                    "type" => "select",
                    "description" => __("The device type for the slider.", "mo_theme"),
                    "label" => __("Device", "mo_theme"),
                    "options" => array(
                        "iphone" => __("iPhone", "mo_theme"),
                        "iphone7gold" => __("iPhone 7 Gold", "mo_theme"),
                        "iphone7rosegold" => __("iPhone 7 Rose Gold", "mo_theme"),
                        "iphone7silver" => __("iPhone 7 Silver", "mo_theme"),
                        "iphone7black" => __("iPhone 7 Black", "mo_theme"),
                        "iphone7jetblack" => __("iPhone 7 Jet Black", "mo_theme"),
                        "googlepixelsilver" => __("Google Pixel Silver", "mo_theme"),
                        "googlepixelblue" => __("Google Pixel Blue", "mo_theme"),
                        "googlepixelblack" => __("Google Pixel Black", "mo_theme"),
                        "galaxys7" => __("Samsung Galaxy S7", "mo_theme"),
                        "nexus6p" => __("Huawei Nexus 6p", "mo_theme"),
                        "galaxys4" => __("Samsung Galaxy S4", "mo_theme"),
                        "htcone" => __("HTC One", "mo_theme"),
                    ),
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array('device')
                    ),
                ),

                'slides' => array(
                    'type' => 'repeater',
                    'label' => __('Image Slides', 'mo_theme'),
                    'item_name' => __('Slide', 'mo_theme'),
                    'item_label' => array(
                        'selector' => "[id*='slides-name']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'name' => array(
                            'type' => 'text',
                            'label' => __('Name', 'mo_theme'),
                            'description' => __('The title to identify the image slide.', 'mo_theme'),
                        ),
                        'slider_image' => array(
                            'type' => 'media',
                            'library' => 'image',
                            'label' => __('Slide Image', 'mo_theme'),
                            'fallback' => true,
                        ),
                    ),
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Slider Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        "animation" => array(
                            "type" => "select",
                            "description" => __("Select your animation type", "mo_theme"),
                            "label" => __("Animation", "mo_theme"),
                            "options" => array(
                                "slide" => __("Slide", "mo_theme"),
                                "fade" => __("Fade", "mo_theme"),
                            ),
                            "default" => "slide",
                        ),
                        "direction_nav" => array(
                            "type" => "checkbox",
                            "description" => __("Create navigation for previous/next navigation?", "mo_theme"),
                            "label" => __("Direction navigation?", "mo_theme"),
                            "default" => true,
                        ),
                        "control_nav" => array(
                            "type" => "checkbox",
                            "description" => __("Create navigation for paging control of each slide? ", "mo_theme"),
                            "label" => __("Control navigation?", "mo_theme"),
                            "default" => false,
                        ),
                        "animation_speed" => array(
                            "type" => "number",
                            "description" => __("Set the speed of animations, in milliseconds.", "mo_theme"),
                            "label" => __("Animation speed", "mo_theme"),
                            "default" => 600,
                        ),
                        "slideshow_speed" => array(
                            "type" => "number",
                            "description" => __("Set the speed of the slideshow cycling, in milliseconds", "mo_theme"),
                            "label" => __("Slideshow speed", "mo_theme"),
                            "default" => 5000,
                        ),
                        "pause_on_action" => array(
                            "type" => "checkbox",
                            "description" => __("Pause the slideshow when interacting with control elements", "mo_theme"),
                            "label" => __("Pause on action?", "mo_theme"),
                            "default" => true,
                        ),
                        "pause_on_hover" => array(
                            "type" => "checkbox",
                            "description" => __("Pause the slideshow when hovering over slider, then resume when no longer hovering.", "mo_theme"),
                            "label" => __("Pause on hover?", "mo_theme"),
                            "default" => true,
                        ),
                        "easing" => array(
                            "type" => "select",
                            "description" => __("Determines the easing method used in jQuery transitions", "mo_theme"),
                            "label" => __("Easing", "mo_theme"),
                            "options" => array(
                                "swing" => __("Swing", "mo_theme"),
                                "linear" => __("Linear", "mo_theme"),
                            ),
                            "default" => "swing",
                        ),
                    )
                )
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "style" => $instance["style"],
            "id" => $instance["id"],
            "device" => $instance["device"],

            "animation" => $instance["settings"]["animation"],
            "direction_nav" => $instance["settings"]["direction_nav"],
            "control_nav" => $instance["settings"]["control_nav"],
            "animation_speed" => $instance["settings"]["animation_speed"],
            "slideshow_speed" => $instance["settings"]["slideshow_speed"],
            "pause_on_action" => $instance["settings"]["pause_on_action"],
            "pause_on_hover" => $instance["settings"]["pause_on_hover"],
            "easing" => $instance["settings"]["easing"],

            "slides" => !empty($instance["slides"]) ? $instance["slides"] : array()
        );
    }

}

siteorigin_widget_register("mo-smartphone-slider", __FILE__, "MO_SmartPhone_Slider_Widget");

