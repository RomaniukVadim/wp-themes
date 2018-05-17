<?php
/**
 * @var $posts_query
 * @var $class
 * @var $image_size
 * @var $number_of_columns
 * @var $hide_thumbnail
 * @var $display_title
 * @var $display_summary
 * @var $show_excerpt
 * @var $excerpt_count
 * @var $show_meta
 */

$shortcode = '[show_post_snippets posts_query=\'' . $posts_query . '\' layout_class="' . $class . '" number_of_columns="' . $number_of_columns . '" image_size="' . $image_size . '" hide_thumbnail="' . ($hide_thumbnail ? 'true' : 'false')  . '" display_title="' . ($display_title ? 'true' : 'false')  . '" display_summary="' . ($display_summary ? 'true' : 'false')  . '" show_excerpt="' . ($show_excerpt ? 'true' : 'false')  . '" excerpt_count="' . $excerpt_count . '" show_meta="' . ($show_meta ? 'true' : 'false')  . '"]';

echo do_shortcode($shortcode);