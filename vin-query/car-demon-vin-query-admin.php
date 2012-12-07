<?php
if (is_admin()) {
	$post_type = car_demon_get_current_post_type();
	// Put admin dashboard js in it's own js file and enqueue
	//add_action( 'wp_dashboard_setup', 'eg_add_dashboard_widgets' );
	if ($post_type == 'cars_for_sale') {
		add_action( 'admin_enqueue_scripts', 'cardemons_automotive_inventory_decode_header' );
		add_action( 'add_meta_boxes', 'start_decode_box' );
		add_action('save_post','cd_save_car');
	}
}

function cd_save_car($post_id) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	  return;
	if ( 'cars_for_sale' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	} else {
		return;
	}
	$sold_status = get_post_meta($post_id, 'sold');
	if (empty($sold_status)) {
		update_post_meta($post_id, 'sold', 'no');
	}
	if (isset($_POST['_vehicle_ribbon'])) {
		update_post_meta($post_id, '_vehicle_ribbon', $_POST['_vehicle_ribbon']);
	}
	if (isset($_POST['_custom_ribbon'])) {
		update_post_meta($post_id, '_custom_ribbon', $_POST['_custom_ribbon']);
	}
	return;
}

function car_demon_get_current_post_type() {
	global $post, $typenow, $current_screen;
	if( $post && $post->post_type )
		$post_type = $post->post_type;
	elseif( $typenow )
		$post_type = $typenow;
	elseif( $current_screen && $current_screen->post_type )
		$post_type = $current_screen->post_type;
	elseif( isset( $_REQUEST['post_type'] ) )
		$post_type = sanitize_key( $_REQUEST['post_type'] );
	else
		$post_type = get_post_type($_GET['post']);
	return $post_type;
}

function cardemons_automotive_inventory_decode_header() {
	wp_enqueue_script('car-demon-jquery-lightbox', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/jquery.lightbox_me.js', array('jquery'));
	wp_enqueue_script('car-demon-vin-query-js', WP_CONTENT_URL . '/plugins/car-demon/vin-query/js/car-demon-vin-query.js.php');
	wp_enqueue_style('car-demon-vin-query-css', WP_CONTENT_URL . '/plugins/car-demon/vin-query/css/car-demon-vin-query.css');
}

function eg_add_dashboard_widgets() {
	wp_add_dashboard_widget('example_dashboard_widget', 'Add a Vehicle', 'eg_add_vehicle_dashboard_widget_function');
	global $wp_meta_boxes;
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$example_widget_backup = array('example_dashboard_widget' => $normal_dashboard['example_dashboard_widget']);
	unset($normal_dashboard['example_dashboard_widget']);
	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

function eg_add_vehicle_dashboard_widget_function() {
	$vin = '';
	$post_id = '';
	$html = '<div id="add_vehicle_div">';
		$html .= 'TITLE:<br /><input type="text" size="35" id="cd_title" name="cd_title" value="'.$vin.'"><br />';
		$html .= 'STOCK #:<br /><input type="text" size="35" id="cd_stock" name="cd_stock" value="'.$vin.'"><br />';
		$html .= 'VIN:<br /><input type="text" size="35" id="cd_vin" name="cd_vin" value="'.$vin.'" onchange="validate_vin(this.value)">';
		$html .= '<br /><input onclick="dashboard_decode_vin('. $post_id .')" type="button" name="decode_vin_'.$post_id.'" id="decode_vin_'.$post_id.'" value="Add Vehicle" class="btn" />';
		$html .= '<div id="alert_msg"></div>';
		$html .= '<div id="decode_results"></div>';
	$html .= '</div>';
	echo $html;
}

function cardemons_automotive_inventory_decode($post_id) {
	$vin_query_decode = get_post_meta($post_id, "decode_string", true);
	$vin = get_post_meta($post_id, "_vin_value", true);
	$html = '
	<div id="vin_decode_options_'.$post_id.'">';
		$specs = get_vin_query_specs_admin($vin_query_decode, $vin, $post_id);
		$safety = get_vin_query_safety_admin($vin_query_decode, $post_id);
		$convienience = get_vin_query_convienience_admin($vin_query_decode, $post_id);
		$comfort = get_vin_query_comfort_admin($vin_query_decode, $post_id);
		$entertainment = get_vin_query_entertainment_admin($vin_query_decode, $post_id);
	$html .= '
		<ul class="tabs"> 
			<li><a href="javascript:car_demon_switch_tabs(1, 5, \'tab_\', \'content_\');" id="tab_1">Specs</a></li>  
			<li><a href="javascript:car_demon_switch_tabs(2, 5, \'tab_\', \'content_\');" id="tab_2">Safety</a></li>
			<li><a href="javascript:car_demon_switch_tabs(3, 5, \'tab_\', \'content_\');" id="tab_3">Convenience</a></li>
			<li><a href="javascript:car_demon_switch_tabs(4, 5, \'tab_\', \'content_\');" id="tab_4">Comfort</a></li>
			<li><a href="javascript:car_demon_switch_tabs(5, 5, \'tab_\', \'content_\');" id="tab_5">Entertainment</a></li>
		</ul>';
		$html .= '<div id="content_1" class="car_features_content">'.$specs.'</div> ';
		$html .= '<div id="content_2" class="car_features_content">'.$safety.'</div>  ';
		$html .= '<div id="content_3" class="car_features_content">'.$convienience.'</div>';
		$html .= '<div id="content_4" class="car_features_content">'.$comfort.'</div>';
		$html .= '<div id="content_5" class="car_features_content">'.$entertainment.'</div>';
	$html .= '</div>';
	return $html;
}

function start_decode_box() {
	global $theme_name;
	add_meta_box('decode-div', 'Vehicle Options', 'decode_metabox', 'cars_for_sale', 'normal', 'high');
	add_meta_box('decode-status', 'Sales Status', 'decode_sales_metabox', 'cars_for_sale', 'side', 'high');
	add_meta_box('decode-ribbon', 'Photo Ribbon', 'decode_photo_ribbon', 'cars_for_sale', 'side', 'default');
}

function decode_photo_ribbon($post) {
	$post_id = $post->ID;
	$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
	$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);
	$no_ribbon = '';
	$custom_ribbon = '';
	$low_price = '';
	$great_deal = '';
	$just_added = '';
	$low_miles = '';
	$brand_new = '';
	if ($ribbon == 'no_ribbon') {
		$no_ribbon = ' selected';
	} elseif ($ribbon == 'custom_ribbon') {
		$custom_ribbon = ' selected';
	} elseif ($ribbon == 'low_price') {
		$low_price = ' selected';
	} elseif ($ribbon == 'great_deal') {
		$great_deal = ' selected';
	} elseif ($ribbon == 'just_added') {
		$just_added = ' selected';
	} elseif ($ribbon == 'low_miles') {
		$low_miles = ' selected';
	} elseif ($ribbon == 'brand_new') {
		$brand_new = ' selected';
	} else {
		update_post_meta($post_id, '_vehicle_ribbon', 'no_ribbon');
		$ribbon = 'no_ribbon';
		$no_ribbon = ' selected';
	}
	echo '<input type="hidden" id="this_car_id" name="this_car_id" value="'.$post_id.'" />';
	echo __('Select Ribbon Banner', 'car-demon').' <select name="_vehicle_ribbon" id="_vehicle_ribbon" onchange="update_vehicle_data(this, '.$post_id.');update_ribbon(this.value);">
			<option value="no_ribbon"'.$no_ribbon.'>'.__('No Ribbon', 'car-demon').'</option>
			<option value="custom_ribbon"'.$custom_ribbon.'>'.__('Custom Ribbon', 'car-demon').'</option>
			<option value="low_price"'.$low_price.'>'.__('Low Price', 'car-demon').'</option>
			<option value="great_deal"'.$great_deal.'>'.__('Great Deal', 'car-demon').'</option>
			<option value="just_added"'.$just_added.'>'.__('Just Added', 'car-demon').'</option>
			<option value="low_miles"'.$low_miles.'>'.__('Low Miles', 'car-demon').'</option>
			<option value="brand_new"'.$brand_new.'>'.__('Brand New', 'car-demon').'</option>
		</select><br />';
	if ($ribbon != 'custom_ribbon') {
		$ribbon = str_replace('_', '-', $ribbon);
		$ribbon_url = WP_CONTENT_URL . '/plugins/car-demon/theme-files/images/ribbon-'.$ribbon.'.png';
		echo '<img src="'.$ribbon_url.'" id="vehicle_ribbon" name="vehicle_ribbon" /><br />';
		$custom_ribbon_div_class = 'custom_ribbon_div_hide';
	} else {
		echo '<img src="'.$custom_ribbon_file.'" id="vehicle_ribbon" name="vehicle_ribbon" /><br />';	
		$custom_ribbon_div_class = 'custom_ribbon_div';
	}
	echo '<div id="custom_ribbon_div" class="'.$custom_ribbon_div_class.'">';
		echo __('Custom Ribbon', 'car-demon').'<br />';
		echo '<input type="text" id="_custom_ribbon" name="_custom_ribbon" value="'.$custom_ribbon_file.'" onchange="update_vehicle_data(this, '.$post_id.');" />';
		echo '&nbsp;&nbsp;&nbsp;<input type="button" value="'.__('Upload', 'car-demon').'" id="custom_ribbon_btn" name="custom_ribbon_btn" class="button" />';
	echo '</div>';
	return;
}

function decode_sales_metabox($post) {
	$post_id = $post->ID;
	$status = get_post_meta($post_id, 'sold', true);
	if ($status == 'yes') {
		$yes = ' selected';
		$no = '';
	} else {
		$no = ' selected';
		$yes = '';
	}
	echo 'Sold <select name="sold" id="sold" onchange=" update_vehicle_data(this, '.$post_id.')">
			<option value="no"'.$no.'>No</option>
			<option value="yes"'.$yes.'>Yes</option>
		</select>';
	return;
}

function decode_metabox($post) {
	$post_id = $post->ID;
	echo cardemons_automotive_inventory_decode($post_id);
	return;
}

function does_vin_exist($vin) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$query = "SELECT post_id FROM ".$prefix."postmeta
		WHERE ".$prefix."postmeta.meta_key = '_vin_value'
		AND ".$prefix."postmeta.meta_value = '".$vin."'";
	$cars = $wpdb->get_results(sprintf($query));
	if (!empty($cars)) {
		foreach ($cars as $car) {
			$car_id = $car->post_id;
		}
	} else {
		$car_id = 0;
	}
	return $car_id;
}

