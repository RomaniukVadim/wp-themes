<?php 
if (is_singular()) {
	if (axiom_welldone_get_theme_option('use_ajax_views_counter')=='yes') {
		?>
		<!-- Post/page views count increment -->
		<script type="text/javascript">
			jQuery(document).ready(function() {
				setTimeout(function(){
					jQuery.post(AXIOM_WELLDONE_STORAGE['ajax_url'], {
						action: 'post_counter',
						nonce: AXIOM_WELLDONE_STORAGE['ajax_nonce'],
						post_id: <?php echo (int) get_the_ID(); ?>,
						views: <?php echo (int) axiom_welldone_get_post_views(get_the_ID()); ?>
					});
					}, 10);
			});
		</script>
		<?php
	} else
		axiom_welldone_set_post_views(get_the_ID());
}
?>