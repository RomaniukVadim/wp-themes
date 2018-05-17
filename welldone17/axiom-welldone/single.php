<?php
/**
 * Single post
 */
get_header(); 

$single_style = axiom_welldone_storage_get('single_style');
if (empty($single_style)) $single_style = axiom_welldone_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	axiom_welldone_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !axiom_welldone_param_is_off(axiom_welldone_get_custom_option('show_sidebar_main')),
			'content' => axiom_welldone_get_template_property($single_style, 'need_content'),
			'terms_list' => axiom_welldone_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>