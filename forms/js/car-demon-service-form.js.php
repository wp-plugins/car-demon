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
$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'service_appointment', 'unk');
$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'service_appointment', 'unk');
?>
// JavaScript Document
function car_demon_validate_service_form(form_id) {
	var msg = "";
	var name_valid = 0;
	if (document.forms["service_form"+form_id].cd_name.value == "") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "service_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["service_form"+form_id].cd_name.value == "Your Name") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "service_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "service_form"+form_id);
		}
	}
	<?php
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
	?>
	if (document.forms["service_form"+form_id].cd_phone.value == "") {
		var msg = msg + "<?php _e('You must enter a valid Phone Number.', 'car-demon'); ?><br />";
		cd_not_valid("cd_phone", "service_form"+form_id);
	} else {
		if (document.forms["service_form"+form_id].cd_phone.value.length != 14) {
			var msg = msg + "<?php _e('The phone number you entered is not valid.', 'car-demon'); ?><br />";
			cd_not_valid("cd_phone", "service_form"+form_id);			
		}
		else {
			cd_valid("cd_phone", "service_form"+form_id);
		}
	}
	<?php
		}
	}
	?>
	var e_msg = validateEmail(document.forms["service_form"+form_id].cd_email);
	if (e_msg == "") {
		cd_valid("cd_email", "service_form"+form_id);
	} else {
		var msg = msg + e_msg + "<br />";
	}			
	var radios = document.forms["service_form"+form_id].service_location;
	var location_value = radios.value;
	if (radios.length > 0) {
		var location_value = "";
	}
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].type === 'radio' && radios[i].checked) {
			location_value = radios[i].value;
		}
	}
	if (location_value == "") {
		var msg = msg + "<?php _e('You did not select a service location.', 'car-demon'); ?><br />";
	}
	if (document.forms["service_form"+form_id].preferred_date.value == "") {
		var msg = msg + "<?php _e('You did not select a preferred appointment date.', 'car-demon'); ?><br />";
		cd_not_valid("preferred_date", "service_form"+form_id);			
	} else {
		cd_valid("preferred_date", "service_form"+form_id);
	}
	if (document.forms["service_form"+form_id].alt_date.value == "") {
		var msg = msg + "<?php _e('You did not select an alternate appointment date.', 'car-demon'); ?><br />";
		cd_not_valid("alt_date", "service_form"+form_id);			
	} else {
		cd_valid("alt_date", "service_form"+form_id);
	}
	if (document.forms["service_form"+form_id].service_needed.value == "") {
		var msg = msg + "<?php _e('You did not tell us what kind of service you need.', 'car-demon'); ?><br />";
		cd_not_valid("service_needed", "service_form"+form_id);			
	} else {
		cd_valid("service_needed", "service_form"+form_id);
	}
	if (msg != "") {
		document.getElementById("service_msg"+form_id).style.display = "block";
		document.getElementById("service_msg"+form_id).innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		var action = "";
		var your_name = document.forms["service_form"+form_id].cd_name.value;
		var phone = document.forms["service_form"+form_id].cd_phone.value;
		var email = document.forms["service_form"+form_id].cd_email.value;
		var radios = document.forms["service_form"+form_id].service_location;
		var service_location = location_value;
		var pref_date = document.forms["service_form"+form_id].preferred_date.value;
		var alt_date = document.forms["service_form"+form_id].alt_date.value;
		var radios = document.forms["service_form"+form_id].waiting;
		var waiting = cd_get_radios(radios);
		var radios =document.forms["service_form"+form_id].transportation;
		var transportation = cd_get_radios(radios);
		var year = document.forms["service_form"+form_id].year.value;
		var make = document.forms["service_form"+form_id].make.value;
		var model = document.forms["service_form"+form_id].model.value;
		var miles = document.forms["service_form"+form_id].miles.value;
		var vin = document.forms["service_form"+form_id].vin.value;
		var service_needed = document.forms["service_form"+form_id].service_needed.value;
		var form_key = document.forms["service_form"+form_id].form_key.value;
		<?php echo $hook_form_js; ?>
		jQuery.ajax({
			type: 'POST',
			data: {'your_name': your_name,'phone':phone, 'email':email, 'service_location':service_location,'pref_date':pref_date, 'alt_date':alt_date, 'waiting':waiting, 'transportation':transportation, 'year':year, 'make':make, 'model':model, 'miles':miles, 'vin':vin, 'service_needed':service_needed, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-service-handler.php?send_service=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("service_msg"+form_id).style.display = "block";
				document.getElementById("service_msg"+form_id).style.background = "#FFFFFF";
				document.getElementById("service_msg"+form_id).innerHTML = html;
				document.getElementById("service_form"+form_id).style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}