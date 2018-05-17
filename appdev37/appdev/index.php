<?php
/**
 * Index Template
 *
 * This is the default template.  It is used when a more specific template can't be found to display
 * posts.  It is unlikely that this template will ever be used, but there may be rare cases.
 *
 * @package Appdev
 * @subpackage Template
 */

get_header(); 

mo_display_archive_content(); 

get_sidebar();

get_footer();  

?>