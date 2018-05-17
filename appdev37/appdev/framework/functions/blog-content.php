<?php

if (!function_exists('mo_display_starter_content')) {

    /**
     * A generic archives loop, reusable across multiple templates
     */
    function mo_display_starter_content($query_args = null) {
        global $mo_theme;

        $mo_theme->set_context('loop', 'starter'); // tells the thumbnail functions to prepare lightbox constructs for the image

        mo_display_blog_content($query_args);

        $mo_theme->set_context('loop', null); //reset it
    }
}
if (!function_exists('mo_display_archive_content')) {

    function mo_display_archive_content($query_args = null) {
        global $mo_theme;

        $mo_theme->set_context('loop', 'archive'); // tells the thumbnail functions to prepare lightbox constructs for the image

        $layout_option = mo_get_theme_option('mo_archive_styling', 'List');

        mo_display_blog_content($query_args, $layout_option);

        $mo_theme->set_context('loop', null); //reset it
    }
}

if (!function_exists('mo_display_blog_content')) {

    function mo_display_blog_content($query_args = null, $layout_option = 'List') {
        $layout_manager = mo_get_layout_manager();
        if ($layout_manager->is_full_width_layout()) {
            $image_size = 'full';
        }
        else {
            // 3 Column and 2 Column layouts will share the
            // image and resizing will happen through css
            $image_size = 'large';
        }
        $class_name = 'default-list';

        $excerpt_count = mo_get_theme_option('mo_excerpt_count', 250);

        $args = array(
            'list_style' => $class_name,
            'image_size' => $image_size,
            'query_args' => $query_args,
            'excerpt_count' => $excerpt_count,
            'layout_option' => $layout_option
        );

        mo_display_post_content_list_style($args);
    }
}

if (!function_exists('mo_show_page_content')) {
    function mo_show_page_content() {

        if (is_archive() || is_search())
            return; // No content to be shown for archive pages. All content is derived.

        if (have_posts()) :
            ?>

            <?php while (have_posts()) : the_post(); ?>

            <?php if (get_the_content()): ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="entry-content clearfix">

                        <?php the_content(); ?>

                    </div>
                    <!-- .entry-content -->

                </div><!-- .hentry -->

            <?php endif; ?>

        <?php endwhile; ?>

        <?php
        endif;
    }
}

if (!function_exists('mo_display_post_content_list_style')) {
    function mo_display_post_content_list_style($args) {

        /* Set the default arguments. */
        $defaults = array(
            'list_style' => 'default-list',
            'image_size' => 'large',
            'query_args' => null,
            'excerpt_count' => 160
        );

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        /* Extract the array to allow easy use of variables. */
        extract($args);
        ?>

        <?php mo_exec_action('before_content'); ?>

        <div id="content" class="<?php echo $list_style ?> <?php echo mo_get_content_class(); ?>">

            <?php mo_exec_action('start_content'); ?>

            <div class="hfeed">

                <?php
                if (isset($query_args) && !empty($query_args)) {
                    query_posts($query_args);
                }
                ?>

                <?php if (have_posts()) : ?>

                    <?php $first_post = true; ?>

                    <?php while (have_posts()) : the_post(); ?>

                        <?php mo_exec_action('before_entry'); ?>

                        <div id="post-<?php the_ID(); ?>"
                             class="<?php echo(join(' ', get_post_class()) . ($first_post ? ' first' : '')); ?> clearfix">

                            <?php mo_exec_action('start_entry');

                            echo '<div class="entry-snippet">';

                            echo mo_get_entry_title();

                            echo '<div class="entry-meta">' . mo_entry_author() . mo_entry_published("M d") . mo_entry_terms_list('category') . mo_entry_comments_link() . '</div>';

                            $thumbnail_exists = mo_display_blog_thumbnail($image_size);

                            mo_display_blog_entry_text($thumbnail_exists, $excerpt_count);

                            $disable_read_more_button = mo_get_theme_option('mo_disable_read_more_button_in_archives');

                            if (!$disable_read_more_button)
                                echo '<span class="read-more"><a class="button default" href="' . get_permalink() . '" title="' . get_the_title() . '">' . __('Read More', 'mo_theme') . '</a></span>';

                            echo '</div> <!-- .entry-snippet -->';

                            mo_exec_action('end_entry');

                            ?>

                        </div><!-- .hentry -->

                        <?php mo_exec_action('after_entry'); ?>

                        <?php $first_post = false; ?>

                    <?php endwhile; ?>

                <?php else : ?>

                    <?php get_template_part('loop-error'); // Loads the loop-error.php template.
                    ?>

                <?php endif; ?>

            </div>
            <!-- .hfeed -->

            <?php mo_exec_action('end_content'); ?>

            <?php get_template_part('loop-nav'); // Loads the loop-nav.php template.
            ?>

        </div><!-- #content -->

        <?php mo_exec_action('after_content'); ?>

        <?php wp_reset_query(); /* Right placement to help not lose context information */
        ?>

    <?php
    }
}

