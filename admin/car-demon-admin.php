<?php
if (is_admin()) {
	add_action('save_post', 'max_save_quick_edit_data');
	add_action('admin_footer', 'max_quick_edit_javascript');
	add_filter('post_row_actions', 'max_expand_quick_edit_link', 10, 2);
	add_action('restrict_manage_posts','restrict_listings_by_sold');
	add_filter( 'parse_query', 'filter_by_sold' );
//	add_action( 'add_meta_boxes', 'start_admin_gallery_box' );
}
if (isset($_GET['page']) && $_GET['page'] == 'car_demon_settings_options') {
	add_action('admin_print_scripts', 'car_demon_admin_scripts');
	add_action('admin_print_styles', 'car_demon_admin_styles');
}
function car_demon_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('cd-upload', '/wp-content/plugins/car-demon/theme-files/js/uploader.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('cd-upload');
}
function car_demon_admin_styles() {
	wp_enqueue_style('thickbox');
}
function restrict_listings_by_sold() {
    global $typenow;
    global $wp_query;
    if ($typenow=='cars_for_sale') {
		echo '<div class="cd_admin_filter"><div class="cd_admin_filter_label">Sold</div><div class="cd_admin_filter_field"><select name="sold" id="sold"><option value="">All</option><option value="no">No</option><option value="yes">Yes</option></select></div></div>';
		echo '<div class="cd_admin_filter"><div class="cd_admin_filter_label">Location</div><div class="cd_admin_filter_field"><select name="vehicle_location" id="vehicle_location"><option value="">All</option>
			'.select_admin_locations().'
		</select></div></div>';
		echo '<div class="cd_admin_filter"><div class="cd_admin_filter_label">Condition</div><div class="cd_admin_filter_field"><select name="vehicle_condition" id="vehicle_condition"><option value="">All</option><option value="new">New</option><option value="preowned">Used</option></select></div></div>';
	}
}
function filter_by_sold($query) {
	global $pagenow;
	$both = 0;
	if (isset($_GET['post_type'])) {
		if (is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='cars_for_sale')  {
			if (isset($_GET['sold'])) {
				if ($_GET['sold'] != '') {
					set_query_var( 'meta_query', array( array( 'key' => 'sold', 'value' => $_GET['sold'] ) ) );
					$both = 1;
				}
			}
		}
	}
}
function select_admin_locations() {
	$html = '';
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'hide_empty'		 => 0,
		'taxonomy'           => 'vehicle_location'
		);
	$locations = get_categories( $args );
	$cnt = 0;
	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$cnt = $cnt + 1;
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$location_name_list = 'Default'.$location_name_list;
		$cnt = 1;
	} else {
		$location_list = '@'.$location_list;
		$location_list = str_replace("@,","", $location_list);
		$location_list = str_replace("@","", $location_list);
		$location_name_list = '@'.$location_name_list;
		$location_name_list = str_replace("@,","", $location_name_list);
		$location_name_list = str_replace("@","", $location_name_list);
	}
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;
	foreach ($location_list_array as $current_location) {
		$html .= '<option value="'.$current_location.'">'.$location_name_list_array[$x].'</option>';
		$x = $x + 1;
	}
	return $html;
}
/*===============
Admin Photo Area
===============*/
function start_admin_gallery_box() {
	add_meta_box('vehicle-photos', 'Vehicle Photos', 'car_demon_vehicle_photo_gallery', 'cars_for_sale', 'normal', 'high');
}
function car_demon_vehicle_photo_gallery($post) {
	$post_id = $post->ID;
	echo admin_car_photos($post_id);
}
function admin_car_photos($post_id) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$this_car = '<div>';
		$this_car .= '<div class="car_detail_div">';
			$this_car .= '<div class="car_main_photo_box">';
				$this_car .= '<div id="main_thumb"><img onerror="ImgError(this, \'no_photo.gif\');" id="'.$post_id.'_pic" name="'.$post_id.'_pic" class="car_demon_main_photo" width="350px" src="';
				$main_guid = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
				$this_car .= $main_guid;
				$this_car .= '" /></div>';
			$this_car .= '</div>';
			$this_car .= '<div class="car_details_box">';
			$this_car .= '</div>';
		$this_car .= '</div>';
		// Thumbnails
		$thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
		$this_car .= '<div class="nohor" id="car_demon_thumbs">';
		$cnt = 0;
		$photo_array = '<img class="car_demon_thumbs" onClick=\'MM_swapImage("'.$post_id.'_pic","","'.trim($main_guid).'",1);active_img('.$cnt.')\' src="'.trim($main_guid).'" width="53" />';
		$this_car .= $photo_array;
		foreach($thumbnails as $thumbnail) {
			$guid = $thumbnail->guid;
			if (!empty($guid)) {
				if ($main_guid != $guid) {
					$cnt = $cnt + 1;
					$photo_array = '<img src="'.$guid.'" class="car_demon_thumbs" width="53" />';
					$this_car .= $photo_array;
				}
			}
		}
		$this_car .= '</div>';
		// End Thumbnails
	$this_car .= '</div>';
	$total_pics = $cnt;
	$html = $this_car;
	return $html;
}
?>