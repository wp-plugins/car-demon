<?php

function get_car_title($post_id) {
	If ($_SESSION['car_demon_options']['use_post_title'] == 'Yes') {
		$car_title = get_the_title($post_id);
	} else {
		$car_title = '';
	}
	if (empty($car_title)) {
		$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
		$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
		$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
		$car_title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
	}
	$car_title = trim($car_title);
	$car_title = substr($car_title, 0, 19);
	return $car_title;
}

function get_car_title_slug($post_id) {
	$car_title = get_car_title($post_id);
	$car_title = strtolower($car_title);
	$car_title = trim($car_title);
	$car_title = str_replace(chr(32), '_', $car_title);
	return $car_title;
}

function car_demon_nav($position,$search_query) {
	$x = '';
	if ($position == 'top') {
		$second_position = 'above';
		$third_position = '-top';
	}
	if ($position == 'bottom') {
		$second_position = 'below';
		$third_position = '';
	}
	if ( $search_query->max_num_pages > 1 ) {
		$x .= '<div id="cd-nav-'.$second_position.'" class="navigation'.$third_position.' inventory_nav_'.$position.'">';
		if(function_exists('wp_pagenavi')) {  
			$nav_list_str = wp_pagenavi(array( 'query' => $search_query, 'echo' => false )); 
			if ($position == 'top') {
				$nav_list_str = str_replace('nextpostslink','nextpostslink-'.$second_position,$nav_list_str);
			}
			$x .= $nav_list_str;
		} else { 
			$x .= '<div class="nav-previous"> '.next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'car-demon' ) ) .'</div>';
			$x .= '<div class="nav-next"> '.previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'car-demon' ) ) .'</div>';
		} 
		$x .= '</div><!-- #nav-'.$second_position.' -->';
	} else {
		$x .= '<div id="cd-nav-'.$second_position.'" class="navigation-'.$position.' inventory_nav"><span class="wp-pagenavi"><span class="pages">'. $wp_query->post_count; _e('Results Found', 'car-demon') .'</span></span>';
		$x .= '</div>';
	}
	$x = str_replace('none', '', $x);
	return $x;
}

//=====Functions used exclusively in single-cars_for_sale.php
function car_demon_photo_lightbox() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('includes/', '', $car_demon_pluginpath);
	$x = '<div class="car_demon_light_box" id="car_demon_light_box">
		<div class="car_demon_photo_box" id="car_demon_photo_box"">
			<div class="close_light_box" onclick="close_car_demon_lightbox();">(close) X</div>
			<div class="car_demon_light_box_main_email" id="car_demon_light_box_main_email"></div>
			<div class="car_demon_light_box_main" id="car_demon_light_box_main">
				<img id="car_demon_light_box_main_img" src="" />
				<div class="run_slideshow_div" onclick="car_slide_show();" id="run_slideshow_div">
						<input type="checkbox" id="run_slideshow" /> '. __('Run Slideshow', 'car-demon').'
				</div>
				<div class="photo_next" id="photo_next">
					<img src="'. $car_demon_pluginpath.'theme-files/images/btn_next.png" onclick="get_next_img();" title="'. __('Next', 'car-demon') .'" />
				</div>
				<div class="photo_prev" id="photo_prev">
					<img src="'. $car_demon_pluginpath.'theme-files/images/btn_prev.png" onclick="get_prev_img();" title="'. __('Previous', 'car-demon') .'" />
				</div>
			</div>
			<div class="hor_lightbox" id="car_demon_thumb_box">
			</div>
		</div>
	</div>';
	return $x;
}

function car_demon_email_a_friend($post_id, $vehicle_stock_number) {
	global $cd_formKey;
	$x = '<div id="email_friend_div" class="email_friend_div">
		<div id="ef_contact_final_msg_tmp" class="ef_contact_final_msg_tmp"></div>
		<div id="main_email_friend_div_tmp" class="main_email_friend_div_tmp">
		<h2>'. __('Send this car to a friend', 'car-demon') .'</h2><hr />
			<form enctype="multicontact/form-data" action="?send_contact=1" method="post" class="cdform contact-appointment" id="email_friend_form_tmp" name="email_friend_form_tmp">
			'. $cd_formKey->outputKey() .'
			<input type="hidden" name="ef_stock_num_tmp" id="ef_stock_num_tmp" value="<?php echo $vehicle_stock_number; ?>" />
					<fieldset class="cd-fs1">
					<legend>'. __('Your Information', 'car-demon') .'</legend>
					<ol class="cd-ol">
						<li class=""><label for="cd_field_2"><span>'. __('Your Name', 'car-demon') .'</span></label><input type="text" name="ef_cd_name_tmp" id="ef_cd_name_tmp" class="single fldrequired" value="'. __('Your Name', 'car-demon') .'" onfocus="ef_clearField(this)" onblur="ef_setField(this)"><span class="reqtxt">*</span></li>
						<li class=""><label for="cd_field_4"><span>'. __('Your Email', 'car-demon') .'</span></label><input type="text" name="ef_cd_email_tmp" id="ef_cd_email_tmp" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
						<li class=""><label for="cd_field_2"><span>'. __('Friend\'s Name', 'car-demon') .'</span></label><input type="text" name="ef_cd_friend_name_tmp" id="ef_cd_friend_name_tmp" class="single fldrequired" value="'. __('Friend Name', 'car-demon') .'" onfocus="ef_clearField(this)" onblur="ef_setField(this)"><span class="reqtxt">*</span></li>
						<li class=""><label for="cd_field_4"><span>'. __('Friend\'s Email', 'car-demon') .'</span></label><input type="text" name="ef_cd_friend_email_tmp" id="ef_cd_friend_email_tmp" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
					</ol>
					</fieldset>
					<fieldset class="cd-fs4">
					<legend>'. __('Your Message', 'car-demon') .'</legend>
					<ol class="cd-ol">
						<li id="li-5" class=""><textarea name="ef_comment_tmp" id="ef_comment_tmp" class="ef_comment_tmp">'. __('Check out this', 'car-demon') .' '. $car_head_title .', '. __('stock number', 'car-demon') .' '. $vehicle_stock_number .'!</textarea><br><span class="reqtxt ef_reqtxt"><br>* '. __('required', 'car-demon') .'</span></li>
					</ol>
					</fieldset>
					<div id="ef_contact_msg_tmp"></div>
					<p class="cd-sb"><input type="button" name="ef_search_btn_tmp" id="ef_sendbutton_tmp" class="search_btn ef_search_btn" value="'. __('Send Now!', 'car-demon') .'" onclick="return ef_car_demon_validate()"></p>
			</form>
		</div>
	</div>';
	return $x;
}

