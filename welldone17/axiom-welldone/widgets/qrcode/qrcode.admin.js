/* global jQuery:false */
/* global AXIOM_WELLDONE_STORAGE:false */

jQuery(document).ready(function(){
	axiom_welldone_qrcode_init();
	axiom_welldone_color_picker();
});

function axiom_welldone_qrcode_init() {
	jQuery('#widgets-right .widget_qrcode:not(.inited)').each(function() {
		var widget = jQuery(this).addClass('inited');
		widget.find('input.iColorPicker:not(.colored)').each(function() {
			var obj = jQuery(this);
			if (obj.attr('id').indexOf('__i__') < 0) {
				obj.addClass('colored');
				axiom_welldone_set_color_picker(obj.attr('id'));
			}
		});
		widget.find('div.qrcode_tabs').tabs();
		widget.on('click', '.fld_button_draw', function() {
			axiom_welldone_qrcode_update(widget);
		});
		widget.parents('form').on('click', '.widget-control-save', function() {
			axiom_welldone_qrcode_update(widget);
		});
		widget.find('.tab_personal input,.tab_text textarea,.fld_auto_draw,.iColorPicker').on('change', function () {
			if (widget.find('.fld_auto_draw').attr('checked')=='checked') {
				widget.find('.fld_button_draw').hide();
				axiom_welldone_qrcode_update(widget);
			} else 
				widget.find('.fld_button_draw').show();
		});
		if (widget && widget.find('.fld_auto_draw').attr('checked')=='checked')
			widget.find('.fld_button_draw').hide();
	});
}

function axiom_welldone_qrcode_update(widget) {
	axiom_welldone_qrcode_show(widget, {
			ufname:		widget.find('.fld_ufname').val(),
			ulname:		widget.find('.fld_ulname').val(),
			ucompany:	widget.find('.fld_ucompany').val(),
			usite:		widget.find('.fld_usite').val(),
			uemail:		widget.find('.fld_uemail').val(),
			uphone:		widget.find('.fld_uphone').val(),
			uaddr:		widget.find('.fld_uaddr').val(),
			ucity:		widget.find('.fld_ucity').val(),
			upostcode:	widget.find('.fld_upostcode').val(),
			ucountry:	widget.find('.fld_ucountry').val(),
			uid:		widget.find('.fld_uid').val(),
			urev:		widget.find('.fld_urev').val(),
			text: 		widget.find('.fld_text').val()
		}, 
		{
			qrcode: widget.find('.qrcode_image').eq(0),
			personal: widget.find('.qrcode_data'),
			show_personal: widget.find('.fld_show_personal').attr('checked')=='checked',
			show_what: widget.find('.fld_show_what').attr('checked')=='checked' ? 1 : 0,
			width: widget.find('.fld_width').val(),
			color: widget.find('.fld_color').val(),
			bg: widget.find('.fld_bg').val()
		}
	);
	var image = widget.find('.qrcode_image canvas').get(0).toDataURL('image/png');
	widget.find('.fld_image').val(image);
	widget.find('.qrcode_image img').attr('src', image);
}

function axiom_welldone_qrcode_show(widget, vc, opt) {
	if (opt.show_what==1) {
		var text = 'BEGIN:VCARD\n'
			+ 'VERSION:3.0\n'
			+ 'FN:' + vc.ufname + ' ' + vc.ulname + '\n'
			+ 'N:' + vc.ulname + ';' + vc.ufname + '\n'
			+ (vc.ucompany ? 'ORG:' + vc.ucompany + '\n' : '')
			+ (vc.uphone ? 'TEL;TYPE=cell, pref:' + vc.uphone + '\n' : '')
			+ (vc.ufax ? 'TEL;TYPE=fax, pref:' + vc.ufax + '\n' : '')
			+ (vc.uaddr || vc.ucity || vc.ucountry ? 'ADR;TYPE=dom, home, postal, parcel:;;' + vc.uaddr + ';' + vc.ucity + ';;' + vc.upostcode + ';' + vc.ucountry + '\n' : '')
			+ (vc.usite ? 'URL:' + vc.usite + '\n' : '')
			+ (vc.uemail ? 'EMAIL;TYPE=INTERNET:' + vc.uemail + '\n' : '')
			+ (vc.ucats ? 'CATEGORIES:' + vc.ucats + '\n' : '')
			+ (vc.unote ? 'NOTE:' + vc.unote + '\n' : '')
			+ (vc.urev ? 'NOTE:' + vc.urev + '\n' : '')
			+ (vc.uid ? 'UID:' + vc.uid + '\n' : '')
			+ 'END:VCARD';
	} else {
		var text = vc.text;
	}
	opt.qrcode
		.empty()
		.qrcode({
			'text': text,
			'color': opt.color,
			'bgColor': opt.bg!='' ? opt.bg : null,
			'width': opt.width,
			'height': opt.width,
			'size': opt.width
		});
	if (opt.show_personal == 0)
		opt.personal.empty().hide(); 
	else
		opt.personal.html(
			'<ul>'
				+ (opt.show_what==1 
					? '<li class="user_name odd first">' + vc.ufname + ' ' + vc.ulname + '</li>'
						+ (vc.ucompany ? '<li class="user_company even">' + vc.ucompany + '</li>' : '')
						+ (vc.uphone ? '<li class="user_phone odd">' + vc.uphone + '</li>' : '')
						+ (vc.uemail ? '<li class="user_email even"><a href="mailto:' + vc.uemail + '">' + vc.uemail + '</a></li>' : '')
						+ (vc.usite ? '<li class="user_site odd"><a href="' + vc.usite + '" target="_blank">' + vc.usite + '</a></li>' : '')
					: '<li class="text odd first">' + vc.text + '</li>')
			+ '</ul>'
		).show();
}

if (!window.axiom_welldone_set_color_picker) {
	function axiom_welldone_set_color_picker(id_picker) {
		jQuery('#'+id_picker).on('click', function (e) {
			"use strict";
			axiom_welldone_color_picker_show(null, jQuery(this), function(fld, clr) {
				"use strict";
				fld.css('backgroundColor', clr).val(clr);
			});
		});
	}
}
