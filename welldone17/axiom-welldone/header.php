<?php
/**
 * The Header for our theme.
 */
// Theme init - don't remove next row! Load custom options
axiom_welldone_core_init_theme();

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php 
		$body_scheme = axiom_welldone_get_custom_option('body_scheme');
		if (empty($body_scheme)  || axiom_welldone_is_inherit_option($body_scheme)) $body_scheme = 'original';
		echo 'scheme_' . esc_attr($body_scheme); 
		?>">
<head>
	<?php
	if (($preloader=axiom_welldone_get_theme_option('page_preloader'))!='') {
		$clr = axiom_welldone_get_scheme_color('bg_color');
   	}

	wp_head(); ?>
</head>

<body <?php body_class();?>>
	<?php 
	echo force_balance_tags(axiom_welldone_get_custom_option('gtm_code'));

	// Page preloader
	if ($preloader!='') {
		?><div id="page_preloader"></div><?php
	}

	do_action( 'before' );
	
	// Add TOC items 'Home' and "To top"
    get_template_part(axiom_welldone_get_file_slug('templates/_parts/menu-toc.php'));	
	?>

	<?php
		$body_style  = axiom_welldone_get_custom_option('body_style');
		$class = $style = '';
		if (axiom_welldone_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || axiom_welldone_get_custom_option('bg_image_load')=='always')) {
			if (($img = axiom_welldone_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', axiom_welldone_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
			else if (($img = axiom_welldone_get_custom_option('bg_pattern_custom')) != '')
				$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
			else if (($img = axiom_welldone_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = axiom_welldone_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.($img);
			if (($img = axiom_welldone_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.($img).';';
		}
	?>

	<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>

		<div class="page_wrap">

			<?php
			$top_panel_style = axiom_welldone_get_custom_option('top_panel_style');
			$top_panel_position = axiom_welldone_get_custom_option('top_panel_position');
			$top_panel_scheme = axiom_welldone_get_custom_option('top_panel_scheme');
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				axiom_welldone_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			if(axiom_welldone_get_custom_option('top_panel_style') != 'header_7'){
				// Mobile Menu
				get_template_part(axiom_welldone_get_file_slug('templates/headers/_parts/header-mobile.php'));
			}

			// Slider
			get_template_part(axiom_welldone_get_file_slug('templates/headers/_parts/slider.php'));
			
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				axiom_welldone_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}

			// Top of page section: page title and breadcrumbs
			get_template_part(axiom_welldone_get_file_slug('templates/headers/_parts/breadcrumbs.php'));
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(axiom_welldone_get_custom_option('body_paddings')); ?>">

				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') axiom_welldone_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				axiom_welldone_open_wrapper('<div class="content">');
				?>