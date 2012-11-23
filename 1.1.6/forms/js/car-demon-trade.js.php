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
$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk');
$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk');
?>
// JavaScript Document
function findValue(li) {
	if( li == null ) return alert("No match!");
	if( !!li.extra ) var sValue = li.extra[0];
	else var sValue = li.selectValue;
}
function selectItem(li) {
	findValue(li);
	var stock_num = li.selectValue;
	jQuery.ajax({
		type: 'POST',
		data: {'stock_num': stock_num},
		url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-trade-form-handler.php?show_stock=1",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("find_voi").style.display = 'none';
			document.getElementById("show_voi").style.display = 'block';
			document.getElementById("show_voi").innerHTML = html;
		}
	})
}
function selectCarItem(li) {
	findValue(li);
	var car_title = li.selectValue;
	jQuery.ajax({
		type: 'POST',
		data: {'car_title': car_title},
		url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-trade-form-handler.php?show_stock=2",
		timeout: 2000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("find_voi").style.display = 'none';
			document.getElementById("show_voi").style.display = 'block';
			document.getElementById("show_voi").innerHTML = html;
		}
	})
}
function formatItem(row) {
	return row[0] + " (" + row[1] + ")";
}
function formatCarItem(row) {
	return row[0];
}
function clearField(fld) {
	if (fld.value == "Your Name") {
		fld.value = "";
	}
}
function setField(fld) {

}
function select_voi(my_type) {
	if (my_type == "stock") {
		document.getElementById("select_description").style.display = "none";
		document.getElementById("select_stock").style.display = "block";
		document.getElementById("trade_locations").style.display = "none";
		document.getElementById("select_stock").value = "";
	}
	if (my_type == "search") {
		document.getElementById("select_stock").style.display = "none";
		document.getElementById("select_description").style.display = "block";
		document.getElementById("trade_locations").style.display = "none";
	}
	if (my_type == "na") {
		document.getElementById("select_stock").style.display = "none";
		document.getElementById("select_description").style.display = "none";
		document.getElementById("trade_locations").style.display = "block";
	}			
}
function show_voi() {
	document.getElementById("find_voi").style.display = "block";
	document.getElementById("trade_locations").style.display = "block";
	document.getElementById("not_voi").style.display = "none";
	document.getElementById("show_voi").style.display = "none";
	document.getElementById("select_description").style.display = "none";
	document.getElementById("select_stock").style.display = "none";
	document.getElementById("select_stock_txt").value = "";
	document.getElementById("select_car_txt").value = "";
	document.getElementById("purchase_stock").value = "";
	var radioObj = document.getElementById("pick_voi_3");
	setCheckedValue(radioObj, 3);
}
function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}
function car_demon_validate() {
	var msg = "";
	var name_valid = 0;
	if (trade_form.cd_name.value == "") {
		var msg = "<?php _e('You must enter your name.', 'car-demon'); ?><br />";
		cd_not_valid("cd_name");
	} else {
		var name_valid = 1;
	}
	if (trade_form.cd_name.value == "Your Name") {
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
	if (trade_form.cd_phone.value == "") {
		var msg = msg + "<?php _e('You must enter a valid Phone Number.', 'car-demon'); ?><br />";
		cd_not_valid("cd_phone");
	} else {
		if (trade_form.cd_phone.value.length != 14) {
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
	var e_msg = validateEmail(trade_form.cd_email);
	if (e_msg == "") {
		cd_valid("cd_email");
	} else {
		var msg = msg + e_msg + "<br />";
	}
	if (trade_form.year.value == "") {
		var msg = msg + "<?php _e('You must enter the year of the vehicle you wish to trade.', 'car-demon'); ?><br />";
		cd_not_valid("year");
	} else {
		cd_valid("year");
	}
	if (trade_form.make.value == "") {
		var msg = msg + "<?php _e('You must enter the manufacturer of the vehicle you wish to trade.', 'car-demon'); ?><br />";
		cd_not_valid("make");
	} else {
		cd_valid("make");
	}
	if (trade_form.model.value == "") {
		var msg = msg + "<?php _e('You must enter the model of the vehicle you wish to trade.', 'car-demon'); ?><br />";
		cd_not_valid("model");
	} else {
		cd_valid("year");
		}
	if (trade_form.miles.value == "") {
		var msg = msg + "<?php _e('You must enter the miles of the vehicle you wish to trade.', 'car-demon'); ?><br />";
		cd_not_valid("miles");
	} else {
		cd_valid("miles");
	}
	var no_car = 0;
	var no_location = 1;
	var selected_car = "";
	var voi_radios = document.getElementsByName("pick_voi");
	var voi_type = 1;
	for (var i = 0; i < voi_radios.length; i++) {
		if (voi_radios[i].type === 'radio' && voi_radios[i].checked) {
			var voi_type = voi_radios[i].value;
		}
	}
	if (voi_type == 1) { no_car = 1; no_location = 1; }
	if (voi_type == 2) { no_car = 1; no_location = 1; }
	if (voi_type == 3) { no_car = 0; no_location = 0; }
	if (no_car == 1) {
		if (document.getElementById("purchase_stock")) {
			if (document.getElementById("purchase_stock").value == "") {
				var no_car = 0;
			}
			else {
				var no_car = 1;
				var selected_car = document.getElementById("purchase_stock").value;
			}
		}
		if (selected_car == "") {
			var msg = msg + "<?php _e('You indicated you were interested in purchasing a vehicle but did not select one.', 'car-demon'); ?><br />";
			cd_not_valid("voi_title");
		}
		else {
			cd_valid("voi_title");
		}
	}
	if (document.getElementById("purchase_stock")) {
		if (document.getElementById("purchase_stock").value == "") {
			var no_car = 0;
		}
		else {
			var no_car = 1;
			var selected_car = document.getElementById("purchase_stock").value;
		}
	}
	if (no_car == 0) {
		var radios = document.getElementsByName("trade_location");
		var location_value = "";
		for (var i = 0; i < radios.length; i++) {
			if (radios[i].type === 'radio' && radios[i].checked) {
				location_value = radios[i].value;
			}
		}
		if (location_value == "") {
			var msg = msg + "<?php _e('You did not select a trade location.', 'car-demon'); ?><br />";
			cd_not_valid("select_location");
		}
		else {
			document.getElementById("select_location").style.background = "";
			cd_valid("select_location");
			if (no_location == 0) {
				cd_valid("voi_title");
			}
		}
	}
	if (msg != "") {
		document.getElementById("trade_msg").style.display = "block";
		document.getElementById("trade_msg").innerHTML = msg;
		javascript:scroll(0,0);
	} else {
		var action = "";
		var your_name = document.getElementById("cd_name").value;
		var phone = document.getElementById("cd_phone").value;
		var email = document.getElementById("cd_email").value;
		var radios = document.getElementsByName("trade_location");
		var trade_location = cd_get_radios(radios);
		var options = cd_get_options();
		var year = document.getElementById("year").value;
		var make = document.getElementById("make").value;
		var model = document.getElementById("model").value;
		var miles = document.getElementById("miles").value;
		var vin = document.getElementById("vin").value;
		var comment = document.getElementById("comment").value;
		var form_key = document.getElementById("form_key").value;
		<?php echo $hook_form_js; ?>
		jQuery.ajax({
			type: 'POST',
			data: {'your_name': your_name,'phone':phone, 'email':email, 'trade_location':trade_location, 'year':year, 'make':make, 'model':model, 'miles':miles, 'vin':vin, 'comment':comment, 'options':options, 'selected_car':selected_car, 'form_key':form_key<?php echo $hook_form_js_data; ?>},
			url: "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-trade-form-handler.php?send_trade=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
				document.getElementById("trade_msg").style.display = "block";
				document.getElementById("trade_msg").style.background = "#FFFFFF";
				document.getElementById("trade_msg").innerHTML = html;
				document.getElementById("trade_form").style.display = "none";
				javascript:scroll(0,0);
			}
		})
	}
	return false;
}
function cd_get_options() {
	var checkboxes = document.getElementsByName('Options[]');
	var vals = "";
	for (var i=0, n=checkboxes.length;i<n;i++) {
	  if (checkboxes[i].checked) vals += ","+checkboxes[i].value;
	}
	if (vals) vals = vals.substring(1); // drop leading comma
	return vals;
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
jQuery("#select_stock_txt").autocomplete(
  "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-trade-form-handler.php",
  {
		extraParams: {action:"findStock"},
		delay:10,
		minChars:2,
		matchSubset:1,
		matchContains:1,
		cacheLength:10,
		onItemSelect:selectItem,
		onFindValue:findValue,
		formatItem:formatItem,
		autoFill:true,
		width:300
	}
);
jQuery("#select_car_txt").autocomplete(
  "<?php echo $car_demon_pluginpath; ?>theme-files/forms/car-demon-trade-form-handler.php",
  {
		extraParams: {action:"findVehicle"},
		delay:10,
		minChars:2,
		matchSubset:1,
		matchContains:1,
		cacheLength:10,
		onItemSelect:selectCarItem,
		onFindValue:findValue,
		formatItem:formatCarItem,
		autoFill:false,
		width:300
	}
);