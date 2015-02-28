<?php
include( 'forms/car-demon-contact-us.php' );
include( 'forms/car-demon-trade-form.php' );
include( 'forms/car-demon-service-form.php' );
include( 'forms/car-demon-service-quote.php' );
include( 'forms/car-demon-part-request.php' );
include( 'forms/car-demon-finance-form.php' );
include( 'forms/car-demon-qualify-form.php' );
include( 'handlers/car-demon-contact-us-handler.php' );
include( 'handlers/car-demon-trade-form-handler.php' );
include( 'handlers/car-demon-part-handler.php' );
include( 'handlers/car-demon-service-handler.php' );
include( 'handlers/car-demon-service-quote-handler.php' );
include( 'handlers/car-demon-qualify-handler.php' );
include( 'handlers/car-demon-email-friend-handler.php' );

add_action("wp_ajax_cd_contact_us_handler", "cd_contact_us_handler");
add_action("wp_ajax_nopriv_cd_contact_us_handler", "cd_contact_us_handler");
//======
add_action("wp_ajax_cd_trade_handler", "cd_trade_handler");
add_action("wp_ajax_nopriv_cd_trade_handler", "cd_trade_handler");
//======
add_action("wp_ajax_cd_trade_show_stock", "cd_trade_show_stock");
add_action("wp_ajax_nopriv_cd_trade_show_stock", "cd_trade_show_stock");
//======
add_action("wp_ajax_cd_trade_find_stock", "cd_trade_find_stock");
add_action("wp_ajax_nopriv_cd_trade_find_stock", "cd_trade_find_stock");
add_action("wp_ajax_cd_trade_find_vehicle", "cd_trade_find_vehicle");
add_action("wp_ajax_nopriv_cd_trade_find_vehicle", "cd_trade_find_vehicle");
//======
add_action("wp_ajax_cd_parts_handler", "cd_parts_handler");
add_action("wp_ajax_nopriv_cd_parts_handler", "cd_parts_handler");
//======
add_action("wp_ajax_cd_service_handler", "cd_service_handler");
add_action("wp_ajax_nopriv_cd_service_handler", "cd_service_handler");
//======
add_action("wp_ajax_cd_service_quote_handler", "cd_service_quote_handler");
add_action("wp_ajax_nopriv_cd_service_quote_handler", "cd_service_quote_handler");
//======
add_action("wp_ajax_cd_qualify_handler", "cd_qualify_handler");
add_action("wp_ajax_nopriv_cd_qualify_handler", "cd_qualify_handler");
//======
add_action("wp_ajax_email_friend_handler", "email_friend_handler");
add_action("wp_ajax_nopriv_email_friend_handler", "email_friend_handler");
//======

