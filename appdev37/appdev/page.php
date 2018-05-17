<?php
/**
 * Page Template
 *
 * This is the default page template.  It is used when a more specific template can't be found to display 
 * singular views of pages.
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); ?>

<?php get_template_part( 'page-content' ); // Loads the reusable page-content.php template. ?>

<?php get_sidebar(); ?>

<?php get_footer();  ?>