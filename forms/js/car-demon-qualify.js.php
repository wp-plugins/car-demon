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
$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'qualify', 'unk');
$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'qualify', 'unk');
?>
// JavaScript Document
function car_demon_validate_qualify(form_id) {
	var msg = "";
	var name_valid = 0;
	if (document.forms["qualify_form"+form_id].cd_name.value == "") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "qualify_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["qualify_form"+form_id].cd_name.value == "Your Name") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "qualify_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "qualify_form"+form_id);
		}
	}
	<?php
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
	?>
	if (document.forms["qualify_form"+form_id].cd_phone.value == "") {
		var msg = msg + "<?php _e('You must enter a valid Phone Number.', 'car-demon'); ?><br />";
		cd_not_valid("cd_phone", "qualify_form"+form_id);
	} else {
		if (document.forms["qualify_form"+form_id].cd_phone.value.length != 14) {
			var msg = msg + "<?php _e('The phone number you entered is not valid.', 'car-demon'); ?><br />";
			cd_not_valid("cd_phone", "qualify_form"+form_id);			
		}
		else {
			cd_valid("cd_phone", "qualify_form"+form_id);
		}
	}
	<?php
		}
	}
	?>
	var e_msg = validateEmail(document.forms["qualify_form"+form_id].cd_email);
	if (e_msg == "") {
		cd_valid("cd_email", "qualify_form"+form_id);
	} else {
		var msg = msg + e_msg + "<br />";
	}
	if (document.forms["qualify_form"+form_id].cd_city.value == "") {
		var msg = msg + "<?php _e('You must enter your city.', 'car-demon'); ?><br />";
		cd_not_valid("cd_city", "qualify_form"+form_id);
	} else {
		cd_valid("cd_city", "qualify_form"+form_id);
	}
	if (document.forms["qualify_form"+form_id].cd_employer.value == "") {
		var msg = msg + "<?php _e('You must enter your employer.', 'car-demon'); ?><br />";
		cd_not_valid("cd_employer", "qualify_form"+form_id);
	} else {
		cd_valid("cd_employer", "qualify_form"+form_id);
	}
	if (document.forms["qualify_form"+form_id].cd_income.value == "") {
		var msg = msg + "<?php _e('You must enter your income.', 'car-demon'); ?><br />";
		cd_not_valid("cd_income", "qualify_form"+form_id);
	} else {
		cd_valid("cd_income", "qualify_form"+form_id);
	}
	if (document.forms["qualify_form"+form_id].cd_down.value == "") {
		document.forms["qualify_form"+form_id].cd_down.value = 0;
	}
	var taa_fld = document.forms["qualify_form"+form_id].taa;
	var taa = taa_fld.options[taa_fld.selectedIndex].value;
	var taj_fld = document.forms["qualify_form"+form_id].taj;
	var taj = taj_fld.options[taj_fld.selectedIndex].value;
	var cd_trade_fld = document.forms["qualify_form"+form_id].have_trade;
	var cd_trade = cd_trade_fld.options[cd_trade_fld.selectedIndex].value;
	if (document.forms["qualify_form"+form_id].finance_location) {
		var radios = document.getElementsByName("finance_location");
		var location_value = "";
		var send_to = '';
		var send_to_name = '';
		for (var i = 0; i < radios.length; i++) {
			if (radios[i].type === 'radio' && radios[i].checked) {
				send_to = radios[i].value;
				send_to_name = send_to;
			}
		}
		if (send_to == "") {
			var msg = msg + "You did not select a finance location.";
		}
	} else {
		var send_to_fld = document.forms["qualify_form"+form_id].send_to;
		var send_to = send_to_fld.options[send_to_fld.selectedIndex].value;
		var send_to_name = send_to_fld.options[send_to_fld.selectedIndex].text;
		if (send_to == "") {
			var msg = msg + "<?php _e('You did not select who you want to send this message to.', 'car-demon'); ?><br />";
			cd_not_valid("send_to", "qualify_form"+form_id);
		}
	}
	if (msg != "") {
		document.getElementById("qualify_msg"+form_id).style.display = "block";
		document.getElementById("qualify_msg"+form_id).innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		<?php echo $hook_form_js; ?>
		var action = "";
		var your_name = document.forms["qualify_form"+form_id].cd_name.value;
		var phone = document.forms["qualify_form"+form_id].cd_phone.value;
		var email = document.forms["qualify_form"+form_id].cd_email.value;
		var qualify_comment = document.forms["qualify_form"+form_id].qualify_comment.value;
		var cd_city =  document.forms["qualify_form"+form_id].cd_city.value;
		var cd_employer =  document.forms["qualify_form"+form_id].cd_employer.value;
		var cd_income =  document.forms["qualify_form"+form_id].cd_income.value;
		var cd_down =  document.forms["qualify_form"+form_id].cd_down.value;
		var form_key = document.forms["qualify_form"+form_id].form_key.value;
		jQuery.ajax({
			type: 'POST',
			data: {'your_name': your_name, 'phone':phone, 'email':email, 'send_to':send_to, 'send_to_name':send_to_name, 'qualify_comment':qualify_comment, 'cd_city':cd_city, 'taa':taa, 'cd_employer':cd_employer, 'cd_income':cd_income, 'taj':taj, 'cd_down':cd_down, 'cd_trade':cd_trade, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-qualify-handler.php?send_qualify=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("qualify_msg"+form_id).style.display = "block";
				document.getElementById("qualify_msg"+form_id).style.background = "#FFFFFF";
				document.getElementById("qualify_msg"+form_id).innerHTML = html;
				document.getElementById("qualify_form"+form_id).style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}