function get_vin_query_specs_admin($vin_query_decode, $vehicle_vin, $post_id) {
	if (isset($vin_query_decode['decoded_model_year'])) {$decoded_model_year = $vin_query_decode['decoded_model_year']; } else {$decoded_model_year = ''; }
	if (isset($vin_query_decode["decoded_make"])) {$decoded_make = $vin_query_decode["decoded_make"]; } else {$decoded_make = ''; }
	if (isset($vin_query_decode["decoded_model"])) {$decoded_model = $vin_query_decode["decoded_model"]; } else {$decoded_model = ''; }
	if (isset($vin_query_decode["decoded_trim_level"])) {$decoded_trim_level = $vin_query_decode["decoded_trim_level"]; } else {$decoded_trim_level = ''; }
	if (isset($vin_query_decode["decoded_manufactured_in"])) {$decoded_manufactured_in = $vin_query_decode["decoded_manufactured_in"]; } else {$decoded_manufactured_in = ''; }
	if (isset($vin_query_decode["decoded_production_seq_number"])) {$decoded_production_seq_number = $vin_query_decode["decoded_production_seq_number"]; } else {$decoded_production_seq_number = ''; }
	if (isset($vin_query_decode["decoded_body_style"])) {$decoded_body_style = $vin_query_decode["decoded_body_style"]; } else {$decoded_body_style = ''; }
	if (isset($vin_query_decode["decoded_engine_type"])) {$decoded_engine_type = $vin_query_decode["decoded_engine_type"]; } else {$decoded_engine_type = ''; }
	if (isset($vin_query_decode["decoded_transmission_long"])) {$decoded_transmission_long = $vin_query_decode["decoded_transmission_long"]; } else {$decoded_transmission_long = ''; }
	if (isset($vin_query_decode["decoded_driveline"])) {$decoded_driveline = $vin_query_decode["decoded_driveline"]; } else {$decoded_driveline = ''; }
	if (isset($vin_query_decode["decoded_tank"])) {$decoded_tank = $vin_query_decode["decoded_tank"]; } else {$decoded_tank = ''; }
	if (isset($vin_query_decode["decoded_fuel_economy_city"])) {$decoded_fuel_economy_city = $vin_query_decode["decoded_fuel_economy_city"]; } else {$decoded_fuel_economy_city = ''; }
	if (isset($vin_query_decode["decoded_fuel_economy_highway"])) {$decoded_fuel_economy_highway = $vin_query_decode["decoded_fuel_economy_highway"]; } else {$decoded_fuel_economy_highway = ''; }
	if (isset($vin_query_decode["decoded_anti_brake_system"])) {$decoded_anti_brake_system = $vin_query_decode["decoded_anti_brake_system"]; } else {$decoded_anti_brake_system = ''; }
	if (isset($vin_query_decode["decoded_steering_type"])) {$decoded_steering_type = $vin_query_decode["decoded_steering_type"]; } else {$decoded_steering_type = ''; }
	if (isset($vin_query_decode["decoded_overall_length"])) {$decoded_overall_length = $vin_query_decode["decoded_overall_length"]; } else {$decoded_overall_length = ''; }
	if (isset($vin_query_decode["decoded_overall_width"])) {$decoded_overall_width = $vin_query_decode["decoded_overall_width"]; } else {$decoded_overall_width = ''; }
	if (isset($vin_query_decode["decoded_overall_height"])) {$decoded_overall_height = $vin_query_decode["decoded_overall_height"]; } else {$decoded_overall_height = ''; }
	// Meta Fields
	$stock_num = wp_kses_data(get_post_meta($post_id, '_stock_value', true));
	$retail_price = wp_kses_data(get_post_meta($post_id, '_msrp_value', true));
	$rebates = wp_kses_data(get_post_meta($post_id, '_rebates_value', true));
	$discount = wp_kses_data(get_post_meta($post_id, '_discount_value', true));
	$price = wp_kses_data(get_post_meta($post_id, '_price_value',true));
	$exterior_color = wp_kses_data(get_post_meta($post_id, '_exterior_color_value', true));
	$interior_color = wp_kses_data(get_post_meta($post_id, '_interior_color_value', true));
	$mileage = wp_kses_data(get_post_meta($post_id, '_mileage_value', true));
	$condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
	$remove_decode_btn = '<input onclick="remove_decode('. $post_id .')" type="button" name="remove_decode_vin_'.$post_id.'" id="remove_decode_vin_'.$post_id.'" value="Reset Options" class="btn" />';
	$car_demon_options = car_demon_options();
	$decode_btn = '';
	$decode_results = '';
	if (empty($vin_query_decode)) {
		$decode_results = '<div id="decode_results"></div>';
		if (isset($car_demon_options['vinquery_id'])) {
			if (!empty($car_demon_options['vinquery_id'])) {
				$decode_btn = '<input onclick="decode_vin('. $post_id .')" type="button" name="decode_vin_'.$post_id.'" id="decode_vin_'.$post_id.'" value="Decode Vin" class="btn" />';
			}
		}
	} else {
		if (isset($car_demon_options['vinquery_id'])) {
			if (!empty($car_demon_options['vinquery_id'])) {
				$decode_results = '<div id="decode_results">VIN HAS BEEN DECODED.</div>';
				$decode_btn = '<input onclick="decode_vin('. $post_id .')" type="button" name="decode_vin_'.$post_id.'" id="decode_vin_'.$post_id.'" value="Re-Decode Vin" class="btn" />';
			}
		}
	}
	$x = '
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td><strong>'.__('VIN #', 'car-demon').'</strong></td>
		<td><input type="text" id="vin" name="vin" onchange="update_vehicle_data(this, '.$post_id.')" value="'.$vehicle_vin.'" />'.$remove_decode_btn.$decode_btn.$decode_results.'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Stock Number', 'car-demon').'</td>
		<td><input type="text" id="stock_num" onchange="update_admin_decode(this, '.$post_id.')" value="'.$stock_num.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Mileage', 'car-demon').'</td>
		<td><input type="text" id="mileage" onchange="update_admin_decode(this, '.$post_id.')" value="'.$mileage.'" /></td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Pricing', 'car-demon').'</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Retail Price', 'car-demon').'</td>
		<td><input type="text" id="msrp" onchange="update_admin_decode(this, '.$post_id.')" value="'.$retail_price.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Rebates', 'car-demon').'</td>
		<td><input type="text" id="rebates" onchange="update_admin_decode(this, '.$post_id.')" value="'.$rebates.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Discount', 'car-demon').'</td>
		<td><input type="text" id="discount" onchange="update_admin_decode(this, '.$post_id.')" value="'.$discount.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Asking Price', 'car-demon').'</td>
		<td><input type="text" id="price" onchange="update_admin_decode(this, '.$post_id.')" value="'.$price.'" /></td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Details', 'car-demon').'</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Condition', 'car-demon').'</td>
		<td>
			<select id="condition" onchange="update_admin_decode(this, '.$post_id.')">
				<option value="'.$condition.'">'.$condition.'</option>
				<option value="'.__('Used', 'car-demon').'">'.__('Used', 'car-demon').'</option>
				<option "'.__('Certified Used', 'car-demon').'">'.__('Certified Used', 'car-demon').'</option>
				<option "'.__('New', 'car-demon').'">'.__('New', 'car-demon').'</option>
			</select>
		</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Model Year', 'car-demon').'</td>
		<td><input type="text" id="decoded_model_year" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_model_year.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Make', 'car-demon').'</td>
		<td><input type="text" id="decoded_make" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_make.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Model', 'car-demon').'</td>
		<td><input type="text" id="decoded_model" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_model.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Trim', 'car-demon').'</td>
		<td><input type="text" id="decoded_trim_level" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_trim_level.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Exterior Color', 'car-demon').'</td>
		<td><input type="text" id="exterior_color" onchange="update_admin_decode(this, '.$post_id.')" value="'.$exterior_color.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Interior Color', 'car-demon').'</td>
		<td><input type="text" id="interior_color" onchange="update_admin_decode(this, '.$post_id.')" value="'.$interior_color.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Manufactured in', 'car-demon').'</td>
		<td><input type="text" id="decoded_manufactured_in" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_manufactured_in.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td>&nbsp;&nbsp;&nbsp;'.__('Production Seq. Number', 'car-demon').'</td>
		<td><input type="text" id="decoded_production_seq_number" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_production_seq_number.'" /></td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Specifications', 'car-demon').'</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Body Style', 'car-demon').'</td>
		<td><input type="text" id="decoded_body_style" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_body_style.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Engine Type', 'car-demon').'</td>
		<td><input type="text" id="decoded_engine_type" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_engine_type.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Transmission', 'car-demon').'</td>
		<td><input type="text" id="decoded_transmission_long" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_transmission_long.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Driveline', 'car-demon').'</td>
		<td><input type="text" id="decoded_driveline" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_driveline.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tank(gallon)', 'car-demon').'</td>
		<td><input type="text" id="decoded_tank" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_tank.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fuel Economy(City, miles/gallon)', 'car-demon').'</td>
		<td><input type="text" id="decoded_fuel_economy_city" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_fuel_economy_city.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fuel Economy(Highway, miles/gallon)', 'car-demon').'</td>
		<td><input type="text" id="decoded_fuel_economy_highway" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_fuel_economy_highway.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Anti-Brake System', 'car-demon').'</td>
		<td><input type="text" id="decoded_anti_brake_system" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_anti_brake_system.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Steering Type', 'car-demon').'</td>
		<td><input type="text" id="decoded_steering_type" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_steering_type.'" /></td>
	  </tr>	  
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Length(in.)', 'car-demon').'</td>
		<td><input type="text" id="decoded_overall_length" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_overall_length.'" /></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Width(in.)', 'car-demon').'</td>
		<td><input type="text" id="decoded_overall_width" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_overall_width.'" /></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Height(in.)', 'car-demon').'</td>
		<td><input type="text" id="decoded_overall_height" onchange="update_admin_decode(this, '.$post_id.')" value="'.$decoded_overall_height.'" /></td>
	  </tr>
	  </table>';
	return $x;
}

