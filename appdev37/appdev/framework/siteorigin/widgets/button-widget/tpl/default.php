<?php
/**
 * @var $id
 * @var $style
 * @var $class
 * @var $color
 * @var $type
 * @var $href
 * @var $align
 * @var $target
 * @var $text
 */


echo do_shortcode('[button id="' . $id . '" style="' . $style . '" class="' . $class . '" color="' . $color . '" type="' . $type . '" href="' . $href . '" align="' . $align . '" link_target="' . ($target? '_blank': '_self') . '" ]'. $text . '[/button]');

