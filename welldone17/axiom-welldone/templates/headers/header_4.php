<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_welldone_template_header_4_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_template_header_4_theme_setup', 1 );
	function axiom_welldone_template_header_4_theme_setup() {
		axiom_welldone_add_template(array(
			'layout' => 'header_4',
			'mode'   => 'header',
			'title'  => esc_html__('Header 4', 'axiom-welldone'),
			'icon'   => axiom_welldone_get_file_url('templates/headers/images/4.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'axiom_welldone_template_header_4_output' ) ) {
	function axiom_welldone_template_header_4_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>
		

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_4 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_4 top_panel_position_<?php echo esc_attr(axiom_welldone_get_custom_option('top_panel_position')); ?>">
			
			<?php if (axiom_welldone_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						axiom_welldone_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('open_hours', 'login', 'cart')
						));
						get_template_part(axiom_welldone_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php axiom_welldone_show_layout($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php axiom_welldone_show_logo(true, true); ?>
					</div>
					<div class="menu_main_wrap">
						<nav class="menu_main_nav_area">
							<?php
							$menu_main = axiom_welldone_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = axiom_welldone_get_nav_menu();
							axiom_welldone_show_layout($menu_main);
							?>
						</nav>
						<?php if (axiom_welldone_get_custom_option('show_search')=='yes') axiom_welldone_show_layout(axiom_welldone_sc_search(array())); ?>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
		axiom_welldone_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => true,
				 'socials' => false,
				 'bookmarks' => true,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => true,
				 'search' => true
			)
		);
	}
}
?>