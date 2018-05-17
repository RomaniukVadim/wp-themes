<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_welldone_template_header_1_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_template_header_1_theme_setup', 1 );
	function axiom_welldone_template_header_1_theme_setup() {
		axiom_welldone_add_template(array(
			'layout' => 'header_1',
			'mode'   => 'header',
			'title'  => esc_html__('Header 1', 'axiom-welldone'),
			'icon'   => axiom_welldone_get_file_url('templates/headers/images/1.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'axiom_welldone_template_header_1_output' ) ) {
	function axiom_welldone_template_header_1_output($post_options, $post_data) {

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

		<header class="top_panel_wrap top_panel_style_1 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_1 top_panel_position_<?php echo esc_attr(axiom_welldone_get_custom_option('top_panel_position')); ?>">
			
			<?php if (axiom_welldone_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						axiom_welldone_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('contact_info', 'open_hours', 'login', 'socials')
						));
						get_template_part(axiom_welldone_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php axiom_welldone_show_layout($header_css); ?>>
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid">
						<div class="column-2_5 contact_logo">
							<?php axiom_welldone_show_logo(); ?>
						</div><?php
						// Address
						$contact_address_1=trim(axiom_welldone_get_custom_option('contact_address_1'));
						$contact_address_2=trim(axiom_welldone_get_custom_option('contact_address_2'));
						if (!empty($contact_address_1) || !empty($contact_address_2)) {
							?><div class="column-1_5 contact_field contact_address"><!--
								<span class="contact_icon icon-home"></span>
								<span class="contact_label contact_address_1"><?php echo force_balance_tags($contact_address_1); ?></span>
								<span class="contact_address_2"><?php echo force_balance_tags($contact_address_2); ?></span> -->
							</div><?php
						}
						
						// Phone and email
						$contact_phone=trim(axiom_welldone_get_custom_option('contact_phone'));
						$contact_email=trim(axiom_welldone_get_custom_option('contact_email'));
						if (!empty($contact_phone) || !empty($contact_email)) {
							?><div class="column-1_5 contact_field contact_phone">
								<div>
									<span class="contact_icon icon-technology"></span>
									<span class="contact_label contact_phone"><?php echo force_balance_tags($contact_phone); ?></span>
									<span class="contact_email"><?php echo force_balance_tags($contact_email); ?></span>
								</div>
							</div><?php
						}
						
						// Woocommerce Cart
						if (function_exists('axiom_welldone_exists_woocommerce') && axiom_welldone_exists_woocommerce() && (axiom_welldone_is_woocommerce_page() && axiom_welldone_get_custom_option('show_cart')=='shop' || axiom_welldone_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
							?><div class="column-1_5 contact_field contact_cart"><?php get_template_part(axiom_welldone_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?></div><?php
						}
						?></div>
				</div>
			</div>

			<div class="top_panel_bottom">
				<div class="content_wrap clearfix">
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
		</header>

		<?php
		axiom_welldone_storage_set('header_mobile', array(
			 'open_hours' => true,
			 'login' => true,
			 'socials' => true,
			 'bookmarks' => true,
			 'contact_address' => true,
			 'contact_phone_email' => true,
			 'woo_cart' => true,
			 'search' => true
			)
		);
	}
}
?>