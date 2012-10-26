<?php
add_action( 'widgets_init', 'car_demon_vehicle_contact_load_widgets' );

function car_demon_vehicle_contact_load_widgets() {
	register_widget( 'car_demon_vehicle_contact_Widget' );
}

class car_demon_vehicle_contact_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function car_demon_vehicle_contact_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_vehicle_contact', 'description' => __('Display contact form on Vehicle Pages.', 'car-demon') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_vehicle_contact-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'car_demon_vehicle_contact-widget', __('Car Demon Vehicle Contact', 'car-demon'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		$post_id = $GLOBALS['post']->ID;
		$post_type = get_post_type($post_id);
		if ( $post_type == 'cars_for_sale' ) {
			if (is_single($post_id)) {
				extract( $args );
				/* Our variables from the widget settings. */
				$title = apply_filters('widget_title', $instance['title'] );
				$list_phone = $instance['list_phone'];
				$cc = $instance['cc'];
				$send_receipt = $instance['send_receipt'];
				$send_receipt_msg = $instance['send_receipt_msg'];
				/* Before widget (defined by themes). */
				echo $before_widget;
				/* Display the widget title if one was input (before and after defined by themes). */
				if (!empty($title)) {
					echo $before_title . $title . $after_title;
				}
				echo '<div style="max-width:275px;">';
					echo car_demon_display_vehicle_contacts_js($post_id, $list_phone, $cc, $send_receipt, $send_receipt_msg);
					echo car_demon_display_vehicle_contacts($post_id, $list_phone, $cc, $send_receipt, $send_receipt_msg);
				echo '</div>';
				/* After widget (defined by themes). */
				echo $after_widget;
			}
		}
	}
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['list_phone'] = strip_tags( $new_instance['list_phone'] );
		$instance['cc'] = strip_tags( $new_instance['cc'] );
		$instance['send_receipt'] = strip_tags( $new_instance['send_receipt'] );
		$instance['send_receipt_msg'] = strip_tags( $new_instance['send_receipt_msg'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __('Contact Us', 'car-demon'),
			'list_phone' => __('No', 'car-demon'),
			'cc' => __('', 'car-demon'),
			'send_receipt' => __('No', 'car-demon'),
			'send_receipt_msg' => __('', 'car-demon'),
			 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon'); ?></label>
			<br /><input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />		
			<br /><label for="<?php echo $this->get_field_id( 'list_phone' ); ?>"><?php _e('List Phone:', 'car-demon'); ?></label>
			<br /><select id="<?php echo $this->get_field_id( 'list_phone' ); ?>" name="<?php echo $this->get_field_name( 'list_phone' ); ?>">
				<option value="<?php echo $instance['list_phone']; ?>"><?php echo $instance['list_phone']; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>
			<br /><label for="<?php echo $this->get_field_id( 'cc' ); ?>"><?php _e('CC:', 'car-demon'); ?></label>
			<br /><input id="<?php echo $this->get_field_id( 'cc' ); ?>" name="<?php echo $this->get_field_name( 'cc' ); ?>" value="<?php echo $instance['cc']; ?>" style="width:100%;" />
			<br /><label for="<?php echo $this->get_field_id( 'send_receipt' ); ?>"><?php _e('Send Confirmation Receipt:', 'car-demon'); ?></label>
			<br /><select id="<?php echo $this->get_field_id( 'send_receipt' ); ?>" name="<?php echo $this->get_field_name( 'send_receipt' ); ?>">
				<option value="<?php echo $instance['send_receipt']; ?>"><?php echo $instance['send_receipt']; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>
			<br /><label for="<?php echo $this->get_field_id( 'send_receipt_msg' ); ?>"><?php _e('Additional Confirmation Message:', 'car-demon'); ?></label>
			<br /><textarea rows="4" id="<?php echo $this->get_field_id( 'send_receipt_msg' ); ?>" name="<?php echo $this->get_field_name( 'send_receipt_msg' ); ?>" style="width:100%;"><?php echo $instance['send_receipt_msg']; ?></textarea>
		</p>
	<?php
	}
}

