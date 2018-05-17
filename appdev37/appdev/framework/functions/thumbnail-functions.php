<?php
/*
 * Use Wordpress Post Thumbnail and Aqua Resizer to resize Get_The_Image thumbnails
 *
 * @package Livemesh_Framework
 *
*/

if (!function_exists('mo_get_custom_sized_image')) {
    function mo_get_custom_sized_image($src, $image_size, $css_class, $title = '') {
        //image_size can be an array with height and width key value pairs or a string
        if (!empty($image_size)) {

            $width = $image_size['width'];
            $height = $image_size['height'];


            $image_url = aq_resize($src, $width, $height, true, true, true); //resize & crop the image
        }
        else {
            $image_url = $src; // retain the original image if no size is specified
        }

        $output = '<img alt="' . $title . '" class="' . $css_class . '" src="' . $image_url . '" />';

        return $output;
    }
}

if (!function_exists('mo_show_image_info')) {

    /* Return false to disable image info on hover */
    function mo_show_image_info($context) {

        if ($context == 'portfolio') {
            $enable_hover = mo_get_theme_option('mo_disable_portfolio_hover') ? false : true;
            return $enable_hover;
        }

        return ($context == 'archive' || $context == 'starter');

    }
}

if (!function_exists('mo_thumbnail')) {
    function mo_thumbnail($args) {

        $thumbnail_element = mo_get_thumbnail($args);

        if (!empty($thumbnail_element)) {
            echo $thumbnail_element;
            return true;
        }

        return false;
    }
}

if (!function_exists('mo_get_thumbnail')) {

    function mo_get_thumbnail($args) {
        global $mo_theme;

        $context = $mo_theme->get_context('loop');

        $defaults = array(
            'image_size' => 'full', // get the original image without resizing if no image size is specified
            'wrapper' => true,
            'show_image_info' => false,
            'before_html' => '',
            'after_html' => '',
            'image_class' => 'thumbnail',
            'image_alt' => '',
            'image_title' => '',
            'taxonomy' => 'category'
        );
        $args = wp_parse_args($args, $defaults);


        /* Extract the array to allow easy use of variables. */
        extract($args);

        $output = '';

        $thumbnail_urls = mo_get_wp_thumbnail_urls($args);

        //create the thumbnail
        if (!empty($thumbnail_urls)) {
            
            $thumbnail_src = $thumbnail_urls[0];
            $thumbnail_element = $thumbnail_urls[1];

            $post_id = get_the_ID();

            $post_title = get_the_title($post_id);
            $post_link = get_permalink($post_id);

            if (empty($before_html)) {
                $before_html = '<a title="' . $post_title . '" href="' . $post_link . ' ">';
                $after_html = '</a>' . $after_html;
            }

            if ($wrapper) {
                $wrapper_html = '<div class="image-area">';
                $before_html = $wrapper_html . $before_html;
                if (mo_show_image_info($context) || $show_image_info) {
                    $image_info = '';
                    $image_info .= '<div class="image-info">';
                    $image_info .= '<h3 class="post-title"><a title="' . $post_title . '" href="' . $post_link . ' ">' . $post_title . '</a></h3>';
                    $image_info .= mo_get_taxonomy_info($taxonomy);
                    $image_info .= '<div class="image-info-buttons">';
                    $rel_attribute = 'rel="prettyPhoto[' . $context . ']" ';
                    // point me to the source of the image for lightbox preview
                    $image_info .= '<a class="lightbox-link" ' . $rel_attribute . 'title="' . $post_title . '" href="' . $thumbnail_src . ' "><span>Preview</span></a>';
                    $image_info .= '</div>';
                    $image_info .= '</div>';

                    $after_html .= $image_info;
                }
                $after_html .= '</div>'; // end of image-area
            }

            $output = $before_html;
            $output .= $thumbnail_element;
            $output .= $after_html;
        }
        return $output;
    }
}

if (!function_exists('mo_get_taxonomy_info')) {

    function mo_get_taxonomy_info($taxonomy) {
        $output = '';
        $terms = get_the_terms(get_the_ID(), $taxonomy);
        if ($terms) {
            $output .= '<div class="terms">';
            $term_count = 0;
            foreach ($terms as $term) {
                if ($term_count != 0)
                    $output .= ', ';
                $output .= '<a href="' . get_term_link($term->slug, $taxonomy) . '">' . $term->name . '</a>';
                $term_count = $term_count + 1;
            }
            $output .= '</div>';
        }
        return $output;
    }
}

if (!function_exists('mo_get_wp_thumbnail_urls')) {

    function mo_get_wp_thumbnail_urls($args) {
        extract($args);

        $thumbnail_urls = array();

        if (empty($post_id))
            $post_id = get_the_ID();

        // 1- First get me the link to featured image src
        $feature_image_id = get_post_thumbnail_id($post_id);
        $feature_image_src = wp_get_attachment_image_src($feature_image_id, 'full');

        if ($feature_image_src) {
            $feature_image_src = $feature_image_src[0];
            // 2- Now get me the complete img element
            $atts = array();

            if (!empty($image_class))
                $atts['class'] = $image_class;

            if (!empty($image_alt))
                $atts['alt'] = $image_alt;

            if (!empty($image_title))
                $atts['title'] = $image_title;


            // make sure you pass the string thumbnail size instead of array to avoid image downsizing by WordPress
            $wp_thumb_name = mo_get_wp_thumb_name($image_size);

            $thumbnail_element = get_the_post_thumbnail($post_id, $wp_thumb_name, $atts);

            $thumbnail_urls[0] = $feature_image_src;
            $thumbnail_urls[1] = $thumbnail_element;
        }

        return $thumbnail_urls;
    }
}