<?php
function car_demon_trade_form($post_id=0) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	wp_enqueue_script('car-demon-trade-js', WP_CONTENT_URL . '/plugins/car-demon/forms/js/car-demon-trade.js.php');
	wp_enqueue_style('car-demon-trade-css', WP_CONTENT_URL . '/plugins/car-demon/forms/css/car-demon-trade.css');
	$x = '
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
			</fieldset>';
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
			<li id="" class="cd-box-title">Vehicle of Interest</li>
			<li id="not_voi" class="cd-box-title"><input type="checkbox" onclick="show_voi()" style="width:15px;margin-left:15px;" />&nbsp;This is <b>NOT</b> the vehicle I\'m interested in.</li>';
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