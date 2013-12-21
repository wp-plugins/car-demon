// JavaScript Document
function show_parts_popup(popup_id) {
	jQuery('#parts_form_container_'+popup_id).lightbox_me({
		centered: true,
		onLoad: function() { }
		});
}
function close_parts_popup(popup_id) {
	jQuery('#parts_form_container_'+popup_id).trigger('close');
}