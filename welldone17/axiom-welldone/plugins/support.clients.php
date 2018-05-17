<?php
/**
 * Axiom Welldone Framework: Clients support
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Theme init
if (!function_exists('axiom_welldone_clients_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_clients_theme_setup', 1 );
	function axiom_welldone_clients_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('axiom_welldone_filter_get_blog_type',			'axiom_welldone_clients_get_blog_type', 9, 2);
		add_filter('axiom_welldone_filter_get_blog_title',		'axiom_welldone_clients_get_blog_title', 9, 2);
		add_filter('axiom_welldone_filter_get_current_taxonomy',	'axiom_welldone_clients_get_current_taxonomy', 9, 2);
		add_filter('axiom_welldone_filter_is_taxonomy',			'axiom_welldone_clients_is_taxonomy', 9, 2);
		add_filter('axiom_welldone_filter_get_stream_page_title',	'axiom_welldone_clients_get_stream_page_title', 9, 2);
		add_filter('axiom_welldone_filter_get_stream_page_link',	'axiom_welldone_clients_get_stream_page_link', 9, 2);
		add_filter('axiom_welldone_filter_get_stream_page_id',	'axiom_welldone_clients_get_stream_page_id', 9, 2);
		add_filter('axiom_welldone_filter_query_add_filters',		'axiom_welldone_clients_query_add_filters', 9, 2);
		add_filter('axiom_welldone_filter_detect_inheritance_key','axiom_welldone_clients_detect_inheritance_key', 9, 1);

		// Extra column for clients lists
		if (axiom_welldone_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-clients_columns',			'axiom_welldone_post_add_options_column', 9);
			add_filter('manage_clients_posts_custom_column',	'axiom_welldone_post_fill_options_column', 9, 2);
		}

		// Registar shortcodes [trx_clients] and [trx_clients_item] in the shortcodes list
		add_action('axiom_welldone_action_shortcodes_list',		'axiom_welldone_clients_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_clients_reg_shortcodes_vc');
		
		// Add supported data types
		axiom_welldone_theme_support_pt('clients');
		axiom_welldone_theme_support_tx('clients_group');
	}
}

if ( !function_exists( 'axiom_welldone_clients_settings_theme_setup2' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_clients_settings_theme_setup2', 3 );
	function axiom_welldone_clients_settings_theme_setup2() {
		// Add post type 'clients' and taxonomy 'clients_group' into theme inheritance list
		axiom_welldone_add_theme_inheritance( array('clients' => array(
			'stream_template' => 'blog-clients',
			'single_template' => 'single-client',
			'taxonomy' => array('clients_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('clients'),
			'override' => 'custom'
			) )
		);
	}
}


if (!function_exists('axiom_welldone_clients_after_theme_setup')) {
	add_action( 'axiom_welldone_action_after_init_theme', 'axiom_welldone_clients_after_theme_setup' );
	function axiom_welldone_clients_after_theme_setup() {
		// Update fields in the meta box
		if (axiom_welldone_storage_get_array('post_meta_box', 'page')=='clients') {
			// Meta box fields
			axiom_welldone_storage_set_array('post_meta_box', 'title', esc_html__('Client Options', 'axiom-welldone'));
			axiom_welldone_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_clients" => array(
					"title" => esc_html__('Clients', 'axiom-welldone'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "iconadmin-users",
					"type" => "partition"),
				"mb_info_clients_1" => array(
					"title" => esc_html__('Client details', 'axiom-welldone'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => wp_kses_data( __('In this section you can put details for this client', 'axiom-welldone') ),
					"class" => "client_meta",
					"type" => "info"),
				"client_name" => array(
					"title" => esc_html__('Contact name',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Name of the contacts manager", 'axiom-welldone') ),
					"override" => "page,post,custom",
					"class" => "client_name",
					"std" => '',
					"type" => "text"),
				"client_position" => array(
					"title" => esc_html__('Position',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Position of the contacts manager", 'axiom-welldone') ),
					"override" => "page,post,custom",
					"class" => "client_position",
					"std" => '',
					"type" => "text"),
				"client_show_link" => array(
					"title" => esc_html__('Show link',  'axiom-welldone'),
					"desc" => wp_kses_data( __("Show link to client page", 'axiom-welldone') ),
					"override" => "page,post,custom",
					"class" => "client_show_link",
					"std" => "no",
					"options" => axiom_welldone_get_list_yesno(),
					"type" => "switch"),
				"client_link" => array(
					"title" => esc_html__('Link',  'axiom-welldone'),
					"desc" => wp_kses_data( __("URL of the client's site. If empty - use link to this page", 'axiom-welldone') ),
					"override" => "page,post,custom",
					"class" => "client_link",
					"std" => '',
					"type" => "text")
				)
			);
		}
	}
}


// Return true, if current page is clients page
if ( !function_exists( 'axiom_welldone_is_clients_page' ) ) {
	function axiom_welldone_is_clients_page() {
		$is = in_array(axiom_welldone_storage_get('page_template'), array('blog-clients', 'single-client'));
		if (!$is) {
			if (!axiom_welldone_storage_empty('pre_query'))
				$is = axiom_welldone_storage_call_obj_method('pre_query', 'get', 'post_type')=='clients'
						|| axiom_welldone_storage_call_obj_method('pre_query', 'is_tax', 'clients_group') 
						|| (axiom_welldone_storage_call_obj_method('pre_query', 'is_page') 
							&& ($id=axiom_welldone_get_template_page_id('blog-clients')) > 0 
							&& $id==axiom_welldone_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='clients' 
						|| is_tax('clients_group') 
						|| (is_page() && ($id=axiom_welldone_get_template_page_id('blog-clients')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiom_welldone_clients_detect_inheritance_key' ) ) {
	//add_filter('axiom_welldone_filter_detect_inheritance_key',	'axiom_welldone_clients_detect_inheritance_key', 9, 1);
	function axiom_welldone_clients_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return axiom_welldone_is_clients_page() ? 'clients' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'axiom_welldone_clients_get_blog_type' ) ) {
	//add_filter('axiom_welldone_filter_get_blog_type',	'axiom_welldone_clients_get_blog_type', 9, 2);
	function axiom_welldone_clients_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('clients_group') || is_tax('clients_group'))
			$page = 'clients_category';
		else if ($query && $query->get('post_type')=='clients' || get_query_var('post_type')=='clients')
			$page = $query && $query->is_single() || is_single() ? 'clients_item' : 'clients';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'axiom_welldone_clients_get_blog_title' ) ) {
	//add_filter('axiom_welldone_filter_get_blog_title',	'axiom_welldone_clients_get_blog_title', 9, 2);
	function axiom_welldone_clients_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( axiom_welldone_strpos($page, 'clients')!==false ) {
			if ( $page == 'clients_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'clients_group' ), 'clients_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'clients_item' ) {
				$title = axiom_welldone_get_post_title();
			} else {
				$title = esc_html__('All clients', 'axiom-welldone');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'axiom_welldone_clients_get_stream_page_title' ) ) {
	//add_filter('axiom_welldone_filter_get_stream_page_title',	'axiom_welldone_clients_get_stream_page_title', 9, 2);
	function axiom_welldone_clients_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (axiom_welldone_strpos($page, 'clients')!==false) {
			if (($page_id = axiom_welldone_clients_get_stream_page_id(0, $page=='clients' ? 'blog-clients' : $page)) > 0)
				$title = axiom_welldone_get_post_title($page_id);
			else
				$title = esc_html__('All clients', 'axiom-welldone');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'axiom_welldone_clients_get_stream_page_id' ) ) {
	//add_filter('axiom_welldone_filter_get_stream_page_id',	'axiom_welldone_clients_get_stream_page_id', 9, 2);
	function axiom_welldone_clients_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (axiom_welldone_strpos($page, 'clients')!==false) $id = axiom_welldone_get_template_page_id('blog-clients');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'axiom_welldone_clients_get_stream_page_link' ) ) {
	//add_filter('axiom_welldone_filter_get_stream_page_link',	'axiom_welldone_clients_get_stream_page_link', 9, 2);
	function axiom_welldone_clients_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (axiom_welldone_strpos($page, 'clients')!==false) {
			$id = axiom_welldone_get_template_page_id('blog-clients');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'axiom_welldone_clients_get_current_taxonomy' ) ) {
	//add_filter('axiom_welldone_filter_get_current_taxonomy',	'axiom_welldone_clients_get_current_taxonomy', 9, 2);
	function axiom_welldone_clients_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( axiom_welldone_strpos($page, 'clients')!==false ) {
			$tax = 'clients_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'axiom_welldone_clients_is_taxonomy' ) ) {
	//add_filter('axiom_welldone_filter_is_taxonomy',	'axiom_welldone_clients_is_taxonomy', 9, 2);
	function axiom_welldone_clients_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('clients_group')!='' || is_tax('clients_group') ? 'clients_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'axiom_welldone_clients_query_add_filters' ) ) {
	//add_filter('axiom_welldone_filter_query_add_filters',	'axiom_welldone_clients_query_add_filters', 9, 2);
	function axiom_welldone_clients_query_add_filters($args, $filter) {
		if ($filter == 'clients') {
			$args['post_type'] = 'clients';
		}
		return $args;
	}
}





// ---------------------------------- [trx_clients] ---------------------------------------

/*
[trx_clients id="unique_id" columns="3" style="clients-1|clients-2|..."]
	[trx_clients_item name="client name" position="director" image="url"]Description text[/trx_clients_item]
	...
[/trx_clients]
*/
if ( !function_exists( 'axiom_welldone_sc_clients' ) ) {
	function axiom_welldone_sc_clients($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "clients-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "title",
			"order" => "asc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'axiom-welldone'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_clients_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && axiom_welldone_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);

		$ws = axiom_welldone_get_css_dimensions_from_values($width);
		$hs = axiom_welldone_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		if (axiom_welldone_param_is_on($slider)) axiom_welldone_enqueue_slider('swiper');
	
		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if (axiom_welldone_param_is_off($custom) && $count < $columns) $columns = $count;
		axiom_welldone_storage_set('sc_clients_data', array(
			'id'=>$id,
            'style'=>$style,
            'counter'=>0,
            'columns'=>$columns,
            'slider'=>$slider,
            'css_wh'=>$ws . $hs
            )
        );

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_clients_wrap'
						. ($scheme && !axiom_welldone_param_is_off($scheme) && !axiom_welldone_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_clients sc_clients_style_'.esc_attr($style)
							. ' ' . esc_attr(axiom_welldone_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_clients_subtitle sc_item_subtitle">' . trim(axiom_welldone_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_clients_title sc_item_title">' . trim(axiom_welldone_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_clients_descr sc_item_descr">' . trim(axiom_welldone_strmacros($description)) . '</div>' : '')
					. (axiom_welldone_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(axiom_welldone_get_slider_controls_classes($controls))
										. (axiom_welldone_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && axiom_welldone_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && axiom_welldone_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ' data-slides-min-width="' . ($style=='clients-1' ? 130 : '') . '"'
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		if (axiom_welldone_param_is_on($custom) && $content) {
			$output .= do_shortcode($content);
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'clients',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = axiom_welldone_query_add_sort_order($args, $orderby, $order);
			$args = axiom_welldone_query_add_posts_and_cats($args, $ids, 'clients', $cat, 'clients_group');

			$query = new WP_Query( $args );
	
			$post_number = 0;

			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => axiom_welldone_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = axiom_welldone_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], axiom_welldone_storage_get('options_prefix') . '_post_options', true);
				$thumb_sizes = axiom_welldone_get_thumb_sizes(array('layout' => $style));
				$args['client_name'] = $post_meta['client_name'];
				$args['client_position'] = $post_meta['client_position'];
				$args['client_image'] = $post_data['post_thumb'];
				$args['client_link'] = axiom_welldone_param_is_on('client_show_link')
					? (!empty($post_meta['client_link']) ? $post_meta['client_link'] : $post_data['post_link'])
					: '';
				$output .= axiom_welldone_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (axiom_welldone_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_clients_button sc_item_button">'.axiom_welldone_do_shortcode('[trx_button link="'.esc_url($link).'"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div><!-- /.sc_clients -->'
			. '</div><!-- /.sc_clients_wrap -->';
	
		// Add template specific scripts and styles
		do_action('axiom_welldone_action_blog_scripts', $style);
	
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_clients', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_clients', 'axiom_welldone_sc_clients');
}


if ( !function_exists( 'axiom_welldone_sc_clients_item' ) ) {
	function axiom_welldone_sc_clients_item($atts, $content=null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts( array(
			// Individual params
			"name" => "",
			"position" => "",
			"image" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		axiom_welldone_storage_inc_array('sc_clients_data', 'counter');
	
		$id = $id ? $id : (axiom_welldone_storage_get_array('sc_clients_data', 'id') ? axiom_welldone_storage_get_array('sc_clients_data', 'id') . '_' . axiom_welldone_storage_get_array('sc_clients_data', 'counter') : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = axiom_welldone_get_thumb_sizes(array('layout' => axiom_welldone_storage_get_array('sc_clients_data', 'style')));

		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$image = axiom_welldone_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => axiom_welldone_storage_get_array('sc_clients_data', 'style'),
			'number' => axiom_welldone_storage_get_array('sc_clients_data', 'counter'),
			'columns_count' => axiom_welldone_storage_get_array('sc_clients_data', 'columns'),
			'slider' => axiom_welldone_storage_get_array('sc_clients_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => axiom_welldone_storage_get_array('sc_clients_data', 'css_wh'),
			'client_position' => $position,
			'client_link' => $link,
			'client_image' => $image
		);
		$output = axiom_welldone_show_post_layout($args, $post_data);
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_clients_item', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_clients_item', 'axiom_welldone_sc_clients_item');
}
// ---------------------------------- [/trx_clients] ---------------------------------------



// Add [trx_clients] and [trx_clients_item] in the shortcodes list
if (!function_exists('axiom_welldone_clients_reg_shortcodes')) {
	//add_filter('axiom_welldone_action_shortcodes_list',	'axiom_welldone_clients_reg_shortcodes');
	function axiom_welldone_clients_reg_shortcodes() {
		if (axiom_welldone_storage_isset('shortcodes')) {

			$users = axiom_welldone_get_list_users();
			$members = axiom_welldone_get_list_posts(false, array(
				'post_type'=>'clients',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$clients_groups = axiom_welldone_get_list_terms(false, 'clients_group');
			$clients_styles = axiom_welldone_get_list_templates('clients');
			$controls 		= axiom_welldone_get_list_slider_controls();

			axiom_welldone_sc_map_after('trx_chat', array(

				// Clients
				"trx_clients" => array(
					"title" => esc_html__("Clients", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Insert clients list in your page (post)", 'axiom-welldone') ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Title for the block", 'axiom-welldone') ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'axiom-welldone') ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Short description for the block", 'axiom-welldone') ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Clients style", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Select style to display clients list", 'axiom-welldone') ),
							"value" => "clients-1",
							"type" => "select",
							"options" => $clients_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'axiom-welldone'),
							"desc" => wp_kses_data( __("How many columns use to show clients", 'axiom-welldone') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'axiom-welldone') ),
							"value" => "",
							"type" => "checklist",
							"options" => axiom_welldone_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Use slider to show clients", 'axiom-welldone') ),
							"value" => "no",
							"type" => "switch",
							"options" => axiom_welldone_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'axiom-welldone') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'axiom-welldone') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'axiom-welldone') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'axiom-welldone') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => axiom_welldone_get_sc_param('yes_no')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'axiom-welldone') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => axiom_welldone_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'axiom-welldone') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => axiom_welldone_array_merge(array(0 => esc_html__('- Select category -', 'axiom-welldone')), $clients_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'axiom-welldone'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'axiom-welldone') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'axiom-welldone') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'axiom-welldone') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => axiom_welldone_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Select desired posts order", 'axiom-welldone') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => axiom_welldone_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'axiom-welldone') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'axiom-welldone') ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'axiom-welldone'),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'axiom-welldone') ),
							"value" => "",
							"type" => "text"
						),
						"width" => axiom_welldone_shortcodes_width(),
						"height" => axiom_welldone_shortcodes_height(),
						"top" => axiom_welldone_get_sc_param('top'),
						"bottom" => axiom_welldone_get_sc_param('bottom'),
						"left" => axiom_welldone_get_sc_param('left'),
						"right" => axiom_welldone_get_sc_param('right'),
						"id" => axiom_welldone_get_sc_param('id'),
						"class" => axiom_welldone_get_sc_param('class'),
						"animation" => axiom_welldone_get_sc_param('animation'),
						"css" => axiom_welldone_get_sc_param('css')
					),
					"children" => array(
						"name" => "trx_clients_item",
						"title" => esc_html__("Client", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Single client (custom parameters)", 'axiom-welldone') ),
						"container" => true,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Name", 'axiom-welldone'),
								"desc" => wp_kses_data( __("Client's name", 'axiom-welldone') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'axiom-welldone'),
								"desc" => wp_kses_data( __("Client's position", 'axiom-welldone') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'axiom-welldone'),
								"desc" => wp_kses_data( __("Link on client's personal page", 'axiom-welldone') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"image" => array(
								"title" => esc_html__("Image", 'axiom-welldone'),
								"desc" => wp_kses_data( __("Client's image", 'axiom-welldone') ),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'axiom-welldone'),
								"desc" => wp_kses_data( __("Client's short description", 'axiom-welldone') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => axiom_welldone_get_sc_param('id'),
							"class" => axiom_welldone_get_sc_param('class'),
							"animation" => axiom_welldone_get_sc_param('animation'),
							"css" => axiom_welldone_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_clients] and [trx_clients_item] in the VC shortcodes list
if (!function_exists('axiom_welldone_clients_reg_shortcodes_vc')) {
	//add_filter('axiom_welldone_action_shortcodes_list_vc',	'axiom_welldone_clients_reg_shortcodes_vc');
	function axiom_welldone_clients_reg_shortcodes_vc() {

		$clients_groups = axiom_welldone_get_list_terms(false, 'clients_group');
		$clients_styles = axiom_welldone_get_list_templates('clients');
		$controls		= axiom_welldone_get_list_slider_controls();

		// Clients
		vc_map( array(
				"base" => "trx_clients",
				"name" => esc_html__("Clients", 'axiom-welldone'),
				"description" => wp_kses_data( __("Insert clients list", 'axiom-welldone') ),
				"category" => esc_html__('Content', 'axiom-welldone'),
				'icon' => 'icon_trx_clients',
				"class" => "trx_sc_columns trx_sc_clients",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_clients_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Clients style", 'axiom-welldone'),
						"description" => wp_kses_data( __("Select style to display clients list", 'axiom-welldone') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($clients_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'axiom-welldone'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'axiom-welldone') ),
						"class" => "",
						"value" => array_flip(axiom_welldone_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'axiom-welldone'),
						"description" => wp_kses_data( __("Use slider to show testimonials", 'axiom-welldone') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'axiom-welldone'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(axiom_welldone_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'axiom-welldone'),
						"description" => wp_kses_data( __("Slider controls style and position", 'axiom-welldone') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'axiom-welldone'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'axiom-welldone') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'axiom-welldone'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'axiom-welldone') ),
						"group" => esc_html__('Slider', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'axiom-welldone'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'axiom-welldone') ),
						"group" => esc_html__('Slider', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'axiom-welldone'),
						"description" => wp_kses_data( __("Allow get clients from inner shortcodes (custom) or get it from specified group (cat)", 'axiom-welldone') ),
						"class" => "",
						"value" => array("Custom clients" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'axiom-welldone'),
						"description" => wp_kses_data( __("Title for the block", 'axiom-welldone') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'axiom-welldone'),
						"description" => wp_kses_data( __("Subtitle for the block", 'axiom-welldone') ),
						"group" => esc_html__('Captions', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'axiom-welldone'),
						"description" => wp_kses_data( __("Description for the block", 'axiom-welldone') ),
						"group" => esc_html__('Captions', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'axiom-welldone'),
						"description" => wp_kses_data( __("Select category to show clients. If empty - select clients from any category (group) or from IDs list", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(axiom_welldone_array_merge(array(0 => esc_html__('- Select category -', 'axiom-welldone')), $clients_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'axiom-welldone'),
						"description" => wp_kses_data( __("How many columns use to show clients", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'axiom-welldone'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'axiom-welldone'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'axiom-welldone'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "title",
						"class" => "",
						"value" => array_flip(axiom_welldone_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'axiom-welldone'),
						"description" => wp_kses_data( __("Select desired posts order", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "asc",
						"class" => "",
						"value" => array_flip(axiom_welldone_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", 'axiom-welldone'),
						"description" => wp_kses_data( __("Comma separated list of client's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'axiom-welldone') ),
						"group" => esc_html__('Query', 'axiom-welldone'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'axiom-welldone'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'axiom-welldone') ),
						"group" => esc_html__('Captions', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'axiom-welldone'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'axiom-welldone') ),
						"group" => esc_html__('Captions', 'axiom-welldone'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiom_welldone_vc_width(),
					axiom_welldone_vc_height(),
					axiom_welldone_get_vc_param('margin_top'),
					axiom_welldone_get_vc_param('margin_bottom'),
					axiom_welldone_get_vc_param('margin_left'),
					axiom_welldone_get_vc_param('margin_right'),
					axiom_welldone_get_vc_param('id'),
					axiom_welldone_get_vc_param('class'),
					axiom_welldone_get_vc_param('animation'),
					axiom_welldone_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_clients_item",
				"name" => esc_html__("Client", 'axiom-welldone'),
				"description" => wp_kses_data( __("Client - all data pull out from it account on your site", 'axiom-welldone') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_clients_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_clients_item',
				"as_child" => array('only' => 'trx_clients'),
				"as_parent" => array('except' => 'trx_clients'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'axiom-welldone'),
						"description" => wp_kses_data( __("Client's name", 'axiom-welldone') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", 'axiom-welldone'),
						"description" => wp_kses_data( __("Client's position", 'axiom-welldone') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'axiom-welldone'),
						"description" => wp_kses_data( __("Link on client's personal page", 'axiom-welldone') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Client's image", 'axiom-welldone'),
						"description" => wp_kses_data( __("Clients's image", 'axiom-welldone') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					axiom_welldone_get_vc_param('id'),
					axiom_welldone_get_vc_param('class'),
					axiom_welldone_get_vc_param('animation'),
					axiom_welldone_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Clients extends AXIOM_WELLDONE_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Clients_Item extends AXIOM_WELLDONE_VC_ShortCodeCollection {}

	}
}
?>