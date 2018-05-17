jQuery(document).ready( function($) {
	jQuery('.advertisement_count').live('change',function(){
		
		var wrap = jQuery(this).closest('p').siblings('.advertisement_input');
		wrap.children('div').hide();
		var count = jQuery(this).val();
		for(var i = 1; i <= count; i++){
			wrap.find('.advertisement_'+i).show();
		}
	});
});