<?php
function car_demon_search_years() {
	$search_years = '';
	$search_years .= '<select class="search_year" name="search_year" id="search_year">';
		$search_years .= '<option value="">'.__('Any', 'car-demon').'</option>';
		$search_years .= car_demon_get_my_tax('vehicle_year',0);
	$search_years .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_years;
}
function car_demon_search_makes() {
	$search_makes = '';
	$search_makes .= '<select class="search_make" name="search_make" id="search_make">';
		$search_makes .= '<option value="">'.__('ALL MAKES', 'car-demon').'</option>';
		$search_makes .= car_demon_get_my_tax('vehicle_make',1);
	$search_makes .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_makes;
}
function car_demon_search_models() {
	$search_models = '';
	$search_models .= '<select class="search_model" name="search_model" id="search_model" onchange="car_demon_fix_model();">';
		$search_models .= '<option value="">'.__('ALL MODELS', 'car-demon').'</option>';
		$search_models .= car_demon_get_my_tax('vehicle_model',2);
	$search_models .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_models;
}
function car_demon_search_condition() {
	$search_condition = '<select class="search_condition" name="search_condition" id="search_condition">';
		$search_condition .= '<option value="">'.__('ALL', 'car-demon').'</option>';
		$search_condition .= car_demon_get_my_tax('vehicle_condition', 3);
	$search_condition .= '</select>';
	return $search_condition;
}
function car_demon_get_my_tax($taxonomy,$val) {
	$my_tag_list = '';
	$this_val = '';
	if ($val == 1) {
		if (isset($_GET['search_make'])) {
			$search_make = $_GET['search_make'];
		}
		else {
			$search_make = '';
		}
		$this_val = $search_make;
		$this_val = str_replace(',', '', $this_val);
	}
	if ($val == 2) {
		if (isset($_GET['search_model'])) {
			$search_model = $_GET['search_model'];
		}
		else {
			$search_model = '';
		} 
		$this_val = $search_model;
	}
	if ($val == 3) {
		if (isset($_GET['search_condition'])) {
			$search_condition = $_GET['search_condition'];
		}
		else {
			$search_condition = '';
		} 
		$this_val = $search_condition;
	}
	$post_type = 'cars_for_sale';
	$args = array(
		'smallest'  => 8, 
		'largest'   => 22,
		'unit'      => 'pt', 
		'number'    => 95,  
		'format'    => 'array',
		'separator' => ' - ',
		'orderby'   => 'name', 
		'order'     => 'ASC',
		'exclude'   => '', 
		'include'   => '', 
		'link'      => 'view', 
		'taxonomy'  => $taxonomy, 
		'echo'      => true );
	$my_tags = wp_tag_cloud( $args );
	if ($my_tags) {
		foreach($my_tags as $my_tag) {
			$my_tag2 = $my_tag;
			$my_tag = str_replace('title=','<title>',$my_tag);
			$my_tag = str_replace('style=','<style>',$my_tag);
			preg_match_all("~<title>([^<]*)<style>~",$my_tag,$bad_things);
			$bad_thing = $bad_things[1][0];
			$my_tag2 = str_replace('</a>','[2]',$my_tag2);
			$my_tag2 = str_replace('>','[1]',$my_tag2);
			$my_tag2 = str_replace('<','',$my_tag2);
			$my_tag2 = str_replace('[1]','<1>',$my_tag2);
			$my_tag2 = str_replace('[2]','<2>',$my_tag2);
			preg_match_all("~<1>([^<]*)<2>~",$my_tag2,$my_tag_names);
			$my_tag_name = $my_tag_names[1][0];
			$count_cars = car_demon_count_active_tax_items($my_tag_name, $post_type, $taxonomy);
			if (empty($count_cars)) {
				$count_cars = car_demon_count_active_items($my_tag_name.'-2', $post_type, $taxonomy);
			}
			if (!empty($count_cars)) {
				if ($val == 1) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_make' );
					$my_slug = $my_term->slug;
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value=",' . $my_slug .'"'.$select.'>';
				}
				elseif ($val == 2) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_model' );
					$my_slug = $my_term->slug;
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value="' . $my_slug . '"'.$select.'>';			
				}
				elseif ($val == 3) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_condition' );
					$my_slug = $my_term->slug;
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value="' . $my_slug . '"'.$select.'>';			
				}
				else {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_year' );
					if ($my_term) {
						$my_slug = $my_term->slug;
					} else {
						$my_slug = '';
					}
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value="' . $my_slug . '"'.$select.'>';
				}
				$my_tag_list .= $my_tag_name . ' (' . $count_cars . ')';
				$my_tag_name .= '</option>';
			}
			else {
				if ($val == 2) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_model' );
					$my_slug = $my_term->slug;
				}
			}
			$total_count = ' - '.$count_cars;
		}
	}
	return $my_tag_list;
}
function car_demon_search_price($size) {
	if (isset($_GET['search_dropdown_'.$size.'_price'])) {
		$price = $_GET['search_dropdown_'.$size.'_price'];
	} else {
		$price = '';
	}
	if (isset($_SESSION['car_demon_options']['currency_symbol'])) {
		$currency_symbol = $_SESSION['car_demon_options']['currency_symbol'];
	} else {
		$currency_symbol = "$";
	}
	if (isset($_SESSION['car_demon_options']['currency_symbol_after'])) {
		$currency_symbol_after = $_SESSION['car_demon_options']['currency_symbol_after'];
		if (!empty($currency_symbol_after)) {
			$currency_symbol = "";
		}
	} else {
		$currency_symbol_after = "";
	}
	$x = '<select id="search_dropdown_'.$size.'_price" name="search_dropdown_'.$size.'_price" class="search_dropdown_sm">';
		$x .= '<option value="0">'.__('No', 'car-demon').'&nbsp; '.$size.'</option>';
		if ($price == '1000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="1000"'.$select.'>'.$currency_symbol.'1,000'.$currency_symbol_after.'</option>';
		if ($price == '10000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="10000"'.$select.'>'.$currency_symbol.'10,000'.$currency_symbol_after.'</option>';
		if ($price == '15000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="15000"'.$select.'>'.$currency_symbol.'15,000'.$currency_symbol_after.'</option>';
		if ($price == '20000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="20000"'.$select.'>'.$currency_symbol.'20,000'.$currency_symbol_after.'</option>';
		if ($price == '30000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="30000"'.$select.'>'.$currency_symbol.'30,000'.$currency_symbol_after.'</option>';
		if ($price == '40000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="40000"'.$select.'>'.$currency_symbol.'40,000'.$currency_symbol_after.'</option>';
		if ($price == '50000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="50000"'.$select.'>'.$currency_symbol.'50,000'.$currency_symbol_after.'</option>';
		if ($price == '75000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="75000"'.$select.'>'.$currency_symbol.'75,000'.$currency_symbol_after.'</option>';
		if ($price == '100000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="100000"'.$select.'>'.$currency_symbol.'100,000'.$currency_symbol_after.'</option>';		
	$x .= '</select>';
	return $x;
}
function car_demon_search_tran() {
	global $wpdb;
	if (isset($_GET['search_dropdown_tran'])) {
		$current_trans = $_GET['search_dropdown_tran'];
	} else {
		$current_trans = '';
	}
	$prefix = $wpdb->prefix;
	$sql = 'SELECT DISTINCT meta_value from '.$prefix.'postmeta WHERE meta_key="_transmission_value"';
	$trans = $wpdb->get_results($sql);
	if (!empty($trans)) {
		$x = '<select id="search_dropdown_tran" name="search_dropdown_tran" class="search_dropdown_sm">';
			$x .= '<option value="">'.__('Any', 'car-demon').'</option>';
			foreach($trans as $tran) {
				if (!empty($tran->meta_value)) {
					if ($current_trans == $tran->meta_value) {$select = ' selected';} else {$select = '';}
					$x .='<option value="'.$tran->meta_value.'"'.$select.'>'.$tran->meta_value.'</option>';
				}
			}
			$x .= '</select>';
	}
	return $x;
}
function car_demon_search_miles() {
	if (isset($_GET['search_dropdown_miles'])) {
		$miles = $_GET['search_dropdown_miles'];
	} else {
		$miles = '';
	}
	$x = '<select id="search_dropdown_miles" name="search_dropdown_miles" class="search_dropdown_sm">';
	if ($miles == '1000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value=""'.$select.'>'.__('Any', 'car-demon').'</option>';
	if ($miles == '5000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="5000"'.$select.'>< 5,000</option>';
	if ($miles == '10000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="10000"'.$select.'>< 10,000</option>';
	if ($miles == '20000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="20000"'.$select.'>< 20,000</option>';
	if ($miles == '30000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="30000"'.$select.'>< 30,000</option>';
	if ($miles == '40000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="40000"'.$select.'>< 40,000</option>';
	if ($miles == '50000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="50000"'.$select.'>< 50,000</option>';
	if ($miles == '60000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="60000"'.$select.'>< 60,000</option>';
	if ($miles == '70000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="70000"'.$select.'>< 70,000</option>';
	if ($miles == '80000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="80000"'.$select.'>< 80,000</option>';
	if ($miles == '90000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="90000"'.$select.'>< 90,000</option>';
	if ($miles == '100000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="100000"'.$select.'>< 100,000</option>';
		$x .='</select>';
	return $x;
}
function car_demon_search_body() {
	$taxonomies = array('vehicle_body_style');
	$args = array('orderby'=>'count','hide_empty'=>true);
	$x = car_demon_get_terms_dropdown($taxonomies, $args);
	return $x;
}
function car_demon_get_terms_dropdown($taxonomies, $args){
	if (isset($_GET['search_dropdown_body'])) {
		$body = $_GET['search_dropdown_body'];
	} else {
		$body = '';
	}
	$myterms = get_terms($taxonomies, $args);
	$output = '<select id="search_dropdown_body" name="search_dropdown_body" class="search_dropdown_sm">
			<option value="">'.__('Any', 'car-demon').'</option>';
	foreach($myterms as $term){
		$root_url = home_url();
		$term_taxonomy=$term->taxonomy;
		$term_slug=$term->slug;
		$term_name =$term->name;
		$link = $root_url.'/'.$term_taxonomy.'/'.$term_slug;
		if ($term_slug == $body) {$selected = ' selected';} else {$selected = '';}
		$output .="<option value='".$term_slug."'".$selected.">".$term_name."</option>";
	}
	$output .="</select>";
return $output;
}
function car_demon_count_active_tax_items($my_tag_name, $post_type, $taxonomy) {
	global $wpdb;
	$my_tag_id = get_term_by( 'slug', ''.$my_tag_name.'', ''.$taxonomy.'');
	if ($my_tag_id == false) {
		$my_tag_id = get_term_by( 'name', ''.$my_tag_name.'', ''.$taxonomy.'');
	}
	$my_tag_id = $my_tag_id->term_id;
	if (!empty($my_tag_id)) {
		$my_search = " AND $wpdb->term_taxonomy.taxonomy = '".$taxonomy."'	AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id.")";
		$query = "SELECT COUNT(*) as num
			FROM $wpdb->posts wposts
				LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
				LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE wposts.post_type='".$post_type."'
				AND wpostmeta.meta_key = 'sold'
				AND wpostmeta.meta_value = 'no'".$my_search;
		$total_cars = $wpdb->get_var($query);
	}
	return $total_cars;
}
function car_demon_search_cars_scripts() {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	wp_register_script('car-demon-search-js', WP_CONTENT_URL . '/plugins/car-demon/includes/js/car-demon-search.js');
	wp_localize_script('car-demon-search-js', 'cdSearchParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'car_demon_path' => CAR_DEMON_PATH,
		'site_url' => get_bloginfo('wpurl')
	));
	wp_enqueue_script('car-demon-search-js');
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-search-css', WP_CONTENT_URL . '/plugins/car-demon/includes/css/car-demon-search.css');
		}
	} else {
		wp_enqueue_style('car-demon-search-css', WP_CONTENT_URL . '/plugins/car-demon/includes/css/car-demon-search.css');
	}
}
add_action("wp_ajax_car_demon_search_handler", "car_demon_search_handler");
add_action("wp_ajax_nopriv_car_demon_search_handler", "car_demon_search_handler");

function car_demon_search_handler() {
	include('car-demon-search-terms.php');
	car_demon_search_cars();
	exit();
}
?>