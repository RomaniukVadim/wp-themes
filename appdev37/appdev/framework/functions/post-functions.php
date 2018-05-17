<?php
/*
 * Various utility functions required by theme defined here
 * 
 * @package Livemesh_Framework
 *
 */

if (!function_exists('mo_get_entry_title')) {
    function mo_get_entry_title() {
        global $post;

        if (is_front_page() && !is_home())
            $title = the_title('<h2 class="' . esc_attr($post->post_type) . '-title entry-title"><a href="' . get_permalink() . '"
                                                                                        title="' . get_the_title() . '"
                                                                                        rel="bookmark">', '</a></h2>',
                false);
        elseif (is_singular())
            $title = the_title('<h1 class="' . esc_attr($post->post_type) . '-title entry-title">', '</h1>', false);
        else
            $title = the_title('<h2 class="entry-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '"
                                               rel="bookmark">', '</a></h2>', false);

        /* If there's no post title, return a default title */
        if (empty($title)) {
            if (!is_singular()) {
                $title = '<h2 class="entry-title no-entry-title"><a href="' . get_permalink() . '" rel="bookmark">' . __('(Untitled)',
                        'mo_theme') . '</a></h2>';
            }
            else {
                $title = '<h1 class="entry-title no-entry-title">' . __('(Untitled)', 'mo_theme') . '</h1>';
            }
        }

        return $title;
    }
}

if (!function_exists('mo_entry_author')) {

    function mo_entry_author() {
        $author = '<span class="author vcard">' . __('Author: ', 'mo_theme') . '<a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author_meta('display_name')) . '">' . get_the_author_meta('display_name') . '</a></span>';
        return $author;
    }
}

if (!function_exists('mo_entry_published')) {

    function mo_entry_published($format = "M d, Y") {


        global $post;

        $post_id = $post->ID;

        $prefix = __('On: ', 'mo_theme');

        $link = '<span class="published">' . $prefix . '<a href="' . get_day_link(get_the_time(__('Y', 'mo_theme')), get_the_time(__('m', 'mo_theme')), get_the_time(__('d', 'mo_theme'))) . '" title="' . sprintf(get_the_time(esc_attr__('l, F, Y, g:i a', 'mo_theme'))) . '">' . '<span class="updated">' . get_the_time($format) . '</span>' . '</a></span>';

        return $link;

        $published = '<span class="published">' . $prefix . ' <abbr title="' . sprintf(get_the_time(esc_attr__('l, F, Y, g:i a', 'mo_theme'))) . '">' . sprintf(get_the_time($format)) . '</abbr></span>';

        return $published;
    }
}

if (!function_exists('mo_custom_entry_published')) {

    function mo_custom_entry_published() {

        $published = '<span class="published"><abbr title="' . sprintf(get_the_time(esc_attr__('l, F, Y, g:i a', 'mo_theme'))) . '"><span class="month">' . sprintf(get_the_time('M')) . '</span><span class="date">' . sprintf(get_the_time('d')) . '</span></abbr></span>';
        return $published;
    }
}

if (!function_exists('mo_entry_terms_list')) {

    function mo_entry_terms_list($taxonomy = 'category', $separator = ', ', $before = ' ', $after = ' ') {
        global $post;

        $output = '<span class="' . $taxonomy . '">';
        if ($taxonomy == 'post_tag')
            $output .= '';
        else
            $output .= __('Categories: ', 'mo_theme');
        $output .= get_the_term_list($post->ID, $taxonomy, $before, $separator, $after);
        $output .= '</span>';

        return $output;
    }
}

if (!function_exists('mo_entry_terms_text')) {

    function mo_entry_terms_text($taxonomy = 'category', $separator = ' , ') {
        global $post;

        $output = '';

        $terms = get_the_terms($post, $taxonomy);
        if (!empty($terms)) {
			foreach ($terms as $term)
				$term_names[] = $term->name;

			$output = implode($separator, $term_names);
        }

        return $output;
    }
}


if (!function_exists('mo_display_related_posts')) {
    function mo_display_related_posts($taxonomy) {

        ?>

        <div class="related-posts">

            <?php

            $args = array('posts_per_page' => 3);

            $posts = mo_related_posts_by_taxonomy(get_the_ID(), $taxonomy, $args);

            foreach ($posts as $post) {

                $post_id = $post->ID;

                echo '<div class="related-post">';

                mo_thumbnail(array('post_id' => $post_id, 'image_size' => 'small', 'wrapper' => false, 'image_alt' => get_the_title($post_id), 'size' => 'full'));

                echo '<h3 class="entry-title"><a href="' . get_permalink($post_id) . '" title="' . get_the_title($post_id) . '" rel="bookmark">' . get_the_title($post_id) . '</a></h3>';

                echo '</div>';
            }

            wp_reset_postdata();

            ?>

        </div><!-- .related-classes -->

    <?php
    }
}

if (!function_exists('mo_related_posts_by_taxonomy')) {

    function mo_related_posts_by_taxonomy($post_id, $taxonomy, $args = array()) {
        $terms = wp_get_object_terms($post_id, $taxonomy);

        //Pluck out the IDs to get an array of IDS
        $term_ids = wp_list_pluck($terms, 'term_id');

        //Query posts with tax_query. Choose in 'IN' if want to query posts with any of the terms
        //Choose 'AND' if you want to query for posts with all terms
        $args = wp_parse_args($args, array(
            'post_type' => get_post_type($post_id),
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $term_ids,
                    'operator' => 'IN' //Or 'AND' or 'NOT IN'
                )),
            'ignore_sticky_posts' => 1,
            'orderby' => 'rand',
            'post__not_in' => array($post_id)
        ));

        $posts = get_posts($args);

        // Return our results in query form
        return $posts;
    }

}

