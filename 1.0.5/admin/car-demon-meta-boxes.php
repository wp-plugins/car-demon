<?php
// This file is being deprecated and replaced by information stored in the decode array

$val1 = array(
	"name" => "_exterior_color",
	"std" => "",
	"title" => "Exterior Color",
	"description" => "Add an Exterior Color to the car.");
$val2 = array(
	"name" => "_interior_color",
	"std" => "",
	"title" => "Interior Color",
	"description" => "Add an Interior Color to the car.");
$val3 = array(
	"name" => "_mileage",
	"std" => "",
	"title" => "Mileage",
	"description" => "Add mileage to the car.");
$val4 = array(
	"name" => "_price",
	"std" => "",
	"title" => "Price",
	"description" => "<b>This is the final price of the vehicle after discounts.</b>");
$val5 = array(
	"name" => "_stock",
	"std" => "",
	"title" => "Stock",
	"description" => "Add a stock number to the car.");
$val6 = array(
	"name" => "_vin",
	"std" => "",
	"title" => "VIN",
	"description" => "Add a VIN number to the car.");
$val7 = array(
	"name" => "_images",
	"std" => "",
	"title" => "Additional Images",
	"description" => "Add a list of additional images to the car.");
$val8 = array(
	"name" => "_transmission",
	"std" => "",
	"title" => "Transmission",
	"description" => "Add a transmission to the car.");
$val9 = array(
	"name" => "_cylinders",
	"std" => "",
	"title" => "Cylinders",
	"description" => "Add cylinders to the car.");
$val10 = array(
	"name" => "_doors",
	"std" => "",
	"title" => "Doors",
	"description" => "Add doors to the car.");
$val11 = array(
	"name" => "_engine",
	"std" => "",
	"title" => "Engine",
	"description" => "Add an engine to the car.");
$val12 = array(
	"name" => "_fuel_type",
	"std" => "",
	"title" => "Fuel Type",
	"description" => "Add fuel type to the car.");
$val13 = array(
	"name" => "_trim_level",
	"std" => "",
	"title" => "Trim Level",
	"description" => "Add trim level to the car.");
$val14 = array(
	"name" => "_warranty",
	"std" => "",
	"title" => "Warranty",
	"description" => "Add warranty to the car.");
$val15 = array(
	"name" => "_number_of_photos",
	"std" => "",
	"title" => "Number of Photos",
	"description" => "Add number of photos the car.");
$val16 = array(
	"name" => "_date_checked",
	"std" => "",
	"title" => "Date Checked",
	"description" => "Add the date car was last updated.");
$val17 = array(
	"name" => "_sales_status",
	"std" => "",
	"title" => "Sales Status",
	"description" => "Add sales status to the car.");
$val18 = array(
	"name" => "_msrp",
	"std" => "",
	"title" => "Retail Price",
	"description" => "Price before any discounts.");
$val19 = array(
	"name" => "_rebates",
	"std" => "",
	"title" => "Rebates",
	"description" => "Rebates for new vehicles.");
$val20 = array(
	"name" => "_discount",
	"std" => "",
	"title" => "Discount",
	"description" => "Discounts for vehicles.");

$new_meta_boxes = array(
//	"subtitle18" => $val18,
//	"subtitle19" => $val19,
//	"subtitle20" => $val20,
//	"subtitle4" => $val4,
//	"subtitle" => $val1,
//	"subtitle2" => $val2,
//	"subtitle3" => $val3,
//	"subtitle5" => $val5,
//	"subtitle6" => $val6,
	"subtitle7" => $val7,
//	"subtitle8" => $val8,
//	"subtitle9" => $val9,
//	"subtitle10" => $val10,
//	"subtitle11" => $val11,
//	"subtitle12" => $val12,
//	"subtitle13" => $val13,
//	"subtitle14" => $val14,
//	"subtitle15" => $val15,
//	"subtitle16" => $val16,
//	"subtitle17" => $val17
	);

function car_demon_new_meta_boxes() {
	global $post;
	$post_id = $post->ID;
	global $new_meta_boxes;
	foreach($new_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
		if($meta_box_value == "")
		$meta_box_value = $meta_box['std'];
		echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
		echo'<h2>'.$meta_box['title'].':</h2>';
		echo'<p><input type="text" name="'.$meta_box['name'].'_value" id="'.$meta_box['name'].'_value" onchange="update_vehicle_data(this, '.$post_id.');" value="'.$meta_box_value.'" size="55" /> ';
		echo'<label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p>';
	}
}

function car_demon_create_meta_box() {
	global $theme_name;
	if ( function_exists('add_meta_box') ) {
		add_meta_box( 'car-demon-new-meta-boxes', 'Car Details', 'car_demon_new_meta_boxes', 'cars_for_sale', 'normal', 'high' );
	}
}

function car_demon_save_postdata( $post_id ) {
	global $post, $new_meta_boxes;
	foreach($new_meta_boxes as $meta_box) {
		if (isset($_POST[$meta_box['name'].'_noncename'])) {
			$meta_box_name = $_POST[$meta_box['name'].'_noncename'];
		}
		else {
			$meta_box_name = '';
		}
		if ( !wp_verify_nonce( $meta_box_name, plugin_basename(__FILE__) )) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ))
			return $post_id;
		} 
		else {
			if ( !current_user_can( 'edit_post', $post_id ))
			return $post_id;
		}
		$data = $_POST[$meta_box['name'].'_value'];
		if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
			add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
		elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
			update_post_meta($post_id, $meta_box['name'].'_value', $data);
		elseif($data == "")
			delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
	}
}

//add_action('admin_menu', 'car_demon_create_meta_box');
//add_action('save_post', 'car_demon_save_postdata');
?>