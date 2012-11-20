<?php
header("Content-Type: text/html");
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/feeds', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\feeds', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}

echo build_csv();

function build_csv() {
	global $wpdb;
	$query = "SELECT ID, post_content
		FROM $wpdb->posts wposts
			LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
		WHERE wposts.post_type='cars_for_sale'
			AND wpostmeta.meta_key = 'sold'
			AND wpostmeta.meta_value = 'no'";
//	$car_csv .= 'dealerId,dealerName,stockId,vin,year,make,model,trim,engineType,transmission,bodyStyle,used_new,certified,price,mileage,color,interior color,dealer notes,equipment list,photoUrl list'.chr(13).chr(10).chr(11);
	$car_csv .= 'dealerId,dealerName,stockId,vin,year,make,model,trim,engineType,transmission,bodyStyle,used_new,certified,price,mileage,color,interior color,dealer notes,equipment list,photoUrl list';
	$car_csv .= chr(13).chr(10);
	$total_cars = $wpdb->get_results(sprintf($query));
	foreach ($total_cars as $total_car) {
		$car_options = $total_car->post_content;
		$car_csv .= '"'.get_post_meta($post_id, "_stock_value", true).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_vin_value", true).'",';
		$car_csv .= '"'.strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )).'",';
		$car_csv .= '"'.strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )).'",';
		$car_csv .= '"'.strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_trim_value", true).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_engine_value", true).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_transmission_value", true).'",';
		$car_csv .= '"'.strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )).'",';
		$car_csv .= '"'.strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )).'",';
		$car_csv .= '"",';
//	PRICE
		$car_csv .= '"'.get_post_meta($post_id, "_price_value", true).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_mileage_value", true).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_exterior_color_value", true).'",';
		$car_csv .= '"'.get_post_meta($post_id, "_interior_color_value", true).'",';
//	DEALER NOTES
		$location = strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' ));
		$car_csv .= '"",';
		$car_options = str_replace(chr(10), '|', $car_options);
		$car_options = str_replace(chr(11), '|', $car_options);
		$car_options = str_replace(chr(13), '|', $car_options);
		$car_options = str_replace('||', '|', $car_options);
		$car_options = str_replace('||', '|', $car_options);
		$car_options = str_replace('Code|', '', $car_options);
		$car_options = str_replace('Description|', '', $car_options);
		$car_options = str_replace('No Dealer Installed Equipment Available	|', '', $car_options);
		$car_options = str_replace('Dealer Installed Equipment|', '', $car_options);
		$car_options = str_replace('DESCRIPTION NOT AVAILABLE|', '', $car_options);
		$car_options = str_replace('Standard Equipment|', '', $car_options);
		$car_options = str_replace('Vehicle Option - All|', '', $car_options);
		$car_options = str_replace('||', '|', $car_options);
		$car_options = str_replace('||', '|', $car_options);
		$car_options = str_replace('| |', '', $car_options);
		$car_csv .= '"'.$car_options.'",';
		$car_pic = wp_get_attachment_thumb_url( get_my_post_thumbnail_id( $post_id ) );
		$car_pic_list = get_post_meta($post_id, "_images_value", true);
		$car_pic_list = str_replace(chr(10), '', $car_pic_list);
		$car_pic_list = str_replace(chr(11), '', $car_pic_list);
		$car_pic_list = str_replace(chr(13), '', $car_pic_list);
		if (empty($car_pic)) {
			$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
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