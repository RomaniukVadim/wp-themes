<?php
/**
 * @var $class
 * @var $mail_to
 * @var $phone
 * @var $web_url
 * @var $subject
 * @var $button_color
 */


echo do_shortcode('[contact_form class="' . $class . '" mail_to="' . $mail_to . '" phone="' . ($phone ? 'true' : 'false') . '" web_url="' . ($web_url ? 'true' : 'false') . '" subject="' . ($subject ? 'true' : 'false') . '" button_color="' . $button_color . '" ]');