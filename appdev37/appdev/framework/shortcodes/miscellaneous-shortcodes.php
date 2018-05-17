<?php


function mo_service_box1_shortcode($atts, $content = null, $shortcode_name = "") {
    extract(shortcode_atts(array(
        'title' => '',
        'link_url' => '',
        'hover_image_url' => '',
        'image_url' => ''
    ), $atts));

    $output = '<div class="service-box1">';

    $output .= '<a href="' . $link_url . '" target="_self">';
    $output .= '<div class="service-img-wrap">';
    $output .= '<img  class="replacer" src="' . $hover_image_url . '" alt="' . $title . '">';
    $output .= '<img  class="hideOnHover" src="' . $image_url . '" alt="' . $title . '">';
    $output .= '</div>';
    $output .= '<h3 class="title">' . $title . '</h3>';
    $output .= $content;
    $output .= '<div class="folded-edge"></div>';
    $output .= '</a>';
    $output .= '</div>';

    return $output;
}

add_shortcode('service_box1', 'mo_service_box1_shortcode');

function mo_service_box2_shortcode($atts, $content = null, $shortcode_name = "") {
    extract(shortcode_atts(array(
        'wrapper_class' => '',
        'title' => '',
        'link_url' => '',
        'hover_image_url' => '',
        'separator' => null,
        'image_url' => ''
    ), $atts));

    $output = '<div class="service-box2 ' . $wrapper_class . '">';

    $output .= '<div class="service-img-wrap">';
    $output .= '<a href="' . $link_url . '" target="_self">';
    $output .= '<img class="replacer" src="' . $hover_image_url . '" alt="' . $title . '">';
    $output .= '<img class="hideOnHover" src="' . $image_url . '" alt="' . $title . '">';
    $output .= '</a>';
    $output .= '</div>';
    $output .= '<h2 class="title"><a href="' . $link_url . '" title="' . $title . '">' . $title . '</a></h2>';
    if ($separator)
        $output .= '<div class="mini-separator"></div>';
    $output .= $content;
    $output .= '</div>';

    return $output;
}

add_shortcode('service_box2', 'mo_service_box2_shortcode');
