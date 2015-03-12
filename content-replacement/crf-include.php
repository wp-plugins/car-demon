<?php
function car_crf_display_car_list($post_id) {
	$cd_cdrf_pluginpath = CAR_DEMON_PATH;
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
	$vehicle_fuel = get_post_meta($post_id, "_fuel_type_value", true);
	$vehicle_transmission = get_post_meta($post_id, "_transmission_value", true);
	$vehicle_cylinders = get_post_meta($post_id, "_cylinders_value", true);
	$vehicle_engine = get_post_meta($post_id, "_engine_value", true);
	$vehicle_doors = get_post_meta($post_id, "_doors_value", true);
	$vehicle_trim = get_post_meta($post_id, "_trim_value", true);
	$vehicle_warranty = get_post_meta($post_id, "_warranty_value", true);
	$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
	$title = substr($title, 0, 19);
	$stock_value = get_post_meta($post_id, "_stock_value", true);
	$mileage_value = get_post_meta($post_id, "_mileage_value", true);
	if ($_SESSION['car_demon_options']['use_compare'] == 'Yes') {
		$in_compare = '';
		if (isset($_SESSION['cd_cdrf_compare'])) {
			$compare_these = split(',',$_SESSION['cd_cdrf_compare']);
		} else {
			$compare_these = array();
		}
		if (in_array($post_id,$compare_these)) {
			$in_compare = ' checked="checked"';
		}
		$compare = '<div class="text_compare_style">';
			$compare .= '<input'.$in_compare.' id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />&nbsp;<a href="" />Compare</a>';
		$compare .= '</div>';
	}
	$link = get_permalink($post_id);
	if ($_COOKIE["sales_code"]) {
		$link = $link .'?sales_code='.$_COOKIE["sales_code"];
	}
	$detail_output = '<div class="random_text">';
		$detail_output .= '<a href="'.$link.'" class="search_btn" style="margin-left:20px;">View Details</a>';
	$detail_output .= '</div>';
	$img_output = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
	$banner_class = 'banner-just-added';
	$banner_class = 'banner-great-deal';
	if ($vehicle_condition == 'New') {
		$banner_class = 'banner-new';
	}
	else {
		if ($mileage_value < 60000) { $banner_class = 'banner-low-miles';	}
		$tmp_price = get_post_meta($post_id, "_price_value", true);
		if ($tmp_price < 12000) { $banner_class = 'banner-low-price';	}
	}
	$car = '
		<div class="random"><!-- list car -->
		<a href="'.$link.'"><span class="'.$banner_class.'"></span><img width="216" height="140" src="'.$img_output.'" class="attachment-thumbnail_results" alt="'.$title.'" title="'.$title.'"></a> 
			<div class="result-detail-wrapper">  <!-- result detail wrapper -->
				<a href="'.$link.'" class="detail_btn">Details</a>
				<p><a href="'.$link.'" rel="bookmark" title="'.$title.'">'.$title.'</a> | Stock #: '.$stock_value.'</p>'.$compare.'
				<p><strong>Mileage: '.$mileage_value.'</strong></p>
				<p>'.$vehicle_body_style.' |  '.$vehicle_transmission.'<br>'.$vehicle_engine.' | '.$vehicle_exterior_color.'</p>
				<div class="result-price">'.get_cr_vehicle_price_style($post_id).'</div>
			</div> <!--   result detail wrapper ends -->
		 </div>
	';
	return $car;
}
?>