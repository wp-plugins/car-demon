<?php
header("Content-Type: text/xml");
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
echo build_xml();
function build_xml() {
	global $wpdb;
	$query = "SELECT ID
		FROM $wpdb->posts wposts
			LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
		WHERE wposts.post_type='cars_for_sale'
			AND wpostmeta.meta_key = 'sold'
			AND wpostmeta.meta_value = 'no'";
	$total_cars = $wpdb->get_results(sprintf($query));
	foreach ($total_cars as $total_car) {
		$post_id =  $total_car->ID;
		$car_xml .= '<vehicle>';
		$car_xml .= '<id>'.$post_id.'</id>';
		$car_xml .= rwh_xml(get_post_meta($post_id, "_vin_value", true),'vin');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),'year');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),'make');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),'model');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),'condition');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),'body_style');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),'location');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_stock_value", true), 'stock');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_exterior_color_value", true), 'exterior_color');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_interior_color_value", true), 'interior_color');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_mileage_value", true), 'mileage');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_fuel_type_value", true), 'fuel_type');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_transmission_value", true), 'transmission');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_cylinders_value", true), 'cylinders');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_engine_value", true), 'engine');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_doors_value", true), 'doors');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_trim_value", true), 'trim');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_warranty_value", true), 'warranty');
		$car_pic = wp_get_attachment_thumb_url( get_my_post_thumbnail_id( $post_id ) );
		if (empty($car_pic)) {
			$car_demon_pluginpath = CAR_DEMON_PATH;
			$car_demon_pluginpath = str_replace('feeds','',$car_demon_pluginpath);
			$car_pic = $car_demon_pluginpath.'no_photo.gif';
		}
		$car_xml .= '<photo>'.$car_pic.'</photo>';
		$car_xml .= '</vehicle>';
	}
	$car_xml = '<inventorySummary>'.$car_xml.'</inventorySummary>';
	return $car_xml;
}
function rwh_xml($x,$y) {
	$new_string = '<'.$y.'>'.$x.'</'.$y.'>'.chr(13);
	return $new_string;
}
function get_my_post_thumbnail_id( $post_id = NULL ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	return get_post_meta( $post_id, '_thumbnail_id', true );
}
?>