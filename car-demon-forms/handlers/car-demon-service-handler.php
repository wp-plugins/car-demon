<?php
function cd_service_handler() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "cd_service_request_nonce")) {
		echo 'Form key error! Submission could not be validated.';  
		exit("No naughty business please");
		//Form key is invalid, show an error  
	} else {
		$request_body = send_service_request();
		$service_location = $_POST['service_location'];
		$service_email = get_service_email($service_location);
		$admin_email = get_bloginfo('admin_email');
		$site_name = get_bloginfo('name');
		$no_cc = 0;
		$email_body = $request_body;
		if (empty($service_email)) {
			$service_email = $admin_email;
			$no_cc = 1;
			$email_body = '<b>'.__('You have not setup a service email in the admin area of your site.', 'car-demon').'</b><br />'.$request_body;
		}
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol="\r";
		} else {
			$eol="\n";
		}
		$to = $service_email;
		if (isset($_COOKIE["sales_code"])) {
			$user_id = $_COOKIE["sales_code"];
			$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
			$location_approved = 0;
			if ($service_location == $user_location) {
				$location_approved = 1;
			}
			else {
				$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
			}
			if ($location_approved == 1) {
				$user_info = get_userdata($user_id);
				$user_email = $user_info->user_email;
				$user_sales_type = 0;
				$user_sales_type = get_the_author_meta('lead_service', $user_id);	
				if ($user_sales_type == "1") {
					$to = $user_email;
				}		
			}
		}
		$subject = __('Service Request from ', 'car-demon').$site_name;
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
		if (isset($_SESSION['car_demon_options']['adfxml_service']) == 'Yes') {
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
			$xml_body .= adfxml_service();
			$email_body = $email_body.$xml_body.$eol;
		}
		else {
			$headers .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
		}
		wp_mail($to, $subject, $email_body, $headers);
		$post_id = '';
		$holder = '';
		apply_filters('car_demon_mail_hook_complete', $holder, 'service_appointment', $to, $subject, $email_body, $headers, $_POST['email'], $post_id, $service_location);
		$thanks = '<h1>'.__('Thank You', 'car-demon').'</h1>';
		$thanks .= '<h2>'.__('Your Service Request has been sent.', 'car-demon').'</h2>';
		$thanks .= '<h3>'.__('You should receive a confirmation from our Service Department Shortly.', 'car-demon').'</h3>';
		$thanks .= '<h4>'.__('The information you sent is below.', 'car-demon').'</h4>';
		$thanks .= '<h4>'.__('If you have questions or concerns please call and let us know.', 'car-demon').'</h4>';
		echo $thanks.'<br />'.$request_body;
	}
	exit();
}
function send_service_request() {
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$service_location = $_POST['service_location'];
	$pref_date = $_POST['pref_date'];
	$alt_date = $_POST['alt_date'];
	$waiting = $_POST['waiting'];
	$transportation = $_POST['transportation'];
	$year = $_POST['year'];
	$make = $_POST['make'];
	$model = $_POST['model'];
	$miles = $_POST['miles'];
	$vin = $_POST['vin'];
	$service_needed = $_POST['service_needed'];
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
			<td>'.__('Service Location', 'car-demon').'</td>
			<td>'.$service_location.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('APPOINTMENT INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Preferred Service Date', 'car-demon').'</td>
			<td>'.$pref_date.'</td>
		  </tr>
		  <tr>
			<td>'.__('Alternate Service Date', 'car-demon').'</td>
			<td>'.$alt_date.'</td>
		  </tr>
		  <tr>
			<td>'.__('Customer will be', 'car-demon').'</td>
			<td>'.$waiting.'</td>
		  </tr>
		  <tr>
			<td>'.__('Needs Transportation', 'car-demon').'</td>
			<td>'.$transportation.'</td>
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
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('SERVICE REQUESTED', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$service_needed.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('SENDER INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>';
		$location = $_POST['service_location'];
		$html = apply_filters('car_demon_mail_hook_subscribe', $html, 'service_appointment', $location, '0');
		  $html .= '<tr>
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
function get_service_email($service_location) {
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
		$location_name = get_option($current_location.'_service_name');
		if ($service_location == $location_name) {
			$html = get_option($current_location.'_service_email');
		}
	}
	return $html;
}
function adfxml_service() {
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
	$lead_date .= $today_year .'-'. $today_month .'-'. $today_day .'T'.$hour.':'.$minute.':'.$second;
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$contact_needed = $_POST['contact_needed'];
	$vendor = get_bloginfo('name');
	$x = '<'.'?xml version="1.0" ?'.'>
		<'.'?adf version="1.0" ?'.'>';
	$x .= '
		<adf>
			<prospect>
				<requestdate>'.$lead_date.'</requestdate>
				<vehicle>
					<year></year>
					<make></make>
					<model></model>
				</vehicle>
				<customer>
					<contact>
						<name part="full">'.$your_name.'</name>
						<phone>'.$phone.'</phone>
					</contact>
					<comments>'.$contact_needed.'</comments>
				</customer>
				<vendor>
					<contact>
						<name part="full">'.$vendor.'</name>
					</contact>
				</vendor>
			</prospect>
		</adf>
	';
	return $x;
}
?>