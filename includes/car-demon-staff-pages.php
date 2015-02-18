<?php
function car_demon_staff_page() {
	$x = car_demon_corporate_users();
	$locations = get_location_array();
	$x .= '<div class="staff_box">';
	foreach ($locations as $location) {
		$x .= '<div class="staff_details">';
			$x .= car_demon_get_user_cards($location, "general_manager");
			$x .= car_demon_get_user_cards($location, 'sales_manager');
			$x .= car_demon_get_user_cards($location, 'sales_staff');
			$x .= car_demon_get_user_cards($location, 'finance_manager');
			$x .= car_demon_get_user_cards($location, 'finance_staff');
			$x .= car_demon_get_user_cards($location, 'service_manager');
			$x .= car_demon_get_user_cards($location, 'service_staff');
			$x .= car_demon_get_user_cards($location, 'parts_manager');
			$x .= car_demon_get_user_cards($location, 'parts_staff');
			$x .= car_demon_get_user_cards($location, 'office_manager');
			$x .= car_demon_get_user_cards($location, 'office_staff');
			$x .= car_demon_get_user_cards($location, 'support_staff');
		$x .= '</div>';
	}
	$x .= '</div>';
	return $x;
}
function car_demon_corporate_users() {
	global $wpdb;
	$x = '';
	$prefix = $wpdb->prefix;
	$sql = "SELECT ".$prefix."users.ID
		FROM ".$prefix."users
			LEFT JOIN ".$prefix."usermeta ON ".$prefix."users.ID = ".$prefix."usermeta.user_id
		WHERE ".$prefix."usermeta.meta_key = 'staff_type' AND ".$prefix."usermeta.meta_value = 'corporate_manager'";
	$users = $wpdb->get_results($sql);
	if ($users) {
		foreach ($users as $user) {
			$user_id = $user->ID;
			$x .= build_user_hcard($user_id);
		}
	}
	$sql = "SELECT ".$prefix."users.ID
		FROM ".$prefix."users
			LEFT JOIN ".$prefix."usermeta ON ".$prefix."users.ID = ".$prefix."usermeta.user_id
		WHERE ".$prefix."usermeta.meta_key = 'staff_type' AND ".$prefix."usermeta.meta_value = 'corporate_staff'";
	$users = $wpdb->get_results($sql);
	if ($users) {
		foreach ($users as $user) {
			$user_id = $user->ID;
			$x .= build_user_hcard($user_id);
		}
	}
	return $x;
}
function car_demon_get_user_cards($location, $type) {
	global $wpdb;
	$x = '';
	$prefix = $wpdb->prefix;
	if ( is_multisite() ) {
		$prefix = $wpdb->base_prefix;
		$sql = 'SELECT '.$prefix.'users.ID
			FROM '.$prefix.'usermeta AS '.$prefix.'usermeta_1 
			RIGHT JOIN ('.$prefix.'usermeta RIGHT JOIN '.$prefix.'users ON '.$prefix.'usermeta.user_id = '.$prefix.'users.ID) ON '.$prefix.'usermeta_1.user_id = '.$prefix.'users.ID
			WHERE ((('.$prefix.'usermeta.meta_key)="staff_type") AND (('.$prefix.'usermeta.meta_value)="'.$type.'") 
			AND (('.$prefix.'usermeta_1.meta_key)="user_location") AND (('.$prefix.'usermeta_1.meta_value)="'.$location.'"))';
	} else {
		$sql = 'SELECT '.$prefix.'users.ID
			FROM '.$prefix.'usermeta AS '.$prefix.'usermeta_1 
			RIGHT JOIN ('.$prefix.'usermeta RIGHT JOIN '.$prefix.'users ON '.$prefix.'usermeta.user_id = '.$prefix.'users.ID) ON '.$prefix.'usermeta_1.user_id = '.$prefix.'users.ID
			WHERE ((('.$prefix.'usermeta.meta_key)="staff_type") AND (('.$prefix.'usermeta.meta_value)="'.$type.'") 
			AND (('.$prefix.'usermeta_1.meta_key)="user_location") AND (('.$prefix.'usermeta_1.meta_value)="'.$location.'"))';
	}
	$users = $wpdb->get_results($sql);
	if ($users) {
		foreach ($users as $user) {
			$user_id = $user->ID;
			$x .= build_user_hcard($user_id, 0, 1);
		}
	}
	return $x;
}
function get_location_array() {
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
	foreach ($locations as $location) {
		$cnt = $cnt + 1;
		$location_list .= ','.$location->slug;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$cnt = 1;
	} else {
		$location_list = '@'.$location_list;
		$location_list = str_replace("@,","", $location_list);
		$location_list = str_replace("@","", $location_list);
	}
	$location_list_array = explode(',',$location_list);
	return $location_list_array;
}
function build_user_hcard($user_id, $about = 0, $contact_form = 1) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/includes', '', $car_demon_pluginpath);
	$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
	$user_location = str_replace('-', ' ', $user_location);
	$user_location = ucwords($user_location);
	if ($user_location == 'Default') {
		$user_location = get_bloginfo('name');
	}
	$staff_type = esc_attr( get_the_author_meta( 'staff_type', $user_id ) );
	$custom_photo = esc_attr( get_the_author_meta( 'profile_photo', $user_id ) );
	$custom_url = esc_attr( get_the_author_meta( 'custom_url', $user_id ) );
	if (empty($custom_url)) {
		$custom_url =  site_url().'?sales='.$user_id;
		$custom_url = str_replace('http://', '', $custom_url);
		$custom_url = str_replace('https://', '', $custom_url);
	}
	if (empty($custom_photo)) {
		$custom_photo = $car_demon_pluginpath.'images/person.gif';
	}
	$user_info = get_userdata($user_id);
	$user_full_name = $user_info->display_name;
	$user_f_name = $user_info->user_firstname;
	$user_m_name = $user_info->nickname;
	$user_l_name = $user_info->user_lastname;
	$user_email = $user_info->user_email;
	$user_description = $user_info->user_description;
	$job_title = esc_attr( get_the_author_meta( 'job_title', $user_id ));
	$user_phone = esc_attr( get_the_author_meta( 'phone_number', $user_id ) );
	$facebook = esc_attr( get_the_author_meta( 'facebook_page', $user_id ) );
	$x = '<div id="staff-card-'.$user_id.'" class="staff_card">';
	if ($about == 1) {
		$photo_class = "staff_desktop_img";
	} else {
		$photo_class = "staff_mobile_img";
	}
	$x .= '<a href="'.$custom_url.'"><img src="'.$custom_photo.'" alt="photo of '.$user_full_name.'" class="photo'.$photo_class.'" /></a>';
		$x .='<span class="fn n">';
			$x .='<span class="given-name">'.$user_f_name.'</span>';
			$x .='<span class="family-name">'.$user_l_name.'</span>';
		$x .='</span>';
		$x .='<div class="job_title">'.$job_title.'</div>';
		$x .='<div class="org">'.$user_location.'</div>';
		if ($contact_form == 0) {
			$x .='<a class="email" href="mailto:'.$user_email.'">'.$user_email.'</a>';		
		} else {
			$popup_id = 'sales_form_'.$user_id;
			$popup_button = 'Email '. $user_f_name .' now!';
			$staff_contact_form = car_demon_contact_request($user_email, $popup_id, $popup_button);
			$staff_contact_form = str_replace('search_btn contact_us_btn','search_btn contact_us_btn staff_btn',$staff_contact_form);
			$x .= $staff_contact_form;
		}
		$x .='<div class="tel">'.__('Call','car-demon').' '.$user_f_name.' '.__('at', 'car-demon').' '.$user_phone.'</div>';
		if (!empty($facebook)) { $x .='<a class="url" target="fb_win" href="'.$facebook.'">Find on Facebook</a>'; }
		if (!empty($user_description)) {
			if (strlen($user_description) < 100) {
				$x .='<div class="user_description">'.$user_description.'</div>';
			} else {
				$x .='<div class="user_description">';
					if ($about == 0) {
						$user_description = strip_tags($user_description);
						$x .= substr($user_description, 0, 100) . '<span class="staff_more" title="'.$user_description.'"> ...more</span>';
						$x .= '<span class="staff_mobile_description">'.$user_description.'</span>';
					}
					else {
						$x .= $user_description;
					}
				$x .= '</div>';
			}
		}
	$x .='</div>';
	return $x;
}
?>