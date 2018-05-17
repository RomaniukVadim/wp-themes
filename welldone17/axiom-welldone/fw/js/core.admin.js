/**
 * Axiom Welldone Framework: Admin scripts
 *
 * @package	axiom_welldone
 * @since	axiom_welldone 1.0
 */

jQuery(document).ready(function() {
	"use strict";
	// Refresh categories when post type is changed
	jQuery('.widgets_param_post_type_selector').on('change', function() {
		"use strict";
		var cat_fld = jQuery(this).parent().next().find('select');
		var cat_lbl = jQuery(this).parent().next().find('label');
		axiom_welldone_admin_fill_categories(this, cat_fld, cat_lbl);
		return false;
	});
	// Set parent course
	jQuery('.axiom_welldone_course_selector').on('click', function(e) {
		"use strict";
		var id = jQuery(this).data('parent_id');
		if (id > 0) {
			jQuery('select#parent_course').val(id).siblings('input[type="submit"]').trigger('click');
			e.preventDefault();
			return false;
		}
	});
});


// Fill categories in specified field
function axiom_welldone_admin_fill_categories(fld, cat_fld, cat_lbl) {
	"use strict";
	var cat_value = axiom_welldone_get_listbox_selected_value(cat_fld.get(0));
	cat_lbl.append('<span class="sc_refresh iconadmin-spin3 animate-spin"></span>');
	var pt = jQuery(fld).val();
	// Prepare data
	var data = {
		action: 'axiom_welldone_admin_change_post_type',
		nonce: AXIOM_WELLDONE_STORAGE['ajax_nonce'],
		post_type: pt
	};
	jQuery.post(AXIOM_WELLDONE_STORAGE['ajax_url'], data, function(response) {
		"use strict";
		var rez = {};
		try {
			rez = JSON.parse(response);
		} catch (e) {
			rez = { error: AXIOM_WELLDONE_STORAGE['ajax_error'] };
			console.log(response);
		}
		if (rez.error === '') {
			var opt_list = '';
			for (var i in rez.data.ids) {
				opt_list += '<option class="'+rez.data.ids[i]+'" value="'+rez.data.ids[i]+'"'+(rez.data.ids[i]==cat_value ? ' selected="selected"' : '')+'>'+rez.data.titles[i]+'</option>';
			}
			cat_fld.html(opt_list);
			cat_lbl.find('span').remove();
		}
	});
	return false;
}
