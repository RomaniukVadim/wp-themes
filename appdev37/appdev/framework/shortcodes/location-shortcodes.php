<?php

//Google Maps Shortcode - http://digwp.com/2010/01/google-maps-shortcode/
function fn_googleMaps($atts, $content = null)
{
    extract(shortcode_atts(array(
        "width" => '640',
        "height" => '480',
        "src" => '',
        "link" => 'true'
    ), $atts));

    $output = '<div class="googlemap"><iframe width="' . $width . '" height="' . $height . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $src . '&amp;output=embed"></iframe></div>';
    if ($link == 'true')
        $output .= '<br /><small><a href=' . $src . '" style="text-align:left">View Larger Map</a></small>';

    return $output;
}

add_shortcode("googlemap", "fn_googleMaps");