function car_demon_vehicle_detail_tabs($post_id) {
	$tab_cnt = 1;
	$vin_query = '';
	$about_cnt = 2;
	$content = get_the_content();
	$content = trim($content);
	if (empty($content)) {
		$location_lists = get_the_terms($post_id, "vehicle_location");
		if ($location_lists) {
			foreach ($location_lists as $location_list) {
				$location_slug = $location_list->slug;
			}
		}
		else {
			$location_slug = "default";
		}
		$content = get_option($location_slug.'_default_description');
		if (empty($content)) {
			$content = get_default_description();
		}
	}
	if ($_SESSION['car_demon_options']['use_about'] == 'Yes') {
		$about = 1;
		$tab_cnt = $tab_cnt + 1;
	} else {
		$about = '';
	}
	if (!empty($_SESSION['car_demon_options']['vinquery_id'])) {
		if ($vehicle_year > 1984) {
			car_demon_get_vin_query($post_id, $vehicle_vin);
		}
	}
	$vin_query_decode_array = get_post_meta($post_id, 'decode_string');
	if ($vin_query_decode_array) {
		$vin_query_decode = $vin_query_decode_array[0];
	} else {
		$vin_query_decode = '';
	}
	if (!empty($vin_query_decode['decoded_fuel_economy_city'])) {
		$tab_cnt = $tab_cnt + 5;
		$vin_query = 1;
		$about_cnt = 7;
	} else {
		$vin_query = 0;
	}
	$x = '<div id="car_features_box" class="car_features_box">';
		$x .= '<div class="car_features">';
			$x .= '<ul class="tabs">';
				$x .= '<li><a href="javascript:car_demon_switch_tabs(1, '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_1" class="active">'. __('Description', 'car-demon') .'</a></li>';
				 if ($vin_query == 1) { 
					$x .= '<li><a href="javascript:car_demon_switch_tabs(2, '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_2">'. __('Specs', 'car-demon') .'</a></li>';
					$x .= '<li><a href="javascript:car_demon_switch_tabs(3, '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_3">'. __('Safety', 'car-demon') .'</a></li>';
					$x .= '<li><a href="javascript:car_demon_switch_tabs(4, '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_4">'. __('Convenience', 'car-demon') .'</a></li>';
					$x .= '<li><a href="javascript:car_demon_switch_tabs(5, '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_5">'. __('Comfort', 'car-demon') .'</a></li>';
					$x .= '<li><a href="javascript:car_demon_switch_tabs(6, '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_6">'. __('Entertainment', 'car-demon') .'</a></li>';
				 } 
				 if ($about == 1) { 
					$x .= '<li><a href="javascript:car_demon_switch_tabs('.  $about_cnt .', '.  $tab_cnt .', \'tab_\', \'content_\');" id="tab_'.  $about_cnt .'">'. __('About', 'car-demon') .'</a></li>';
				 } 
			$x .= '</ul>';
			$x .= '<div id="content_1" class="car_features_content">'.  $content .'</div>';
			 if ($vin_query == 1) {
				$specs = get_vin_query_specs($vin_query_decode, $vehicle_vin);
				$safety = get_vin_query_safety($vin_query_decode);
				$convienience = get_vin_query_convienience($vin_query_decode);
				$comfort = get_vin_query_comfort($vin_query_decode);
				$entertainment = get_vin_query_entertainment($vin_query_decode);
				$x .= '<div id="content_2" class="car_features_content">'.  $specs .'</div>';
				$x .= '<div id="content_3" class="car_features_content">'.  $safety .'</div>';
				$x .= '<div id="content_4" class="car_features_content">'.  $convienience .'</div>';
				$x .= '<div id="content_5" class="car_features_content">'.  $comfort .'</div>';
				$x .= '<div id="content_6" class="car_features_content">'.  $entertainment .'</div>';
			 } 
			 if ($about == 1) { 
					$x .= '<div id="content_'.  $about_cnt .'" class="car_features_content car_features_content_about">';
						 if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  
							$x .= '<div id="entry-author-info">';
								if ($_COOKIE['sales_code']) {
									$user_id = $_COOKIE['sales_code'];
									$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
									$location_approved = 0;
									if ($vehicle_location == $user_location) {
										$location_approved = 1;
									}
									else {
										$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
									}
								}
								if ($location_approved == 1) {
									$user_sales_type = 0;
									if ($vehicle_condition == 'New') {
										$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);	
									}
									else {
										$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);		
									}
								}
								if ($user_sales_type == 1) {
									$x .= build_user_hcard($_COOKIE['sales_code'], 1);
								}
								else {
									$x .= build_location_hcard($vehicle_location, $vehicle_condition);
								} 
							$x .= '</div><!-- #entry-author-info -->';
						 endif;
					$x .= '</div>';
			 } 
		$x .= '</div>';
	$x .= '</div>';
	return $x;
}
?>