<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_welldone_template_form_2_theme_setup' ) ) {
	add_action( 'axiom_welldone_action_before_init_theme', 'axiom_welldone_template_form_2_theme_setup', 1 );
	function axiom_welldone_template_form_2_theme_setup() {
		axiom_welldone_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'axiom-welldone')
			));
	}
}

// Template output
if ( !function_exists( 'axiom_welldone_template_form_2_output' ) ) {
	function axiom_welldone_template_form_2_output($post_options, $post_data) {
		$address_1 = axiom_welldone_get_theme_option('contact_address_1');
		$address_2 = axiom_welldone_get_theme_option('contact_address_2');
		$phone = axiom_welldone_get_theme_option('contact_phone');
		$fax = axiom_welldone_get_theme_option('contact_fax');
		$email = axiom_welldone_get_theme_option('contact_email');
		$open_hours = axiom_welldone_get_theme_option('contact_open_hours');
		
		?><div class="sc_columns columns_wrap"><?php

			// Form info
			?><div class="sc_form_address column-1_3">
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'axiom-welldone'); ?></span>
					<span class="sc_form_address_data"><?php axiom_welldone_show_layout($address_1 . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2); ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('We are open', 'axiom-welldone'); ?></span>
					<span class="sc_form_address_data"><?php axiom_welldone_show_layout($open_hours); ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Phone', 'axiom-welldone'); ?></span>
					<span class="sc_form_address_data"><?php axiom_welldone_show_layout($phone . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax); ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('E-mail', 'axiom-welldone'); ?></span>
					<span class="sc_form_address_data"><?php axiom_welldone_show_layout($email); ?></span>
				</div>
				<?php echo do_shortcode('[trx_socials size="tiny"][/trx_socials]'); ?>
			</div><div class="sc_form_fields column-2_3">
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php axiom_welldone_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'axiom-welldone'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'axiom-welldone'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'axiom-welldone'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'axiom-welldone'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'axiom-welldone'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'axiom-welldone'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'axiom-welldone'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'axiom-welldone'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button class="sc_button sc_button_size_big"><span class="overfl"><span class="first"><?php esc_html_e('Send Message', 'axiom-welldone'); ?></span><span class="second"><?php esc_html_e('Send Message', 'axiom-welldone'); ?></span></span></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>