add_action( 'wp_enqueue_scripts', 'cd_forms_enqueue_style' );
function cd_forms_enqueue_style() {
	//= Load jquery-ui.css so autocomplete will work
	wp_enqueue_style('jquery-ui-css', WP_PLUGIN_URL.'/car-demon/theme-files/css/jquery-ui.css');
	//= wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
	wp_enqueue_script('jquery-ui-js', WP_PLUGIN_URL.'/car-demon/theme-files/js/jquery-ui.js', array('jquery'));
	//= wp_enqueue_script('jquery-ui-js', 'http://code.jquery.com/ui/1.10.4/jquery-ui.js');
}
if (!is_admin()) {
	add_filter('the_posts', 'cd_conditionally_add_scripts_and_styles'); // the_posts gets triggered before wp_head
}
function cd_conditionally_add_scripts_and_styles($posts){
	if (empty($posts)) return $posts;
	$use_css = 1;
	$x = '';
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			$use_css = 1;
		}
	} else {
		$use_css = 1;
	}
	if ($use_css == 1) {
		$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
		foreach ($posts as $post) {
			if (stripos($post->post_content, '[contact_us') !== false || stripos($post->post_content, '[staff') !== false) {
				wp_register_script("car-demon-common-js", WP_PLUGIN_URL."/car-demon/car-demon-forms/forms/js/car-demon-common.js", array('jquery') );
				wp_register_script("car-demon-contact-us-form-js", WP_PLUGIN_URL.'/car-demon/car-demon-forms/forms/js/car-demon-contact-us.js', array('jquery') );
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-contact-us-form-js', 'cdContactParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You did not select who you want to send this message to.', 'car-demon'),
					'error6' => __('You did not enter a message to send.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'contact_us', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'contact_us', 'unk'),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-contact-us-form-js' );
			}
			if (stripos($post->post_content, '[finance_form') !== false) {
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-trade-form-js', 'cdTradeParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You did not select who you want to send this message to.', 'car-demon'),
					'error6' => __('You did not enter a message to send.', 'car-demon'),
					'error7' => __('You must enter the year of the vehicle you wish to trade.', 'car-demon'),
					'error8' => __('You must enter the manufacturer of the vehicle you wish to trade.', 'car-demon'),
					'error9' => __('You must enter the model of the vehicle you wish to trade.', 'car-demon'),
					'error10' => __('You must enter the miles of the vehicle you wish to trade.', 'car-demon'),
					'error11' => __('You indicated you were interested in purchasing a vehicle but did not select one.', 'car-demon'),
					'error12' => __('You did not select a trade location.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk'),
					'validate_phone' => $validate_phone
				));
			}
			if (stripos($post->post_content, '[trade') !== false) {
				wp_enqueue_style('cd-jquery-autocomplete-css', WP_PLUGIN_URL.'/car-demon/theme-files/css/jquery.autocomplete.css');
				wp_register_script("cd-jquery-autocomplete-js", WP_PLUGIN_URL.'/car-demon/theme-files/js/jquery.autocomplete.js', array('jquery') );
				wp_register_script("car-demon-common-js", WP_PLUGIN_URL."/car-demon/car-demon-forms/forms/js/car-demon-common.js", array('jquery') );
				wp_register_script("car-demon-trade-form-js", WP_PLUGIN_URL.'/car-demon/car-demon-forms/forms/js/car-demon-trade.js', array('jquery'), false, true );
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-trade-form-js', 'cdTradeParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You did not select who you want to send this message to.', 'car-demon'),
					'error6' => __('You did not enter a message to send.', 'car-demon'),
					'error7' => __('You must enter the year of the vehicle you wish to trade.', 'car-demon'),
					'error8' => __('You must enter the manufacturer of the vehicle you wish to trade.', 'car-demon'),
					'error9' => __('You must enter the model of the vehicle you wish to trade.', 'car-demon'),
					'error10' => __('You must enter the miles of the vehicle you wish to trade.', 'car-demon'),
					'error11' => __('You indicated you were interested in purchasing a vehicle but did not select one.', 'car-demon'),
					'error12' => __('You did not select a trade location.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk'),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'cd-jquery-autocomplete-js' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-trade-form-js' );
			}
			if (stripos($post->post_content, '[part_request') !== false) {
				wp_register_script('car-demon-part-request-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-part-request.js');
				wp_register_script('car-demon-common-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-common.js');
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-part-request-js', 'cdPartsParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You may only add 10 parts to your request', 'car-demon'),
					'error2' => __('If you need additional parts please add them in the comment area.', 'car-demon'),
					'error3' => __('You must enter your name.', 'car-demon'),
					'error4' => __('You must enter your name.', 'car-demon'),
					'error5' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error6' => __('The phone number you entered is not valid.', 'car-demon'),
					'error7' => __('You did not select a part location.', 'car-demon'),
					'error8' => __('You need to add at least the name of one part you are looking for.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'trade', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'trade', 'unk'),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-part-request-js' );
			}
			if (stripos($post->post_content, '[service_form') !== false) {
				wp_register_script('car-demon-service-form-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-service-form.js');
				wp_register_script('car-demon-common-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-common.js');
				wp_register_script('car-demon-service-calendar-js', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/CalendarPopup.js');
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-service-form-js', 'cdServiceParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You did not select a service location.', 'car-demon'),
					'error6' => __('You did not select a preferred appointment date.', 'car-demon'),
					'error7' => __('You did not select an alternate appointment date.', 'car-demon'),
					'error8' => __('You did not tell us what kind of service you need.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'service', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'service', 'unk'),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_localize_script( 'car-demon-service-calendar-js', 'cdCalendarParams', array(
					'jan' => __('January', 'car-demon'),
					'feb' => __('February', 'car-demon'),
					'mar' => __('March', 'car-demon'),
					'apr' => __('April', 'car-demon'),
					'may' => __('May', 'car-demon'),
					'jun' => __('June', 'car-demon'),
					'jul' => __('July', 'car-demon'),
					'aug' => __('August', 'car-demon'),
					'sep' => __('September', 'car-demon'),
					'oct' => __('October', 'car-demon'),
					'nov' => __('November', 'car-demon'),
					'dec' => __('December', 'car-demon'),
					'picktime' => __('Pick Time', 'car-demon'),
					'early_morning' => __('Early Morning', 'car-demon'),
					'mid_morning' => __('Mid Morning', 'car-demon'),
					'late_morning' => __('Late Morning', 'car-demon'),
					'early_afternoon' => __('Early Afternoon', 'car-demon'),
					'mid_afternoon' => __('Mid Afternoon', 'car-demon'),
					'late_afternoon' => __('Late Afternoon', 'car-demon'),				
					'clear' => __('Clear', 'car-demon'), 
					'close_it' => __('Close', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-service-form-js' );
				wp_enqueue_script( 'car-demon-service-calendar-js' );
			}
			if (stripos($post->post_content, '[service_quote') !== false) {
				wp_register_script('car-demon-service-quote-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-service-quote.js');
				wp_register_script('car-demon-common-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-common.js');
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-service-quote-js', 'cdServiceQuoteParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You did not select a service location.', 'car-demon'),
					'error6' => __('You did not tell us what kind of service you need.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'service_quote', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'service_quote', 'unk'),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-service-quote-js' );
			}
			if (stripos($post->post_content, '[qualify') !== false) {
				wp_register_script('car-demon-qualify-us-form-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-qualify.js');
				wp_register_script('car-demon-common-js', WP_CONTENT_URL . '/plugins/car-demon/car-demon-forms/forms/js/car-demon-common.js');
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-qualify-us-form-js', 'cdQualifyParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You must enter your city.', 'car-demon'),
					'error6' => __('You must enter your employer.', 'car-demon'),
					'error7' => __('You must enter your income.', 'car-demon'),
					'error8' => __('You did not select who you want to send this message to.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'qualify', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'qualify', 'unk'),
					'validate_phone' => $validate_phone
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-qualify-us-form-js' );
			}
		}
	}
	return $posts;
}
?>