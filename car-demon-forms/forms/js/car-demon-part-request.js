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
		alert(cdPartsParams.error1+"\n"+cdPartsParams.error2);
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
	document.forms["part_form"+form_id].style.display = "none";
	if (document.forms["part_form"+form_id].cd_name.value == "") {
		var msg = cdPartsParams.error2+"<br />";
		cd_not_valid("cd_name", "part_form"+form_id);
	} else {
		var name_valid = 1;
	}
	if (document.forms["part_form"+form_id].cd_name.value == "Your Name") {
		var msg = cdPartsParams.error4+"<br />";
		cd_not_valid("cd_name", "part_form"+form_id);
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name", "part_form"+form_id);
		}
	}
	if (cdPartsParams.validate_phone == 1) {
		if (document.forms["part_form"+form_id].cd_phone.value == "") {
			var msg = msg + cdPartsParams.error5+"<br />";
			cd_not_valid("cd_phone", "part_form"+form_id);
		} else {
			if (document.forms["part_form"+form_id].cd_phone.value.length != 14) {
				var msg = msg + cdPartsParams.error6+"<br />";
				cd_not_valid("cd_phone", "part_form"+form_id);			
			}
			else {
				cd_valid("cd_phone", "part_form"+form_id);
			}
		}
	}
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
		var msg = msg + cdPartsParams.error7+"<br />";
	}
	if (document.forms["part_form"+form_id].part_name_1.value == "") {
		var msg = msg + cdPartsParams.error8+"<br />";
		cd_not_valid("part_name_1", "part_form"+form_id);			
	} else {
		cd_valid("part_name_1", "part_form"+form_id);
	}
	if (msg != "") {
		document.getElementById("part_msg"+form_id).style.display = "block";
		document.getElementById("part_msg"+form_id).innerHTML = msg;
		jQuery("#part_form"+form_id).fadeIn(
			function () {
				var top = document.getElementById('part_msg'+form_id).offsetTop; //Getting Y of target element
				window.scrollTo(0, top);
			}
		);
		return;
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
		var str = "";
		var i = 0;
		var num = "";
		var nonce = document.forms["part_form"+form_id].nonce.value;
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
		jQuery(cdPartsParams.hook_js);
		jQuery.ajax({
			type: 'POST',
			data: {action: 'cd_parts_handler', 'nonce': nonce, 'your_name': your_name, 'phone':phone, 'email':email, 'part_location':part_location,'year':year, 'make':make, 'model':model, 'vin':vin, 'part_needed':part_needed, 'number_of_parts':number_of_parts, 'part_list':str},
			url: cdPartsParams.ajaxurl,
			timeout: 5000,
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