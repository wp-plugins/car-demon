// JavaScript Document
function car_demon_validate(form_id) {
	var msg = "";
	var name_valid = 0;
	document.forms["contact_form"+form_id].style.display = "none";
	if (document.forms["contact_form"+form_id].cd_name.value == "") {
		var msg = cdContactParams.error1+"<br />";
		cd_not_valid("cd_name", "contact_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["contact_form"+form_id].cd_name.value == "Your Name") {
		var msg = cdContactParams.error2+"<br />";
		cd_not_valid("cd_name", "contact_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "contact_form"+form_id);
		}
	}
	if (cdContactParams.validate_phone == 1) {
		if (document.forms["contact_form"+form_id].cd_phone.value == "") {
			var msg = msg + cdContactParams.error3 +"<br />";
			cd_not_valid("cd_phone", "contact_form"+form_id);
		} else {
			if (document.forms["contact_form"+form_id].cd_phone.value.length != 14) {
				var msg = msg + cdContactParams.error4+"<br />";
				cd_not_valid("cd_phone", "contact_form"+form_id);			
			} else {
				cd_valid("cd_phone", "contact_form"+form_id);
			}
		}
	}
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
		var msg = msg + cdContactParams.error5+"<br />";
		cd_not_valid("send_to", "contact_form"+form_id);
	} 
	if (document.forms["contact_form"+form_id].contact_needed.value == "") {
		var msg = msg + cdContactParams.error6+"<br />";
		cd_not_valid("contact_needed", "contact_form"+form_id);	
	} else {
		document.forms["contact_form"+form_id].contact_needed.style.background = "";
	}
	if (msg != "") {
		document.getElementById("contact_msg"+form_id).style.display = "block";
		document.getElementById("contact_msg"+form_id).innerHTML = msg;
		jQuery("#contact_form"+form_id).fadeIn(
			function () {
				var top = document.getElementById('contact_msg'+form_id).offsetTop; //Getting Y of target element
				window.scrollTo(0, top);
			}
		);
		return;
	} else {
		jQuery(cdContactParams.hook_js);
		var action = "";
		var your_name = document.forms["contact_form"+form_id].cd_name.value;
		var phone = document.forms["contact_form"+form_id].cd_phone.value;
		var email = document.forms["contact_form"+form_id].cd_email.value;
		var contact_needed = document.forms["contact_form"+form_id].contact_needed.value;
		var form_data = cdContactParams.form_data;
		var nonce = document.forms["contact_form"+form_id].nonce.value;
		jQuery.ajax({
			type: 'POST',
			data: {action: 'cd_contact_us_handler', 'nonce': nonce, 'your_name': your_name, 'phone':phone, 'email':email, 'send_to':send_to, 'send_to_name':send_to_name, 'contact_needed':contact_needed},
			url: cdContactParams.ajaxurl,
			timeout: 5000,
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