<?php
/**
 * Template Name: Single Page Site
 *
 * Custom Page template for creating single page site utilizing page sections custom post type instances
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); // displays slider content if so chosen by user
?>

<div id="content" class="<?php echo mo_get_content_class(); ?>">

    <?php while (have_posts()) : the_post(); ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div class="entry-content clearfix">
                <?php the_content(); ?>
            </div>
            <!-- .entry-content -->

        </div><!-- .hentry -->

    <?php endwhile; ?>

    <div class="hfeed">

        <?php

        $page_sections = mo_get_theme_option('mo_page_section_select_for_one_page');

        query_posts(array('post_type' => 'page_section', 'post__in' => $page_sections, 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order', 'post_status' => 'publish'));

        if (have_posts()) :

            while (have_posts()) : the_post();

                global $post;

                $slug = get_post($post)->post_name;?>

                <div id="<?php echo $slug ?>" class="<?php echo(join(' ', get_post_class()) . ' first'); ?>">

                    <?php the_content(); ?>

                    <?php
                    if (current_user_can('edit_post', $post->ID) && $link = esc_url(get_edit_post_link($post->ID)))
                        echo '<a title="' . get_the_title($post->ID) . '" class="button red edit-button" href="' . $link . '" target="_blank">' . __('Edit', 'mo_theme') . '</a>';
                    ?>

                </div>
                <!-- .hentry -->

            <?php

            endwhile;
        else :
            get_template_part('loop-error'); // Loads the loop-error.php template.
        endif;

        ?>

    </div>
    <!-- .hfeed -->

</div><!-- #content -->

<?php wp_reset_query(); /* Right placement to help not lose context information */ ?>

<?php get_footer(); ?>
