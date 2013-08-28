// JavaScript Document
function show_contact_popup(popup_id) {
	jQuery('#contact_form_container_'+popup_id).lightbox_me({
		centered: true,
		onLoad: function() { }
		});
}
function close_contact_form(popup_id) {
	jQuery('#contact_form_container_'+popup_id).trigger('close');
}