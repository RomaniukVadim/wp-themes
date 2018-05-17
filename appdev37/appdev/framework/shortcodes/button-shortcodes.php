<?php

/* Button Shortcode -

Usage:

[button id="purchase-button" style="padding: 10px 20px;" color="green" type="rounded" size="large" href="http://targeturl.com" align="left" target="_blank"]Green Button[/button]

Parameters -

id - The element id (optional).
style - Inline CSS styling (optional)
class - Custom CSS class name (optional)
color - Color of the button. Available colors are black, blue, cyan, green, orange, pink, red, teal, theme and trans.
align - Alignment of the button and text alignment of the button title displayed.
type - Can be large, small or rounded.
href - The URL to which button should point to. The user is taken to this destination when the button is clicked.
target - The HTML anchor target. Can be _self (default) or _blank which opens a new window to the URL specified when the button is clicked.

*/

if (!function_exists('mo_button_shortcode')) {

    function mo_button_shortcode($atts, $content = null) {
        extract(shortcode_atts(
            array(
                'id' => false,
                'class' => '',
                'style' => null,
                'type' => '',
                'color' => '',
                'align' => false,
                'href' => '',
                'target' => '_self'),
            $atts));

        $color = ' ' . $color;
        if (!empty($type))
            $type = ' ' . $type;
        $button_text = trim($content);
        $id_text = $id ? ' id ="' . $id . '"' : '';
        $style = $style ? ' style="' . $style . '"' : '';

        $button_content = '<a' . $id_text . ' class= "button ' . $class . $color . $type . '"' . $style . ' href="' . $href . '" target="' . $target . '">' . $button_text . '</a>';
        if ($align)
            $button_content = '<div style="text-align:' . $align . ';float:' . $align . ';">' . $button_content . '</div>';
        return $button_content;
    }
}

add_shortcode('button', 'mo_button_shortcode');

if (!function_exists('mo_read_more_shortcode')) {

    /* Example -
     * [read_more text="Read More" href="#" align="left" target="_self" arrows=" >>"]
     */
    function mo_read_more_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
            'text' => __('Read More', 'mo_theme'),
            'href' => '',
            'style' => null,
            'id' => null,
            'arrows' => ' &rarr;',
            'target' => '_self'), $atts));

        $text = trim($text);

        $read_more_link = '<a href="' . $href . '" target="' . $target . '" title="' . $text . '">' . $text . $arrows . '</a>';
        $read_more_link = '<div ' . ($id ? 'id="' . $id . '"' : '') . ' class="read-more" ' . ($style ? ' style="' . $style . '"' : '') . '>' . $read_more_link . '</div>';
        return $read_more_link;
    }
}
add_shortcode('read_more', 'mo_read_more_shortcode');
?>