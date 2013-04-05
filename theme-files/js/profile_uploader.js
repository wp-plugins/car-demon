jQuery(document).ready(function() {
	var sendto = '';
	var sendto_img = '';
	jQuery('#upload_profile_button').click(function() {
	 formfield = jQuery('#profile_photo').attr('name');
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 sendto = 'profile_photo';
	 sendto_img = 'custom_user_photo';
	 return false;
	});
	jQuery('#upload_slider_button').click(function() {
	 formfield = jQuery('#slider_image').attr('name');
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 sendto = 'slider_image';
	 sento_img = 'custom_slider_photo';
	 return false;
	});
	window.send_to_editor = function(html) {
	 imgurl = jQuery('img',html).attr('src');
	 jQuery('#'+sendto_img).attr('src',imgurl);
	 jQuery('#'+sendto_img).width('200px');
	 jQuery('#'+sendto).val(imgurl);
	 tb_remove();
	}
});