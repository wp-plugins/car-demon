<?php
function car_demon_trade_form($post_id=0, $location) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/car-demon-forms/forms', '', $car_demon_pluginpath);
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-trade-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-trade.css');
		}
	} else {
		wp_enqueue_style('car-demon-trade-css', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/css/car-demon-trade.css');
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
	$nonce = wp_create_nonce("cd_contact_us_nonce");
	$x = '
	<div id="trade_msg" class="trade_msg"></div>
	<form enctype="multipart/form-data" action="?send_trade=1" method="post" class="cdform trade-appointment " id="trade_form">
			<input type="hidden" name="nonce" id="nonce" value="'.$nonce.'" />
			<fieldset class="cd-fs1">
			<legend>'.__('Your Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>'.__('Your Name', 'car-demon').'</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="'.__('Your Name', 'car-demon').'" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li" class=""><label for="cd_field_"><span>'.__('Phone #', 'car-demon').'</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" '.$validate_phone.'><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>'.__('Email', 'car-demon').'</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">('.__('valid email required', 'car-demon').')</span></li>
			</ol>
			</fieldset>';
	$x .='
			<fieldset class="cd-fs4">
			<legend>'.__('Vehicle Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>'.__('Year', 'car-demon').'</span></label><input type="text" name="year" id="year" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>'.__('Manufacturer', 'car-demon').'</span></label><input type="text" name="make" id="make" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>'.__('Model', 'car-demon').'</span></label><input type="text" name="model" id="model" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-17" class=""><label for="cd_field_17"><span>'.__('Miles', 'car-demon').'</span></label><input type="text" name="miles" id="miles" class="single" value=""><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-18" class=""><label for="cd_field_18"><span>'.__('Vin', 'car-demon').'</span></label><input type="text" name="vin" id="vin" class="single" value=""></li>
				<li id="li-5" class=""><label for="cd_field_5"><span>'.__('Comments', 'car-demon').'</span></label><textarea cols="30" rows="4" name="comment" id="comment" class="area fldrequired"></textarea></li>
			</ol>
			</fieldset>';
	$x .= car_demon_trade_options();
	$x .= '
		<fieldset class="cd-fs2">
		<legend>'.__('Purchase Information', 'car-demon').'</legend>
		';
		if (isset($_GET['stock_num'])) {
			$x .= select_trade_for_vehicle(1);
			$x .= get_trade_for_vehicle($_GET['stock_num']);
		}
		else {
			$x .= select_trade_for_vehicle(0);
			$x .= '<ol class="cd-ol" id="show_voi"></o>';
		}
	$x .= '</fieldset>';
	if ($location == 'normal') {
		$x .= trade_locations_radio();
	} else {
		$x .= '<span id="select_location"><input type="radio" style="display:none;" name="trade_location" id="trade_location_1" value="'.$location.'" checked /></span>';
	}
	$x = apply_filters('car_demon_mail_hook_form', $x, 'trade_form', 'unk');
	$x .= '
		<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn trade_btn" value="'.__("Get Quote!").'" onclick="return car_demon_validate()"></p></form>
	';
	return $x;
}
function get_trade_for_vehicle($stock_num) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value='".$stock_num."'";
	$posts = $wpdb->get_results($sql);
	$vehicle_vin = '';
	$vehicle_year = '';
	$vehicle_make = '';
	$vehicle_model = '';
	$vehicle_condition = '';
	$vehicle_body_style = '';
	$vehicle_photo = '';
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
			$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
			$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
			$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
			$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
			$vehicle_body_style = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),0);
			$vehicle_photo = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		}
	}
	$x = '
		<input type="hidden" id="purchase_stock" value="'.$stock_num.'" />
		<ol class="cd-ol" id="show_voi">
			<li id="" class="cd-box-title">'.__('Vehicle of Interest', 'car-demon').'</li>
			<li id="not_voi" class="cd-box-title"><input type="checkbox" class="not_my_car" onclick="show_voi()" />&nbsp;'.__('This is', 'car-demon').' <b>'.__('NOT', 'car-demon').'</b> '.__('the vehicle I\'m interested in.', 'car-demon').'</li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('Stock #', 'car-demon').'</span></label><label class="trade_label"><span class="trade_label">'.$stock_num.'</span></label></li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('VIN', 'car-demon').'</span></label><label class="trade_label"><span class="trade_label">'.$vehicle_vin.'</span></label></li>';
			$vehicle = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model .' '. $vehicle_body_style;
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('Vehicle', 'car-demon').'</span></label><label class="trade_label"><span class="trade_label">'.$vehicle.'</span></label></li>';
			$x .= '<li id="" class=""><img src="'.$vehicle_photo.'" width="300" class="random_widget_image trade_img" title="'.$vehicle.'" alt="'.$vehicle.'" /></li>';
			$x .= '
			</li>
		</ol>
	';
	return $x;
}
function select_trade_for_vehicle($hide=0) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('forms/','',$car_demon_pluginpath);
	if ($hide == 1) {
		$hidden = " trade_hide";
	} else {
		$hidden = '';
	}
	$x = '
		<ol class="cd-ol'.$hidden.'" id="find_voi">
			<li id="voi_title" class="cd-box-title">'.__('What Vehicle are you Interested In Purchasing?', 'car-demon').'</li>
			<li id="" class="cd-box-title"><input onclick="select_voi(\'stock\');" name="pick_voi" id="pick_voi_1" type="radio" value="1" />'.__('I know the stock#', 'car-demon').'</li>
			<li id="select_stock" class="trade_select_stock"><span>'.__('Stock #', 'car-demon').'</span>&nbsp;<input class="ac_input" type="text" id="select_stock_txt" /></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_2" onclick="select_voi(\'search\');" type="radio" value="2" />'.__('I would like to search for it', 'car-demon').'</li>
			<li id="select_description" class="trade_select_description"><span>'.__('Description', 'car-demon').'</span>&nbsp;<input type="text"  id="select_car_txt" /><span>&nbsp;('.__('enter year, make or model', 'car-demon').')</span></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_3" onclick="select_voi(\'na\');" type="radio" checked="checked" value="3" />'.__('I haven\'t made up my mind.', 'car-demon').'</li>
			<li id="li-7items" class="cd-box-group">';
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
	if (empty($_GET['stock_num'])) {
		$hidden = "";	
	} else {
		$hidden = " trade_hide";
	}
	$html = '
		<fieldset class="cd-fs2'.$hidden.'" id="trade_locations">
		<legend id="trade_locations_label">'.__('Trade Location', 'car-demon').'</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">'.__('Select your preferred Trade Location', 'car-demon').'</li>
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
		<legend>'.__('Your Trade-In Vehicle Options', 'car-demon').'</legend>
		<ol class="cd-ol">
			<li id="li-7-25items" class="cd-box-group">
				<input type="checkbox" id="Options-1" name="Options[]" value="'.__('4 Wheel Drive', 'car-demon').'" class="cd-box"><label for="Options-1" class="cd-group-after"><span>'.__('4 Wheel Drive', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-2" name="Options[]" value="'.__('ABS Brakes', 'car-demon').'" class="cd-box"><label for="Options-2" class="cd-group-after"><span>'.__('ABS Brakes', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-3" name="Options[]" value="'.__('Air Bag', 'car-demon').'" class="cd-box"><label for="Options-3" class="cd-group-after"><span>'.__('Air Bag', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-4" name="Options[]" value="'.__('Air Conditioning', 'car-demon').'" class="cd-box"><label for="Options-4" class="cd-group-after"><span>'.__('Air Conditioning', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-5" name="Options[]" value="'.__('Alloy Wheels', 'car-demon').'" class="cd-box"><label for="Options-5" class="cd-group-after"><span>'.__('Alloy Wheels', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-6" name="Options[]" value="'.__('AM/FM Stereo', 'car-demon').'" class="cd-box"><label for="Options-6" class="cd-group-after"><span>'.__('AM/FM Stereo', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-7" name="Options[]" value="'.__('Anti-Theft', 'car-demon').'" class="cd-box"><label for="Options-7" class="cd-group-after"><span>'.__('Anti-Theft', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-8" name="Options[]" value="'.__('Bed Liner', 'car-demon').'" class="cd-box"><label for="Options-8" class="cd-group-after"><span>'.__('Bed Liner', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-9" name="Options[]" value="'.__('Bra', 'car-demon').'" class="cd-box"><label for="Options-9" class="cd-group-after"><span>'.__('Bra', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-10" name="Options[]" value="'.__('Cassette', 'car-demon').'" class="cd-box"><label for="Options-10" class="cd-group-after"><span>'.__('Cassette', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-11" name="Options[]" value="'.__('Cruise Control', 'car-demon').'" class="cd-box"><label for="Options-11" class="cd-group-after"><span>'.__('Cruise Control', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-12" name="Options[]" value="'.__('Dual Air Bags', 'car-demon').'" class="cd-box"><label for="Options-12" class="cd-group-after"><span>'.__('Dual Air Bags', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-13" name="Options[]" value="'.__('Dual Rear Wheels', 'car-demon').'" class="cd-box"><label for="Options-13" class="cd-group-after"><span>'.__('Dual Rear Wheels', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-14" name="Options[]" value="'.__('DVD System', 'car-demon').'" class="cd-box"><label for="Options-14" class="cd-group-after"><span>'.__('DVD System', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-15" name="Options[]" value="'.__('Integrated Cellular', 'car-demon').'" class="cd-box"><label for="Options-15" class="cd-group-after"><span>'.__('Integrated Cellular', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-16" name="Options[]" value="'.__('Leather', 'car-demon').'" class="cd-box"><label for="Options-16" class="cd-group-after"><span>'.__('Leather', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-17" name="Options[]" value="'.__('Long Bed', 'car-demon').'" class="cd-box"><label for="Options-17" class="cd-group-after"><span>'.__('Long Bed', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-18" name="Options[]" value="'.__('Luggage Rack', 'car-demon').'" class="cd-box"><label for="Options-18" class="cd-group-after"><span>'.__('Luggage Rack', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-19" name="Options[]" value="'.__('Moon Roof', 'car-demon').'" class="cd-box"><label for="Options-19" class="cd-group-after"><span>'.__('Moon Roof', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-20" name="Options[]" value="'.__('Multi CD', 'car-demon').'" class="cd-box"><label for="Options-20" class="cd-group-after"><span>'.__('Multi CD', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-21" name="Options[]" value="'.__('Nav System', 'car-demon').'" class="cd-box"><label for="Options-21" class="cd-group-after"><span>'.__('Nav System', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-22" name="Options[]" value="'.__('Power Locks', 'car-demon').'" class="cd-box"><label for="Options-22" class="cd-group-after"><span>'.__('Power Locks', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-23" name="Options[]" value="'.__('Power Seats', 'car-demon').'" class="cd-box"><label for="Options-23" class="cd-group-after"><span>'.__('Power Seats', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-24" name="Options[]" value="'.__('Power Windows', 'car-demon').'" class="cd-box"><label for="Options-24" class="cd-group-after"><span>'.__('Power Windows', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-25" name="Options[]" value="'.__('Premium Wheels', 'car-demon').'" class="cd-box"><label for="Options-25" class="cd-group-after"><span>'.__('Premium Wheels', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-26" name="Options[]" value="'.__('Privacy Glass', 'car-demon').'" class="cd-box"><label for="Options-26" class="cd-group-after"><span>'.__('Privacy Glass', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-27" name="Options[]" value="'.__('Rear Air/Heat', 'car-demon').'" class="cd-box"><label for="Options-27" class="cd-group-after"><span>'.__('Rear Air/Heat', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-28" name="Options[]" value="'.__('Running Boards', 'car-demon').'" class="cd-box"><label for="Options-28" class="cd-group-after"><span>'.__('Running Boards', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-29" name="Options[]" value="'.__('Short Bed', 'car-demon').'" class="cd-box"><label for="Options-29" class="cd-group-after"><span>'.__('Short Bed', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-30" name="Options[]" value="'.__('Single CD', 'car-demon').'" class="cd-box"><label for="Options-30" class="cd-group-after"><span>'.__('Single CD', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-31" name="Options[]" value="'.__('Sliding Rear Window', 'car-demon').'" class="cd-box"><label for="Options-31" class="cd-group-after"><span>'.__('Sliding Rear Window', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-32" name="Options[]" value="'.__('Sun Roof', 'car-demon').'" class="cd-box"><label for="Options-32" class="cd-group-after"><span>'.__('Sun Roof', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-33" name="Options[]" value="'.__('Third Seat', 'car-demon').'" class="cd-box"><label for="Options-33" class="cd-group-after"><span>'.__('Third Seat', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-34" name="Options[]" value="'.__('Tilt Wheel', 'car-demon').'" class="cd-box"><label for="Options-34" class="cd-group-after"><span>'.__('Tilt Wheel', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-35" name="Options[]" value="'.__('Towing Package', 'car-demon').'" class="cd-box"><label for="Options-35" class="cd-group-after"><span>'.__('Towing Package', 'car-demon').'</span></label>
				<input type="checkbox" id="Options-36" name="Options[]" value="'.__('Video System', 'car-demon').'" class="cd-box"><label for="Options-36" class="cd-group-after"><span>'.__('Video System', 'car-demon').'</span></label>
				<br>
				<input type="checkbox" id="Options-37" name="Options[]" value="'.__('Wheel Covers', 'car-demon').'" class="cd-box"><label for="Options-37" class="cd-group-after"><span>'.__('Wheel Covers', 'car-demon').'</span></label>
			</li>
		</ol><span class="reqtxt trade_reqtxt">('.__('options not required, but help provide an accurate quote', 'car-demon').')</span>
		</fieldset>';
	return $x;
}
?>