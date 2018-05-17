<?php
/**
 * Axiom Welldone Framework: debug utilities (for internal use only!)
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_welldone_debug_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_debug_theme_setup', 11 );
	function axiom_welldone_debug_theme_setup() {
		if (axiom_welldone_get_theme_option('debug_mode')=='yes' && axiom_welldone_get_theme_setting('allow_profiler')) {
			if (is_admin())
				add_action('admin_print_footer_scripts',	'axiom_welldone_profiler_show', 100);
			else
				add_action('wp_footer',		'axiom_welldone_profiler_show', 100);
		}
	}
}

// Short analogs for debug functions
if (!function_exists('dcl')) {	function dcl($msg) {				if (is_user_logged_in()) echo "<pre class=\"debug_log\">\n" . esc_html($msg) . "\n</pre>"; } }	// Console log - output any message on the screen
if (!function_exists('dco')) {	function dco(&$var, $lvl=-1)	{ 	if (is_user_logged_in()) axiom_welldone_debug_dump_screen($var, $lvl); } }	// Console obj - output object structure on the screen
if (!function_exists('dcs')) {	function dcs($lvl=-1) { 	if (is_user_logged_in()) axiom_welldone_debug_calls_stack_screen($lvl); } }			// Console stack - output calls stack on the screen
if (!function_exists('dcw')) {	function dcw($q=null) {	if (is_user_logged_in()) axiom_welldone_debug_dump_wp($q); } }						// Console WP - output WP is_... states on the screen
if (!function_exists('ddo')) {	function ddo(&$var, $lvl=-1)	{ 	if (is_user_logged_in()) axiom_welldone_debug_dump_var($var, $lvl); } }	// Return obj - return object structure
if (!function_exists('dfl')) {	function dfl($var)	{	if (is_user_logged_in()) axiom_welldone_debug_trace_message($var); } }				// File log - output any message into file debug.log
if (!function_exists('dfo')) {	function dfo(&$var, $lvl=-1)	{ 	if (is_user_logged_in()) axiom_welldone_debug_dump_file($var, $lvl); } }	// File obj - output object structure into file debug.log
if (!function_exists('dfs')) {	function dfs($lvl=-1) { 	if (is_user_logged_in()) axiom_welldone_debug_calls_stack_file($lvl); } }				// File stack - output calls stack into file debug.log


if (!function_exists('axiom_welldone_debug_die_message')) {
	function axiom_welldone_debug_die_message($msg) {
		axiom_welldone_debug_trace_message($msg);
		die($msg);
	}
}

if (!function_exists('axiom_welldone_debug_trace_message')) {
	function axiom_welldone_debug_trace_message($msg) {
		axiom_welldone_fpc(get_stylesheet_directory().'/debug.log', date('d.m.Y H:i:s')." $msg\n", FILE_APPEND);
	}
}

if (!function_exists('axiom_welldone_debug_calls_stack_screen')) {
	function axiom_welldone_debug_calls_stack_screen($level=-1) {
		$s = debug_backtrace();
		$s1 = array_splice($s, 1, $level);
		axiom_welldone_debug_dump_screen($s1, -1);
	}
}

if (!function_exists('axiom_welldone_debug_calls_stack_file')) {
	function axiom_welldone_debug_calls_stack_file($level=-1) {
		$s = debug_backtrace();
		$s1 = array_splice($s, 1, $level);
		axiom_welldone_debug_dump_file($s1, -1);
	}
}

if (!function_exists('axiom_welldone_debug_dump_screen')) {
	function axiom_welldone_debug_dump_screen(&$var, $level=-1) {
		if ((is_array($var) || is_object($var)) && count($var))
			echo "<pre class=\"debug_log\">\n".esc_html(axiom_welldone_debug_dump_var($var, 0, $level))."\n</pre>";
		else
			echo "<tt>".esc_html(axiom_welldone_debug_dump_var($var, 0, $level))."</tt>\n";
	}
}

if (!function_exists('axiom_welldone_debug_dump_file')) {
	function axiom_welldone_debug_dump_file(&$var, $level=-1) {
		axiom_welldone_debug_trace_message("\n\n".axiom_welldone_debug_dump_var($var, 0, $level));
	}
}

if (!function_exists('axiom_welldone_debug_dump_var')) {
	function axiom_welldone_debug_dump_var(&$var, $level=0, $max_level=-1)  {
		if (is_array($var)) $type="Array[".count($var)."]";
		else if (is_object($var)) $type="Object";
		else $type="";
		if ($type) {
			$rez = "$type\n";
			if ($max_level<0 || $level < $max_level) {
				for (Reset($var), $level++; list($k, $v)=each($var); ) {
					if (is_array($v) && $k==="GLOBALS") continue;
					for ($i=0; $i<$level*3; $i++) $rez .= " ";
					$rez .= $k.' => '. axiom_welldone_debug_dump_var($v, $level, $max_level);
				}
			}
		} else if (is_bool($var))
			$rez = ($var ? 'true' : 'false')."\n";
		else if (is_long($var) || is_float($var) || intval($var) != 0)
			$rez = $var."\n";
		else
			$rez = '"'.($var).'"'."\n";
		return $rez;
	}
}

if (!function_exists('axiom_welldone_debug_dump_wp')) {
	function axiom_welldone_debug_dump_wp($query=null) {
		global $wp_query;
		if (!$query) $query = $wp_query;
		echo "<pre class=\"debug_log\">"
			."\nadmin=".is_admin()
			."\nmobile=".wp_is_mobile()
			."\nmain_query=".is_main_query()."  query=".esc_html($query->is_main_query())
			."\nquery->is_posts_page=".esc_html($query->is_posts_page)
			."\nhome=".is_home()."  query=".esc_html($query->is_home())
			."\nfp=".is_front_page()."  query=".esc_html($query->is_front_page())
			."\nsearch=".is_search()."  query=".esc_html($query->is_search())
			."\ncategory=".is_category()."  query=".esc_html($query->is_category())
			."\ntag=".is_tag()."  query=".esc_html($query->is_tag())
			."\narchive=".is_archive()."  query=".esc_html($query->is_archive())
			."\nday=".is_day()."  query=".esc_html($query->is_day())
			."\nmonth=".is_month()."  query=".esc_html($query->is_month())
			."\nyear=".is_year()."  query=".esc_html($query->is_year())
			."\nauthor=".is_author()."  query=".esc_html($query->is_author())
			."\npage=".is_page()."  query=".esc_html($query->is_page())
			."\nsingle=".is_single()."  query=".esc_html($query->is_single())
			."\nsingular=".is_singular()."  query=".esc_html($query->is_singular())
			."\nattachment=".is_attachment()."  query=".esc_html($query->is_attachment())
			."\nWooCommerce=".esc_html(function_exists('axiom_welldone_is_woocommerce_page') && axiom_welldone_is_woocommerce_page())
			."</pre>";
	}
}


/* Profiler functions
---------------------------------------------------------- */
// Add profiler point
if (!function_exists('axiom_welldone_profiler_add_point')) {
	function axiom_welldone_profiler_add_point($name, $theme_mode=true, $data=false) {
		global $timestart;
		if (axiom_welldone_get_theme_option('debug_mode')=='yes' && axiom_welldone_get_theme_setting('allow_profiler')) {
			if ($data===false) {
				$data = array(
					'mode' => $theme_mode,
					'time' => microtime(true)-max(0, $timestart),
					'memory' => memory_get_usage(),
					'queries' => get_num_queries()
					);
			}
			axiom_welldone_storage_set_array('profiler_points', microtime(true).'|'.$name, $data);
		}
	}
}

