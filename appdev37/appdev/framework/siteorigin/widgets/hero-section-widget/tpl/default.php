<?php
/**
 * @var $id
 * @var $class
 * @var $style
 * @var $text
 * @var $background_color
 * @var $background_image
 * @var $parallax_background
 * @var $background_speed
 * @var $background_pattern
 */

$background_speed = $background_speed / 100;

$background_image = siteorigin_widgets_get_attachment_image_src( $background_image, 'full');

if (!empty ($background_image))
    $background_image = $background_image[0];

$background_pattern = siteorigin_widgets_get_attachment_image_src( $background_pattern, 'full');

if (!empty ($background_pattern))
    $background_pattern = $background_pattern[0];

$shortcode = '[segment id="' . $id . '" class="' . $class . '" style="' . $style . '" background_color="' . $background_color . '" background_image="' . $background_image . '" parallax_background="' . ($parallax_background ? 'true' : 'false') . '" background_speed="' . $background_speed . '" background_pattern="' . $background_pattern . '"]';

$shortcode .= $text;

$shortcode .= '[/segment]';

echo do_shortcode($shortcode);