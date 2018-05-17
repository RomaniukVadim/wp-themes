<?php
/**
 * Displays page section with no sidebars - these page sections are used in single page templates.
 * NOT TO BE PUBLIC (no search, links etc - must be part of another page) but can be opened for
 * testing purposes during development only.
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); ?>

<?php mo_exec_action('before_content'); ?>

    <div id="content" class="<?php echo mo_get_content_class(); ?>">

        <?php if (have_posts()) : ?>

            <?php while (have_posts()) : the_post(); ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="entry-content">

                        <?php
                        $thumbnail_args = mo_get_thumbnail_args_for_singular();
                        mo_thumbnail($thumbnail_args);
                        ?>

                        <?php the_content(); ?>

                        <?php wp_link_pages(array('before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>

                    </div>
                    <!-- .entry-content -->

                </div><!-- .hentry -->

            <?php endwhile; ?>

        <?php endif; ?>

    </div><!-- #content -->

<?php get_footer(); ?>