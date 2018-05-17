<?php
/**
 * @var $id
 * @var $style
 * @var $type
 * @var $animation
 * @var $control_nav
 * @var $direction_nav
 * @var $pause_on_hover
 * @var $pause_on_action
 * @var $loop
 * @var $slideshow
 * @var $slideshow_speed
 * @var $animation_speed
 * @var $elements
 */


$shortcode = '[responsive_slider id="' . $id . '" style="' . $style . '" type="' . ($type ? $type : 'flex') . '" animation="' . $animation . '" control_nav="' . ($control_nav ? 'true' : 'false') . '" direction_nav="' . ($direction_nav ? 'true' : 'false') . '" pause_on_hover="' . ($pause_on_hover ? 'true' : 'false') . '" pause_on_action="' . ($pause_on_action ? 'true' : 'false') . '" loop="' . ($loop ? 'true' : 'false') . '" slideshow="' . ($slideshow ? 'true' : 'false') . '" slideshow_speed="' . $slideshow_speed . '" animation_speed="' . $animation_speed . '" ]';

$shortcode .= '<ul>';

foreach ($elements as $element):

    $shortcode .= '<li>';

    $shortcode .= $element['text'];

    $shortcode .= '</li>';

endforeach;

$shortcode .= '</ul>';

$shortcode .= '[/responsive_slider]';

echo do_shortcode($shortcode);