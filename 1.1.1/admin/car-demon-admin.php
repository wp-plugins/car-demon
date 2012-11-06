<?php
add_action('save_post', 'max_save_quick_edit_data');
add_action('admin_footer', 'max_quick_edit_javascript');
add_filter('post_row_actions', 'max_expand_quick_edit_link', 10, 2);
add_action('restrict_manage_posts','restrict_listings_by_sold');
add_filter( 'parse_query', 'filter_by_sold' );

if (isset($_GET['page']) && $_GET['page'] == 'car_demon_settings_options') {
	add_action('admin_print_scripts', 'car_demon_admin_scripts');
	add_action('admin_print_styles', 'car_demon_admin_styles');
}

function car_demon_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('cd-upload', '/wp-content/plugins/car-demon/js/uploader.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('cd-upload');
}

function car_demon_admin_styles() {
	wp_enqueue_style('thickbox');
}

function restrict_listings_by_sold() {
    global $typenow;
    global $wp_query;
    if ($typenow=='cars_for_sale') {
		echo 'Sold <select name="sold" id="sold"><option value="">All</option><option value="no">No</option><option value="yes">Yes</option></select>';
		echo '&nbsp;Location <select name="vehicle_location" id="vehicle_location"><option value="">All</option>
			'.select_admin_locations().'
		</select>';
		echo '&nbsp;Condition <select name="vehicle_condition" id="vehicle_condition"><option value="">All</option><option value="new">New</option><option value="preowned">Used</option></select>';
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
	}
	else {
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
?>