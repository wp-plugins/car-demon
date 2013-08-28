// JavaScript Document
function clearField(fld) {
	if (fld.value == "Your Name") {
		fld.value = "";
	}
}
function setField(fld) {
}
function car_demon_validate() {
	var msg = "";
	var name_valid = 0;
	document.forms["contact_form"].style.display = "none";
	if (contact_form.cd_name.value == "") {
		var msg = cdContactWidgetParams.error1+"<br />";
		cd_not_valid("cd_name");
	} else {
		var name_valid = 1;
	}
	if (contact_form.cd_name.value == "Your Name") {
		var msg = cdContactWidgetParams.error2+"<br />";
		cd_not_valid("cd_name");
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name");
		}
	}
	if (cdContactWidgetParams.validate_phone == 1) {
		if (contact_form.cd_phone.value == "") {
			var msg = msg + cdContactWidgetParams.error3+"<br />";
			cd_not_valid("cd_phone");
		} else {
			if (contact_form.cd_phone.value.length != 14) {
				var msg = msg + cdContactWidgetParams.error4+"<br />";
				cd_not_valid("cd_phone");			
			} else {
				cd_valid("cd_phone");
			}
		}
	}
	var e_msg = validateEmail(contact_form.cd_email);
	if (e_msg == "") {
		cd_valid("cd_email");
	} else {
		var msg = msg + e_msg + "<br />";
	}
	if (contact_form.contact_needed.value == "") {
		var msg = msg + cdContactWidgetParams.error6+"<br />";
		cd_not_valid("contact_needed");	
	} else {
		document.getElementById("contact_needed").style.background = "";
	}
	if (msg != "") {
		document.getElementById("contact_msg").style.display = "block";
		document.getElementById("contact_msg").innerHTML = msg;
		jQuery("#contact_form").fadeIn();
		javascript:scroll(0,0);
	} else {
		var action = "";
		var your_name = document.getElementById("cd_name").value;
		var phone = document.getElementById("cd_phone").value;
		var email = document.getElementById("cd_email").value;
		var send_to = document.getElementById("send_to").value;
		var contact_needed = document.getElementById("contact_needed").value;
		var vehicle_vin = document.getElementById("vehicle_vin").value;
		var vehicle_year = document.getElementById("vehicle_year").value;
		var vehicle_make = document.getElementById("vehicle_make").value;
		var vehicle_model = document.getElementById("vehicle_model").value;
		var vehicle_condition = document.getElementById("vehicle_condition").value;
		var vehicle_location = document.getElementById("vehicle_location").value;
		var vehicle_stock_number = document.getElementById("vehicle_stock_number").value;
		var vehicle_photo = document.getElementById("vehicle_photo").value;
		var send_to_name = document.getElementById("send_to_name").value;
		var car_id = document.getElementById("car_id").value;
		var cc = document.getElementById("cc").value;
		var send_receipt = document.getElementById("send_receipt").value;
		var send_receipt_msg = document.getElementById("send_receipt_msg").value;
		var sending = "<div align='center'><h3>Sending...</h3><img src='"+cdContactWidgetParams.path_url+"theme-files/images/loading.gif' /></div>"
		var nonce = document.getElementById("nonce").value;
		jQuery(cdContactWidgetParams.hook_js);
		document.getElementById("contact_form").innerHTML = sending;
		jQuery.ajax({
			type: 'POST',
			data: {action: 'cd_contact_us_widget_handler', 'nonce': nonce, 'your_name': your_name,'phone':phone, 'email':email, 'contact_needed':contact_needed, 'send_to':send_to, 'send_to_name':send_to_name, 'vehicle_vin':vehicle_vin, 'vehicle_year':vehicle_year, 'vehicle_make':vehicle_make, 'vehicle_model':vehicle_model, 'vehicle_condition':vehicle_condition, 'vehicle_location':vehicle_location, 'vehicle_stock_number':vehicle_stock_number, 'vehicle_photo':vehicle_photo, 'car_id':car_id, 'cc':cc, 'send_receipt':send_receipt, 'send_receipt_msg': send_receipt_msg},
			url: cdContactWidgetParams.ajaxurl,
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("contact_msg").style.display = "block";
				document.getElementById("contact_msg").style.background = "#FFFFFF";
				document.getElementById("contact_msg").innerHTML = html;
				document.getElementById("contact_form").style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}
function cd_get_radios(radios) {
	var my_val = "";
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].type === "radio" && radios[i].checked) {
			// get value, set checked flag or do whatever you need to
			my_val = radios[i].value;       
		}
	}
	return my_val;
}
function cd_not_valid(fld) {
	document.getElementById(fld).style.fontweight = "bold";
	document.getElementById(fld).style.background = "Yellow";
}
function cd_valid(fld) {
	document.getElementById(fld).style.fontweight = "normal";
	document.getElementById(fld).style.background = "#ffffff";
}
function trim(s) {
  return s.replace(/^\s+|\s+$/, '');
} 
function validateEmail(fld) {
	var error="";
	var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
	var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
	var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
	
	if (fld.value == "") {
		fld.style.background = 'Yellow';
		error = "You didn't enter an email address.\n";
	} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
		fld.style.background = 'Yellow';
		error = "Please enter a valid email address.\n";
	} else if (fld.value.match(illegalChars)) {
		fld.style.background = 'Yellow';
		error = "The email address contains illegal characters.\n";
	} else {
		fld.style.background = 'White';
	}
	return error;
}
var zChar = new Array(' ', '(', ')', '-', '.');
var maxphonelength = 14;
var phonevalue1;
var phonevalue2;
var cursorposition;
function ParseForNumber1(object){
  phonevalue1 = ParseChar(object.value, zChar);
}
function ParseForNumber2(object){
  phonevalue2 = ParseChar(object.value, zChar);
}
function backspacerUP(object,e) { 
  if(e){ 
	e = e 
  } else {
	e = window.event 
  } 
  if(e.which){ 
	var keycode = e.which 
  } else {
	var keycode = e.keyCode 
  }
  ParseForNumber1(object)
  if(keycode >= 48){
	ValidatePhone(object)
  }
}
function backspacerDOWN(object,e) { 
  if(e){ 
	e = e 
  } else {
	e = window.event 
  } 
  if(e.which){ 
	var keycode = e.which 
  } else {
	var keycode = e.keyCode 
  }
  ParseForNumber2(object)
} 
function GetCursorPosition(){
  var t1 = phonevalue1;
  var t2 = phonevalue2;
  var bool = false
  for (i=0; i<t1.length; i++) {
	if (t1.substring(i,1) != t2.substring(i,1)) {
	  if(!bool) {
		cursorposition=i
		window.status=cursorposition
		bool=true
	  }
	}
  }
}		
function ValidatePhone(object){
  var p = phonevalue1
  p = p.replace(/[^\d]*/gi,"")
  if (p.length < 3) {
	object.value=p
  } else if(p.length==3){
	pp=p;
	d4=p.indexOf('(')
	d5=p.indexOf(')')
	if(d4==-1){
	  pp="("+pp;
	}
	if(d5==-1){
	  pp=pp+")";
	}
	object.value = pp;
  } else if(p.length>3 && p.length < 7){
	p ="(" + p; 
	l30=p.length;
	p30=p.substring(0,4);
	p30=p30+") " 
	p31=p.substring(4,l30);
	pp=p30+p31;
	object.value = pp; 
  } else if(p.length >= 7){
	p ="(" + p; 
	l30=p.length;
	p30=p.substring(0,4);
	p30=p30+") " 
	p31=p.substring(4,l30);
	pp=p30+p31;
	l40 = pp.length;
	p40 = pp.substring(0,9);
	p40 = p40 + "-"
	p41 = pp.substring(9,l40);
	ppp = p40 + p41;
	object.value = ppp.substring(0, maxphonelength);
  }
  GetCursorPosition()
  if(cursorposition >= 0){
	if (cursorposition == 0) {
	  cursorposition = 2
	} else if (cursorposition <= 2) {
	  cursorposition = cursorposition + 1
	} else if (cursorposition <= 4) {
	  cursorposition = cursorposition + 3
	} else if (cursorposition == 5) {
	  cursorposition = cursorposition + 3
	} else if (cursorposition == 6) { 
	  cursorposition = cursorposition + 3 
	} else if (cursorposition == 7) { 
	  cursorposition = cursorposition + 4 
	} else if (cursorposition == 8) { 
	  cursorposition = cursorposition + 4
	  e1=object.value.indexOf(')')
	  e2=object.value.indexOf('-')
	  if (e1>-1 && e2>-1){
		if (e2-e1 == 4) {
		  cursorposition = cursorposition - 1
		}
	  }
	} else if (cursorposition == 9) {
	  cursorposition = cursorposition + 4
	} else if (cursorposition < 11) {
	  cursorposition = cursorposition + 3
	} else if (cursorposition == 11) {
	  cursorposition = cursorposition + 1
	} else if (cursorposition == 12) {
	  cursorposition = cursorposition + 1
	} else if (cursorposition >= 13) {
	  cursorposition = cursorposition
	}
	var txtRange = object.createTextRange();
	txtRange.moveStart( "character", cursorposition);
	txtRange.moveEnd( "character", cursorposition - object.value.length);
	txtRange.select();
  }		
}
function ParseChar(sStr, sChar) {
  if (sChar.length == null) {
	zChar = new Array(sChar);
  }
	else zChar = sChar;
  for (i=0; i<zChar.length; i++) {
	sNewStr = "";
	var iStart = 0;
	var iEnd = sStr.indexOf(sChar[i]);
	while (iEnd != -1) {
	  sNewStr += sStr.substring(iStart, iEnd);
	  iStart = iEnd + 1;
	  iEnd = sStr.indexOf(sChar[i], iStart);
	}
	sNewStr += sStr.substring(sStr.lastIndexOf(sChar[i]) + 1, sStr.length);		
	sStr = sNewStr;
  }
  return sNewStr;
}