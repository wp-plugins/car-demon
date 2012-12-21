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
$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'parts', 'unk');
$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'parts', 'unk');
?>
// JavaScript Document
function add_part(form_id) {
	if (document.getElementById('lb_overlay')) {
		var b_height = getDocHeight();
		b_height = b_height + 150;
		document.getElementById('lb_overlay').style.height = b_height + 'px';
	}
	var number_of_parts = document.forms["part_form"+form_id].number_of_parts.value;
	var number_of_parts = parseInt(number_of_parts) + 1;
	if (number_of_parts > 1) {
		document.getElementById("remove_part_btn"+form_id).style.display = "block";
	}
	if (number_of_parts < 11) {
		document.forms["part_form"+form_id].number_of_parts.value = number_of_parts;
		document.getElementById("part_name_label_"+number_of_parts+form_id).style.display = "block"
		document.getElementById("part_number_label_"+number_of_parts+form_id).style.display = "block"
		document.getElementById("part_end_"+number_of_parts+form_id).style.display = "block"
	} else {
		alert("<?php echo __('You may only add 10 parts to your request', 'car-demon'); ?>\n<?php echo __('If you need additional parts please add them in the comment area.', 'car-demon'); ?>");
	}
	if (number_of_parts > 9) {
		document.getElementById("add_part_btn"+form_id).style.display = "none";
	}
}
function remove_part(form_id) {
	var number_of_parts = document.forms["part_form"+form_id].number_of_parts.value;
	document.getElementById("add_part_btn"+form_id).style.display = "block";
	if (number_of_parts > 1) {
		document.getElementById("part_name_label_"+number_of_parts+form_id).style.display = "none"
		document.getElementById("part_number_label_"+number_of_parts+form_id).style.display = "none"
		document.getElementById("part_end_"+number_of_parts+form_id).style.display = "none"
		var number_of_parts = parseInt(number_of_parts) - 1;
		document.forms["part_form"+form_id].number_of_parts.value = number_of_parts;
	}
	if (number_of_parts < 2) {
		document.getElementById("remove_part_btn"+form_id).style.display = "none";
	}
}
function car_demon_validate_part_request(form_id) {
	var msg = "";
	var name_valid = 0;
	if (document.forms["part_form"+form_id].cd_name.value == "") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "part_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["part_form"+form_id].cd_name.value == "Your Name") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "part_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "part_form"+form_id);
		}
	}
	<?php
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
	?>
	if (document.forms["part_form"+form_id].cd_phone.value == "") {
		var msg = msg + "<?php _e('You must enter a valid Phone Number.', 'car-demon'); ?><br />";
		cd_not_valid("cd_phone", "part_form"+form_id);
	} else {
		if (document.forms["part_form"+form_id].cd_phone.value.length != 14) {
			var msg = msg + "<?php _e('The phone number you entered is not valid.', 'car-demon'); ?><br />";
			cd_not_valid("cd_phone", "part_form"+form_id);			
		}
		else {
			cd_valid("cd_phone", "part_form"+form_id);
		}
	}
	<?php
		}
	}
	?>
	var e_msg = validateEmail(document.forms["part_form"+form_id].cd_email);
	if (e_msg == "") {
		cd_valid("cd_email", "part_form"+form_id);
	} else {
		var msg = msg + e_msg + "<br />";
	}
	var radios = document.forms["part_form"+form_id].part_location;
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
		var msg = msg + "<?php _e('You did not select a part location.', 'car-demon'); ?><br />";
	}
	if (document.forms["part_form"+form_id].part_name_1.value == "") {
		var msg = msg + "<?php _e('You need to add at least the name of one part you are looking for.', 'car-demon'); ?><br />";
		cd_not_valid("part_name_1", "part_form"+form_id);			
	} else {
		cd_valid("part_name_1", "part_form"+form_id);
	}
	if (msg != "") {
		document.getElementById("part_msg"+form_id).style.display = "block";
		document.getElementById("part_msg"+form_id).innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		var action = "";
		var your_name = document.forms["part_form"+form_id].cd_name.value;
		var phone = document.forms["part_form"+form_id].cd_phone.value;
		var email = document.forms["part_form"+form_id].cd_email.value;
		var radios = document.forms["part_form"+form_id].part_location;
		var part_location = location_value;
		var part_needed = document.forms["part_form"+form_id].part_needed.value;
		var number_of_parts = document.forms["part_form"+form_id].number_of_parts.value;
		var year = document.forms["part_form"+form_id].year.value;
		var make = document.forms["part_form"+form_id].make.value;
		var model = document.forms["part_form"+form_id].model.value;
		var vin = document.forms["part_form"+form_id].vin.value;
		var form_key = document.forms["part_form"+form_id].form_key.value;
		var str = "";
		var i = 0;
		var num = ""
		while (i<number_of_parts) {
			i++;
			num = i;
			num = num.toString()
			window["part_name_" + num] = document.forms["part_form"+form_id]["part_name_"+num].value
			window["part_number_" + num] = document.forms["part_form"+form_id]["part_number_"+num].value
			str = str + ",Part Name #"+num+":";
			str = str + window["part_name_" + num];
			str = str + ",Part Number #"+num+":";
			str = str + window["part_number_" + num];
		}
		str = "@" + str;
		str = str.replace("@,","");
		str = str.replace("@","");
		<?php echo $hook_form_js; ?>
		jQuery.ajax({
			type: 'POST',
			data: {'your_name': your_name,'phone':phone, 'email':email, 'part_location':part_location,'year':year, 'make':make, 'model':model, 'vin':vin, 'part_needed':part_needed, 'number_of_parts':number_of_parts, 'part_list': str, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-part-handler.php?send_part=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("part_msg"+form_id).style.display = "block";
				document.getElementById("part_msg"+form_id).style.background = "#FFFFFF";
				document.getElementById("part_msg"+form_id).innerHTML = html;
				document.getElementById("part_form"+form_id).style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}