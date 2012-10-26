<?php
function car_demon_part_request() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	$x = '';
	$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'parts', 'unk');
	$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'parts', 'unk');
	$x = '
	<script>
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
			}
			else {
				alert("'.__('You may only add 10 parts to your request.', 'car-demon').'\n'.__('If you need additional parts please add them in the comment area.', 'car-demon').'");
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
				var msg = "You must enter your name.<br />";
				cd_not_valid("cd_name");
			}
			else {
				var name_valid = 1;
			}
			if (part_form.cd_name.value == "Your Name") {
				var msg = "You must enter your name.<br />";
				cd_not_valid("cd_name");
			}
			else {
				if (name_valid == 1) {
					cd_valid("cd_name");
				}
			}
			if (part_form.cd_phone.value == "") {
				var msg = msg + "You must enter a valid Phone Number.<br />";
				cd_not_valid("cd_phone");
			}
			else {
				if (part_form.cd_phone.value.length != 14) {
					var msg = msg + "The phone number you entered is not valid.<br />";
					cd_not_valid("cd_phone");			
				}
				else {
					cd_valid("cd_phone");
				}
			}
			var e_msg = validateEmail(part_form.cd_email);
			if (e_msg == "") {
				cd_valid("cd_email");
			}
			else {
				var msg = msg + e_msg + "<br />";
			}			
			var radios = document.getElementsByName("part_location");
			var location_value = "";
			for (var i = 0; i < radios.length; i++) {
				if (radios[i].type === \'radio\' && radios[i].checked) {
					location_value = radios[i].value;
				}
			}
			if (location_value == "") {
				var msg = msg + "You did not select a part location.<br />";
				cd_not_valid("select_location");
			}
			else {
				document.getElementById("select_location").style.background = "";
			}
			if (part_form.part_name_1.value == "") {
				var msg = msg + "You need to add at least the name of one part you are looking for.<br />";
				cd_not_valid("part_name_1");			
			}
			else {
				cd_valid("part_name_1");
			}
			if (msg != "") {
				document.getElementById("part_msg").style.display = "block";
				document.getElementById("part_msg").innerHTML = msg;
				javascript:scroll(0,0);
			}
			else {
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
				'.$hook_form_js.'
				jQuery.ajax({
					type: \'POST\',
					data: {\'your_name\': your_name,\'phone\':phone, \'email\':email, \'part_location\':part_location,\'year\':year, \'make\':make, \'model\':model, \'part_needed\':part_needed, \'number_of_parts\':number_of_parts, \'part_list\': str, \'form_key\':form_key'.$hook_form_js_data.'},
					url: "'.$car_demon_pluginpath.'theme-files/forms/car-demon-part-handler.php?send_part=1",
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
	.remove_part {
		cursor: pointer;
	}
	.hide_parts {
		display:none;
	}
	.remove_part_btn {
		cursor: pointer;
		display:none;
		margin-left:10px;
		margin-top:4px;
	}
	.add_part_btn {
		cursor: pointer;
		margin-left:10px;
		margin-top:4px;
	}
	.part_msg {
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
	<div id="part_msg" class="part_msg"></div>
	<form enctype="multipart/form-data" action="?send_part=1" method="post" class="cdform part-appointment " id="part_form">
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
	$x .= part_locations_radio();
	$add = '<span id="add_part_btn" class="add_part_btn" onclick="add_part();" class="add_part" title="'.__('Add Part', 'car-demon').'">+ Add Part</span>';
	$remove = '<span id="remove_part_btn" class="remove_part_btn" onclick="remove_part();" class="remove_part" title="'.__('Remove Part', 'car-demon').'">- Remove Part</span>';
	$x .='
			<fieldset class="cd-fs4">
			<legend>Vehicle Information</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>Year</span></label><input type="text" name="year" id="year" class="single" value=""></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>Manufacturer</span></label><input type="text" name="make" id="make" class="single" value=""></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>Model</span></label><input type="text" name="model" id="model" class="single" value=""></li>
			</ol>
			</fieldset>
			<fieldset class="cd-fs4">
			<legend>Parts Needed</legend>
			<ol class="cd-ol">
				'.list_part_lines().'
				<li id="add_part" class="">
					<input type="hidden" id="number_of_parts" value="1" />'.$add.$remove.'
				</li>
				<li id="li-5" class=""><label for="cd_field_5"><span>Comments & Questions</span></label><textarea cols="30" rows="4" name="part_needed" id="part_needed" class="area fldrequired"></textarea></li>
			</ol>
			</fieldset>';
			$x = apply_filters('car_demon_mail_hook_form', $x, 'parts', 'unk');
			$x .= '<p class="cd-sb"><input type="button" style="float:right;" name="search_btn" id="sendbutton" class="search_btn" value="Request Quote" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}
function list_part_lines() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '/images', $car_demon_pluginpath);
	$start = 0;
	$stop = 10;
	$x = '';
	$remove_it = '';
	$class = 'show_parts';
	do {
		if ($start > 0) {$class = 'hide_parts';}
		$start = $start + 1;
		if ($start == 1) {$require='<span class="reqtxt">(required)</span>';}
			else {$require='';}
		$x .= '<li id="part_name_label_'.$start.'" class="'.$class.'"><label for="part_name_'.$start.'"><span>'.$remove_it.'Part Name #'.$start.'</span></label><input type="text" name="part_name_'.$start.'" id="part_name_'.$start.'" class="single" value="">'.$require.'</li>';
		$x .= '<li id="part_number_label_'.$start.'" class="'.$class.'"><label for="part_number_'.$start.'"><span>Part Number #'.$start.'</span></label><input type="text" name="part_number_'.$start.'" id="part_number_'.$start.'" class="single" value=""></li>';
		$x .= '<li id="part_end_'.$start.'" class='.$class.'><hr /></li>';
	} while ($start < $stop);
	return $x;
}

function part_locations_radio() {
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
		<legend>Parts Department</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">Select your preferred Parts Department</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_part = " checked='checked'";
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_part.' id="part_location_'.$x.'" name="part_location" value="'.get_option($current_location.'_parts_name').'" class="cd-box-b fldrequired"><span for="part_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_parts_name').'</span></span>
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