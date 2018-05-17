<?php

/**
 * Post Template
 *
 * This template is loaded when browsing a Wordpress post.
 *
 * @package Appdev
 * @subpackage Template
 */

function mo_display_post_thumbnail() {

    $post_id = get_the_ID();
    $args = mo_get_thumbnail_args_for_singular();
    $image_size = $args['image_size'];
    $thumbnail_exists = mo_display_video_or_slider_thumbnail($post_id, $image_size);
    if (!$thumbnail_exists) {

        $thumbnail_exists = mo_thumbnail($args);
    }
    return $thumbnail_exists;

}

get_header();
?>

<?php mo_exec_action('before_content'); ?>

    <div id="content" class="<?php echo mo_get_content_class(); ?>">

        <?php mo_exec_action('start_content'); ?>

        <div class="hfeed">

            <?php if (have_posts()) : ?>

                <?php while (have_posts()) : the_post(); ?>

                    <?php mo_exec_action('before_entry'); ?>

                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php mo_exec_action('start_entry'); ?>

                        <?php echo mo_get_entry_title(); ?>


                        <?php
                        echo '<div class="entry-meta">' . mo_entry_published("M d") . mo_entry_author() . mo_entry_terms_list('category') . mo_entry_comments_link() . '</div>';
                        ?>

                        <div class="entry-content clearfix">

                            <?php
                            mo_display_post_thumbnail();
                            ?>

                            <?php the_content(); ?>

                            <?php wp_link_pages(array('link_before' => '<span class="page-number">', 'link_after' => '</span>', 'before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>

                        </div>
                        <!-- .entry-content -->

                        <?php $post_tags = wp_get_post_tags($post->ID);

                        if (!empty($post_tags)) : ?>

                            <div class="entry-meta">

                                <div class="taglist">

                                    <?php echo mo_entry_terms_list('post_tag'); ?>

                                </div>

                            </div>

                        <?php endif; ?>

                        <?php mo_exec_action('end_entry'); ?>

                    </div><!-- .hentry -->

                    <?php mo_exec_action('after_entry'); ?>

                    <?php mo_display_sidebar('after-singular-post'); ?>

                    <?php mo_exec_action('after_singular'); ?>

                    <?php comments_template('/comments.php', true); // Loads the comments.php template.   ?>

                <?php endwhile; ?>

            <?php endif; ?>

        </div>
        <!-- .hfeed -->

        <?php mo_exec_action('end_content'); ?>

        <?php get_template_part('loop-nav'); // Loads the loop-nav.php template.   ?>

    </div><!-- #content -->

<?php mo_exec_action('after_content'); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>