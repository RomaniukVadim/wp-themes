<?php

/*
Widget Name: Responsive Slider
Description: Create a touch friendly responsive slider of a collection of HTML content.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Responsive_Slider_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-responsive-slider",
            __("Responsive Slider", "mo_theme"),
            array(
                "description" => __("Create a touch friendly responsive slider of a collection of HTML content.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "id" => array(
                    "type" => "text",
                    "description" => __("The element id to be set for the slider container DIV element. (optional).", "wired_theme"),
                    "label" => __("Id", "wired_theme"),
                ),
                "style" => array(
                    "type" => "text",
                    "description" => __(" The inline CSS applied to the slider container DIV element.(optional)", "mo_theme"),
                    "label" => __("Style", "mo_theme"),
                    "default" => "",
                ),
                "type" => array(
                    "type" => "text",
                    "description" => __("Constructs and sets a unique CSS class for the slider. (optional).", "mo_theme"),
                    "label" => __("Type", "mo_theme"),
                    "default" => "flex",
                ),

                'elements' => array(
                    'type' => 'repeater',
                    'label' => __('HTML Elements', 'mo_theme'),
                    'item_name' => __('HTML Element', 'mo_theme'),
                    'item_label' => array(
                        'selector' => "[id*='elements-name']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'name' => array(
                            'type' => 'text',
                            'label' => __('Name', 'mo_theme'),
                            'description' => __('The title to identify the HTML element', 'mo_theme'),
                        ),

                        'text' => array(
                            'type' => 'tinymce',
                            'label' => __('HTML element', 'mo_theme'),
                            'description' => __('The HTML content for the slider item.', 'mo_theme'),
                        ),
                    )
                ),


                'settings' => array(
                    'type' => 'section',
                    'label' => __('Slider Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        "animation" => array(
                            "type" => "select",
                            "description" => __("Select your animation type.", "mo_theme"),
                            "label" => __("Animation", "mo_theme"),
                            "options" => array(
                                "slide" => __("Slide", "mo_theme"),
                                "fade" => __("Fade", "mo_theme"),
                            ),
                            "default" => "slide",
                        ),
                        "control_nav" => array(
                            "type" => "checkbox",
                            "description" => __("Create navigation for paging control of each slide?", "mo_theme"),
                            "label" => __("Control navigation?", "mo_theme"),
                            "default" => true,
                        ),
                        "direction_nav" => array(
                            "type" => "checkbox",
                            "description" => __("Create navigation for previous/next navigation?", "mo_theme"),
                            "label" => __("Direction navigation?", "mo_theme"),
                            "default" => false,
                        ),
                        "pause_on_hover" => array(
                            "type" => "checkbox",
                            "description" => __("Pause the slideshow when hovering over slider, then resume when no longer hovering.", "mo_theme"),
                            "label" => __("Pause on hover?", "mo_theme"),
                            "default" => true,
                        ),
                        "pause_on_action" => array(
                            "type" => "checkbox",
                            "description" => __("Pause the slideshow when interacting with control elements.", "mo_theme"),
                            "label" => __("Pause on action?", "mo_theme"),
                            "default" => true,
                        ),
                        "loop" => array(
                            "type" => "checkbox",
                            "description" => __("Should the animation loop?", "mo_theme"),
                            "label" => __("Loop", "mo_theme"),
                            "default" => true,
                        ),
                        "slideshow" => array(
                            "type" => "checkbox",
                            "description" => __("Animate slider automatically without user intervention?", "mo_theme"),
                            "label" => __("Slideshow", "mo_theme"),
                            "default" => true,
                        ),
                        "slideshow_speed" => array(
                            "type" => "number",
                            "description" => __("Set the speed of the slideshow cycling, in milliseconds", "mo_theme"),
                            "label" => __("Slideshow speed", "mo_theme"),
                            "default" => 5000,
                        ),
                        "animation_speed" => array(
                            "type" => "number",
                            "description" => __("Set the speed of animations, in milliseconds.", "mo_theme"),
                            "label" => __("Animation speed", "mo_theme"),
                            "default" => 600,
                        ),
                    )
                )
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "id" => $instance["id"],
            "style" => $instance["style"],
            "type" => $instance["type"],

            "animation" => $instance["settings"]["animation"],
            "control_nav" => $instance["settings"]["control_nav"],
            "direction_nav" => $instance["settings"]["direction_nav"],
            "pause_on_hover" => $instance["settings"]["pause_on_hover"],
            "pause_on_action" => $instance["settings"]["pause_on_action"],
            "loop" => $instance["settings"]["loop"],
            "slideshow" => $instance["settings"]["slideshow"],
            "slideshow_speed" => $instance["settings"]["slideshow_speed"],
            "animation_speed" => $instance["settings"]["animation_speed"],


            'elements' => !empty($instance['elements']) ? $instance['elements'] : array(),
        );
    }

}

siteorigin_widget_register("mo-responsive-slider", __FILE__, "MO_Responsive_Slider_Widget");

