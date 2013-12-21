// JavaScript Document
function show_qualify_popup(popup_id) {
	jQuery('#qualify_form_container_'+popup_id).lightbox_me({
		centered: true,
		onLoad: function() { }
		});
}
function close_qualify_form(popup_id) {
	jQuery('#qualify_form_container_'+popup_id).trigger('close');
}