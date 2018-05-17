<?php
/**
 * Template Name: Full Width 
 *
 * A custom page template for displaying full width content with no sidebars, no comments or widgets.
 * This is completely free form with no before content or after content hooks that is generally attached with other page templates.
 * Use segments for all your boxed content.
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); ?>

<div id="full-width-template" class="layout-1c">

    <?php mo_exec_action('before_content'); ?>

    <div id="content" class="<?php echo mo_get_content_class();?>">

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

                    </div><!-- .entry-content -->

                </div><!-- .hentry -->

            <?php endwhile; ?>

        <?php endif; ?>


    </div><!-- #content -->

</div> <!-- #full_width-template -->

<?php get_footer();  ?>