<?php
//= Template part for vehicle archive and search loops
function cdcr_loop($content, $post_id) {
	$cd_cdrf_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$cd_cdrf_pluginpath = str_replace('includes', '', $cd_cdrf_pluginpath);
	$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
	$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
	$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
	$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
	$vehicle_body_style = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),0);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_stock_number = get_post_meta($post_id, "_stock_value", true);
	$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
	$vehicle_exterior_color = get_post_meta($post_id, "_exterior_color_value", true);
	$vehicle_interior_color = get_post_meta($post_id, "_interior_color_value", true);
	$vehicle_mileage = get_post_meta($post_id, "_mileage_value", true);
	$vehicle_transmission = get_post_meta($post_id, "_transmission_value", true);
	$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
	$title = substr($title, 0, 19);
	$stock_value = get_post_meta($post_id, "_stock_value", true);
	$mileage_value = get_post_meta($post_id, "_mileage_value", true);
	if (isset($_SESSION['car_demon_options']['use_compare'])) {
		if ($_SESSION['car_demon_options']['use_compare'] == 'Yes') {
			$in_compare = '';
			if (isset($_SESSION['car_demon_compare'])) {
				$compare_these = split(',',$_SESSION['car_demon_compare']);
			} else {
				$compare_these = array();
			}
			if (in_array($post_id,$compare_these)) {
				$in_compare = ' checked="checked"';
			}
			$compare = '<div class="compare">';
				$compare .= '<div class="compare_input">';
					$compare .= '<input'.$in_compare.' id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />';
				$compare .= '</div>';
				$compare .= '<div class="compare_label">';
					$compare .= 'Compare';
				$compare .= '</div>';
			$compare .= '</div>';
		}
	}
	$link = get_permalink($post_id);
	if (isset($_COOKIE["sales_code"])) {
		$link = $link .'?sales_code='.$_COOKIE["sales_code"];
	}
	$main_photo = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
	//= Build the HTML for each vehicle
	$x .= '<div class="main_photo">';
		$x .= '<a href="'.$link.'">';
			$x .= '<img class="photo_thumb" src="'.$main_photo.'" alt="" title="'.$title.'">';
		$x .= '</a>';
	$x .= '</div>';
	$x .= '<div class="car_title">';
//		$x .= $title;
	$x .= '</div>';
	$x .= $compare;
	$x .= '
		<div class="description">
			<div class="description_left">
				<div class="description_label">Stock #:</div>
				<div class="description_text">'.$vehicle_stock_number.'</div>
				<div class="description_label">Condition:</div>
				<div class="description_text">'. $vehicle_condition.'</div>
				<div class="description_label">Year:</div>
				<div class="description_text">'. $vehicle_year.'</div>
				<div class="description_label">Make:</div>
				<div class="description_text">'. $vehicle_make.'</div>
				<div class="description_label">Model:</div>
				<div class="description_text">'. $vehicle_model.'</div>
				<div class="description_label">Body Style:</div>
				<div class="description_text">'. $vehicle_body_style.'</div>
			</div>
			<div class="description_right">
				<div class="description_label">Transmission:</div>
				<div class="description_text">'. $vehicle_transmission.'</div>
				<div class="description_label">Mileage:</div>
				<div class="description_text">'. $vehicle_mileage.'</div>
				<div class="description_label">Ext. Color:</div>
				<div class="description_text">'. $vehicle_exterior_color.'</div>
				<div class="description_label">Vin Number:</div>
				<div class="description_text_vin">'. $vehicle_vin.'</div>
			</div>
		</div>
		';
	$x .= get_vehicle_price_style($post_id);
	return $x;
}
?>