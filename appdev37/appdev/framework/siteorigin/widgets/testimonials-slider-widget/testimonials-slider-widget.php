<?php

/*
Widget Name: Testimonials Slider
Description: Display a slider of testimonial posts created in the Testimonials tab of the WordPress Admin
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Testimonials_Slider_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-testimonials-slider",
            __("Testimonials Slider", "mo_theme"),
            array(
                "description" => __("Display a slider of testimonial posts created in the Testimonials tab of the WordPress Admin. ", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "post_count" => array(
                    "type" => "text",
                    "description" => __("The number of testimonials to be displayed. By default displays all of the custom posts entered as testimonial in the Testimonials tab of the WordPress Admin (optional).", "mo_theme"),
                    "label" => __("Post count", "mo_theme"),
                    "default" => "",
                ),
                "testimonial_ids" => array(
                    "type" => "text",
                    "description" => __("A comma separated post ids of the Testimonial custom post types created in the Testimonials tab of the WordPress Admin. Helps to filter the testimonials for display (optional). Example: 234,235,236", "mo_theme"),
                    "label" => __("Testimonial ids", "mo_theme"),
                    "default" => "",
                ),


                'settings' => array(
                    'type' => 'section',
                    'label' => __('Slider Settings', 'livemesh-so-widgets'),
                    'fields' => array(
                        "animation" => array(
                            "type" => "select",
                            "description" => __("Select your animation type, fade or slide.", "mo_theme"),
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
            "post_count" => $instance["post_count"],

            "testimonial_ids" => $instance["testimonial_ids"],

            "animation" => $instance["settings"]["animation"],
            "control_nav" => $instance["settings"]["control_nav"],
            "direction_nav" => $instance["settings"]["direction_nav"],
            "pause_on_hover" => $instance["settings"]["pause_on_hover"],
            "pause_on_action" => $instance["settings"]["pause_on_action"],
            "slideshow_speed" => $instance["settings"]["slideshow_speed"],
            "animation_speed" => $instance["settings"]["animation_speed"],
        );
    }

}

siteorigin_widget_register("mo-testimonials-slider", __FILE__, "MO_Testimonials_Slider_Widget");