if (!function_exists('mo_get_post_snippets')) {

// Display grid style posts layout for portfolio or regular posts
    function mo_get_post_snippets($args) {
        global $mo_theme;

        $mo_theme->set_context('loop', 'portfolio'); // tells the thumbnail functions to prepare lightbox constructs for the image

        $output = mo_get_post_snippets_layout($args);

        $mo_theme->set_context('loop', null); //reset it

        return $output;

    }
}

if (!function_exists('mo_display_post_nuggets_grid_style')) {

    function mo_display_post_nuggets_grid_style($args) {

        /* Set the default arguments. */
        $defaults = array(
            'loop' => null,
            'number_of_columns' => 2,
            'image_size' => 'medium',
            'excerpt_count' => 120,
            'show_meta' => false,
            'style' => null
        );

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        /* Extract the array to allow easy use of variables. */
        extract($args);

        $style_class = mo_get_column_style($number_of_columns);

        if ($loop->have_posts()) :
            $post_count = 0;

            $first_row = true;
            $last_column = false;

            $style = ($style ? ' ' . $style : '');

            echo '<div class="post-list' . $style . '">';

            while ($loop->have_posts()) : $loop->the_post();

                if ($last_column) {
                    echo '<div class="start-row"></div>';
                    $first_row = false;
                }

                if (++$post_count % $number_of_columns == 0)
                    $last_column = true;
                else
                    $last_column = false;

                echo '<div class="' . $style_class . ' clearfix' . ($last_column ? ' last' : '') . '">';

                echo '<div class="' . join(' ', get_post_class()) . ($first_row ? ' first' : '') . '">';

                $thumbnail_exists = mo_thumbnail(array('image_size' => $image_size, 'wrapper' => true, 'size' => 'full'));

                echo '<div class="entry-text-wrap' . ($thumbnail_exists ? '' : ' nothumbnail') . '">';

                $before_title = '<div class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">';
                $after_title = '</a></div>';

                the_title($before_title, $after_title);

                if ($show_meta)
                    echo '<div class="byline">' . mo_entry_published() . mo_entry_comments_number() . '</div>';

                if ($excerpt_count != 0) {
                    echo '<div class="entry-summary">';
                    echo mo_truncate_string(get_the_excerpt(), $excerpt_count);
                    echo '</div><!-- .entry-summary -->';
                }

                echo '</div><!-- entry-text-wrap -->';

                echo '</div><!-- .hentry -->';

                echo '</div> <!-- .column-class -->';

            endwhile;

            echo '</div> <!-- post-list -->';

            echo '<div class="clear"></div>';

        endif;

        wp_reset_postdata(); // Right placement to help not lose context information
    }
}