if (!function_exists('mo_display_blog_entry_text')) {
    function mo_display_blog_entry_text($thumbnail_exists, $excerpt_count) {

        echo '<div class="entry-text-wrap' . ($thumbnail_exists ? '' : ' nothumbnail') . '">';

        echo '<div class="entry-summary">';

        $show_content = mo_get_theme_option('mo_show_content_in_archives');

        if ($show_content) {
            global $more;
            $more = 0;
            /*TODO: Remove the more link here since it will be shown later */
            the_content(__('Read More <span class="meta-nav">&rarr;</span>', 'mo_theme'));
        }
        else {
            echo mo_truncate_string(get_the_excerpt(), $excerpt_count);
        }

        wp_link_pages(array('before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>'));

        echo '</div> <!-- .entry-summary -->';

        echo '</div>';

    }
}

if (!function_exists('mo_display_video_or_slider_thumbnail')) {
    function mo_display_video_or_slider_thumbnail($post_id, $image_size) {
        $use_video_thumbnail = get_post_meta($post_id, 'mo_use_video_thumbnail', true);

        if ($use_video_thumbnail) {
            $video_url = get_post_meta($post_id, 'mo_video_thumbnail_url', true);

            if (!empty($video_url)) {
                echo '<div class="video-box">';
                if (mo_is_vimeo($video_url) || mo_is_youtube($video_url)) {
                    if (mo_is_vimeo($video_url)) {
                        $video_url = "https://player.vimeo.com/video/" . mo_get_vimeo_id($video_url);
                    }
                    if (mo_is_youtube($video_url)) {
                        $video_url = "https://www.youtube.com/embed/" . mo_get_youtube_id($video_url);
                    }

                    echo '<iframe parent-selector=#content src="' . $video_url . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

                }
                echo '</div>'; // video-box

                return true;
            }
        }

        $use_slider_thumbnail = get_post_meta($post_id, 'mo_use_slider_thumbnail', true);

        if ($use_slider_thumbnail) {

            $slides = get_post_meta($post_id, 'post_slider', true);

            if (!empty($slides)) {
                $output = '[responsive_slider type="thumbnail" ]';
                $output .= '<ul>';
                foreach ($slides as $slide) {
                    $output .= '<li>' . mo_get_custom_sized_image($slide['slider_image'], null, 'thumbnail-slide', $slide['title']) . '</li>';
                }
                $output .= '</ul>';
                $output .= '[/responsive_slider]';

                $output = do_shortcode($output);

                echo $output;

                return true;
            }
        }
    }
}

if (!function_exists('mo_display_blog_thumbnail')) {
    function mo_display_blog_thumbnail($image_size, $taxonomy = "category") {

        global $post;

        $thumbnail_exists = mo_display_video_or_slider_thumbnail($post->ID, $image_size);

        if (!$thumbnail_exists)
            $thumbnail_exists = mo_thumbnail(array('image_size' => $image_size, 'wrapper' => true, 'size' => 'full', 'taxonomy' => $taxonomy));
        return $thumbnail_exists;
    }
}

if (!function_exists('mo_get_blog_thumbnail')) {
    function mo_get_blog_thumbnail($image_size, $taxonomy = "category") {

        global $post;

        $output = '';

        $use_video_thumbnail = get_post_meta($post->ID, 'mo_use_video_thumbnail', true);

        if ($use_video_thumbnail) {
            $video_url = get_post_meta($post->ID, 'mo_video_thumbnail_url', true);

            if (!empty($video_url)) {
                $output .= '<div class="video-box">';
                if (mo_is_vimeo($video_url) || mo_is_youtube($video_url)) {
                    if (mo_is_vimeo($video_url)) {
                        $video_url = "https://player.vimeo.com/video/" . mo_get_vimeo_id($video_url);
                    }
                    if (mo_is_youtube($video_url)) {
                        $video_url = "https://www.youtube.com/embed/" . mo_get_youtube_id($video_url);
                    }

                    $output .= '<iframe parent-selector=#content src="' . $video_url . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

                }
                $output .= '</div>'; // video-box

                return $output;
            }
        }

        $use_slider_thumbnail = get_post_meta($post->ID, 'mo_use_slider_thumbnail', true);

        if ($use_slider_thumbnail) {

            $slides = get_post_meta($post->ID, 'post_slider', true);

            if (!empty($slides)) {
                $slider_content = '[responsive_slider direction_nav="false" control_nav="false"]';
                $slider_content .= '<ul>';
                foreach ($slides as $slide) {
                    $slider_content .= '<li>' . mo_get_custom_sized_image($slide['slider_image'], null, 'thumbnail-slide', $slide['title']) . '</li>';
                }
                $slider_content .= '</ul>';
                $slider_content .= '[/responsive_slider]';

                $output .= do_shortcode($slider_content);

                return $output;
            }
        }

        /* Place image outside the content area for thumbnail lists to avoid going for css circus */
        $output .= mo_get_thumbnail(array('image_size' => $image_size, 'wrapper' => true, 'size' => 'full', 'taxonomy' => $taxonomy));
        return $output;
    }
}

