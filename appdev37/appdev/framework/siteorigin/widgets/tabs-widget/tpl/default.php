<?php
/**
 * @var $tabs
 */


$shortcode = '[tabgroup]';

foreach ($tabs as $tab):

    $shortcode .= '[tab title="' . $tab['title'] . '" ]';

    $shortcode .= $tab['text'];

    $shortcode .= '[/tab]';

endforeach;

$shortcode .= '[/tabgroup]';

echo do_shortcode($shortcode);