function get_vin_query_safety_admin($vin_query_decode, $post_id) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	if (isset($vin_query_decode["decoded_child_safety_door_locks"])) {$decoded_child_safety_door_locks = $vin_query_decode["decoded_child_safety_door_locks"]; } else {$decoded_child_safety_door_locks = ''; }
	if (isset($vin_query_decode["decoded_locking_pickup_truck_tailgate"])) {$decoded_locking_pickup_truck_tailgate = $vin_query_decode["decoded_locking_pickup_truck_tailgate"]; } else {$decoded_locking_pickup_truck_tailgate = ''; }
	if (isset($vin_query_decode["decoded_power_door_locks"])) {$decoded_power_door_locks = $vin_query_decode["decoded_power_door_locks"]; } else {$decoded_power_door_locks = ''; }
	if (isset($vin_query_decode["decoded_vehicle_anti_theft"])) {$decoded_vehicle_anti_theft = $vin_query_decode["decoded_vehicle_anti_theft"]; } else {$decoded_vehicle_anti_theft = ''; }
	if (isset($vin_query_decode["decoded_4wd_awd"])) {$decoded_4wd_awd = $vin_query_decode["decoded_4wd_awd"]; } else {$decoded_4wd_awd = ''; }
	if (isset($vin_query_decode["decoded_abs_brakes"])) {$decoded_abs_brakes = $vin_query_decode["decoded_abs_brakes"]; } else {$decoded_abs_brakes = ''; }
	if (isset($vin_query_decode["decoded_automatic_load_leveling"])) {$decoded_automatic_load_leveling = $vin_query_decode["decoded_automatic_load_leveling"]; } else {$decoded_automatic_load_leveling = ''; }
	if (isset($vin_query_decode["decoded_electronic_brake_assistance"])) {$decoded_electronic_brake_assistance = $vin_query_decode["decoded_electronic_brake_assistance"]; } else {$decoded_electronic_brake_assistance = ''; }
	if (isset($vin_query_decode["decoded_limited_slip_differential"])) {$decoded_limited_slip_differential = $vin_query_decode["decoded_limited_slip_differential"]; } else {$decoded_limited_slip_differential = ''; }
	if (isset($vin_query_decode["decoded_locking_differential"])) {$decoded_locking_differential = $vin_query_decode["decoded_locking_differential"]; } else {$decoded_locking_differential = ''; }
	if (isset($vin_query_decode["decoded_traction_control"])) {$decoded_traction_control = $vin_query_decode["decoded_traction_control"]; } else {$decoded_traction_control = ''; }
	if (isset($vin_query_decode["decoded_vehicle_stability_control_system"])) {$decoded_vehicle_stability_control_system = $vin_query_decode["decoded_vehicle_stability_control_system"]; } else {$decoded_vehicle_stability_control_system = ''; }
	if (isset($vin_query_decode["decoded_driver_airbag"])) {$decoded_driver_airbag = $vin_query_decode["decoded_driver_airbag"]; } else {$decoded_driver_airbag = ''; }
	if (isset($vin_query_decode["decoded_front_side_airbag"])) {$decoded_front_side_airbag = $vin_query_decode["decoded_front_side_airbag"]; } else {$decoded_front_side_airbag = ''; }
	if (isset($vin_query_decode["decoded_front_side_airbag_with_head_protection"])) {$decoded_front_side_airbag_with_head_protection = $vin_query_decode["decoded_front_side_airbag_with_head_protection"]; } else {$decoded_front_side_airbag_with_head_protection = ''; }
	if (isset($vin_query_decode["decoded_passenger_airbag"])) {$decoded_passenger_airbag = $vin_query_decode["decoded_passenger_airbag"]; } else {$decoded_passenger_airbag = ''; }
	if (isset($vin_query_decode["decoded_side_head_curtain_airbag"])) {$decoded_side_head_curtain_airbag = $vin_query_decode["decoded_side_head_curtain_airbag"]; } else {$decoded_side_head_curtain_airbag = ''; }
	if (isset($vin_query_decode["decoded_second_row_side_airbag"])) {$decoded_second_row_side_airbag = $vin_query_decode["decoded_second_row_side_airbag"]; } else {$decoded_second_row_side_airbag = ''; }
	if (isset($vin_query_decode["decoded_second_row_side_airbag_with_head_protection"])) {$decoded_second_row_side_airbag_with_head_protection = $vin_query_decode["decoded_second_row_side_airbag_with_head_protection"]; } else {$decoded_second_row_side_airbag_with_head_protection = ''; }
	if (isset($vin_query_decode["decoded_electronic_parking_aid"])) {$decoded_electronic_parking_aid = $vin_query_decode["decoded_electronic_parking_aid"]; } else {$decoded_electronic_parking_aid = ''; }
	if (isset($vin_query_decode["decoded_first_aid_kit"])) {$decoded_first_aid_kit = $vin_query_decode["decoded_first_aid_kit"]; } else {$decoded_first_aid_kit = ''; }
	if (isset($vin_query_decode["decoded_trunk_anti_trap_device"])) {$decoded_trunk_anti_trap_device = $vin_query_decode["decoded_trunk_anti_trap_device"]; } else {$decoded_trunk_anti_trap_device = ''; }
	$x = '
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Anti-Theft & Locks</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Child Safety Door Locks</td>
		<td>'.decode_select('decoded_child_safety_door_locks', $decoded_child_safety_door_locks, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Locking Pickup Truck Tailgate</td>
		<td>'.decode_select('decoded_locking_pickup_truck_tailgate',$decoded_locking_pickup_truck_tailgate, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Power Door Locks</td>
		<td>'.decode_select('decoded_power_door_locks',$decoded_power_door_locks, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Vehicle Anti-Theft</td>
		<td>'.decode_select('decoded_vehicle_anti_theft',$decoded_vehicle_anti_theft, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Braking & Traction</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;4WD/AWD</td>
		<td>'.decode_select('decoded_4wd_awd',$decoded_4wd_awd, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;ABS(2-Wheel/4-Wheel)</td>
		<td>'.decode_select('decoded_abs_brakes',$decoded_abs_brakes, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Automatic Load-Leveling</td>
		<td>'.decode_select('decoded_automatic_load_leveling',$decoded_automatic_load_leveling, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Electronic Brake Assistance</td>
		<td>'.decode_select('decoded_electronic_brake_assistance',$decoded_electronic_brake_assistance, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Limited Slip Differential</td>
		<td>'.decode_select('decoded_limited_slip_differential',$decoded_limited_slip_differential, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Locking Differential</td>
		<td>'.decode_select('decoded_locking_differential',$decoded_locking_differential, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Traction Control</td>
		<td>'.decode_select('decoded_traction_control',$decoded_traction_control, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Vehicle Stability Control System</td>
		<td>'.decode_select('decoded_vehicle_stability_control_system',$decoded_vehicle_stability_control_system, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Safety</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Driver Airbag</td>
		<td>'.decode_select('decoded_driver_airbag',$decoded_driver_airbag, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Side Airbag</td>
		<td>'.decode_select('decoded_front_side_airbag',$decoded_front_side_airbag, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Side Airbag with Head Protection</td>
		<td>'.decode_select('decoded_front_side_airbag_with_head_protection',$decoded_front_side_airbag_with_head_protection, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Passenger Airbag</td>
		<td>'.decode_select('decoded_passenger_airbag',$decoded_passenger_airbag, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Side Head Curtain Airbag</td>
		<td>'.decode_select('decoded_side_head_curtain_airbag',$decoded_side_head_curtain_airbag, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Side Airbag</td>
		<td>'.decode_select('decoded_second_row_side_airbag',$decoded_second_row_side_airbag, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Side Airbag with Head Protection</td>
		<td>'.decode_select('decoded_second_row_side_airbag_with_head_protection',$decoded_second_row_side_airbag_with_head_protection, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Electronic Parking Aid</td>
		<td>'.decode_select('decoded_electronic_parking_aid',$decoded_electronic_parking_aid, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;First Aid Kit</td>
		<td>'.decode_select('decoded_first_aid_kit',$decoded_first_aid_kit, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Trunk Anti-Trap Device</td>
		<td>'.decode_select('decoded_trunk_anti_trap_device',$decoded_trunk_anti_trap_device, $post_id).'</td>
	  </tr>
	  <tr>
		<td class="lastrowinpage" colspan="2">&nbsp;</td>
	  </tr>
	  </table>';
	return $x;
}

