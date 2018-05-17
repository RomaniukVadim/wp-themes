<?php

	// Add TOC items 'Home' and "To top"
	if (axiom_welldone_get_custom_option('menu_toc_home')=='yes')
		axiom_welldone_show_layout(axiom_welldone_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'axiom-welldone'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'axiom-welldone'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => esc_url(home_url('/'))
			)
		)); 
	if (axiom_welldone_get_custom_option('menu_toc_top')=='yes')
		axiom_welldone_show_layout(axiom_welldone_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'axiom-welldone'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'axiom-welldone'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			)); 
?>