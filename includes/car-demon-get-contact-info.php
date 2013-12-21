<?php
function get_car_contact($post_id) {
	$car_contact = array();
	$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
	if ($vehicle_condition == 'New') {
		$sales_type = 'new';
	} else {
		$sales_type = 'used';
	}
	$current_location_terms = get_the_terms( $post_id, 'vehicle_location');
	if ($current_location_terms) {
		foreach ($current_location_terms as $current_locations) {
			$current_location = $current_locations->slug;
		}
	} else {
		$current_location = 'default';
	}
	$car_contact['sales_code'] = 0;
	if (isset($_COOKIE["sales_code"])) {
		$sales_code = $_COOKIE["sales_code"];
	} else {
		$sales_code = '';
	}
	if ($sales_code) {
		$user_id = $_COOKIE["sales_code"];
		$car_contact['sales_code'] = $user_id;
		$user_info = get_userdata($user_id);
		$user_phone = esc_attr( get_the_author_meta( 'phone_number', $user_id ) );
		//---Check if user has this sales type
		if ($sales_type == 'new') {
			$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);
		}
		if ($sales_type == 'used') {
			$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);
		}
		if ($user_sales_type == 1) {
			$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
		} else {
			$user_location = '';
		}
		if ($user_location == $current_location) {
			$car_contact['sales_name'] = $user_info->display_name;
			$car_contact['sales_email'] = $user_info->user_email;
			$car_contact['custom_photo'] = esc_attr( get_the_author_meta( 'profile_photo', $user_id ) );
			if (empty($custom_photo)) {
				$car_demon_pluginpath = CAR_DEMON_PATH;
				$car_demon_pluginpath = str_replace('/includes', '', $car_demon_pluginpath);
				$custom_photo = $car_demon_pluginpath.'images/person.gif';
			}
			if (!empty($user_phone)) {
				$car_contact['sales_phone'] = $user_phone;
			}
			else {
				$car_contact['sales_phone'] = get_option($current_location.'_'.$sales_type.'_sales_number');
			}
		}
		else {
			$car_contact['sales_name'] = get_option($current_location.'_'.$sales_type.'_sales_name');
			$car_contact['sales_email'] = get_option($current_location.'_'.$sales_type.'_sales_email');
			$car_contact['sales_phone'] = get_option($current_location.'_'.$sales_type.'_sales_number');
		}
		$car_contact['sales_mobile'] = get_option($current_location.'_'.$sales_type.'_sales_mobile_number');
		$car_contact['sales_mobile_provider'] = get_option($current_location.'_'.$sales_type.'_mobile_provider');
		//---Can User get trades
		$user_trade = get_the_author_meta('lead_trade', $user_id);
		if ($user_trade == 1) {
			$car_contact['trade_name'] = $user_info->user_nicename;
			$car_contact['trade_email'] = $user_info->user_email;
			if (!empty($user_phone)) {
				$car_contact['trade_phone'] = $user_phone;
			}
			else {
				$car_contact['trade_phone'] = get_option($current_location.'_trade_number');
			}
		}
		else {
			$car_contact['trade_name'] = get_option($current_location.'_trade_name');
			$car_contact['trade_email'] = get_option($current_location.'_trade_email');
			$car_contact['trade_phone'] = get_option($current_location.'_trade_number');
		}
		$car_contact['trade_url'] = get_option($current_location.'_trade_url');
		//---Can user get finance
		$user_finance = get_the_author_meta('lead_finance', $user_id);
		if ($user_finance == 1) {
			$car_contact['finance_name'] = $user_info->user_nicename;
			$car_contact['finance_email'] = $user_info->user_email;
			if (!empty($user_phone)) {
				$car_contact['finance_phone'] = $user_phone;
			}
			else {
				$car_contact['finance_phone'] = get_option($current_location.'_finance_phone');
			}
		}
		else {
			$car_contact['finance_name'] = get_option($current_location.'_finance_name');
			$car_contact['finance_email'] = get_option($current_location.'_finance_email');
			$car_contact['finance_phone'] = get_option($current_location.'_finance_phone');
		}
		$car_contact['finance_url'] = get_option($current_location.'_finance_url');
		$car_contact['finance_popup'] = get_option($current_location.'_finance_popup');
		$car_contact['finance_width'] = get_option($current_location.'_finance_width');
		$car_contact['finance_height'] = get_option($current_location.'_finance_height');
	} else {
		$car_contact['sales_name'] = get_option($current_location.'_'.$sales_type.'_sales_name');
		$car_contact['sales_email'] = get_option($current_location.'_'.$sales_type.'_sales_email');
		$car_contact['sales_phone'] = get_option($current_location.'_'.$sales_type.'_sales_number');
		$car_contact['sales_mobile'] = get_option($current_location.'_'.$sales_type.'_sales_mobile_number');
		$car_contact['sales_mobile_provider'] = get_option($current_location.'_'.$sales_type.'_mobile_provider');
		$car_contact['trade_name'] = get_option($current_location.'_trade_name');
		$car_contact['trade_email'] = get_option($current_location.'_trade_email');
		$car_contact['trade_phone'] = get_option($current_location.'_trade_number');
		$car_contact['trade_url'] = get_option($current_location.'_trade_url');
		$car_contact['finance_name'] = get_option($current_location.'_finance_name');
		$car_contact['finance_email'] = get_option($current_location.'_finance_email');
		$car_contact['finance_phone'] = get_option($current_location.'_finance_phone');
		$car_contact['finance_url'] = get_option($current_location.'_finance_url');
		$car_contact['finance_popup'] = get_option($current_location.'_finance_popup');
		$car_contact['finance_width'] = get_option($current_location.'_finance_width');
		$car_contact['finance_height'] = get_option($current_location.'_finance_height');
	}
	return $car_contact;
}
function replace_contact_info_tags($post_id, $body) {
	if (empty($post_id)) {
		$post_id = 0;
	}
	$car_contact = array();
	$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
	if ($vehicle_condition == 'New') {
		$sales_type = 'new';
	} else {
		$sales_type = 'used';
	}
	$current_location_terms = get_the_terms( $post_id, 'vehicle_location');
	if ($current_location_terms) {
		foreach ($current_location_terms as $current_locations) {
			$current_location = $current_locations->slug;
		}
	}
	if (empty($current_location)) {
		$current_location = 'default';
	}
	$car_contact['sales_code'] = 0;
	if (isset($_COOKIE["sales_code"])) {
		$user_id = $_COOKIE["sales_code"];
		$car_contact['sales_code'] = $user_id;
		$user_info = get_userdata($user_id);
		$user_phone = esc_attr( get_the_author_meta( 'phone_number', $user_id ) );
		$car_contact['facebook_page'] = esc_attr( get_the_author_meta( 'facebook_page', $user_id ) );
		//---Check if user has this sales type
		if ($sales_type == 'new') {
			$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);
		}
		if ($sales_type == 'used') {
			$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);
		}
		if ($user_sales_type == 1) {
			$car_contact['sales_name'] = $user_info->display_name;
			$car_contact['sales_email'] = $user_info->user_email;
			if (!empty($user_phone)) {
				$car_contact['sales_phone'] = $user_phone;
			}
			else {
				$car_contact['sales_phone'] = get_option($current_location.'_'.$sales_type.'_sales_number');
			}
		}
		else {
			$car_contact['sales_name'] = get_option($current_location.'_'.$sales_type.'_sales_name');
			$car_contact['sales_email'] = get_option($current_location.'_'.$sales_type.'_sales_email');
			$car_contact['sales_phone'] = get_option($current_location.'_'.$sales_type.'_sales_number');
		}
		$car_contact['sales_mobile'] = get_option($current_location.'_'.$sales_type.'_sales_mobile_number');
		$car_contact['sales_mobile_provider'] = get_option($current_location.'_'.$sales_type.'_mobile_provider');
		//---Can User get trades
		$user_trade = get_the_author_meta('lead_trade', $user_id);
		if ($user_trade == 1) {
			$car_contact['trade_name'] = $user_info->user_nicename;
			$car_contact['trade_email'] = $user_info->user_email;
			if (!empty($user_phone)) {
				$car_contact['trade_phone'] = $user_phone;
			}
			else {
				$car_contact['trade_phone'] = get_option($current_location.'_trade_number');
			}
		}
		else {
			$car_contact['trade_name'] = get_option($current_location.'_trade_name');
			$car_contact['trade_email'] = get_option($current_location.'_trade_email');
			$car_contact['trade_phone'] = get_option($current_location.'_trade_number');
		}
		$car_contact['trade_url'] = get_option($current_location.'_trade_url');
		//---Can user get finance
		$user_finance = get_the_author_meta('lead_finance', $user_id);
		if ($user_finance == 1) {
			$car_contact['finance_name'] = $user_info->user_nicename;
			$car_contact['finance_email'] = $user_info->user_email;
			if (!empty($user_phone)) {
				$car_contact['finance_phone'] = $user_phone;
			}
			else {
				$car_contact['finance_phone'] = get_option($current_location.'_finance_phone');
			}
		}
		else {
			$car_contact['finance_name'] = get_option($current_location.'_finance_name');
			$car_contact['finance_email'] = get_option($current_location.'_finance_email');
			$car_contact['finance_phone'] = get_option($current_location.'_finance_phone');
		}
	} else {
		$car_contact['sales_name'] = get_option($current_location.'_'.$sales_type.'_sales_name');
		$car_contact['sales_email'] = get_option($current_location.'_'.$sales_type.'_sales_email');
		$car_contact['sales_phone'] = get_option($current_location.'_'.$sales_type.'_sales_number');
		$car_contact['sales_mobile'] = get_option($current_location.'_'.$sales_type.'_sales_mobile_number');
		$car_contact['sales_mobile_provider'] = get_option($current_location.'_'.$sales_type.'_mobile_provider');
		$car_contact['trade_name'] = get_option($current_location.'_trade_name');
		$car_contact['trade_email'] = get_option($current_location.'_trade_email');
		$car_contact['trade_phone'] = get_option($current_location.'_trade_number');
		$car_contact['finance_name'] = get_option($current_location.'_finance_name');
		$car_contact['finance_email'] = get_option($current_location.'_finance_email');
		$car_contact['finance_phone'] = get_option($current_location.'_finance_phone');
		$car_contact['facebook_page'] = get_option($current_location.'_facebook_page');
	}
	$body = str_replace('[sales_code]', $car_contact['sales_code'],$body);
	$body = str_replace('[sales_name]', $car_contact['sales_name'],$body);
	$body = str_replace('[sales_email]', $car_contact['sales_email'],$body);
	$body = str_replace('[sales_phone]', $car_contact['sales_phone'],$body);
	$body = str_replace('[sales_mobile]', $car_contact['sales_mobile'],$body);
	$body = str_replace('[sales_mobile_provider]', $car_contact['sales_mobile_provider'],$body);
	$body = str_replace('[trade_name]', $car_contact['trade_name'],$body);
	$body = str_replace('[trade_email]', $car_contact['trade_email'],$body);
	$body = str_replace('[trade_phone]', $car_contact['trade_phone'],$body);
	$body = str_replace('[finance_name]', $car_contact['finance_name'],$body);
	$body = str_replace('[finance_email]', $car_contact['finance_email'],$body);
	$body = str_replace('[finance_phone]', $car_contact['finance_phone'],$body);
	$body = str_replace('[facebook_page]', $car_contact['facebook_page'],$body);
	$body = str_replace('[sales_code]', '',$body);
	$body = str_replace('[sales_name]', '',$body);
	$body = str_replace('[sales_email]', '',$body);
	$body = str_replace('[sales_phone]', '',$body);
	$body = str_replace('[sales_mobile]', '',$body);
	$body = str_replace('[sales_mobile_provider]', '',$body);
	$body = str_replace('[trade_name]', '',$body);
	$body = str_replace('[trade_email]', '',$body);
	$body = str_replace('[trade_phone]', '',$body);
	$body = str_replace('[finance_name]', '',$body);
	$body = str_replace('[finance_email]', '',$body);
	$body = str_replace('[finance_phone]', '',$body);
	$body = str_replace('[facebook_page]', '',$body);
	return $body;
}
?>