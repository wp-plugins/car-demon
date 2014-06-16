<?php
function cd_compare_handler() {
	if (isset($_SESSION['car_demon_compare'])) {
		$compare_these = $_SESSION['car_demon_compare'];
	} else {
		$compare_these = '';
	}
	if ($_POST['add_it'] == 1) {
		$compare_these = str_replace(','.$_POST['post_id'],'',$compare_these);
		$compare_these = str_replace($_POST['post_id'],'',$compare_these);
		$compare_these = $compare_these.','.$_POST['post_id'];	
	} else {
		$compare_these = str_replace(','.$_POST['post_id'],'',$compare_these);
		$compare_these = str_replace($_POST['post_id'],'',$compare_these);
	}
	$compare_these = '@'.$compare_these;
	$compare_these = str_replace('@,','',$compare_these);
	$compare_these = str_replace('@','',$compare_these);
	$_SESSION['car_demon_compare'] = $compare_these;
	echo show_compare_vehicles();
	exit();
}
function cd_get_compare_list() {
	echo show_compare_list();
	exit();
}
function show_compare_vehicles() {
	$x = '';
	if (isset($_SESSION['car_demon_compare'])) {
		$compare_these_array = $_SESSION['car_demon_compare'];
	} else {
		$compare_these_array = '';
	}
	if ($compare_these_array) {
		$compare_these = explode(',',$compare_these_array);
			if (!$compare_these) {
				$x .= $_SESSION['car_demon_compare_options']['no_vehicles_msg'];
			}
			foreach ($compare_these as $car) {
				$vehicle_year = strip_tags(get_the_term_list( $car, 'vehicle_year', '','', '', '' ));
				$vehicle_make = strip_tags(get_the_term_list( $car, 'vehicle_make', '','', '', '' ));
				$vehicle_model = strip_tags(get_the_term_list( $car, 'vehicle_model', '','', '', '' ));
				$vehicle_condition = strip_tags(get_the_term_list( $car, 'vehicle_condition', '','', '', '' ));
				$stock_value = get_post_meta($car, "_stock_value", true);
				$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
				$title = substr($title, 0, 24);
				$link = get_permalink($car);
				$x .= '<input checked="checked" type="checkbox" onclick="update_car('.$car.',this);" />&nbsp;';
				$x .= '<a href="'.$link.'" title="'.$title.', '.__('Stock#:', 'car-demon').' '.$stock_value.'">';
				$x .= "<img onerror='ImgError(this, \"no_photo.gif\");' class='compare_widget_image' width='20px' height='15px' src='";
				$x .= wp_get_attachment_thumb_url( get_post_thumbnail_id( $car ) );
				$x .= "' />&nbsp;";
				$x .= $title;
				$x .= "</a><br />";
			}
		$x .= '<input onclick="open_car_demon_compare();" type="button" class="search_btn compare_btn" value="Compare Now" />';
	} else {
		$x .= $_SESSION['car_demon_compare_options']['no_vehicles_msg'];
	}
	$x = '<p>'.$x.'</p>';
	return $x;
}
function show_compare_list() {
	$x = '';
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	$compare_these_array = $_SESSION['car_demon_compare'];
	if ($compare_these_array) {
		$compare_these = explode(',',$compare_these_array);
		//= Find out which of the default fields are hidden
		$show_hide = get_show_hide_fields();
		//= Get the labels for the default fields
		$field_labels = get_default_field_labels();
		$x .= '<h2 class="offscreen">'.__('Compare Vehicles', 'car-demon').'</h2>';
		$x .='<div id="car_demon_compare_box_list_cars" class="car_demon_compare_box_list_cars">';
			foreach ($compare_these as $car) {
				$post_id = $car;
				$x .= '<div class="car_demon_compare_box_list_cars_div">';
				$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
				$vehicle_exterior_color = get_post_meta($post_id, "_exterior_color_value", true);
				$vehicle_transmission = get_post_meta($post_id, "_transmission_value", true);
				$vehicle_engine = get_post_meta($post_id, "_engine_value", true);
				$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
				$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
				$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
				$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
				$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
				$title = substr($title, 0, 24);
				$stock_value = get_post_meta($post_id, "_stock_value", true);
				$mileage_value = get_post_meta($post_id, "_mileage_value", true);
				$detail_output = '<div class="compare_title">'.$title.'</div>';
				if ($show_hide['condition'] != true) {
					if (!empty($vehicle_condition)) {
						$detail_output .= '<div class="compare_text">';
							$detail_output .= $field_labels['condition'].': '.$vehicle_condition;			
						$detail_output .= '</div>';
					}
				}
				if ($show_hide['mileage'] != true) {
					if (!empty($mileage_value)) {
						$detail_output .= '<div class="compare_text">';
							$detail_output .= $field_labels['mileage'].': '.$mileage_value;
						$detail_output .= '</div>';
					}
				}
				if ($show_hide['stock_number'] != true) {
					if (!empty($stock_value)) {
						$detail_output .= '<div class="compare_text">';
							$detail_output .= $field_labels['stock_number'].': '.$stock_value;
						$detail_output .= '</div>';
					}
				}
				if ($show_hide['vin'] != true) {
					if (!empty($vehicle_vin)) {
						$detail_output .= '<div class="compare_text">';				
							$detail_output .= $field_labels['vin'].': '.$vehicle_vin;
						$detail_output .= '</div>';
					}
				}
				if ($show_hide['exterior_color'] != true) {
					if (!empty($vehicle_exterior_color)) {
						$detail_output .= '<div class="compare_text">';
							$detail_output .= $field_labels['exterior_color'].': '.$vehicle_exterior_color;
						$detail_output .= '</div>';
					}
				}
				if ($show_hide['transmission'] != true) {
					if (!empty($vehicle_transmission)) {
						$detail_output .= '<div class="compare_text">';
							$detail_output .= $field_labels['transmission'].': '.$vehicle_transmission;
						$detail_output .= '</div>';
					}
				}
				if ($show_hide['engine'] != true) {
					if (!empty($vehicle_engine)) {
						$detail_output .= '<div class="compare_text">';
							$detail_output .= $field_labels['engine'].': '.$vehicle_engine;
						$detail_output .= '</div>';
					}
				}
				$new_price = get_vehicle_price($post_id);
				$new_price = str_replace('class="car_selling_price"', 'class="car_selling_price car_selling_price_compare"', $new_price);
				$new_price = str_replace('class="car_rebate', 'class="car_rebate car_rebate_compare"', $new_price);
				$new_price = str_replace('class="car_dealer_discounts', 'class="car_dealer_discounts car_dealer_discounts_compare"', $new_price);
				$new_price = str_replace('class="car_retail_price', 'class="car_retail_price car_retail_price_compare"', $new_price);
				$new_price = str_replace('class="car_your_price', 'class="car_your_price car_your_price_compare"', $new_price);
				$new_price = str_replace('class="car_final_price', 'class="car_final_price car_final_price_compare"', $new_price);
				$detail_output .= $new_price;
				$link = get_permalink($post_id);
				$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='compare_widget_image_bg' width='120px' height='95px' src='";
				$img_output .= wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
				$img_output .= "' />";
				$x .= '
					<div class="random">
						<div class="random_img random_img_compare">
							'.$img_output.'
						</div>
						<div class="random_description random_description_compare">
							'.$detail_output.'
						</div>
					</div>';
				$x .= '</div>';
			}
		$x .= '</div>';
	}
	return $x;
}
?>