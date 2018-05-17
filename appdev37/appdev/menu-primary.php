<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package Appdev
 * @subpackage Template
 */

$custom_page_menu = get_post_meta(get_queried_object_id(), 'mo_custom_primary_navigation_menu', true);

echo '<div id="primary-menu" class="dropdown-menu-wrap clearfix">';

if (!empty($custom_page_menu) && $custom_page_menu !== 'default') {

    wp_nav_menu(array(
        'menu' => $custom_page_menu,
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'menu clearfix',
        'fallback_cb' => false
    ));

}
else {
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'menu clearfix',
        'fallback_cb' => false
    ));

}

echo '</div><!-- #primary-menu -->';