<?php
function email_friend_handler() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "cd_email_friend_nonce")) {
		//Form key is invalid, show an error  
		echo 'Form key error! Submission could not be validated.';  
	} else {
		$request_body = send_to_friend_body();
		$your_name = $_POST['your_name'];
		$your_email = $_POST['your_email'];
		$friend_name = $_POST['friend_name'];
		$friend_email = $_POST['friend_email'];
		$admin_email = get_bloginfo('admin_email');
		$site_name = get_bloginfo('name');
		$email_body = $request_body;
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol="\r";
		} else {
			$eol="\n";
		}
		$to = $contact_email;
		$subject = __('Check out this car from', 'car-demon').' '.$site_name;
		$headers = "From: " . strip_tags($_POST['your_email']) . "\r\n";
		$headers .= "Reply-To: " . strip_tags($_POST['your_email']) . "\r\n";
		$headers .= "BCC: ".$admin_email."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		if (($_SESSION['car_demon_options']['adfxml_friend']) == 'Yes') {
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
			$xml_body .= adfxml_friend();
			$email_body = $email_body.$xml_body.$eol;
		}
		else {
			$headers .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
		}
		mail($friend_email, $subject, $email_body, $headers);
		$thanks .= '<div style="margin:20px;">';
			$thanks .= '<h1>'.__('Thank You', 'car-demon').'</h1>';
			$thanks .= '<h2>'.__('Your Message has been sent to your friend.', 'car-demon').'</h2>';
			$thanks .= '<h4>'.__('Your friend should receive their email shortly.', 'car-demon').'</h4>';
			$thanks .= '<h4>'.__('If you have questions or concerns please call and let us know.', 'car-demon').'</h4>';
			$thanks .= get_car_from_stock($_POST['stock_num']);
		$thanks .= '</div>';
		echo $thanks;
	}
}

function send_to_friend_body() {
	$your_name = $_POST['your_name'];
	$your_email = $_POST['your_email'];
	$friend_name = $_POST['friend_name'];
	$friend_email = $_POST['friend_email'];
	$selected_car = get_car_from_stock($_POST['stock_num']);
	$comment = $_POST['comment'];
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
			<td colspan="2" align="center">'.__('YOUR INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td width="225">'.__('Your Name', 'car-demon').'</td>
			<td width="225">'.$friend_name.'</td>
		  </tr>
		  <tr>
			<td width="225">'.__('Your Email', 'car-demon').'</td>
			<td width="225">'.$friend_email.'</td>
		  </tr>
		  <tr>
			<td width="225">'.__('Your Friend', 'car-demon').'</td>
			<td width="225">'.$your_name.' '.$your_email.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('Message from your Friend ', 'car-demon').$your_name.'</td>
		  </tr>
		  <tr>
			  <td colspan="2" align="center">'.$comment.'</td>
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
		  </tr>
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
function adfxml_friend() {
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
					<comments>Email a Friend: '.$contact_needed.'</comments>
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