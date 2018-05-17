<?php

/**
 * Post Template
 *
 * This template is loaded when browsing a Wordpress post.
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); ?>

<?php mo_exec_action('before_content'); ?>

<div id="content">

    <?php mo_exec_action('start_content'); ?>

    <?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

        <?php mo_exec_action('before_entry'); ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php mo_exec_action('start_entry'); ?>

            <div class="entry-content ninecol">

                <?php

                $args = mo_get_thumbnail_args_for_singular();

                mo_thumbnail($args);

                ?>

                <?php the_content(); /* No thumbnail support for this. Everything user has to input - videos, audio, slider etc. */ ?>

                <?php wp_link_pages(array('before' => '<p class="page-links">' . __('Pages:', 'mo_theme'), 'after' => '</p>')); ?>

            </div>
            <!-- .entry-content -->

            <?php mo_exec_action('end_entry'); ?>

            <div class="portfolio-sidebar threecol last">

                <?php

                echo '<div class="portfolio-info" ><h2>' . __('App Details: ', 'mo_theme') . '</h2></div > ';

                $project_author = get_post_meta($post->ID, '_portfolio_author_field', true);
                if (!empty($project_author)) {
                    echo '<p>' . htmlspecialchars_decode($project_author) . '</p>';
                    echo '<div class="portfolio-label" >' . __('Author ', 'mo_theme') . ' </div > ';
                }

                $project_client = get_post_meta($post->ID, '_portfolio_client_field', true);
                if (!empty($project_client)) {
                    echo '<p>' . htmlspecialchars_decode($project_client) . '</p>';
                    echo '<div class="portfolio-label" >' . __('Client ', 'mo_theme') . ' </div > ';
                }

                $project_date = get_post_meta($post->ID, '_portfolio_date_field', true);
                if (!empty($project_date)) {
                    echo '<p>' . htmlspecialchars_decode($project_date) . '</p>';
                    echo '<div class="portfolio-label" >' . __('Date ', 'mo_theme') . ' </div > ';
                }

                echo '<p>' . mo_entry_terms_text('portfolio_category') . '</p>';
                echo '<div class="portfolio-label" >' . __('Category ', 'mo_theme') . ' </div > ';

                echo '<div class="clear" ></div>';

                $project_info = get_post_meta($post->ID, '_portfolio_info_field', true);
                if (!empty($project_info)) {
                    echo '<div class="portfolio-description">' . htmlspecialchars_decode($project_info);
                    echo '<div class="portfolio-label" >' . __('Description ', 'mo_theme') . ' </div>';
                    echo '</div>';
                }

                $project_url = get_post_meta($post->ID, '_portfolio_link_field', true);
                if (!empty($project_url)) {
                    echo '<div class="portfolio-link" ><a href = "' . $project_url . '" class="button default">' . __('Visit Website', 'mo_theme') . '</a></div > ';
                }

                ?>

                <?php  get_template_part('loop-nav'); // Loads the loop-nav.php template.   ?>

            </div>

        </div><!-- .hentry -->

        <?php mo_exec_action('after_entry'); ?>

        <?php if (mo_get_theme_option('mo_enable_portfolio_comments')) {
                comments_template('/comments.php', true); // Loads the comments.php template.
        } ?>

        <?php endwhile; ?>

    <?php endif; ?>

    <?php mo_exec_action('end_content'); ?>


</div><!-- #content -->

<?php mo_exec_action('after_content'); ?>

<?php get_footer(); ?>