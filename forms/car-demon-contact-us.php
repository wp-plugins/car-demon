<?php
function car_demon_contact_request() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	wp_enqueue_script('car-demon-contact-us-js', WP_CONTENT_URL . '/plugins/car-demon/forms/js/car-demon-contact-us.js.php');
	wp_enqueue_style('car-demon-contact-us-css', WP_CONTENT_URL . '/plugins/car-demon/forms/css/car-demon-contact-us.css');
	$x = '
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