<?php
function car_demon_part_request($location) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	global $cd_formKey;
	wp_enqueue_script('car-demon-part-request-js', WP_CONTENT_URL . '/plugins/car-demon/forms/js/car-demon-part-request.js.php');
	wp_enqueue_style('car-demon-part-request-css', WP_CONTENT_URL . '/plugins/car-demon/forms/css/car-demon-part-request.css');
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
			$validate_phone = ' onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"';
		} else {
			$validate_phone = '';
		}
	} else {
		$validate_phone = '';
	}
	$x = '
	<div id="part_msg" class="part_msg"></div>
	<form enctype="multipart/form-data" action="?send_part=1" method="post" class="cdform part-appointment " id="part_form">
	'.$cd_formKey->outputKey().'
			<fieldset class="cd-fs1">
			<legend>'.__('Your Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>'.__('Your Name', 'car-demon').'</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="Your Name" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li" class=""><label for="cd_field_"><span>'.__('Phone #', 'car-demon').'</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" '.$validate_phone.'><span class="reqtxt">('.__('required', 'car-demon').')</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>'.__('Email', 'car-demon').'</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">('.__('valid email required', 'car-demon').')</span></li>
			</ol>
			</fieldset>
	';
	if ($location == 'normal') {
		$x .= part_locations_radio();
	} else {
		$x .= '<span id="select_location"><input type="radio" style="display:none;" name="part_location" id="part_location_1" value="'.$location.'" checked /></span>';
	}
	$add = '<span id="add_part_btn" class="add_part_btn" onclick="add_part();" class="add_part" title="'.__('Add Part', 'car-demon').'">+ '.__('Add Part', 'car-demon').'</span>';
	$remove = '<span id="remove_part_btn" class="remove_part_btn" onclick="remove_part();" class="remove_part" title="'.__('Remove Part', 'car-demon').'">- '.__('Remove Part', 'car-demon').'</span>';
	$x .='
			<fieldset class="cd-fs4">
			<legend>'.__('Vehicle Information', 'car-demon').'</legend>
			<ol class="cd-ol">
				<li id="li-15" class=""><label for="cd_field_15"><span>'.__('Year', 'car-demon').'</span></label><input type="text" name="year" id="year" class="single" value=""></li>
				<li id="li-14" class=""><label for="cd_field_14"><span>'.__('Manufacturer', 'car-demon').'</span></label><input type="text" name="make" id="make" class="single" value=""></li>
				<li id="li-16" class=""><label for="cd_field_16"><span>'.__('Model', 'car-demon').'</span></label><input type="text" name="model" id="model" class="single" value=""></li>
			</ol>
			</fieldset>
			<fieldset class="cd-fs4">
			<legend>'.__('Parts Needed', 'car-demon').'</legend>
			<ol class="cd-ol">
				'.list_part_lines().'
				<li id="add_part" class="">
					<input type="hidden" id="number_of_parts" value="1" />'.$add.$remove.'
				</li>
				<li id="li-5" class=""><label for="cd_field_5"><span>'.__('Comments & Questions', 'car-demon').'</span></label><textarea cols="30" rows="4" name="part_needed" id="part_needed" class="area fldrequired"></textarea></li>
			</ol>
			</fieldset>';
			$x = apply_filters('car_demon_mail_hook_form', $x, 'parts', 'unk');
			$x .= '<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn parts_btn" value="'.__('Request Quote', 'car-demon').'" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}
function list_part_lines() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '/images', $car_demon_pluginpath);
	$start = 0;
	$stop = 10;
	$x = '';
	$remove_it = '';
	$class = 'show_parts';
	do {
		if ($start > 0) {$class = 'hide_parts';}
		$start = $start + 1;
		if ($start == 1) {$require='<span class="reqtxt">('.__('required', 'car-demon').')</span>';}
			else {$require='';}
		$x .= '<li id="part_name_label_'.$start.'" class="'.$class.'"><label for="part_name_'.$start.'"><span>'.$remove_it.__('Part Name #', 'car-demon').$start.'</span></label><input type="text" name="part_name_'.$start.'" id="part_name_'.$start.'" class="single" value="">'.$require.'</li>';
		$x .= '<li id="part_number_label_'.$start.'" class="'.$class.'"><label for="part_number_'.$start.'"><span>'.__('Part Number #', 'car-demon').$start.'</span></label><input type="text" name="part_number_'.$start.'" id="part_number_'.$start.'" class="single" value=""></li>';
		$x .= '<li id="part_end_'.$start.'" class='.$class.'><hr /></li>';
	} while ($start < $stop);
	return $x;
}

function part_locations_radio() {
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
		<legend>'.__('Parts Department', 'car-demon').'</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">'.__('Select your preferred Parts Department', 'car-demon').'</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_part = " checked='checked'";
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_part.' id="part_location_'.$x.'" name="part_location" value="'.get_option($current_location.'_parts_name').'" class="cd-box-b fldrequired"><span for="part_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_parts_name').'</span></span>
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