if (!function_exists('mo_display_portfolio_entry_text')) {
    function mo_display_portfolio_entry_text($thumbnail_exists, $excerpt_count) {

        $display_title = mo_get_theme_option('mo_show_title_in_portfolio') ? true : false;

        $display_summary = mo_get_theme_option('mo_show_details_in_portfolio') ? true : false;

        if ($display_summary || $display_title) {

            echo '<div class="entry-text-wrap' . ($thumbnail_exists ? '' : ' nothumbnail') . '">';

            echo mo_get_entry_title();

            if ($display_summary) {

                echo '<div class="entry-summary">';

                $show_excerpt = mo_get_theme_option('mo_show_content_summary_in_portfolio') ? false : true;

                if ($show_excerpt) {
                    echo mo_truncate_string(get_the_excerpt(), $excerpt_count);
                }
                else {
                    global $more;
                    $more = 0;
                    the_content(__('Read More <span class="meta-nav">&rarr;</span>', 'mo_theme'));
                }
                echo '</div> <!-- .entry-summary -->';

            }

            echo '</div> <!-- .entry-text-wrap -->';
        }


    }
}

if (!function_exists('mo_get_portfolio_categories_filter')) {
    /** Isotope filtering support for Portfolio pages * */
    function mo_get_portfolio_categories_filter() {

        $output = '';

        $portfolio_categories = get_terms('portfolio_category');

        if (!empty($portfolio_categories)) {

            $output .= '<ul id="portfolio-filter">';

            $output .= '<li class="filter-text">' . __('Portfolio Filter:', 'mo_theme') . '</li>';

            $output .= '<li class="segment-0"><a class="portfolio-filter" data-value="*" href="#">' . __('All', 'mo_theme') . '</a></li>';

            $segment_count = 1;
            foreach ($portfolio_categories as $term) {

                $output .= '<li class="segment-' . $segment_count . '"><a class="" href="#" data-value=".term-' . $term->term_id . '" title="View all items filed under ' . $term->name . '">' . $term->name . '</a></li>';

                $segment_count++;
            }

            $output .= '</ul>';

        }

        return $output;
    }
}

if (!function_exists('mo_display_portfolio_content_grid_style')) {
    function mo_display_portfolio_content_grid_style($args) {

        /* Set the default arguments. */
        $defaults = array(
            'number_of_columns' => 4,
            'image_size' => 'medium',
            'query_args' => null,
            'excerpt_count' => 160
        );

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        /* Extract the array to allow easy use of variables. */
        extract($args);

        $style_class = mo_get_column_style($number_of_columns);

        mo_exec_action('before_content');
        ?>

        <div id="content" class="<?php echo mo_get_content_class(); ?>">

            <?php mo_exec_action('start_content'); ?>

            <div class="hfeed">

                <?php
                mo_show_page_content();

                if (isset($query_args) && !empty($query_args))
                    query_posts($query_args);

                if (have_posts()) :

                    if ($filterable)
                        echo mo_get_portfolio_categories_filter();

                    echo '<ul id="portfolio-items" class="image-grid">';

                    while (have_posts()) : the_post();

                        $style = $style_class . ' portfolio-item clearfix';
                        $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                        if (!empty($terms)) {
                            foreach ($terms as $term) {
                                $style .= ' term-' . $term->term_id;
                            }
                        }
                        ?>
                        <li data-id="id-<?php the_ID(); ?>" class="<?php echo $style; ?>">

                            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                <?php $thumbnail_exists = mo_thumbnail(array('image_size' => $image_size, 'wrapper' => true, 'size' => 'full', 'taxonomy' => 'portfolio_category'));

                                mo_display_portfolio_entry_text($thumbnail_exists, $excerpt_count);
                                ?>

                            </div>
                            <!-- .hentry -->

                        </li> <!--Isotope element -->


                    <?php endwhile; ?>

                    </ul> <!-- Isotope items -->

                <?php else : ?>

                    <?php get_template_part('loop-error'); // Loads the loop-error.php template.                  ?>

                <?php endif; ?>

            </div>
            <!-- .hfeed -->

            <?php mo_exec_action('end_content'); ?>


            <?php
            /* No need to load the loop-nav.php template if filterable is true since all posts get displayed. */
            if (!$filterable)
                echo get_template_part('loop-nav');
            ?>

        </div><!-- #content -->

        <?php mo_exec_action('after_content'); ?>

        <?php wp_reset_query(); /* Right placement to help not lose context information */ ?>

    <?php
    }
}