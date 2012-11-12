<?php
function car_demon_service_form() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	wp_enqueue_script('car-demon-service-form-js', WP_CONTENT_URL . '/plugins/car-demon/forms/js/car-demon-service-form.js.php');
	wp_enqueue_style('car-demon-service-form-css', WP_CONTENT_URL . '/plugins/car-demon/forms/css/car-demon-service-form.css');
	wp_enqueue_script('car-demon-service-calendar-js', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/CalendarPopup.js');
	wp_enqueue_style('car-demon-service-calendar-css', WP_CONTENT_URL . '/plugins/car-demon/theme-files/css/CalendarControl.css');
	if (isset($_GET['service_needed'])) {
		$service_needed = $_GET['service_needed'];
	} else {
		$service_needed = '';
	}
	$x = '
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
			$x .= '<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn service_btn" value="Send Appointment" onclick="return car_demon_validate()"></p></form>
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
	} else {
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