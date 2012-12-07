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
function car_demon_validate(form_id) {
	var msg = "";
	var name_valid = 0;
	if (document.forms["contact_form"+form_id].cd_name.value == "") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "contact_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["contact_form"+form_id].cd_name.value == "Your Name") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name", "contact_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "contact_form"+form_id);
		}
	}
	<?php
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
	?>
	if (document.forms["contact_form"+form_id].cd_phone.value == "") {
		var msg = msg + "<?php _e('You must enter a valid Phone Number.', 'car-demon'); ?><br />";
		cd_not_valid("cd_phone", "contact_form"+form_id);
	} else {
		if (document.forms["contact_form"+form_id].cd_phone.value.length != 14) {
			var msg = msg + "<?php _e('The phone number you entered is not valid.', 'car-demon'); ?><br />";
			cd_not_valid("cd_phone", "contact_form"+form_id);			
		}
		else {
			cd_valid("cd_phone", "contact_form"+form_id);
		}
	}
	<?php
		}
	}
	?>
	var e_msg = validateEmail(document.forms["contact_form"+form_id].cd_email);
	if (e_msg == "") {
		cd_valid("cd_email", "contact_form"+form_id);
	} else {
		var msg = msg + e_msg + "<br />";
	}
	var send_to_fld = document.forms["contact_form"+form_id].send_to;
	var send_to = send_to_fld.options[send_to_fld.selectedIndex].value;
	var send_to_name = send_to_fld.options[send_to_fld.selectedIndex].text;
	if (send_to == "") {
		var msg = msg + "<?php _e('You did not select who you want to send this message to.', 'car-demon'); ?><br />";
		cd_not_valid("send_to", "contact_form"+form_id);
	} 
	if (document.forms["contact_form"+form_id].contact_needed.value == "") {
		var msg = msg + "<?php _e('You did not enter a message to send.', 'car-demon'); ?><br />";
		cd_not_valid("contact_needed", "contact_form"+form_id);	
	} else {
		document.forms["contact_form"+form_id].contact_needed.style.background = "";
	}
	if (msg != "") {
		document.getElementById("contact_msg"+form_id).style.display = "block";
		document.getElementById("contact_msg"+form_id).innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		<?php echo $hook_form_js; ?>
		var action = "";
		var your_name = document.forms["contact_form"+form_id].cd_name.value;
		var phone = document.forms["contact_form"+form_id].cd_phone.value;
		var email = document.forms["contact_form"+form_id].cd_email.value;
		var contact_needed = document.forms["contact_form"+form_id].contact_needed.value;
		var form_key = document.forms["contact_form"+form_id].form_key.value;
		jQuery.ajax({
			type: 'POST',
			data: {'your_name': your_name, 'phone':phone, 'email':email, 'send_to':send_to, 'send_to_name':send_to_name, 'contact_needed':contact_needed, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-contact-us-handler.php?send_contact=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("contact_msg"+form_id).style.display = "block";
				document.getElementById("contact_msg"+form_id).style.background = "#FFFFFF";
				document.getElementById("contact_msg"+form_id).innerHTML = html;
				document.getElementById("contact_form"+form_id).style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}