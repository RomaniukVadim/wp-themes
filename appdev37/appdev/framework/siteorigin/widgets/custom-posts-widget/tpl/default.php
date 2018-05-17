<?php
/**
 * @var $posts_query
 * @var $hide_thumbnail
 * @var $show_meta
 * @var $excerpt_count
 * @var $image_size
 */

$defaults = array(
    'loop' => null,
    'image_size' => 'thumbnail',
    'style' => null,
    'show_meta' => false,
    'excerpt_count' => 120,
    'hide_thumbnail' => false
);

$data_input = array(
    'image_size' => $image_size,
    'excerpt_count' => $excerpt_count,
    'show_meta' => ($show_meta ? 'true' : 'false'),
    'hide_thumbnail' => ($hide_thumbnail ? 'true' : 'false')

);
$data_input['query_args'] = siteorigin_widget_post_selector_process_query($posts_query);

$post_list_args = array_merge($defaults, $data_input);

echo mo_get_thumbnail_post_list($post_list_args);