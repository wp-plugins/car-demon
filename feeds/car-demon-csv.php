<?php
//= using text/plain rather than text/csv so it can be displayed in the browser
header("Content-Type: text/plain");
// Using ob_start to keep extra line from being added
ob_start();
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
} else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/feeds', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
} else {
	$newPath = str_replace('wp-content\plugins\car-demon\feeds', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
//= Example
//= http://cardemons.com/wp-content/plugins/car-demon/feeds/car-demon-csv.php
ob_end_clean();
echo build_csv();
function build_csv() {
	if (is_multisite()) {
		if (isset($_GET['dealer_id'])) {
			$dealer_id = $_GET['dealer_id'];
		} else {
			return;
		}
		global $wpdb;
		$wpdb->set_blog_id($dealer_id);
		$wpdb->set_prefix($wpdb->base_prefix);
		$prefix = $wpdb->prefix;
	} else {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$dealer_id = '1';
	}
	$query = "SELECT ID, post_content
		FROM ".$prefix."posts wposts
			LEFT JOIN ".$prefix."postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
		WHERE wposts.post_type='cars_for_sale'
			AND wpostmeta.meta_key = 'sold'
			AND wpostmeta.meta_value = 'no'";
	$car_csv = '';
	$car_csv .= 'dealerId,dealerName,vehicleId,stockId,vin,year,make,model,trim,engineType,transmission,bodyStyle,used_new,certified,price,mileage,color,interior color,dealer notes,description,equipment list,photoUrl list';
	$car_csv .= chr(13).chr(10);
	$total_cars = $wpdb->get_results($query);
	foreach ($total_cars as $total_car) {
		$post_id =  $total_car->ID;
		if (empty($post_id)) {
			$cnt = $cnt + 1;
			continue;
		}
		$car_csv .= '"'.trim($dealer_id).'",';
		$location = trim(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )));
		$location_name = get_bloginfo('title');
		$car_csv .= '"'.trim($location_name).'",';
		$car_csv .= '"'.$post_id.'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_stock_value", true)).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_vin_value", true)).'",';
		$car_csv .= '"'.trim(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ))).'",';
		$car_csv .= '"'.trim(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ))).'",';
		$car_csv .= '"'.trim(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ))).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_trim_value", true)).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_engine_value", true)).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_transmission_value", true)).'",';
		$car_csv .= '"'.trim(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' ))).'",';
		$car_csv .= '"'.trim(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ))).'",';
		$car_csv .= '"",';
//	PRICE
		$car_csv .= '"'.trim(get_post_meta($post_id, "_price_value", true)).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_mileage_value", true)).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_exterior_color_value", true)).'",';
		$car_csv .= '"'.trim(get_post_meta($post_id, "_interior_color_value", true)).'",';
//	DEALER NOTES

		$car_csv .= '"",';
		$description = $total_car->post_content;
		$description = str_replace(chr(10), '<br />', $description);
		$description = str_replace(chr(13), '<br />', $description);		
		$car_csv .= '"'.trim($description).'",';
//		$car_csv .= '"'.trim(strip_tags($description)).'",';
		$options = '';
		$option_array_list = strip_tags(get_post_meta($post_id, '_vehicle_options', true));

		$option_array = explode(',',$option_array_list);
		foreach($option_array as $option_item) {
			$label = str_replace('_',' ',$option_item);
			$label = strtoupper($label);
			$options .= trim($label).',';
		}
		$decode_saved = get_post_meta($post_id, 'decode_saved', true);

		if (!empty($decode_saved)) {
			if (empty($options)) {
				$options = $decode_saved;
			} else {
				$options .= ','.$decode_saved;
			}
		} else {
			$options .= '@@';		
		}
		$options = str_replace(',@@','',$options);
		$options = str_replace('@@','',$options);
		
//		$options = $option_array;
		$car_csv .= '"'.$options.'",';
		$car_pic = wp_get_attachment_thumb_url( get_my_post_thumbnail_id( $post_id ) );
		$car_pic_list = get_post_meta($post_id, "_images_value", true);
		$car_pic_list = str_replace(chr(10), '', $car_pic_list);
		$car_pic_list = str_replace(chr(11), '', $car_pic_list);
		$car_pic_list = str_replace(chr(13), '', $car_pic_list);
		if (empty($car_pic)) {
			$car_demon_pluginpath = CAR_DEMON_PATH;
			$car_demon_pluginpath = str_replace('feeds','',$car_demon_pluginpath);
			$car_pic = $car_demon_pluginpath.'no_photo.gif';
		}
		$car_csv .= '"'.$car_pic.', '.$car_pic_list.'"';
		$car_csv .= chr(13).chr(10);
		$cnt = $cnt + 1;
	}
	return $car_csv;
}
function get_my_post_thumbnail_id( $post_id = NULL ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	return get_post_meta( $post_id, '_thumbnail_id', true );
}
?>