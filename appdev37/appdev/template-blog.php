<?php

/**
 * Template Name: Blog
 *
 * This is the blog template. 
 *
 * @package Appdev
 * @subpackage Template
 */
get_header();

if (get_query_var('paged')) {
    $paged = get_query_var('paged');
}
else if (get_query_var('page')) {
    $paged = get_query_var('page');
}
else {
    $paged = 1;
}

$query = array('posts_per_page' => intval(get_option('posts_per_page')), 'ignore_sticky_posts' => 0, 'paged' => $paged);

mo_display_starter_content($query);

get_sidebar();

get_footer();  
?>