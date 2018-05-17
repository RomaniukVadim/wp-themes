<?php
/**
 * Template Name: Portfolio 3 Column Sorted Full Width
 *
 * A custom page template for showing the portfolio items sorted by name
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
        'posts_per_page' => 50,
        'filterable' => true
    );

    mo_display_portfolio_content($args);
    ?>

</div> <!-- #portfolio-full-width -->

<?php
get_footer(); // Loads the footer.php template. ?>