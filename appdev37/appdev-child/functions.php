<?php

/**
 * @package Appdev Child Theme
 */

/**
 * Load the child theme styles after the parent ones
 */
function mo_child_theme_style() {

    // Child theme styles
    wp_enqueue_style('style-child-theme', get_stylesheet_directory_uri() . '/style.css', array('style-theme', 'style-plugins', 'style-custom'));

}

add_action('wp_enqueue_scripts', 'mo_child_theme_style');

$shortcodes_path = get_stylesheet_directory() . '/framework/shortcodes/';

include_once($shortcodes_path . 'contact-shortcodes.php');

?>

