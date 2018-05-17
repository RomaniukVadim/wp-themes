<?php

/* Recent Posts Shortcode -

Displays the most recent blog posts sorted by date of posting.

Usage:

[recent_posts post_count=5 hide_thumbnail="false" show_meta="false" excerpt_count=70 image_size="small"]

Parameters -

post_count - 5 (number) - Number of posts to display
hide_thumbnail false (boolean) - Display thumbnail or hide the same.
show_meta - false (boolean) Display meta information like the author, date of publishing and number of comments.
excerpt_count - 50 (number) The total number of characters of excerpt to display.
image_size - small (string) - Can be mini, small, medium, large, full, square.

*/
function mo_recent_posts_shortcode($atts) {

    $args = shortcode_atts(array(
        'post_count' => '5',
        'hide_thumbnail' => false,
        'show_meta' => false,
        'excerpt_count' => '50',
        'image_size' => 'small'
    ), $atts);
    extract($args);

    $args['loop'] = new WP_Query(array('posts_per_page' => $post_count, 'ignore_sticky_posts' => 1));

    $output = mo_get_thumbnail_post_list($args);

    return $output;
}

add_shortcode('recent_posts', 'mo_recent_posts_shortcode');


/* Popular Posts Shortcode -

Displays the most popular blog posts. Popularity is based on by number of comments posted on the blog post. The higher the number of comments, the more popular the blog post.

Usage:

[popular_posts post_count=5 hide_thumbnail="false" show_meta="false" excerpt_count=70 image_size="small"]

Parameters -

post_count - 5 (number) - Number of posts to display
hide_thumbnail false (boolean) - Display thumbnail or hide the same.
show_meta - false (boolean) Display meta information like the author, date of publishing and number of comments.
excerpt_count - 50 (number) The total number of characters of excerpt to display.
image_size - small (string) - Can be mini, small, medium, large, full, square.

*/
function mo_popular_posts_shortcode($atts) {

    $args = shortcode_atts(array(
        'post_count' => '5',
        'hide_thumbnail' => false,
        'show_meta' => false,
        'excerpt_count' => '50',
        'image_size' => 'small'
    ), $atts);
    extract($args);

    $args['loop'] = new WP_Query(array('orderby' => 'comment_count', 'posts_per_page' => $post_count, 'ignore_sticky_posts' => 1));

    $output = mo_get_thumbnail_post_list($args);

    return $output;
}

add_shortcode('popular_posts', 'mo_popular_posts_shortcode');

/* Category Posts Shortcode -

Displays the blog posts belonging to one or more categories.

Usage:

[category_posts category_slugs="nature,lifestyle" post_count=5 hide_thumbnail="false" show_meta="false" excerpt_count=70 image_size="small"]

Parameters -

category_slugs - (string) The comma separated list of category slugs whose posts need to be displayed.
post_count - 5 (number) - Number of posts to display
hide_thumbnail false (boolean) - Display thumbnail or hide the same.
show_meta - false (boolean) Display meta information like the author, date of publishing and number of comments.
excerpt_count - 50 (number) The total number of characters of excerpt to display.
image_size - small (string) - Can be mini, small, medium, large, full, square.

*/
function mo_category_posts_shortcode($atts) {

    $args = shortcode_atts(array(
        'category_slugs' => '',
        'post_count' => '5',
        'hide_thumbnail' => false,
        'show_meta' => false,
        'excerpt_count' => '50',
        'image_size' => 'small'
    ), $atts);
    extract($args);

    $args['loop'] = new WP_Query(array('category_name' => $category_slugs, 'posts_per_page' => $post_count, 'ignore_sticky_posts' => 1));

    $output = mo_get_thumbnail_post_list($args);

    return $output;
}

add_shortcode('category_posts', 'mo_category_posts_shortcode');

