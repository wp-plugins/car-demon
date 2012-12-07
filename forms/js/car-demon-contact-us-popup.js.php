<?php
header('Content-type: text/javascript');
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}

if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/forms/js', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\forms\js', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$car_demon_pluginpath = str_replace('forms/js','',$car_demon_pluginpath);
$x = '';
$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'contact_us', 'unk');
$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'contact_us', 'unk');
?>
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