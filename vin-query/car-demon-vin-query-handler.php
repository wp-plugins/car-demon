<?php
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/vin-query', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\vin-query', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$post_id = $_POST['post_id'];
if ($_GET['decode'] == "post") {
	$vin = $_POST['vin'];
	car_demon_get_vin_query($post_id, $vin);
}
if ($_GET['decode'] == "dashboard") {
	$vin = $_POST['vin'];
	$title = $_POST['title'];
	$stock = $_POST['stock'];
	global $current_user;
    get_currentuserinfo();
	$user_id = $current_user->ID;
	$post = array(
		'post_title' => $title,
		'post_status' => 'draft',
		'post_type' => 'cars_for_sale',
		'post_author' => $user_id,
		);
	$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
	$post_id = wp_insert_post($post);
	$car_demon_options = car_demon_options();
	if (!empty($car_demon_options['vinquery_id'])) {
		car_demon_get_vin_query($post_id, $vin);
	}
	update_post_meta($post_id, '_vin_value', $vin);
	if ($user_location) {
		wp_set_post_terms($post_id, $user_location, 'vehicle_location', false );
	}
	update_post_meta($post_id, '_stock_value', $stock);
	update_post_meta($post_id, 'sold', 'no');
	$blog_url = site_url();
	$url = $blog_url . '/wp-admin/post.php?post='.$post_id.'&action=edit';
	echo $url;
}
if ($_GET['decode'] == "decode_string") {
	$details = get_post_meta($post_id, 'decode_string', true);
	update_post_meta($post_id, 'decode_string', $details);
}
if ($_GET['decode'] == "update_data") {
	$fld = $_POST['fld'];
	if ($fld == 'vin') {
		$fld = '_vin_value';
	}
	$val = $_POST['val'];
	update_post_meta($post_id, $fld, $val);
}
if ($_GET['decode'] == 'update') {
	$fld = $_POST['fld'];
	$val = $_POST['val'];
	$vin_query_decode = get_post_meta($post_id, "decode_string", true);
	$vin_query_decode[$fld] = $_POST['val'];
	update_post_meta($post_id, 'decode_string', $vin_query_decode);	
	if ($fld == 'decoded_model_year') {
		wp_set_post_terms( $post_id, $val, 'vehicle_year', false );
	}
	if ($fld == 'decoded_make') {
		wp_set_post_terms( $post_id, $val, 'vehicle_make', false );
	}
	if ($fld == 'decoded_model') {
		wp_set_post_terms( $post_id, $val, 'vehicle_model', false );
	}
	if ($fld == 'decoded_transmission_long') {
		update_post_meta($post_id, '_transmission_value', $_POST['val']);	
	}
	if ($fld == 'decoded_engine_type') {
		update_post_meta($post_id, '_engine_value', $_POST['val']);	
	}
	if ($fld == 'decoded_trim_level') {
		update_post_meta($post_id, '_trim_value', $_POST['val']);	
	}
}
if ($_GET['decode'] == 'remove') {
	delete_post_meta($post_id, "decode_string");
	delete_post_meta($post_id, "decode_saved");
}
function car_demon_decode_new_vin($vin) {
	$does_vin_exist = does_vin_exist($vin);
	if ($does_vin_exist != 0) {
		$has_car_been_decoded = get_post_meta($does_vin_exist, "decode_results", true);
		if (empty($has_car_been_decoded)) {
			car_demon_decode_vin($post_id, $vin);
		}
		$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
		$rootpath = str_replace('wp-content/plugins/car-demons/','',$pluginpath);
		$this_post = get_post($does_vin_exist); 
		$title = $this_post->post_title;
		echo '<p><strong>This VIN number has been decoded;</strong></p>';
		echo '<p><strong>'.$title.'</strong></p>';
		echo '<p><a href="'. get_permalink( $does_vin_exist ) .'" target="new_win">View Vehicle on site</a></p>';
		echo '<p><a href="'. $rootpath .'wp-admin/post.php?post='.$does_vin_exist.'&action=edit&message=1">Edit This Vehicle</a></p>';
	}
	else {
// Decode vehicle, add to inventory
		echo add_vehicle_mini_form($vin);
	}
}

function car_demon_update_car_with_decode() {
	// Save post if not already saved
	// Set Post Title if not set
	// Set year, make, model, vin, condition, body style, location
	// Exterior Color, Interior Color
	//Transmission, Cylinders, Doors, Engine, Fuel Type, Trim Level, Warranty, Date Checked	
	
	// stock num, condition, mileage, 	
}
?>