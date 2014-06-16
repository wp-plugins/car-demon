<?php
function car_demon_display_car_list($post_id) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('includes', '', $car_demon_pluginpath);
	$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
	$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
	$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
	$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
	$title = get_car_title($post_id);
	$stock_value = get_post_meta($post_id, "_stock_value", true);
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();
	$mileage_value = get_post_meta($post_id, "_mileage_value", true);
	$detail_output = '<span class="random_title">'.$title.'</span><br />';
	if ($show_hide['condition'] != true) {
		$detail_output .= '<span class="random_text">';
			$detail_output .= $field_labels['condition'].' '.$vehicle_condition.'<br />';			
		$detail_output .= '</span>';
	}
	if ($show_hide['mileage'] != true) {
		$detail_output .= '<span class="random_text">';
			$detail_output .= $field_labels['mileage'].' '.$mileage_value.'<br />';
		$detail_output .= '</span>';
	}
	if ($show_hide['stock_number'] != true) {
		$detail_output .= '<span class="random_text">';
			$detail_output .= $field_labels['stock_number'].' '.$stock_value;
		$detail_output .= '</span>';
	}
	if (isset($_SESSION['car_demon_options']['use_compare'])) {
		$use_compare = $_SESSION['car_demon_options']['use_compare'];
	} else {
		$use_compare = '';
	}
	if ($use_compare == 'Yes') {
		$in_compare = '';
		if (isset($_SESSION['car_demon_compare'])) {
			$compare_list = $_SESSION['car_demon_compare'];
		}
		else {
			$compare_list = '';
		}
		$compare_these = explode(',',$compare_list);
		if (in_array($post_id,$compare_these)) {
			$in_compare = ' checked="checked"';
		}
		$detail_output .= '<span class="random_text_compare">';
			$detail_output .= '<input'.$in_compare.' id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />&nbsp;<a href="" />Compare</a>';
		$detail_output .= '</span>';
	}
	$detail_output .= '<div class="inventory_price_box">';
		$detail_output .= get_vehicle_price($post_id);
	$detail_output .= '</div>';	
	$link = get_permalink($post_id);
	if (isset($_COOKIE["sales_code"])) {
		$link = $link .'?sales_code='.$_COOKIE["sales_code"];
	}
	$detail_output .= '<div class="random_text">';
		$detail_output .= '<a href="'.$link.'" class="search_btn inventory_btn">'.__('View Details', 'car-demon').'</a>';
	$detail_output .= '</div>';
	$img_output = "<div class='inventory_photo_box'><img title='".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='random_widget_image inventory_photo_box' src='";
	$img_output .= wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
	$img_output .= "' /></div>";
	$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
	if (empty($ribbon)) {
		$ribbon = 'no-ribbon';
	}
	if ($ribbon != 'custom_ribbon') {
		$ribbon = str_replace('_', '-', $ribbon);
		$current_ribbon = '<img class="inventory_ribbon" src="'. $car_demon_pluginpath .'theme-files/images/ribbon-'.$ribbon.'.png" width="76" height="76" id="ribbon">';
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
	$car = '
		<div class="random inventory_item">
			<div class="random_img inventory_img">
				<a href="'.$link.'">
					'.$current_ribbon.'
					<img class="inventory_lookup" src="'. $car_demon_pluginpath .'theme-files/images/look_close.png" width="188" height="143" id="look_close" class="look_close">
					'.$img_output.'
				</a>
			</div>
			<div class="random_description inventory_description">
				'.$detail_output.'
			</div>
		</div>';
	return $car;
}
function car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, $size) {
	if (strpos($current_ribbon, 'no-ribbon')) {
		$car_demon_pluginpath = CAR_DEMON_PATH;
		$car_demon_pluginpath = str_replace('includes', '', $car_demon_pluginpath);
		$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
		$ribbon = 'ribbon-just-added';
		$ribbon = 'ribbon-great-deal';
		if ($vehicle_condition == 'New') {
			$ribbon = 'ribbon-new';
		}
		else {
			$mileage_value = get_post_meta($post_id, "_mileage_value", true);
			if ($mileage_value < 60000) { $ribbon = 'ribbon-low-miles';	}
			$tmp_price = get_post_meta($post_id, "_price_value", true);
			if ($tmp_price < 12000) { $ribbon = 'ribbon-low-price';	}
		}
		$custom_ribbon_file = $car_demon_pluginpath .'theme-files/images/'.$ribbon.'.png';
		if ($size == '76') {
			$current_ribbon = '<img class="inventory_ribbon" src="'.$custom_ribbon_file.'" width="'.$size.'" height="'.$size.'" id="ribbon">';
		} else {
			$current_ribbon = '<img src="'.$custom_ribbon_file.'" width="'.$size.'" height="'.$size.'" id="ribbon">';
		}
	}
	$current_ribbon = apply_filters('car_demon_dynamic_ribbon_hook', $current_ribbon, $post_id, $size);
	return $current_ribbon;
}
?>