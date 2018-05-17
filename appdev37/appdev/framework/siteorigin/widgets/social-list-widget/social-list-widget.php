<?php

/*
Widget Name: Social List
Description: Display a list of social icons with links to various social network pages.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


class MO_Social_List_Widget extends SiteOrigin_Widget {
    function __construct() {
        parent::__construct(
            "mo-social-list",
            __("Social List", "mo_theme"),
            array(
                "description" => __("Display a list of social icons with links to various social network pages.", "mo_theme"),
                "panels_icon" => "dashicons dashicons-minus",
            ),
            array(),
            array(
                "widget_title" => array(
                    "type" => "text",
                    "label" => __("Title", "mo_theme"),
                ),
                "googleplus_url" => array(
                    "type" => "text",
                    "description" => __("The URL for the Google Plus page.", "mo_theme"),
                    "label" => __("Googleplus URL", "mo_theme"),
                    "default" => __("http://plus.google.com", "mo_theme"),
                ),
                "facebook_url" => array(
                    "type" => "text",
                    "description" => __("The URL of the Facebook page.", "mo_theme"),
                    "label" => __("Facebook URL", "mo_theme"),
                    "default" => __("http://www.facebook.com", "mo_theme"),
                ),
                "twitter_url" => array(
                    "type" => "text",
                    "description" => __("The URL of the Twitter account.", "mo_theme"),
                    "label" => __("Twitter URL", "mo_theme"),
                    "default" => __("http://www.twitter.com", "mo_theme"),
                ),
                "youtube_url" => array(
                    "type" => "text",
                    "description" => __("The URL for the YouTube channel.", "mo_theme"),
                    "label" => __("YouTube URL", "mo_theme"),
                    "default" => __("http://www.youtube.com/", "mo_theme"),
                ),
                "linkedin_url" => array(
                    "type" => "text",
                    "description" => __("The URL for the LinkedIn profile.", "mo_theme"),
                    "label" => __("LinkedIn URL", "mo_theme"),
                    "default" => __("http://www.linkedin.com", "mo_theme"),
                ),
                "flickr_url" => array(
                    "type" => "text",
                    "description" => __("The URL of the Flickr page.", "mo_theme"),
                    "label" => __("Flickr URL", "mo_theme"),
                    "default" => __("http://flickr.com", "mo_theme"),
                ),
                "include_rss" => array(
                    "type" => "checkbox",
                    "description" => __("Include RSS feed URL. ", "mo_theme"),
                    "label" => __("Include rss", "mo_theme"),
                    "default" => false,
                ),
            )
        );
    }

    function get_template_variables($instance, $args) {
        return array(
            "googleplus_url" => $instance["googleplus_url"],
            "facebook_url" => $instance["facebook_url"],
            "twitter_url" => $instance["twitter_url"],
            "youtube_url" => $instance["youtube_url"],
            "linkedin_url" => $instance["linkedin_url"],
            "flickr_url" => $instance["flickr_url"],
            "include_rss" => $instance["include_rss"],
        );
    }

}
siteorigin_widget_register("mo-social-list", __FILE__, "MO_Social_List_Widget");

