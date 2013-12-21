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
	$post_id = $_GET['post_id'];
		if (empty($post_id)) {
			if ($_GET['vin']) {
				$post_id = get_post_from_vin($_GET['vin']);
			}
			if ($_GET['stock_number']) {
				$post_id = get_post_from_stock($_GET['stock_number']);
			}		
		}
		$post_content = get_post($post_id); 
		$content = $post_content->post_content;
		$car_xml .= '<vehicleSummary><vehicle>';
		$car_xml .= '<car_info><![CDATA['.$content.']]></car_info>';
		$car_xml .= rwh_xml(get_post_meta($post_id, "_vin_value", true),'vin');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),'year');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),'make');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),'model');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),'condition');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),'body_style');
		$car_xml .= rwh_xml(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),'location');
		$car_xml .= rwh_xml(get_post_meta($post_id, "_stock_value", true), 'stock_num');
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
		if (function_exists(attachments_get_attachments)) {
			$attachments = attachments_get_attachments($post_id);
	
			$total_attachments = count($attachments);
			if( $total_attachments > 0 ) {
				$car_xml .= '<pictures>';
				for ($i=0; $i < $total_attachments; $i++) {
				  $car_xml .= '<picture><url>' . $attachments[$i]['location'] . '</url></picture>';
				}
				$car_xml .= '</pictures>';
			}
			$car_pic = wp_get_attachment_thumb_url( get_my_post_thumbnail_id_detail( $post_id ) );
		}
		$car_pic = str_replace('-150x150', '', $car_pic);
		if (empty($car_pic)) {
			$car_demon_pluginpath = CAR_DEMON_PATH;
			$car_demon_pluginpath = str_replace('feeds','',$car_demon_pluginpath);
			$car_pic = $car_demon_pluginpath.'no_photo.gif';
		}
		$car_xml .= '<photo>'.$car_pic.'</photo>';
		$car_xml .= '</vehicle></vehicleSummary>';
	return $car_xml;
}
function rwh_xml($x,$y) {
	$x = trim($x);
	if (!empty($x)) {
		$new_string = '<'.$y.'>'.$x.'</'.$y.'>'.chr(13);
	} else {
		$new_string = '<'.$y.'> </'.$y.'>'.chr(13);
	}
	return $new_string;
}
function get_my_post_thumbnail_id_detail( $post_id = NULL ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	$my_pic = get_post_meta( $post_id, '_thumbnail_id', true );
	return $my_pic;
}
function get_post_from_vin($vin) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$post_id = '';
	$sql = 'SELECT '.$prefix.'posts.ID, '.$prefix.'postmeta.meta_key, '.$prefix.'postmeta.meta_value
		FROM '.$prefix.'posts LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'posts.ID = '.$prefix.'postmeta.post_id
		WHERE (('.$prefix.'postmeta.meta_key="_vin_value") AND ('.$prefix.'postmeta.meta_value="'.$vin.'"))';
	$cars = $wpdb->get_results($sql);
	if ($cars) {
		foreach ($cars as $car) {
			$post_id = $car->ID;
		}
	}
	return $post_id;
}
function get_post_from_stock($stock) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$post_id = '';
	$sql = 'SELECT '.$prefix.'posts.ID, '.$prefix.'postmeta.meta_key, '.$prefix.'postmeta.meta_value
		FROM '.$prefix.'posts LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'posts.ID = '.$prefix.'postmeta.post_id
		WHERE (('.$prefix.'postmeta.meta_key="_stock_value") AND ('.$prefix.'postmeta.meta_value="'.$stock.'"))';
	$cars = $wpdb->get_results($sql);
	if ($cars) {
		foreach ($cars as $car) {
			$post_id = $car->ID;
		}
	}
	return $post_id;
}
?>