function get_vin_query_convienience_admin($vin_query_decode, $post_id) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	if (isset($vin_query_decode["decoded_keyless_entry"])) {$decoded_keyless_entry = $vin_query_decode["decoded_keyless_entry"]; } else {$decoded_keyless_entry = ''; }
	if (isset($vin_query_decode["decoded_remote_ignition"])) {$decoded_remote_ignition = $vin_query_decode["decoded_remote_ignition"]; } else {$decoded_remote_ignition = ''; }
	if (isset($vin_query_decode["decoded_cruise_control"])) {$decoded_cruise_control = $vin_query_decode["decoded_cruise_control"]; } else {$decoded_cruise_control = ''; }
	if (isset($vin_query_decode["decoded_tachometer"])) {$decoded_tachometer = $vin_query_decode["decoded_tachometer"]; } else {$decoded_tachometer = ''; }
	if (isset($vin_query_decode["decoded_tilt_steering"])) {$decoded_tilt_steering = $vin_query_decode["decoded_tilt_steering"]; } else {$decoded_tilt_steering = ''; }
	if (isset($vin_query_decode["decoded_tilt_steering_column"])) {$decoded_tilt_steering_column = $vin_query_decode["decoded_tilt_steering_column"]; } else {$decoded_tilt_steering_column = ''; }
	if (isset($vin_query_decode["decoded_heated_steering_wheel"])) {$decoded_heated_steering_wheel = $vin_query_decode["decoded_heated_steering_wheel"]; } else {$decoded_heated_steering_wheel = ''; }
	if (isset($vin_query_decode["decoded_leather_steering_wheel"])) {$decoded_leather_steering_wheel = $vin_query_decode["decoded_leather_steering_wheel"]; } else {$decoded_leather_steering_wheel = ''; }
	if (isset($vin_query_decode["decoded_steering_wheel_mounted_controls"])) {$decoded_steering_wheel_mounted_controls = $vin_query_decode["decoded_steering_wheel_mounted_controls"]; } else {$decoded_steering_wheel_mounted_controls = ''; }
	if (isset($vin_query_decode["decoded_telescopic_steering_column"])) {$decoded_telescopic_steering_column = $vin_query_decode["decoded_telescopic_steering_column"]; } else {$decoded_telescopic_steering_column = ''; }
	if (isset($vin_query_decode["decoded_adjustable_foot_pedals"])) {$decoded_adjustable_foot_pedals = $vin_query_decode["decoded_adjustable_foot_pedals"]; } else {$decoded_adjustable_foot_pedals = ''; }
	if (isset($vin_query_decode["decoded_genuine_wood_trim"])) {$decoded_genuine_wood_trim = $vin_query_decode["decoded_genuine_wood_trim"]; } else {$decoded_genuine_wood_trim = ''; }
	if (isset($vin_query_decode["decoded_tire_pressure_monitor"])) {$decoded_tire_pressure_monitor = $vin_query_decode["decoded_tire_pressure_monitor"]; } else {$decoded_tire_pressure_monitor = ''; }
	if (isset($vin_query_decode["decoded_trip_computer"])) {$decoded_trip_computer = $vin_query_decode["decoded_trip_computer"]; } else {$decoded_trip_computer = ''; }
	if (isset($vin_query_decode["decoded_cargo_area_cover"])) {$decoded_cargo_area_cover = $vin_query_decode["decoded_cargo_area_cover"]; } else {$decoded_cargo_area_cover = ''; }
	if (isset($vin_query_decode["decoded_cargo_area_tiedowns"])) {$decoded_cargo_area_tiedowns = $vin_query_decode["decoded_cargo_area_tiedowns"]; } else {$decoded_cargo_area_tiedowns = ''; }
	if (isset($vin_query_decode["decoded_cargo_net"])) {$decoded_cargo_net = $vin_query_decode["decoded_cargo_net"]; } else {$decoded_cargo_net = ''; }
	if (isset($vin_query_decode["decoded_load_bearing_exterior_rack"])) {$decoded_load_bearing_exterior_rack = $vin_query_decode["decoded_load_bearing_exterior_rack"]; } else {$decoded_load_bearing_exterior_rack = ''; }
	if (isset($vin_query_decode["decoded_pickup_truck_bed_liner"])) {$decoded_pickup_truck_bed_liner = $vin_query_decode["decoded_pickup_truck_bed_liner"]; } else {$decoded_pickup_truck_bed_liner = ''; }
	if (isset($vin_query_decode["decoded_power_sunroof"])) {$decoded_power_sunroof = $vin_query_decode["decoded_power_sunroof"]; } else {$decoded_power_sunroof = ''; }
	if (isset($vin_query_decode["decoded_removable_top"])) {$decoded_removable_top = $vin_query_decode["decoded_removable_top"]; } else {$decoded_removable_top = ''; }
	if (isset($vin_query_decode["decoded_manual_sunroof"])) {$decoded_manual_sunroof = $vin_query_decode["decoded_manual_sunroof"]; } else {$decoded_manual_sunroof = ''; }
	$x = '
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Remote Controls & Release</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Keyless Entry</td>
		<td>'.decode_select('decoded_keyless_entry',$decoded_keyless_entry, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Remote Ignition</td>
		<td>'.decode_select('decoded_remote_ignition',$decoded_remote_ignition, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Interior Features</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Cruise Control</td>
		<td>'.decode_select('decoded_cruise_control',$decoded_cruise_control, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Tachometer</td>
		<td>'.decode_select('decoded_tachometer',$decoded_tachometer, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Tilt Steering Wheel</td>
		<td>'.decode_select('decoded_tilt_steering',$decoded_tilt_steering, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Tilt Steering Column</td>
		<td>'.decode_select('decoded_tilt_steering_column',$decoded_tilt_steering_column, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Heated Steering Wheel</td>
		<td>'.decode_select('decoded_heated_steering_wheel',$decoded_heated_steering_wheel, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Leather Steering Wheel</td>
		<td>'.decode_select('decoded_leather_steering_wheel',$decoded_leather_steering_wheel, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Steering Wheel Mounted Controls</td>
		<td>'.decode_select('decoded_steering_wheel_mounted_controls',$decoded_steering_wheel_mounted_controls, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Telescopic Steering Column</td>
		<td>'.decode_select('decoded_telescopic_steering_column',$decoded_telescopic_steering_column, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Adjustable Foot Pedals</td>
		<td>'.decode_select('decoded_adjustable_foot_pedals',$decoded_adjustable_foot_pedals, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Genuine Wood Trim</td>
		<td>'.decode_select('decoded_genuine_wood_trim',$decoded_genuine_wood_trim, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Tire Inflation/Pressure Monitor</td>
		<td>'.decode_select('decoded_tire_pressure_monitor',$decoded_tire_pressure_monitor, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Trip Computer</td>
		<td>'.decode_select('decoded_trip_computer',$decoded_trip_computer, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Storage</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Cargo Area Cover</td>
		<td>'.decode_select('decoded_cargo_area_cover',$decoded_cargo_area_cover, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Cargo Area Tiedowns</td>
		<td>'.decode_select('decoded_cargo_area_tiedowns',$decoded_cargo_area_tiedowns, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Cargo Net</td>
		<td>'.decode_select('decoded_cargo_net',$decoded_cargo_net, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Load Bearing Exterior Rack</td>
		<td>'.decode_select('decoded_load_bearing_exterior_rack',$decoded_load_bearing_exterior_rack, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Pickup Truck Bed Liner</td>
		<td>'.decode_select('decoded_pickup_truck_bed_liner',$decoded_pickup_truck_bed_liner, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Roof</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Power Sunroof/Moonroof</td>
		<td>'.decode_select('decoded_power_sunroof',$decoded_power_sunroof, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Manual Sunroof/Moonroof</td>
		<td>'.decode_select('decoded_removable_top',$decoded_removable_top, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Removable/Convertible Top</td>
		<td>'.decode_select('decoded_manual_sunroof',$decoded_manual_sunroof, $post_id).'</td>
	  </tr>
	</table>
	';
	return $x;
}

