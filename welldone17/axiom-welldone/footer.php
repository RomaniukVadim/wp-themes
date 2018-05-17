<?php
/**
 * The template for displaying the footer.
 */

				axiom_welldone_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (axiom_welldone_get_custom_option('body_style')!='fullscreen') axiom_welldone_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer sidebar
			get_template_part(axiom_welldone_get_file_slug('templates/_parts/footer-sidebar.php'));
			
			// Footer contacts
			get_template_part(axiom_welldone_get_file_slug('templates/_parts/footer-contacts.php'));

			// Copyright area
			get_template_part(axiom_welldone_get_file_slug('templates/_parts/footer-copyright-area.php'));
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
<?php
// Post/Page views counter
get_template_part(axiom_welldone_get_file_slug('templates/_parts/views-counter.php'));

// Login/Register
if (axiom_welldone_get_theme_option('show_login')=='yes') {
	axiom_welldone_enqueue_popup();
	// Anyone can register ?
	if ( (int) get_option('users_can_register') > 0) {
		get_template_part(axiom_welldone_get_file_slug('templates/_parts/popup-register.php'));
	}
	get_template_part(axiom_welldone_get_file_slug('templates/_parts/popup-login.php'));
}

// Front customizer
if (axiom_welldone_get_custom_option('show_theme_customizer')=='yes') {
	require_once trailingslashit( get_template_directory() ) . 'core/core.customizer/front.customizer.php';
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'axiom-welldone'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(axiom_welldone_get_custom_option('custom_code')); ?>
</div>

<?php
echo force_balance_tags(axiom_welldone_get_custom_option('gtm_code2'));

wp_footer(); ?>

</body>
</html>