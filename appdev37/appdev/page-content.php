<?php
/**
 * A generic page content template part
 *
 * A reusable page template part for displaying contents of a page
 *
 * @package Appdev
 * @subpackage Template
 */
?>

<?php mo_exec_action('before_content'); ?>

<div id="content" class="<?php echo mo_get_content_class();?>">

    <?php mo_exec_action('start_content'); ?>

    <?php if (have_posts()) : ?>

        <?php while (have_posts()) : the_post(); ?>

            <?php mo_exec_action('before_entry'); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <?php mo_exec_action('start_entry'); ?>

                <div class="entry-content">

                    <?php
                    $thumbnail_args = mo_get_thumbnail_args_for_singular();
                    mo_thumbnail($thumbnail_args);
                    ?>

                    <?php the_content(); ?>

                    <?php wp_link_pages(array('before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>

                </div><!-- .entry-content -->

                <?php mo_exec_action('end_entry'); ?>

            </div><!-- .hentry -->

            <?php mo_exec_action('after_entry'); ?>

            <?php mo_display_sidebar('after-singular-page'); ?>

            <?php mo_exec_action('after_singular'); ?>

            <?php comments_template('/comments.php', true); // Loads the comments.php template.  ?>

        <?php endwhile; ?>

    <?php endif; ?>

    <?php mo_exec_action('end_content'); ?>

</div><!-- #content -->

<?php mo_exec_action('after_content');
?>