function car_demon_display_vehicle_contacts_js($post_id, $list_phone, $cc, $send_receipt, $send_receipt_msg) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/widgets', '', $car_demon_pluginpath);
	$x = '';
	$hook_form_js = apply_filters('car_demon_mail_hook_js', $x, 'contact_us_vehicle', 'unk');
	$hook_form_js_data = apply_filters('car_demon_mail_hook_js_data', $x, 'contact_us_vehicle', 'unk');
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
				var sending = "<div align=\'center\'><h3>Sending...</h3><img src=\''.$car_demon_pluginpath.'theme-files/images/loading.gif\' /></div>"
				'.$hook_form_js.'
				document.getElementById("contact_form").innerHTML = sending;
				jQuery.ajax({
					type: \'POST\',
					data: {\'your_name\': your_name,\'phone\':phone, \'email\':email, \'contact_needed\':contact_needed, \'send_to\':send_to, \'send_to_name\':send_to_name, \'vehicle_vin\':vehicle_vin, \'vehicle_year\':vehicle_year, \'vehicle_make\':vehicle_make, \'vehicle_model\':vehicle_model, \'vehicle_condition\':vehicle_condition, \'vehicle_location\':vehicle_location, \'vehicle_stock_number\':vehicle_stock_number, \'vehicle_photo\':vehicle_photo, \'car_id\':car_id, \'cc\':cc, \'send_receipt\':send_receipt, \'send_receipt_msg\': send_receipt_msg'.$hook_form_js_data.'},
					url: "'.$car_demon_pluginpath.'widgets/car-demon-vehicle-contact-widget-handler.php?send_contact=1",
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
	</script>';
	return $x;
}

function car_demon_display_vehicle_contacts($post_id, $list_phone, $cc, $send_receipt, $send_receipt_msg) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/widgets', '', $car_demon_pluginpath);
	$car_contact = get_car_contact($post_id);
	$contact_sales_name = $car_contact['sales_name'];
	$contact_sales_email = $car_contact['sales_email'];
	$contact_sales_phone = $car_contact['sales_phone'];
	$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
	$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
	$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
	$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
	$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_location_term = get_the_terms( $post_id, 'vehicle_location');
	$vehicle_location_slug = '';
	if ($vehicle_location_term) {
		foreach( $vehicle_location_term as $term ) {
			$vehicle_location_slug = $term->slug;
		}
	}
	$vehicle_stock_number = get_post_meta($post_id, "_stock_value", true);
	$phone_contact = '';
	if ($list_phone == 'Yes') {
		$phone_contact = '<h4>'. __('For more information','car-demon').'<br />'.__('Call') .' '.$contact_sales_name.' '.__('at:','car-demon').'<br />'.$contact_sales_phone.'</h4>';
	}
	
	$x = $phone_contact.'
	<div id="contact_msg" class="contact_msg"></div>
	<form enctype="multicontact/form-data" action="?send_contact=1" method="post" class="cdform contact-appointment " id="contact_form">
			<input type="hidden" name="cc" id="cc" value="'.$cc.'" />
			<input type="hidden" name="send_receipt" id="send_receipt" value="'.$send_receipt.'" />
			<input type="hidden" name="send_receipt_msg" id="send_receipt_msg" value="'.$send_receipt_msg.'" />
			<input type="hidden" name="send_to" id="send_to" value="'.$contact_sales_email.'" />
			<input type="hidden" name="send_to_name" id="send_to_name" value="'.$contact_sales_name.'" />
			<input type="hidden" name="car_id" id="car_id" value="'.$post_id.'" />
			<input type="hidden" name="vehicle_vin" id="vehicle_vin" value="'.$vehicle_vin.'" />
			<input type="hidden" name="vehicle_year" id="vehicle_year" value="'.$vehicle_year.'" />
			<input type="hidden" name="vehicle_make" id="vehicle_make" value="'.$vehicle_make.'" />
			<input type="hidden" name="vehicle_model" id="vehicle_model" value="'.$vehicle_model.'" />
			<input type="hidden" name="vehicle_condition" id="vehicle_condition" value="'.$vehicle_condition.'" />
			<input type="hidden" name="vehicle_location" id="vehicle_location" value="'.$vehicle_location.'" />
			<input type="hidden" name="vehicle_stock_number" id="vehicle_stock_number" value="'.$vehicle_stock_number.'" />
			<input type="hidden" name="vehicle_photo" id="vehicle_photo" value="'.wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) ).'" />
			<fieldset class="cd-fs1">
			<legend>Your Information</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>Your Name</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="Your Name" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">*</span></li>
				<li id="li" class=""><label for="cd_field_"><span>Phone #</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"><span class="reqtxt">*</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>Email</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
			</ol>
			</fieldset>
	';
	$x2 = '
			<fieldset class="cd-fs2">
				<legend>I have a Trade&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select>
							<option></option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
				</legend>
			</fieldset>
			<fieldset class="cd-fs2">
				<legend>I need Financing&nbsp;&nbsp;
						<select>
							<option></option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
				</legend>
			</fieldset>	
	';
	$add = '<img src="'.$car_demon_pluginpath.'images/btn_add_contact.png" id="add_contact_btn" class="add_contact_btn" onclick="add_contact();" class="add_contact" title="'.__('Add Contact','car-demon').'" />';
	$remove = '<img src="'.$car_demon_pluginpath.'images/btn_remove_contact.png" id="remove_contact_btn" class="remove_contact_btn" onclick="remove_contact();" class="remove_contact" title="'.__('Remove Contact','car-demon').'" />';
	$x .='
			<fieldset class="cd-fs4">
			<legend>Questions or Comments</legend>
			<ol class="cd-ol">
				<li id="li-5" class=""><textarea style="margin-left:3%;width:90%;height:70px;" name="contact_needed" id="contact_needed" class="area fldrequired"></textarea><br /><span class="reqtxt" style="margin-left:10px;"><br />* required</span></li>
			</ol>
			</fieldset>';
	$x = apply_filters('car_demon_mail_hook_form', $x, 'contact_us_vehicle', $vehicle_location_slug);
	$x .= '<p class="cd-sb"><input type="button" style="margin-left:100px;" name="search_btn" id="sendbutton" class="search_btn" value="Send Now!" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}
?>