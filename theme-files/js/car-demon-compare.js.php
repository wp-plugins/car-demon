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
	$newPath = str_replace('wp-content/plugins/car-demon/theme-files/js', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\theme-files\js', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$car_demon_pluginpath = str_replace('theme-files/js','',$car_demon_pluginpath);
?>
// JavaScript Document

function update_car(post_id,fld) {
	if (fld.checked == true) {
		var action = '1';
	} else {
		var action = '0';
		var compareElement = document.getElementById("compare_"+post_id);
		if (compareElement != null) {
			document.getElementById("compare_"+post_id).checked = false;
		}
	}
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'action': action},
		url: "<?php echo $car_demon_pluginpath; ?>includes/car-demon-handler.php?compare_car=1",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("car_demon_compare").innerHTML = html;
		}
	})
	return false;
}
function get_compare_list() {
	jQuery.ajax({
		type: 'POST',
		data: {'compare_list': '1'},
		url: "<?php echo $car_demon_pluginpath; ?>includes/car-demon-handler.php?get_compare_list=1",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("car_demon_compare_box_main").innerHTML = html;
		}
	})
	return false;
}
function open_car_demon_compare() {
	jQuery("#car_demon_compare_div").lightbox_me({
		overlayCSS: {background: 'black', opacity: .6}
	});
	document.getElementById('car_demon_compare_box').style.display = "block";
	get_compare_list();
}
function close_car_demon_compare() {
	jQuery("#car_demon_compare_div").trigger('close');
}
function print_compare() {
	w=window.open();
	if(!w)alert('Please enable pop-ups');
	var new_print = '<title><?php _e('Compare Vehicles','car-demon'); ?></title>';
	var new_print = new_print + '<meta http-equiv="X-UA-Compatible" content="IE8"/>';
	var new_print = new_print + '<link rel="stylesheet" type="text\/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />';
	var new_print = new_print + document.getElementById('car_demon_compare_box_list_cars').innerHTML;
	w.document.write(new_print);
	if (navigator.appName == "Microsoft Internet Explorer") {
		w.document.close();
	}
	w.focus();
	w.print();
	w.close();
	return false;
}