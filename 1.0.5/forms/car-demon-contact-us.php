<?php
function car_demon_contact_request() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	$x = '';
	$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'contact_us', 'unk');
	$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'contact_us', 'unk');
	$x = '
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
				'.$hook_form_js.'
				var action = "";
				var your_name = document.getElementById("cd_name").value;
				var phone = document.getElementById("cd_phone").value;
				var email = document.getElementById("cd_email").value;
				var contact_needed = document.getElementById("contact_needed").value;
				var form_key = document.getElementById("form_key").value;
				jQuery.ajax({
					type: \'POST\',
					data: {\'your_name\': your_name,\'phone\':phone, \'email\':email, \'send_to\':send_to, \'send_to_name\':send_to_name, \'contact_needed\':contact_needed, \'form_key\':form_key'.$hook_form_js_data.'},
					url: "'.$car_demon_pluginpath.'theme-files/forms/car-demon-contact-us-handler.php?send_contact=1",
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
	.remove_contact {
		cursor: pointer;
	}
	.hide_contacts {
		display:none;
	}
	.remove_contact_btn {
		cursor: pointer;
		display:none;
		margin-left:10px;
		margin-top:4px;
	}
	.add_contact_btn {
		cursor: pointer;
		margin-left:10px;
		margin-top:4px;
	}
	.contact_msg {
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
	<div id="contact_msg" class="contact_msg"></div>
	<form enctype="multicontact/form-data" action="?send_contact=1" method="post" class="cdform contact-appointment " id="contact_form">
			'.$cd_formKey->outputKey().'
			<fieldset class="cd-fs1">
			<legend>Your Information</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>Your Name</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="Your Name" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">(required)</span></li>
				<li id="li" class=""><label for="cd_field_"><span>Phone #</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"><span class="reqtxt">(required)</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>Email</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">(valid email required)</span></li>
			</ol>
			</fieldset>
	';
	$x .= contact_locations_radio();
	$add = '<img src="'.$car_demon_pluginpath.'images/btn_add_contact.png" id="add_contact_btn" class="add_contact_btn" onclick="add_contact();" class="add_contact" title="'.__('Add Contact', 'car-demon').'" />';
	$remove = '<img src="'.$car_demon_pluginpath.'images/btn_remove_contact.png" id="remove_contact_btn" class="remove_contact_btn" onclick="remove_contact();" class="remove_contact" title="'.__('Remove Contact', 'car-demon').'" />';
	$x .='
			<fieldset class="cd-fs4">
			<legend>Questions and Comments</legend>
			<ol class="cd-ol">
				<li id="li-5" class=""><textarea style="margin-left:10px;width:425px;height:100px;" cols="60" rows="4" name="contact_needed" id="contact_needed" class="area fldrequired"></textarea><br /><span class="reqtxt" style="margin-left:10px;">(required)</span></li>
			</ol>
			</fieldset>';
	$x = apply_filters('car_demon_mail_hook_form', $x, 'contact_us', 'unk');
	$x .='
			<p class="cd-sb"><input type="button" style="float:right;" name="search_btn" id="sendbutton" class="search_btn" value="Contact Us" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}

function contact_locations_radio() {
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
		<legend>Send To</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">Select who you would like to send your message to</li>
			<li id="li-7items" class="cd-box-group">
			<select style="margin-left:10px;" id="send_to"><option></option>
	';
	if ($cnt == 1) {
		$select_contact = " checked='checked'";
	}
	if (isset($_COOKIE["sales_code"])) {
		$custom_sales_id = $_COOKIE["sales_code"];
	}
	else {
		$custom_sales_id = '';
	}
	foreach ($location_list_array as $current_location) {
		if (!empty($custom_sales_id)) {
			$used_email = car_demon_get_custom_email($custom_sales_id, 'lead_used_cars', $current_location);
			if (empty($used_email)) {
				$used_email = get_option($current_location.'_used_sales_email');
			}
		}
		else {
			$used_email = get_option($current_location.'_used_sales_email');
		}
		if ($used_email) {
			$html .= '<option value="'.$used_email.'">'.$location_name_list_array[$x].' - Used Sales</option>';
		}
		if (!empty($custom_sales_id)) {
			$new_email = car_demon_get_custom_email($custom_sales_id, 'lead_new_cars', $current_location);
			if (empty($new_email)) {
				$new_email = get_option($current_location.'_new_sales_email');
			}
		}
		else {
			$new_email = get_option($current_location.'_new_sales_email');
		}
		if ($new_email) {
			$html .= '<option value="'.$new_email.'">'.$location_name_list_array[$x].' - New Sales</option>';
		}
		if (!empty($custom_sales_id)) {
			$trade_email = car_demon_get_custom_email($custom_sales_id, 'lead_trade', $current_location);
			if (empty($trade_email)) {
				$trade_email = get_option($current_location.'_trade_email');
			}
		}
		else {
			$trade_email = get_option($current_location.'_trade_email');
		}
		if ($trade_email) {
			$html .= '<option value="'.$trade_email.'">'.$location_name_list_array[$x].' - Trade Ins</option>';
		}
		if (!empty($custom_sales_id)) {
			$finance_email = car_demon_get_custom_email($custom_sales_id, 'lead_finance', $current_location);
			if (empty($finance_email)) {
				$finance_email = get_option($current_location.'_finance_email');
			}
		}
		else {
			$finance_email = get_option($current_location.'_finance_email');
		}
		if ($finance_email) {
			$html .= '<option value="'.$finance_email.'">'.$location_name_list_array[$x].' - Finance</option>';
		}
		if (!empty($custom_sales_id)) {
			$service_email = car_demon_get_custom_email($custom_sales_id, 'lead_service', $current_location);
			if (empty($service_email)) {
				$service_email = get_option($current_location.'_service_email');
			}
		}
		else {
			$service_email = get_option($current_location.'_service_email');
		}
		if ($service_email) {
			$html .= '<option value="'.$service_email.'">'.$location_name_list_array[$x].' - Service</option>';
		}
		if (!empty($custom_sales_id)) {
			$parts_email = car_demon_get_custom_email($custom_sales_id, 'lead_parts', $current_location);
			if (empty($parts_email)) {
				$parts_email = get_option($current_location.'_parts_email');
			}
		}
		else {
			$parts_email = get_option($current_location.'_parts_email');
		}
		if ($parts_email) {
			$html .= '<option value="'.$parts_email.'">'.$location_name_list_array[$x].' - Parts</option>';
		}
		$x = $x + 1;
	}
	$html .= '
			</select>
			</li>
		</ol>
		</fieldset>
	';
	$html = str_replace('Default - ', '', $html);
	return $html;
}

function car_demon_get_custom_email($user_id, $lead_type, $current_location) {
	$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
	$location_approved = 0;
	if ($current_location == $user_location) {
		$location_approved = 1;
	}
	else {
		$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
	}
	if ($location_approved == 1) {
		$user_info = get_userdata($user_id);
		$user_email = $user_info->user_email;
		$user_sales_type = 0;
		$user_sales_type = get_the_author_meta($lead_type, $user_id);	
		if ($user_sales_type == "1") {
			$to = $user_email;
		}
	}
	return $to;
}
?>