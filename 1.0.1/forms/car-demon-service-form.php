<?php
function car_demon_service_form() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	$x = '';
	$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'service_appointment', 'unk');
	$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'service_appointment', 'unk');
	if (isset($_GET['service_needed'])) {
		$service_needed = $_GET['service_needed'];
	}
	else {
		$service_needed = '';
	}
	$x = '
	<link href="'.$car_demon_pluginpath.'theme-files/css/CalendarControl.css" rel="stylesheet" type="text/css">
	<script src="'.$car_demon_pluginpath.'theme-files/js/CalendarPopup.js"></script>
	<script>
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
			if (service_form.cd_name.value == "") {
				var msg = "You must enter your name.<br />";
				cd_not_valid("cd_name");
			}
			else {
				var name_valid = 1;
			}
			if (service_form.cd_name.value == "Your Name") {
				var msg = "You must enter your name.<br />";
				cd_not_valid("cd_name");
			}
			else {
				if (name_valid == 1) {
					cd_valid("cd_name");
				}
			}
			if (service_form.cd_phone.value == "") {
				var msg = msg + "You must enter a valid Phone Number.<br />";
				cd_not_valid("cd_phone");
			}
			else {
				if (service_form.cd_phone.value.length != 14) {
					var msg = msg + "The phone number you entered is not valid.<br />";
					cd_not_valid("cd_phone");			
				}
				else {
					cd_valid("cd_phone");
				}
			}
			var e_msg = validateEmail(service_form.cd_email);
			if (e_msg == "") {
				cd_valid("cd_email");
			}
			else {
				var msg = msg + e_msg + "<br />";
			}			
			var radios = document.getElementsByName("service_location");
			var location_value = "";
			for (var i = 0; i < radios.length; i++) {
				if (radios[i].type === \'radio\' && radios[i].checked) {
					location_value = radios[i].value;
				}
			}
			if (location_value == "") {
				var msg = msg + "You did not select a service location.<br />";
				cd_not_valid("select_location");
			}
			else {
				document.getElementById("select_location").style.background = "";
			}
			if (service_form.preferred_date.value == "") {
				var msg = msg + "You did not select a preferred appointment date.<br />";
				cd_not_valid("preferred_date");			
			}
			else {
				cd_valid("preferred_date");
			}
			if (service_form.alt_date.value == "") {
				var msg = msg + "You did not select an alternate appointment date.<br />";
				cd_not_valid("alt_date");			
			}
			else {
				cd_valid("alt_date");
			}
			if (service_form.service_needed.value == "") {
				var msg = msg + "You did not tell us what kind of service you need.<br />";
				cd_not_valid("service_needed");			
			}
			else {
				cd_valid("service_needed");
			}
			if (msg != "") {
				document.getElementById("service_msg").style.display = "block";
				document.getElementById("service_msg").innerHTML = msg;
				javascript:scroll(0,0);
			}
			else {
				var action = "";
				var your_name = document.getElementById("cd_name").value;
				var phone = document.getElementById("cd_phone").value;
				var email = document.getElementById("cd_email").value;
				var radios = document.getElementsByName("service_location");
				var service_location = cd_get_radios(radios);
				var pref_date = document.getElementById("preferred_date").value;
				var alt_date = document.getElementById("alt_date").value;
				var radios = document.getElementsByName("waiting");
				var waiting = cd_get_radios(radios);
				var radios = document.getElementsByName("transportation");
				var transportation = cd_get_radios(radios);
				var year = document.getElementById("year").value;
				var make = document.getElementById("make").value;
				var model = document.getElementById("model").value;
				var miles = document.getElementById("miles").value;
				var vin = document.getElementById("vin").value;
				var service_needed = document.getElementById("service_needed").value;
				var form_key = document.getElementById("form_key").value;
				'.$hook_form_js.'
				jQuery.ajax({
					type: \'POST\',
					data: {\'your_name\': your_name,\'phone\':phone, \'email\':email, \'service_location\':service_location,\'pref_date\':pref_date, \'alt_date\':alt_date, \'waiting\':waiting, \'transportation\':transportation, \'year\':year, \'make\':make, \'model\':model, \'miles\':miles, \'vin\':vin, \'service_needed\':service_needed, \'form_key\':form_key'.$hook_form_js_data.'},
					url: "'.$car_demon_pluginpath.'forms/car-demon-service-handler.php?send_service=1",
					timeout: 2000,
					error: function() {},
					dataType: "html",
					success: function(html){
						document.getElementById("service_msg").style.display = "block";
						document.getElementById("service_msg").style.background = "#FFFFFF";
						document.getElementById("service_msg").innerHTML = html;
						document.getElementById("service_form").style.display = "none";
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
		  return s.replace(/^\s+|\s+$/, \'\');
		} 
		function validateEmail(fld) {
			var error="";
			var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
			var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
			var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
			
			if (fld.value == "") {
				fld.style.background = \'Yellow\';
				error = "You didn\'t enter an email address.\n";
			} else if (!emailFilter.test(tfld)) {              //test email for illegal characters
				fld.style.background = \'Yellow\';
				error = "Please enter a valid email address.\n";
			} else if (fld.value.match(illegalChars)) {
				fld.style.background = \'Yellow\';
				error = "The email address contains illegal characters.\n";
			} else {
				fld.style.background = \'White\';
			}
			return error;
		}
		
		var zChar = new Array(\' \', \'(\', \')\', \'-\', \'.\');
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
			d4=p.indexOf(\'(\')
			d5=p.indexOf(\')\')
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
			  e1=object.value.indexOf(\')\')
			  e2=object.value.indexOf(\'-\')
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
		
	</script>
	<style>
	.service_msg {
		display:none;
		background: #f1cadf;
		margin:10px;
		padding:5px;
		font-weight:bold;
	}
	.cdform {
		margin: 10px auto 0 auto;
		width: 460px;
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.cdform fieldset {
		margin-top: 10px;
		padding: 5px 0 15px 0;
		border: 1px solid #ADADAD;
		border-left-color: #ECECEC;
		border-top-color: #ECECEC;
		background: #F7F7F7;
	}
	.cdform legend {
		margin-left: 10px;
		padding: 0 2px;
		font: normal 20px Times;
		color: 
		#666;
	}
	.cdform label {
		width: 140px;
		margin: 4px 10px 0 0;
		display: -moz-inline-box;
		display: inline-block;
		text-align: right;
		vertical-align: top;
	}
	.cd-box-title {
		margin-left:5px!important;
	}
	.cdlabel_right {
		width: 190px;
		margin: 4px 10px 0 0;
		display: -moz-inline-box;
		display: inline-block;
		text-align: left;
		vertical-align: top;
	}
	.cdform textarea, .cdform input {
		width: 150px;
	}
	.search_btn {
		width: 175px !important;
	}
	.cd_date {
		font-size:11px;
	}
	span.reqtxt, span.emailreqtxt {
		margin: 3px 0 0 3px;
		font-size: 0.9em;
		display: -moz-inline-box;
		vertical-align: top;
		color:#ff0000;
	}
	ol.cd-ol {
		margin: 0!important;
		padding: 0!important;
	}
	ol.cd-ol li {
		background: none;
		margin: 5px 0;
		padding: 0;
		list-style: none!important;
		text-align: left;
		line-height: 1.3em;
	}
	</style>
	<div id="service_msg" class="service_msg"></div>
	<form enctype="multipart/form-data" action="?send_service=1" method="post" class="cdform service-appointment " id="service_form">
			'.$cd_formKey->outputKey().'
			<fieldset class="cd-fs1">
			<legend>Schedule Service Appointment</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>Your Name</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="Your Name" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">(required)</span></li>
				<li id="li" class=""><label for="cd_field_"><span>Phone #</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"><span class="reqtxt">(required)</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>Email</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">(valid email required)</span></li>
			</ol>
			</fieldset>
	';
	$x .= service_locations_radio();
	$x .='
			<fieldset class="cd-fs">
			<legend>Appointment Information</legend>
			<ol class="cd-ol">
				<li id="li-9" class=""><label for="cd_field_9"><span>Preferred Appointment Date</span></label>
					<input type="text" name="preferred_date" id="preferred_date" class="cd_date" value="" onfocus="showCalendarControl(this);" />
					<a href="#" name="anchor1xx" id="anchor1xx"></a>
				<span class="reqtxt">(required)</span></li>
				<li id="li-10" class=""><label for="cd_field_10"><span>Alternate Appointment Date</span></label>
					<input type="text" name="alt_date" id="alt_date" class="cd_date" value="" onfocus="showCalendarControl(this);" />
					<a href="#" name="anchor2xx" id="anchor2xx"></a>				
				<span class="reqtxt">(required)</span></li>
				<li id="li-11" class="cd-box-title">Will you be...</li>
				<li id="li-11items" class="cd-box-group">
					<input type="radio" id="waiting1" name="waiting" value="Waiting" checked="checked" class="cd-box-b fldrequired"><span for="cd_field_11-2" class="cdlabel_right"><span>Waiting</span></span>
					<br>
					<input type="radio" id="waiting2" name="waiting" value="Leaving Car" class="cd-box-b fldrequired"><span for="cd_field_11-1" class="cdlabel_right"><span>Leaving Car</span></span>
					<br>
				</li>
				<li id="li-12" class="cd-box-title">Do you need alternate transportation?</li>
				<li id="li-12items" class="cd-box-group">
					<input type="radio" id="transportation1" name="transportation" value="Yes" class="cd-box-b fldrequired"><span for="cd_field_12-1" class="cdlabel_right"><span>Yes</span></span>
					<br>
					<input type="radio" id="transportation2" name="transportation" value="No" checked="checked" class="cd-box-b fldrequired"><span for="cd_field_12-2" class="cdlabel_right"><span>No</span></span>
					<br>
				</li>
			</ol>
			</fieldset>
			<fieldset class="cd-fs4">
			<legend>Vehicle Information</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>Year</span></label><input type="text" name="year" id="year" class="single" value=""></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>Manufacturer</span></label><input type="text" name="make" id="make" class="single" value=""></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>Model</span></label><input type="text" name="model" id="model" class="single" value=""></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>Miles</span></label><input type="text" name="miles" id="miles" class="single" value=""></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>Vin</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>Service Required</span></label><textarea cols="30" rows="4" name="service_needed" id="service_needed" class="area fldrequired">'.$service_needed.'</textarea><span class="reqtxt">(required)</span></li>
			</ol>
			</fieldset>';
			$x = apply_filters('car_demon_mail_hook_form', $x, 'service_appointment', 'unk');
			$x .= '<p class="cd-sb"><input type="button" style="float:right;" name="search_btn" id="sendbutton" class="search_btn" value="Send Appointment" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}

function service_locations_radio() {
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'taxonomy'           => 'vehicle_location'
		);
	$locations = get_categories( $args );
	$cnt = 0;
	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$cnt = $cnt + 1;
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$location_name_list = 'Default'.$location_name_list;
		$cnt = 1;
	}
	else {
		$location_list = '@'.$location_list;
		$location_list = str_replace("@,","", $location_list);
		$location_list = str_replace("@","", $location_list);
		$location_name_list = '@'.$location_name_list;
		$location_name_list = str_replace("@,","", $location_name_list);
		$location_name_list = str_replace("@","", $location_name_list);
	}
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;
	$html = '
		<fieldset class="cd-fs2">
		<legend>Service Location</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">Select your preferred Service Location</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_service = " checked='checked'";
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_service.' id="service_location_'.$x.'" name="service_location" value="'.get_option($current_location.'_service_name').'" class="cd-box-b fldrequired"><span for="service_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_service_name').'</span></span>
			<br>
		';
	}
	$html .= '
			</li>
		</ol>
		</fieldset>
	';
	return $html;
}
?>