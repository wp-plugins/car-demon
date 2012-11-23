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
function clearField(fld) {
	if (fld.value == "Your Name") {
		fld.value = "";
	}
}
function setField(fld) {

}
function add_part() {
	var number_of_parts = document.getElementById("number_of_parts").value;
	var number_of_parts = parseInt(number_of_parts) + 1;
	if (number_of_parts > 1) {
		document.getElementById("remove_part_btn").style.display = "block";
	}
	if (number_of_parts < 11) {
		document.getElementById("number_of_parts").value = number_of_parts;
		document.getElementById("part_name_label_"+number_of_parts).style.display = "block"
		document.getElementById("part_number_label_"+number_of_parts).style.display = "block"
		document.getElementById("part_end_"+number_of_parts).style.display = "block"
	} else {
		alert("<?php echo __('You may only add 10 parts to your request', 'car-demon'); ?>\n<?php echo __('If you need additional parts please add them in the comment area.', 'car-demon'); ?>");
	}
	if (number_of_parts > 9) {
		document.getElementById("add_part_btn").style.display = "none";
	}
}
function remove_part() {
	var number_of_parts = document.getElementById("number_of_parts").value;
	document.getElementById("add_part_btn").style.display = "block";
	if (number_of_parts > 1) {
		document.getElementById("part_name_label_"+number_of_parts).style.display = "none"
		document.getElementById("part_number_label_"+number_of_parts).style.display = "none"
		document.getElementById("part_end_"+number_of_parts).style.display = "none"
		var number_of_parts = parseInt(number_of_parts) - 1;
		document.getElementById("number_of_parts").value = number_of_parts;
	}
	if (number_of_parts < 2) {
		document.getElementById("remove_part_btn").style.display = "none";
	}
}
function car_demon_validate() {
	var msg = "";
	var name_valid = 0;
	if (part_form.cd_name.value == "") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name");
	} else {
		var name_valid = 1;
	}
	if (part_form.cd_name.value == "Your Name") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name");
	} else {
		if (name_valid == 1) {
			cd_valid("cd_name");
		}
	}
	<?php
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
	?>
	if (part_form.cd_phone.value == "") {
		var msg = msg + "<?php _e('You must enter a valid Phone Number.', 'car-demon'); ?><br />";
		cd_not_valid("cd_phone");
	} else {
		if (part_form.cd_phone.value.length != 14) {
			var msg = msg + "<?php _e('The phone number you entered is not valid.', 'car-demon'); ?><br />";
			cd_not_valid("cd_phone");			
		}
		else {
			cd_valid("cd_phone");
		}
	}
	<?php
		}
	}
	?>
	var e_msg = validateEmail(part_form.cd_email);
	if (e_msg == "") {
		cd_valid("cd_email");
	} else {
		var msg = msg + e_msg + "<br />";
	}			
	var radios = document.getElementsByName("part_location");
	var location_value = "";
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].type === 'radio' && radios[i].checked) {
			location_value = radios[i].value;
		}
	}
	if (location_value == "") {
		var msg = msg + "<?php _e('You did not select a part location.', 'car-demon'); ?><br />";
		cd_not_valid("select_location");
	} else {
		document.getElementById("select_location").style.background = "";
	}
	if (part_form.part_name_1.value == "") {
		var msg = msg + "<?php _e('You need to add at least the name of one part you are looking for.', 'car-demon'); ?><br />";
		cd_not_valid("part_name_1");			
	} else {
		cd_valid("part_name_1");
	}
	if (msg != "") {
		document.getElementById("part_msg").style.display = "block";
		document.getElementById("part_msg").innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		var action = "";
		var your_name = document.getElementById("cd_name").value;
		var phone = document.getElementById("cd_phone").value;
		var email = document.getElementById("cd_email").value;
		var radios = document.getElementsByName("part_location");
		var part_location = cd_get_radios(radios);
		var part_needed = document.getElementById("part_needed").value;
		var number_of_parts = document.getElementById("number_of_parts").value;
		var year = document.getElementById("year").value;
		var make = document.getElementById("make").value;
		var model = document.getElementById("model").value;
		var form_key = document.getElementById("form_key").value;
		var str = "";
		var i = 0;
		var num = ""
		while (i<number_of_parts) {
			i++;
			num = i;
			num = num.toString()
			window["part_name_" + num] = document.getElementById("part_name_"+num).value
			window["part_number_" + num] = document.getElementById("part_number_"+num).value
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
			data: {'your_name': your_name,'phone':phone, 'email':email, 'part_location':part_location,'year':year, 'make':make, 'model':model, 'part_needed':part_needed, 'number_of_parts':number_of_parts, 'part_list': str, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-part-handler.php?send_part=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("part_msg").style.display = "block";
				document.getElementById("part_msg").style.background = "#FFFFFF";
				document.getElementById("part_msg").innerHTML = html;
				document.getElementById("part_form").style.display = "none";
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
		error = "<?php _e('You didn\'t enter an email address.', 'car-demon'); ?>\n";
	} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
		fld.style.background = 'Yellow';
		error = "<?php _e('Please enter a valid email address.', 'car-demon'); ?>\n";
	} else if (fld.value.match(illegalChars)) {
		fld.style.background = 'Yellow';
		error = "<?php _e('The email address contains illegal characters.', 'car-demon'); ?>\n";
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