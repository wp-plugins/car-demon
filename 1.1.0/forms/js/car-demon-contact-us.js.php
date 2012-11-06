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
	if (contact_form.cd_name.value == "") {
		var msg = "You must enter your name.<br />";
		cd_not_valid("cd_name");
	}
	else {
		var name_valid = 1;
	}
	if (contact_form.cd_name.value == "Your Name") {
		var msg = "You must enter your name.<br />";
		cd_not_valid("cd_name");
	}
	else {
		if (name_valid == 1) {
			cd_valid("cd_name");
		}
	}
	if (contact_form.cd_phone.value == "") {
		var msg = msg + "You must enter a valid Phone Number.<br />";
		cd_not_valid("cd_phone");
	}
	else {
		if (contact_form.cd_phone.value.length != 14) {
			var msg = msg + "The phone number you entered is not valid.<br />";
			cd_not_valid("cd_phone");			
		}
		else {
			cd_valid("cd_phone");
		}
	}
	var e_msg = validateEmail(contact_form.cd_email);
	if (e_msg == "") {
		cd_valid("cd_email");
	}
	else {
		var msg = msg + e_msg + "<br />";
	}
	var send_to_fld = document.getElementById("send_to");
	var send_to = send_to_fld.options[send_to_fld.selectedIndex].value;
	var send_to_name = send_to_fld.options[send_to_fld.selectedIndex].text;
	if (send_to == "") {
		var msg = msg + "You did not select who you want to send this message to.<br />";
		cd_not_valid("select_location");
	}
	else {
		document.getElementById("select_location").style.background = "";
	}

	if (contact_form.contact_needed.value == "") {
		var msg = msg + "You did not enter a message to send.<br />";
		cd_not_valid("contact_needed");	
	}
	else {
		document.getElementById("contact_needed").style.background = "";
	}
	if (msg != "") {
		document.getElementById("contact_msg").style.display = "block";
		document.getElementById("contact_msg").innerHTML = msg;
		javascript:scroll(0,0);
	}
	else {
		<?php echo $hook_form_js; ?>
		var action = "";
		var your_name = document.getElementById("cd_name").value;
		var phone = document.getElementById("cd_phone").value;
		var email = document.getElementById("cd_email").value;
		var contact_needed = document.getElementById("contact_needed").value;
		var form_key = document.getElementById("form_key").value;
		jQuery.ajax({
			type: 'POST',
			data: {'your_name': your_name, 'phone':phone, 'email':email, 'send_to':send_to, 'send_to_name':send_to_name, 'contact_needed':contact_needed, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-contact-us-handler.php?send_contact=1",
			timeout: 2000,
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
		fld.style.background = "Yellow";
		error = "You didnt enter an email address.\n";
	} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
		fld.style.background = "Yellow";
		error = "Please enter a valid email address.\n";
	} else if (fld.value.match(illegalChars)) {
		fld.style.background = "Yellow";
		error = "The email address contains illegal characters.\n";
	} else {
		fld.style.background = "White";
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