<?php
function car_demon_qualify_form($location, $popup_id, $popup_button) {
	$x = '';
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/car-demon-forms/forms', '', $car_demon_pluginpath);
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-qualify-us-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-qualify.css');
		}
	} else {
		wp_enqueue_style('car-demon-qualify-us-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-qualify.css');
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
	if (!empty($popup_id)) {
		wp_enqueue_script('car-demon-jquery-lightbox', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/jquery.lightbox_me.js', array('jquery'));
		wp_enqueue_script('car-demon-qualify-popup-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-qualify-popup.js');
		if (isset($_SESSION['car_demon_options']['use_form_css'])) {
			if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
				wp_enqueue_style('car-demon-qualify-popup-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-qualify-popup.css');
			}
		} else {
			wp_enqueue_style('car-demon-qualify-popup-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-qualify-popup.css');
		}
		$x .= '<div class="qualify_form_container" id="qualify_form_container_'.$popup_id.'">';
			$x .= '<div class="close_form" onclick="close_qualify_form(\''.$popup_id.'\');"><img src="'.$car_demon_pluginpath.'theme-files/images/close.png" /></div>';
	}
	$nonce = wp_create_nonce("cd_qualify_nonce");
	$x .= '
	<div id="qualify_msg'.$popup_id.'" class="qualify_msg"></div>
	<form enctype="multiqualify/form-data" action="?send_qualify=1" method="post" class="cdform qualify-appointment " id="qualify_form'.$popup_id.'">
		<input type="hidden" name="nonce" id="nonce" value="'.$nonce.'" />
		<fieldset class="cd-fs1">
		<legend>'.__('YOUR INFORMATION','car-demon').'</legend>
		<ol class="cd-ol">
			<li id="li-name" class=""><label for="cd_field_2"><span>'.__('Your Name','car-demon').'</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="'.__('Your Name', 'car-demon').'" onfocus="clearField(this)" onblur="setField(this)"></li>
			<li id="li" class=""><label for="cd_field_"><span>'.__('Phone #','car-demon').'</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" '.$validate_phone.'></li>
			<li id="li-4" class=""><label for="cd_field_4"><span>'.__('Email') .'</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">('.__('valid email required','car-demon').')</span></li>
			<li id="li-5" class=""><label for="cd_field_5"><span>'.__('What city do you live in?') .'</span></label><input type="text" name="cd_city" id="cd_city" class="single fldrequired" value=""></li>
			<li id="li-6" class=""><label for="cd_field_6"><span>'.__('How long at current address?') .'</span></label>
				<select id="taa">
					<option value="'.__('Under 1 Year', 'car-demon').'">'.__('Under 1 Year', 'car-demon').'</option>
					<option value="'.__('1-2 Years', 'car-demon').'">'.__('1-2 Years', 'car-demon').'</option>
					<option value="'.__('2-3 Years', 'car-demon').'">'.__('2-3 Years', 'car-demon').'</option>
					<option value="'.__('Over 3 Years', 'car-demon').'">'.__('Over 3 Years', 'car-demon').'</option>
				</select>
			</li>
			<li id="li-7" class=""><label for="cd_field_7"><span>'.__('Current Employer') .'</span></label><input type="text" name="cd_employer" id="cd_employer" class="single fldrequired" value=""></li>
			<li id="li-8" class=""><label for="cd_field_8"><span>'.__('Gross Monthly Income') .'</span></label><input type="text" name="cd_income" id="cd_income" class="single fldrequired" value=""></li>
			<li id="li-9" class=""><label for="cd_field_9"><span>'.__('How long at current job?') .'</span></label>
				<select id="taj">
					<option value="'.__('Under 1 Year', 'car-demon').'">'.__('Under 1 Year', 'car-demon').'</option>
					<option value="'.__('1-2 Years', 'car-demon').'">'.__('1-2 Years', 'car-demon').'</option>
					<option value="'.__('2-3 Years', 'car-demon').'">'.__('2-3 Years', 'car-demon').'</option>
					<option value="'.__('Over 3 Years', 'car-demon').'">'.__('Over 3 Years', 'car-demon').'</option>
				</select>
			</li>
			<li id="li-10" class=""><label for="cd_field_10"><span>'.__('How much can you put down?') .'</span></label><input type="text" name="cd_down" id="cd_down" class="single fldrequired" value="0"></li>
			<li id="li-10" class=""><label for="cd_field_10"><span>'.__('Do you have a trade in?') .'</span></label>
				<select id="have_trade">
					<option value="'.__('No','car-demon').'">'.__('No','car-demon').'</option>
					<option value="'.__('Yes','car-demon').'">'.__('Yes','car-demon').'</option>
				</select>
			</li>
		</ol>
		</fieldset>
	';
	if ($location == 'normal') {
		$x .= finance_locations_radio();
	} else {
		$x .= '<span id="select_location"><select class="qualify_us_send_to" id="send_to" style="display:none;"><option value="'.$location.'"></option></select></span>';
	}
	$add = '<img src="'.$car_demon_pluginpath.'images/btn_add_qualify.png" id="add_qualify_btn" class="add_qualify_btn" onclick="add_qualify();" class="add_qualify" title="'.__('Add qualify', 'car-demon').'" />';
	$remove = '<img src="'.$car_demon_pluginpath.'images/btn_remove_qualify.png" id="remove_qualify_btn" class="remove_qualify_btn" onclick="remove_qualify();" class="remove_qualify" title="'.__('Remove qualify', 'car-demon').'" />';
	$x .='
			<fieldset class="cd-fs4">
			<legend>'.__('Questions and Comments','car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-5" class=""><textarea class="qualify_comment" cols="60" rows="4" name="qualify_comment" id="qualify_comment" class="area fldrequired"></textarea></li>
			</ol>
			</fieldset>';
	$x = apply_filters('car_demon_mail_hook_form', $x, 'qualify', 'unk');
	$x .='
			<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn qualify_us_btn" value="'.__('Qualify Me','car-demon').'" onclick="return car_demon_validate_qualify(\''.$popup_id.'\')"></p></form>
		';
	if (!empty($popup_id)) {
		$x .= '</div>';
		$x .= '<input type="button" class="search_btn qualify_us_btn" id="show_qualify_form_container_btn_'.$popup_id.'" value="'.$popup_button.'" onclick="show_qualify_popup(\''.$popup_id.'\')" />';
	}
	return $x;
}
?>