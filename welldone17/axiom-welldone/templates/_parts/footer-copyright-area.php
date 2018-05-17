<?php

	$copyright_style = axiom_welldone_get_custom_option('show_copyright_in_footer');
	if (!axiom_welldone_param_is_off($copyright_style)) {
		?> 
		<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(axiom_welldone_get_custom_option('copyright_scheme')); ?>">
			<div class="copyright_wrap_inner">
				<div class="content_wrap">
					<?php
					if ($copyright_style == 'menu') {
						if (($menu = axiom_welldone_get_nav_menu('menu_footer'))!='') {
							axiom_welldone_show_layout($menu);
						}
					} else if ($copyright_style == 'socials') {
						axiom_welldone_show_layout(axiom_welldone_sc_socials(array('size'=>"tiny")));
					}
					?>
					<div class="copyright_text"><?php echo force_balance_tags(axiom_welldone_get_custom_option('footer_copyright')); ?></div>
				</div>
			</div>
		</div>
		<?php
	}
			
?>