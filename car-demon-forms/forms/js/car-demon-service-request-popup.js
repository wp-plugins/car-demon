// JavaScript Document
function show_service_popup(popup_id) {
	jQuery('#service_form_container_'+popup_id).lightbox_me({
		centered: true,
		onLoad: function() { }
		});
}
function close_service_popup(popup_id) {
	jQuery('#service_form_container_'+popup_id).trigger('close');
}