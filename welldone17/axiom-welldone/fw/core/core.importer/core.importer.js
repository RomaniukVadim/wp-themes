// AXIOM_WELLDONE Importer script

jQuery(document).ready(function(){
	"use strict";

	// Hide/Show pages list on change import_posts
	jQuery('#trx_importer_form .trx_importer_item_posts').on('change', function() {
		"use strict";
		var demo_set = jQuery('#trx_importer_form [name="demo_set"]:checked').val();
		if (jQuery(this).get(0).checked && demo_set=='part') 
			jQuery('.trx_importer_part_pages').show();
		else
			jQuery('.trx_importer_part_pages').hide();
	});

	// Change demo type
	jQuery('.trx_importer_demo_type input[type="radio"]').on('change', function() {
		"use strict";
		var type = jQuery(this).val();
		// Refresh list of the pages
		var data = {
			ajax_nonce: AXIOM_WELLDONE_STORAGE['ajax_nonce'],
			action: 'axiom_welldone_importer_get_list_pages',
			demo_type: type
		};
		jQuery.post(AXIOM_WELLDONE_STORAGE['ajax_url'], data, function(response) {
			"use strict";
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: AXIOM_WELLDONE_STORAGE['ajax_error']+':<br>'+response };
				console.log(response);
			}
			if (rez.error === '') {
				var html = '';
				for (var id in rez.data) {
					html += '<label>'
							+ '<input class="trx_importer_pages" type="checkbox" value="'+id+'" name="import_pages_'+id+'" id="import_pages_'+id+'" />'
							+ ' ' + rez.data[id]
							+ '</label>';
				}
				if (html != '') jQuery('.trx_importer_part_pages').html(html);
			}
		});
	});

	// Change demo set
	jQuery('.trx_importer_demo_set input[type="radio"]').on('change', function() {
		"use strict";
		var set = jQuery(this).val();
		// Show/hide description of the set
		jQuery(this).parents('.trx_importer_demo_set')
			.find('.trx_importer_description').hide()
			.end()
			.find('.trx_importer_description_'+set).show();
		// Show/hide set items
		jQuery(this).parents('form').find('[data-set-'+set+'="1"]').parent().show();
		jQuery(this).parents('form').find('[data-set-'+set+'="0"]').removeAttr('checked').parent().hide();
		jQuery(this).parents('form').find('.trx_importer_item_posts').trigger('change');
	});
	jQuery('.trx_importer_demo_set input[type="radio"]:checked').trigger('change');
	
	// Start import
	jQuery('.trx_importer_section').on('click', '.trx_buttons input[type="button"]', function() {
		"use strict";
		var steps = [];
		var demo_type = jQuery('#trx_importer_form [name="demo_type"]:checked').val();
		var demo_set = jQuery('#trx_importer_form [name="demo_set"]:checked').val();
		var demo_parts = '', demo_pages = '';
		jQuery(this).parents('form').find('input[type="checkbox"].trx_importer_item').each(function() {
			"use strict";
			var name = jQuery(this).attr('name');
			// Collect parts to be imported
			if (jQuery(this).get(0).checked) {
				demo_parts += (demo_parts ? ',' : '') + name.substr(7); // Remove 'import_' from name - save only slug
				// Collect pages to be import
				if (demo_set=='part' && name == 'import_posts') {
					jQuery('.trx_importer_part_pages input[type="checkbox"]').each(function() {
						"use strict";
						if (jQuery(this).get(0).checked) {
							demo_pages += (demo_pages ? ',' : '') + jQuery(this).val();
						}
					});
				}
				var step = {
					action: name,
					data: {
						demo_type: demo_type,
						demo_set: demo_set,
						demo_parts: demo_parts,
						demo_pages: demo_pages,
						start_from_id: 0
					}
				};
				steps.push(step);
			} else
				jQuery('#trx_importer_progress .'+name).hide();
		});
		steps.unshift({
			action: 'import_start',
			data: { 
				demo_type: demo_type,
				demo_set: demo_set,
				demo_parts: demo_parts
			}
		});
		steps.push({
			action: 'import_end',
			data: { 
				demo_type: demo_type,
				demo_set: demo_set,
				demo_parts: demo_parts
			}
		});
		// Start import
		jQuery('#trx_importer_form').hide();
		jQuery('#trx_importer_progress').fadeIn();
		AXIOM_WELLDONE_STORAGE['importer_error_messages'] = '';
		AXIOM_WELLDONE_STORAGE['importer_ignore_errors'] = true;
		axiom_welldone_importer_do_action(steps, 0);
	});
});

// Call specified action (step)
function axiom_welldone_importer_do_action(steps, idx) {
	"use strict";
	if ( !jQuery('#trx_importer_progress .'+steps[idx].action+' .import_progress_status').hasClass('step_in_progress') )
		jQuery('#trx_importer_progress .'+steps[idx].action+' .import_progress_status').addClass('step_in_progress').html('0%');
	// AJAX query params
	var data = {
		ajax_nonce: AXIOM_WELLDONE_STORAGE['ajax_nonce'],
		action: 'axiom_welldone_importer_start_import',
		importer_action: steps[idx].action
	};
	// Additional params depend current step
	for (var i in steps[idx].data)
		data[i] = steps[idx].data[i];
	// Send request to server
	jQuery.post(AXIOM_WELLDONE_STORAGE['ajax_url'], data, function(response) {
		"use strict";
		var rez = {};
		try {
			rez = JSON.parse(response);
		} catch (e) {
			rez = { error: AXIOM_WELLDONE_STORAGE['ajax_error']+':<br>'+response };
			console.log(response);
		}
		if (rez.error === '' || AXIOM_WELLDONE_STORAGE['importer_ignore_errors']) {
			if (rez.error !== '') 
				AXIOM_WELLDONE_STORAGE['importer_error_messages'] += '<p class="error_message">' + rez.error + '</p>';
			var action = rez.action;
			if (rez.result === null || rez.result >= 100) {
				jQuery('#trx_importer_progress .'+action+' .import_progress_status').html('');
				jQuery('#trx_importer_progress .'+action+' .import_progress_status').removeClass('step_in_progress').addClass('step_complete'+(rez.error ? ' step_complete_with_error' : ''));
				idx++;
			} else {
				jQuery('#trx_importer_progress .'+action+' .import_progress_status').html(rez.result + '%');
				steps[idx].data['start_from_id'] = (typeof rez.start_from_id != 'undefined') ? rez.start_from_id : 0;
				steps[idx].data['attempt'] = (typeof rez.attempt != 'undefined') ? rez.attempt : 0;
			}
			// Do next action
			if (idx < steps.length) {
				axiom_welldone_importer_do_action(steps, idx);
			} else {
				if (AXIOM_WELLDONE_STORAGE['importer_error_messages']) {
					jQuery('#trx_importer_progress').removeClass('notice-info').addClass('notice-error').append('<h4>' + AXIOM_WELLDONE_STORAGE['importer_error_msg'] + '</h4>' + AXIOM_WELLDONE_STORAGE['importer_error_messages']);
				} else {
					jQuery('#trx_importer_progress').removeClass('notice-info').addClass('notice-success');
					jQuery('.trx_importer_progress_complete').show();
				}
			}
		} else {
			// Add Error block above Import section
			jQuery('#trx_importer_progress').removeClass('notice-info').addClass('notice-error').css({'paddingTop': '1em', 'paddingBottom': '1em'}).html(rez.error);
		}
	});
}