if (!function_exists('mo_get_post_image_size')) {

    function mo_get_post_image_size($size_name) {
        // Translate user language to to theme specific image size
        if ($size_name == "small")
            $size_name = "mini";
        elseif ($size_name == "medium")
            $size_name = "small";
        else
            $size_name = "mini";

        return $size_name;
    }
}

if (!function_exists('mo_get_thumbnail_post_list')) {
    function mo_get_thumbnail_post_list($args) {

        /* Set the default arguments. */
        $defaults = array(
            'loop' => null,
            'image_size' => 'small',
            'style' => null,
            'show_meta' => false,
            'excerpt_count' => 120,
            'hide_thumbnail' => false
        );

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        /* Extract the array to allow easy use of variables. */
        extract($args);

        if (!$loop)
            $loop = new WP_Query($query_args);

        if ($loop->have_posts()):

            $css_class = $image_size . '-size';

            $style = ($style ? ' ' . $style : '');

            $output = '<ul class="post-list' . $style . ' ' . $css_class . '">';

            $hide_thumbnail = mo_to_boolean($hide_thumbnail);

            $show_meta = mo_to_boolean($show_meta);

            while ($loop->have_posts()) : $loop->the_post();

                $output .= '<li>';

                $thumbnail_exists = false;

                $output .= "\n" . '<div class="' . join(' ', get_post_class()) . '">' . "\n"; // Removed id="post-'.get_the_ID() to help avoid duplicate IDs validation error in the page

                if (!$hide_thumbnail) {
                    $thumbnail_url = mo_get_thumbnail(array('image_size' => $image_size));
                    if (!empty($thumbnail_url)) {
                        $thumbnail_exists = true;
                        $output .= $thumbnail_url;
                    }
                }

                $output .= "\n" . '<div class="entry-text-wrap ' . ($thumbnail_exists ? '' : 'nothumbnail') . '">';

                $output .= "\n" . the_title('<div class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></div>', false);

                if ($show_meta) {
                    $output .= '<div class="byline">' . mo_entry_published() . mo_entry_comments_number() . '</div>';
                }

                if ($excerpt_count != 0) {

                    $output .= "\n" . '<div class="entry-summary">';

                    $excerpt_text = mo_truncate_string(get_the_excerpt(), $excerpt_count);
                    $output .= $excerpt_text;

                    $output .= "\n" . '</div><!-- entry-summary -->';
                }

                $output .= "\n" . '</div><!-- entry-text-wrap -->';

                $output .= "\n" . '</div><!-- .hentry -->';

                $output .= '</li>';

            endwhile;

            $output .= '</ul>';

        endif;

        wp_reset_postdata();

        return $output;
    }
}

