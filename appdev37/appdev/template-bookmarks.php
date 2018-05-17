<?php
/**
 * Template Name: Bookmarks
 *
 * A custom page template for displaying the site's bookmarks/links.
 *
 * @package Appdev
 * @subpackage Template
 */
get_header();
?>

<?php mo_exec_action('before_content'); ?>

<div id="content" class="<?php echo mo_get_content_class();?>">

    <?php mo_exec_action('start_content'); ?>

    <?php if (have_posts()) : ?>

        <?php while (have_posts()) : the_post(); ?>

            <?php mo_exec_action('before_entry'); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <?php mo_exec_action('start_entry'); ?>

                <div class="entry-content clearfix">
                    <?php the_content(); ?>

                    <?php
                    $args = array(
                        'title_li' => false,
                        'title_before' => '<h2>',
                        'title_after' => '</h2>',
                        'category_before' => false,
                        'category_after' => false,
                        'categorize' => true,
                        'show_description' => true,
                        'between' => '<br />',
                        'show_images' => false,
                        'show_rating' => false,
                    );
                    ?>
                    <?php wp_list_bookmarks($args); ?>

                <?php wp_link_pages(array('before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>
                </div><!-- .entry-content -->

        <?php mo_exec_action('end_entry'); ?>

            </div><!-- .hentry -->

        <?php mo_exec_action('after_entry'); ?>

            <!-- mo_display_sidebar( 'after-singular-page' ); -->

            <?php mo_exec_action('after_singular'); ?>

            <?php comments_template('/comments.php', true); // Loads the comments.php template. ?>

        <?php endwhile; ?>

    <?php endif; ?>

<?php mo_exec_action('end_content'); ?>

</div><!-- #content -->

<?php mo_exec_action('after_content'); ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>