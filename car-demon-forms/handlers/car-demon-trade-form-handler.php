<?php
function cd_trade_show_stock() {
	if(isset($_GET['show_stock'])) {
		$show_stock = $_GET['show_stock'];
	}
	if(isset($_POST['show_stock'])) {
		$show_stock = $_POST['show_stock'];
	}
	if ($show_stock==1) {
		if (isset($_POST['stock_num'])) {
			$stock_num = $_POST['stock_num'];
		} else {
			$stock_num = '';
		}
		echo get_trade_for_vehicle($stock_num);
	}
	if ($show_stock==2) {
		$car_title = $_POST['car_title'];
		$post_id = get_car_id_from_title($car_title);
		$stock_num = get_post_meta($post_id, "_stock_value", true);
		echo get_trade_for_vehicle($stock_num);
	}
	exit();
}
function cd_trade_find_stock() {
	echo car_demon_find_stock_info();
	exit();
}
function cd_trade_find_vehicle() {
	echo car_demon_find_car_info();
	exit();
}
function cd_trade_handler() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "cd_contact_us_nonce")) {
		echo 'Form key error! Submission could not be validated.';  
		exit("No naughty business please");
		//Form key is invalid, show an error  
	} else {
		$request_body = build_trade_body();
		$trade_location = $_POST['trade_location'];
		$selected_car = $_POST['selected_car'];
		if (empty($selected_car)) {
			$trade_email = get_trade_email($trade_location);
		}
		else {
			$trade_location = get_trade_location($selected_car);
			$selected_car = get_car_from_stock($selected_car);
			$trade_email = get_trade_email($trade_location);
		}
		$admin_email = get_bloginfo('admin_email');
		$site_name = get_bloginfo('name');
		$no_cc = 0;
		$email_body = $request_body;
		if (empty($trade_email)) {
			$trade_email = $admin_email;
			$no_cc = 1;
			$email_body = '<b>'.__('You have not setup a trade email in the admin area of your site.', 'car-demon').'</b><br />'.$request_body;
		}
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol="\r";
		} else {
			$eol="\n";
		}
		$to = $trade_email;
		if (isset($_COOKIE["sales_code"])) {
			$user_id = $_COOKIE["sales_code"];
			$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
			$location_approved = 0;
			if ($trade_location == $user_location) {
				$location_approved = 1;
			}
			else {
				$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
			}
			if ($location_approved == 1) {
				$user_info = get_userdata($user_id);
				$user_email = $user_info->user_email;
				$user_sales_type = 0;
				$user_sales_type = get_the_author_meta('lead_trade', $user_id);	
				if ($user_sales_type == "1") {
					$to = $user_email;
				}		
			}
		}
		$subject = __('Trade Request from ', 'car-demon').$site_name;
		$headers = "From: " . strip_tags($_POST['email']) . "\r\n";
		$headers .= "Reply-To: " . strip_tags($_POST['email']) . "\r\n";
		if (isset($_SESSION['car_demon_options']['cc_admin'])) {
			if ($_SESSION['car_demon_options']['cc_admin'] == 'No') {
				$no_cc == 1;
			}
		}
		if ($no_cc == 0) {
			$headers .= "BCC: ".$admin_email."\r\n";
		}
		$headers .= "MIME-Version: 1.0\r\n";
		if (($_SESSION['car_demon_options']['adfxml']) == 'Yes') {
			$semi_rand = md5(time());
			$mime_boundary = "==MULTIPART_BOUNDARY_".$semi_rand;
			$headers .= 'Content-Type: multipart/mixed; boundary="'.$mime_boundary.'"'.$eol;
			$text_body = '--'.$mime_boundary.$eol;
			$text_body .= 'Content-Type: text/html; charset=ISO-8859-1'.$eol;
			$text_body .= 'Content-Transfer-Encoding: 7bit'.$eol.$eol;
			$email_body = $text_body.$email_body.$eol;
			$xml_body = '--'.$mime_boundary.$eol;
			$xml_body .= "Content-Type: application/xml;".$eol;
			$xml_body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
			$xml_body .= adfxml_trade($trade_location, $rep_name, $to);
			$email_body = $email_body.$xml_body.$eol;
		}
		else {
			$headers .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
		}
		wp_mail($to, $subject, $email_body, $headers);
		$selected_car = $_POST['selected_car'];
		$car_id = get_car_id_from_stock($selected_car);
		$holder = '';		
		apply_filters('car_demon_mail_hook_complete', $holder, 'trade', $to, $subject, $email_body, $headers, $_POST['email'], $car_id,$trade_location);
		$thanks = '<h1>'.__('Thank You').'</h1>';
		$thanks .= '<h2>'.__('Your Trade Request has been sent.', 'car-demon').'</h2>';
		$thanks .= '<h3>'.__('You should receive a confirmation shortly.', 'car-demon').'</h3>';
		$thanks .= '<h4>'.__('If you have questions or concerns please call and let us know.', 'car-demon').'</h4>';
		echo $thanks;
	}
	exit();
}
function build_trade_body() {
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$trade_location = $_POST['trade_location'];
	$year = $_POST['year'];
	$make = $_POST['make'];
	$model = $_POST['model'];
	$miles = $_POST['miles'];
	$vin = $_POST['vin'];
	$options = $_POST['options'];
	$options = str_replace(',','<br />',$options);
	$comment = $_POST['comment'];
	$selected_car = $_POST['selected_car'];
	if (empty($selected_car)) {
		$selected_car = 'Not Decided';
		$trade_email = get_trade_email($trade_location);
		$trade_location .= ' ('.$trade_email.')';
	} else {
		$trade_location = get_trade_location($selected_car);
		$selected_car = get_car_from_stock($selected_car);
		$trade_email = get_trade_email($trade_location);
		$trade_location .= ' ('.$trade_email.')';
	}
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
	$right_now .= ' '.$hour.':'.$minute.':'.$second;
	$style = " style='margin-top: 10px; padding: 5px 0 15px 0; border: 3px solid #ADADAD; border-left-color: #ECECEC; border-top-color: #ECECEC; background: #F7F7F7;'";
	$html = '
		<table align="center" width="450" border="0"'.$style.'>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('CUSTOMER INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td width="225">'.__('Customer Name', 'car-demon').'</td>
			<td width="225">'.$your_name.'</td>
		  </tr>
		  <tr>
			<td>'.__('Phone Number', 'car-demon').'</td>
			<td>'.$phone.'</td>
		  </tr>
		  <tr>
			<td>'.__('Email Address', 'car-demon').'</td>
			<td>'.$email.'</td>
		  </tr>
		  <tr>
			<td>'.__('Trade Location', 'car-demon').'</td>
			<td>'.$trade_location.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('VEHICLE INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Vehicle Year', 'car-demon').'</td>
			<td>'.$year.'</td>
		  </tr>
		  <tr>
			<td>'.__('Vehicle Make', 'car-demon').'</td>
			<td>'.$make.'</td>
		  </tr>
		  <tr>
			<td>'.__('Vehicle Model', 'car-demon').'</td>
			<td>'.$model.'</td>
		  </tr>
		  <tr>
			<td>'.__('Vehicle Miles', 'car-demon').'</td>
			<td>'.$miles.'</td>
		  </tr>
		  <tr>
			<td>'.__('Vehicle VIN', 'car-demon').'</td>
			<td>'.$vin.'</td>
		  </tr>
		  <tr>
			<td valign="top">'.__('Options', 'car-demon').'</td>
			<td>'.$options.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('Comment', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comment.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('Vehicle of Interest', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$selected_car.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('SENDER INFORMATION', 'car-demon').'</td>
		  </tr>';
			$trade_location = $_POST['trade_location'];
			$selected_car = $_POST['selected_car'];
			if (!empty($selected_car)) {
				$trade_location = get_trade_location($selected_car);
			}
			$html = apply_filters('car_demon_mail_hook_subscribe', $html, 'trade_form', $trade_location, $selected_car);
		  $html .= '
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Time Sent', 'car-demon').'</td>
			<td>'.$right_now.'</td>
		  </tr>
		  <tr>
			<td>'.__('IP', 'car-demon').'</td>
			<td>'.$ip.'</td>
		  </tr>
		  <tr>
			<td>'.__('User Agent', 'car-demon').'</td>
			<td>'.$agent.'</td>
		  </tr>
		</table>
	';
	return $html;
}
function get_car_id_from_title($car_title) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select ID from ".$prefix."posts WHERE post_title='".$car_title."' and post_type='cars_for_sale'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$x = $post->ID;
		}
	}
	return $x;
}
function car_demon_find_car_info() {
	$q = '';
	if (isset($_GET['q'])) {
		$q = strtolower($_GET["q"]);
	}
	if (isset($_GET["term"])) {
		$q = strtolower($_GET["term"]);
	}
	if (!$q) return;
	$items = array();
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = 'SELECT '.$prefix.'postmeta.post_id AS ID, '.$prefix.'posts.post_title, 
		'.$prefix.'postmeta_1.meta_value as stock, '.$prefix.'postmeta.meta_key, 
		'.$prefix.'postmeta.meta_value, '.$prefix.'postmeta_1.meta_key
		FROM ('.$prefix.'posts LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'posts.ID = '.$prefix.'postmeta.post_id) 
		LEFT JOIN '.$prefix.'postmeta AS '.$prefix.'postmeta_1 ON '.$prefix.'posts.ID = '.$prefix.'postmeta_1.post_id
		WHERE ((('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no") AND (('.$prefix.'postmeta_1.meta_key)="_stock_value"))
		and '.$prefix.'posts.post_title like "%'.$q.'%" and '.$prefix.'posts.post_status="publish";
	';
	$a_json = array();
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->ID;
			array_push($a_json, $post->post_title);
		}
	}
	$json = json_encode($a_json);
	header("Content-Type: application/json");
	return $json;
}
function car_demon_find_stock_info() {
	$q = strtolower($_GET["q"]);
	if (isset($_GET["term"])) {
		$q = strtolower($_GET["term"]);
	}
	if (!$q) return;
	$items = array();
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value like '%".$q."%'";
	$posts = $wpdb->get_results($sql);
	$x = '';
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
			$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
			$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
			$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
			$car = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model;
			$x .= $post->meta_value .'|'. $car .chr(13).chr(10).chr(11);
			// "$key|$value\n";
		}
	}
	return $x;
}
function get_trade_location($selected_car) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value = '".$selected_car."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$location_name = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
			$terms = get_the_terms($post_id, 'vehicle_location');
			if ($terms) {
				foreach ($terms as $term) {
					if ($term->name == $location_name) {
						$current_location = $term->slug;
						$x = get_option($current_location.'_trade_name');
					}		
				}
			}
		}
	}
	return $x;
}
function get_trade_email($trade_location) {
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
	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$location_name_list = 'Default'.$location_name_list;
	} else {
		$location_list = '#'.$location_list;
		$location_list = str_replace("#,","", $location_list);
		$location_list = str_replace("#","", $location_list);
		$location_name_list = '#'.$location_name_list;
		$location_name_list = str_replace("#,","", $location_name_list);
		$location_name_list = str_replace("#","", $location_name_list);
	}
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	foreach ($location_list_array as $current_location) {
		$location_name = get_option($current_location.'_trade_name');
		if ($trade_location == $location_name) {
			$html = get_option($current_location.'_trade_email');
		}
	}
	return $html;
}
function adfxml_trade($location, $rep_name, $rep_email) {
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
	$lead_date .= $today_year .'-'. $today_month .'-'. $today_day .'T'.$hour.':'.$minute.':'.$second;
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$contact_needed = $_POST['contact_needed'];
	$vendor = get_bloginfo('name');
	//== Vehicle
	$interest = 'buy';
	$selected_car = $_POST['selected_car'];
	if (!empty($selected_car)) {
		$car_id = get_car_id_from_stock($selected_car);
		$condition = rwh(strip_tags(get_the_term_list( $car_id, 'vehicle_condition', '','', '', '' )),0);
		$year = rwh(strip_tags(get_the_term_list( $car_id, 'vehicle_year', '','', '', '' )),0);
		$make = rwh(strip_tags(get_the_term_list( $car_id, 'vehicle_make', '','', '', '' )),0);
		$model = rwh(strip_tags(get_the_term_list( $car_id, 'vehicle_model', '','', '', '' )),0);
		$vin = rwh(get_post_meta($car_id, "_vin_value", true),0);
		$stock_num = $selected_car;
		$trim = get_post_meta($car_id, "_trim_value", true);
		$doors = get_post_meta($car_id, "_doors_value", true);
		$body_style = rwh(strip_tags(get_the_term_list( $car_id, 'vehicle_body_style', '','', '', '' )),0);
		$transmission = get_post_meta($car_id, "_transmission_value", true);
		$mileage = get_post_meta($car_id, "_mileage_value", true);
		$interior_color = get_post_meta($car_id, "_interior_color_value", true);
		$exterior_color = get_post_meta($car_id, "_exterior_color_value", true);
		$vehicle_price = get_post_meta($car_id, "_price_value", true);
	} else {
		$car_id = '';
		$condition = '';
		$year = '';
		$make = '';
		$model = '';
		$vin = '';
		$stock_num = '';
		$trim = '';
		$doors = '';
		$body_style = '';
		$transmission = '';
		$mileage = '';
		$interior_color = '';
		$exterior_color = '';
		$vehicle_price = '';
	}
	//== Contact
	$full_name = $_POST['your_name'];
	$contact_email = $_POST['email'];
	$phone = $_POST['phone'];
	//== Provider
	$blog_name = get_bloginfo('name');
	$blog_url = site_url();
	$blog_email = get_bloginfo('admin_email');
	$x = '<'.'?xml version="1.0" ?'.'>
		<'.'?adf version="1.0" ?'.'>';
	$x .= '
		<adf>
			<prospect>
				<requestdate>'.$lead_date.'</requestdate>
				<vehicle interest="'.$interest.'" status="'.$condition.'">
					<year>'.$year.'</year>
					<make>'.$make.'</make>
					<model>'.$model.'</model>
					<vin>'.$vin.'</vin>
					<stock>'.$stock_num.'</stock>
					<trim>'.$trim.'</trim>
					<doors>'.$doors.'</doors>
					<bodystyle>'.$body_style.'</bodystyle>
					<transmission>'.$transmission.'</transmission>
					<odometer status="replaced" units="miles">'.$mileage.'</odometer>
					<colorcombination>
						<interiorcolor>'.$interior_color.'</interiorcolor>
						<exteriorcolor>'.$exterior_color.'</exteriorcolor>
						<preference>1</preference>
					</colorcombination>
					<price type="quote" currency="USD">'.$vehicle_price.'</price>
				</vehicle>
				<vehicle interest="trade-in">
					<year>'.$_POST['year'].'</year>
					<make>'.$_POST['make'].'</make>
					<model>'.$_POST['model'].'</model>
					<vin>'.$_POST['vin'].'</vin>
					<odometer status="replaced" units="miles">'.$_POST['miles'].'</odometer>
				';
				$options = $_POST['options'];
				$options_array = explode(',',$options);
				foreach ($options_array as $option) {
					$x .= '<option><optionname>'.$option.'</optionname></option>';
				}
				$x .= '
				</vehicle>
				<customer>
					<contact>
						<name part="full">'.$full_name.'</name>
						<email>'.$contact_email.'</email>
						<phone type="voice">'.$phone.'</phone>
					</contact>
					<comments>'.$contact_needed.'</comments>
				</customer>
				<vendor>
					<vendorname>'.$location.'</vendorname>
					<contact primarycontact="1">
						<name part="full">'.$rep_name.'</name>
						<email>'.$rep_email.'</email>
					</contact>
				</vendor>
				<provider>
					<name part="full">'.$blog_name.' '.__('Trade Form', 'car-demon').'</name>
					<url>'.$blog_url.'</url>
					<email>'.$blog_email.'</email>
				</provider>
			</prospect>
		</adf>
	';
	return $x;
}
?>