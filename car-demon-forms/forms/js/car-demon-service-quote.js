// JavaScript Document
function car_demon_validate_service_quote(form_id) {
	var msg = "";
	var name_valid = 0;
	document.forms["service_quote"+form_id].style.display = "none";
	if (document.forms["service_quote"+form_id].cd_name.value == "") {
		var msg = cdServiceQuoteParams.error1+"<br />";
		cd_not_valid("cd_name", "service_quote"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["service_quote"+form_id].cd_name.value == "Your Name") {
		var msg = cdServiceQuoteParams.error2+"<br />";
		cd_not_valid("cd_name", "service_quote"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "service_quote"+form_id);
		}
	}
	if (cdServiceQuoteParams.validate_phone == 1) {
		if (document.forms["service_quote"+form_id].cd_phone.value == "") {
			var msg = msg + cdServiceQuoteParams.error3+"<br />";
			cd_not_valid("cd_phone", "service_quote"+form_id);
		} else {
			if (document.forms["service_quote"+form_id].cd_phone.value.length != 14) {
				var msg = msg + cdServiceQuoteParams.error4+"<br />";
				cd_not_valid("cd_phone", "service_quote"+form_id);			
			}
			else {
				cd_valid("cd_phone", "service_quote"+form_id);
			}
		}
	}
	var e_msg = validateEmail(document.forms["service_quote"+form_id].cd_email);
	if (e_msg == "") {
		cd_valid("cd_email", "service_quote"+form_id);
	} else {
		var msg = msg + e_msg + "<br />";
	}			
	var radios = document.forms["service_quote"+form_id].service_location;
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
		var msg = msg + cdServiceQuoteParams.error5+"<br />";
	}
	if (document.forms["service_quote"+form_id].service_needed.value == "") {
		var msg = msg + cdServiceQuoteParams.error6+"<br />";
		cd_not_valid("service_needed", "service_quote"+form_id);			
	} else {
		cd_valid("service_needed", "service_quote"+form_id);
	}
	if (msg != "") {
		document.getElementById("service_msg"+form_id).style.display = "block";
		document.getElementById("service_msg"+form_id).innerHTML = msg;
		jQuery("#service_quote"+form_id).fadeIn(
			function () {
				var top = document.getElementById('service_msg'+form_id).offsetTop; //Getting Y of target element
				window.scrollTo(0, top);
			}
		);
		return;
	} else {
		var action = "";
		var your_name = document.forms["service_quote"+form_id].cd_name.value;
		var phone = document.forms["service_quote"+form_id].cd_phone.value;
		var email = document.forms["service_quote"+form_id].cd_email.value;
		var radios = document.forms["service_quote"+form_id].service_location;
		var service_location = location_value;
		var year = document.forms["service_quote"+form_id].year.value;
		var make = document.forms["service_quote"+form_id].make.value;
		var model = document.forms["service_quote"+form_id].model.value;
		var miles = document.forms["service_quote"+form_id].miles.value;
		var vin = document.forms["service_quote"+form_id].vin.value;
		var service_needed = document.forms["service_quote"+form_id].service_needed.value;
		var nonce = document.forms["service_quote"+form_id].nonce.value;
		jQuery(cdServiceQuoteParams.hook_js);
		jQuery.ajax({
			type: 'POST',
			data: {action: 'cd_service_quote_handler', 'nonce': nonce, 'your_name': your_name,'phone':phone, 'email':email, 'service_location':service_location, 'year':year, 'make':make, 'model':model, 'miles':miles, 'vin':vin, 'service_needed':service_needed},
			url: cdServiceQuoteParams.ajaxurl,
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("service_msg"+form_id).style.display = "block";
				document.getElementById("service_msg"+form_id).style.background = "#FFFFFF";
				document.getElementById("service_msg"+form_id).innerHTML = html;
				document.getElementById("service_quote"+form_id).style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}