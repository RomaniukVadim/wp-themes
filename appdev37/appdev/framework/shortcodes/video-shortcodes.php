<?php
/* HTML5 Audio Shortcode -

Displays a HTML5 audio clip with controls.

Usage:

[html5_audio ogg_url="http://mydomain.com/song.ogg" mp3_url="http://mydomain.com/song.mp3" ]

Parameters -

ogg_url - The URL of the audio clip uploaded in OGG format.
mp3_url - The URL of the audio clip uploaded in MP3 format.

*/
function mo_html5_audio_shortcode($atts, $content = null, $code = "") {

    extract(shortcode_atts(array('mp3_url' => '', 'ogg_url' => ''), $atts));


    if (!empty($mp3_url) || !empty($ogg_url)) {
        return <<<HTML
<div class="video-box">
<audio controls="controls">
  <source src="{$ogg_url}" type="audio/ogg" />
  <source src="{$mp3_url}" type="audio/mp3" />
  Your browser does not support the HTML5 audio. Do upgrade. 
</audio>
</div>
HTML;
    }
}

add_shortcode('html5_audio', 'mo_html5_audio_shortcode');

function mo_youtube_video_shortcode($atts, $content = null, $code = "") {

    extract(shortcode_atts(array('clip_id' => '', 'height' => false, 'width' => false, 'hd' => false, 'align' => 'center', 'style' => '', 'parent_selector' => '#content'), $atts));

    $output = '';

    if ($height && !$width)
        $width = intval($height * 16 / 9);
    if (!$height && $width)
        $height = intval($width * 9 / 16);

    if (!$height && !$width) {

        $height = mo_get_theme_option('mo_youtube_height', 480);
        $width = mo_get_theme_option('mo_youtube_width', 640);
    }

    if (!empty($style))
        $style = ' style="' . $style . '"';

    if (!empty($clip_id))
        $output = '<div class="video-box' . ' align' . $align . '"' . $style . '><iframe title="YouTube video player" parent-selector=' . $parent_selector . ' width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $clip_id . '?rel=0&amp;' . ($hd ? '?hd=1' : '') . '" frameborder="0" allowfullscreen></iframe></div>';

    return $output;
}

add_shortcode('youtube_video', 'mo_youtube_video_shortcode');

function mo_vimeo_video_shortcode($atts, $content = null, $code = "") {

    extract(shortcode_atts(array('clip_id' => '', 'height' => false, 'width' => false, 'hd' => false, 'align' => 'center', 'style' => '', 'parent_selector' => '#content'), $atts));

    if ($height && !$width)
        $width = intval($height * 16 / 9);
    if (!$height && $width)
        $height = intval($width * 9 / 16);

    if (!$height && !$width) {

        $height = mo_get_theme_option('mo_vimeo_height', 225);
        $width = mo_get_theme_option('mo_vimeo_width', 400);
    }

    if (!empty($style))
        $style = ' style="' . $style . '"';

    if (!empty($clip_id))
        $out = '<div class="video-box' . ' align' . $align . '"' . $style . '><iframe parent-selector=' . $parent_selector . ' width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $clip_id . '?byline=0&amp;portrait=0" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe></div>';

    return $out;
}

add_shortcode('vimeo_video', 'mo_vimeo_video_shortcode');


function mo_dailymotion_video_shortcode($atts, $content = null, $code = "") {
    global $mo_theme;

    extract(shortcode_atts(array('clip_id' => '', 'height' => false, 'width' => false, 'theme' => 'none'), $atts));

    if ($height && !$width)
        $width = intval($height * 16 / 9);
    if (!$height && $width)
        $height = intval($width * 9 / 16);

    if (!$height && !$width) {

        $height = mo_get_theme_option('mo_dailymotion_height', 360);
        $width = mo_get_theme_option('mo_dailymotion_width', 480);
    }

    if (!empty($clip_id))
        $out = '<div class="video-box"><iframe width="' . $width . '" height="' . $height . '" src="http://www.dailymotion.com/video/' . $clip_id . '" frameborder="0"></iframe></div>';

    return $out;

}

add_shortcode('dailymotion_video', 'mo_dailymotion_video_shortcode');

function mo_flash_video_shortcode($atts, $content = null, $code = "") {
    extract(shortcode_atts(array('video_url' => '', 'width' => false, 'height' => false, 'play' => false), $atts));

    if ($height && !$width)
        $width = intval($height * 16 / 9);
    if (!$height && $width)
        $height = intval($width * 9 / 16);

    if (!$height && !$width) {
        $height = mo_get_theme_option('mo_flash_height', 360);
        $width = mo_get_theme_option('mo_flash_width', 480);
    }

    $play_video = $play ? 'true' : 'false';

    if (!empty($video_url)) {
        return <<<HTML
<div class="video-box">
<object width="{$width}" height="{$height}">
    <param name="movie" value="{$video_url}" />
    <param name="quality" value="high">
    <param name="allowFullScreen" value="true" />
    <param name="allowscriptaccess" value="always" />
    <param name="play" value="{$play_video}"/>
    <param name="wmode" value="transparent" />
    <embed type="application/x-shockwave-flash" src="{$video_url}" pluginspage="http://get.adobe.com/flashplayer/" width="{$width}" height="{$height}" wmode="direct" allowfullscreen="true" allowscriptaccess="always"></embed>
</object>
</div>
HTML;
    }
}

add_shortcode('flash_video', 'mo_flash_video_shortcode');



?>