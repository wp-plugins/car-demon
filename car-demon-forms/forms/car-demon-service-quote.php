<?php
function car_demon_service_quote($location, $popup_id = '', $popup_button='') {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/car-demon-forms/forms', '', $car_demon_pluginpath);
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-service-quote-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-quote.css');
		}
	} else {
		wp_enqueue_style('car-demon-service-quote-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-quote.css');
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
		wp_enqueue_script('car-demon-service-quote-popup-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-service-quote-popup.js');
		if (isset($_SESSION['car_demon_options']['use_form_css'])) {
			if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
				wp_enqueue_style('car-demon-service-quote-popup-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-quote-popup.css');
			}
		} else {
			wp_enqueue_style('car-demon-service-quote-popup-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-service-quote-popup.css');
		}
		$x .= '<div class="service_quote_container" id="service_quote_container_'.$popup_id.'">';
			$x .= '<div class="close_form" onclick="close_service_quote_popup(\''.$popup_id.'\');"><img src="'.$car_demon_pluginpath.'theme-files/images/close.png" /></div>';
	}
	$nonce = wp_create_nonce("cd_service_quote_request_nonce");
	$x .= '
	<div id="service_msg'.$popup_id.'" class="service_msg"></div>
	<form enctype="multipart/form-data" action="?send_service=1" method="post" class="cdform service-appointment " id="service_quote'.$popup_id.'">
		<input type="hidden" name="nonce" id="nonce" value="'.$nonce.'" />
		<fieldset class="cd-fs1">
		<legend>'.__('Service Quote', 'car-demon').'</legend>
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
	$x .= '
			<fieldset class="cd-fs4">
			<legend>'.__('Vehicle Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>'.__('Year', 'car-demon').'</span></label><input type="text" name="year" id="year" class="single" value=""></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>'.__('Manufacturer', 'car-demon').'</span></label><input type="text" name="make" id="make" class="single" value=""></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>'.__('Model', 'car-demon').'</span></label><input type="text" name="model" id="model" class="single" value=""></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>'.__('Miles', 'car-demon').'</span></label><input type="text" name="miles" id="miles" class="single" value=""></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>'.__('Vin', 'car-demon').'</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>'.__('Service Required', 'car-demon').'</span></label><textarea cols="30" rows="4" name="service_needed" id="service_needed" class="area fldrequired"></textarea><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
			</ol>
			</fieldset>';
			$x = apply_filters('car_demon_mail_hook_form', $x, 'service_quote', 'unk');
			$x .= '<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn service_quote_btn" value="'.__('Send Quote', 'car-demon').'" onclick="return car_demon_validate_service_quote(\''.$popup_id.'\')"></p></form>
		';
	if (!empty($popup_id)) {
		$x .= '</div>';
		$x .= '<input type="button" class="search_btn service_btn" id="show_service_form_container_btn_'.$popup_id.'" value="'.$popup_button.'" onclick="show_service_quote_popup(\''.$popup_id.'\')" />';
	}
	return $x;
}
?>