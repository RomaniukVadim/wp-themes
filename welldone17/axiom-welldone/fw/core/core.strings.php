<?php
/**
 * Axiom Welldone Framework: strings manipulations
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'AXIOM_WELLDONE_MULTIBYTE' ) ) define( 'AXIOM_WELLDONE_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('axiom_welldone_strlen')) {
	function axiom_welldone_strlen($text) {
		return AXIOM_WELLDONE_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('axiom_welldone_strpos')) {
	function axiom_welldone_strpos($text, $char, $from=0) {
		return AXIOM_WELLDONE_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('axiom_welldone_strrpos')) {
	function axiom_welldone_strrpos($text, $char, $from=0) {
		return AXIOM_WELLDONE_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('axiom_welldone_substr')) {
	function axiom_welldone_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = axiom_welldone_strlen($text)-$from;
		}
		return AXIOM_WELLDONE_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('axiom_welldone_strtolower')) {
	function axiom_welldone_strtolower($text) {
		return AXIOM_WELLDONE_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('axiom_welldone_strtoupper')) {
	function axiom_welldone_strtoupper($text) {
		return AXIOM_WELLDONE_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('axiom_welldone_strtoproper')) {
	function axiom_welldone_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<axiom_welldone_strlen($text); $i++) {
			$ch = axiom_welldone_substr($text, $i, 1);
			$rez .= axiom_welldone_strpos(' .,:;?!()[]{}+=', $last)!==false ? axiom_welldone_strtoupper($ch) : axiom_welldone_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('axiom_welldone_strrepeat')) {
	function axiom_welldone_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('axiom_welldone_strshort')) {
	function axiom_welldone_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= axiom_welldone_strlen($str)) 
			return strip_tags($str);
		$str = axiom_welldone_substr(strip_tags($str), 0, $maxlength - axiom_welldone_strlen($add));
		$ch = axiom_welldone_substr($str, $maxlength - axiom_welldone_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = axiom_welldone_strlen($str) - 1; $i > 0; $i--)
				if (axiom_welldone_substr($str, $i, 1) == ' ') break;
			$str = trim(axiom_welldone_substr($str, 0, $i));
		}
		if (!empty($str) && axiom_welldone_strpos(',.:;-', axiom_welldone_substr($str, -1))!==false) $str = axiom_welldone_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('axiom_welldone_strclear')) {
	function axiom_welldone_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (axiom_welldone_substr($text, 0, axiom_welldone_strlen($open))==$open) {
					$pos = axiom_welldone_strpos($text, '>');
					if ($pos!==false) $text = axiom_welldone_substr($text, $pos+1);
				}
				if (axiom_welldone_substr($text, -axiom_welldone_strlen($close))==$close) $text = axiom_welldone_substr($text, 0, axiom_welldone_strlen($text) - axiom_welldone_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('axiom_welldone_get_slug')) {
	function axiom_welldone_get_slug($title) {
		return axiom_welldone_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('axiom_welldone_strmacros')) {
	function axiom_welldone_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('axiom_welldone_unserialize')) {
	function axiom_welldone_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>