/* Tag Posts Shortcode -

Displays the blog posts with one or more tags specified as a parameter to the shortcode.

Usage:

[tag_posts tag_slugs="growth,motivation" post_count=5 hide_thumbnail="false" show_meta="false" excerpt_count=70 image_size="small"]

Parameters -

tag_slugs - (string) The comma separated list of tag slugs whose posts need to be displayed.
post_count - 5 (number) - Number of posts to display
hide_thumbnail false (boolean) - Display thumbnail or hide the same.
show_meta - false (boolean) Display meta information like the author, date of publishing and number of comments.
excerpt_count - 50 (number) The total number of characters of excerpt to display.
image_size - small (string) - Can be mini, small, medium, large, full, square.

*/
function mo_tag_posts_shortcode($atts) {

    $args = shortcode_atts(array(
        'tag_slugs' => '',
        'post_count' => '5',
        'hide_thumbnail' => false,
        'show_meta' => false,
        'excerpt_count' => '50',
        'image_size' => 'small'
    ), $atts);
    extract($args);

    $args['loop'] = new WP_Query(array('tag' => $tag_slugs, 'posts_per_page' => $post_count, 'ignore_sticky_posts' => 1));

    $output = mo_get_thumbnail_post_list($args);

    return $output;
}

add_shortcode('tag_posts', 'mo_tag_posts_shortcode');

/* Display Posts from the custom taxonomy specified */
/* TODO: Support for multiple custom taxonomies using string matching */

function mo_custom_taxonomy_posts_shortcode($atts) {
    $args = shortcode_atts(array(
        'taxonomy_slug' => '',
        'taxonomy_term' => '',
        'post_count' => '5',
        'hide_thumbnail' => false,
        'show_meta' => false,
        'excerpt_count' => '50',
        'image_size' => 'small'
    ), $atts);
    extract($args);


    $taxonomy_map = array($taxonomy_slug => $taxonomy_term);

    $args['loop'] = new WP_Query(array('tax' => $taxonomy_wrap, 'posts_per_page' => $post_count, 'ignore_sticky_posts' => 1));

    $output = mo_get_thumbnail_post_list($args);

    return $output;
}

add_shortcode('custom_taxonomy_posts', 'mo_custom_taxonomy_posts_shortcode');

/* Display content of one or more custom post types */

function mo_show_custom_post_types_shortcode($atts) {
    $args = shortcode_atts(array(
        'post_types' => 'post',
        'post_count' => '5',
        'hide_thumbnail' => false,
        'show_meta' => false,
        'excerpt_count' => '50',
        'image_size' => 'small'
    ), $atts);
    extract($args);

    $custom_post_types = explode(",", $post_types); // return me an array of post types

    $args['loop'] = new WP_Query(array('post_type' => $custom_post_types, 'posts_per_page' => $post_count, 'ignore_sticky_posts' => 1));

    $output = mo_get_thumbnail_post_list($args);

    return $output;
}

add_shortcode('show_custom_post_types', 'mo_show_custom_post_types_shortcode');

/* Post Snippets Shortcode - See https://www.livemeshthemes.com/austin/portfolio-shortcodes/ ‎for examples.

Displays the post snippets of blog posts or another custom post types with featured images. The post snippets are displayed in a grid fashion like a typical portfolio page or grid based blog page.

The number_of_columns parameter helps decide on the number of columns of posts/custom post types to display for each row displayed. Total number of posts displayed is derived from post_count parameter value.

This shortcode is quite powerful when used with custom post types and with filters based on custom taxonomy/terms specified as arguments.

Usage:

[show_post_snippets layout_class="rounded-images" post_type="portfolio" number_of_columns=3 post_count=6 image_size='medium' excerpt_count=100 display_title="true" display_summary="true" show_excerpt="true" hide_thumbnail="false"]

With taxonomy and terms specified, the portfolio items can be drawn from a specific portfolio category as shown below.

[show_post_snippets number_of_columns=3 post_count=6 image_size='large' terms="inspiration,technology" taxonomy="portfolio_category" post_type="portfolio"]

Parameters -

post_type -  (string) The custom post type whose posts need to be displayed. Examples include post, portfolio, team etc.
post_count - 4 (number) - Number of posts to display
image_size - medium (string) - Can be mini, small, medium, large, full, square.
title - (string) Display a header title for the post snippets.
layout_class - (string) The CSS class to be set for the list element (UL) displaying the post snippets. Useful if you need to do some custom styling of our own (rounded, hexagon images etc.) for the displayed items.
number_of_columns - 4 (number) - The number of columns to display per row of the post snippets
display_title - false (boolean) - Specify if the title of the post or custom post type needs to be displayed below the featured image
display_summary - false (boolean) - Specify if the excerpt or summary content of the post/custom post type needs to be displayed below the featured image thumbnail.
show_excerpt - true (boolean) - Display excerpt for the post/custom post type. Has no effect if display_summary is set to false. If show_excerpt is set to false and display_summary is set to true, the content of the post is displayed truncated by the WordPress <!--more--> tag. If more tag is not specified, the entire post content is displayed.
excerpt_count - 100 (number) - Applicable only to excerpts. The excerpt displayed is truncated to the number of characters specified with this parameter.
hide_thumbnail false (boolean) - Display thumbnail image or hide the same.
show_meta - false (boolean) Display meta information like the author, date of publishing and number of comments.
excerpt_count - 100 (number) The total number of characters of excerpt to display.
taxonomy - (string) Custom taxonomy to be used for filtering the posts/custom post types displayed.
terms - (string) The terms of taxonomy specified.
*/

