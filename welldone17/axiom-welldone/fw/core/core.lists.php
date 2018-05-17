<?php
/**
 * Axiom Welldone Framework: return lists
 *
 * @package axiom_welldone
 * @since axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'axiom_welldone_get_list_styles' ) ) {
	function axiom_welldone_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'axiom-welldone'), $i);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'axiom_welldone_get_list_margins' ) ) {
	function axiom_welldone_get_list_margins($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'axiom-welldone'),
				'tiny'		=> esc_html__('Tiny',		'axiom-welldone'),
				'small'		=> esc_html__('Small',		'axiom-welldone'),
				'medium'	=> esc_html__('Medium',		'axiom-welldone'),
				'large'		=> esc_html__('Large',		'axiom-welldone'),
				'huge'		=> esc_html__('Huge',		'axiom-welldone'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'axiom-welldone'),
				'small-'	=> esc_html__('Small (negative)',	'axiom-welldone'),
				'medium-'	=> esc_html__('Medium (negative)',	'axiom-welldone'),
				'large-'	=> esc_html__('Large (negative)',	'axiom-welldone'),
				'huge-'		=> esc_html__('Huge (negative)',	'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_margins', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'axiom_welldone_get_list_line_styles' ) ) {
	function axiom_welldone_get_list_line_styles($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'axiom-welldone'),
				'dashed'=> esc_html__('Dashed', 'axiom-welldone'),
				'dotted'=> esc_html__('Dotted', 'axiom-welldone'),
				'double'=> esc_html__('Double', 'axiom-welldone'),
				'image'	=> esc_html__('Image', 'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_line_styles', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'axiom_welldone_get_list_animations' ) ) {
	function axiom_welldone_get_list_animations($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'axiom-welldone'),
				'bounced'		=> esc_html__('Bounced',		'axiom-welldone'),
				'flash'			=> esc_html__('Flash',		'axiom-welldone'),
				'flip'			=> esc_html__('Flip',		'axiom-welldone'),
				'pulse'			=> esc_html__('Pulse',		'axiom-welldone'),
				'rubberBand'	=> esc_html__('Rubber Band','axiom-welldone'),
				'shake'			=> esc_html__('Shake',		'axiom-welldone'),
				'swing'			=> esc_html__('Swing',		'axiom-welldone'),
				'tada'			=> esc_html__('Tada',		'axiom-welldone'),
				'wobble'		=> esc_html__('Wobble',		'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_animations', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'axiom_welldone_get_list_animations_in' ) ) {
	function axiom_welldone_get_list_animations_in($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'axiom-welldone'),
				'bounceIn'			=> esc_html__('Bounce In',			'axiom-welldone'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'axiom-welldone'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'axiom-welldone'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'axiom-welldone'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'axiom-welldone'),
				'fadeIn'			=> esc_html__('Fade In',			'axiom-welldone'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'axiom-welldone'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'axiom-welldone'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'axiom-welldone'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'axiom-welldone'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'axiom-welldone'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'axiom-welldone'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'axiom-welldone'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'axiom-welldone'),
				'flipInX'			=> esc_html__('Flip In X',			'axiom-welldone'),
				'flipInY'			=> esc_html__('Flip In Y',			'axiom-welldone'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'axiom-welldone'),
				'rotateIn'			=> esc_html__('Rotate In',			'axiom-welldone'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','axiom-welldone'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'axiom-welldone'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'axiom-welldone'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','axiom-welldone'),
				'rollIn'			=> esc_html__('Roll In',			'axiom-welldone'),
				'slideInUp'			=> esc_html__('Slide In Up',		'axiom-welldone'),
				'slideInDown'		=> esc_html__('Slide In Down',		'axiom-welldone'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'axiom-welldone'),
				'slideInRight'		=> esc_html__('Slide In Right',		'axiom-welldone'),
				'zoomIn'			=> esc_html__('Zoom In',			'axiom-welldone'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'axiom-welldone'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'axiom-welldone'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'axiom-welldone'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_animations_in', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'axiom_welldone_get_list_animations_out' ) ) {
	function axiom_welldone_get_list_animations_out($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'axiom-welldone'),
				'bounceOut'			=> esc_html__('Bounce Out',			'axiom-welldone'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'axiom-welldone'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'axiom-welldone'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'axiom-welldone'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'axiom-welldone'),
				'fadeOut'			=> esc_html__('Fade Out',			'axiom-welldone'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'axiom-welldone'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'axiom-welldone'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'axiom-welldone'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'axiom-welldone'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'axiom-welldone'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'axiom-welldone'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'axiom-welldone'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'axiom-welldone'),
				'flipOutX'			=> esc_html__('Flip Out X',			'axiom-welldone'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'axiom-welldone'),
				'hinge'				=> esc_html__('Hinge Out',			'axiom-welldone'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'axiom-welldone'),
				'rotateOut'			=> esc_html__('Rotate Out',			'axiom-welldone'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','axiom-welldone'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','axiom-welldone'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'axiom-welldone'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','axiom-welldone'),
				'rollOut'			=> esc_html__('Roll Out',		'axiom-welldone'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'axiom-welldone'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'axiom-welldone'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'axiom-welldone'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'axiom-welldone'),
				'zoomOut'			=> esc_html__('Zoom Out',			'axiom-welldone'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'axiom-welldone'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'axiom-welldone'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'axiom-welldone'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_animations_out', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('axiom_welldone_get_animation_classes')) {
	function axiom_welldone_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return axiom_welldone_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!axiom_welldone_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'axiom_welldone_get_list_categories' ) ) {
	function axiom_welldone_get_list_categories($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'axiom_welldone_get_list_terms' ) ) {
	function axiom_welldone_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = axiom_welldone_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = axiom_welldone_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'axiom_welldone_get_list_posts_types' ) ) {
	function axiom_welldone_get_list_posts_types($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('axiom_welldone_filter_list_post_types', array());
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'axiom_welldone_get_list_posts' ) ) {
	function axiom_welldone_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = axiom_welldone_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'axiom-welldone');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set($hash, $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'axiom_welldone_get_list_pages' ) ) {
	function axiom_welldone_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return axiom_welldone_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'axiom_welldone_get_list_users' ) ) {
	function axiom_welldone_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = axiom_welldone_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'axiom-welldone');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_users', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'axiom_welldone_get_list_sliders' ) ) {
	function axiom_welldone_get_list_sliders($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_list_sliders', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'axiom_welldone_get_list_slider_controls' ) ) {
	function axiom_welldone_get_list_slider_controls($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'axiom-welldone'),
				'side'		=> esc_html__('Side', 'axiom-welldone'),
				'bottom'	=> esc_html__('Bottom', 'axiom-welldone'),
				'pagination'=> esc_html__('Pagination', 'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_slider_controls', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'axiom_welldone_get_slider_controls_classes' ) ) {
	function axiom_welldone_get_slider_controls_classes($controls) {
		if (axiom_welldone_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'axiom_welldone_get_list_popup_engines' ) ) {
	function axiom_welldone_get_list_popup_engines($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'axiom-welldone'),
				"magnific"	=> esc_html__("Magnific popup", 'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_popup_engines', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_menus' ) ) {
	function axiom_welldone_get_list_menus($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'axiom-welldone');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'axiom_welldone_get_list_sidebars' ) ) {
	function axiom_welldone_get_list_sidebars($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_sidebars'))=='') {
			if (($list = axiom_welldone_storage_get('registered_sidebars'))=='') $list = array();
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'axiom_welldone_get_list_sidebars_positions' ) ) {
	function axiom_welldone_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'axiom-welldone'),
				'left'  => esc_html__('Left',  'axiom-welldone'),
				'right' => esc_html__('Right', 'axiom-welldone')
				);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'axiom_welldone_get_sidebar_class' ) ) {
	function axiom_welldone_get_sidebar_class() {
		$sb_main = axiom_welldone_get_custom_option('show_sidebar_main');
		$sb_outer = axiom_welldone_get_custom_option('show_sidebar_outer');
		return (axiom_welldone_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (axiom_welldone_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_body_styles' ) ) {
	function axiom_welldone_get_list_body_styles($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'axiom-welldone'),
				'wide'	=> esc_html__('Wide',		'axiom-welldone')
				);
			if (axiom_welldone_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'axiom-welldone');
				$list['fullscreen']	= esc_html__('Fullscreen',	'axiom-welldone');
			}
			$list = apply_filters('axiom_welldone_filter_list_body_styles', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_skins' ) ) {
	function axiom_welldone_get_list_skins($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_skins'))=='') {
			$list = array(
                                'less' => esc_html__('Less', 'axiom-welldone')
                        );
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'axiom_welldone_get_list_themes' ) ) {
	function axiom_welldone_get_list_themes($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_themes'))=='') {
			$list = axiom_welldone_get_list_files("css/themes");
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_templates' ) ) {
	function axiom_welldone_get_list_templates($mode='') {
		if (($list = axiom_welldone_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = axiom_welldone_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: axiom_welldone_strtoproper($v['layout'])
										);
				}
			}
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_templates_blog' ) ) {
	function axiom_welldone_get_list_templates_blog($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_templates_blog'))=='') {
			$list = axiom_welldone_get_list_templates('blog');
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_templates_blogger' ) ) {
	function axiom_welldone_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_templates_blogger'))=='') {
			$list = axiom_welldone_array_merge(axiom_welldone_get_list_templates('blogger'), axiom_welldone_get_list_templates('blog'));
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_templates_single' ) ) {
	function axiom_welldone_get_list_templates_single($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_templates_single'))=='') {
			$list = axiom_welldone_get_list_templates('single');
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_templates_header' ) ) {
	function axiom_welldone_get_list_templates_header($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_templates_header'))=='') {
			$list = axiom_welldone_get_list_templates('header');
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_templates_forms' ) ) {
	function axiom_welldone_get_list_templates_forms($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_templates_forms'))=='') {
			$list = axiom_welldone_get_list_templates('forms');
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_article_styles' ) ) {
	function axiom_welldone_get_list_article_styles($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'axiom-welldone'),
				"stretch" => esc_html__('Stretch', 'axiom-welldone')
				);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_post_formats_filters' ) ) {
	function axiom_welldone_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'axiom-welldone'),
				"thumbs"  => esc_html__('With thumbs', 'axiom-welldone'),
				"reviews" => esc_html__('With reviews', 'axiom-welldone'),
				"video"   => esc_html__('With videos', 'axiom-welldone'),
				"audio"   => esc_html__('With audios', 'axiom-welldone'),
				"gallery" => esc_html__('With galleries', 'axiom-welldone')
				);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_portfolio_filters' ) ) {
	function axiom_welldone_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'axiom-welldone'),
				"tags"		=> esc_html__('Tags', 'axiom-welldone'),
				"categories"=> esc_html__('Categories', 'axiom-welldone')
				);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_hovers' ) ) {
	function axiom_welldone_get_list_hovers($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'axiom-welldone');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'axiom-welldone');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'axiom-welldone');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'axiom-welldone');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'axiom-welldone');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'axiom-welldone');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'axiom-welldone');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'axiom-welldone');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'axiom-welldone');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'axiom-welldone');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'axiom-welldone');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'axiom-welldone');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'axiom-welldone');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'axiom-welldone');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'axiom-welldone');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'axiom-welldone');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'axiom-welldone');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'axiom-welldone');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'axiom-welldone');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'axiom-welldone');
			$list['square effect1']  = esc_html__('Square Effect 1',  'axiom-welldone');
			$list['square effect2']  = esc_html__('Square Effect 2',  'axiom-welldone');
			$list['square effect3']  = esc_html__('Square Effect 3',  'axiom-welldone');
			$list['square effect5']  = esc_html__('Square Effect 5',  'axiom-welldone');
			$list['square effect6']  = esc_html__('Square Effect 6',  'axiom-welldone');
			$list['square effect7']  = esc_html__('Square Effect 7',  'axiom-welldone');
			$list['square effect8']  = esc_html__('Square Effect 8',  'axiom-welldone');
			$list['square effect9']  = esc_html__('Square Effect 9',  'axiom-welldone');
			$list['square effect10'] = esc_html__('Square Effect 10',  'axiom-welldone');
			$list['square effect11'] = esc_html__('Square Effect 11',  'axiom-welldone');
			$list['square effect12'] = esc_html__('Square Effect 12',  'axiom-welldone');
			$list['square effect13'] = esc_html__('Square Effect 13',  'axiom-welldone');
			$list['square effect14'] = esc_html__('Square Effect 14',  'axiom-welldone');
			$list['square effect15'] = esc_html__('Square Effect 15',  'axiom-welldone');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'axiom-welldone');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'axiom-welldone');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'axiom-welldone');
			$list['square effect_more']  = esc_html__('Square Effect More',  'axiom-welldone');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'axiom-welldone');
			$list = apply_filters('axiom_welldone_filter_portfolio_hovers', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'axiom_welldone_get_list_blog_counters' ) ) {
	function axiom_welldone_get_list_blog_counters($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'axiom-welldone'),
				'likes'		=> esc_html__('Likes', 'axiom-welldone'),
				'rating'	=> esc_html__('Rating', 'axiom-welldone'),
				'comments'	=> esc_html__('Comments', 'axiom-welldone')
				);
			$list = apply_filters('axiom_welldone_filter_list_blog_counters', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_alter_sizes' ) ) {
	function axiom_welldone_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'axiom-welldone'),
					'1_2' => esc_html__('1x2', 'axiom-welldone'),
					'2_1' => esc_html__('2x1', 'axiom-welldone'),
					'2_2' => esc_html__('2x2', 'axiom-welldone'),
					'1_3' => esc_html__('1x3', 'axiom-welldone'),
					'2_3' => esc_html__('2x3', 'axiom-welldone'),
					'3_1' => esc_html__('3x1', 'axiom-welldone'),
					'3_2' => esc_html__('3x2', 'axiom-welldone'),
					'3_3' => esc_html__('3x3', 'axiom-welldone')
					);
			$list = apply_filters('axiom_welldone_filter_portfolio_alter_sizes', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_hovers_directions' ) ) {
	function axiom_welldone_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'axiom-welldone'),
				'right_to_left' => esc_html__('Right to Left',  'axiom-welldone'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'axiom-welldone'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'axiom-welldone'),
				'scale_up'      => esc_html__('Scale Up',  'axiom-welldone'),
				'scale_down'    => esc_html__('Scale Down',  'axiom-welldone'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'axiom-welldone'),
				'from_left_and_right' => esc_html__('From Left and Right',  'axiom-welldone'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_portfolio_hovers_directions', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'axiom_welldone_get_list_label_positions' ) ) {
	function axiom_welldone_get_list_label_positions($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'axiom-welldone'),
				'bottom'	=> esc_html__('Bottom',		'axiom-welldone'),
				'left'		=> esc_html__('Left',		'axiom-welldone'),
				'over'		=> esc_html__('Over',		'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_label_positions', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'axiom_welldone_get_list_bg_image_positions' ) ) {
	function axiom_welldone_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'axiom-welldone'),
				'center top'   => esc_html__("Center Top", 'axiom-welldone'),
				'right top'    => esc_html__("Right Top", 'axiom-welldone'),
				'left center'  => esc_html__("Left Center", 'axiom-welldone'),
				'center center'=> esc_html__("Center Center", 'axiom-welldone'),
				'right center' => esc_html__("Right Center", 'axiom-welldone'),
				'left bottom'  => esc_html__("Left Bottom", 'axiom-welldone'),
				'center bottom'=> esc_html__("Center Bottom", 'axiom-welldone'),
				'right bottom' => esc_html__("Right Bottom", 'axiom-welldone')
			);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'axiom_welldone_get_list_bg_image_repeats' ) ) {
	function axiom_welldone_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'axiom-welldone'),
				'repeat-x'	=> esc_html__('Repeat X', 'axiom-welldone'),
				'repeat-y'	=> esc_html__('Repeat Y', 'axiom-welldone'),
				'no-repeat'	=> esc_html__('No Repeat', 'axiom-welldone')
			);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'axiom_welldone_get_list_bg_image_attachments' ) ) {
	function axiom_welldone_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'axiom-welldone'),
				'fixed'		=> esc_html__('Fixed', 'axiom-welldone'),
				'local'		=> esc_html__('Local', 'axiom-welldone')
			);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'axiom_welldone_get_list_bg_tints' ) ) {
	function axiom_welldone_get_list_bg_tints($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'axiom-welldone'),
				'light'	=> esc_html__('Light', 'axiom-welldone'),
				'dark'	=> esc_html__('Dark', 'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_bg_tints', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_field_types' ) ) {
	function axiom_welldone_get_list_field_types($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'axiom-welldone'),
				'textarea' => esc_html__('Text Area','axiom-welldone'),
				'password' => esc_html__('Password',  'axiom-welldone'),
				'radio'    => esc_html__('Radio',  'axiom-welldone'),
				'checkbox' => esc_html__('Checkbox',  'axiom-welldone'),
				'select'   => esc_html__('Select',  'axiom-welldone'),
				'date'     => esc_html__('Date','axiom-welldone'),
				'time'     => esc_html__('Time','axiom-welldone'),
				'button'   => esc_html__('Button','axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_field_types', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'axiom_welldone_get_list_googlemap_styles' ) ) {
	function axiom_welldone_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_googlemap_styles', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return images list
if (!function_exists('axiom_welldone_get_list_images')) {	
	function axiom_welldone_get_list_images($folder, $ext='', $only_names=false) {
		return function_exists('trx_utils_get_folder_list') ? trx_utils_get_folder_list($folder, $ext, $only_names) : array();
	}
}

// Return iconed classes list
if ( !function_exists( 'axiom_welldone_get_list_icons' ) ) {
	function axiom_welldone_get_list_icons($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_icons'))=='') {
			$list = axiom_welldone_parse_icons_classes(axiom_welldone_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'axiom_welldone_get_list_socials' ) ) {
	function axiom_welldone_get_list_socials($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_socials'))=='') {
			$list = axiom_welldone_get_list_images(AXIOM_WELLDONE_FW_DIR."/images/socials", "png");
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'axiom_welldone_get_list_flags' ) ) {
	function axiom_welldone_get_list_flags($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_flags'))=='') {
			$list = axiom_welldone_get_list_files("images/flags", "png");
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'axiom_welldone_get_list_yesno' ) ) {
	function axiom_welldone_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'axiom-welldone'),
			'no'  => esc_html__("No", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'axiom_welldone_get_list_onoff' ) ) {
	function axiom_welldone_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'axiom-welldone'),
			"off" => esc_html__("Off", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'axiom_welldone_get_list_showhide' ) ) {
	function axiom_welldone_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'axiom-welldone'),
			"hide" => esc_html__("Hide", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'axiom_welldone_get_list_orderings' ) ) {
	function axiom_welldone_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'axiom-welldone'),
			"desc" => esc_html__("Descending", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'axiom_welldone_get_list_directions' ) ) {
	function axiom_welldone_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'axiom-welldone'),
			"vertical" => esc_html__("Vertical", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'axiom_welldone_get_list_shapes' ) ) {
	function axiom_welldone_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'axiom-welldone'),
			"square" => esc_html__("Square", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'axiom_welldone_get_list_sizes' ) ) {
	function axiom_welldone_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'axiom-welldone'),
			"small"  => esc_html__("Small", 'axiom-welldone'),
			"medium" => esc_html__("Medium", 'axiom-welldone'),
			"large"  => esc_html__("Large", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'axiom_welldone_get_list_controls' ) ) {
	function axiom_welldone_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'axiom-welldone'),
			"side" => esc_html__("Side", 'axiom-welldone'),
			"bottom" => esc_html__("Bottom", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'axiom_welldone_get_list_floats' ) ) {
	function axiom_welldone_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'axiom-welldone'),
			"left" => esc_html__("Float Left", 'axiom-welldone'),
			"right" => esc_html__("Float Right", 'axiom-welldone')
		);
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'axiom_welldone_get_list_alignments' ) ) {
	function axiom_welldone_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'axiom-welldone'),
			"left" => esc_html__("Left", 'axiom-welldone'),
			"center" => esc_html__("Center", 'axiom-welldone'),
			"right" => esc_html__("Right", 'axiom-welldone')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'axiom-welldone');
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'axiom_welldone_get_list_hpos' ) ) {
	function axiom_welldone_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'axiom-welldone');
		if ($center) $list['center'] = esc_html__("Center", 'axiom-welldone');
		$list['right'] = esc_html__("Right", 'axiom-welldone');
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'axiom_welldone_get_list_vpos' ) ) {
	function axiom_welldone_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'axiom-welldone');
		if ($center) $list['center'] = esc_html__("Center", 'axiom-welldone');
		$list['bottom'] = esc_html__("Bottom", 'axiom-welldone');
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'axiom_welldone_get_list_sortings' ) ) {
	function axiom_welldone_get_list_sortings($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'axiom-welldone'),
				"title" => esc_html__("Alphabetically", 'axiom-welldone'),
				"views" => esc_html__("Popular (views count)", 'axiom-welldone'),
				"comments" => esc_html__("Most commented (comments count)", 'axiom-welldone'),
				"author_rating" => esc_html__("Author rating", 'axiom-welldone'),
				"users_rating" => esc_html__("Visitors (users) rating", 'axiom-welldone'),
				"random" => esc_html__("Random", 'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_list_sortings', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'axiom_welldone_get_list_columns' ) ) {
	function axiom_welldone_get_list_columns($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'axiom-welldone'),
				"1_1" => esc_html__("100%", 'axiom-welldone'),
				"1_2" => esc_html__("1/2", 'axiom-welldone'),
				"1_3" => esc_html__("1/3", 'axiom-welldone'),
				"2_3" => esc_html__("2/3", 'axiom-welldone'),
				"1_4" => esc_html__("1/4", 'axiom-welldone'),
				"3_4" => esc_html__("3/4", 'axiom-welldone'),
				"1_5" => esc_html__("1/5", 'axiom-welldone'),
				"2_5" => esc_html__("2/5", 'axiom-welldone'),
				"3_5" => esc_html__("3/5", 'axiom-welldone'),
				"4_5" => esc_html__("4/5", 'axiom-welldone'),
				"1_6" => esc_html__("1/6", 'axiom-welldone'),
				"5_6" => esc_html__("5/6", 'axiom-welldone'),
				"1_7" => esc_html__("1/7", 'axiom-welldone'),
				"2_7" => esc_html__("2/7", 'axiom-welldone'),
				"3_7" => esc_html__("3/7", 'axiom-welldone'),
				"4_7" => esc_html__("4/7", 'axiom-welldone'),
				"5_7" => esc_html__("5/7", 'axiom-welldone'),
				"6_7" => esc_html__("6/7", 'axiom-welldone'),
				"1_8" => esc_html__("1/8", 'axiom-welldone'),
				"3_8" => esc_html__("3/8", 'axiom-welldone'),
				"5_8" => esc_html__("5/8", 'axiom-welldone'),
				"7_8" => esc_html__("7/8", 'axiom-welldone'),
				"1_9" => esc_html__("1/9", 'axiom-welldone'),
				"2_9" => esc_html__("2/9", 'axiom-welldone'),
				"4_9" => esc_html__("4/9", 'axiom-welldone'),
				"5_9" => esc_html__("5/9", 'axiom-welldone'),
				"7_9" => esc_html__("7/9", 'axiom-welldone'),
				"8_9" => esc_html__("8/9", 'axiom-welldone'),
				"1_10"=> esc_html__("1/10", 'axiom-welldone'),
				"3_10"=> esc_html__("3/10", 'axiom-welldone'),
				"7_10"=> esc_html__("7/10", 'axiom-welldone'),
				"9_10"=> esc_html__("9/10", 'axiom-welldone'),
				"1_11"=> esc_html__("1/11", 'axiom-welldone'),
				"2_11"=> esc_html__("2/11", 'axiom-welldone'),
				"3_11"=> esc_html__("3/11", 'axiom-welldone'),
				"4_11"=> esc_html__("4/11", 'axiom-welldone'),
				"5_11"=> esc_html__("5/11", 'axiom-welldone'),
				"6_11"=> esc_html__("6/11", 'axiom-welldone'),
				"7_11"=> esc_html__("7/11", 'axiom-welldone'),
				"8_11"=> esc_html__("8/11", 'axiom-welldone'),
				"9_11"=> esc_html__("9/11", 'axiom-welldone'),
				"10_11"=> esc_html__("10/11", 'axiom-welldone'),
				"1_12"=> esc_html__("1/12", 'axiom-welldone'),
				"5_12"=> esc_html__("5/12", 'axiom-welldone'),
				"7_12"=> esc_html__("7/12", 'axiom-welldone'),
				"10_12"=> esc_html__("10/12", 'axiom-welldone'),
				"11_12"=> esc_html__("11/12", 'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_list_columns', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'axiom_welldone_get_list_dedicated_locations' ) ) {
	function axiom_welldone_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'axiom-welldone'),
				"center"  => esc_html__('Above the text of the post', 'axiom-welldone'),
				"left"    => esc_html__('To the left the text of the post', 'axiom-welldone'),
				"right"   => esc_html__('To the right the text of the post', 'axiom-welldone'),
				"alter"   => esc_html__('Alternates for each post', 'axiom-welldone')
			);
			$list = apply_filters('axiom_welldone_filter_list_dedicated_locations', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'axiom_welldone_get_post_format_name' ) ) {
	function axiom_welldone_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'axiom-welldone') : esc_html__('galleries', 'axiom-welldone');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'axiom-welldone') : esc_html__('videos', 'axiom-welldone');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'axiom-welldone') : esc_html__('audios', 'axiom-welldone');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'axiom-welldone') : esc_html__('images', 'axiom-welldone');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'axiom-welldone') : esc_html__('quotes', 'axiom-welldone');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'axiom-welldone') : esc_html__('links', 'axiom-welldone');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'axiom-welldone') : esc_html__('statuses', 'axiom-welldone');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'axiom-welldone') : esc_html__('asides', 'axiom-welldone');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'axiom-welldone') : esc_html__('chats', 'axiom-welldone');
		else						$name = $single ? esc_html__('standard', 'axiom-welldone') : esc_html__('standards', 'axiom-welldone');
		return apply_filters('axiom_welldone_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'axiom_welldone_get_post_format_icon' ) ) {
	function axiom_welldone_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('axiom_welldone_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'axiom_welldone_get_list_fonts_styles' ) ) {
	function axiom_welldone_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','axiom-welldone'),
				'u' => esc_html__('U', 'axiom-welldone')
			);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'axiom_welldone_get_list_fonts' ) ) {
	function axiom_welldone_get_list_fonts($prepend_inherit=false) {
		if (($list = axiom_welldone_storage_get('list_fonts'))=='') {
			$list = array();
			$list = axiom_welldone_array_merge($list, axiom_welldone_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>axiom_welldone_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = axiom_welldone_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Hind' => array('family'=>'sans-serif'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('axiom_welldone_filter_list_fonts', $list);
			if (axiom_welldone_get_theme_setting('use_list_cache')) axiom_welldone_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? axiom_welldone_array_merge(array('inherit' => esc_html__("Inherit", 'axiom-welldone')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'axiom_welldone_get_list_font_faces' ) ) {
	function axiom_welldone_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$fonts = axiom_welldone_storage_get('required_custom_fonts');
		$list = array();
		if (is_array($fonts)) {
			foreach ($fonts as $font) {
				if (($url = axiom_welldone_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
					$list[sprintf(esc_html__('%s (uploaded font)', 'axiom-welldone'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>