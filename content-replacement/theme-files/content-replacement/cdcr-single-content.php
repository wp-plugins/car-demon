<?php
function cdcr_single_content($content) {
	$cd_cdrf_pluginpath = CAR_DEMON_PATH;
	$post_id = get_the_ID();
	$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
	$car_title = get_car_title_slug($post_id);
	$car_head_title = get_car_title($post_id);
	$car_url = get_permalink($post_id);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_details = get_post_meta($post_id, 'decode_string', true);
	$mileage = strip_tags(get_post_meta($post_id, "_mileage_value", true));
	//=========================Contact Info===========================
	$car_contact = get_car_contact($post_id);
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();
	echo car_demon_photo_lightbox();
	$detail_output = '<div class="car_title_div">';
		$detail_output .= '<ul>';
			if (!empty($vehicle_details['condition'])) {
				$detail_output .= '<li><strong>'.$field_labels['condition'].':</strong> '.$vehicle_details['condition'].'</li>';
			}
			if ($show_hide['mileage'] != true) {
				$detail_output .= '<li><strong>'.$field_labels['mileage'].':</strong> '.$mileage.'</li>';
			}
			if ($show_hide['stock_number'] != true) {
				$detail_output .= '<li><strong>'.$field_labels['stock_number'].':</strong> '.$vehicle_details['stock_number'].'</li>';
			}
			if ($show_hide['vin'] != true) {
				$detail_output .= '<li><strong>'.$field_labels['vin'].':</strong> '.$vehicle_vin.'</li>';
			}
			if (isset($vehicle_details['exterior_color'])) {
				$detail_output .= '<li><strong>'.$field_labels['exterior_color'].':</strong> '.$vehicle_details['exterior_color'].'/'.$vehicle_details['interior_color'].'</li>';
			}
			if (isset($vehicle_details['decoded_transmission_long'])) {
				$detail_output .= '<li><strong>'.$field_labels['transmission'].':</strong> '.$vehicle_details['decoded_transmission_long'].'</li>';
			}
			if (isset($vehicle_details['decoded_engine_type'])) {
				$detail_output .= '<li><strong>'.$field_labels['engine'].':</strong> '.$vehicle_details['decoded_engine_type'].'</li>';
			}
			$detail_output .= get_vehicle_price($post_id);
		$detail_output .= '</ul>';
	$detail_output .= '</div>';
	$x = car_photos($post_id, $detail_output, $vehicle_condition);
	$x .= car_demon_vehicle_detail_tabs($post_id, true);
	$x .= '<div class="similar_cars_container">
			'. car_demon_display_similar_cars($vehicle_details['decoded_body_style'], $post_id) .'
		  </div>';
	return $x;
}
function cdcr_single_content_2($content) {
	ob_start();
	include_once('styles/single-car-2.php');
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function cdcr_single_content_3($content) {
	$cd_cdrf_pluginpath = CAR_DEMON_PATH;
	$post_id = get_the_ID();
	$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
	$car_title = get_car_title_slug($post_id);
	$car_head_title = get_car_title($post_id);
	$car_url = get_permalink($post_id);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_details = get_post_meta($post_id, 'decode_string', true);
	$mileage = strip_tags(get_post_meta($post_id, "_mileage_value", true));
	//=========================Contact Info===========================
	$car_contact = get_car_contact($post_id);
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();
	echo car_demon_photo_lightbox();
	$detail_output = '<div class="car_title_div">';
		$detail_output .= '<ul>';
			if (!empty($vehicle_details['condition'])) {
				$detail_output .= '<li><strong>'.$field_labels['condition'].':</strong> '.$vehicle_details['condition'].'</li>';
			}
			if ($show_hide['mileage'] != true) {
				$detail_output .= '<li><strong>'.$field_labels['mileage'].':</strong> '.$mileage.'</li>';
			}
			if ($show_hide['stock_number'] != true) {
				$detail_output .= '<li><strong>'.$field_labels['stock_number'].':</strong> '.$vehicle_details['stock_number'].'</li>';
			}
			if ($show_hide['vin'] != true) {
				$detail_output .= '<li><strong>'.$field_labels['vin'].':</strong> '.$vehicle_vin.'</li>';
			}
			if (isset($vehicle_details['exterior_color'])) {
				$detail_output .= '<li><strong>'.$field_labels['exterior_color'].':</strong> '.$vehicle_details['exterior_color'].'/'.$vehicle_details['interior_color'].'</li>';
			}
			if (isset($vehicle_details['decoded_transmission_long'])) {
				$detail_output .= '<li><strong>'.$field_labels['transmission'].':</strong> '.$vehicle_details['decoded_transmission_long'].'</li>';
			}
			if (isset($vehicle_details['decoded_engine_type'])) {
				$detail_output .= '<li><strong>'.$field_labels['engine'].':</strong> '.$vehicle_details['decoded_engine_type'].'</li>';
			}
			$detail_output .= get_vehicle_price($post_id);
		$detail_output .= '</ul>';
	$detail_output .= '</div>';
	
	$detail_output = car_demon_vehicle_detail_tabs($post_id, true);
	$x = car_photos($post_id, $detail_output, $vehicle_condition);
	//$x .= car_demon_vehicle_detail_tabs($post_id, true);
	$x .= '<div class="similar_cars_container">
			'. car_demon_display_similar_cars($vehicle_details['decoded_body_style'], $post_id) .'
		  </div>';
	return $x;
}
?>