if (!function_exists('mo_get_post_snippets_list')) {

    // Display posts snippets list for flexslider carousel
    function mo_get_post_snippets_list($args) {

        /* Extract the array to allow easy use of variables. */
        extract($args);

        if (!empty($posts_query) && function_exists('siteorigin_widget_post_selector_process_query')) {
            $posts_query = siteorigin_widget_post_selector_process_query($posts_query);
            $loop = new WP_Query($posts_query);

            if (array_key_exists('post_type' , $posts_query)) {
                $post_type = $posts_query['post_type'][0];
                $taxonomy = mo_get_taxonomy($post_type);
            }
        }
        else {

            $taxonomy = mo_get_taxonomy($post_type);

            if (empty($post_type))
                $loop = new WP_Query(array(
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => $post_count
                ));
            elseif (!empty($post_ids))
                $loop = new WP_Query(array(
                    'post_type' => $post_type,
                    'posts_per_page' => $post_count,
                    'post__in' => explode(',', $post_ids)
                ));
            elseif (empty($taxonomy) || empty($terms))
                $loop = new WP_Query(array(
                    'ignore_sticky_posts' => 1,
                    'post_type' => $post_type,
                    'posts_per_page' => $post_count
                ));
            else
                $loop = new WP_Query(array(
                    'post_type' => $post_type,
                    'posts_per_page' => $post_count,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field' => 'slug',
                            'terms' => explode(',', $terms)
                        )
                    )
                ));
        }

        $output = '';

        if ($loop->have_posts()) :

            global $mo_theme;

            $mo_theme->set_context('loop', 'portfolio'); // tells the thumbnail functions to prepare lightbox constructs for the image

            $hide_thumbnail = mo_to_boolean($hide_thumbnail);

            $display_title = mo_to_boolean($display_title);

            $show_meta = mo_to_boolean($show_meta);

            $display_summary = mo_to_boolean($display_summary);

            $output .= '<ul>';

            while ($loop->have_posts()) : $loop->the_post();

                $thumbnail_exists = false;

                $output .= "\n" . '<li>';

                $output .= '<div class="' . join(' ', get_post_class()) . '">';

                if (!$hide_thumbnail) {
                    $thumbnail_url = mo_get_thumbnail(array(
                        'show_image_info' => true,
                        'image_size' => $image_size
                    ));
                    if (!empty($thumbnail_url)) {
                        $thumbnail_exists = true;
                        $output .= $thumbnail_url;
                    }
                }

                if ($display_title || $display_summary || $show_meta) {

                    $output .= "\n" . '<div class="entry-text-wrap ' . ($thumbnail_exists ? '' : 'nothumbnail') . '">';

                    if ($display_title)
                        $output .= "\n" . the_title('<div class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></div>', false);

                    if ($show_meta) {
                        $output .= '<div class="byline">' . mo_entry_published() . mo_entry_comments_number() . '</div>';
                    }


                    if ($display_summary) {

                        $output .= '<div class="entry-summary">';

                        if ($show_excerpt) {
                            $output .= mo_truncate_string(get_the_excerpt(), $excerpt_count);
                        }
                        else {
                            global $more;
                            $more = 0;
                            $output .= get_the_content(__('Read More <span class="meta-nav">&rarr;</span>', 'mo_theme'));
                        }
                        $output .= '</div><!-- .entry-summary -->';
                    }

                    $output .= '</div><!-- .entry-text-wrap -->';
                }

                $output .= '</div><!-- .hentry -->';

                $output .= '</li>';


            endwhile;

            $output .= '</ul>';

            $mo_theme->set_context('loop', null); //reset it

        endif;

        wp_reset_postdata();

        return $output;
    }
}

