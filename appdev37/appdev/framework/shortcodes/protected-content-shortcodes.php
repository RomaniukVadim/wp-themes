<?php

// Make notes visible only to content creators
function mo_private_content($atts, $content = null) {
    if (current_user_can('create_users'))
        return '<span class="private-content">' . $content . '</span>';
    return '';
}

add_shortcode('private', 'mo_private_content');

// Show content only to registered or logged in customers
function mo_protected_content_shortcode($atts, $content = null, $shortcode_name = "") {
    $default_content = 'You must be a registered user to view this content!';
    extract(shortcode_atts(array('alternate_content' => $default_content), $atts));
    if (current_user_can('read') && !empty($content) && !is_feed()) {
        return '<span class="protected-content">' . $content . '</span>';
    } else {
        return $alternate_content;
    }
}

add_shortcode('protected', 'mo_protected_content_shortcode');
?>