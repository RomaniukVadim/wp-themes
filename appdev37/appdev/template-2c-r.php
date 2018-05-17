<?php
/**
 * Template Name: Two Column Left Navigation 
 *
 * A custom page template for displaying content with left sidebar
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); ?>

<div id="left_nav_2c_template" class="layout-2c-r">

	<?php get_template_part( 'page-content' ); // Loads the reusable page-content.php template. ?>

	<?php get_sidebar(); ?>

</div> <!-- #left_nav_2c_template -->

<?php get_footer();  ?>