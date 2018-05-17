<?php
/**
 * Template Name: Portfolio 4 Column Full Width
 *
 * A custom page template for showing the most popular posts based on number of reader comments
 *
 * @package Appdev
 * @subpackage Template
 */
get_header();
?>

<div id="portfolio-full-width">

    <?php
    $args = array(
        'number_of_columns' => 4,
		'image_size' => 'medium',
        'posts_per_page' => 8,
        'filterable' => false
    );

    mo_display_portfolio_content($args);
    ?>

</div> <!-- #portfolio-full-width -->

<?php
get_footer(); // Loads the footer.php template. ?>