<?php
function car_demon_query_search() {
	if ($_GET['car']) {
		add_filter( 'wp_title', 'car_demon_filter_search_title', 10, 3 );
		$order_by = '_price_value';
		$order_by_dir = 'ASC';
		if (isset($_GET['order_by'])) {
			$order_by = $_GET['order_by'];
		}
		if (isset($_GET['order_by_dir'])) {
			$order_by_dir = $_GET['order_by_dir'];
		}	
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		if (isset($_GET['search_dropdown_Min_price'])) {
			$min_price = $_GET['search_dropdown_Min_price'];
		} else {
			$min_price = '';
		}
		if (isset($_GET['search_dropdown_Max_price'])) {
			$max_price = $_GET['search_dropdown_Max_price'];
		} else {
			$max_price = '';
		}
		if ($_SESSION['car_demon_options']['show_sold'] != 'Yes') {
			$meta_query = array(
					array(
						'key' => 'sold',
						'value' => 'no',
						'compare' => '='
					)
				);
		}
		if (isset($_GET['stock'])) {
			if ($_GET['stock']) {
				$meta_query = array_merge($meta_query, array(array('key' => '_stock_value','value' => $_GET['stock'], 'compare' => '=', 'type' => 'text')));
			}
		}
		if (isset($_GET['search_dropdown_miles'])) {
			if ($_GET['search_dropdown_miles']) {
				$meta_query = array_merge($meta_query, array(array('key' => '_mileage_value','value' => $_GET['search_dropdown_miles'], 'compare' => '<', 'type' => 'numeric')));
			}
		}
		if (isset($_GET['search_dropdown_tran'])) {
			if ($_GET['search_dropdown_tran']) {
				$meta_query = array_merge($meta_query, array(array('key' => '_transmission_value','value' => $_GET['search_dropdown_tran'], 'compare' => '=', 'type' => 'text')));
			}
		}
		if ($max_price > 0) {
			if ($min_price == 0) { $min_price = 1; }
			$meta_query = array_merge($meta_query, array(array('key' => '_price_value','value' => array( $min_price, $max_price ), 'compare' => 'BETWEEN', 'type' => 'numeric')));
		} else {
			if ($min_price > 0) {
				$meta_query = array_merge($meta_query, array(array('key' => '_price_value','value' => $min_price, 'compare' => '>', 'type' => 'numeric')));
			}
		}
		$my_query = array(
				'post_type' => 'cars_for_sale',
				'is_paged' => true,
				'paged' => $paged,
				'posts_per_page' => 9,
				'meta_query' => $meta_query,
				'orderby' => 'meta_value_num',
				'meta_key' => $order_by,
				'order'    => $order_by_dir
			);
			if (isset($_GET['search_year'])) {
				if ($_GET['search_year']) {
					$my_query = array_merge ($my_query, array('vehicle_year' => $_GET['search_year']));
				}
			}
			if (isset($_GET['search_condition'])) {
				if ($_GET['search_condition']) {
					$my_query = array_merge ($my_query, array('vehicle_condition' => $_GET['search_condition']));
				}
			}
			if (isset($_GET['search_make'])) {
				if ($_GET['search_make']) {
					$my_query = array_merge ($my_query, array('vehicle_make' => $_GET['search_make']));
				}
			}
			if (isset($_GET['search_model'])) {
				if ($_GET['search_model']) {
					$my_query = array_merge ($my_query, array('vehicle_model' => $_GET['search_model']));
				}
			}
			if (isset($_GET['search_dropdown_body'])) {
				if ($_GET['search_dropdown_body']) {
					$my_query = array_merge ($my_query, array('vehicle_body_style' => $_GET['search_dropdown_body']));
				}
			}
		return $my_query;
	}
}

function car_demon_query_archive() {
	global $query_string;
	$order_by = '_price_value';
	$order_by_dir = 'ASC';
	if (isset($_GET['order_by'])) {
		$order_by = $_GET['order_by'];
	}
	else {
		$order_by = '';
	}
	if (isset($_GET['order_by_dir'])) {
		$order_by_dir = $_GET['order_by_dir'];
	}
	else {
		$order_by_dir = '';
	}
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	if (isset($_GET['search_dropdown_Min_price'])) {
		$min_price = $_GET['search_dropdown_Min_price'];
	}
	else {
		$min_price = '';
	}
	if (isset($_GET['search_dropdown_Max_price'])) {
		$max_price = $_GET['search_dropdown_Max_price'];
	}
	else {
		$max_price = '';
	}
	if ($_SESSION['car_demon_options']['show_sold'] != 'Yes') {
		$meta_query = array(
				array(
					'key' => 'sold',
					'value' => 'no',
					'compare' => '='
				)
			);
	}
	$my_query = array(
			'post_type' => 'cars_for_sale',
			'is_paged' => true,
			'paged' => $paged,
			'posts_per_page' => 9,
			'meta_query' => $meta_query,
			'orderby' => 'meta_value_num',
			'meta_key' => $order_by,
			'order'    => $order_by_dir
		);
	$my_query = wp_parse_args( $query_string, $my_query );
	return $my_query;
}

function car_demon_sorting($page_type = 'search') {
	$wpurl = site_url();
	$query_string = $_SERVER['QUERY_STRING'];
	$query_string = str_replace('&order_by_dir=desc', '', $query_string);
	$query_string = str_replace('&order_by_dir=asc', '', $query_string);
	$query_string = str_replace('&order_by=_price_value', '', $query_string);
	$query_string = str_replace('&order_by=_mileage_value', '', $query_string);
	$wpurl_img = $wpurl.'/wp-content/plugins/car-demon/theme-files/images/';
	if ($page_type == 'search') {
		$wpurl = $wpurl .'?'. $query_string;
	} else {
		$wpurl = '?'. $query_string;
	}
	$car_demon_sorting = __('Sort By:', 'car-demon');
	$sort_asc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="'.__('Sort Low to High', 'car-demon').'" /></a>&nbsp;';
	$sort_desc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="'.__('Sort High to Low', 'car-demon').'" /></a>';
		$car_demon_sorting .= '&nbsp;&nbsp;&nbsp;'.__('Price', 'car-demon').' '.$sort_asc_img.$sort_desc_img;
	$sort_asc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="'.__('Sort Low to High', 'car-demon').'" /></a>&nbsp;';
	$sort_desc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="'.__('Sort High to Low', 'car-demon').'" /></a>';
		$car_demon_sorting .= '&nbsp;&nbsp;&nbsp;'.__('Mileage', 'car-demon').' '.$sort_asc_img.$sort_desc_img;
	return $car_demon_sorting;
}

function car_demon_filter_search_title($title) {
	$title = str_replace('Page not found','Search Results', $title);
	return $title;
}
?>