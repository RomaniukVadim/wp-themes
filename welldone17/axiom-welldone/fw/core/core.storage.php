<?php
/**
 * Axiom Welldone Framework: theme variables storage
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('axiom_welldone_storage_get')) {
	function axiom_welldone_storage_get($var_name, $default='') {
		global $AXIOM_WELLDONE_STORAGE;
		return isset($AXIOM_WELLDONE_STORAGE[$var_name]) ? $AXIOM_WELLDONE_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('axiom_welldone_storage_set')) {
	function axiom_welldone_storage_set($var_name, $value) {
		global $AXIOM_WELLDONE_STORAGE;
		$AXIOM_WELLDONE_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('axiom_welldone_storage_empty')) {
	function axiom_welldone_storage_empty($var_name, $key='', $key2='') {
		global $AXIOM_WELLDONE_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($AXIOM_WELLDONE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($AXIOM_WELLDONE_STORAGE[$var_name][$key]);
		else
			return empty($AXIOM_WELLDONE_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('axiom_welldone_storage_isset')) {
	function axiom_welldone_storage_isset($var_name, $key='', $key2='') {
		global $AXIOM_WELLDONE_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($AXIOM_WELLDONE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($AXIOM_WELLDONE_STORAGE[$var_name][$key]);
		else
			return isset($AXIOM_WELLDONE_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('axiom_welldone_storage_inc')) {
	function axiom_welldone_storage_inc($var_name, $value=1) {
		global $AXIOM_WELLDONE_STORAGE;
		if (empty($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = 0;
		$AXIOM_WELLDONE_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('axiom_welldone_storage_concat')) {
	function axiom_welldone_storage_concat($var_name, $value) {
		global $AXIOM_WELLDONE_STORAGE;
		if (empty($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = '';
		$AXIOM_WELLDONE_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('axiom_welldone_storage_get_array')) {
	function axiom_welldone_storage_get_array($var_name, $key, $key2='', $default='') {
		global $AXIOM_WELLDONE_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($AXIOM_WELLDONE_STORAGE[$var_name][$key]) ? $AXIOM_WELLDONE_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($AXIOM_WELLDONE_STORAGE[$var_name][$key][$key2]) ? $AXIOM_WELLDONE_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('axiom_welldone_storage_set_array')) {
	function axiom_welldone_storage_set_array($var_name, $key, $value) {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if ($key==='')
			$AXIOM_WELLDONE_STORAGE[$var_name][] = $value;
		else
			$AXIOM_WELLDONE_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('axiom_welldone_storage_set_array2')) {
	function axiom_welldone_storage_set_array2($var_name, $key, $key2, $value) {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name][$key])) $AXIOM_WELLDONE_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$AXIOM_WELLDONE_STORAGE[$var_name][$key][] = $value;
		else
			$AXIOM_WELLDONE_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('axiom_welldone_storage_set_array_after')) {
	function axiom_welldone_storage_set_array_after($var_name, $after, $key, $value='') {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if (is_array($key))
			axiom_welldone_array_insert_after($AXIOM_WELLDONE_STORAGE[$var_name], $after, $key);
		else
			axiom_welldone_array_insert_after($AXIOM_WELLDONE_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('axiom_welldone_storage_set_array_before')) {
	function axiom_welldone_storage_set_array_before($var_name, $before, $key, $value='') {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if (is_array($key))
			axiom_welldone_array_insert_before($AXIOM_WELLDONE_STORAGE[$var_name], $before, $key);
		else
			axiom_welldone_array_insert_before($AXIOM_WELLDONE_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('axiom_welldone_storage_push_array')) {
	function axiom_welldone_storage_push_array($var_name, $key, $value) {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($AXIOM_WELLDONE_STORAGE[$var_name], $value);
		else {
			if (!isset($AXIOM_WELLDONE_STORAGE[$var_name][$key])) $AXIOM_WELLDONE_STORAGE[$var_name][$key] = array();
			array_push($AXIOM_WELLDONE_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('axiom_welldone_storage_pop_array')) {
	function axiom_welldone_storage_pop_array($var_name, $key='', $defa='') {
		global $AXIOM_WELLDONE_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($AXIOM_WELLDONE_STORAGE[$var_name]) && is_array($AXIOM_WELLDONE_STORAGE[$var_name]) && count($AXIOM_WELLDONE_STORAGE[$var_name]) > 0) 
				$rez = array_pop($AXIOM_WELLDONE_STORAGE[$var_name]);
		} else {
			if (isset($AXIOM_WELLDONE_STORAGE[$var_name][$key]) && is_array($AXIOM_WELLDONE_STORAGE[$var_name][$key]) && count($AXIOM_WELLDONE_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($AXIOM_WELLDONE_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('axiom_welldone_storage_inc_array')) {
	function axiom_welldone_storage_inc_array($var_name, $key, $value=1) {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if (empty($AXIOM_WELLDONE_STORAGE[$var_name][$key])) $AXIOM_WELLDONE_STORAGE[$var_name][$key] = 0;
		$AXIOM_WELLDONE_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('axiom_welldone_storage_concat_array')) {
	function axiom_welldone_storage_concat_array($var_name, $key, $value) {
		global $AXIOM_WELLDONE_STORAGE;
		if (!isset($AXIOM_WELLDONE_STORAGE[$var_name])) $AXIOM_WELLDONE_STORAGE[$var_name] = array();
		if (empty($AXIOM_WELLDONE_STORAGE[$var_name][$key])) $AXIOM_WELLDONE_STORAGE[$var_name][$key] = '';
		$AXIOM_WELLDONE_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('axiom_welldone_storage_call_obj_method')) {
	function axiom_welldone_storage_call_obj_method($var_name, $method, $param=null) {
		global $AXIOM_WELLDONE_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($AXIOM_WELLDONE_STORAGE[$var_name]) ? $AXIOM_WELLDONE_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($AXIOM_WELLDONE_STORAGE[$var_name]) ? $AXIOM_WELLDONE_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('axiom_welldone_storage_get_obj_property')) {
	function axiom_welldone_storage_get_obj_property($var_name, $prop, $default='') {
		global $AXIOM_WELLDONE_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($AXIOM_WELLDONE_STORAGE[$var_name]->$prop) ? $AXIOM_WELLDONE_STORAGE[$var_name]->$prop : $default;
	}
}
?>