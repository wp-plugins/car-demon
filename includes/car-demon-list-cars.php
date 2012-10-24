<?php
function car_demon_display_car_list($post_id) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('includes', '', $car_demon_pluginpath);
	$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
	$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
	$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
	$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
	$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
	$title = substr($title, 0, 19);
	$stock_value = get_post_meta($post_id, "_stock_value", true);
	$mileage_value = get_post_meta($post_id, "_mileage_value", true);
	$detail_output = '<span class="random_title">'.$title.'</span><br />';
	$detail_output .= '<span class="random_text">';
		$detail_output .= 'Condition: '.$vehicle_condition.'<br />';			
	$detail_output .= '</span>';
	$detail_output .= '<span class="random_text">';
		$detail_output .= 'Mileage: '.$mileage_value.'<br />';
	$detail_output .= '</span>';
	$detail_output .= '<span class="random_text">';
		$detail_output .= 'Stock#: '.$stock_value;
	$detail_output .= '</span>';
	if (isset($_SESSION['car_demon_options']['use_compare'])) {
		$use_compare = $_SESSION['car_demon_options']['use_compare'];
	}
	else {
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
		$compare_these = split(',',$compare_list);
		if (in_array($post_id,$compare_these)) {
			$in_compare = ' checked="checked"';
		}
		$detail_output .= '<span class="random_text_compare">';
			$detail_output .= '<input'.$in_compare.' id="compare_'.$post_id.'" type="checkbox" onclick="update_car('.$post_id.',this);" />&nbsp;<a href="" />Compare</a>';
		$detail_output .= '</span>';
	}
	$detail_output .= '<div style="height:110px;">';
		$detail_output .= get_vehicle_price($post_id);
	$detail_output .= '</div>';	
	$link = get_permalink($post_id);
	if (isset($_COOKIE["sales_code"])) {
		$link = $link .'?sales_code='.$_COOKIE["sales_code"];
	}
	$detail_output .= '<div class="random_text">';
		$detail_output .= '<a href="'.$link.'" class="search_btn" style="margin-left:20px;">View Details</a>';
	$detail_output .= '</div>';
	$img_output = "<div style='position:absolute;'><img title='Click for price on this ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='random_widget_image' width='180px' height='135px' src='";
	$img_output .= wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
	$img_output .= "' /></div>";
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
	$car = '
		<div class="random" style="float:left;width:210px;height:385px;">
			<div class="random_img" style="height:137px;">
				<a href="'.$link.'">
					<img style="margin-left:15px;" src="'. $car_demon_pluginpath .'theme-files/images/'.$ribbon.'.png" width="76" height="76" alt="New Ribbon" id="ribbon">
					<img style="margin-left:15px;" src="'. $car_demon_pluginpath .'theme-files/images/look_close.png" width="188" height="143" alt="New Ribbon" id="look_close" class="look_close">
					'.$img_output.'
				</a>
			</div>
			<div class="random_description" style="margin-top:5px;">
				'.$detail_output.'
			</div>
		</div>';
		
	return $car;
}
?>