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

function car_demon_vinquery_scripts() {
	wp_register_script('car-demon-vinquery-js', WP_CONTENT_URL . '/plugins/car-demon/admin/js/car-demon-admin.js');
	wp_localize_script('car-demon-vinquery-js', 'cdVinQueryParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'car_demon_path' => CAR_DEMON_PATH
	));
	wp_enqueue_script('car-demon-vinquery-js');
}
add_action("wp_ajax_car_demon_vinquery", "car_demon_vinquery");
add_action("wp_ajax_nopriv_car_demon_vinquery", "car_demon_vinquery");

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
	if ( $post && $post->post_type )
		$post_type = $post->post_type;
	elseif( $typenow )
		$post_type = $typenow;
	elseif( $current_screen && $current_screen->post_type )
		$post_type = $current_screen->post_type;
	elseif( isset( $_REQUEST['post_type'] ) )
		$post_type = sanitize_key( $_REQUEST['post_type'] );
	elseif ( isset($_GET['post']) )
		$post_type = get_post_type($_GET['post']);
	else
		$post_type = 'post';
	return $post_type;
}
function cardemons_automotive_inventory_decode_header() {
	wp_register_script('car-demon-vin-query-admin-js', WP_CONTENT_URL . '/plugins/car-demon/vin-query/js/car-demon-vin-query.js');
	wp_localize_script('car-demon-vin-query-admin-js', 'cdVinQueryParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'car_demon_path' => CAR_DEMON_PATH
	));
	wp_enqueue_script('car-demon-vin-query-admin-js');
	wp_enqueue_script('car-demon-jquery-lightbox', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/jquery.lightbox_me.js', array('jquery'));
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
	$html = '';
	$show_tabs = 1;
	if (isset($_SESSION['car_demon_options']['hide_tabs'])) {
		if ($_SESSION['car_demon_options']['hide_tabs'] == 'Yes') {
			$show_tabs = 0;
		}
	}
	if ($show_tabs == 1) {
		$html .= __('Hide Tabs on vehicle display page?', 'car-demon').'
			<select name="hide_tabs" id="hide_tabs" onchange="update_admin_decode(this, '.$post_id.')">
				<option value="'.$vin_query_decode['hide_tabs'].'">'.$vin_query_decode['hide_tabs'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />
		'.__('This option does not hide the description tab or the about us tab.','car-demon').'
		<hr />';
	} else {
		$html .= __('Vehicle Option Tabs have been set to hidden under Car Demon settings and will not appear on the front end.', 'car_demon_options');		
	}
	$html .= '
	<div id="vin_decode_options_'.$post_id.'">';
		$specs = get_vin_query_specs_admin($vin_query_decode, $vin, $post_id);
		$safety = get_option_tab('safety',$post_id,'admin');
		$convienience = get_option_tab('convenience',$post_id,'admin');
		$comfort = get_option_tab('comfort',$post_id,'admin');
		$entertainment = get_option_tab('entertainment',$post_id,'admin');
	//	echo get_option_tab('about_us',$post_id,'admin');
		echo '<hr />';
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
//= Feature being deprecated in favor of new custom vehicle option feature.
//	add_meta_box('decode-custom', 'Custom Options', 'decode_custom_metabox', 'cars_for_sale', 'normal', 'high');
	add_meta_box('decode-status', 'Sales Status', 'decode_sales_metabox', 'cars_for_sale', 'side', 'high');
	add_meta_box('decode-ribbon', 'Photo Ribbon', 'decode_photo_ribbon', 'cars_for_sale', 'side', 'default');
	add_meta_box('decode-images', 'Vehicle Photos', 'decode_images', 'cars_for_sale', 'side', 'default');
}
function decode_custom_metabox($post) {
	$content = '';
	$vehicle_options = '<div style="overflow:hidden;">';
	$post_id = $post->ID;
	$vehicle_options_list = get_post_meta($post_id, '_vehicle_options', true);
	$custom_option_list = $_SESSION['car_demon_options']['custom_options'];
	$custom_option_list_array = split(',', $custom_option_list);
	$select_custom_options = '';
	foreach ($custom_option_list_array as $custom_item) {
		$select_custom_options .= '<option value="'.$custom_item.'">'.$custom_item.'</option>';
	}
	$vehicle_options .= '<div class="custom_option_container">
			<h3>
			Add custom vehicle options here
			</h3>
			<div class="cd_select_custom_options_container" id="cd_select_custom_options_container">
				'.__('Available', 'car-demon').'<br />
				<select size="5" id="ListBox1" class="cd_select_custom_options" id="cd_select_custom_options">'.$select_custom_options.'</select>
				<br /><input type = "button" id = "btnMoveRight" class="btn_move_right" value="'.__('Add To Vehicle', 'car-demon').' ->" onclick = "fnMoveItems(\'ListBox1\',\'vehicle_options\');update_admin_decode(document.getElementById(\'vehicle_options\'), '.$post_id.')">
			</div>
			<div class="cd_custom_options_box_container" id="cd_custom_options_box_container">
				'.__('Current Options', 'car-demon').'<br />
				<textarea cols="60" class="cd_custom_options_box" id="vehicle_options" name="vehicle_options" onchange="update_admin_decode(this, '.$post_id.')">'.$vehicle_options_list.'</textarea>
			</div>
			<div class="custom_option_directions" id="custom_option_directions">
			'.__('You can select from the list or you can manually add and remove options in the box on the left. Make sure you seperate each option with a comma.', 'car-demon').'
			</div>
		</div>';
	$vehicle_options_array = split(',',$vehicle_options_list);
	$options_image = '<img src="'.WP_CONTENT_URL . '/plugins/car-demon/theme-files/images/opt_standard.gif" />';
	$include_options = 0;
	foreach ($vehicle_options_array as $vehicle_option) {
		if (!empty($vehicle_option)) {
			$include_options = 1;
			$vehicle_options .= '<div style="float:left;width:260px;">';
				$vehicle_options .= $options_image .'&nbsp;'. $vehicle_option.'<br />';
			$vehicle_options .= '</div>';
		}
	}
	$vehicle_options .= '</div>';
	if ($include_options == 1) {
		$content .= $vehicle_options;
	}
	echo $content;
	return;
}
function decode_images($post) {
	// Show currently attached photos
	$post_id = $post->ID;
	echo '
	<div align="center">
		<a href="#" class="custom_media_upload" id="manage_vehicle_photos"><input type="button" value="Manage Photos" class="wp-core-ui button-primary" /></a>
	</div>
	<img class="custom_media_image" src="" />
		<input class="custom_media_url" type="hidden" name="attachment_url" value="">
		<input class="custom_media_id" type="hidden" name="attachment_id" value="">
		<input type="hidden" name="attachment_post_id" id="attachment_post_id" value="'.$post_id.'">
	';
	$image_list = get_post_meta($post_id, '_images_value', true);
	$this_car = '';
	$cnt = 1;
	if (!empty($image_list)) {
		echo '<h3>Imported Photos</h3><br />';
		$thumbnails = split(",",$image_list);
		foreach($thumbnails as $thumbnail) {
			$pos = strpos($thumbnail,'.jpg');
			if($pos == true) {
				$photo_array = '<div id="car_photo_'.$cnt.'" name="car_photo_'.$cnt.'" class="car_photo_admin_box">';
					$photo_array .= '<div class="car_photo_remove" onclick="remove_linked_car_image('.$post_id.', \''.trim($thumbnail).'\', '.$cnt.')">';
						$photo_array .= 'X';
					$photo_array .= '</div>';
					$photo_array .= '<div align="center">';
						$photo_array .= '<img class="car_demon_thumbs" style="cursor:pointer"'.$popup_imgs.' src="'.trim($thumbnail).'" width="162" />';
					$photo_array .= '</div>';
				$photo_array .= '</div>';
				$this_car .= $photo_array;
				$cnt = $cnt + 1;
			}
		}
	}
	echo $this_car;
	$this_car = '';
	$thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image', 'orderby' => 'menu_order ID') );
	echo '<h3>Attached Photos</h3><br />';
		foreach($thumbnails as $thumbnail) {
			$guid = wp_get_attachment_url($thumbnail->ID);
			if (!empty($guid)) {
				$photo_array = '<div id="car_photo_'.$cnt.'" name="car_photo_'.$cnt.'" class="car_photo_admin_box">';
					$photo_array .= '<div class="car_photo_remove" onclick="remove_attached_car_image('.$post_id.', \''.$thumbnail->ID.'\', '.$cnt.')">';
						$photo_array .= 'X';
					$photo_array .= '</div>';
					$photo_array .= '<div align="center">';
						$photo_array .= '<img class="car_demon_thumbs" style="cursor:pointer"'.$popup_imgs.' src="'.trim($guid).'" width="162" />';
					$photo_array .= '</div>';
				$photo_array .= '</div>';
				$this_car .= $photo_array;
			}
		}
	$photo_array = '<div id="car_photo_attachments">'.$photo_array.'</div>';	
	echo $this_car;
	return;
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
function get_about_us_tab($post_id) {
	$map = cd_get_vehicle_map();
	$x = '';
	if (isset($map['about_us'])) {
		foreach($map['about_us'] as $tab_group => $value) {
			$x .= '<h2>'.$tab_group.'</h2>';
			$x .= '<p>'.$value.'</p>';
		}
	}
	return $x;	
}

function decode_select($fld, $val, $post_id) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
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