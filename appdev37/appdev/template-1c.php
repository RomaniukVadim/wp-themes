<?php
/**
 * Template Name: One Column No Sidebars
 *
 * A custom page template for displaying full width content with no sidebars
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); ?>

    <div id="one-column-template" class="layout-1c">

        <?php get_template_part( 'page-content' ); // Loads the reusable page-content.php template. ?>

    </div> <!-- #one-column-template -->

<?php get_footer();  ?>