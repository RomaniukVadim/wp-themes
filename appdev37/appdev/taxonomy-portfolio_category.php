<?php
/**
 *
 * Displays portfolio items belonging to specific portfolio categories
 *
 * @package Appdev
 * @subpackage Template
 */
get_header();
?>

    <div id="portfolio-full-width">

        <?php
        $args = array(
            'number_of_columns' => 3,
            'image_size' => 'medium',
            'posts_per_page' => 6,
            'filterable' => false
        );

        mo_display_portfolio_content($args);
        ?>

    </div> <!-- portfolio-full-width -->

<?php
get_footer(); // Loads the footer.php template. ?>