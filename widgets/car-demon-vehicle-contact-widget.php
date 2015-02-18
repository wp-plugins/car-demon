<?php
include('car-demon-vehicle-contact-widget-handler.php');
add_action( 'widgets_init', 'car_demon_vehicle_contact_load_widgets' );
add_action("wp_ajax_cd_contact_us_widget_handler", "cd_contact_us_widget_handler");
add_action("wp_ajax_nopriv_cd_contact_us_widget_handler", "cd_contact_us_widget_handler");
//======
function car_demon_vehicle_contact_load_widgets() {
	register_widget( 'car_demon_vehicle_contact_Widget' );
}
class car_demon_vehicle_contact_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function car_demon_vehicle_contact_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_vehicle_contact', 'description' => __('Display contact form on Vehicle Pages.', 'car-demon') );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_vehicle_contact-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'car_demon_vehicle_contact-widget', __('Car Demon Vehicle Contact', 'car-demon'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		$post_id = $GLOBALS['post']->ID;
		$post_type = get_post_type($post_id);
		$x = '';
		if ( $post_type == 'cars_for_sale' ) {
			if (is_single($post_id)) {
				$car_demon_pluginpath = CAR_DEMON_PATH;
				$car_demon_pluginpath = str_replace('widgets/', '', $car_demon_pluginpath);
				wp_register_script('car-demon-contact-widget-js', WP_CONTENT_URL . '/plugins/car-demon/widgets/js/car-demon-vehicle-contact-widget.js');
				$validate_phone = 0;
				if (isset($_SESSION['car_demon_options']['validate_phone'])) {
					if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
						$validate_phone = 1;
					}
				}
				wp_localize_script( 'car-demon-contact-widget-js', 'cdContactWidgetParams', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error1' => __('You must enter your name.', 'car-demon'),
					'error2' => __('You must enter your name.', 'car-demon'),
					'error3' => __('You must enter a valid Phone Number.', 'car-demon'),
					'error4' => __('The phone number you entered is not valid.', 'car-demon'),
					'error5' => __('You did not select who you want to send this message to.', 'car-demon'),
					'error6' => __('You did not enter a message to send.', 'car-demon'),
					'form_js' => apply_filters('car_demon_mail_hook_js', $x, 'contact_us', 'unk'),
					'form_data' => apply_filters('car_demon_mail_hook_js_data', $x, 'contact_us', 'unk'),
					'validate_phone' => $validate_phone,
					'path_url' => $car_demon_pluginpath
				));
				wp_localize_script( 'car-demon-common-js', 'cdCommonParams', array(
					'error1' => __('You didn\'t enter an email address.', 'car-demon'),
					'error2' => __('Please enter a valid email address.', 'car-demon'),
					'error2' => __('The email address contains illegal characters.', 'car-demon')
				));
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'car-demon-common-js' );
				wp_enqueue_script( 'car-demon-contact-widget-js' );
				extract( $args );
				/* Our variables from the widget settings. */
				$title = apply_filters('widget_title', $instance['title'] );
				$list_phone = $instance['list_phone'];
				$cc = $instance['cc'];
				$send_receipt = $instance['send_receipt'];
				$send_receipt_msg = $instance['send_receipt_msg'];
				/* Before widget (defined by themes). */
				echo $before_widget;
				/* Display the widget title if one was input (before and after defined by themes). */
				if (!empty($title)) {
					echo $before_title . $title . $after_title;
				}
				echo '<div class="contact_us_widget">';
					echo car_demon_display_vehicle_contacts($post_id, $list_phone, $cc, $send_receipt, $send_receipt_msg);
				echo '</div>';
				/* After widget (defined by themes). */
				echo $after_widget;
			}
		}
	}
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['list_phone'] = strip_tags( $new_instance['list_phone'] );
		$instance['cc'] = strip_tags( $new_instance['cc'] );
		$instance['send_receipt'] = strip_tags( $new_instance['send_receipt'] );
		$instance['send_receipt_msg'] = strip_tags( $new_instance['send_receipt_msg'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __('Contact Us', 'car-demon'),
			'list_phone' => __('No', 'car-demon'),
			'cc' => __('', 'car-demon'),
			'send_receipt' => __('No', 'car-demon'),
			'send_receipt_msg' => __('', 'car-demon'),
			 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon'); ?></label>
			<br /><input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="car_demon_wide" />		
			<br /><label for="<?php echo $this->get_field_id( 'list_phone' ); ?>"><?php _e('List Phone:', 'car-demon'); ?></label>
			<br /><select id="<?php echo $this->get_field_id( 'list_phone' ); ?>" name="<?php echo $this->get_field_name( 'list_phone' ); ?>">
				<option value="<?php echo $instance['list_phone']; ?>"><?php echo $instance['list_phone']; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>
			<br /><label for="<?php echo $this->get_field_id( 'cc' ); ?>"><?php _e('CC:', 'car-demon'); ?></label>
			<br /><input id="<?php echo $this->get_field_id( 'cc' ); ?>" name="<?php echo $this->get_field_name( 'cc' ); ?>" value="<?php echo $instance['cc']; ?>" class="car_demon_wide" />
			<br /><label for="<?php echo $this->get_field_id( 'send_receipt' ); ?>"><?php _e('Send Confirmation Receipt:', 'car-demon'); ?></label>
			<br /><select id="<?php echo $this->get_field_id( 'send_receipt' ); ?>" name="<?php echo $this->get_field_name( 'send_receipt' ); ?>">
				<option value="<?php echo $instance['send_receipt']; ?>"><?php echo $instance['send_receipt']; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>
			<br /><label for="<?php echo $this->get_field_id( 'send_receipt_msg' ); ?>"><?php _e('Additional Confirmation Message:', 'car-demon'); ?></label>
			<br /><textarea rows="4" id="<?php echo $this->get_field_id( 'send_receipt_msg' ); ?>" name="<?php echo $this->get_field_name( 'send_receipt_msg' ); ?>" class="car_demon_wide"><?php echo $instance['send_receipt_msg']; ?></textarea>
		</p>
	<?php
	}
}
function car_demon_display_vehicle_contacts($post_id, $list_phone, $cc, $send_receipt, $send_receipt_msg) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/widgets', '', $car_demon_pluginpath);
	$car_contact = get_car_contact($post_id);
	$contact_sales_name = $car_contact['sales_name'];
	$contact_sales_email = $car_contact['sales_email'];
	$contact_sales_phone = $car_contact['sales_phone'];
	$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
	$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
	$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
	$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
	$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_location_term = get_the_terms( $post_id, 'vehicle_location');
	$vehicle_location_slug = '';
	if ($vehicle_location_term) {
		foreach( $vehicle_location_term as $term ) {
			$vehicle_location_slug = $term->slug;
		}
	}
	$vehicle_stock_number = get_post_meta($post_id, "_stock_value", true);
	$phone_contact = '';
	if ($list_phone == 'Yes') {
		$phone_contact = '<h4>'. __('For more information','car-demon').'<br />'.__('Call') .' '.$contact_sales_name.' '.__('at:','car-demon').'<br />'.$contact_sales_phone.'</h4>';
	}
	if (isset($_SESSION['car_demon_options']['validate_phone'])) {
		if ($_SESSION['car_demon_options']['validate_phone'] == 'Yes') {
			$validate_phone = ' onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);"';
		} else {
			$validate_phone = '';
		}
	} else {
		$validate_phone = '';
	}
	$nonce = wp_create_nonce("cd_contact_us_widget_nonce");
	$x = $phone_contact.'
		<div id="contact_msg" class="contact_msg"></div>
		<form enctype="multicontact/form-data" action="?send_contact=1" method="post" class="cdform contact-appointment " id="contact_form">
			<input type="hidden" name="cc" id="cc" value="'.$cc.'" />
			<input type="hidden" name="send_receipt" id="send_receipt" value="'.$send_receipt.'" />
			<input type="hidden" name="send_receipt_msg" id="send_receipt_msg" value="'.$send_receipt_msg.'" />
			<input type="hidden" name="send_to" id="send_to" value="'.$contact_sales_email.'" />
			<input type="hidden" name="send_to_name" id="send_to_name" value="'.$contact_sales_name.'" />
			<input type="hidden" name="car_id" id="car_id" value="'.$post_id.'" />
			<input type="hidden" name="vehicle_vin" id="vehicle_vin" value="'.$vehicle_vin.'" />
			<input type="hidden" name="vehicle_year" id="vehicle_year" value="'.$vehicle_year.'" />
			<input type="hidden" name="vehicle_make" id="vehicle_make" value="'.$vehicle_make.'" />
			<input type="hidden" name="vehicle_model" id="vehicle_model" value="'.$vehicle_model.'" />
			<input type="hidden" name="vehicle_condition" id="vehicle_condition" value="'.$vehicle_condition.'" />
			<input type="hidden" name="vehicle_location" id="vehicle_location" value="'.$vehicle_location.'" />
			<input type="hidden" name="vehicle_stock_number" id="vehicle_stock_number" value="'.$vehicle_stock_number.'" />
			<input type="hidden" name="vehicle_photo" id="vehicle_photo" value="'.wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) ).'" />
			<input type="hidden" name="nonce" id="nonce" value="'.$nonce.'" />
			<fieldset class="cd-fs1">
			<legend>Your Information</legend>
			<ol class="cd-ol">
				<li id="li-name" class=""><label for="cd_field_2"><span>Your Name</span></label><input type="text" name="cd_name" id="cd_name" class="single fldrequired" value="Your Name" onfocus="clearField(this)" onblur="setField(this)"><span class="reqtxt">*</span></li>
				<li id="li" class=""><label for="cd_field_"><span>Phone #</span></label><input type="text" name="cd_phone" id="cd_phone" class="single fldrequired" value="" '.$validate_phone.'><span class="reqtxt">*</span></li>
				<li id="li-4" class=""><label for="cd_field_4"><span>Email</span></label><input type="text" name="cd_email" id="cd_email" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
			</ol>
			</fieldset>
	';
	$x2 = '
			<fieldset class="cd-fs2">
				<legend>I have a Trade&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select>
						<option></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</legend>
			</fieldset>
			<fieldset class="cd-fs2">
				<legend>I need Financing&nbsp;&nbsp;
					<select>
						<option></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</legend>
			</fieldset>
	';
	$add = '<img src="'.$car_demon_pluginpath.'images/btn_add_contact.png" id="add_contact_btn" class="add_contact_btn" onclick="add_contact();" class="add_contact" title="'.__('Add Contact','car-demon').'" />';
	$remove = '<img src="'.$car_demon_pluginpath.'images/btn_remove_contact.png" id="remove_contact_btn" class="remove_contact_btn" onclick="remove_contact();" class="remove_contact" title="'.__('Remove Contact','car-demon').'" />';
	$x .='
			<fieldset class="cd-fs4">
			<legend>Questions or Comments</legend>
			<ol class="cd-ol">
				<li id="li-5" class=""><textarea name="contact_needed" id="contact_needed" class="contact_us_comment fldrequired"></textarea><br /><span class="reqtxt reqtxt_comment"><br />* required</span></li>
			</ol>
			</fieldset>';
	$x = apply_filters('car_demon_mail_hook_form', $x, 'contact_us_vehicle', $vehicle_location_slug);
	$x .= '<p class="cd-sb"><input type="button" name="search_btn" id="sendbutton" class="search_btn contact_us_btn" value="Send Now!" onclick="return car_demon_validate()"></p></form>
		';
	return $x;
}
?>