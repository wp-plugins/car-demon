<?php
function contact_us_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'send_to' => 'normal',
		'popup_id' => '',
		'popup_button' => __('Contact Us', 'car-demon')
	), $atts ) );
	$contact_us = car_demon_contact_request($send_to, $popup_id, $popup_button);
	return $contact_us;
}
add_shortcode( 'contact_us', 'contact_us_shortcode_func' );
function search_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'size' => '0',
		'popup_id' => '',
		'popup_button' => __('Search Vehicles', 'car-demon')
	), $atts ) );
	if ($size == 0) {
		$size = 's';
		$search_form = car_demon_simple_search($size);	
	} elseif ($size == 1) {
		$size = '1';
		$search_form = car_demon_simple_search($size);	
	} else {
		$search_form = car_demon_search_form();
	}
	return $search_form;
}
add_shortcode( 'search_form', 'search_shortcode_func' );
function search_box_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'button' => __('Search', 'car-demon'),
		'message' => 'Search Vehicles',
		'popup_id' => '',
		'popup_button' => __('Search Vehicles', 'car-demon')
	), $atts ) );
	$search_box = vehicle_search_box($button, $message);
	return $search_box;
}
add_shortcode( 'search_box', 'search_box_shortcode_func' );
function parts_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'location' => 'normal',
		'popup_id' => '',
		'popup_button' => __('Request Parts Quote', 'car-demon')
	), $atts ) );
	$part_quote = car_demon_part_request($location, $popup_id, $popup_button);
	return $part_quote;
}
add_shortcode( 'part_request', 'parts_shortcode_func' );
function service_form_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'location' => 'normal',
		'popup_id' => '',
		'popup_button' => __('Service Appointment', 'car-demon')
	), $atts ) );
	$service_form = car_demon_service_form($location, $popup_id, $popup_button);
	return $service_form;
}
add_shortcode( 'service_form', 'service_form_shortcode_func' );
function service_quote_form_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'location' => 'normal',
		'popup_id' => '',
		'popup_button' => __('Service Quote', 'car-demon')
	), $atts ) );
	$service_quote = car_demon_service_quote($location, $popup_id, $popup_button);
	return $service_quote;
}
add_shortcode( 'service_quote', 'service_quote_form_shortcode_func' );
function trade_form_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'location' => 'normal'
	), $atts ) );
	$trade_form = car_demon_trade_form(0,$location);
	return $trade_form;
}
add_shortcode( 'trade', 'trade_form_shortcode_func' );
function finance_form_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'location' => 'normal'
	), $atts ) );
	$finance_form = car_demon_finance_form(0,$location);
	return $finance_form;
}
add_shortcode( 'finance_form', 'finance_form_shortcode_func' );
function qualify_form_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'location' => 'normal',
		'popup_id' => '',
		'popup_button' => __('Qualify Me', 'car-demon')
	), $atts ) );
	$qualify_form = car_demon_qualify_form($location, $popup_id, $popup_button);
	return $qualify_form;
}
add_shortcode( 'qualify', 'qualify_form_shortcode_func' );
function highlight_staff_shortcode_func( $atts ) {
	if (isset($_COOKIE["sales_code"])) {
		$staff_id = $_COOKIE["sales_code"];
	} else {
		$staff_id = '';
	}
	extract( shortcode_atts( array(
		'staff_id' => $staff_id,
		'contact_id' => '',
		'contact_button' => __('Contact Me', 'car-demon')
	), $atts ) );
	if (!empty($staff_id)) {
		$highlight_staff = build_user_hcard($staff_id, 1, 1);
		$highlight_staff = '<div class="highlight_staff">'.$highlight_staff.'</div>';
	} else {
		$highlight_staff = '';
	}
	return $highlight_staff;
}
add_shortcode( 'highlight_staff', 'highlight_staff_shortcode_func' );
function vehicle_cloud_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'taxonomy' => 'vehicle_body_style',
		'max_num' => '',
		'max_font' => '14',
		'min_font' => '14'
	), $atts ) );
	$vehicle_cloud = vehicle_cloud($taxonomy, $max_num, $max_font, $min_font);
	return $vehicle_cloud;
}
add_shortcode( 'vehicle_cloud', 'vehicle_cloud_shortcode_func' );
function vehicle_search_box_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'button' => 'Search Inventory',
		'message' => ''
	), $atts ) );
	$vehicle_cloud = vehicle_search_box($button, $message);
	return $vehicle_cloud;
}
add_shortcode( 'vehicle_search_box', 'vehicle_search_box_shortcode_func' );

function staff_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
	), $atts ) );
	$staff_page = car_demon_staff_page();
	return $staff_page;
}
add_shortcode( 'staff_page', 'staff_shortcode_func' );

function random_cars_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
		'amount'=> '1'
	), $atts ) );
	$x = car_demon_display_random_cars($amount);
	return $x;
}
add_shortcode( 'random_cars', 'random_cars_shortcode_func' );

//===================================================
//= Legacy shortcode
function car_demon_shortcodes( $content ) {
	if (strpos($content, '[-staff_page-]')) {
		$staff_page = car_demon_staff_page();
		$content = str_replace('[-staff_page-]', $staff_page, $content);
	}
	return $content;
}
add_filter('the_content', 'car_demon_shortcodes');
?>