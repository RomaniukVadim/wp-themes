<?php
/**
 * Axiom Welldone Framework: messages subsystem
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiom_welldone_messages_theme_setup')) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_messages_theme_setup' );
	function axiom_welldone_messages_theme_setup() {
		// Core messages strings
		add_action('axiom_welldone_action_add_scripts_inline', 'axiom_welldone_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('axiom_welldone_get_error_msg')) {
	function axiom_welldone_get_error_msg() {
		return axiom_welldone_storage_get('error_msg');
	}
}

if (!function_exists('axiom_welldone_set_error_msg')) {
	function axiom_welldone_set_error_msg($msg) {
		$msg2 = axiom_welldone_get_error_msg();
		axiom_welldone_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('axiom_welldone_get_success_msg')) {
	function axiom_welldone_get_success_msg() {
		return axiom_welldone_storage_get('success_msg');
	}
}

if (!function_exists('axiom_welldone_set_success_msg')) {
	function axiom_welldone_set_success_msg($msg) {
		$msg2 = axiom_welldone_get_success_msg();
		axiom_welldone_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('axiom_welldone_get_notice_msg')) {
	function axiom_welldone_get_notice_msg() {
		return axiom_welldone_storage_get('notice_msg');
	}
}

if (!function_exists('axiom_welldone_set_notice_msg')) {
	function axiom_welldone_set_notice_msg($msg) {
		$msg2 = axiom_welldone_get_notice_msg();
		axiom_welldone_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('axiom_welldone_set_system_message')) {
	function axiom_welldone_set_system_message($msg, $status='info', $hdr='') {
		update_option(axiom_welldone_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('axiom_welldone_get_system_message')) {
	function axiom_welldone_get_system_message($del=false) {
		$msg = get_option(axiom_welldone_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			axiom_welldone_del_system_message();
		return $msg;
	}
}

if (!function_exists('axiom_welldone_del_system_message')) {
	function axiom_welldone_del_system_message() {
		delete_option(axiom_welldone_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('axiom_welldone_messages_add_scripts_inline')) {
	function axiom_welldone_messages_add_scripts_inline() {
		echo '<script type="text/javascript">'
			
			. "if (typeof AXIOM_WELLDONE_STORAGE == 'undefined') var AXIOM_WELLDONE_STORAGE = {};"
			
			// Strings for translation
			. 'AXIOM_WELLDONE_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'axiom-welldone')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'axiom-welldone')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'axiom-welldone')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'axiom-welldone')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'axiom-welldone')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'axiom-welldone')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'axiom-welldone')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'axiom-welldone')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'axiom-welldone')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'axiom-welldone')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'axiom-welldone')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'axiom-welldone')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'axiom-welldone')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'axiom-welldone')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'axiom-welldone')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'axiom-welldone')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'axiom-welldone')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'axiom-welldone')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'axiom-welldone')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'axiom-welldone')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'axiom-welldone')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'axiom-welldone')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'axiom-welldone')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'axiom-welldone')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'axiom-welldone')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'axiom-welldone')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'axiom-welldone')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'axiom-welldone')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'axiom-welldone')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'axiom-welldone')) . '",'
				. 'terms_not_agree:	"' . addslashes(esc_html__('Please check terms', 'axiom-welldone')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'axiom-welldone')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'axiom-welldone')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'axiom-welldone')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'axiom-welldone')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'axiom-welldone')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'axiom-welldone')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'axiom-welldone')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'axiom-welldone')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'axiom-welldone')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'axiom-welldone')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'axiom-welldone')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'axiom-welldone')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>