// Show time and memory statistic
if (!function_exists('axiom_welldone_profiler_show')) {
	function axiom_welldone_profiler_show() {
		global $timestart;
		axiom_welldone_profiler_add_point(esc_html__('WP PAGE OUTPUT END', 'axiom-welldone'), false);
		?>
		<div class="axiom_welldone_profiler" align="center">
			<h4 class="profiler_title"><?php esc_html_e('Execution time and Memory usage', 'axiom-welldone'); ?></h4>
			<table>
				<tr>
					<th rowspan="2"><?php esc_html_e('Point', 'axiom-welldone'); ?></th>
					<th colspan="2"><?php esc_html_e('Execution time (seconds)', 'axiom-welldone'); ?></th>
					<th colspan="2"><?php esc_html_e('Usage memory (bytes)', 'axiom-welldone'); ?></th>
					<th colspan="2"><?php esc_html_e('Database queries', 'axiom-welldone'); ?></th>
				</tr>
				<tr>
					<th><?php esc_html_e('By theme', 'axiom-welldone'); ?></th>
					<th><?php esc_html_e('Total (WP+Plugins+Theme)', 'axiom-welldone'); ?></th>
					<th><?php esc_html_e('By theme', 'axiom-welldone'); ?></th>
					<th><?php esc_html_e('Total (WP+Plugins+Theme)', 'axiom-welldone'); ?></th>
					<th><?php esc_html_e('By theme', 'axiom-welldone'); ?></th>
					<th><?php esc_html_e('Total (WP+Plugins+Theme)', 'axiom-welldone'); ?></th>
				</tr>
				<?php
				$points = array_merge( array('THEME START' => array(
						'mode'   => false,
						'time'   => AXIOM_WELLDONE_START_TIME-max(0, $timestart),
						'memory' => AXIOM_WELLDONE_START_MEMORY,
						'queries'=> AXIOM_WELLDONE_START_QUERIES
						)
					), axiom_welldone_storage_get('profiler_points') );
				$theme_usage = $last_usage = array(
					'time' => 0,
					'memory' => 0,
					'queries' => 0
				);
				foreach ($points as $key => $data) {
					$point = explode('|', $key);
					$point = !empty($point[1]) ? $point[1] : $key;
					if ($data['mode']) {
						$theme_usage['time']    += $data['time'] - $last_usage['time'];
						$theme_usage['memory']  += $data['memory'] - $last_usage['memory'];
						$theme_usage['queries'] += $data['queries'] - $last_usage['queries'];
					}
					?>
					<tr align="right">
						<td align="left"><?php echo esc_html($point); ?></td>
						<td><?php echo esc_html($data['mode'] ? round($theme_usage['time'], 3) : '-'); ?></td>
						<td><?php echo esc_html(round($data['time'], 3)); ?></td>
						<td><?php echo esc_html($data['mode'] ? number_format($theme_usage['memory'], 0, '.', ' ') : '-'); ?></td>
						<td><?php echo esc_html(number_format($data['memory'], 0, '.', ' ')); ?></td>
						<td><?php echo esc_html($data['mode'] ? $theme_usage['queries'] : '-'); ?></td>
						<td><?php echo esc_html($data['queries']); ?></td>
					</tr>
					<?php
					$last_usage = $data;
				}
				?>
			</table>
		</div>
		<?php
	}
}
?>