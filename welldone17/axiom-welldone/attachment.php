<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move axiom_welldone_set_post_views to the javascript - counter will work under cache system
	if (axiom_welldone_get_custom_option('use_ajax_views_counter')=='no') {
		axiom_welldone_set_post_views(get_the_ID());
	}

	axiom_welldone_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !axiom_welldone_param_is_off(axiom_welldone_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>