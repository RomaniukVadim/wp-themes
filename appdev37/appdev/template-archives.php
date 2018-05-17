<?php
/**
 * Template Name: Archives
 *
 * A custom page template for displaying blog archives.
 *
 * @package Appdev
 * @subpackage Template
 */
get_header();
?>

<div id="archives-template" class="layout-1c">


<?php mo_exec_action('before_content'); ?>

<div id="content" class="<?php echo mo_get_content_class();?>">

        <?php mo_exec_action('start_content'); ?>

        <?php if (have_posts()) : ?>

        <?php while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="entry-content clearfix">

                    <?php the_content(); ?>

                    <div class="fourcol">

                        <h2><?php _e('Recent 20 Posts', 'mo_theme'); ?></h2>

                        <ul class="recent-posts list1">
                            <?php
                            $args = array('numberposts' => '20');
                            $recent_posts = wp_get_recent_posts($args);
                            foreach ($recent_posts as $recent) {
                                echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look ' . esc_attr($recent["post_title"]) . '" >' . $recent["post_title"] . '</a> </li> ';
                            }
                            ?>
                        </ul>

                    </div>

                    <div class="fourcol">

                        <h2><?php _e('Archives by category', 'mo_theme'); ?></h2>

                        <ul class="category-archives list1">

                            <?php wp_list_categories(array('show_count' => false, 'use_desc_for_title' => false, 'title_li' => false)); ?>

                        </ul>
                        <!-- .category-archives -->

                    </div>

                    <div class="fourcol last">

                        <h2><?php _e('Archives by month', 'mo_theme'); ?></h2>

                        <ul class="monthly-archives list1">

                            <?php wp_get_archives(array('show_post_count' => true, 'type' => 'monthly')); ?>

                        </ul>
                        <!-- .monthly-archives -->

                        <div class="divider-line"></div>

                        <h2 id="authors"> <?php echo __('Author Archives', 'mo_theme'); ?></h2>

                        <ul class="list1">

                            <?php wp_list_authors(array('exclude_admin' => false, 'optioncount' => true)); ?>

                        </ul>

                    </div>

                    <?php wp_link_pages(array('before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>

                </div>
                <!-- .entry-content -->

            </div><!-- .hentry -->

            <?php endwhile; ?>

        <?php endif; ?>

        <?php mo_exec_action('end_content'); ?>

    </div><!-- #content -->

    <?php mo_exec_action('after_content'); ?>

</div>

<?php get_footer(); ?>