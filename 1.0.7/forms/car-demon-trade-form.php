<?php
function car_demon_trade_form($post_id=0) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	$x = '';
	$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk');
	$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk');
	$x = '
	<script>
		function findValue(li) {
			if( li == null ) return alert("No match!");
			if( !!li.extra ) var sValue = li.extra[0];
			else var sValue = li.selectValue;
		}
		function selectItem(li) {
			findValue(li);
			var stock_num = li.selectValue;
			jQuery.ajax({
				type: \'POST\',
				data: {\'stock_num\': stock_num},
				url: "'.$car_demon_pluginpath.'theme-files/forms/car-demon-trade-form-handler.php?show_stock=1",
				timeout: 2000,
				error: function() {},
				dataType: "html",
				success: function(html){
					document.getElementById("find_voi").style.display = \'none\';
					document.getElementById("show_voi").style.display = \'block\';
					document.getElementById("show_voi").innerHTML = html;
				}
			})
		}
		function selectCarItem(li) {
			findValue(li);
			var car_title = li.selectValue;
			jQuery.ajax({
				type: \'POST\',
				data: {\'car_title\': car_title},
				url: "'.$car_demon_pluginpath.'theme-files/forms/car-demon-trade-form-handler.php?show_stock=2",
				timeout: 2000,
				error: function() {},
				dataType: "html",
				success: function(html){
					document.getElementById("find_voi").style.display = \'none\';
					document.getElementById("show_voi").style.display = \'block\';
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
				var msg = "You must enter your name.<br />";
				cd_not_valid("cd_name");
			}
			else {
				var name_valid = 1;
			}
			if (trade_form.cd_name.value == "Your Name") {
				var msg = "You must enter your name.<br />";
				cd_not_valid("cd_name");
			}
			else {
				if (name_valid == 1) {
					cd_valid("cd_name");
				}
			}
			if (trade_form.cd_phone.value == "") {
				var msg = msg + "You must enter a valid Phone Number.<br />";
				cd_not_valid("cd_phone");
			}
			else {
				if (trade_form.cd_phone.value.length != 14) {
					var msg = msg + "The phone number you entered is not valid.<br />";
					cd_not_valid("cd_phone");			
				}
				else {
					cd_valid("cd_phone");
				}
			}
			var e_msg = validateEmail(trade_form.cd_email);
			if (e_msg == "") {
				cd_valid("cd_email");
			}
			else {
				var msg = msg + e_msg + "<br />";
			}
			if (trade_form.year.value == "") {
				var msg = msg + "You must enter the year of the vehicle you wish to trade.<br />";
				cd_not_valid("year");
			}
			else {
				cd_valid("year");
			}
			if (trade_form.make.value == "") {
				var msg = msg + "You must enter the manufacturer of the vehicle you wish to trade.<br />";
				cd_not_valid("make");
			}
			else {
				cd_valid("make");
			}
			if (trade_form.model.value == "") {
				var msg = msg + "You must enter the model of the vehicle you wish to trade.<br />";
				cd_not_valid("model");
			}
			else {
				cd_valid("year");
				}
			if (trade_form.miles.value == "") {
				var msg = msg + "You must enter the miles of the vehicle you wish to trade.<br />";
				cd_not_valid("miles");
			}
			else {
				cd_valid("miles");
			}
			var no_car = 0;
			var no_location = 1;
			var selected_car = "";
			var voi_radios = document.getElementsByName("pick_voi");
			var voi_type = 1;
			for (var i = 0; i < voi_radios.length; i++) {
				if (voi_radios[i].type === \'radio\' && voi_radios[i].checked) {
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
					var msg = msg + "You indicated you were interested in purchasing a vehicle but did not select one.<br />";
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
					if (radios[i].type === \'radio\' && radios[i].checked) {
						location_value = radios[i].value;
					}
				}
				if (location_value == "") {
					var msg = msg + "You did not select a trade location.<br />";
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
			}
			else {
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
				'.$hook_form_js.'
				jQuery.ajax({
					type: \'POST\',
					data: {\'your_name\': your_name,\'phone\':phone, \'email\':email, \'trade_location\':trade_location, \'year\':year, \'make\':make, \'model\':model, \'miles\':miles, \'vin\':vin, \'comment\':comment, \'options\':options, \'selected_car\':selected_car, \'form_key\':form_key'.$hook_form_js_data.'},
					url: "'.$car_demon_pluginpath.'theme-files/forms/car-demon-trade-form-handler.php?send_trade=1",
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
			var checkboxes = document.getElementsByName(\'Options[]\');
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
	.trade_msg {
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
	ol.cf-ol li.cd-box-group {
		margin: 10px 0pt 0px !important;
		padding-left: 100px;
	}
	input.cd-box {
		margin: 2px 8px; 0 0;
		width: 14px;
		height: 22px;
		border: none!important;
		background: none!important;
	}
	label.cd-group-after {
		width: 92px;
		text-align: left;
	}
	label.cd-group-after span {
		width: 92px;
		display: block;
	}
	</style>
	<div id="trade_msg" class="trade_msg"></div>
	<form enctype="multipart/form-data" action="?send_trade=1" method="post" class="cdform trade-appointment " id="trade_form">
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
	$x .='
			<fieldset class="cd-fs4">
			<legend>Vehicle Information</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>Year</span></label><input type="text" name="year" id="year" class="single" value=""><span class="reqtxt">(required)</span></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>Manufacturer</span></label><input type="text" name="make" id="make" class="single" value=""><span class="reqtxt">(required)</span></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>Model</span></label><input type="text" name="model" id="model" class="single" value=""><span class="reqtxt">(required)</span></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>Miles</span></label><input type="text" name="miles" id="miles" class="single" value=""><span class="reqtxt">(required)</span></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>Vin</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>Comments</span></label><textarea cols="30" rows="4" name="comment" id="comment" class="area fldrequired"></textarea></li>
			</ol>
			</fieldset>';
	$x .= car_demon_trade_options();
	$x .= '
		<fieldset class="cd-fs2">
		<legend>Purchase Information</legend>
		';
		if (empty($_GET['stock_num'])) {
			$x .= select_trade_for_vehicle(0);
			$x .= '<ol class="cd-ol" id="show_voi"></o>';
		}
		else {
			$x .= select_trade_for_vehicle(1);
			$x .= get_trade_for_vehicle($_GET['stock_num']);
		}
	$x .= '</fieldset>';
// ===================================================================================
	$x .= trade_locations_radio();
	$x = apply_filters('car_demon_mail_hook_form', $x, 'trade_form', 'unk');
	$x .= '
		<p class="cd-sb"><input type="button" style="float:right;" name="search_btn" id="sendbutton" class="search_btn" value="'.__("Get Quote!").'" onclick="return car_demon_validate()"></p></form>
	';	
	return $x;
}

function get_trade_for_vehicle($stock_num) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value='".$stock_num."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
			$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
			$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
			$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
			$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
			$vehicle_body_style = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),0);
			$vehicle_photo = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
		}
	}
	$x = '
		<input type="hidden" id="purchase_stock" value="'.$stock_num.'" />
		<ol class="cd-ol" id="show_voi">
			<li id="" class="cd-box-title">Vehicle of Interest</li>
			<li id="not_voi" class="cd-box-title"><input type="checkbox" onclick="show_voi()" style="width:15px;margin-left:15px;" />&nbsp;This is <b>NOT</b> the vehicle I\'m interested in.</li>
			';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>Stock #</span></label><label style="width:250px;"><span style="width:250px;">'.$stock_num.'</span></label></li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>VIN</span></label><label style="width:250px;"><span style="width:250px;">'.$vehicle_vin.'</span></label></li>';
			$vehicle = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model .' '. $vehicle_body_style;
			$x .= '<li id="" class=""><label for="cd_field_2"><span>Vehicle</span></label><label style="width:250px;"><span style="width:250px;">'.$vehicle.'</span></label></li>';
			$x .= '<li id="" class=""><img src="'.$vehicle_photo.'" width="300" class="random_widget_image" style="margin-left:75px;" title="'.$vehicle.'" alt="'.$vehicle.'" /></li>';
			$x .= '
			</li>
		</ol>
	';
	return $x;
}

function select_trade_for_vehicle($hide=0) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('forms/','',$car_demon_pluginpath);
	if ($hide == 1) {
		$hidden = " style='display:none;'";
	}
	else {
		$hidden = '';
	}
	$x = '
		<ol class="cd-ol" id="find_voi"'.$hidden.'>
			<li id="voi_title" class="cd-box-title">What Vehicle are you Interested In Purchasing?</li>
			<li id="" class="cd-box-title"><input onclick="select_voi(\'stock\');" name="pick_voi" id="pick_voi_1" type="radio" value="1" />I know the stock#</li>
			<li id="select_stock" style="display:none;margin-left:20px;"><span>Stock #</span>&nbsp;<input class="ac_input" type="text" id="select_stock_txt" /></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_2" onclick="select_voi(\'search\');" type="radio" value="2" />I would like to search for it</li>
			<li id="select_description" style="display:none;margin-left:20px;"><span>Description</span>&nbsp;<input type="text"  id="select_car_txt" />&nbsp;(enter year, make or model)</li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_3" onclick="select_voi(\'na\');" type="radio" checked="checked" value="3" />I haven\'t made up my mind.</li>
			<li id="li-7items" class="cd-box-group">
	';
	$x .= '
	<script>
	   $("#select_stock_txt").autocomplete(
		  "'.$car_demon_pluginpath.'theme-files/forms/car-demon-trade-form-handler.php",
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
	   $("#select_car_txt").autocomplete(
		  "'.$car_demon_pluginpath.'theme-files/forms/car-demon-trade-form-handler.php",
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
	</script>';
	$x .= '
			</li>
		</ol>
	';
	return $x;
}

function trade_locations_radio() {
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
	if (empty($_GET['stock_num'])) {
		$hidden = "";	
	}
	else {
		$hidden = " style='display:none;'";
	}
	$html = '
		<fieldset class="cd-fs2" id="trade_locations"'.$hidden.'>
		<legend>Trade Location</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">Select your preferred Trade Location</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_trade = " checked='checked'";
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_trade.' id="trade_location_'.$x.'" name="trade_location" value="'.get_option($current_location.'_trade_name').'" class="cd-radio"><span for="trade_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_trade_name').'</span></span>
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

function car_demon_trade_options() {
	$x = '
	<fieldset class="cd-fs3">
		<legend>Your Trade-In Vehicle Options</legend>
		<ol class="cd-ol">
			<li id="li-7-25items" class="cd-box-group">
				<input type="checkbox" id="Options-1" name="Options[]" value="4 Wheel Drive" class="cd-box"><label for="Options-1" class="cd-group-after"><span>4 Wheel Drive</span></label>
				<input type="checkbox" id="Options-2" name="Options[]" value="ABS Brakes" class="cd-box"><label for="Options-2" class="cd-group-after"><span>ABS Brakes</span></label>
				<input type="checkbox" id="Options-3" name="Options[]" value="Air Bag" class="cd-box"><label for="Options-3" class="cd-group-after"><span>Air Bag</span></label>
				<br>
				<input type="checkbox" id="Options-4" name="Options[]" value="Air Conditioning" class="cd-box"><label for="Options-4" class="cd-group-after"><span>Air Conditioning</span></label>
				<input type="checkbox" id="Options-5" name="Options[]" value="Alloy Wheels" class="cd-box"><label for="Options-5" class="cd-group-after"><span>Alloy Wheels</span></label>
				<input type="checkbox" id="Options-6" name="Options[]" value="AM/FM Stereo" class="cd-box"><label for="Options-6" class="cd-group-after"><span>AM/FM Stereo</span></label>
				<br>
				<input type="checkbox" id="Options-7" name="Options[]" value="Anti-Theft" class="cd-box"><label for="Options-7" class="cd-group-after"><span>Anti-Theft</span></label>
				<input type="checkbox" id="Options-8" name="Options[]" value="Bed Liner" class="cd-box"><label for="Options-8" class="cd-group-after"><span>Bed Liner</span></label>
				<input type="checkbox" id="Options-9" name="Options[]" value="Bra" class="cd-box"><label for="Options-9" class="cd-group-after"><span>Bra</span></label>
				<br>
				<input type="checkbox" id="Options-10" name="Options[]" value="Cassette" class="cd-box"><label for="Options-10" class="cd-group-after"><span>Cassette</span></label>
				<input type="checkbox" id="Options-11" name="Options[]" value="Cruise Control" class="cd-box"><label for="Options-11" class="cd-group-after"><span>Cruise Control</span></label>
				<input type="checkbox" id="Options-12" name="Options[]" value="Dual Air Bags" class="cd-box"><label for="Options-12" class="cd-group-after"><span>Dual Air Bags</span></label>
				<br>
				<input type="checkbox" id="Options-13" name="Options[]" value="Dual Rear Wheels" class="cd-box"><label for="Options-13" class="cd-group-after"><span>Dual Rear Wheels</span></label>
				<input type="checkbox" id="Options-14" name="Options[]" value="DVD System" class="cd-box"><label for="Options-14" class="cd-group-after"><span>DVD System</span></label>
				<input type="checkbox" id="Options-15" name="Options[]" value="Integrated Cellular" class="cd-box"><label for="Options-15" class="cd-group-after"><span>Integrated Cellular</span></label>
				<br>
				<input type="checkbox" id="Options-16" name="Options[]" value="Leather" class="cd-box"><label for="Options-16" class="cd-group-after"><span>Leather</span></label>
				<input type="checkbox" id="Options-17" name="Options[]" value="Long Bed" class="cd-box"><label for="Options-17" class="cd-group-after"><span>Long Bed</span></label>
				<input type="checkbox" id="Options-18" name="Options[]" value="Luggage Rack" class="cd-box"><label for="Options-18" class="cd-group-after"><span>Luggage Rack</span></label>
				<br>
				<input type="checkbox" id="Options-19" name="Options[]" value="Moon Roof" class="cd-box"><label for="Options-19" class="cd-group-after"><span>Moon Roof</span></label>
				<input type="checkbox" id="Options-20" name="Options[]" value="Multi CD" class="cd-box"><label for="Options-20" class="cd-group-after"><span>Multi CD</span></label>
				<input type="checkbox" id="Options-21" name="Options[]" value="Nav System" class="cd-box"><label for="Options-21" class="cd-group-after"><span>Nav System</span></label>
				<br>
				<input type="checkbox" id="Options-22" name="Options[]" value="Power Locks" class="cd-box"><label for="Options-22" class="cd-group-after"><span>Power Locks</span></label>
				<input type="checkbox" id="Options-23" name="Options[]" value="Power Seats" class="cd-box"><label for="Options-23" class="cd-group-after"><span>Power Seats</span></label>
				<input type="checkbox" id="Options-24" name="Options[]" value="Power Windows" class="cd-box"><label for="Options-24" class="cd-group-after"><span>Power Windows</span></label>
				<br>
				<input type="checkbox" id="Options-25" name="Options[]" value="Premium Wheels" class="cd-box"><label for="Options-25" class="cd-group-after"><span>Premium Wheels</span></label>
				<input type="checkbox" id="Options-26" name="Options[]" value="Privacy Glass" class="cd-box"><label for="Options-26" class="cd-group-after"><span>Privacy Glass</span></label>
				<input type="checkbox" id="Options-27" name="Options[]" value="Rear Air/Heat" class="cd-box"><label for="Options-27" class="cd-group-after"><span>Rear Air/Heat</span></label>
				<br>
				<input type="checkbox" id="Options-28" name="Options[]" value="Running Boards" class="cd-box"><label for="Options-28" class="cd-group-after"><span>Running Boards</span></label>
				<input type="checkbox" id="Options-29" name="Options[]" value="Short Bed" class="cd-box"><label for="Options-29" class="cd-group-after"><span>Short Bed</span></label>
				<input type="checkbox" id="Options-30" name="Options[]" value="Single CD" class="cd-box"><label for="Options-30" class="cd-group-after"><span>Single CD</span></label>
				<br>
				<input type="checkbox" id="Options-31" name="Options[]" value="Sliding Rear Window" class="cd-box"><label for="Options-31" class="cd-group-after"><span>Sliding Rear Window</span></label>
				<input type="checkbox" id="Options-32" name="Options[]" value="Sun Roof" class="cd-box"><label for="Options-32" class="cd-group-after"><span>Sun Roof</span></label>
				<input type="checkbox" id="Options-33" name="Options[]" value="Third Seat" class="cd-box"><label for="Options-33" class="cd-group-after"><span>Third Seat</span></label>
				<br>
				<input type="checkbox" id="Options-34" name="Options[]" value="Tilt Wheel" class="cd-box"><label for="Options-34" class="cd-group-after"><span>Tilt Wheel</span></label>
				<input type="checkbox" id="Options-35" name="Options[]" value="Towing Package" class="cd-box"><label for="Options-35" class="cd-group-after"><span>Towing Package</span></label>
				<input type="checkbox" id="Options-36" name="Options[]" value="Video System" class="cd-box"><label for="Options-36" class="cd-group-after"><span>Video System</span></label>
				<br>
				<input type="checkbox" id="Options-37" name="Options[]" value="Wheel Covers" class="cd-box"><label for="Options-37" class="cd-group-after"><span>Wheel Covers</span></label>
			</li>
		</ol><span class="reqtxt" style="margin-left:10px;">(options not required, but help provide an accurate quote)</span>
		</fieldset>';
	return $x;
}
?>