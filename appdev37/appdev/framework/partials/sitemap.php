<?php

/**
 * A reusable sitemap templates
 *
 * @package Livemesh_Framework
 */

if (!function_exists('mo_sitemap_template')) {

    function mo_sitemap_template() {
        ?>

        <div class="fourcol">

            <h2 id="posts"><?php echo __('Posts by Category', 'mo_theme'); ?></h2>

            <ul class="list1">

                <?php
                // Add categories you'd like to exclude in the exclude here
                $categories = get_categories('exclude=');

                foreach ($categories as $cat) {

                    echo '<li><a href="' . get_category_link($cat->cat_ID) . '" class="category-link">' . $cat->cat_name . '<small> (' . $cat->count . ')</small></a>';

                    echo '<ul class="list1">';

                    $loop = new WP_Query(array('posts_per_page' => -1, 'cat' => $cat->cat_ID));

                    while ($loop->have_posts()) {

                        $loop->the_post();

                        $category = get_the_category();

                        // Only display a post link once, even if it's in multiple categories
                        if ($category[0]->cat_ID == $cat->cat_ID) {
                            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                        }
                    }

                    echo "</ul>";
                    echo "</li>";

                    wp_reset_postdata();
                }
                ?>
            </ul>

        </div>

        <div class="fourcol">

            <h2 id="pages"><?php echo __('Pages', 'mo_theme'); ?></h2>

            <ul class="list1">

                <?php wp_list_pages(array('exclude' => '', 'title_li' => '')); // Add pages you'd like to exclude in the exclude here ?>

            </ul>


        </div>

        <div class="fourcol last">

            <h2 id="authors"> <?php echo __('Authors', 'mo_theme'); ?></h2>

            <ul class="list1">

                <?php wp_list_authors(array('exclude_admin' => false)); ?>
            </ul>

            <h2 id="portfolio"><?php echo __('Our Portfolio', 'mo_theme'); ?></h2>

            <?php

            echo '<ul class="list1">';

            $loop = new WP_Query(array('post_type' => 'portfolio', 'posts_per_page' => '-1'));

            while ($loop->have_posts()) {

                $loop->the_post();

                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }

            wp_reset_postdata();

            echo "</ul>";   ?>

        </div>

    <?php
    }
}
?>
