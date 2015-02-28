<?php
function cd_contact_us_widget_handler() {
	$request_body = send_contact_widget_request();
	if (isset($_POST['contact_location'])) {
		$contact_location = $_POST['contact_location'];
	}
	$contact_email = $_POST['send_to'];
	if (isset($_COOKIE["sales_code"])) {
		$user_id = $_COOKIE["sales_code"];
		$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
		$location_approved = 0;
		if ($contact_location == $user_location) {
			$location_approved = 1;
		} else {
			$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
		}
		if ($location_approved == 1) {
			$user_info = get_userdata($user_id);
			$user_email = $user_info->user_email;
			$condition = $_POST['vehicle_condition'];
			$user_sales_type = 0;
			if ($condition == 'New') {
				$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);	
			} else {
				$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);		
			}
			if ($user_sales_type == "1") {
				$contact_email = $user_email;
			}		
		}
	}
	$admin_email = get_bloginfo('admin_email');
	$site_name = get_bloginfo('name');
	$no_cc = 0;
	$cc = $_POST['cc'];
	$send_receipt = $_POST['send_receipt'];
	$send_receipt_msg = $_POST['send_receipt_msg'];
	$email_body = $request_body;
	if (empty($contact_email)) {
		$contact_email = $admin_email;
		$no_cc = 1;
		$email_body = '<b>'.__('You have not setup a contact email in the admin area of your site.','car-demon').'</b><br />'.$request_body;
	}
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
		$eol="\r\n";
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
		$eol="\r";
	} else {
		$eol="\n";
	}
	$to = $contact_email;
	$subject = __('Vehicle Contact Form from ','car-demon').$site_name;
	$headers = "From: " . strip_tags($_POST['email']) . "\r\n";
	$headers .= "Reply-To: " . strip_tags($_POST['email']) . "\r\n";
	if ($no_cc == 0) {
		if (empty($cc)) {
			$headers .= "BCC: ".$admin_email."\r\n";
		}
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
		$xml_body .= adfxml_vehicle_contact($contact_location, $rep_name, $to);
		$email_body = $email_body.$xml_body.$eol;
	} else {
		$headers .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
	}
	wp_mail($to, $subject, $email_body, $headers);
	$contact_location = $_POST['vehicle_location'];
	$contact_location = strtolower($contact_location);
	$contact_location = str_replace(' ', '-', $contact_location);
	$post_id = $_POST['car_id'];
	$holder = '';
	apply_filters('car_demon_mail_hook_complete', $holder, 'contact_us_vehicle', $to, $subject, $email_body, $headers, $_POST['email'], $post_id,$contact_location);
	if (!empty($cc)) {
		mail($cc, $subject, $email_body, $headers);
	}
	if ($send_receipt == 'Yes') {
		if (!empty($send_receipt_msg)) {
			$post_id = $_POST['car_id'];
			$send_receipt_msg = replace_contact_info_tags($post_id, $send_receipt_msg);
			$email_body = $send_receipt_msg.'<br />'.$email_body;
		}
		else {
			$send_receipt_msg = __('Your message has been received.','car-demon').'<br />'.__('A Representative will contact you as soon as possible.','car-demon').'<br />'.__('A copy of your message is below.','car-demon').'<br />';
			$email_body = $send_receipt_msg.'<br />'.$email_body;
		}
		$subject = __('Email Confirmation from ','car-demon').$site_name;
		$headers = "From: " . $to . "\r\n";
		$headers .= "Reply-To: " . $to . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		mail($_POST['email'], $subject, $email_body, $headers);
	}
	$thanks = '<h1>'.__('Thank You','car-demon').'</h1>';
	$thanks .= '<h2>'.__('Your Message has been sent.','car-demon').'</h2>';
	$thanks .= '<h3>'.__('You should receive a confirmation from us shortly.','car-demon').'</h3>';
	$thanks .= '<h4>'.__('If you have questions or concerns please call and let us know.','car-demon').'</h4>';
	echo $thanks.'<br />';
	exit();
}
function send_contact_widget_request() {
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$send_to_name = $_POST['send_to_name'];
	$send_to_email = $_POST['send_to'];
	if (isset($_COOKIE["sales_code"])) {
		$user_id = $_COOKIE["sales_code"];
		$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
		$location_approved = 0;
		$contact_location = $_POST['contact_location'];
		if ($contact_location == $user_location) {
			$location_approved = 1;
		}
		else {
			$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
		}
		if ($location_approved == 1) {
			$user_info = get_userdata($user_id);
			$user_email = $user_info->user_email;
			$user_name = $user_info->display_name;
			$condition = $_POST['vehicle_condition'];
			$user_sales_type = 0;
			if ($condition == 'New') {
				$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);	
			}
			else {
				$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);		
			}
			if ($user_sales_type == "1") {
				$send_to_email = $user_email;
				$send_to_name = $user_name;
			}		
		}
	}
	$contact_needed = $_POST['contact_needed'];
	$location = $_POST['vehicle_location'];
	$vin = $_POST['vehicle_vin'];
	$stock_num = $_POST['vehicle_stock_number'];
	$condition = $_POST['vehicle_condition'];
	$year = $_POST['vehicle_year'];
	$make = $_POST['vehicle_make'];
	$model = $_POST['vehicle_model'];
	$vehicle_photo = $_POST['vehicle_photo'];
	$vehicle_photo = str_replace(chr(32), "%20", $vehicle_photo);
	$car_id = $_POST['car_id'];
	$vehicle_link = get_permalink($car_id);
	if (isset($_COOKIE["sales_code"])) {
		$vehicle_link = $vehicle_link .'?sales_code='.$_COOKIE["sales_code"];
	}
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
	$right_now .= ' '.$hour.':'.$minute.':'.$second;
	$style = " style='margin-top: 10px; padding: 5px 0 15px 0; border: 3px solid #ADADAD; border-left-color: #ECECEC; border-top-color: #ECECEC; background: #F7F7F7;'";
	$html = '
		<table align="center" width="600" border="0"'.$style.'>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('CONTACT INFORMATION','car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td width="225">'.__('Customer Name','car-demon').'</td>
			<td width="225">'.$your_name.'</td>
		  </tr>
		  <tr>
			<td>'.__('Phone Number','car-demon').'</td>
			<td>'.$phone.'</td>
		  </tr>
		  <tr>
			<td>'.__('Email Address','car-demon').'</td>
			<td>'.$email.'</td>
		  </tr>
		  <tr>
			<td>'.__('Message Sent to','car-demon').'</td>
			<td>'.$send_to_name.' ('.$send_to_email.')</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('COMMENT OR QUESTION','car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$contact_needed.'</td>
		  </tr>
		  
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('Vehicle Information','car-demon').'</td>
		  </tr>
		  <tr>
		  <tr>
			<td width="225">'.__('Location','car-demon').'</td>
			<td width="225">'.$location.'</td>
		  </tr>
		  <tr>
			<td width="225">'.__('Vin Number','car-demon').'</td>
			<td width="225">'.$vin.'</td>
		  </tr>
		  <tr>
			<td width="225">'.__('Stock #','car-demon').'</td>
			<td width="225">'.$stock_num.'</td>
		  </tr>
		  <tr>
			<td width="225">'.__('Description','car-demon').'</td>
			<td width="225">'.$condition.' '.$year.' '.$make.' '.$model.'</td>
		  </tr>
		  <tr>
			<td colspan="2" align="center"><a href="'.$vehicle_link.'"><img src="'.$vehicle_photo.'" width="400" /></a><br />'.$vehicle_link.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('SENDER INFORMATION','car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>';
			$html = apply_filters('car_demon_mail_hook_subscribe', $html, 'contact_us_vehicle', $location, $car_id);
		  $html .='
		  <tr>
			<td>'.__('Time Sent','car-demon').'</td>
			<td>'.$right_now.'</td>
		  </tr>
		  <tr>
			<td>'.__('IP','car-demon').'</td>
			<td>'.$ip.'</td>
		  </tr>
		  <tr>
			<td>'.__('User Agent','car-demon').'</td>
			<td>'.$agent.'</td>
		  </tr>
		</table>
	';
	return $html;
}
function adfxml_vehicle_contact($location, $rep_name, $to) {
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = explode( '([^0-9])', $blogtime );
	$lead_date .= $today_year .'-'. $today_month .'-'. $today_day .'T'.$hour.':'.$minute.':'.$second;
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$contact_needed = $_POST['contact_needed'];
	$vendor = get_bloginfo('name');
	//== Vehicle
	$interest = 'buy';
	$location = $_POST['vehicle_location'];
	$vin = $_POST['vehicle_vin'];
	$stock_num = $_POST['vehicle_stock_number'];
	$condition = $_POST['vehicle_condition'];
	$year = $_POST['vehicle_year'];
	$make = $_POST['vehicle_make'];
	$model = $_POST['vehicle_model'];
	$selected_car = $_POST['selected_car'];
	$car_id = $_POST['car_id'];
	$trim = get_post_meta($car_id, "_trim_value", true);
	$doors = get_post_meta($car_id, "_doors_value", true);
	$body_style = rwh(strip_tags(get_the_term_list( $car_id, 'vehicle_body_style', '','', '', '' )),0);
	$transmission = get_post_meta($car_id, "_transmission_value", true);
	$mileage = get_post_meta($car_id, "_mileage_value", true);
	$interior_color = get_post_meta($car_id, "_interior_color_value", true);
	$exterior_color = get_post_meta($car_id, "_exterior_color_value", true);
	$vehicle_price = get_post_meta($car_id, "_price_value", true);
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
					<name part="full">'.$blog_name.' '.__('Vehicle Contact Form','car-demon').'</name>
					<url>'.$blog_url.'</url>
					<email>'.$blog_email.'</email>
				</provider>
			</prospect>
		</adf>
	';
	return $x;
}
?>