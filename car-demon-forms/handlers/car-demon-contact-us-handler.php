<?php
function cd_contact_us_handler() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "cd_contact_us_nonce")) {
		echo 'Form key error! Submission could not be validated.';  
		exit("No naughty business please");
		//Form key is invalid, show an error  
	} else {
		$request_body = send_contact_request();
		$contact_email = $_POST['send_to'];
		$admin_email = get_bloginfo('admin_email');
		$site_name = get_bloginfo('name');
		$no_cc = 0;
		$email_body = $request_body;
		if (empty($contact_email)) {
			$contact_email = $admin_email;
			$no_cc = 1;
			$email_body = '<b>'.__('You have not setup a contact email in the admin area of your site.', 'car-demon').'</b><br />'.$request_body;
		}
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol="\r";
		} else {
			$eol="\n";
		}
		$to = $contact_email;
		$subject = __('Contact Form from ', 'car-demon').$site_name;
		$headers = "From: " . strip_tags($_POST['email']) . $eol;
		$headers .= "Reply-To: " . strip_tags($_POST['email']) . $eol;
		if (isset($_SESSION['car_demon_options']['cc_admin'])) {
			if ($_SESSION['car_demon_options']['cc_admin'] == 'No') {
				$no_cc == 1;
			}
		}
		if ($no_cc == 0) {
			$headers .= "BCC: ".$admin_email.$eol;
		}
		$headers .= "MIME-Version: 1.0".$eol;
		if (isset($_SESSION['car_demon_options']['adfxml']) == 'Yes') {
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
			$xml_body .= adfxml_contact_us();
			$email_body = $email_body.$xml_body.$eol;
		} else {
			$headers .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
		}
		wp_mail($to, $subject, $email_body, $headers);
		$post_id = '';
		$holder = '';
		apply_filters('car_demon_mail_hook_complete', $holder, 'contact_us', $to, $subject, $email_body, $headers, $_POST['email'], $post_id, $_POST['send_to_name']);
		$thanks = '<h1>'.__('Thank You', 'car-demon').'</h1>';
		$thanks .= '<h2>'.__('Your Message has been sent.', 'car-demon').'</h2>';
		$thanks .= '<h3>'.__('You should receive a confirmation from us shortly.', 'car-demon').'</h3>';
		$thanks .= '<h4>'.__('The information you sent is below.', 'car-demon').'</h4>';
		$thanks .= '<h4>'.__('If you have questions or concerns please call and let us know.', 'car-demon').'</h4>';
		echo $thanks.'<br />'.$request_body;
	}
	exit();
}
function send_contact_request() {
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$send_to_name = $_POST['send_to_name'];
	$contact_needed = $_POST['contact_needed'];
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
			<td colspan="2" align="center">'.__('CONTACT INFORMATION', 'car-demon').'</td>
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
			<td>'.__('Message Sent to', 'car-demon').'</td>
			<td>'.$send_to_name.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('COMMENT OR QUESTION', 'car-demon').'</td>
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
			<td colspan="2" align="center">'.__('SENDER INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>';
		$html = apply_filters('car_demon_mail_hook_subscribe', $html, 'contact_us', 'unk', '0');
		  $html .= '
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
function adfxml_contact_us() {
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
	$lead_date = $today_year .'-'. $today_month .'-'. $today_day .'T'.$hour.':'.$minute.':'.$second;
	$your_name = $_POST['your_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$contact_needed = $_POST['contact_needed'];
	$vendor = get_bloginfo('name');
	$blog_name = get_bloginfo('name');
	$blog_url = site_url();
	$blog_email = get_bloginfo('admin_email');
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
				<provider>
					<name part="full">'.$blog_name.' '.__('Contact Form', 'car-demon').'</name>
					<url>'.$blog_url.'</url>
					<email>'.$blog_email.'</email>
				</provider>
			</prospect>
		</adf>
	';
	return $x;
}
?>