// JavaScript Document

function show_service_quote_popup(popup_id) {
	jQuery('#service_quote_container_'+popup_id).lightbox_me({
		centered: true,
		onLoad: function() { }
		});
}

function close_service_quote_popup(popup_id) {
	jQuery('#service_quote_container_'+popup_id).trigger('close');
}