<?php
/**
 * Template for Page 404
 */

// Tribe Events hack - create empty post object to prevent error message from the plugin
if (!isset($GLOBALS['post'])) {
	$GLOBALS['post'] = new stdClass();
	$GLOBALS['post']->post_type = 'unknown';
}
// End Tribe Events hack

get_header(); 

axiom_welldone_show_post_layout( array('layout' => '404'), false );

get_footer(); 
?>