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
	$newPath = str_replace('wp-content/plugins/car-demon/vin-query/js', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\vin-query\js', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$rootpath = str_replace('wp-content/plugins/car-demon/vin-query/js','wp-admin/images/',$car_demon_pluginpath);
$car_demon_pluginpath = str_replace('vin-query/js','',$car_demon_pluginpath);
?>
// JavaScript Document
function remove_decode(post_id) {
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id},
		url: "<?php echo  $car_demon_pluginpath; ?>vin-query/car-demon-vin-query-handler.php?decode=remove",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			window.location.reload();
		}
	})
}
function update_vehicle_data(fld, post_id) {
	var fld_name = fld.id;
	var val = fld.value;
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'fld': fld_name, 'val': val},
		url: "<?php echo  $car_demon_pluginpath; ?>vin-query/car-demon-vin-query-handler.php?decode=update_data",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById(fld_name).style.background = "#00FF00";
			var delay = function() {
				document.getElementById(fld_name).style.background = "#FFFFFF";
				};
			setTimeout(delay, 2500);
		}
	})
}
function update_admin_decode(fld, post_id) {
	var fld_name = fld.id;
	var val = fld.value;	
	var loading = "<?php echo $rootpath; ?>wpspin_light.gif";
	document.getElementById(fld_name).style.backgroundPosition = "right center";
	document.getElementById(fld_name).style.backgroundRepeat = "no-repeat";
	document.getElementById(fld_name).style.backgroundImage = "url("+loading+")";
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'fld': fld_name, 'val': val},
		url: "<?php echo  $car_demon_pluginpath; ?>vin-query/car-demon-vin-query-handler.php?decode=update",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById(fld_name).style.background = "#00FF00";
			var delay = function() {
				document.getElementById(fld_name).style.background = "#FFFFFF";
				};
			setTimeout(delay, 2500);
		}
	})
}
function update_decode_option(fld, post_id) {
	var fld_name = fld.id;
	var val = fld.value;
	if (val == "") {
		var img = "<?php echo $car_demon_pluginpath; ?>theme-files/images/spacer.gif";
	}
	else if (val == "Std.") {
		var img = "<?php echo $car_demon_pluginpath; ?>theme-files/images/opt_standard.gif";
	}
	else if (val == "Opt.") {
		var img = "<?php echo $car_demon_pluginpath; ?>theme-files/images/opt_optional.gif";
	}
	else if (val == "N/A") {
		var img = "<?php echo $car_demon_pluginpath; ?>theme-files/images/opt_na.gif";
	}
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'fld': fld_name, 'val': val},
		url: "<?php echo  $car_demon_pluginpath; ?>vin-query/car-demon-vin-query-handler.php?decode=update",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("img_"+fld_name).src = img;
			document.getElementById("img_"+fld_name).style.width = "22px";
			document.getElementById("img_"+fld_name).style.height = "24px";
			document.getElementById(fld_name).style.background = "#00FF00";
			var delay = function() {
				document.getElementById(fld_name).style.background = "#FFFFFF";
				};
			setTimeout(delay, 2500);
		}
	})
}
function car_demon_switch_tabs(active, number, tab_prefix, content_prefix) {
	for (var i=1; i < number+1; i++) {  
	  document.getElementById(content_prefix+i).style.display = 'none';  
	  document.getElementById(tab_prefix+i).className = '';  
	}
	document.getElementById(content_prefix+active).style.display = 'block';
	document.getElementById(tab_prefix+active).className = 'active'; 
}
function edit_decode_vin(post_id) {
	jQuery("#car_demon_light_box").lightbox_me({
		overlayCSS: {background: 'black', opacity: .6}
	});

	document.getElementById("vin_decode_options_"+post_id).style.display = "inline";
}
function close_car_demon_lightbox() {
	jQuery("#car_demon_light_box").trigger('close');
}
function activate_vehicle() {
	var go = 1;
	if (document.getElementById("title").value == "") {var go = 0; var msg = "Title"}
	if (document.getElementById("stock_num").value == "") {var go = 0; var msg = "Stock Number"}
	if (document.getElementById("vin").value == "") {var go = 0; var msg = "VIN"}
	if (document.getElementById("selling_price").value == "") {var go = 0; var msg = "Selling Price"}
	if (document.getElementById("year").value == "") {var go = 0; var msg = "Year"}
	if (document.getElementById("make").value == "") {var go = 0; var msg = "Make"}
	if (document.getElementById("model").value == "") {var go = 0; var msg = "Model"}
	if (document.getElementById("mileage").value == "") {var go = 0; var msg = "Mileage"}
	if (go == 0) {
		document.getElementById("status_yes").checked = false;
		document.getElementById("status_no").checked = true;
		alert("You must fill out the "+ msg +" field before you can mark this vehicle as ready for Sale.");
	} else {
		alert("All Good");
	}
}
function dashboard_decode_vin(post_id) {
	var vin = document.getElementById("cd_vin").value;
	var title = document.getElementById("cd_title").value;
	var stock = document.getElementById("cd_stock").value
	var vin_status = validate_vin(vin);
	var nogo = 0;
	if (stock == "") { nogo = 1; }
	if (title == "") { nogo = 1; }
	if (nogo == 0) {
		if (vin_status == 1) {
			var loading = "<span class='decode_loading'><img src='<?php echo $rootpath; ?>wpspin_light.gif'>&nbsp;Loading...</span>";
			document.getElementById("decode_results").style.display = 'block';
			document.getElementById("decode_results").innerHTML = loading;
			jQuery.ajax({
				type: 'POST',
				data: {'post_id': post_id, 'vin': vin, 'title': title, 'stock': stock},
				url: "<?php echo  $car_demon_pluginpath; ?>vin-query/car-demon-vin-query-handler.php?decode=dashboard",
				timeout: 2000,
				error: function() {},
				dataType: "html",
				success: function(html){
				var new_body = html;
					window.location = html;
				}
			})
		}
	} else {
		dashboard_send_alert();
	}
	return false;
}
function dashboard_send_alert() {
	document.getElementById("alert_msg").style.display = "block"; 
	document.getElementById("alert_msg").innerHTML = "You must fill out all fields.";
	document.getElementById("cd_title").style.background = "#FF0000";
	document.getElementById("cd_stock").style.background = "#FF0000";
	var delay = function() { 
		document.getElementById("cd_title").style.background = "#FFFFFF";
		document.getElementById("cd_stock").style.background = "#FFFFFF";
		document.getElementById("alert_msg").style.display = "none"; 
		};
	setTimeout(delay, 2500);
}
function decode_vin(post_id) {
	var loading = "<span class='decode_loading'><img src='<?php echo $rootpath; ?>wpspin_light.gif'>&nbsp;Loading...</span>";
	document.getElementById("decode_results").style.display = 'block';
	document.getElementById("decode_results").innerHTML = loading;
	var vin = document.getElementById("vin").value
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, 'vin': vin},
		url: "<?php echo  $car_demon_pluginpath; ?>vin-query/car-demon-vin-query-handler.php?decode=post",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			window.location.reload();
		}
	})
	return false;
}
function validate_vin(vin) {
	vin_len=vin.length;
	var go = 1
	if (vin_len < 17) {
			document.getElementById("cd_vin").style.background = "#FF0000";
			var delay = function() { document.getElementById("cd_vin").style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
			document.getElementById("alert_msg").style.display = "block"; 
			document.getElementById("alert_msg").innerHTML = "Your VIN must be exactly 17 characters. You currently have "+ vin_len + " characters.";
			document.getElementById("cd_vin").style.background = "#FF0000";
			var delay = function() { 
				document.getElementById("cd_vin").style.background = "#FFFFFF";
				document.getElementById("alert_msg").style.display = "none"; 
				};
			setTimeout(delay, 2500);
			var go = 0;
	}
	if (vin_len > 17) {
			document.getElementById("cd_vin").style.background = "#FF0000";
			var delay = function() { document.getElementById("cd_vin").style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
			document.getElementById("alert_msg").style.display = "block"; 
			document.getElementById("alert_msg").innerHTML = "Your VIN must be exactly 17 characters. You currently have "+ vin_len + " characters.";
			document.getElementById("cd_vin").style.background = "#FF0000";
			var delay = function() { 
				document.getElementById("cd_vin").style.background = "#FFFFFF";
				document.getElementById("alert_msg").style.display = "none"; 
				};
			setTimeout(delay, 2500);
			var go = 0;
	}
	if (go == 1) {
		document.getElementById("cd_vin").style.background = "#99CC99";
		var delay = function() { document.getElementById("cd_vin").style.background = "#FFFFFF" };
		setTimeout(delay, 1000);
	}
	return go;
}