function get_vin_query_comfort_admin($vin_query_decode, $post_id) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	if (isset($vin_query_decode["decoded_air_conditioning"])) {$decoded_air_conditioning = $vin_query_decode["decoded_air_conditioning"]; } else {$decoded_air_conditioning = ''; }
	if (isset($vin_query_decode["decoded_separate_driver_front_passenger_climate_controls"])) {$decoded_separate_driver_front_passenger_climate_controls = $vin_query_decode["decoded_separate_driver_front_passenger_climate_controls"]; } else {$decoded_separate_driver_front_passenger_climate_controls = ''; }
	if (isset($vin_query_decode["decoded_driver_multi_adjustable_power_seat"])) {$decoded_driver_multi_adjustable_power_seat = $vin_query_decode["decoded_driver_multi_adjustable_power_seat"]; } else {$decoded_driver_multi_adjustable_power_seat = ''; }
	if (isset($vin_query_decode["decoded_front_cooled_seat"])) {$decoded_front_cooled_seat = $vin_query_decode["decoded_front_cooled_seat"]; } else {$decoded_front_cooled_seat = ''; }
	if (isset($vin_query_decode["decoded_front_heated_seat"])) {$decoded_front_heated_seat = $vin_query_decode["decoded_front_heated_seat"]; } else {$decoded_front_heated_seat = ''; }
	if (isset($vin_query_decode["decoded_front_power_lumbar_support"])) {$decoded_front_power_lumbar_support = $vin_query_decode["decoded_front_power_lumbar_support"]; } else {$decoded_front_power_lumbar_support = ''; }
	if (isset($vin_query_decode["decoded_front_power_memory_seat"])) {$decoded_front_power_memory_seat = $vin_query_decode["decoded_front_power_memory_seat"]; } else {$decoded_front_power_memory_seat = ''; }
	if (isset($vin_query_decode["decoded_front_split_bench_seat"])) {$decoded_front_split_bench_seat = $vin_query_decode["decoded_front_split_bench_seat"]; } else {$decoded_front_split_bench_seat = ''; }
	if (isset($vin_query_decode["decoded_leather_seat"])) {$decoded_leather_seat = $vin_query_decode["decoded_leather_seat"]; } else {$decoded_leather_seat = ''; }
	if (isset($vin_query_decode["decoded_passenger_multi_adjustable_power_seat"])) {$decoded_passenger_multi_adjustable_power_seat = $vin_query_decode["decoded_passenger_multi_adjustable_power_seat"]; } else {$decoded_passenger_multi_adjustable_power_seat = ''; }
	if (isset($vin_query_decode["decoded_second_row_folding_seat"])) {$decoded_second_row_folding_seat = $vin_query_decode["decoded_second_row_folding_seat"]; } else {$decoded_second_row_folding_seat = ''; }
	if (isset($vin_query_decode["decoded_second_row_heated_seat"])) {$decoded_second_row_heated_seat = $vin_query_decode["decoded_second_row_heated_seat"]; } else {$decoded_second_row_heated_seat = ''; }
	if (isset($vin_query_decode["decoded_second_row_multi_adjustable_power_seat"])) {$decoded_second_row_multi_adjustable_power_seat = $vin_query_decode["decoded_second_row_multi_adjustable_power_seat"]; } else {$decoded_second_row_multi_adjustable_power_seat = ''; }
	if (isset($vin_query_decode["decoded_second_row_removable_seat"])) {$decoded_second_row_removable_seat = $vin_query_decode["decoded_second_row_removable_seat"]; } else {$decoded_second_row_removable_seat = ''; }
	if (isset($vin_query_decode["decoded_third_row_removable_seat"])) {$decoded_third_row_removable_seat = $vin_query_decode["decoded_third_row_removable_seat"]; } else {$decoded_third_row_removable_seat = ''; }
	if (isset($vin_query_decode["decoded_automatic_headlights"])) {$decoded_automatic_headlights = $vin_query_decode["decoded_automatic_headlights"]; } else {$decoded_automatic_headlights = ''; }
	if (isset($vin_query_decode["decoded_daytime_running_lights"])) {$decoded_daytime_running_lights = $vin_query_decode["decoded_daytime_running_lights"]; } else {$decoded_daytime_running_lights = ''; }
	if (isset($vin_query_decode["decoded_fog_lights"])) {$decoded_fog_lights = $vin_query_decode["decoded_fog_lights"]; } else {$decoded_fog_lights = ''; }
	if (isset($vin_query_decode["decoded_high_intensity_discharge_headlights"])) {$decoded_high_intensity_discharge_headlights = $vin_query_decode["decoded_high_intensity_discharge_headlights"]; } else {$decoded_high_intensity_discharge_headlights = ''; }
	if (isset($vin_query_decode["decoded_pickup_truck_cargo_box_light"])) {$decoded_pickup_truck_cargo_box_light = $vin_query_decode["decoded_pickup_truck_cargo_box_light"]; } else {$decoded_pickup_truck_cargo_box_light = ''; }
	if (isset($vin_query_decode["decoded_running_boards"])) {$decoded_running_boards = $vin_query_decode["decoded_running_boards"]; } else {$decoded_running_boards = ''; }
	if (isset($vin_query_decode["decoded_front_air_dam"])) {$decoded_front_air_dam = $vin_query_decode["decoded_front_air_dam"]; } else {$decoded_front_air_dam = ''; }
	if (isset($vin_query_decode["decoded_rear_spoiler"])) {$decoded_rear_spoiler = $vin_query_decode["decoded_rear_spoiler"]; } else {$decoded_rear_spoiler = ''; }
	if (isset($vin_query_decode["decoded_skid_plate"])) {$decoded_skid_plate = $vin_query_decode["decoded_skid_plate"]; } else {$decoded_skid_plate = ''; }
	if (isset($vin_query_decode["decoded_splash_guards"])) {$decoded_splash_guards = $vin_query_decode["decoded_splash_guards"]; } else {$decoded_splash_guards = ''; }
	if (isset($vin_query_decode["decoded_wind_deflector_for_convertibles"])) {$decoded_wind_deflector_for_convertibles = $vin_query_decode["decoded_wind_deflector_for_convertibles"]; } else {$decoded_wind_deflector_for_convertibles = ''; }
	if (isset($vin_query_decode["decoded_power_sliding_side_van_door"])) {$decoded_power_sliding_side_van_door = $vin_query_decode["decoded_power_sliding_side_van_door"]; } else {$decoded_power_sliding_side_van_door = ''; }
	if (isset($vin_query_decode["decoded_power_trunk_lid"])) {$decoded_power_trunk_lid = $vin_query_decode["decoded_power_trunk_lid"]; } else {$decoded_power_trunk_lid = ''; }
	if (isset($vin_query_decode["decoded_alloy_wheels"])) {$decoded_alloy_wheels = $vin_query_decode["decoded_alloy_wheels"]; } else {$decoded_alloy_wheels = ''; }
	if (isset($vin_query_decode["decoded_chrome_wheels"])) {$decoded_chrome_wheels = $vin_query_decode["decoded_chrome_wheels"]; } else {$decoded_chrome_wheels = ''; }
	if (isset($vin_query_decode["decoded_steel_wheels"])) {$decoded_steel_wheels = $vin_query_decode["decoded_steel_wheels"]; } else {$decoded_steel_wheels = ''; }
	if (isset($vin_query_decode["decoded_full_size_spare_tire"])) {$decoded_full_size_spare_tire = $vin_query_decode["decoded_full_size_spare_tire"]; } else {$decoded_full_size_spare_tire = ''; }
	if (isset($vin_query_decode["decoded_run_flat_tires"])) {$decoded_run_flat_tires = $vin_query_decode["decoded_run_flat_tires"]; } else {$decoded_run_flat_tires = ''; }
	if (isset($vin_query_decode["decoded_power_windows"])) {$decoded_power_windows = $vin_query_decode["decoded_power_windows"]; } else {$decoded_power_windows = ''; }
	if (isset($vin_query_decode["decoded_glass_rear_window_on_convertible"])) {$decoded_glass_rear_window_on_convertible = $vin_query_decode["decoded_glass_rear_window_on_convertible"]; } else {$decoded_glass_rear_window_on_convertible = ''; }
	if (isset($vin_query_decode["decoded_sliding_rear_pickup_truck_window"])) {$decoded_sliding_rear_pickup_truck_window = $vin_query_decode["decoded_sliding_rear_pickup_truck_window"]; } else {$decoded_sliding_rear_pickup_truck_window = ''; }
	if (isset($vin_query_decode["decoded_electrochromic_exterior_rearview_mirror"])) {$decoded_electrochromic_exterior_rearview_mirror = $vin_query_decode["decoded_electrochromic_exterior_rearview_mirror"]; } else {$decoded_electrochromic_exterior_rearview_mirror = ''; }
	if (isset($vin_query_decode["decoded_heated_exterior_mirror"])) {$decoded_heated_exterior_mirror = $vin_query_decode["decoded_heated_exterior_mirror"]; } else {$decoded_heated_exterior_mirror = ''; }
	if (isset($vin_query_decode["decoded_electrochromic_interior_rearview_mirror"])) {$decoded_electrochromic_interior_rearview_mirror = $vin_query_decode["decoded_electrochromic_interior_rearview_mirror"]; } else {$decoded_electrochromic_interior_rearview_mirror = ''; }
	if (isset($vin_query_decode["decoded_power_adjustable_exterior_mirror"])) {$decoded_power_adjustable_exterior_mirror = $vin_query_decode["decoded_power_adjustable_exterior_mirror"]; } else {$decoded_power_adjustable_exterior_mirror = ''; }
	if (isset($vin_query_decode["decoded_interval_wipers"])) {$decoded_interval_wipers = $vin_query_decode["decoded_interval_wipers"]; } else {$decoded_interval_wipers = ''; }
	if (isset($vin_query_decode["decoded_rain_sensing_wipers"])) {$decoded_rain_sensing_wipers = $vin_query_decode["decoded_rain_sensing_wipers"]; } else {$decoded_rain_sensing_wipers = ''; }
	if (isset($vin_query_decode["decoded_rear_wiper"])) {$decoded_rear_wiper = $vin_query_decode["decoded_rear_wiper"]; } else {$decoded_rear_wiper = ''; }
	if (isset($vin_query_decode["decoded_rear_window_defogger"])) {$decoded_rear_window_defogger = $vin_query_decode["decoded_rear_window_defogger"]; } else {$decoded_rear_window_defogger = ''; }
	if (isset($vin_query_decode["decoded_tow_hitch_receiver"])) {$decoded_tow_hitch_receiver = $vin_query_decode["decoded_tow_hitch_receiver"]; } else {$decoded_tow_hitch_receiver = ''; }
	if (isset($vin_query_decode["decoded_towing_preparation_package"])) {$decoded_towing_preparation_package = $vin_query_decode["decoded_towing_preparation_package"]; } else {$decoded_towing_preparation_package = ''; }
	$x ='
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Climate Control</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Air Conditioning</td>
		<td>'.decode_select('decoded_air_conditioning',$decoded_air_conditioning, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Separate Driver/Front Passenger Climate Controls</td>
		<td>'.decode_select('decoded_separate_driver_front_passenger_climate_controls',$decoded_separate_driver_front_passenger_climate_controls, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Seat</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Driver Multi-Adjustable Power Seat</td>
		<td>'.decode_select('decoded_driver_multi_adjustable_power_seat',$decoded_driver_multi_adjustable_power_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Cooled Seat</td>
		<td>'.decode_select('decoded_front_cooled_seat',$decoded_front_cooled_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Heated Seat</td>
		<td>'.decode_select('decoded_front_heated_seat',$decoded_front_heated_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Power Lumbar Support</td>
		<td>'.decode_select('decoded_front_power_lumbar_support',$decoded_front_power_lumbar_support, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Power Memory Seat</td>
		<td>'.decode_select('decoded_front_power_memory_seat',$decoded_front_power_memory_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Split Bench Seat</td>
		<td>'.decode_select('decoded_front_split_bench_seat',$decoded_front_split_bench_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Leather Seat</td>
		<td>'.decode_select('decoded_leather_seat',$decoded_leather_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Passenger Multi-Adjustable Power Seat</td>
		<td>'.decode_select('decoded_passenger_multi_adjustable_power_seat',$decoded_passenger_multi_adjustable_power_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Folding Seat</td>
		<td>'.decode_select('decoded_second_row_folding_seat',$decoded_second_row_folding_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Heated Seat</td>
		<td>'.decode_select('decoded_second_row_heated_seat',$decoded_second_row_heated_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Multi-Adjustable Power Seat</td>
		<td>'.decode_select('decoded_second_row_multi_adjustable_power_seat',$decoded_second_row_multi_adjustable_power_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Removable Seat</td>
		<td>'.decode_select('decoded_second_row_removable_seat',$decoded_second_row_removable_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Third Row Removable Seat</td>
		<td>'.decode_select('decoded_third_row_removable_seat',$decoded_third_row_removable_seat, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Exterior Lighting</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Automatic Headlights</td>
		<td>'.decode_select('decoded_automatic_headlights',$decoded_automatic_headlights, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Daytime Running Lights</td>
		<td>'.decode_select('decoded_daytime_running_lights',$decoded_daytime_running_lights, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Fog Lights</td>
		<td>'.decode_select('decoded_fog_lights',$decoded_fog_lights, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;High Intensity Discharge Headlights</td>
		<td>'.decode_select('decoded_high_intensity_discharge_headlights',$decoded_high_intensity_discharge_headlights, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Pickup Truck Cargo Box Light</td>
		<td>'.decode_select('decoded_pickup_truck_cargo_box_light',$decoded_pickup_truck_cargo_box_light, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Exterior Features</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Bodyside/Cab Step or Running Board</td>
		<td>'.decode_select('decoded_running_boards',$decoded_running_boards, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Front Air Dam</td>
		<td>'.decode_select('decoded_front_air_dam',$decoded_front_air_dam, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Rear Spoiler</td>
		<td>'.decode_select('decoded_rear_spoiler',$decoded_rear_spoiler, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Skid Plate or Underbody Protection</td>
		<td>'.decode_select('decoded_skid_plate',$decoded_skid_plate, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Splash Guards</td>
		<td>'.decode_select('decoded_splash_guards',$decoded_splash_guards, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Wind Deflector or Buffer for Convertible</td>
		<td>'.decode_select('decoded_wind_deflector_for_convertibles',$decoded_wind_deflector_for_convertibles, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Power Sliding Side Van Door</td>
		<td>'.decode_select('decoded_power_sliding_side_van_door',$decoded_power_sliding_side_van_door, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Power Trunk Lid</td>
		<td>'.decode_select('decoded_power_trunk_lid',$decoded_power_trunk_lid, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Wheels</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Alloy Wheels</td>
		<td>'.decode_select('decoded_alloy_wheels',$decoded_alloy_wheels, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Chrome Wheels</td>
		<td>'.decode_select('decoded_chrome_wheels',$decoded_chrome_wheels, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Steel Wheels</td>
		<td>'.decode_select('decoded_steel_wheels',$decoded_steel_wheels, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Tires</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Full Size Spare Tire</td>
		<td>'.decode_select('decoded_full_size_spare_tire',$decoded_full_size_spare_tire, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Run Flat Tires</td>
		<td>'.decode_select('decoded_run_flat_tires',$decoded_run_flat_tires, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Windows</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Power Windows</td>
		<td>'.decode_select('decoded_power_windows',$decoded_power_windows, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Glass Rear Window on Convertible</td>
		<td>'.decode_select('decoded_glass_rear_window_on_convertible',$decoded_glass_rear_window_on_convertible, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Sliding Rear Pickup Truck Window</td>
		<td>'.decode_select('decoded_sliding_rear_pickup_truck_window',$decoded_sliding_rear_pickup_truck_window, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Mirrors</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Electrochromic Exterior Rearview Mirror</td>
		<td>'.decode_select('decoded_electrochromic_exterior_rearview_mirror',$decoded_electrochromic_exterior_rearview_mirror, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Heated Exterior Mirror</td>
		<td>'.decode_select('decoded_heated_exterior_mirror',$decoded_heated_exterior_mirror, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Electrochromic Interior Rearview Mirror</td>
		<td>'.decode_select('decoded_electrochromic_interior_rearview_mirror',$decoded_electrochromic_interior_rearview_mirror, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Power Adjustable Exterior Mirror</td>
		<td>'.decode_select('decoded_power_adjustable_exterior_mirror',$decoded_power_adjustable_exterior_mirror, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Wipers</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Interval Wipers</td>
		<td>'.decode_select('decoded_interval_wipers',$decoded_interval_wipers, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Rain Sensing Wipers</td>
		<td>'.decode_select('decoded_rain_sensing_wipers',$decoded_rain_sensing_wipers, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Rear Wiper</td>
		<td>'.decode_select('decoded_rear_wiper',$decoded_rear_wiper, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Rear Window Defogger</td>
		<td>'.decode_select('decoded_rear_window_defogger',$decoded_rear_window_defogger, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Towings</strong></td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Tow Hitch Receiver</td>
		<td>'.decode_select('decoded_tow_hitch_receiver',$decoded_tow_hitch_receiver, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Towing Preparation Package</td>
		<td>'.decode_select('decoded_towing_preparation_package',$decoded_towing_preparation_package, $post_id).'</td>
	  </tr>
	</table>
	';
	return $x;
}