function mo_show_post_snippets_shortcode($atts) {
    $args = shortcode_atts(array(
        'post_type' => 'post',
        'post_count' => 4,
        'post_ids' => false,
        'image_size' => 'medium',
        'title' => null,
        'layout_class' => '',
        'excerpt_count' => 100,
        'number_of_columns' => 4,
        'show_meta' => true,
        'display_title' => true,
        'display_summary' => true,
        'show_excerpt' => true,
        'hide_thumbnail' => false,
        'row_line_break' => true,
        'terms' => '',
        'taxonamy' => 'category', /* For backward compatibility */
        'taxonomy' => 'category',
        'enable_sorting' => false,
        'posts_query' => ''
    ), $atts);

    $output = mo_get_post_snippets($args);

    return $output;

}

add_shortcode('show_post_snippets', 'mo_show_post_snippets_shortcode');

function mo_show_rounded_post_snippets_shortcode($atts) {
    $args = shortcode_atts(array(
        'post_type' => 'portfolio',
        'post_count' => 3,
        'number_of_columns' => 3,
        'title' => null,
        'terms' => '',
        'taxonamy' => 'category',
        'taxonomy' => 'category'
    ), $atts);

    /* Set default values for variables */
    $args['layout_class'] = 'rounded';
    $args['image_size'] = 'square';
    $args['show_meta'] = false;
    $args['excerpt_count'] = 0;
    $args['display_title'] = false;
    $args['display_summary'] = false;
    $args['show_excerpt'] = true;
    $args['hide_thumbnail'] = false;
    $args['row_line_break'] = true;

    $output = mo_get_post_snippets($args);

    return $output;

}

add_shortcode('show_rounded_post_snippets', 'mo_show_rounded_post_snippets_shortcode');

/* Show Portfolio shortcode -

Helps to display a portfolio page style display of portfolio items with JS powered portfolio category filter. Packed layout option is also available.

Usage:

[show_portfolio number_of_columns=4 post_count=12 image_size='small' filterable=true no_margin=true]

Parameters -

post_count - 9 (number) - Total number of portfolio items to display
number_of_columns - 3 (number) - The number of columns to display per row of the portfolio items
image_size - medium (string) - Can be mini, small, medium, large, full, square.
filterable - true (boolean) The portfolio items will be filterable based on portfolio categories if set to true.
*/
function mo_show_portfolio($atts) {

    $args = shortcode_atts(array(
        'number_of_columns' => 3,
        'image_size' => 'medium',
        'post_count' => 9,
        'filterable' => true
    ), $atts);

    $output = '<div id="portfolio-full-width">';

    $args['posts_per_page'] = $args['post_count'];

    $output .= mo_get_home_portfolio_content($args);

    $output .= '</div>';

    return $output;
}

add_shortcode('show_portfolio', 'mo_show_portfolio');




