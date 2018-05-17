<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_sc_skills_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_sc_skills_theme_setup' );
	function axiom_welldone_sc_skills_theme_setup() {
		add_action('axiom_welldone_action_shortcodes_list', 		'axiom_welldone_sc_skills_reg_shortcodes');
		if (function_exists('axiom_welldone_exists_visual_composer') && axiom_welldone_exists_visual_composer())
			add_action('axiom_welldone_action_shortcodes_list_vc','axiom_welldone_sc_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('axiom_welldone_sc_skills')) {	
	function axiom_welldone_sc_skills($atts, $content=null){	
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => esc_html__("Skills", 'axiom-welldone'),
			"pie_compact" => "on",
			"pie_cutout" => 0,
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'axiom-welldone'),
			"link" => '',
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
		axiom_welldone_storage_set('sc_skills_data', array(
			'counter' => 0,
            'columns' => 0,
            'height'  => 0,
            'type'    => $type,
            'pie_compact' => axiom_welldone_param_is_on($pie_compact) ? 'on' : 'off',
            'pie_cutout'  => max(0, min(99, $pie_cutout)),
            'circle_compact' => 'off',
            'circle_cutout'  => 96,
            'color'   => $color,
            'bg_color'=> $bg_color,
            'border_color'=> $border_color,
            'legend'  => '',
            'data'    => ''
			)
		);
		axiom_welldone_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') axiom_welldone_storage_set_array('sc_skills_data', 'columns', $columns);
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			axiom_welldone_storage_set_array('sc_skills_data', 'style', $style);
		}
		axiom_welldone_storage_set_array('sc_skills_data', 'max', $max_value);
		axiom_welldone_storage_set_array('sc_skills_data', 'dir', $dir);
		axiom_welldone_storage_set_array('sc_skills_data', 'height', axiom_welldone_prepare_css_value($height));
		$class .= ($class ? ' ' : ''); $css .= axiom_welldone_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= axiom_welldone_get_css_dimensions_from_values($width);
		if (!axiom_welldone_storage_empty('sc_skills_data', 'height') && (axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'arc' || (axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'pie' && axiom_welldone_param_is_on(axiom_welldone_storage_get_array('sc_skills_data', 'pie_compact')))))
			$css .= 'height: '.axiom_welldone_storage_get_array('sc_skills_data', 'height');
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'pie_compact')) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!axiom_welldone_param_is_off($animation) ? ' data-animation="'.esc_attr(axiom_welldone_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . trim($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.(axiom_welldone_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . (axiom_welldone_storage_get_array('sc_skills_data', 'data')) . '</div>'
						  )
						: '')
					. ($type=='pie' && axiom_welldone_param_is_on(axiom_welldone_storage_get_array('sc_skills_data', 'pie_compact'))
						? ('<div class="sc_skills_legend">'.(axiom_welldone_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . (axiom_welldone_storage_get_array('sc_skills_data', 'data')) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_skills', 'axiom_welldone_sc_skills');
}


if (!function_exists('axiom_welldone_sc_skills_item')) {	
	function axiom_welldone_sc_skills_item($atts, $content=null) {
		if (axiom_welldone_in_shortcode_blogger()) return '';
		extract(axiom_welldone_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"text_color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		axiom_welldone_storage_inc_array('sc_skills_data', 'counter');
		$ed = axiom_welldone_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if (axiom_welldone_storage_get_array('sc_skills_data', 'max') < $value) axiom_welldone_storage_set_array('sc_skills_data', 'max', $value);
		$percent = round($value / axiom_welldone_storage_get_array('sc_skills_data', 'max') * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round(axiom_welldone_storage_get_array('sc_skills_data', 'max')/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label"'.(!empty($text_color) ? ' style="color: '.$text_color.';"' : '').'>' . ($title) . '</div></div>';
		$old_color = $color;
		if (empty($color)) $color = axiom_welldone_storage_get_array('sc_skills_data', 'color');
		if (empty($color)) $color = axiom_welldone_get_scheme_color('accent1', $color);
		if (empty($bg_color)) $bg_color = axiom_welldone_storage_get_array('sc_skills_data', 'bg_color');
		if (empty($bg_color)) $bg_color = axiom_welldone_get_scheme_color('bd_color', $bg_color);
		if (empty($border_color)) $border_color = axiom_welldone_storage_get_array('sc_skills_data', 'border_color');
		if (empty($border_color)) $border_color = axiom_welldone_get_scheme_color('bd_color', $border_color);
		if (empty($style)) $style = axiom_welldone_storage_get_array('sc_skills_data', 'style');
		$style = max(1, min(4, $style));
		$output = '';
		if (axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'arc' || (axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'pie' && axiom_welldone_param_is_on(axiom_welldone_storage_get_array('sc_skills_data', 'pie_compact')))) {
			if (axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'arc' && empty($old_color)) {
				$rgb = axiom_welldone_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*(axiom_welldone_storage_get_array('sc_skills_data', 'counter')-1)).')';
			}
			axiom_welldone_storage_concat_array('sc_skills_data', 'legend', 
				'<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>'
			);
			axiom_welldone_storage_concat_array('sc_skills_data', 'data', 
				'<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'type')).'"'
					. (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='pie'
						? ( ' data-start="'.esc_attr($start).'"'
							. ' data-stop="'.esc_attr($stop).'"'
							. ' data-step="'.esc_attr($step).'"'
							. ' data-steps="'.esc_attr($steps).'"'
							. ' data-max="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'max')).'"'
							. ' data-speed="'.esc_attr($speed).'"'
							. ' data-duration="'.esc_attr($animation).'"'
							. ' data-color="'.esc_attr($color).'"'
							. ' data-bg_color="'.esc_attr($bg_color).'"'
							. ' data-border_color="'.esc_attr($border_color).'"'
							. ' data-cutout="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
							. ' data-easing="easeOutCirc"'
							. ' data-ed="'.esc_attr($ed).'"'
							)
						: '')
					. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>'
			);
		} else {
			$output .= (axiom_welldone_storage_get_array('sc_skills_data', 'columns') > 0 
							? '<div class="sc_skills_column column-1_'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'columns')).'">' 
							: '')
					. (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='bar' && axiom_welldone_storage_get_array('sc_skills_data', 'dir')=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. (axiom_welldone_storage_get_array('sc_skills_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
							. (axiom_welldone_storage_get_array('sc_skills_data', 'counter') == 1 ? ' first' : '') 
							. '"'
						. (axiom_welldone_storage_get_array('sc_skills_data', 'height') !='' || $css 
							? ' style="' 
								. (axiom_welldone_storage_get_array('sc_skills_data', 'height') !='' 
										? 'height: '.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'height')).';' 
										: '') 
								. ($css) 
								. '"' 
							: '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
			if (in_array(axiom_welldone_storage_get_array('sc_skills_data', 'type'), array('bar', 'counter'))) {
				$output .= '<div class="sc_skills_count"' . (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
							. '<div class="sc_skills_total" '.(!empty($text_color) ? ' style="color: '.$text_color.';"' : '').''
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'max')).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'">'
								. ($start) . ($ed)
							.'</div>'
						. '</div>';
			} else if (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total" '.(!empty($text_color) ? ' style="color: '.$text_color.';"' : '').''
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'max')).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			} else if (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='circle') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total" '.(!empty($text_color) ? ' style="color: '.$text_color.';"' : '').''
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'max')).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr(axiom_welldone_storage_get_array('sc_skills_data', 'circle_cutout')).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .= 
					  (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='counter' ? $title_block : '')
					. '</div>'
					. (axiom_welldone_storage_get_array('sc_skills_data', 'type')=='bar' && axiom_welldone_storage_get_array('sc_skills_data', 'dir')=='vertical' || axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'pie' || axiom_welldone_storage_get_array('sc_skills_data', 'type') == 'circle'  ? $title_block : '')
					. (axiom_welldone_storage_get_array('sc_skills_data', 'columns') > 0 ? '</div>' : '');
		}
		return apply_filters('axiom_welldone_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	axiom_welldone_require_shortcode('trx_skills_item', 'axiom_welldone_sc_skills_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_skills_reg_shortcodes' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list', 'axiom_welldone_sc_skills_reg_shortcodes');
	function axiom_welldone_sc_skills_reg_shortcodes() {
	
		axiom_welldone_sc_map("trx_skills", array(
			"title" => esc_html__("Skills", 'axiom-welldone'),
			"desc" => wp_kses_data( __("Insert skills diagramm in your page (post)", 'axiom-welldone') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Max value for skills items", 'axiom-welldone') ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"type" => array(
					"title" => esc_html__("Skills type", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select type of skills block", 'axiom-welldone') ),
					"value" => "bar",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'bar' => esc_html__('Bar', 'axiom-welldone'),
						'pie' => esc_html__('Pie chart', 'axiom-welldone'),
						'circle' => esc_html__('Circle chart', 'axiom-welldone'),
						'counter' => esc_html__('Counter', 'axiom-welldone'),
						'arc' => esc_html__('Arc', 'axiom-welldone')
					)
				), 
				"layout" => array(
					"title" => esc_html__("Skills layout", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select layout of skills block", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('counter','pie','circle','bar')
					),
					"value" => "rows",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'rows' => esc_html__('Rows', 'axiom-welldone'),
						'columns' => esc_html__('Columns', 'axiom-welldone')
					)
				),
				"dir" => array(
					"title" => esc_html__("Direction", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select direction of skills block", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('counter','pie','circle','bar')
					),
					"value" => "horizontal",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('dir')
				), 
				"style" => array(
					"title" => esc_html__("Counters style", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Select style of skills items (only for type=counter)", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('counter')
					),
					"value" => 1,
					"options" => axiom_welldone_get_list_styles(1, 4),
					"type" => "checklist"
				), 
				// "columns" - autodetect, not set manual
				"color" => array(
					"title" => esc_html__("Skills items color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Color for all skills items", 'axiom-welldone') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Background color for all skills items (only for type=pie & type=circle)", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('pie','circle')
					),
					"value" => "",
					"type" => "color"
				),
				"border_color" => array(
					"title" => esc_html__("Border color", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Border color for all skills items (only for type=pie & type=circle)", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('pie','circle')
					),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Align skills block", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Align skills block to left or right side", 'axiom-welldone') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => axiom_welldone_get_sc_param('float')
				), 
				"arc_caption" => array(
					"title" => esc_html__("Arc Caption", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Arc caption - text in the center of the diagram", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('arc')
					),
					"value" => "",
					"type" => "text"
				),
				"pie_compact" => array(
					"title" => esc_html__("Pie compact", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => axiom_welldone_get_sc_param('yes_no')
				),
				"pie_cutout" => array(
					"title" => esc_html__("Pie cutout", 'axiom-welldone'),
					"desc" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'axiom-welldone') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => 0,
					"min" => 0,
					"max" => 99,
					"type" => "spinner"
				),
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
				"name" => "trx_skills_item",
				"title" => esc_html__("Skill", 'axiom-welldone'),
				"desc" => wp_kses_data( __("Skills item", 'axiom-welldone') ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current skills item title", 'axiom-welldone') ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current skills level", 'axiom-welldone') ),
						"value" => 50,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current skills item color", 'axiom-welldone') ),
						"value" => "",
						"type" => "color"
					),
					"text_color" => array(
						"title" => esc_html__("Text color", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current skills text color", 'axiom-welldone') ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current skills item background color (only for type=pie & type=circle)", 'axiom-welldone') ),
						"value" => "",
						"type" => "color"
					),
					"border_color" => array(
						"title" => esc_html__("Border color", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Current skills item border color (only for type=pie & type=circle)", 'axiom-welldone') ),
						"value" => "",
						"type" => "color"
					),
					"style" => array(
						"title" => esc_html__("Counter style", 'axiom-welldone'),
						"desc" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", 'axiom-welldone') ),
						"value" => 1,
						"options" => axiom_welldone_get_list_styles(1, 4),
						"type" => "checklist"
					), 
					"icon" => array(
						"title" => esc_html__("Counter icon",  'axiom-welldone'),
						"desc" => wp_kses_data( __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'axiom-welldone') ),
						"value" => "",
						"type" => "icons",
						"options" => axiom_welldone_get_sc_param('icons')
					),
					"id" => axiom_welldone_get_sc_param('id'),
					"class" => axiom_welldone_get_sc_param('class'),
					"css" => axiom_welldone_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_welldone_sc_skills_reg_shortcodes_vc' ) ) {
	//add_action('axiom_welldone_action_shortcodes_list_vc', 'axiom_welldone_sc_skills_reg_shortcodes_vc');
	function axiom_welldone_sc_skills_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_skills",
			"name" => esc_html__("Skills", 'axiom-welldone'),
			"description" => wp_kses_data( __("Insert skills diagramm", 'axiom-welldone') ),
			"category" => esc_html__('Content', 'axiom-welldone'),
			'icon' => 'icon_trx_skills',
			"class" => "trx_sc_collection trx_sc_skills",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_skills_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", 'axiom-welldone'),
					"description" => wp_kses_data( __("Max value for skills items", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Skills type", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select type of skills block", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Bar', 'axiom-welldone') => 'bar',
						esc_html__('Pie chart', 'axiom-welldone') => 'pie',
						esc_html__('Circle chart', 'axiom-welldone') => 'circle',
						esc_html__('Counter', 'axiom-welldone') => 'counter',
						esc_html__('Arc', 'axiom-welldone') => 'arc'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "layout",
					"heading" => esc_html__("Skills layout", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select layout of skills block", 'axiom-welldone') ),
					"admin_label" => true,
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter','bar','pie','circle')
					),
					"class" => "",
					"value" => array(
						esc_html__('Rows', 'axiom-welldone') => 'rows',
						esc_html__('Columns', 'axiom-welldone') => 'columns'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select direction of skills block", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('dir')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counters style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select style of skills items (only for type=counter)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_list_styles(1, 4)),
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", 'axiom-welldone'),
					"description" => wp_kses_data( __("Skills columns count (required)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Color for all skills items", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Background color for all skills items (only for type=pie & type=circle)", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie','circle')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Border color for all skills items (only for type=pie & type=circle)", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie','circle')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'axiom-welldone'),
					"description" => wp_kses_data( __("Align skills block to left or right side", 'axiom-welldone') ),
					"class" => "",
					"value" => array_flip(axiom_welldone_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "arc_caption",
					"heading" => esc_html__("Arc caption", 'axiom-welldone'),
					"description" => wp_kses_data( __("Arc caption - text in the center of the diagram", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('arc')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pie_compact",
					"heading" => esc_html__("Pie compact", 'axiom-welldone'),
					"description" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => array(esc_html__('Show separate skills', 'axiom-welldone') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pie_cutout",
					"heading" => esc_html__("Pie cutout", 'axiom-welldone'),
					"description" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'axiom-welldone') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('animation'),
				axiom_welldone_get_vc_param('css'),
				axiom_welldone_vc_width(),
				axiom_welldone_vc_height(),
				axiom_welldone_get_vc_param('margin_top'),
				axiom_welldone_get_vc_param('margin_bottom'),
				axiom_welldone_get_vc_param('margin_left'),
				axiom_welldone_get_vc_param('margin_right')
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_skills_item",
			"name" => esc_html__("Skill", 'axiom-welldone'),
			"description" => wp_kses_data( __("Skills item", 'axiom-welldone') ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_skills_item',
			"class" => "trx_sc_single trx_sc_skills_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_skills'),
			"as_parent" => array('except' => 'trx_skills'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'axiom-welldone'),
					"description" => wp_kses_data( __("Title for the current skills item", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'axiom-welldone'),
					"description" => wp_kses_data( __("Value for the current skills item", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Color for current skills item", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "text_color",
					"heading" => esc_html__("Text color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Color for current text item", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Background color for current skills item (only for type=pie & type=circle)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", 'axiom-welldone'),
					"description" => wp_kses_data( __("Border color for current skills item (only for type=pie & type=circle)", 'axiom-welldone') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counter style", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", 'axiom-welldone') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(axiom_welldone_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Counter icon", 'axiom-welldone'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set, placed before counter (only for type=counter)", 'axiom-welldone') ),
					"class" => "",
					"value" => axiom_welldone_get_sc_param('icons'),
					"type" => "dropdown"
				),
				axiom_welldone_get_vc_param('id'),
				axiom_welldone_get_vc_param('class'),
				axiom_welldone_get_vc_param('css'),
			)
		) );
		
		class WPBakeryShortCode_Trx_Skills extends AXIOM_WELLDONE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Skills_Item extends AXIOM_WELLDONE_VC_ShortCodeSingle {}
	}
}
?>
