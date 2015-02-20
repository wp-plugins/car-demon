<?php
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/theme-files/forms', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\theme-files\forms', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
require($newPath.'wp-content/plugins/car-demon/car-demon-forms/forms/car-demon-form-key-class.php');
$cd_formKey = new cd_formKey();
if(!isset($_POST['form_key']) || !$cd_formKey->validate()) {  
	//Form key is invalid, show an error  
	echo 'Form key error! Submission could not be validated.';  
}  
else {
	//Do the rest of your validation here  
	send_finance_email();
}
function send_finance_email() {
	$request_body = build_finance_body();
	$finance_location = $_POST['finance_location'];
	$selected_car = $_POST['purchase_stock'];
	if (empty($selected_car)) {
		$finance_email = get_finance_email($finance_location);
	} else {
		$finance_location = get_finance_location($selected_car);
		$selected_car = get_car_from_stock($selected_car);
		$finance_email = get_finance_email($finance_location);
	}
	$admin_email = get_bloginfo('admin_email');
	$site_name = get_bloginfo('name');
	$no_cc = 0;
	$email_body = $request_body;
	if (empty($finance_email)) {
		$finance_email = $admin_email;
		$no_cc = 1;
		$email_body = '<b>'.__('You have not setup a finance email in the admin area of your site.', 'car-demon').'</b><br />'.$request_body;
	}
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
		$eol="\r\n";
	} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
		$eol="\r";
	} else {
		$eol="\n";
	}
	$to = $finance_email;
	if ($_COOKIE["sales_code"]) {
		$user_id = $_COOKIE["sales_code"];
		$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
		$location_approved = 0;
		if ($finance_location == $user_location) {
			$location_approved = 1;
		}
		else {
			$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
		}
		if ($location_approved == 1) {
			$user_info = get_userdata($user_id);
			$user_email = $user_info->user_email;
			$user_sales_type = 0;
			$rep_name = $user_info->display_name;
			$user_sales_type = get_the_author_meta('lead_finance', $user_id);	
			if ($user_sales_type == "1") {
				$to = $user_email;
			}		
		}
	}
	$from_name = $_POST['fn'] .' '. $_POST['mi'] .' '.$_POST['ln'];
	$subject = __('Finance Request from ', 'car-demon').$site_name;
	$headers = "From: " . strip_tags($_POST['ea']) . "\r\n";
	$headers .= "Reply-To: " . strip_tags($_POST['ea']) . "\r\n";
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
		$xml_body .= adfxml_finance($finance_location, $rep_name, $to);
		$email_body = $email_body.$xml_body.$eol;
	} else {
		$headers .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
	}
	wp_mail($to, $subject, $email_body, $headers);
	$selected_car = $_POST['selected_car'];
	$post_id = get_car_id_from_stock($selected_car);
	apply_filters('car_demon_mail_hook_complete', $holder, 'finance', $to, $subject, $email_body, $headers, $_POST['email'], $post_id, $finance_location);
	$thanks = '<br /><h1>'.__('Thank You', 'car-demon').'</h1>';
	$thanks .= '<br /><h2>'.__('Your Finance Request has been sent.', 'car-demon').'</h2>';
	$thanks .= '<h3>'.__('You should receive a confirmation shortly.', 'car-demon').'</h3>';
	$thanks .= '<h4>'.__('If you have questions or concerns please call and let us know.', 'car-demon').'</h4>';
	echo $thanks;
}
function build_finance_body() {
	$x = '';
	$selected_car = $_POST['purchase_stock'];
	$finance_location = $_POST['finance_location'];
	if (empty($selected_car)) {
		$selected_car = 'Not Decided';
		$finance_email = get_finance_email($finance_location);
		$finance_location .= ' ('.$finance_email.')';
	} else {
		$finance_location = get_finance_location($selected_car);
		$selected_car = get_car_from_stock($selected_car);
		$finance_email = get_finance_email($finance_location);
		$finance_location .= ' ('.$finance_email.')';
	}
	$comment = $_POST['comment'];
	$your_name = $_POST['fn'] .' '. $_POST['mi'] .' '.$_POST['ln'];
	$phone = $_POST['hpn'];
	$email = $_POST['ea'];
	$ssn = $_POST['ssn'];
	$address = '';
	if ($_POST['app_rural_route']) { $address .= ' '.__('RR', 'car-demon').' '.$_POST['app_rural_route']; }
	if ($_POST['app_po_box_num']) { $address .= ' '.__('PO BOX', 'car-demon').' '.$_POST['app_po_box_num']; }
	if ($_POST['app_apt_num']) { $address .= ' '.__('Apt', 'car-demon').' '.$_POST['app_apt_num']; }
	if ($_POST['app_street_num']) { $address .= ' '.$_POST['app_street_num']; }	
	if ($_POST['app_street_name']) { $address .= ' '.$_POST['app_street_name']; }
	if ($_POST['app_street_type']) { $address .= ' '.$_POST['app_street_type']; }	
	$city = $_POST['cty'];
	$state = $_POST['st'];
	$zip = $_POST['zi'];
	$address .= '<br />'.$city.', '.$state.' '.$zip;
	$birthdate = $_POST['bdy'] .'-'. $_POST['bdm'] .'-'. $_POST['bdd'];
	$employer = $_POST['en'];
	$job_title = $_POST['p'];
	$years_on_job = $_POST['yac'] .' '.__('year(s)', 'car-demon').' '.$_POST['mac'] .' '.__('month(s)', 'car-demon');
	$employer_phone = $_POST['epn'];
	setlocale(LC_MONETARY, get_locale());
	$my_local_settings = localeconv();
	if ($my_local_settings['int_curr_symbol'] == "") setlocale(LC_MONETARY, 'en_US');
	$gross_income = money_format("%.0n", $_POST['gmi']);
	$other_income = money_format("%.0n", $_POST['oi']);
	$p_employer = $_POST['p2en'];
	$p_job_title = $_POST['p2p'];
	$p_years_on_job = $_POST['p2yac'] .' '.__('year(s)', 'car-demon').' '.$_POST['p2mac'] .' '.__('month(s)', 'car-demon');
	$p_employer_phone = $_POST['p2epn'];
	if ($_POST['p2gmi']) { $p_gross_income = money_format("%.0n", $_POST['p2gmi']); }
	if ($_POST['p2oi']) { $p_other_income = money_format("%.0n", $_POST['p2oi']); }
	$time_at_address = $_POST['yaca'] .' '.__('year(s)', 'car-demon').' '.$_POST['maca'] .' '.__('month(s)', 'car-demon');
	$rent_or_own = $_POST['roo'];
	$monthly_payment = money_format("%.0n", $_POST['ramp']);
	$p_address = '';
	if ($_POST['p1app_rural_route']) { $p_address .= ' '.__('RR', 'car-demon').' '.$_POST['p1app_rural_route']; }
	if ($_POST['p1app_po_box_num']) { $p_address .= ' '.__('PO BOX', 'car-demon').' '.$_POST['p1app_po_box_num']; }
	if ($_POST['p1app_apt_num']) { $p_address .= ' '.__('Apt', 'car-demon').' '.$_POST['p1app_apt_num']; }
	if ($_POST['p1app_street_num']) { $p_address .= ' '.$_POST['p1app_street_num']; }	
	if ($_POST['p1app_street_name']) { $p_address .= ' '.$_POST['p1app_street_name']; }
	if ($_POST['p1app_street_type']) { $p_address .= ' '.$_POST['p1app_street_type']; }	
	$p_city = $_POST['p1cty'];
	$p_state = $_POST['p1st'];
	$p_zip = $_POST['p1zi'];
	$p_address .= '<br />'.$p_city.', '.$p_state.' '.$p_zip;	
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$right_now = date(get_option('date_format'));
	$blogtime = current_time('mysql'); 
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
	$right_now .= ' '.$hour.':'.$minute.':'.$second;
	$style = " style='margin-top: 10px; padding: 5px 0 15px 0; border: 3px solid #ADADAD; border-left-color: #ECECEC; border-top-color: #ECECEC; background: #F7F7F7;'";
	$best_time_to_contact = $_POST['bcp'] .' '.$_POST['bct'];
	$co_buyer = $_POST['co_fn2'];
	$x = '
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
			<td width="225">'.__('Best Time to Contact', 'car-demon').'</td>
			<td width="225">'.$best_time_to_contact.'</td>
		  </tr>
		  <tr>
			<td>'.__('DOB', 'car-demon').'</td>
			<td>'.$birthdate.'</td>
		  </tr>
		  <tr>
			<td>'.__('Social Security Number', 'car-demon').'</td>
			<td>'.$ssn.'</td>
		  </tr>
		  <tr>
			<td>'.__('Finance Location', 'car-demon').'</td>
			<td>'.$finance_location.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('Living Situation', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
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
			<td>'.__('Current Address', 'car-demon').'</td>
			<td>'.$address.'</td>
		  </tr>
		  <tr>
			<td>'.__('Time at Current Address', 'car-demon').'</td>
			<td>'.$time_at_address.'</td>
		  </tr>
		  <tr>
			<td>'.__('Rent or Own', 'car-demon').'</td>
			<td>'.$rent_or_own.'</td>
		  </tr>
		  <tr>
			<td>'.__('Monthly Payment', 'car-demon').'</td>
			<td>'.$monthly_payment.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  ';
		  if (!empty($p_address)) {
		  	$x .='
			  <tr>
				<td colspan="2" align="center">'.__('Previous Living Situation', 'car-demon').'</td>
			  </tr>
			  <tr>
				<td colspan="2"><hr class="hr_margin" /></td>
			  </tr>
			  <tr>
				<td>'.__('Previous Address', 'car-demon').'</td>
				<td>'.$p_address.'</td>
			  </tr>
			  <tr>
				<td colspan="2"><hr class="hr_margin" /></td>
			  </tr>
			';
		  }
		  $x .= '
		  <tr>
			<td colspan="2" align="center">'.__('Employment History', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Employer', 'car-demon').'</td>
			<td>'.$employer.'</td>
		  </tr>
		  <tr>
			<td>'.__('Job Title', 'car-demon').'</td>
			<td>'.$job_title.'</td>
		  </tr>
		  <tr>
			<td>'.__('Years at Job', 'car-demon').'</td>
			<td>'.$years_on_job.'</td>
		  </tr>
		  <tr>
			<td>'.__('Employer Phone').'</td>
			<td>'.$employer_phone.'</td>
		  </tr>
		  <tr>
			<td>'.__('Gross Income', 'car-demon').'</td>
			<td>'.$gross_income.'</td>
		  </tr>
		  <tr>
			<td>'.__('Other Income', 'car-demon').'</td>
			<td>'.$other_income.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  ';
		if (!empty($p_employer)) {
			$x .= '
		  <tr>
			<td colspan="2" align="center">'.__('Previous Employer', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Employer', 'car-demon').'</td>
			<td>'.$p_employer.'</td>
		  </tr>
		  <tr>
			<td>'.__('Job Title', 'car-demon').'</td>
			<td>'.$p_job_title.'</td>
		  </tr>
		  <tr>
			<td>'.__('Years at Job', 'car-demon').'</td>
			<td>'.$p_years_on_job.'</td>
		  </tr>
		  <tr>
			<td>'.__('Employer Phone', 'car-demon').'</td>
			<td>'.$p_employer_phone.'</td>
		  </tr>
		  <tr>
			<td>'.__('Gross Income', 'car-demon').'</td>
			<td>'.$p_gross_income.'</td>
		  </tr>
		  <tr>
			<td>'.__('Other Income', 'car-demon').'</td>
			<td>'.$p_other_income.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
			';
		}
		  $x .= '
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
		  ';
		  if (!empty($co_buyer)) {
		  	$x .= get_co_buyer();
		  }
		  $x .='
		  <tr>
			<td colspan="2" align="center">'.__('SENDER INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>';
		$location = $_POST['finance_location'];
		$selected_car = $_POST['purchase_stock'];
		if (!empty($selected_car)) {
			$location = get_finance_location($selected_car);
		}
		$x = apply_filters('car_demon_mail_hook_subscribe', $x, 'finance', $location, $selected_car);
		  $x .= '
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
	return $x;
}
function get_co_buyer() {
	$your_name = $_POST['co_fn2'] .' '. $_POST['co_mi'] .' '.$_POST['co_ln2'];
	$phone = $_POST['co_hpn2'];
	$email = $_POST['co_ea2'];
	$ssn = $_POST['co_ssn2'];
	$address = '';
	if ($_POST['co_app_rural_route']) { $address .= ' '.__('RR', 'car-demon').' '.$_POST['co_app_rural_route']; }
	if ($_POST['co_app_po_box_num']) { $address .= ' '.__('PO BOX', 'car-demon').' '.$_POST['co_app_po_box_num']; }
	if ($_POST['co_app_apt_num']) { $address .= ' '.__('Apt', 'car-demon').' '.$_POST['co_app_apt_num']; }
	if ($_POST['co_app_street_num']) { $address .= ' '.$_POST['co_app_street_num']; }	
	if ($_POST['co_app_street_name']) { $address .= ' '.$_POST['co_app_street_name']; }
	if ($_POST['co_app_street_type']) { $address .= ' '.$_POST['co_app_street_type']; }	
	$city = $_POST['co_cty2'];
	$state = $_POST['co_st2'];
	$zip = $_POST['co_zi2'];
	$address .= '<br />'.$city.', '.$state.' '.$zip;
	$birthdate = $_POST['co_bdy'] .'-'. $_POST['co_bdm'] .'-'. $_POST['co_bdd'];
	$employer = $_POST['co_en2'];
	$job_title = $_POST['co_p2'];
	$years_on_job = $_POST['co_yac2'] .' '.__('year(s)', 'car-demon').' '.$_POST['co_mac2'] .' '.__('month(s)', 'car-demon');
	$employer_phone = $_POST['co_epn2'];
	setlocale(LC_MONETARY, get_locale());
	$my_local_settings = localeconv();
	if ($my_local_settings['int_curr_symbol'] == "") setlocale(LC_MONETARY, 'en_US');
	$gross_income = money_format("%.0n", $_POST['co_gmi2']);
	$other_income = money_format("%.0n", $_POST['co_oi2']);
	$p_employer = $_POST['p1co_en2'];
	$p_job_title = $_POST['p1co_p2'];
	$p_years_on_job = $_POST['p1co_yac1'] .' '.__('year(s)', 'car-demon').' '.$_POST['p1co_mac2'] .' '.__('month(s)', 'car-demon');
	$p_employer_phone = $_POST['p1co_epn2'];
	if ($_POST['p1co_gmi2']) { $p_gross_income = money_format("%.0n", $_POST['p1co_gmi2']); }
	if ($_POST['p1co_oi2']) { $p_other_income = money_format("%.0n", $_POST['p1co_oi2']); }
	$time_at_address = $_POST['co_yaca2'] .' '.__('year(s)', 'car-demon').' '.$_POST['co_maca2'] .' '.__('month(s)', 'car-demon');
	$rent_or_own = $_POST['co_roo2'];
	$monthly_payment = money_format("%.0n", $_POST['co_ramp2']);
	$p_address = '';
	if ($_POST['p1co_app_rural_route']) { $p_address .= ' '.__('RR', 'car-demon').' '.$_POST['p1co_app_rural_route']; }
	if ($_POST['p1co_app_po_box_num']) { $p_address .= ' '.__('PO BOX', 'car-demon').' '.$_POST['p1co_app_po_box_num']; }
	if ($_POST['p1co_app_apt_num']) { $p_address .= ' '.__('Apt', 'car-demon').' '.$_POST['p1co_app_apt_num']; }
	if ($_POST['p1co_app_street_num']) { $p_address .= ' '.$_POST['p1co_app_street_num']; }	
	if ($_POST['p1co_app_street_name']) { $p_address .= ' '.$_POST['p1co_app_street_name']; }
	if ($_POST['p1co_app_street_type']) { $p_address .= ' '.$_POST['p1co_app_street_type']; }	
	$p_city = $_POST['p1co_cty2'];
	$p_state = $_POST['p1co_st2'];
	$p_zip = $_POST['p1co_zi2'];
	$p_address .= '<br />'.$p_city.', '.$p_state.' '.$p_zip;	
	$best_time_to_contact = $_POST['co_bcp2'] .' '.$_POST['co_bct2'];
	$x = '
		  <tr>
			<td colspan="2" align="center">'.__('CO-BUYER INFORMATION', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td width="225">'.__('Name', 'car-demon').'</td>
			<td width="225">'.$your_name.'</td>
		  </tr>
		  <tr>
			<td width="225">'.__('Best Time to Contact', 'car-demon').'</td>
			<td width="225">'.$best_time_to_contact.'</td>
		  </tr>
		  <tr>
			<td>'.__('DOB').'</td>
			<td>'.$birthdate.'</td>
		  </tr>
		  <tr>
			<td>'.__('Social Security Number', 'car-demon').'</td>
			<td>'.$ssn.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">'.__('Co-Buyer Living Situation', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
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
			<td>'.__('Current Address', 'car-demon').'</td>
			<td>'.$address.'</td>
		  </tr>
		  <tr>
			<td>'.__('Time at Current Address', 'car-demon').'</td>
			<td>'.$time_at_address.'</td>
		  </tr>
		  <tr>
			<td>'.__('Rent or Own', 'car-demon').'</td>
			<td>'.$rent_or_own.'</td>
		  </tr>
		  <tr>
			<td>'.__('Monthly Payment', 'car-demon').'</td>
			<td>'.$monthly_payment.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  ';
		  if (!empty($p_address)) {
		  	$x .='
			  <tr>
				<td colspan="2" align="center">'.__('Co-Buyer Previous Living Situation', 'car-demon').'</td>
			  </tr>
			  <tr>
				<td colspan="2"><hr class="hr_margin" /></td>
			  </tr>
			  <tr>
				<td>'.__('Previous Address', 'car-demon').'</td>
				<td>'.$p_address.'</td>
			  </tr>
			  <tr>
				<td colspan="2"><hr class="hr_margin" /></td>
			  </tr>
			';
		  }
		  $x .= '
		  <tr>
			<td colspan="2" align="center">'.__('Co-Buyer Employment History', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Employer', 'car-demon').'</td>
			<td>'.$employer.'</td>
		  </tr>
		  <tr>
			<td>'.__('Job Title', 'car-demon').'</td>
			<td>'.$job_title.'</td>
		  </tr>
		  <tr>
			<td>'.__('Years at Job', 'car-demon').'</td>
			<td>'.$years_on_job.'</td>
		  </tr>
		  <tr>
			<td>'.__('Employer Phone', 'car-demon').'</td>
			<td>'.$employer_phone.'</td>
		  </tr>
		  <tr>
			<td>'.__('Gross Income', 'car-demon').'</td>
			<td>'.$gross_income.'</td>
		  </tr>
		  <tr>
			<td>'.__('Other Income', 'car-demon').'</td>
			<td>'.$other_income.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  ';
		if (!empty($p_employer)) {
			$x .= '
		  <tr>
			<td colspan="2" align="center">'.__('Co-Buyer Previous Employer', 'car-demon').'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
		  <tr>
			<td>'.__('Employer', 'car-demon').'</td>
			<td>'.$p_employer.'</td>
		  </tr>
		  <tr>
			<td>'.__('Job Title', 'car-demon').'</td>
			<td>'.$p_job_title.'</td>
		  </tr>
		  <tr>
			<td>'.__('Years at Job', 'car-demon').'</td>
			<td>'.$p_years_on_job.'</td>
		  </tr>
		  <tr>
			<td>'.__('Employer Phone', 'car-demon').'</td>
			<td>'.$p_employer_phone.'</td>
		  </tr>
		  <tr>
			<td>'.__('Gross Income', 'car-demon').'</td>
			<td>'.$p_gross_income.'</td>
		  </tr>
		  <tr>
			<td>'.__('Other Income', 'car-demon').'</td>
			<td>'.$p_other_income.'</td>
		  </tr>
		  <tr>
			<td colspan="2"><hr class="hr_margin" /></td>
		  </tr>
			';
		}
	return $x;
}
function get_finance_location($selected_car) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value = '".$selected_car."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$location_name = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
			$terms = get_the_terms($post_id, 'vehicle_location');
			foreach ($terms as $term) {
				if ($term->name == $location_name) {
					$current_location = $term->slug;
					$x = get_option($current_location.'_finance_name');
				}		
			}
		}
	}
	return $x;
}
function get_finance_email($finance_location) {
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
		$location_name = get_option($current_location.'_finance_name');
		if ($finance_location == $location_name) {
			$html = get_option($current_location.'_finance_email');
		}
	}
	return $html;
}
function adfxml_finance($location_name, $rep_name, $rep_email) {
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
	$selected_car = $_POST['purchase_stock'];
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
	//== Contact
	$full_name = $_POST['fn'] .' '. $_POST['mi'] .' '.$_POST['ln'];;
	$first_name = $_POST['fn'];
	$last_name = $_POST['ln'];
	$contact_email = $_POST['ea'];
	$phone = $_POST['hpn'];
	$street = $_POST['co_app_street_num'].' '.$_POST['co_app_street_name'];
	$apartment = $_POST['co_app_apt_num'];
	$city = $_POST['cty'];
	$state = $_POST['st'];
	$zip = $_POST['zi'];
	$country = 'US';
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
					<finance>
						<method>Finance</method>
					</finance>
				</vehicle>
				<customer>
					<contact>
						<name part="full">'.$full_name.'</name>
						<name part="first">'.$first_name.'</name>
						<name part="last">'.$last_name.'</name>
						<email>'.$contact_email.'</email>
						<phone type="voice">'.$phone.'</phone>
						<address type="home">
							<street line="1">'.$street.'</street>
							<apartment>'.$apartment.'</apartment>
							<city>'.$city.'</city>
							<regioncode>'.$state.'</regioncode>
							<postalcode>'.$zip.'</postalcode>
							<country>'.$country.'</country>
						</address>
					</contact>
					<comments>'.$contact_needed.'</comments>
				</customer>
				<vendor>
					<vendorname>'.$location_name.'</vendorname>
					<contact primarycontact="1">
						<name part="full">'.$rep_name.'</name>
						<email>'.$rep_email.'</email>
					</contact>
				</vendor>
				<provider>
					<name part="full">'.$blog_name.' '.__('Finance Form', 'car-demon').'</name>
					<url>'.$blog_url.'</url>
					<email>'.$blog_email.'</email>
				</provider>
			</prospect>
		</adf>
	';
	return $x;
}
?>