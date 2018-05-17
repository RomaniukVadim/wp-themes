<?php
/**
 * Archive Template
 *
 * This template is loaded when viewing a archive and replaces the default template.  
 * It can also be overwritten for individual categories and tags with new template files
 * specific to the category or the tag. 
 * 
 * @package Appdev
 * @subpackage Template
 */



get_header(); 

mo_display_archive_content(); 

get_sidebar();

get_footer(); 

?>