function get_vin_query_entertainment_admin($vin_query_decode, $post_id) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	if (isset($vin_query_decode["decoded_am_fm_radio"])) {$decoded_am_fm_radio = $vin_query_decode["decoded_am_fm_radio"]; } else {$decoded_am_fm_radio = ''; }
	if (isset($vin_query_decode["decoded_cassette_player"])) {$decoded_cassette_player = $vin_query_decode["decoded_cassette_player"]; } else {$decoded_cassette_player = ''; }
	if (isset($vin_query_decode["decoded_cd_player"])) {$decoded_cd_player = $vin_query_decode["decoded_cd_player"]; } else {$decoded_cd_player = ''; }
	if (isset($vin_query_decode["decoded_cd_changer"])) {$decoded_cd_changer = $vin_query_decode["decoded_cd_changer"]; } else {$decoded_cd_changer = ''; }
	if (isset($vin_query_decode["decoded_dvd_player"])) {$decoded_dvd_player = $vin_query_decode["decoded_dvd_player"]; } else {$decoded_dvd_player = ''; }
	if (isset($vin_query_decode["decoded_voice_activated_telephone"])) {$decoded_voice_activated_telephone = $vin_query_decode["decoded_voice_activated_telephone"]; } else {$decoded_voice_activated_telephone = ''; }
	if (isset($vin_query_decode["decoded_navigation_aid"])) {$decoded_navigation_aid = $vin_query_decode["decoded_navigation_aid"]; } else {$decoded_navigation_aid = ''; }
	if (isset($vin_query_decode["decoded_second_row_sound_controls"])) {$decoded_second_row_sound_controls = $vin_query_decode["decoded_second_row_sound_controls"]; } else {$decoded_second_row_sound_controls = ''; }
	if (isset($vin_query_decode["decoded_subwoofer"])) {$decoded_subwoofer = $vin_query_decode["decoded_subwoofer"]; } else {$decoded_subwoofer = ''; }
	if (isset($vin_query_decode["decoded_telematics_system"])) {$decoded_telematics_system = $vin_query_decode["decoded_telematics_system"]; } else {$decoded_telematics_system = ''; }
	$x = '
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td colspan="2"><strong>Equipment - Entertainment, Communication & Navigation</strong></td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;AM/FM Radio</td>
		<td>'.decode_select('decoded_am_fm_radio',$decoded_am_fm_radio, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Cassette Player</td>
		<td>'.decode_select('decoded_cassette_player',$decoded_cassette_player, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;CD Player</td>
		<td>'.decode_select('decoded_cd_player',$decoded_cd_player, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;CD Changer</td>
		<td>'.decode_select('decoded_cd_changer',$decoded_cd_changer, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;DVD Player</td>
		<td>'.decode_select('decoded_dvd_player',$decoded_dvd_player, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Hands Free/Voice Activated Telephone</td>
		<td>'.decode_select('decoded_voice_activated_telephone',$decoded_voice_activated_telephone, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Navigation Aid</td>
		<td>'.decode_select('decoded_navigation_aid',$decoded_navigation_aid, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Second Row Sound Controls or Accessories</td>
		<td>'.decode_select('decoded_second_row_sound_controls',$decoded_second_row_sound_controls, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_odd">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Subwoofer</td>
		<td>'.decode_select('decoded_subwoofer',$decoded_subwoofer, $post_id).'</td>
	  </tr>
	  <tr class="decode_table_even">
		<td class="decode_table_label">&nbsp;&nbsp;&nbsp;Telematic Systems</td>
		<td>'.decode_select('decoded_telematics_system',$decoded_telematics_system, $post_id).'</td>
	  </tr>
	</table>
	';
	return $x;
}

