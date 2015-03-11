<?php
function car_demon_query_search() {
	if (isset($_SESSION['car_demon_options']['cars_per_page'])) {
		$cars_per_page = $_SESSION['car_demon_options']['cars_per_page'];
	} else {
		$cars_per_page = 9;
	}
	if (isset($_GET['car'])) {
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
		// Search decode field
		if (isset($_GET['criteria'])) {
			if ($_GET['criteria']) {
				if (strpos($_GET['criteria'], ',')) {
					$criteria_array = explode(',', $_GET['criteria']);
					foreach($criteria_array as $search_criteria) {
						$meta_query = array_merge($meta_query, array(array('key' => 'decode_string','value' => $search_criteria, 'compare' => 'LIKE', 'type' => 'text')));
					}
				} else {
					$meta_query = array_merge($meta_query, array(array('key' => 'decode_string','value' => $_GET['criteria'], 'compare' => 'LIKE', 'type' => 'text')));
				}
			}
		}
		if (isset($_GET['search_location'])) {
			$search_location = $_GET['search_location'];
			$my_query = array(
					'post_type' => 'cars_for_sale',
					'is_paged' => true,
					'paged' => $paged,
					'posts_per_page' => $cars_per_page,
					'meta_query' => $meta_query,
					'orderby' => 'meta_value_num',
					'meta_key' => $order_by,
					'order'    => $order_by_dir,
					'taxonomy' =>'vehicle_location',
					'term' => $search_location
				);
		} else {
			$my_query = array(
					'post_type' => 'cars_for_sale',
					'is_paged' => true,
					'paged' => $paged,
					'posts_per_page' => $cars_per_page,
					'meta_query' => $meta_query,
					'orderby' => 'meta_value_num',
					'meta_key' => $order_by,
					'order'    => $order_by_dir
				);
			$search_location = '';
		}
		$my_query = array(
				'post_type' => 'cars_for_sale',
				'is_paged' => true,
				'paged' => $paged,
				'posts_per_page' => $cars_per_page,
				'meta_query' => $meta_query,
				'orderby' => 'meta_value_num',
				'meta_key' => $order_by,
				'order'    => $order_by_dir,
				'taxonomy' =>'vehicle_location',
				'term' => $search_location
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
		$my_query = apply_filters('car_demon_query_filter', $my_query );
		return $my_query;
	}
}
function car_demon_query_archive() {
	global $query_string;
	if (isset($_SESSION['car_demon_options']['cars_per_page'])) {
		$cars_per_page = $_SESSION['car_demon_options']['cars_per_page'];
	} else {
		$cars_per_page = 9;
	}
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
			'posts_per_page' => $cars_per_page,
			'meta_query' => $meta_query,
			'orderby' => 'meta_value_num',
			'meta_key' => $order_by,
			'order'    => $order_by_dir
		);
	$my_query = wp_parse_args( $query_string, $my_query );
	$my_query = apply_filters('car_demon_query_filter', $my_query );
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
	$car_demon_sorting = '';
	$do_sort = 1;
	$sort_price = 1;
	$sort_miles = 1;
	if (isset($_SESSION['car_demon_options']['do_sort'])) {
		if ($_SESSION['car_demon_options']['do_sort'] == 'No') {
			$do_sort = 0;
		}
	}
	if ($do_sort == 1) {
		$car_demon_sorting = __('Sort By:', 'car-demon');
		if (isset($_SESSION['car_demon_options']['sort_price'])) {
			if ($_SESSION['car_demon_options']['sort_price'] == 'No') {
				$sort_price = 0;
			}
		}
		if ($sort_price == 1) {
			$sort_asc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="'.__('Sort Low to High', 'car-demon').'" /></a>&nbsp;';
			$sort_desc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="'.__('Sort High to Low', 'car-demon').'" /></a>';
			$car_demon_sorting .= '&nbsp;&nbsp;&nbsp;'.__('Price', 'car-demon').' '.$sort_asc_img.$sort_desc_img;
		}
		if (isset($_SESSION['car_demon_options']['sort_miles'])) {
			if ($_SESSION['car_demon_options']['sort_miles'] == 'No') {
				$sort_miles = 0;
			}
		}
		if ($sort_miles == 1) {
			$sort_asc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="'.__('Sort Low to High', 'car-demon').'" /></a>&nbsp;';
			$sort_desc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="'.__('Sort High to Low', 'car-demon').'" /></a>';
				$car_demon_sorting .= '&nbsp;&nbsp;&nbsp;'.__('Mileage', 'car-demon').' '.$sort_asc_img.$sort_desc_img;
		}
	}
	if (isset($_SESSION['car_demon_options']['drop_down_sort'])) {
		if ($_SESSION['car_demon_options']['drop_down_sort'] == 'Yes') {
			if (isset($_GET['order_by'])) {
				if ($_GET['order_by'] == '_mileage_value') {
					$select_price = '';
					$select_mileage = ' selected';
				} else {
					$select_price = ' selected';
					$select_mileage = '';
				}
			} else {
				$select_price = ' selected';
				$select_mileage = '';
			}
			if (isset($_GET['order_by_dir'])) {
				if ($_GET['order_by_dir'] == 'asc') {
					$select_desc = '';
					$select_asc = ' selected';
				} else {
					$select_desc = ' selected';
					$select_asc = '';	
				}
			} else {
				$select_desc = ' selected';
				$select_asc = '';	
			}
			parse_str($query_string, $output);
			$hidden_items = '';
			foreach ( $output as $key => $value ) {
				if (!empty($value)) {
					$hidden_items .= '<input type="hidden" value="'.$value.'" name="'.$key.'" />';
				}
			}
			$car_demon_sorting = '<form id="frm_cd_sort" name="frm_cd_sort" action="" method="get">
									'.$hidden_items.'
									<span id="cd_sort_by_label" class="cd_sort_by_label">'.__('Sort By: ','car-demon').'</span>
									<select id="order_by" name="order_by" class="cd_order_by" onchange="document.forms[\'frm_cd_sort\'].submit();">
										<option value="_price_value"'.$select_price.'>'.__('Price', 'car-demon').'</option>
										<option value="_mileage_value"'.$select_mileage.'>'.__('Mileage', 'car-demon').'</option>
									</select>';
			$car_demon_sorting .= '&nbsp;<select id="order_by_dir" name="order_by_dir" class="cd_order_by_dir" onchange="document.forms[\'frm_cd_sort\'].submit();">
										<option value="desc"'.$select_desc.'>'.__('Desc', 'car-demon').'</option>
										<option value="asc"'.$select_asc.'>'.__('Asc', 'car-demon').'</option>
									</select></form>';
		}
	}
	$car_demon_sorting = apply_filters('car_demon_sort_filter', $car_demon_sorting );
	return $car_demon_sorting;
}
function car_demon_filter_search_title($title) {
	$title = str_replace('Page not found','Search Results', $title);
	return $title;
}
?>