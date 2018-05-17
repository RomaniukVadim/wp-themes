<?php
/*
Template Name: Donations list
*/

/**
 * Make empty page with this template 
 * and put it into menu
 * to display all Donations as streampage
 */

axiom_welldone_storage_set('blog_filters', 'donations');

get_template_part('blog');
?>