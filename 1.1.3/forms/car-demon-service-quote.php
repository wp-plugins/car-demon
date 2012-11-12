<?php
function car_demon_service_quote() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	wp_enqueue_script('car-demon-service-quote-js', WP_CONTENT_URL . '/plugins/car-demon/forms/js/car-demon-service-quote.js.php');
	wp_enqueue_style('car-demon-service-quote-css', WP_CONTENT_URL . '/plugins/car-demon/forms/css/car-demon-service-quote.css');
	$x = '
	<div id="service_msg" class="service_msg"></div>
	<form enctype="multipart/form-data" action="?send_service=1" method="post" class="cdform service-appointment " id="service_form">
			'.$cd_formKey->outputKey().'
			<fieldset class="cd-fs1">
			<legend>Service Quote</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>Your Name</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="Your Name" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">(required)</span></li>
				<li id="li" class=""><label for="cd_field_"><span>Phone #</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"><span class="reqtxt">(required)</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>Email</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">(valid email required)</span></li>
			</ol>
			</fieldset>
	';
	$x .= service_locations_radio();
	$x .='
			<fieldset class="cd-fs4">
			<legend>Vehicle Information</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>Year</span></label><input type="text" name="year" id="year" class="single" value=""></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>Manufacturer</span></label><input type="text" name="make" id="make" class="single" value=""></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>Model</span></label><input type="text" name="model" id="model" class="single" value=""></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>Miles</span></label><input type="text" name="miles" id="miles" class="single" value=""></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>Vin</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>Service Required</span></label><textarea cols="30" rows="4" name="service_needed" id="service_needed" class="area fldrequired"></textarea><span class="reqtxt">(required)</span></li>
			</ol>
			</fieldset>';
			$x = apply_filters('car_demon_mail_hook_form', $x, 'service_quote', 'unk');
			$x .= '<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn service_quote_btn" value="Send Quote" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}
?>