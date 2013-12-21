<?php
function car_demon_service_form($location, $popup_id = '', $popup_button='') {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/car-demon-forms/forms', '', $car_demon_pluginpath);
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-service-form-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-form.css');
		}
	} else {
		wp_enqueue_style('car-demon-service-form-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-form.css');
	}
	wp_enqueue_style('car-demon-service-calendar-css', WP_CONTENT_URL . '/plugins/car-demon/theme-files/css/CalendarControl.css');
	if (isset($_GET['service_needed'])) {
		$service_needed = $_GET['service_needed'];
	} else {
		$service_needed = '';
	}
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
			$validate_phone = ' onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"';
		} else {
			$validate_phone = '';
		}
	} else {
		$validate_phone = '';
	}
	$x = '';
	if (!empty($popup_id)) {
		wp_enqueue_script('car-demon-jquery-lightbox', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/jquery.lightbox_me.js', array('jquery'));
		wp_enqueue_script('car-demon-service-request-popup-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-service-request-popup.js');
		if (isset($_SESSION['car_demon_options']['use_form_css'])) {
			if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
				wp_enqueue_style('car-demon-service-popup-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-request-popup.css');
			}
		} else {
			wp_enqueue_style('car-demon-service-popup-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-request-popup.css');
		}
		$x .= '<div class="service_form_container" id="service_form_container_'.$popup_id.'">';
			$x .= '<div class="close_form" onclick="close_service_popup(\''.$popup_id.'\');"><img src="'.$car_demon_pluginpath.'theme-files/images/close.png" /></div>';
	}
	$nonce = wp_create_nonce("cd_service_request_nonce");
	$x .= '
	<div id="service_msg'.$popup_id.'" class="service_msg"></div>
	<form enctype="multipart/form-data" action="?send_service=1" method="post" class="cdform service-appointment " id="service_form'.$popup_id.'">
	<input type="hidden" name="nonce" id="nonce" value="'.$nonce.'" />
		<fieldset class="cd-fs1">
		<legend>'.__('Schedule Service Appointment', 'car-demon').'</legend>
		<ol class="cd-ol">
			<li id="li-name" class=""><label for="cd_field_2"><span>'.__('Your Name', 'car-demon').'</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="'.__('Your Name', 'car-demon').'" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
			<li id="li" class=""><label for="cd_field_"><span>'.__('Phone #', 'car-demon').'</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" '.$validate_phone.'><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
			<li id="li-4" class=""><label for="cd_field_4"><span>'.__('Email', 'car-demon').'</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">('.__('valid email required', 'car-demon').')</span></li>
		</ol>
		</fieldset>
	';
	if ($location == 'normal') {
		$x .= service_locations_radio();
	} else {
		$x .= '<span id="select_location"><input type="radio" style="display:none;" name="service_location" id="service_location_1" value="'.$location.'" checked /></span>';
	}
	$x .='
			<fieldset class="cd-fs">
			<legend>'.__('Appointment Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-9" class=""><label for="cd_field_9"><span>'.__('Preferred Appointment Date', 'car-demon').'</span></label>
					<input type="text" name="preferred_date" id="preferred_date" class="cd_date" value="" onfocus="showCalendarControl(this);" />
					<a href="#" name="anchor1xx" id="anchor1xx"></a>
				<span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-10" class=""><label for="cd_field_10"><span>'.__('Alternate Appointment Date', 'car-demon').'</span></label>
					<input type="text" name="alt_date" id="alt_date" class="cd_date" value="" onfocus="showCalendarControl(this);" />
					<a href="#" name="anchor2xx" id="anchor2xx"></a>				
				<span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-11" class="cd-box-title">'.__('Will you be...', 'car-demon').'</li>
				<li id="li-11items" class="cd-box-group">
					<input type="radio" id="waiting1" name="waiting" value="'.__('Waiting', 'car-demon').'" checked="checked" class="cd-box-b fldrequired"><span for="cd_field_11-2" class="cdlabel_right"><span>'.__('Waiting', 'car-demon').'</span></span>
					<br>
					<input type="radio" id="waiting2" name="waiting" value="'.__('Leaving Car', 'car-demon').'" class="cd-box-b fldrequired"><span for="cd_field_11-1" class="cdlabel_right"><span>'.__('Leaving Car', 'car-demon').'</span></span>
					<br>
				</li>
				<li id="li-12" class="cd-box-title">'.__('Do you need alternate transportation?', 'car-demon').'</li>
				<li id="li-12items" class="cd-box-group">
					<input type="radio" id="transportation1" name="transportation" value="Yes" class="cd-box-b fldrequired"><span for="cd_field_12-1" class="cdlabel_right"><span>'.__('Yes', 'car-demon').'</span></span>
					<br>
					<input type="radio" id="transportation2" name="transportation" value="No" checked="checked" class="cd-box-b fldrequired"><span for="cd_field_12-2" class="cdlabel_right"><span>'.__('No', 'car-demon').'</span></span>
					<br>
				</li>
			</ol>
			</fieldset>
			<fieldset class="cd-fs4">
			<legend>'.__('Vehicle Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>'.__('Year', 'car-demon').'</span></label><input type="text" name="year" id="year" class="single" value=""></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>'.__('Manufacturer', 'car-demon').'</span></label><input type="text" name="make" id="make" class="single" value=""></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>'.__('Model', 'car-demon').'</span></label><input type="text" name="model" id="model" class="single" value=""></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>'.__('Miles', 'car-demon').'</span></label><input type="text" name="miles" id="miles" class="single" value=""></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>'.__('Vin', 'car-demon').'</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>'.__('Service Required', 'car-demon').'</span></label><textarea cols="30" rows="4" name="service_needed" id="service_needed" class="area fldrequired">'.$service_needed.'</textarea><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
			</ol>
			</fieldset>';
			$x = apply_filters('car_demon_mail_hook_form', $x, 'service_appointment', 'unk');
			$x .= '<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn service_btn" value="'.__('Send Appointment', 'car-demon').'" onclick="return car_demon_validate_service_form(\''.$popup_id.'\')"></p></form>
		';
	if (!empty($popup_id)) {
		$x .= '</div>';
		$x .= '<input type="button" class="search_btn service_btn" id="show_service_form_container_btn_'.$popup_id.'" value="'.$popup_button.'" onclick="show_service_popup(\''.$popup_id.'\')" />';
	}
	return $x;
}
function service_locations_radio() {
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'hide_empty'		 => 0,
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
			<li id="select_location" class="cd-box-title">'.__('Select your preferred Service Location', 'car-demon').'</li>
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