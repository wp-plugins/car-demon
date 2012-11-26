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
	$newPath = str_replace('wp-content/plugins/car-demon/admin/js', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\admin\js', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$car_demon_pluginpath = str_replace('admin/js','',$car_demon_pluginpath);
?>
// JavaScript Document
function update_car(post_id, this_fld, fld) {
	var new_value = this_fld.value;
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'val': new_value, 'fld': fld},
		url: "<?php  echo $car_demon_pluginpath ; ?>includes/car-demon-handler.php?update_car=1",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			this_fld.style.background = "#99CC99";
			var delay = function() { this_fld.style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
			var msrp = document.getElementById("msrp_"+post_id).value;
			var rebate = document.getElementById("rebate_"+post_id).value;
			var discount = document.getElementById("discount_"+post_id).value;				
			var price = document.getElementById("price_"+post_id).value;
			if (msrp == "") { msrp = 0; }
			if (rebate == "") { rebate = 0; }
			if (discount == "") { discount = 0; }
			if (price == "") { price = 0; }
			msrp = parseInt(msrp);
			rebate = parseInt(rebate);
			discount = parseInt(discount);
			price = parseInt(price);
			var calc_price = msrp - rebate - discount;
			document.getElementById("calc_price_"+post_id).innerHTML = calc_price
			document.getElementById("calc_discounts_"+post_id).innerHTML = rebate + discount;
			if (price != calc_price) {
				if (msrp != 0) {
					document.getElementById("price_"+post_id).style.background = "#FFB3B3";
					document.getElementById("calc_error_"+post_id).innerHTML = "Calc Error: " + (calc_price - price) + "<br />";
				}
				else {
					document.getElementById("price_"+post_id).style.background = "#FFFFFF";
					document.getElementById("calc_error_"+post_id).innerHTML = "";
				}
			}
			else {
				document.getElementById("calc_error_"+post_id).innerHTML = "";
				document.getElementById("price_"+post_id).style.background = "#FFFFFF";
			}
		}
	})
	return false;
}
function update_car_sold(post_id, this_fld, fld) {
	var new_value = this_fld.options[this_fld.selectedIndex].value;
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'val': new_value, 'fld': fld},
		url: "<?php  echo $car_demon_pluginpath ; ?>includes/car-demon-handler.php?update_car=1",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			this_fld.style.background = "#99CC99";
			var delay = function() { this_fld.style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}
function show_custom_slide(slide_num) {
	document.getElementById("custom_slide_"+slide_num).style.display = "inline";
	document.getElementById("show_slide_"+slide_num).style.display = "none";
	document.getElementById("hide_slide_"+slide_num).style.display = "inline";
}
function hide_custom_slide(slide_num) {
	document.getElementById("custom_slide_"+slide_num).style.display = "none";
	document.getElementById("show_slide_"+slide_num).style.display = "inline";
	document.getElementById("hide_slide_"+slide_num).style.display = "none";
}
function clear_custom_slide(slide_num) {
	document.getElementById("custom_slide"+slide_num+"_title").value = "";
	document.getElementById("custom_slide"+slide_num+"_img").value = "";
	document.getElementById("custom_slide"+slide_num+"_link").value = "";
	document.getElementById("custom_slide"+slide_num+"_text").value = "";
}