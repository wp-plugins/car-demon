<?php
function show_compare_vehicles() {
	$x = '';
	if (isset($_SESSION['car_demon_compare'])) {
		$compare_these_array = $_SESSION['car_demon_compare'];
	}
	else {
		$compare_these_array = '';
	}
	if ($compare_these_array) {
		$compare_these = split(',',$compare_these_array);
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
				$x .= "<img style='margin-left:2px;' onerror='ImgError(this, \"no_photo.gif\");' class='compare_widget_image' width='20px' height='15px' src='";
				$x .= wp_get_attachment_thumb_url( get_post_thumbnail_id( $car ) );
				$x .= "' />&nbsp;";
				$x .= $title;
				$x .= "</a><br />";
			}
		$x .= '<input onclick="open_car_demon_compare();" style="margin-top:10px;" type="button" class="search_btn" value="Compare Now" />';
	}
	else {
		$x .= $_SESSION['car_demon_compare_options']['no_vehicles_msg'];
	}
	$x = '<p>'.$x.'</p>';
	return $x;
}

function show_compare_list() {
	$x = '';
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	$compare_these_array = $_SESSION['car_demon_compare'];
	if ($compare_these_array) {
		$compare_these = split(',',$compare_these_array);
		$x .= '<h2 class="offscreen">'.__('Compare Vehicles', 'car-demon').'</h2>';
		$x .='<div id="car_demon_compare_box_list_cars" style="margin-top:10px;height:550px;overflow:scroll;">';
			foreach ($compare_these as $car) {
				$post_id = $car;
				$x .= '<div style="float:left;width:180px;margin:3px;height:350px;">';
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
				$detail_output .= '<div class="compare_text">';
					$detail_output .= 'Condition: '.$vehicle_condition;			
				$detail_output .= '</div>';
				$detail_output .= '<div class="compare_text">';
					$detail_output .= 'Mileage: '.$mileage_value;
				$detail_output .= '</div>';
				$detail_output .= '<div class="compare_text">';
					$detail_output .= 'Stock#: '.$stock_value;
				$detail_output .= '</div>';
				$detail_output .= '<div class="compare_text">';				
					$detail_output .= 'VIN#: '.$vehicle_vin;
				$detail_output .= '</div>';
				$detail_output .= '<div class="compare_text">';
					$detail_output .= 'Color: '.$vehicle_exterior_color;
				$detail_output .= '</div>';
				$detail_output .= '<div class="compare_text">';
					$detail_output .= 'Transmission: '.$vehicle_transmission;
				$detail_output .= '</div>';
				$detail_output .= '<div class="compare_text">';
					$detail_output .= 'Engine: '.$vehicle_engine;
				$detail_output .= '</div>';
				$new_price = get_vehicle_price($post_id);
				$new_price = str_replace('font-size:16px', 'font-size:12px', $new_price);
				$new_price = str_replace('font-size:20px', 'font-size:14px', $new_price);
				$new_price = str_replace('font-size:28px', 'font-size:14px', $new_price);
				$detail_output .= $new_price;
				$link = get_permalink($post_id);
				$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='compare_widget_image_bg' width='120px' height='95px' src='";
				$img_output .= wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
				$img_output .= "' />";
				$ribbon = 'ribbon-just-added';
				$ribbon = 'ribbon-great-deal';
				if ($vehicle_condition == 'New') {
					$ribbon = 'ribbon-new';
				}
				else {
					if ($mileage_value < 60000) { $ribbon = 'ribbon-low-miles';	}
					$tmp_price = get_post_meta($post_id, "_price_value", true);
					if ($tmp_price < 12000) { $ribbon = 'ribbon-low-price';	}
				}
				$x .= '
					<div class="random">
						<div class="random_img" style="position:relative;">
							'.$img_output.'
						</div>
						<div class="random_description" style="width:180px;">
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