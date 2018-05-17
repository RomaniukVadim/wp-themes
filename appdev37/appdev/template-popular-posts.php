<?php
/**
 * Template Name: Popular Posts
 *
 *A custom page template for showing the most popular posts based on number of reader comments
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); 

$query = array( 'orderby' => 'comment_count',  'posts_per_page' => get_option( 'posts_per_page' ), 'ignore_sticky_posts' => 1, 'paged' => ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 ) );

mo_display_archive_content($query);

get_sidebar();

get_footer(); // Loads the footer.php template. 