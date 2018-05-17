<?php
/**
 * @var $post_count
 * @var $testimonial_ids
 * @var $animation
 * @var $control_nav
 * @var $direction_nav
 * @var $pause_on_hover
 * @var $pause_on_action
 * @var $slideshow_speed
 * @var $animation_speed
 */


$shortcode = '[responsive_slider type="testimonials2" animation="' . $animation . '" control_nav="' . ($control_nav ? 'true' : 'false') . '" direction_nav="' . ($direction_nav ? 'true' : 'false') . '" pause_on_hover="' . ($pause_on_hover ? 'true' : 'false') . '" pause_on_action="' . ($pause_on_action ? 'true' : 'false') . '" slideshow_speed="' . $slideshow_speed . '" animation_speed="' . $animation_speed . '" ]';

$shortcode .= '[testimonials post_count="' . $post_count . '" testimonial_ids="' . $testimonial_ids . '"]';

$shortcode .= '[/responsive_slider]';

echo do_shortcode($shortcode);