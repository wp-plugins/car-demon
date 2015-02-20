// JavaScript Document
function car_demon_validate_service_form(form_id) {
	var msg = "";
	var name_valid = 0;
	document.forms["service_form"+form_id].style.display = "none";
	if (document.forms["service_form"+form_id].cd_name.value == "") {
		var msg = cdServiceParams.error1+"<br />";
		cd_not_valid("cd_name", "service_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["service_form"+form_id].cd_name.value == "Your Name") {
		var msg = cdServiceParams.error2+"<br />";
		cd_not_valid("cd_name", "service_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "service_form"+form_id);
		}
	}
	if (cdServiceParams.validate_phone == 1) {
		if (document.forms["service_form"+form_id].cd_phone.value == "") {
			var msg = msg + cdServiceParams.error3+"<br />";
			cd_not_valid("cd_phone", "service_form"+form_id);
		} else {
			if (document.forms["service_form"+form_id].cd_phone.value.length != 14) {
				var msg = msg + cdServiceParams.error4+"<br />";
				cd_not_valid("cd_phone", "service_form"+form_id);			
			}
			else {
				cd_valid("cd_phone", "service_form"+form_id);
			}
		}
	}
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
		var msg = msg + cdServiceParams.error5+"<br />";
	}
	if (document.forms["service_form"+form_id].preferred_date.value == "") {
		var msg = msg + cdServiceParams.error6+"<br />";
		cd_not_valid("preferred_date", "service_form"+form_id);			
	} else {
		cd_valid("preferred_date", "service_form"+form_id);
	}
	if (document.forms["service_form"+form_id].alt_date.value == "") {
		var msg = msg + cdServiceParams.error7+"<br />";
		cd_not_valid("alt_date", "service_form"+form_id);			
	} else {
		cd_valid("alt_date", "service_form"+form_id);
	}
	if (document.forms["service_form"+form_id].service_needed.value == "") {
		var msg = msg + cdServiceParams.error8+"<br />";
		cd_not_valid("service_needed", "service_form"+form_id);			
	} else {
		cd_valid("service_needed", "service_form"+form_id);
	}
	if (msg != "") {
		document.getElementById("service_msg"+form_id).style.display = "block";
		document.getElementById("service_msg"+form_id).innerHTML = msg;
		jQuery("#service_form"+form_id).fadeIn(
			function () {
				var top = document.getElementById('service_msg'+form_id).offsetTop; //Getting Y of target element
				window.scrollTo(0, top);
			}
		);
		return;
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
		var nonce = document.forms["service_form"+form_id].nonce.value;
		jQuery(cdServiceParams.hook_js);
		jQuery.ajax({
			type: 'POST',
			data: {action: 'cd_service_handler', 'nonce': nonce, 'your_name': your_name,'phone':phone, 'email':email, 'service_location':service_location,'pref_date':pref_date, 'alt_date':alt_date, 'waiting':waiting, 'transportation':transportation, 'year':year, 'make':make, 'model':model, 'miles':miles, 'vin':vin, 'service_needed':service_needed},
			url: cdServiceParams.ajaxurl,
			timeout: 5000,
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