if (!function_exists('mo_get_post_snippets_layout')) {

    function mo_get_post_snippets_layout($args) {

        /* Extract the array to allow easy use of variables. */
        extract($args);

        if (!empty($posts_query) && function_exists('siteorigin_widget_post_selector_process_query')) {
            $posts_query = siteorigin_widget_post_selector_process_query($posts_query);
            $loop = new WP_Query($posts_query);

            if (array_key_exists('post_type', $posts_query)) {
                $post_type = $posts_query['post_type'][0];
                $taxonomy = mo_get_taxonomy($post_type);
            }
        }
        else {

            if (empty($taxonamy))
                $taxonomy = mo_get_taxonomy($post_type);
            else
                $taxonomy = $taxonamy; /* TODO: Remove later. For backwards compatibility */

            if (!empty($post_ids))
                $query_args = array(
                    'post_type' => $post_type,
                    'posts_per_page' => $post_count,
                    'post__in' => explode(',', $post_ids)
                );
            elseif (empty($taxonomy) || empty($terms))
                $query_args = array(
                    'ignore_sticky_posts' => 1,
                    'post_type' => $post_type,
                    'posts_per_page' => $post_count
                );
            else
                $query_args = array(
                    'post_type' => $post_type,
                    'posts_per_page' => $post_count,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field' => 'slug',
                            'terms' => explode(',', $terms)
                        )
                    )
                );

            if (isset($enable_sorting) && $enable_sorting) {
                $query_args['orderby'] = 'menu_order';
                $query_args['order'] = 'ASC';
            }

            if (!empty($post_parent))
                $query_args['post_parent'] = $post_parent;

            $loop = new WP_Query($query_args);
        }

        $output = '';

        if ($loop->have_posts()) :

            $style_class = mo_get_column_style($number_of_columns);

            if ($post_type == 'portfolio')
                $style_class .= ' portfolio-item';

            $hide_thumbnail = mo_to_boolean($hide_thumbnail);

            $display_title = mo_to_boolean($display_title);

            $show_meta = mo_to_boolean($show_meta);

            $display_summary = mo_to_boolean($display_summary);

            if (!empty($title))
                $output .= '<h3 class="post-snippets-title">' . $title . '</h3>';

            $output .= '<ul class="image-grid post-snippets ' . $layout_class . '">';

            while ($loop->have_posts()) : $loop->the_post();

                $thumbnail_exists = false;

                $output .= '<li data-id="' . get_the_ID() . '" class="' . $style_class . ' entry-item">';

                $output .= "\n" . '<div class="' . join(' ', get_post_class()) . '">';

                if (!$hide_thumbnail) {
                    if ($post_type == 'post')
                        $thumbnail_output = mo_get_blog_thumbnail($image_size, $taxonomy);
                    else
                        $thumbnail_output = mo_get_thumbnail(array(
                            'image_size' => $image_size,
                            'taxonomy' => $taxonomy
                        ));
                    if (!empty($thumbnail_output)) {
                        $thumbnail_exists = true;
                        $output .= $thumbnail_output;
                    }
                }

                if ($display_title || $display_summary || $show_meta) {
                    $output .= "\n" . '<div class="entry-text-wrap ' . ($thumbnail_exists ? '' : 'nothumbnail') . '">';

                    if ($display_title)
                        $output .= "\n" . the_title('<div class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></div>', false);

                    if ($show_meta) {
                        $output .= '<div class="byline">' . mo_entry_published() . mo_entry_comments_number() . '</div>';
                    }

                    if ($display_summary) {

                        $output .= '<div class="entry-summary">';

                        if ($show_excerpt) {
                            $output .= mo_truncate_string(get_the_excerpt(), $excerpt_count);
                        }
                        else {
                            global $more;
                            $more = 0;
                            $output .= get_the_content(__('Read More <span class="meta-nav">&rarr;</span>', 'mo_theme'));
                        }
                        $output .= '</div><!-- .entry-summary -->';
                    }

                    $output .= '</div><!-- .entry-text-wrap -->';
                }

                $output .= '</div><!-- .hentry -->';

                $output .= '</li><!-- .isotope element -->';

            endwhile;

            $output .= '</ul> <!-- post-snippets -->';

        endif;

        wp_reset_postdata();

        return $output;
    }
}

if (!function_exists('mo_is_context')) {
    function mo_is_context($context) {

        global $mo_theme;

        $current = $mo_theme->get_context('loop');

        if ($current == $context)
            return true;

        return false;
    }
}

if (!function_exists('mo_get_taxonomy')) {
    function mo_get_taxonomy($post_type) {

        $taxonomy = 'category';

        if ($post_type == 'portfolio')
            $taxonomy = 'portfolio_category';
        elseif ($post_type == 'gallery_item')
            $taxonomy = 'gallery_category';
        elseif ($post_type == 'post')
            $taxonomy = 'category';

        return $taxonomy;
    }
}