function decode_select($fld, $val, $post_id) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query','',$car_demon_pluginpath);
	$val = trim($val);
	$no_check = '';
	$standard_checked = '';
	$option_checked = '';
	$na_checked = '';
	$img = '';
	if ($val == '') {
		$no_check = ' selected';
		$img = '<img id="img_'.$fld.'" src="'.$car_demon_pluginpath . 'theme-files/images/spacer.gif" width="22" height="24" title="Standard Option" alt="Standard Option" />';	
	}
	if ($val == 'Std.') {
		$standard_checked = ' selected';
		$img = '<img id="img_'.$fld.'" src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="Standard Option" alt="Standard Option" />';
	}
	if ($val == 'Opt.') {
		$option_checked = ' selected';
		$img = '<img id="img_'.$fld.'" src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="Optional" alt="Optional" />';	
	}
	if ($val == 'N/A') {
		$na_checked = ' selected';
		$img = '<img id="img_'.$fld.'" src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="NA" alt="NA" />';
	}
	$x = $img.'&nbsp;<select onchange="update_decode_option(this, '.$post_id.')" id="'.$fld.'">';
		$x .= '<option value=""'.$no_check.'>None</option>';
		$x .= '<option value="Std."'.$standard_checked.'>Standard</option>';
		$x .= '<option value="Opt."'.$option_checked.'>Optional</option>';
		$x .= '<option value="N/A"'.$na_checked.'>Not Available</option>';
	$x .= '</select>';
	return $x;
}
?>