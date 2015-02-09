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
	//= Get Ribbon
		$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
	if (empty($ribbon)) {
		$ribbon = 'no-ribbon';
	}
	if ($ribbon != 'custom_ribbon') {
		$ribbon = str_replace('_', '-', $ribbon);
		$current_ribbon = '<img class="inventory_ribbon" src="'. CAR_DEMON_PATH .'theme-files/images/ribbon-'.$ribbon.'.png" width="76" height="76" id="ribbon">';
	} else {
		$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);
		$current_ribbon = '<img class="inventory_ribbon" src="'.$custom_ribbon_file.'" width="76" height="76" id="ribbon">';
	}
	$x = '';
	if (isset($_SESSION['car_demon_options']['dynamic_ribbons'])) {
		if ($_SESSION['car_demon_options']['dynamic_ribbons'] == 'Yes') {
			$current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '76');
		}
	}

	//= Get Main Photo
	$x .= $current_ribbon;	
	$x .= '<div class="main_photo">';
		$x .= '<a href="'.$link.'">';
			$x .= '<img class="photo_thumb" src="'.$main_photo.'" alt="" title="'.$title.'">';
		$x .= '</a>';
	$x .= '</div>';
	$x .= '<div class="car_title">';
//		$x .= $title;
	$x .= '</div>';
	$x .= $compare;
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();

	$x .= '<div class="description">';
		$x .= '<div class="description_left">';
			if ($show_hide['stock_number'] != true) {
				$x .= '<div class="description_label">'.$field_labels['stock_number'].':</div>';
				$x .= '<div class="description_text">'.$vehicle_stock_number.'</div>';
			}
			if (!empty($vehicle_transmission)) {
				$x .= '<div class="description_label">'.$field_labels['condition'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_condition.'</div>';
			}
			if ($show_hide['year'] != true) {
				$x .= '<div class="description_label">'.$field_labels['year'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_year.'</div>';
			}
			if ($show_hide['make'] != true) {
				$x .= '<div class="description_label">'.$field_labels['make'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_make.'</div>';
			}
			if ($show_hide['model'] != true) {
				$x .= '<div class="description_label">'.$field_labels['model'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_model.'</div>';
			}
			if ($show_hide['body_style'] != true) {
				$x .= '<div class="description_label">'.$field_labels['body_style'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_body_style.'</div>';
			}
		$x .= '</div>';
		$x .= '<div class="description_right">';
			if (!empty($vehicle_transmission)) {
				$x .= '<div class="description_label">'.$field_labels['transmission'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_transmission.'</div>';
			}
			if ($show_hide['mileage'] != true) {
				$x .= '<div class="description_label">'.$field_labels['mileage'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_mileage.'</div>';
			}
			if (!empty($vehicle_exterior_color)) {
				$x .= '<div class="description_label">'.$field_labels['exterior_color'].':</div>';
				$x .= '<div class="description_text">'. $vehicle_exterior_color.'</div>';
			}
			if ($show_hide['vin'] != true) {
				$x .= '<div class="description_label">'.$field_labels['vin'].':</div>';
				$x .= '<div class="description_text_vin">'. $vehicle_vin.'</div>';
			}
		$x .= '</div>';
	$x .= '</div>';
	$x .= get_cr_vehicle_price_style($post_id);
	//$x .= get_vehicle_price($post_id);
	return $x;
}
?>