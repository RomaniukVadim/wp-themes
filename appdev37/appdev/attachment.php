<?php

/**
 * Attachment Template
 *
 * This template is loaded when browsing a Wordpress attachment.
 *
 * @package Appdev
 * @subpackage Template
 */

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
                        // Do not display meta for attachments - not much use
                        echo '<div class="entry-meta">' . mo_entry_published("M d") . mo_entry_author() . mo_entry_comments_link() . '</div>';
                        ?>

                        <div class="entry-content clearfix">

                            <?php the_attachment_link(get_the_ID(), true) ?>

                            <?php the_content(); ?>

                            <?php wp_link_pages(array('link_before' => '<span class="page-number">', 'link_after' => '</span>', 'before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>

                        </div>
                        <!-- .entry-content -->

                        <?php mo_exec_action('end_entry'); ?>

                    </div><!-- .hentry -->

                    <?php mo_exec_action('after_entry'); ?>

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

<?php get_footer(); ?>