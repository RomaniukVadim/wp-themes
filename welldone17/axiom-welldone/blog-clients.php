<?php
/*
Template Name: Clients list
*/

/**
 * Make empty page with this template 
 * and put it into menu
 * to display all Clients as streampage
 */

axiom_welldone_storage_set('blog_filters', 'clients');

get_template_part('blog');
?>