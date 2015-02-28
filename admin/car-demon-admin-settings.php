<?php
include('car-demon-admin-vehicle-options.php');
function car_demon_settings_page() {
	add_submenu_page( 'edit.php?post_type=cars_for_sale', 'Contact Settings', 'Contact Settings', 'edit_pages', 'car_demon_plugin_options', 'car_demon_plugin_options_do_page' );
	add_submenu_page( 'edit.php?post_type=cars_for_sale', 'Car Demon Settings', 'Car Demon Settings', 'edit_pages', 'car_demon_settings_options', 'car_demon_settings_options_do_page' );
	add_action( 'admin_enqueue_scripts', 'car_demon_admin_car_scripts' );
}
add_action( 'admin_menu', 'car_demon_settings_page' );
function get_my_post_thumbnail_id_detail_eil( $post_id = NULL ) {
	global $id;
	$post_id = ( NULL === $post_id ) ? $id : $post_id;
	$my_pic = get_post_meta( $post_id, '_thumbnail_id', true );
	return $my_pic;
}
function be_hidden_meta_boxes( $hidden, $screen ) {
	if ( 'cars-for-sale' == $screen->base )
		$hidden = array('postcustom', 'slugdiv', 'trackbacksdiv', 'postexcerpt', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv');
	return $hidden;
}
function car_demon_admin_car_scripts() {
	wp_register_script('car-demon-admin-js', WP_CONTENT_URL . '/plugins/car-demon/admin/js/car-demon-admin.js','','',true);
	wp_localize_script( 'car-demon-admin-js', 'cdAdminParams', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'error1' => __('You must fill out both fields before adding a new option group.','car-demon'),
		'msg_update' => __('Option Group Updated','car-demon')
	));
	wp_enqueue_script('car-demon-admin-js');
	wp_enqueue_style('car-demon-admin-css', WP_CONTENT_URL . '/plugins/car-demon/admin/css/car-demon-admin.css');
}
add_action("wp_ajax_car_demon_admin_update", "car_demon_admin_update");
add_action("wp_ajax_nopriv_car_demon_admin_update", "car_demon_admin_update");
add_action("wp_ajax_car_demon_add_option_group", "car_demon_add_option_group");
add_action("wp_ajax_nopriv_car_demon_add_option_group", "car_demon_add_option_group");
add_action("wp_ajax_car_demon_remove_option_group", "car_demon_remove_option_group");
add_action("wp_ajax_nopriv_car_demon_remove_option_group", "car_demon_remove_option_group");
add_action("wp_ajax_car_demon_update_option_group", "car_demon_update_option_group");
add_action("wp_ajax_nopriv_car_demon_update_option_group", "car_demon_update_option_group");
add_action("wp_ajax_car_demon_update_default_fields", "car_demon_update_default_fields");
add_action("wp_ajax_nopriv_car_demon_update_default_fields", "car_demon_update_default_fields");
add_action("wp_ajax_car_demon_update_default_labels", "car_demon_update_default_labels");
add_action("wp_ajax_nopriv_car_demon_update_default_labels", "car_demon_update_default_labels");

function car_demon_admin_update() {
	$post_id = $_POST['post_id'];
	$fld = $_POST['fld'];
	$val = $_POST['val'];
	update_post_meta( $post_id, $fld, $val);
}
function car_demon_plugin_options_do_page() {
	screen_icon();
	echo '<div class="wrap">';
		echo "<h2>". __('Car Demon Contact Options', 'car-demon') . "</h2>";
		echo __('For support please visit', 'car-demon').' <a href="http://www.cardemons.com" target="demon_win">www.CarDemons.com</a><br />';
		echo __('Each location will have it\'s own contact settings. Make sure you scroll to the bottom of the page.','car-demon');
		admin_contact_forms();
	echo '</div>';
}
function admin_contact_forms() {
	if (isset($_POST['update_location_options'])) {
		update_car_location_options();
	}
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
	$holder = '';
	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	$location_list = 'default'.$location_list;
	$location_name_list = 'Default'.$location_name_list;
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;
	foreach ($location_list_array as $current_location) {
		?>
		<form action="" name="frm_admin" method="post" class="cd_admin_form">
		<input type="hidden" name="update_location_options" id="update_location_options" value="1" />
		<h1><?php echo $location_name_list_array[$x]; ?> <?php _e('Contact Information', 'car-demon'); ?></h1>
		<span class="cd_admin_form_label"><?php _e('Facebook Fan Page', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_facebook_page') ?>" name="<?php echo $current_location; ?>_facebook_page" id="<?php echo $current_location; ?>_facebook_page" />
		<br />
		<span class="cd_admin_form_label"><?php _e('New Sales Name', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_new_sales_name') ?>" name="<?php echo $current_location; ?>_new_sales_name" id="<?php echo $current_location; ?>_new_sales_name" />
		<br />
		<span class="cd_admin_form_label"><?php _e('New Sales Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_new_sales_number') ?>" name="<?php echo $current_location; ?>_new_sales_number" id="<?php echo $current_location; ?>_new_sales_number" />
		<span class="cd_admin_form_label"><?php _e('New Mobile Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_new_mobile_number') ?>" name="<?php echo $current_location; ?>_new_mobile_number" id="<?php echo $current_location; ?>_new_mobile_number" />
		<br />
		<span class="cd_admin_form_label"><?php _e('New Mobile Provider', 'car-demon'); ?></span>
		<?php
			$current_val = get_option($current_location.'_new_mobile_provider');
			echo select_cell_provider($current_location.'_new_mobile_provider', $current_val);
		?> <span class="small_text"><?php _e('- blank disables SMS for new.', 'car-demon'); ?></span>
		<br />
		<span class="cd_admin_form_label"><?php _e('New Sales Email', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_new_sales_email') ?>" name="<?php echo $current_location; ?>_new_sales_email" id="<?php echo $current_location; ?>_new_sales_email" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Used Sales Name', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_used_sales_name') ?>" name="<?php echo $current_location; ?>_used_sales_name" id="<?php echo $current_location; ?>_used_sales_name" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Used Sales Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_used_sales_number') ?>" name="<?php echo $current_location; ?>_used_sales_number" id="<?php echo $current_location; ?>_used_sales_number" />
		<br />		
		<span class="cd_admin_form_label"><?php _e('Used Mobile Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_used_mobile_number') ?>" name="<?php echo $current_location; ?>_used_mobile_number" id="<?php echo $current_location; ?>_used_mobile_number" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Used Mobile Provider', 'car-demon'); ?></span>
		<?php
			$current_val = get_option($current_location.'_used_mobile_provider');
			echo select_cell_provider($current_location.'_used_mobile_provider', $current_val);
		?> <span class="small_text"><?php _e('- blank disables SMS for used.', 'car-demon'); ?></span>
		<br />
		<span class="cd_admin_form_label"><?php _e('Used Sales Email', 'car-demon'); ?></span>
		<input type="text" value="<?php echo get_option($current_location.'_used_sales_email') ?>" name="<?php echo $current_location; ?>_used_sales_email" id="<?php echo $current_location; ?>_used_sales_email" />
		<br />
		<?php
			$default_description = get_option($current_location.'_default_description');
			if (strlen($default_description) < 2) {
				$default_description = get_default_description();
			}
		?>
		<span class="cd_admin_form_label"><?php _e('Default Description', 'car-demon'); ?></span>
		<textarea name="<?php echo $current_location; ?>_default_description" id="<?php echo $current_location; ?>_default_description" class="admin_default_description"><?php echo $default_description; ?></textarea>
		<br />
		<span class="cd_admin_form_label"><?php _e('Service Name', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_name')) ?>" name="<?php echo $current_location; ?>_service_name" id="<?php echo $current_location; ?>_service_name" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Service Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_number')) ?>" name="<?php echo $current_location; ?>_service_number" id="<?php echo $current_location; ?>_service_number" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Service Email', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_email')) ?>" name="<?php echo $current_location; ?>_service_email" id="<?php echo $current_location; ?>_service_email" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Parts Name', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_name')) ?>" name="<?php echo $current_location; ?>_parts_name" id="<?php echo $current_location; ?>_parts_name" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Parts Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_number')) ?>" name="<?php echo $current_location; ?>_parts_number" id="<?php echo $current_location; ?>_parts_number" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Parts Email', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_email')) ?>" name="<?php echo $current_location; ?>_parts_email" id="<?php echo $current_location; ?>_parts_email" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Finance Name', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_name')) ?>" name="<?php echo $current_location; ?>_finance_name" id="<?php echo $current_location; ?>_finance_name" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Finance Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_number')) ?>" name="<?php echo $current_location; ?>_finance_number" id="<?php echo $current_location; ?>_finance_number" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Finance Email', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_email')) ?>" name="<?php echo $current_location; ?>_finance_email" id="<?php echo $current_location; ?>_finance_email" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Link to Finance Form', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_url')) ?>" name="<?php echo $current_location; ?>_finance_url" id="<?php echo $current_location; ?>_finance_url" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Pop Up Finance Form', 'car-demon'); ?></span>
		<select name="<?php echo $current_location; ?>_finance_popup" id="<?php echo $current_location; ?>_finance_popup">
			<option value="<?php echo strip_tags(get_option($current_location.'_finance_popup')) ?>"><?php echo get_option($current_location.'_finance_popup') ?></option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>
		&nbsp;Width: <input name="<?php echo $current_location; ?>_finance_width" id="<?php echo $current_location; ?>_finance_width" type="text" class="admin_finance_size" value="<?php echo get_option($current_location.'_finance_width') ?>" />&nbsp;Height: <input name="<?php echo $current_location; ?>_finance_height" id="<?php echo $current_location; ?>_finance_height" type="text" class="admin_finance_size" value="<?php echo get_option($current_location.'_finance_height') ?>" /> (800px X 600px optimal)
		<br />
		<?php
			$finance_disclaimer =  wp_kses_post(get_option($current_location.'_finance_disclaimer'));
			if (strlen($finance_disclaimer) < 2) {
				$finance_disclaimer = get_default_finance_disclaimer();
			}
			$finance_description =  wp_kses_post(get_option($current_location.'_finance_description'));
			if (strlen($finance_description) < 2) {
				$finance_description = get_default_finance_description();
			}
		?>
		<span class="cd_admin_form_label"><?php _e('*Finance Disclaimer', 'car-demon'); ?></span>
		<textarea name="<?php echo $current_location; ?>_finance_disclaimer" id="<?php echo $current_location; ?>_finance_disclaimer" class="admin_default_description"><?php echo $finance_disclaimer; ?></textarea>
		<br />
		<span class="cd_admin_form_label"><?php _e('*Finance Description', 'car-demon'); ?></span>
		<textarea name="<?php echo $current_location; ?>_finance_description" id="<?php echo $current_location; ?>_finance_description" class="admin_default_description"><?php echo $finance_description; ?></textarea>
		<br />
		<span class="cd_admin_form_label"><?php _e('Trade Name', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_name')) ?>" name="<?php echo $current_location; ?>_trade_name" id="<?php echo $current_location; ?>_trade_name" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Trade Number', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_number')) ?>" name="<?php echo $current_location; ?>_trade_number" id="<?php echo $current_location; ?>_trade_number" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Trade Email', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_email')) ?>" name="<?php echo $current_location; ?>_trade_email" id="<?php echo $current_location; ?>_trade_email" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Link to Trade Form', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_url')) ?>" name="<?php echo $current_location; ?>_trade_url" id="<?php echo $current_location; ?>_trade_url" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Show Prices on New', 'car-demon'); ?></span>
		<select name="<?php echo $current_location; ?>_show_new_prices" id="<?php echo $current_location; ?>_show_new_prices">
			<option value="<?php echo strip_tags(get_option($current_location.'_show_new_prices')) ?>"><?php echo strip_tags(get_option($current_location.'_show_new_prices')) ?></option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>&nbsp;If No use: 
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_no_new_price')) ?>" name="<?php echo $current_location; ?>_no_new_price" id="<?php echo $current_location; ?>_no_new_price" class="admin_no_price" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Show Prices on Used', 'car-demon'); ?></span>
		<select name="<?php echo $current_location; ?>_show_used_prices" id="<?php echo $current_location; ?>_show_used_prices">
			<option value="<?php echo strip_tags(get_option($current_location.'_show_used_prices')) ?>"><?php echo strip_tags(get_option($current_location.'_show_used_prices')) ?></option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>&nbsp;If No use: 
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_no_used_price')) ?>" name="<?php echo $current_location; ?>_no_used_price" id="<?php echo $current_location; ?>_no_used_price" class="admin_no_price" />
		<br />
		<span class="cd_admin_form_label"><?php _e('New Large Photo Url', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_new_large_photo_url')) ?>" name="<?php echo $current_location; ?>_new_large_photo_url" id="<?php echo $current_location; ?>_new_large_photo_url" />
		<br />
		<span class="cd_admin_form_label"><?php _e('New Small Photo Url', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_new_small_photo_url')) ?>" name="<?php echo $current_location; ?>_new_small_photo_url" id="<?php echo $current_location; ?>_new_small_photo_url" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Used Large Photo Url', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_used_large_photo_url')) ?>" name="<?php echo $current_location; ?>_used_large_photo_url" id="<?php echo $current_location; ?>_used_large_photo_url" />
		<br />
		<span class="cd_admin_form_label"><?php _e('Used Small Photo Url', 'car-demon'); ?></span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_used_small_photo_url')) ?>" name="<?php echo $current_location; ?>_used_small_photo_url" id="<?php echo $current_location; ?>_used_small_photo_url" />
		<?php
			$car_demon_settings_hook = apply_filters('car_demon_admin_hook', $holder, $current_location);
		?>
		<br /><span class="admin_disclaimer_notice"><?php _e('* The Default disclaimer and description are provided as examples ONLY and may or may not be legally suitable for your state. Please have a lawyer review your disclaimer and description before using.', 'car-demon'); ?></span>
		<br />
		<input type="submit" name="sbtSendIt" value="<?php _e('Update Options', 'car-demon'); ?>" class="admin_update_options_btn" />
		</form>
		<?php
		$x = $x + 1;
		echo '<br /><a href="http://www.cardemons.com" target="demon_win"><img title="Certified Support" src="'.CAR_DEMON_PATH.'images/cd-certified-support.png" /></a>';
		echo '<br />'.__('For support please visit', 'car-demon').' <a href="http://www.cardemons.com" target="demon_win">www.CarDemons.com</a><br />';
	}
}
function update_car_location_options() {
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'hide_empty'		 => 0,
		'taxonomy'           => 'vehicle_location'
		);
	$location_list = '';
	$location_list_array = '';
	$holder = '';
	$locations = get_categories( $args );
	foreach ($locations as $location) {
		$location_list .= ','.$location->slug;
	}
	$location_list = 'default'.$location_list;
	$location_list_array = explode(',',$location_list);
	$x = 0;
	foreach ($location_list_array as $current_location) {
		if ($_POST[$current_location.'_new_mobile_number']) { update_option($current_location.'_new_mobile_number', wp_filter_nohtml_kses($_POST[$current_location.'_new_mobile_number'])); }
		if ($_POST[$current_location.'_new_mobile_provider']) { update_option($current_location.'_new_mobile_provider', wp_filter_nohtml_kses($_POST[$current_location.'_new_mobile_provider'])); }
		if ($_POST[$current_location.'_used_mobile_number']) { update_option($current_location.'_used_mobile_number', wp_filter_nohtml_kses($_POST[$current_location.'_used_mobile_number'])); }
		if ($_POST[$current_location.'_used_mobile_provider']) { update_option($current_location.'_used_mobile_provider', wp_filter_nohtml_kses($_POST[$current_location.'_used_mobile_provider'])); }
		if ($_POST[$current_location.'_facebook_page']) { update_option($current_location.'_facebook_page', wp_filter_nohtml_kses($_POST[$current_location.'_facebook_page'])); }
		if ($_POST[$current_location.'_new_sales_name']) { update_option($current_location.'_new_sales_name', wp_filter_nohtml_kses($_POST[$current_location.'_new_sales_name'])); }
		if ($_POST[$current_location.'_new_sales_number']) { update_option($current_location.'_new_sales_number', wp_filter_nohtml_kses($_POST[$current_location.'_new_sales_number'])); }
		if ($_POST[$current_location.'_new_sales_email']) { update_option($current_location.'_new_sales_email', wp_filter_nohtml_kses($_POST[$current_location.'_new_sales_email'])); }
		if ($_POST[$current_location.'_used_sales_name']) { update_option($current_location.'_used_sales_name', wp_filter_nohtml_kses($_POST[$current_location.'_used_sales_name'])); }
		if ($_POST[$current_location.'_used_sales_number']) { update_option($current_location.'_used_sales_number', wp_filter_nohtml_kses($_POST[$current_location.'_used_sales_number'])); }
		if ($_POST[$current_location.'_used_sales_email']) { update_option($current_location.'_used_sales_email', wp_filter_nohtml_kses($_POST[$current_location.'_used_sales_email'])); }
		if ($_POST[$current_location.'_default_description']) { update_option($current_location.'_default_description', wp_filter_nohtml_kses($_POST[$current_location.'_default_description'])); }
		if ($_POST[$current_location.'_service_name']) { update_option($current_location.'_service_name', wp_filter_nohtml_kses($_POST[$current_location.'_service_name'])); }
		if ($_POST[$current_location.'_service_number']) { update_option($current_location.'_service_number', wp_filter_nohtml_kses($_POST[$current_location.'_service_number'])); }
		if ($_POST[$current_location.'_service_email']) { update_option($current_location.'_service_email', wp_filter_nohtml_kses($_POST[$current_location.'_service_email'])); }
		if ($_POST[$current_location.'_parts_name']) { update_option($current_location.'_parts_name', wp_filter_nohtml_kses($_POST[$current_location.'_parts_name'])); }
		if ($_POST[$current_location.'_parts_number']) { update_option($current_location.'_parts_number', wp_filter_nohtml_kses($_POST[$current_location.'_parts_number'])); }
		if ($_POST[$current_location.'_parts_email']) { update_option($current_location.'_parts_email', wp_filter_nohtml_kses($_POST[$current_location.'_parts_email'])); }
		if ($_POST[$current_location.'_finance_name']) { update_option($current_location.'_finance_name', wp_filter_nohtml_kses($_POST[$current_location.'_finance_name'])); }
		if ($_POST[$current_location.'_finance_number']) { update_option($current_location.'_finance_number', wp_filter_nohtml_kses($_POST[$current_location.'_finance_number'])); }
		if ($_POST[$current_location.'_finance_email']) { update_option($current_location.'_finance_email', wp_filter_nohtml_kses($_POST[$current_location.'_finance_email'])); }
		if ($_POST[$current_location.'_finance_url']) { update_option($current_location.'_finance_url', wp_filter_nohtml_kses($_POST[$current_location.'_finance_url'])); }
		if ($_POST[$current_location.'_finance_popup']) { update_option($current_location.'_finance_popup', wp_filter_nohtml_kses($_POST[$current_location.'_finance_popup'])); }
		if ($_POST[$current_location.'_finance_width']) { update_option($current_location.'_finance_width', wp_filter_nohtml_kses($_POST[$current_location.'_finance_width'])); }
		if ($_POST[$current_location.'_finance_height']) { update_option($current_location.'_finance_height', wp_filter_nohtml_kses($_POST[$current_location.'_finance_height'])); }
		if ($_POST[$current_location.'_finance_disclaimer']) { 
			$finance_disclaimer = $_POST[$current_location.'_finance_disclaimer'];
			$finance_disclaimer = str_replace("\'", "'", $finance_disclaimer);
			$finance_disclaimer = str_replace('\"', '"', $finance_disclaimer);
			$finance_disclaimer = str_replace('\\', '', $finance_disclaimer);
			update_option($current_location.'_finance_disclaimer', $finance_disclaimer); 
		}
		if ($_POST[$current_location.'_finance_description']) {
			$finance_description = $_POST[$current_location.'_finance_description'];
			$finance_description = str_replace("\'", "'", $finance_description);
			$finance_description = str_replace('\"', '"', $finance_description);
			$finance_description = str_replace('\\', '', $finance_description);			
			update_option($current_location.'_finance_description', $finance_description);
		}
		if ($_POST[$current_location.'_trade_name']) { update_option($current_location.'_trade_name', wp_filter_nohtml_kses($_POST[$current_location.'_trade_name'])); }
		if ($_POST[$current_location.'_trade_number']) { update_option($current_location.'_trade_number', wp_filter_nohtml_kses($_POST[$current_location.'_trade_number'])); }
		if ($_POST[$current_location.'_trade_email']) { update_option($current_location.'_trade_email', wp_filter_nohtml_kses($_POST[$current_location.'_trade_email'])); }
		if ($_POST[$current_location.'_trade_url']) { update_option($current_location.'_trade_url', wp_filter_nohtml_kses($_POST[$current_location.'_trade_url'])); }
		if ($_POST[$current_location.'_show_new_prices']) { update_option($current_location.'_show_new_prices', wp_filter_nohtml_kses($_POST[$current_location.'_show_new_prices'])); }
		if ($_POST[$current_location.'_show_used_prices']) { update_option($current_location.'_show_used_prices', wp_filter_nohtml_kses($_POST[$current_location.'_show_used_prices'])); }
		if ($_POST[$current_location.'_new_large_photo_url']) { update_option($current_location.'_new_large_photo_url', wp_filter_nohtml_kses($_POST[$current_location.'_new_large_photo_url'])); }
		if ($_POST[$current_location.'_new_small_photo_url']) { update_option($current_location.'_new_small_photo_url', wp_filter_nohtml_kses($_POST[$current_location.'_new_small_photo_url'])); }
		if ($_POST[$current_location.'_used_large_photo_url']) { update_option($current_location.'_used_large_photo_url', wp_filter_nohtml_kses($_POST[$current_location.'_used_large_photo_url'])); }
		if ($_POST[$current_location.'_used_small_photo_url']) { update_option($current_location.'_used_small_photo_url', wp_filter_nohtml_kses($_POST[$current_location.'_used_small_photo_url'])); }
		if ($_POST[$current_location.'_no_new_price']) { update_option($current_location.'_no_new_price', wp_filter_nohtml_kses($_POST[$current_location.'_no_new_price'])); }
		if ($_POST[$current_location.'_no_used_price']) { update_option($current_location.'_no_used_price', wp_filter_nohtml_kses($_POST[$current_location.'_no_used_price'])); }
		$car_demon_settings_hook = apply_filters('car_demon_admin_update_hook', $holder, $current_location);
	}
}
function car_demon_options() {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('admin/','',$car_demon_pluginpath);
	$default = array();
	$default['currency_symbol'] = '$';
	$default['currency_symbol_after'] = '';
	$default['vinquery_id'] = '';
	$default['vinquery_type'] = '0';
	$default['use_about'] = 'Yes';
	$default['adfxml'] = 'No';
	$default['use_compare'] = 'Yes';
	$default['dynamic_load'] = 'No';
	$default['secure_finance'] = 'Yes';
	$default['use_theme_files'] = 'No';
	$default['mobile_chat_code'] = '';
	$default['mobile_theme'] = 'No';
	$default['mobile_logo'] = '';
	$default['mobile_header'] = 'Yes';
	$default['validate_phone'] = 'Yes';
	$default['dynamic_ribbons'] = 'No';
	$default['before_listings'] = '';
	$default['use_post_title'] = 'No';
	$default['show_sold'] = 'No';
	$default['cc_admin'] = 'Yes';
	$default['do_sort'] = 'Yes';
	$default['drop_down_sort'] = 'No';
	$default['sort_price'] = 'Yes';
	$default['sort_miles'] = 'Yes';
	$default['hide_tabs'] = 'No';
	$default['popup_images'] = 'No';
	$default['custom_options'] = '';
	$default['use_form_css'] = 'Yes';
	$default['use_vehicle_css'] = 'Yes';
	$default['title_trim'] = '49';
	$default['cars_per_page'] = '9';
	$default['cd_cdrf_style'] = '';
	$default['cd_cdrf_page_style'] = '';
	$default['show_custom_specs'] = 'Yes';
	//= Sidebars
	$default['cd_page_id'] = '';
	$default['cd_page_css'] = '';
	$default['cd_content_id'] = '';
	$default['sidebar_id'] = '';
	$default['vehicle_sidebar_class'] = '';
	$default['left_list_sidebar'] = '';
	$default['right_list_sidebar'] = '';
	$default['vehicle_container'] = '';
	$default['left_vehicle_sidebar'] = '';
	$default['right_vehicle_sidebar'] = '';
	//= Auto Load Inventory Options
	$default['dl_container'] = '.grid-box.width100';
	$default['dl_items'] = '.item';
	$default['dl_pagination'] = '.pagination';
	$default['dl_next'] = '.next';
	//= Content Replacement
	$default['cd_cdrf_style'] = 'content-replacement';
	$default['cd_cdrf_page_style'] = 'content-replacement';
	$default['cd_slug'] = get_option('car-demon-slug','cars-for-sale');

	$car_demon_options = array();
	$car_demon_options = get_option( 'car_demon_options', $default );
	if (empty($car_demon_options['currency_symbol'])) {$car_demon_options['currency_symbol'] = $default['currency_symbol'];}
	if (empty($car_demon_options['currency_symbol_after'])) {$car_demon_options['currency_symbol_after'] = $default['currency_symbol_after'];} else { $car_demon_options['currency_symbol'] = ""; }
	if (empty($car_demon_options['vinquery_id'])) {$car_demon_options['vinquery_id'] = $default['vinquery_id'];}
	if (empty($car_demon_options['vinquery_type'])) {$car_demon_options['vinquery_type'] = $default['vinquery_type'];}
	if (empty($car_demon_options['use_about'])) {$car_demon_options['use_about'] = $default['use_about'];}
	if (empty($car_demon_options['adfxml'])) {$car_demon_options['adfxml'] = $default['adfxml'];}
	if (empty($car_demon_options['use_compare'])) {$car_demon_options['use_compare'] = $default['use_compare'];}
	if (empty($car_demon_options['dynamic_load'])) {$car_demon_options['dynamic_load'] = $default['dynamic_load'];}
	if (empty($car_demon_options['secure_finance'])) {$car_demon_options['secure_finance'] = $default['secure_finance'];}
	if (empty($car_demon_options['use_theme_files'])) {$car_demon_options['use_theme_files'] = $default['use_theme_files'];}
	if (empty($car_demon_options['mobile_chat_code'])) {$car_demon_options['mobile_chat_code'] = $default['mobile_chat_code'];}
	if (empty($car_demon_options['mobile_theme'])) {$car_demon_options['mobile_theme'] = $default['mobile_theme'];}
	if (empty($car_demon_options['mobile_logo'])) {$car_demon_options['mobile_logo'] = $default['mobile_logo'];}
	if (empty($car_demon_options['mobile_header'])) {$car_demon_options['mobile_header'] = $default['mobile_header'];}
	if (empty($car_demon_options['validate_phone'])) {$car_demon_options['validate_phone'] = $default['validate_phone'];}
	if (empty($car_demon_options['dynamic_ribbons'])) {$car_demon_options['dynamic_ribbons'] = $default['dynamic_ribbons'];}
	if (empty($car_demon_options['before_listings'])) {$car_demon_options['before_listings'] = $default['before_listings'];}
	if (empty($car_demon_options['use_post_title'])) {$car_demon_options['use_post_title'] = $default['use_post_title'];}
	if (empty($car_demon_options['show_sold'])) {$car_demon_options['show_sold'] = $default['show_sold'];}
	if (empty($car_demon_options['cc_admin'])) {$car_demon_options['cc_admin'] = $default['cc_admin'];}
	if (empty($car_demon_options['drop_down_sort'])) {$car_demon_options['drop_down_sort'] = $default['drop_down_sort'];}
	if (empty($car_demon_options['do_sort'])) {$car_demon_options['do_sort'] = $default['do_sort'];}
	if (empty($car_demon_options['sort_price'])) {$car_demon_options['sort_price'] = $default['sort_price'];}
	if (empty($car_demon_options['sort_miles'])) {$car_demon_options['sort_miles'] = $default['sort_miles'];}
	if (empty($car_demon_options['hide_tabs'])) {$car_demon_options['hide_tabs'] = $default['hide_tabs'];}
	if (empty($car_demon_options['popup_images'])) {$car_demon_options['popup_images'] = $default['popup_images'];}
	if (empty($car_demon_options['custom_options'])) {$car_demon_options['custom_options'] = $default['custom_options'];}
	if (empty($car_demon_options['use_form_css'])) {$car_demon_options['use_form_css'] = $default['use_form_css'];}
	if (empty($car_demon_options['use_vehicle_css'])) {$car_demon_options['use_vehicle_css'] = $default['use_vehicle_css'];}
	if (empty($car_demon_options['title_trim'])) {$car_demon_options['title_trim'] = $default['title_trim'];}
	if (empty($car_demon_options['cars_per_page'])) {$car_demon_options['cars_per_page'] = $default['cars_per_page'];}
	if (empty($car_demon_options['show_custom_specs'])) {$car_demon_options['show_custom_specs'] = $default['show_custom_specs'];}
	if (empty($car_demon_options['cd_slug'])) {$car_demon_options['cd_slug'] = $default['cd_slug'];}
	//= Sidebars
	if (empty($car_demon_options['cd_page_id'])) {$car_demon_options['cd_page_id'] = $default['cd_page_id'];}
	if (empty($car_demon_options['cd_page_css'])) {$car_demon_options['cd_page_css'] = $default['cd_page_css'];}
	if (empty($car_demon_options['cd_content_id'])) {$car_demon_options['cd_content_id'] = $default['cd_content_id'];}
	if (empty($car_demon_options['vehicle_sidebar_class'])) {$car_demon_options['vehicle_sidebar_class'] = $default['vehicle_sidebar_class'];}
	if (empty($car_demon_options['left_list_sidebar'])) {$car_demon_options['left_list_sidebar'] = $default['left_list_sidebar'];}
	if (empty($car_demon_options['right_list_sidebar'])) {$car_demon_options['right_list_sidebar'] = $default['right_list_sidebar'];}
	if (empty($car_demon_options['vehicle_container'])) {$car_demon_options['vehicle_container'] = $default['vehicle_container'];}
	if (empty($car_demon_options['left_vehicle_sidebar'])) {$car_demon_options['left_vehicle_sidebar'] = $default['left_vehicle_sidebar'];}
	if (empty($car_demon_options['right_vehicle_sidebar'])) {$car_demon_options['right_vehicle_sidebar'] = $default['right_vehicle_sidebar'];}
	if (empty($car_demon_options['sidebar_id'])) {$car_demon_options['sidebar_id'] = $default['sidebar_id'];}
	//= Content Replacement
	if (empty($car_demon_options['cd_cdrf_style'])) {$car_demon_options['cd_cdrf_style'] = $default['cd_cdrf_style'];}
	if (empty($car_demon_options['cd_cdrf_page_style'])) {$car_demon_options['cd_cdrf_page_style'] = $default['cd_cdrf_page_style'];}
	//= Auto Load Inventory Options
	if (empty($car_demon_options['dl_container'])) {$car_demon_options['dl_container'] = $default['dl_container'];}
	if (empty($car_demon_options['dl_items'])) {$car_demon_options['dl_items'] = $default['dl_items'];}
	if (empty($car_demon_options['dl_pagination'])) {$car_demon_options['dl_pagination'] = $default['dl_pagination'];}
	if (empty($car_demon_options['dl_next'])) {$car_demon_options['dl_next'] = $default['dl_next'];}

	return $car_demon_options;
}
function car_demon_settings_options_do_page() {
	if (isset($_GET['edit_vehicle_options'])) {
		car_demon_settings_edit_vehicle_options();
	} else {
		car_demon_settings_form();
	}
}
function car_demon_settings_form() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div><h1>'.__('Car Demon Settings', 'car-demon').'</h1>';
	if (isset($_POST['reset_car_demon'])) {
		reset_car_demon();
	} else {
		if(isset($_POST['update_car_demon']) == 1) {
			update_car_demon_settings();
		}
	}
	$car_demon_options = car_demon_options();
	echo __('For support please visit', 'car-demon').' <a href="http://www.cardemons.com" target="demon_win">www.CarDemons.com</a><br />';

	echo '<hr />';
	echo '<form action="" id="cd_settings" name="cd_settings" method="get">';
		echo '<input type="hidden" name="post_type" value="cars_for_sale" />';
		echo '<input type="hidden" name="page" value="car_demon_settings_options" />';
		echo '<input type="hidden" name="edit_vehicle_options" value="1" />';
		//= js submit button is down below in tab options
		//= echo '<input type="submit" value="Edit Vehicle Option Tabs" />';
	echo '</form>';
	echo '<form action="" method="post">';
		//= Save Start
		echo '<fieldset class="cd_admin_group cd_save_settings">';
			echo '<legend>';
				echo __('Save settings','car-demon');
			echo '</legend>';
			echo '<blockquote>';
				echo __('After you make your changes click the "Update Car Demon" button.','car-demon').'<br />';
			echo '</blockquote>';			
			echo '<p><input type="submit" value="'.__('Update Car Demon', 'car-demon').'" />';
			echo '<input type="submit" name="reset_car_demon" value="'.__('Reset to Default', 'car-demon').'" /></p>';
		echo '</fieldset>';
		//= Save Stop

		echo '<input type="hidden" name="update_car_demon" value="1" />';
		//= Currency Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('Currency Options','car-demon');
			echo '</legend>';
			echo '<br />*'.__('Currency Symbol', 'car-demon').':<br />';
			echo '<input type="text" name="currency_symbol" value="'.$car_demon_options['currency_symbol'].'" /><br />';
			echo '<br />*'.__('Currency Symbol After Price', 'car-demon').':<br />';
			echo '<input type="text" name="currency_symbol_after" value="'.$car_demon_options['currency_symbol_after'].'" /><br />';
		echo '</fieldset>';
		//= Currency Stop

		//= Slug Start
		echo '<fieldset class="cd_admin_group cd_slug_option">';
			echo '<legend>';
				echo __('URL Slug Options','car-demon');
			echo '</legend>';
			echo '<br />*'.__('URL path for inventory (ie cars-for-sale creates http://yoursite.com/cars-for-sale)', 'car-demon').'<br />';
			echo '<br />*'.__('If you enter "inventory" it creates http://yoursite.com/inventory', 'car-demon').'<br />';
			echo '<input type="text" name="cd_slug" value="'.$car_demon_options['cd_slug'].'" /><br />';
			echo '<br />*'.__('You will need to refresh your permalinks after changing this setting.', 'car-demon').'<br />';
			echo '<br />*'.__('Only use lower case letters, no spaces but you may use - to seperate words.', 'car-demon').'<br />';
		echo '</fieldset>';
		//= Slug Stop

		//= VinQuery Start		
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('VinQuery Options','car-demon');
			echo '</legend>';
			echo '<br />*'.__('VinQuery.com Access Code', 'car-demon').':<br />';
			echo '<input type="text" name="vinquery_id" value="'.$car_demon_options['vinquery_id'].'" />';
			echo '*(optional)<br />';
			$vinquery_type_num = $car_demon_options['vinquery_type'];
			if (empty($vinquery_type_num)) {
				$vinquery_type_num = 0;
			}
			$select_basic = '';
			$select_standard = '';
			$select_extended = '';
			$select_lite = '';
			if ($vinquery_type_num == 0) {
				$select_basic = ' selected';
			} elseif ($vinquery_type_num == 1) {
				$select_standard = ' selected';
			} elseif ($vinquery_type_num == 2) {
				$select_extended = ' selected';
			} elseif ($vinquery_type_num == 3) {
				$select_lite = ' selected';
			}
			echo '<br />'.__('VinQuery.com Report Type', 'car-demon').':<br />';
			echo '<select name="vinquery_type">
					<option value="2"'.$select_extended.'>'.__('Extended', 'car-demon').'</option>
					<option value="1"'.$select_standard.'>'. __('Standard', 'car-demon') .'</option>
					<option value="0"'.$select_basic.'>'. __('Basic', 'car-demon') .'</option>
					<option value="3"'.$select_lite.'>'. __('Lite', 'car-demon') .'</option>
				</select><br />';
			echo '<br /><strong>';
			echo __('Did you know you can get 50 FREE Decodes from VinQuery?','car-demon');
			echo '<br />';
			echo '<a href="http://www.cardemonspro.com/vinquery-com/" target="vin_win">'.__('Sign up here!','car-demon').'</a></strong>';
		echo '</fieldset>';
		//= VinQuery Stop

		//= Style Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('Style Options','car-demon');
			echo '</legend>';
			//==============
			echo '<br />'.__('Use Form CSS?', 'car-demon').':<br />';
			echo '<select name="use_form_css">
					<option value="'.$car_demon_options['use_form_css'].'">'.$car_demon_options['use_form_css'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Use Vehicle CSS?', 'car-demon').':<br />';
			echo '<select name="use_vehicle_css">
					<option value="'.$car_demon_options['use_vehicle_css'].'">'.$car_demon_options['use_vehicle_css'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Use included theme files?', 'car-demon').':<br />';
			echo '<select name="use_theme_files">
					<option value="'.$car_demon_options['use_theme_files'].'">'.$car_demon_options['use_theme_files'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
				//= Sidebar and Page ID code
				if ($car_demon_options['use_theme_files'] == 'No') {
					$show_template_options = ' style="display:none;"';
				} else {
					$show_template_options = '';	
				}
				echo '<fieldset class="cd_admin_group"'.$show_template_options.'>';
					echo '<legend>';
						echo __('Advanced template options','car-demon');
					echo '</legend>';
					echo '<blockquote>';
						echo '<br />'.__('These options give you greater control over the included template files.', 'car-demon').'<br />';
						echo '<br />'.__('If you\'re not sure how to use these then please leave them set at their defaults.', 'car-demon').'<hr />';
						echo '<br />'.__('Custom Page ID', 'car-demon').':<br />';
							$cd_page_id = $car_demon_options['cd_page_id'];
							echo '<input type="text" id="cd_page_id" name="cd_page_id" value="'.$cd_page_id.'" />';
							echo '<br />'.__('If you enter an ID then the vehicle content and sidebar will be enclosed in a div with that ID.', 'car-demon').'<br />';
						echo '<br />'.__('Custom Page CSS class', 'car-demon').':<br />';
							$cd_page_css = $car_demon_options['cd_page_css'];
							echo '<input type="text" id="cd_page_css" name="cd_page_css" value="'.$cd_page_css.'" />';
							echo '<br />'.__('If you enter a class name then the vehicle content and sidebar will be enclosed in a div with that class. You must set an ID above for this to work.', 'car-demon').'<br />';
						echo '<br /><br />'.__('Custom Sidebar ID', 'car-demon').':<br />';
							$sidebar_id = $car_demon_options['sidebar_id'];
							echo '<input type="text" id="sidebar_id" name="sidebar_id" value="'.$sidebar_id.'" />';
						echo '<br />'.__('Custom Sidebar CSS class', 'car-demon').':<br />';
							$vehicle_sidebar_class = $car_demon_options['vehicle_sidebar_class'];
							echo '<input type="text" id="vehicle_sidebar_class" name="vehicle_sidebar_class" value="'.$vehicle_sidebar_class.'" />';
						echo '<br /><br />'.__('Left Sidebar on List Pages', 'car-demon').':<br />';
							$left_list_sidebar = $car_demon_options['left_list_sidebar'];
							echo cd_sidebar_selectbox('left_list_sidebar', $left_list_sidebar);
						echo '<br />'.__('Right Sidebar on List Pages', 'car-demon').':<br />';
							$right_list_sidebar = $car_demon_options['right_list_sidebar'];
							echo cd_sidebar_selectbox('right_list_sidebar', $right_list_sidebar);
						echo '<br /><br />'.__('Left Sidebar on Vehicle Pages', 'car-demon').':<br />';
							$left_vehicle_sidebar = $car_demon_options['left_vehicle_sidebar'];
							echo cd_sidebar_selectbox('left_vehicle_sidebar', $left_vehicle_sidebar);
						echo '<br />'.__('Right Sidebar on Vehicle Pages', 'car-demon').':<br />';
							$right_vehicle_sidebar = $car_demon_options['right_vehicle_sidebar'];
							echo cd_sidebar_selectbox('right_vehicle_sidebar', $right_vehicle_sidebar);
						echo '<br /><br />'.__('Custom Vehicle Content ID', 'car-demon').':<br />';
							$cd_content_id = $car_demon_options['cd_content_id'];
							echo '<input type="text" id="cd_content_id" name="cd_content_id" value="'.$cd_content_id.'" />';
						echo '<br />'.__('Vehicle Content CSS container class', 'car-demon').':<br />';
							$vehicle_container = $car_demon_options['vehicle_container'];
							echo '<input type="text" id="vehicle_container" name="vehicle_container" value="'.$vehicle_container.'" />';
					echo '</blockquote>';
				echo '</fieldset>';
				//= End Sidebar Code
			echo '<br />'.__('Use Title field for Vehicle Titles? - If No then title will be "Year Make Model"', 'car-demon').':<br />';
			echo '<select name="use_post_title">
					<option value="'.$car_demon_options['use_post_title'].'">'.$car_demon_options['use_post_title'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Trim title', 'car-demon').':<br />';
			echo '<input type="text" name="title_trim" value="'.$car_demon_options['title_trim'].'" /><br />';
			echo '<br />'.__('Use Dynamic Ribbons?', 'car-demon').':<br />';
			echo '<select name="dynamic_ribbons">
					<option value="'.$car_demon_options['dynamic_ribbons'].'">'.$car_demon_options['dynamic_ribbons'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Use Popup Images', 'car-demon').':<br />';
			echo '<select name="popup_images">
					<option value="'.$car_demon_options['popup_images'].'">'.$car_demon_options['popup_images'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
		echo '</fieldset>';
		//= Style Option Stop

		//= Tab Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('Tab Options','car-demon');
			echo '</legend>';
			echo '<br />'.__('Hide all Vehicle Option Tabs?', 'car-demon').':<br />';
			echo '<select name="hide_tabs">
					<option value="'.$car_demon_options['hide_tabs'].'">'.$car_demon_options['hide_tabs'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Use About Tab on Vehicle Pages', 'car-demon').':<br />';
			echo '<select name="use_about">
					<option value="'.$car_demon_options['use_about'].'">'.$car_demon_options['use_about'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			//= Next segment needs to be deprecated
			//= Change next value to Yes to enable this feature
			if ($car_demon_options['hide_tabs'] == 'Hide') {
				echo '<br />'.__('Custom Vehicle Options', 'car-demon').':<br />';
				echo '<textarea name="custom_options" cols="60" rows="6">'.$car_demon_options['custom_options'].' </textarea><br />';
				echo __('Separate options with a comma. The options you enter here will appear on the vehicle edit page under "Custom Options"','car-demon');
			}
			echo '<div class="edit_option_tabs_div"><input type="button" value="Edit Vehicle Option Tabs" onclick="document.cd_settings.submit();" class="edit_option_tabs" /></div>';
		echo '</fieldset>';
		//= Tab Option Stop

		//= Legacy Option Start
		//= Allow users to return to legacy show_custom_specs option
		if (isset($_GET['advanced'])) {
			echo '<fieldset class="cd_admin_group">';
				echo '<legend>';
					echo __('Legacy Options','car-demon');
				echo '</legend>';
				echo '<br />'.__('Use Custom Specs?', 'car-demon').':<br />';
				echo '<br />'.__('This option was added to allow support for users that can not migrate to the new custom specs code. If you are having problems viewing your vehicle specs or if information does not appear to be updating then try setting this to No.', 'car-demon').'<br />';
				echo '<select name="show_custom_specs">
						<option value="'.$car_demon_options['show_custom_specs'].'">'.$car_demon_options['show_custom_specs'].'</option>
						<option value="Yes">'.__('Yes', 'car-demon').'</option>
						<option value="No">'.__('No', 'car-demon').'</option>
					</select><br />';
			echo '</fieldset>';
		}

		//= List Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('List Options','car-demon');
			echo '</legend>';
			//==============
			echo '<br />'.__('Max number of vehicles in search results and archive pages:', 'car-demon').'<br />';
			echo '<input type="text" name="cars_per_page" id="cars_per_page" value='.$car_demon_options['cars_per_page'].' /><br />';
			if ($car_demon_options['cd_cdrf_style'] != 'content-replacement') {
				echo '<br />'.__('Display before listings:', 'car-demon').'<br />';
				echo '<textarea name="before_listings" rows="5" cols="60">'.$car_demon_options['before_listings'].'</textarea><br />';
			}
			echo '<br />'.__('Load Next Inventory Page on Scroll', 'car-demon').':<br />';
			echo '<select name="dynamic_load">
					<option value="'.$car_demon_options['dynamic_load'].'">'.$car_demon_options['dynamic_load'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
				echo '<fieldset class="cd_admin_group">';
					echo '<legend>';
						echo __('Auto Load Inventory Options','car-demon');
					echo '</legend>';
					echo '<br />'.__('You must match the object settings of your theme for dynamic load to work.', 'car-demon').'<br />';
					echo '<br />'.__('You can use an ID or a Class to id an opject.', 'car-demon').'<br /><br />';
					echo '<br />'.__('Class or ID of content container - default .grid-box.width100', 'car-demon').'<br />';
					echo '<input type="text" name="dl_container" id="dl_container" value="'.$car_demon_options['dl_container'].'" /><br />';
					echo '<br />'.__('Class or ID of item containers - default .item', 'car-demon').'<br />';
					echo '<input type="text" name="dl_items" id="dl_items" value="'.$car_demon_options['dl_items'].'" /><br />';
					echo '<br />'.__('Class or ID of pagination container - default .pagination', 'car-demon').'<br />';
					echo '<input type="text" name="dl_pagination" id="dl_pagination" value="'.$car_demon_options['dl_pagination'].'" /><br />';
					echo '<br />'.__('Class or ID of pagination next container container - default .next-post a', 'car-demon').'<br />';
					echo '<input type="text" name="dl_next" id="dl_next" value="'.$car_demon_options['dl_next'].'" /><br />';
				echo '</fieldset>';
			echo '<br />'.__('Show sold vehicles in search results?', 'car-demon').':<br />';
			echo '<select name="show_sold">
					<option value="'.$car_demon_options['show_sold'].'">'.$car_demon_options['show_sold'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Use Compare Vehicle Option', 'car-demon').':<br />';
			echo '<select name="use_compare">
					<option value="'.$car_demon_options['use_compare'].'">'.$car_demon_options['use_compare'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select> '.__('You must use the vehicle compare widget to display the list to your visitors .','car-demon').'<br />';
			echo '<hr />'.__('Show sorting options on vehicle listing pages?', 'car-demon').':<br />';
			echo '<select name="do_sort">
					<option value="'.$car_demon_options['do_sort'].'">'.$car_demon_options['do_sort'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<blockquote>';
				echo '<hr />'.__('Use Drop down sorting?', 'car-demon').':<br />';
				echo '<select name="drop_down_sort">
						<option value="'.$car_demon_options['drop_down_sort'].'">'.$car_demon_options['drop_down_sort'].'</option>
						<option value="Yes">'.__('Yes', 'car-demon').'</option>
						<option value="No">'.__('No', 'car-demon').'</option>
					</select><br />';
				echo '<br />'.__('Sort By Price? - Sorting options must be set to yes to use this feature.', 'car-demon').':<br />';
				echo '<select name="sort_price">
						<option value="'.$car_demon_options['sort_price'].'">'.$car_demon_options['sort_price'].'</option>
						<option value="Yes">'.__('Yes', 'car-demon').'</option>
						<option value="No">'.__('No', 'car-demon').'</option>
					</select><br />';
				echo '<br />'.__('Sort By Mileage? - Sorting options must be set to yes to use this feature.', 'car-demon').':<br />';
				echo '<select name="sort_miles">
						<option value="'.$car_demon_options['sort_miles'].'">'.$car_demon_options['sort_miles'].'</option>
						<option value="Yes">'.__('Yes', 'car-demon').'</option>
						<option value="No">'.__('No', 'car-demon').'</option>
					</select><br />';
			echo '</blockquote>';
		echo '</fieldset>';
		//= List Option Stop

		//= Form Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('Form Options','car-demon');
			echo '</legend>';
			//==============
			echo '<br />'.__('You also need to setup the information on the Contact Information page.', 'car-demon').'<hr />';
			echo '<br />'.__('Blind Carbon Copy Forms to Admin?', 'car-demon').':<br />';
			echo '<select name="cc_admin">
					<option value="'.$car_demon_options['cc_admin'].'">'.$car_demon_options['cc_admin'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Include ADFxml with Leads?', 'car-demon').':<br />';
			echo '<select name="adfxml">
					<option value="'.$car_demon_options['adfxml'].'">'.$car_demon_options['adfxml'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Validate Phone Numbers?', 'car-demon').':<br />';
			echo '<select name="validate_phone">
					<option value="'.$car_demon_options['validate_phone'].'">'.$car_demon_options['validate_phone'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			echo '<br />'.__('Disable Finance Form if it isn\'t loaded with SSL', 'car-demon').':<br />';
			echo '<select name="secure_finance">
				<option value="'.$car_demon_options['secure_finance'].'">'.$car_demon_options['secure_finance'].'</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
				</select><br />';
		echo '</fieldset>';
		//= Form Option Stop

		//= Mobile Option Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('Mobile Options','car-demon');
			echo '</legend>';
			echo '<br />'.__('Is Mobile Theme Installed?', 'car-demon').':<br />';
			echo '<select name="mobile_theme">
					<option value="'.$car_demon_options['mobile_theme'].'">'.$car_demon_options['mobile_theme'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select><br />';
			if ($car_demon_options['mobile_theme'] == 'Yes') {
				echo '<br />'.__('Use Default Mobile Header with Mobile Logo?', 'car-demon').':<br />';
				echo '<select name="mobile_header">
					<option value="'.$car_demon_options['mobile_header'].'">'.$car_demon_options['mobile_header'].'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
					</select><br />';
				echo '<br />'.__('Mobile Header Logo', 'car-demon').'<br />';
				echo '<table><tr valign="top">
					<td><label for="upload_mobile_logo">
					<input name="mobile_logo" id="mobile_logo" type="text" size="36" value="'.$car_demon_options['mobile_logo'].'" />
					<input id="upload_mobile_logo_button" type="button" value="'.__('Upload Logo', 'car-demon').'" />
					<br />'.__('Enter a URL or upload an image for the Mobile Logo. 169x58 px', 'car-demon').'
					</label></td>
					</tr></table>';
				echo '<br />'.__('Mobile Chat Code', 'car-demon').'<br />';
				echo '<textarea name="mobile_chat_code" rows="5" cols="60">'.$car_demon_options['mobile_chat_code'].'</textarea><br />';
			}
		echo '</fieldset>';
		//= Mobile Option Stop

		//= Hook for additional settings
		$holder = '';
		$location = '';
		$car_demon_settings_hook = apply_filters('car_demon_settings_hook', $holder, $location);

		//= Save Start
		echo '<fieldset class="cd_admin_group">';
			echo '<legend>';
				echo __('Save settings','car-demon');
			echo '</legend>';
			echo '<p><input type="submit" value="'.__('Update Car Demon', 'car-demon').'" />';
			echo '<input type="submit" name="reset_car_demon" value="'.__('Reset to Default', 'car-demon').'" /></p>';
		echo '</fieldset>';
		//= Save Stop
		
	echo '</form>';
	echo '<fieldset class="cd_admin_group">';
		echo '<legend>';
			echo __('Shortcodes','car-demon');
		echo '</legend>';
		echo '<h2>'.__('Car Demon Pages', 'car-demon').'</h2>';
		echo '<div class="admin_pages_div">';
			echo '<p>'.__('Car Demon comes with support for adding several custom forms and pages. You can quickly and easily add these pages by clicking the buttons below or you can create your own pages and simply paste the shortcode you want to use into the content of the page.', 'car-demon').'</p>';
		echo '</div>';
		if (isset($_POST['add_page'])) {
			$title = $_POST['title'];
			$type = $_POST['type'];
			$new_page_id = car_demon_add_page($title, $type);
			$link = get_permalink($new_page_id);
			echo '<a href="'.$link.'" target="_blank">New '.$title.' Page</a>';
		}
		echo '<div class="admin_add_pages_div">';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="contact" />';
				echo '<input type="hidden" name="title" value="Contact Us" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Contact Us Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="service_appointment" />';
				echo '<input type="hidden" name="title" value="Service Appointment" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Service Appointment Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="service_quote" />';
				echo '<input type="hidden" name="title" value="Service Quote" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Service Quote Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="parts" />';
				echo '<input type="hidden" name="title" value="Parts Quote" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Parts Quote Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="trade" />';
				echo '<input type="hidden" name="title" value="Trade In" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Trade In Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="finance" />';
				echo '<input type="hidden" name="title" value="Finance Application" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Finanace Application Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="qualify" />';
				echo '<input type="hidden" name="title" value="Pre-Qualify" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Pre-Qualify Page', 'car-demon').'" />';
			echo '</form>';
			echo '<form method="POST" action="">';
				echo '<input type="hidden" name="type" value="staff" />';
				echo '<input type="hidden" name="title" value="Staff Page" />';
				echo '<input type="submit" class="admin_add_pages_btn" name="add_page" value="'.__('Add Staff Page', 'car-demon').'" />';
			echo '</form>';
		echo '</div>';
		echo '<div class="admin_add_pages_shortcodes">';
			echo '<h3 class="admin_add_pages_shortcodes">'.__('Shortcodes', 'car-demon').'</h3>';
			echo '<blockquote>';
				echo '[contact_us]<br />';
				echo '[service_form]<br />';
				echo '[service_quote]<br />';
				echo '[part_request]<br />';
				echo '[trade]<br />';
				echo '[finance_form]<br />';
				echo '[qualify]<br />';
				echo '[staff_page]<br />';
			echo '</blockquote>';
		echo '</div>';
	echo '</fieldset>';
	//= Save Stop
	echo '<br /><a href="http://www.cardemons.com" target="demon_win"><img title="Certified Support" src="'.CAR_DEMON_PATH.'images/cd-certified-support.png" /></a>';
}
function update_car_demon_settings() {
	$new = array();
	$new = get_option( 'car_demon_options' );
	if (isset($_POST['cd_slug'])) {
		$new['cd_slug'] = $_POST['cd_slug'];
		update_option('car-demon-slug', $new['cd_slug']);
	}
	if (isset($_POST['currency_symbol'])) $new['currency_symbol'] = $_POST['currency_symbol'];
	if (isset($_POST['currency_symbol_after'])) $new['currency_symbol_after'] = $_POST['currency_symbol_after'];
	if (isset($_POST['vinquery_id'])) $new['vinquery_id'] = $_POST['vinquery_id'];
	if (isset($_POST['vinquery_type'])) $new['vinquery_type'] = $_POST['vinquery_type'];
	if (isset($_POST['use_about'])) $new['use_about'] = $_POST['use_about'];
	if (isset($_POST['adfxml'])) $new['adfxml'] = $_POST['adfxml'];
	if (isset($_POST['use_compare'])) $new['use_compare'] = $_POST['use_compare'];
	if (isset($_POST['secure_finance'])) $new['secure_finance'] = $_POST['secure_finance'];
	if (isset($_POST['use_theme_files'])) $new['use_theme_files'] = $_POST['use_theme_files'];
	if (isset($_POST['dynamic_load'])) $new['dynamic_load'] = $_POST['dynamic_load'];
	if (isset($_POST['dynamic_ribbons'])) $new['dynamic_ribbons'] = $_POST['dynamic_ribbons'];
	if (isset($_POST['mobile_chat_code'])) $mobile_chat_code = $_POST['mobile_chat_code'];
	$mobile_chat_code = '';
	$mobile_chat_code = str_replace("\'", "'", $mobile_chat_code);
	$mobile_chat_code = str_replace('\"', '"', $mobile_chat_code);
	$mobile_chat_code = str_replace('\\', '', $mobile_chat_code);	
	$new['mobile_chat_code'] = $mobile_chat_code;
	if (isset($_POST['custom_header_type'])) $new['custom_header_type'] = $_POST['custom_header_type'];
	if (isset($_POST['mobile_theme'])) {
		$new['mobile_theme'] = $_POST['mobile_theme'];
		if (isset($_POST['mobile_logo'])) {
			$new['mobile_logo'] = $_POST['mobile_logo'];
		}
		if (empty($new['mobile_logo'])) {
			$new['mobile_logo'] = CAR_DEMON_PATH.'theme-files/images/mobile_header.png';
		}
	}
	if (isset($_POST['mobile_header'])) $new['mobile_header'] = $_POST['mobile_header'];
	if (isset($_POST['validate_phone'])) $new['validate_phone'] = $_POST['validate_phone'];
	if (isset($_POST['before_listings'])) $new['before_listings'] = $_POST['before_listings'];
	if (isset($_POST['use_post_title'])) $new['use_post_title'] = $_POST['use_post_title'];
	if (isset($_POST['show_sold'])) $new['show_sold'] = $_POST['show_sold'];
	if (isset($_POST['cc_admin'])) $new['cc_admin'] = $_POST['cc_admin'];
	if (isset($_POST['drop_down_sort'])) $new['drop_down_sort'] = $_POST['drop_down_sort'];
	if (isset($_POST['do_sort'])) $new['do_sort'] = $_POST['do_sort'];
	if (isset($_POST['sort_price'])) $new['sort_price'] = $_POST['sort_price'];
	if (isset($_POST['sort_miles'])) $new['sort_miles'] = $_POST['sort_miles'];
	if (isset($_POST['hide_tabs'])) $new['hide_tabs'] = $_POST['hide_tabs'];
	if (isset($_POST['popup_images'])) $new['popup_images'] = $_POST['popup_images'];
	if (isset($_POST['custom_options'])) $new['custom_options'] = $_POST['custom_options'];
	if (isset($_POST['use_form_css'])) $new['use_form_css'] = $_POST['use_form_css'];
	if (isset($_POST['use_vehicle_css'])) $new['use_vehicle_css'] = $_POST['use_vehicle_css'];
	if (isset($_POST['title_trim'])) $new['title_trim'] = $_POST['title_trim'];
	if (isset($_POST['cars_per_page'])) $new['cars_per_page'] = $_POST['cars_per_page'];
	//= Legacy Specs option
	
	if (isset($_POST['show_custom_specs'])) $new['show_custom_specs'] = $_POST['show_custom_specs'];
	//=Sidebars
	if (isset($_POST['vehicle_sidebar_class'])) $new['vehicle_sidebar_class'] = $_POST['vehicle_sidebar_class'];
	if (isset($_POST['left_list_sidebar'])) $new['left_list_sidebar'] = $_POST['left_list_sidebar'];
	if (isset($_POST['right_list_sidebar'])) $new['right_list_sidebar'] = $_POST['right_list_sidebar'];
	if (isset($_POST['vehicle_container'])) $new['vehicle_container'] = $_POST['vehicle_container'];
	if (isset($_POST['left_vehicle_sidebar'])) $new['left_vehicle_sidebar'] = $_POST['left_vehicle_sidebar'];
	if (isset($_POST['right_vehicle_sidebar'])) $new['right_vehicle_sidebar'] = $_POST['right_vehicle_sidebar'];
	if (isset($_POST['sidebar_id'])) $new['sidebar_id'] = $_POST['sidebar_id'];
	if (isset($_POST['cd_content_id'])) $new['cd_content_id'] = $_POST['cd_content_id'];
	if (isset($_POST['cd_page_id'])) $new['cd_page_id'] = $_POST['cd_page_id'];
	if (isset($_POST['cd_page_css'])) $new['cd_page_css'] = $_POST['cd_page_css'];
	if (isset($_POST['dl_container'])) $new['dl_container'] = $_POST['dl_container'];
	if (isset($_POST['dl_items'])) $new['dl_items'] = $_POST['dl_items'];
	if (isset($_POST['dl_pagination'])) $new['dl_pagination'] = $_POST['dl_pagination'];
	if (isset($_POST['dl_next'])) $new['dl_next'] = $_POST['dl_next'];

	update_option( 'car_demon_options', $new );
	$holder = '';
	$car_demon_settings_hook = apply_filters('car_demon_settings_update_hook', $holder);
	echo '<h3 class="admin_settings_updated_title">'.__('SETTINGS HAVE BEEN UPDATED', 'car-demon').'</h3>';
}
function reset_car_demon() {
	delete_option('car_demon_options');
	echo '<h3 class="admin_settings_updated_title">'.__('SETTINGS HAVE BEEN RESET', 'car-demon').'</h3>';
}
function get_default_finance_description() {
	$x = '<span>'.__('This is not a contract to purchase. It is an application for financing a possible automotive purchase.', 'car-demon').' <br />
		  <strong> '.__('You are not obligated to purchase', 'car-demon').'</strong> '.__('a vehicle if you submit this form.', 'car-demon').' </span>
		  <br />
		  <span>'.__('Your information is kept confidential and is used only to assist in obtaining financing for a potential vehicle purchase.', 'car-demon').'<br />
		  '.__('*By clicking this button you agree to the terms  posted above.', 'car-demon').'
		  </span>
	';
	return $x;
}
function get_default_finance_disclaimer() {
	$x = __('
	*TERMS AND DISCLOSURE 
	
	The following terms of agreement apply to all of our online applications;
	
	we, us, our and ours as used below refer to the dealer and to the Financial 
	
	Institutions selected to receive your application.
	
	You authorize the dealer, as part of the credit underwriting process, to submit this 
	
	application and any other application submitted in connection with the proposed transaction
	
	to the Financial Institutions disclosed to you by the dealer, for review. In addition, in 
	
	accordance with the Fair Credit Reporting Act, you authorize that such Financial Institutions 
	
	may submit your applications for review to other Financial Institutions that may want to 
	
	purchase your contract.
	
	
	You agree that we and any Financial Institutions to which your application is submitted may 
	
	obtain a consumer credit report periodically from one or more consumer reporting agencies 
	
	(credit bureaus) in connection with the proposed transaction and any update, renewal, 
	
	refinancing, modification or extension of that transaction.
	
	You agree that we may verify your employment, pay, assets and debts, and that anyone 
	
	receiving a copy of this is authorized to provide us with such information.
	
	You further authorize us to gather whatever credit and employment history we consider
	
	necessary and appropriate in reviewing this application and any other applications 
	
	submitted in connection with the proposed transaction.
	
	We may keep this application and any other application submitted to us, and information 
	
	about you whether or not the application is approved. You certify that the information on 
	
	this application and in any other application submitted to us, is true and complete. You 
	
	understand that false statements may subject you to criminal penalties.
	
	FEDERAL NOTICES
	
	IMPORTANT INFORMATION ABOUT PROCEDURES FOR OPENING A NEW ACCOUNT
	
	To help the government fight the funding of terrorism and money laundering activities, 
	
	Federal law requires all financial institutions to obtain, verify, and record information that 
	
	identifies each person who opens an account. What this means for you: When you open an 
	
	account, we will ask for your name,address, date of birth, and other information that will  
	
	allow us to identify you. We may also ask to see your driver\'s license or other identifying 
	
	documents.
	
	STATE NOTICES
	
	Arizona, California, Idaho, Louisiana, Nevada, New Mexico, Texas, Washington or  
	
	Wisconsin Residents: If you, the applicant, are married and live in a community property 
	
	state, you should also provide the personal credit information on your spouse in the 
	
	co-applicant section. Your spouse is not required to be a co-applicant for the credit 
	
	requested unless he/she wishes to be a co-applicant.
	
	California Residents: An applicant, if married, may apply for a separate account.
	
	Ohio Residents: Ohio laws against discrimination require that all creditors make credit 
	
	equally available to all creditworthy customers and that credit reporting agencies maintain  
	
	separate credit histories on each individual upon request. The Ohio Civil Rights  
	
	Commission administers compliance with this law.
	
	New Hampshire Residents:If this is an application for balloon financing, you are entitled to 
	
	receive, upon request, a written estimate of the monthly payment amount that would be  
	
	required to refinance the balloon payment at the time such payment is due based on the
	
	creditor\'s current refinancing programs.
	
	New Hampshire Residents: In connection with your application for credit, we may request a 
	
	consumer report that contains information on your credit worthiness, credit standing,  
	
	personal characteristics and general reputation. If we grant you credit, we or our  
	
	servicer may order additional consumer reports in connection with any update, renewal  
	
	or extension of the credit. If you ask us, we will tell you whether we obtained a consumer  
	
	report and if we did, we will tell you the name and address of the consumer reporting 
	
	agency that gave us the report.
	
	Vermont Residents: By clicking on Submit, you authorize us and our employees or agents  
	
	to obtain and verify information about you (including one or more credit reports, information 
	
	about your employment and banking and credit relationships) that we may deem necessary  
	
	or appropriate in reviewing your application. If your application is approved and credit is 
	
	extended, you also authorize us, and our employees and agents, to obtain additional credit 
	
	reports and other information about you in connection with reviewing the account,  
	
	increasing the available credit on the account (if applicable), taking collection on  
	
	the account, or for any other legitimate purpose.
	
	Married Wisconsin Residents: Wisconsin law provides that no provision of any marital  
	
	property agreement, or unilateral statement, or court order applied to marital property  
	
	will adversely affect a creditor\'s interests unless, prior to the time that the credit 
	
	is granted, the creditor is furnished with a copy of the agreement, statement or decree,
	
	or has actual  knowledge of the adverse provision. If you are making this application  
	
	individually, and not jointly with your spouse, the full name and current address of 
	
	your spouse must be properly disclosed in the co-applicant section of this application.
	', 'car-demon');
	return $x;
}
function get_default_description() {
	$x = __('This vehicle is ready to go right now.', 'car-demon');
	return $x;
}

function car_demon_settings_edit_vehicle_options() {
	$options = cd_get_vehicle_map();
	$description_options = $options['description'];
	$specs_options = $options['specs'];
	$safety_options = $options['safety'];
	$convenience_options = $options['convenience'];
	$comfort_options = $options['comfort'];
	$entertainment_options = $options['entertainment'];
	$about_us_options = $options['about_us'];
	if (isset($_SESSION['car_demon_options']['hide_tabs'])) {
		if ($_SESSION['car_demon_options']['hide_tabs'] == 'Yes') {
			$show_tabs = 0;
		} else {
			$show_tabs = 1;	
		}
	}
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div><h1>'.__('Edit Vehicle Option Tabs', 'car-demon').'</h1>';
	echo '<blockquote>';
		_e('Add and remove the groups and options that appear under the different vehicle information tabs.','car-demon');
	echo '</blockquote>';
		/*
		echo '<h3 class="open_tab" id="cd_open_description">+ '.__('Description Tab', 'car-demon').'</h3>';
		echo '<div class="tab" id="description_tab">';
			echo '<h5 class="close_tab" id="cd_close_description">- '.__('Close Tab', 'car-demon').'</h5>';
			echo '<h4 class="add_vehicle_option_group" id="cd_add_description"> + '.__('Add Group', 'car-demon').'</h4>';
			echo add_vehicle_option_group_form('description');
			if (!empty($description_options)) {
				echo '<blockquote>';
					foreach ($description_options as $group => $value) {
						echo '<div id="group_'.$group.'">';
							echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
							echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'description\',\''.$group.'\')">X - Delete this group</div>';
							echo '<div class="clear"></div>';
							echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
								echo $value;
							echo '</textarea>';
							echo '<div class="clear"></div>';
							echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'description\',\''.$group.'\')" />';
							echo '<div class="clear"></div>';
						echo '</div>';
					}
				echo '</blockquote>';
				echo '<div class="clear"></div>';
			}
		echo '</div>';
		*/
		echo '<h3 class="open_tab" id="cd_open_specs">+ '.__('Specs Tab', 'car-demon').'</h3>';
		echo '<div class="tab" id="specs_tab">';
			echo '<h5 class="close_tab" id="cd_close_specs">- '.__('Close Tab', 'car-demon').'</h5>';
			echo '<form method="post" action="">';
				echo '<fieldset class="cd_admin_group">';
					echo '<legend>'.__('Manage default fields','car-demon').'</legend>';
					echo '<h4>'.__('Check the box next to each field to hide it.','car-demon').'</h4>';
					echo '<h4>'.__('You may also relabel the field by changing the value in the box.','car-demon').'</h4>';
					echo '<h5>'.__('These label changes are only reflected in the page content and do not change URL structures.','car-demon').'</h5>';
					echo '<blockquote>';
						$show_hide = get_show_hide_fields();
						$field_labels = get_default_field_labels();
						echo '<div id="sh_vin"><input type="checkbox"'.($show_hide['vin']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="vin" /><input type="text" id="label_vin" value="'.$field_labels['vin'].'" onchange="update_default_labels(this);" /> '.__('Vin','car-demon').'</div>';
						echo '<div id="sh_stock_number"><input type="checkbox"'.($show_hide['stock_number']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="stock_number" /><input type="text" id="label_stock_number" value="'.$field_labels['stock_number'].'" onchange="update_default_labels(this);" /> '.__('Stock Number','car-demon').'</div>';
						echo '<div id="sh_mileage"><input type="checkbox"'.($show_hide['mileage']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="mileage" /><input type="text" id="label_mileage" value="'.$field_labels['mileage'].'" onchange="update_default_labels(this);" /> '.__('Mileage','car-demon').'</div>';
						echo '<div id="sh_body_style"><input type="checkbox"'.($show_hide['body_style']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="body_style" /><input type="text" id="label_body_style" value="'.$field_labels['body_style'].'" onchange="update_default_labels(this);" /> '.__('Body Style','car-demon').'</div>';
						echo '<div id="sh_year"><input type="checkbox"'.($show_hide['year']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="year" /><input type="text" id="label_year" value="'.$field_labels['year'].'" onchange="update_default_labels(this);" /> '.__('Year','car-demon').'</div>';
						echo '<div id="sh_make"><input type="checkbox"'.($show_hide['make']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="make" /><input type="text" id="label_make" value="'.$field_labels['make'].'" onchange="update_default_labels(this);" /> '.__('Make','car-demon').'</div>';
						echo '<div id="sh_model"><input type="checkbox"'.($show_hide['model']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="model" /><input type="text" id="label_model" value="'.$field_labels['model'].'" onchange="update_default_labels(this);" /> '.__('Model','car-demon').'</div>';
						echo '<div id="sh_retail"><input type="checkbox"'.($show_hide['retail']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="retail" /><input type="text" id="label_retail" value="'.$field_labels['retail'].'" onchange="update_default_labels(this);" /> '.__('Retail Price','car-demon').'</div>';
						echo '<div id="sh_rebates"><input type="checkbox"'.($show_hide['rebates']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="rebates" /><input type="text" id="label_rebates" value="'.$field_labels['rebates'].'" onchange="update_default_labels(this);" /> '.__('Rebates','car-demon').'</div>';
						echo '<div id="sh_discount"><input type="checkbox"'.($show_hide['discount']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="discount" /><input type="text" id="label_discount" value="'.$field_labels['discount'].'" onchange="update_default_labels(this);" /> '.__('Discount','car-demon').'</div>';
						echo '<div id="sh_price"><input type="checkbox"'.($show_hide['price']==true ? ' checked' : '').' onclick="show_hide_default_fields(this);" value="price" /><input type="text" id="label_price" value="'.$field_labels['price'].'" onchange="update_default_labels(this);" /> '.__('Price','car-demon').'</div>';
						echo '<h5>'.__('These values should update as soon as you make changes.','car-demon').'<h5>';
						echo '<h5>'.__('The vehicle search widget requires you to use a Stock # and may not behave properly if this field is hidden.','car-demon').'<h5>';
					echo '</blockquote>';
				echo '</fieldset>';
			echo '</form>';
			echo '<h4 class="add_vehicle_option_group" id="cd_add_specs"> + '.__('Add Group', 'car-demon').'</h4>';
			echo add_vehicle_option_group_form('specs');
			if (!empty($specs_options)) {
				echo '<blockquote>';
					foreach ($specs_options as $group => $value) {
						echo '<div id="group_'.$group.'">';
							echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
							echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'specs\',\''.$group.'\')">X - Delete this group</div>';
							echo '<div class="clear"></div>';
							echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
								echo $value;
							echo '</textarea>';
							echo '<div class="clear"></div>';
							echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'specs\',\''.$group.'\')" />';
							echo '<div class="clear"></div>';
						echo '</div>';
					}
				echo '</blockquote>';
				echo '<div class="clear"></div>';
			}
		echo '</div>';
		if ($show_tabs == 1) {
			echo '<h3 class="open_tab" id="cd_open_safety">+ '.__('Safety Tab', 'car-demon').'</h3>';
			echo '<div class="tab" id="safety_tab">';
				echo '<h5 class="close_tab" id="cd_close_safety">- '.__('Close Tab', 'car-demon').'</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_safety"> + '.__('Add Group', 'car-demon').'</h4>';
				echo add_vehicle_option_group_form('safety');
				if (!empty($safety_options)) {
					echo '<blockquote>';
						foreach ($safety_options as $group => $value) {
							echo '<div id="group_'.$group.'">';
								echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'safety\',\''.$group.'\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'safety\',\''.$group.'\')" />';
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
	
			echo '<h3 class="open_tab" id="cd_open_convenience">+ '.__('Convenience Tab', 'car-demon').'</h3>';
			echo '<div class="tab" id="convenience_tab">';
				echo '<h5 class="close_tab" id="cd_close_convenience">- '.__('Close Tab', 'car-demon').'</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_convenience"> + '.__('Add Group', 'car-demon').'</h4>';
				echo add_vehicle_option_group_form('convenience');
				if (!empty($convenience_options)) {
					echo '<blockquote>';
						foreach ($convenience_options as $group => $value) {
							echo '<div id="group_'.$group.'">';
								echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'convenience\',\''.$group.'\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'convenience\',\''.$group.'\')" />';
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
	
			echo '<h3 class="open_tab" id="cd_open_comfort">+ '.__('Comfort Tab', 'car-demon').'</h3>';
			echo '<div class="tab" id="comfort_tab">';
				echo '<h5 class="close_tab" id="cd_close_comfort">- '.__('Close Tab', 'car-demon').'</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_comfort"> + '.__('Add Group', 'car-demon').'</h4>';
				echo add_vehicle_option_group_form('comfort');
				if (!empty($comfort_options)) {
					echo '<blockquote>';
						foreach ($comfort_options as $group => $value) {
							echo '<div id="group_'.$group.'">';
								echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'comfort\',\''.$group.'\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'comfort\',\''.$group.'\')" />';
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
	
			echo '<h3 class="open_tab" id="cd_open_entertainment">+ '.__('Entertainment Tab', 'car-demon').'</h3>';
			echo '<div class="tab" id="entertainment_tab">';
				echo '<h5 class="close_tab" id="cd_close_entertainment">- '.__('Close Tab', 'car-demon').'</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_entertainment"> + '.__('Add Group', 'car-demon').'</h4>';
				echo add_vehicle_option_group_form('entertainment');
				if (!empty($entertainment_options)) {
					echo '<blockquote>';
						foreach ($entertainment_options as $group => $value) {
							echo '<div id="group_'.$group.'">';
								echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'entertainment\',\''.$group.'\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'entertainment\',\''.$group.'\')" />';
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
				}
			echo '</div>';
		}
		if ($_SESSION['car_demon_options']['use_about'] == 'Yes') {
			$about = 1;
			echo '<h3 class="open_tab" id="cd_open_about_us">+ '.__('About Us Tab', 'car-demon').'</h3>';
			echo '<div class="tab" id="about_us_tab">';
				echo '<h5 class="close_tab" id="cd_close_about_us">- '.__('Close Tab', 'car-demon').'</h5>';
				echo '<h4 class="add_vehicle_option_group" id="cd_add_about_us"> + '.__('Add Group', 'car-demon').'</h4>';
				echo add_vehicle_option_group_form('about_us');
				if (!empty($about_us_options)) {
					echo '<blockquote>';
						foreach ($about_us_options as $group => $value) {
							echo '<div id="group_'.$group.'">';
								echo '<input type="text" value="'.$group.'" class="vehicle_option_group" id="vehicle_option_group_'.$group.'" />';
								echo '<div class="delete_vehicle_option_group" onclick="remove_option_group(\'about_us\',\''.$group.'\')">X - Delete this group</div>';
								echo '<div class="clear"></div>';
								echo '<textarea class="vehicle_option_group_items" id="vehicle_option_group_items_'.$group.'">';
									echo $value;
								echo '</textarea>';
								echo '<div class="clear"></div>';
								echo '<input type="submit" value="Update Group" class="btn_update_group" onclick="update_option_group(\'about_us\',\''.$group.'\')" />';
								echo '<div class="clear"></div>';
							echo '</div>';
						}
					echo '</blockquote>';
					echo '<div class="clear"></div>';
					_e('The About Us tab works slightly differently than the other tabs.','car-demon');
					echo '<br />';
					_e('It displays each title and description area as a block of text with a title, it does not display items delimited by a comma.','car-demon');
					echo '<div class="clear"></div>';
				}
			echo '</div>';
		}
		echo '<br />';
		//= If tabs are hidden give an alert
		if ($show_tabs == 0) {
			echo '<h3 id="cd_disabled_tabs">'.__('You currently have additional tabs diabled.', 'car-demon').'</h3>';
			echo '<h5 id="cd_disabled_tabs_msg">'.__('You may turn them back on from the main Car Demon Settings Page.', 'car-demon').'</h5>';
		}
		
		_e('If you delete a group or option here then it will no longer show on the vehicles.', 'car-demon');
		echo '<br />';
		_e('However, the information is not removed from the vehicles, it is retained but hidden.', 'car-demon');
		echo '<br />';
		_e('To permanently delete the information from the vehicles you will need to remove it from each one, then remove the option here to prevent it from being readded to a vehicle.', 'car-demon');
	echo '</div>';
}

function add_vehicle_option_group_form($group) {
	$x = '<div id="frm_add_'.$group.'" class="add_vehicle_option_group_form">';
		$x .= '<blockquote>';
			$x .= 'Group Title:<br /><input type="text" id="group_option_title_'.$group.'" class="vehicle_option_group" />';
			$x .= '<span id="cancel_'.$group.'" class="cancel_add_group">- Cancel Add</span>';
			$x .= '<div class="clear"></div>';
			$x .= 'Group Items:<br /><textarea class="vehicle_option_group_items" id="group_options_'.$group.'">';
			$x .= '</textarea>';
			$x .= '<div class="clear"></div>';
			if ($group != 'about_us') {
				$x .= 'Put a comma between each item, do not use a space ie. item1,item2,item3';
			}
		$x .= '</blockquote>';
		$x .= '<div class="clear"></div>';
		$x .= '<input type="button" value="Add New Group" onclick="add_option_group(\''.$group.'\');" />';
	$x .= '</div>';
	return $x;
}

function select_group_type($slug, $value) {
	$x = '
		<select id="select_type_'.$slug.'">
			<option value="'.$value.'">'.$value.'</option>
			<option value="Text"></option>
			<option value="Option"></option>
		</select>
	';
	return $x;	
}

function car_demon_remove_option_group() {
	$group = $_POST['group'];
	$group_title = $_POST['group_title'];
	$map = cd_get_vehicle_map();
	if(isset($map[$group][$group_title])) {
		unset($map[$group][$group_title]);
		update_option('cd_vehicle_option_map', $map);
	}
}

function car_demon_add_option_group() {
	$group = $_POST['group'];
	$group_options = $_POST['group_options'];
	$group_title = $_POST['title'];
	$map = cd_get_vehicle_map();
	$map[$group][$group_title] = $group_options;
	update_option('cd_vehicle_option_map', $map);
	exit();
}

function car_demon_update_option_group() {
	$group = $_POST['group'];
	$group_options = $_POST['group_options'];
	$group_title = $_POST['group_title'];
	$map = cd_get_vehicle_map();
	$map[$group][$group_title] = $group_options;
	update_option('cd_vehicle_option_map', $map);
	exit();
}

function cd_sidebar_selectbox( $name = '', $current_value = false ) {
    global $wp_registered_sidebars;
	$sidebar_list = '';
    if ( empty( $wp_registered_sidebars ) )
        return;
	$sidebar_list .= '<select name="'.$name.'" id="'.$name.'">';
		if (empty($current_value)) {
			$sidebar_list .= '<option value="" selected>None</option>';
		} else {
			$sidebar_list .= '<option value="">None</option>';
		}
		foreach ( $wp_registered_sidebars as $sidebar ) :
			if ($sidebar['name'] == $current_value) {
				$sidebar_list .= '<option value="'.$sidebar['name'].'" selected>'.$sidebar['name'].'</option>';
			} else {
				$sidebar_list .= '<option value="'.$sidebar['name'].'">'.$sidebar['name'].'</option>';
			}
		endforeach; 
	$sidebar_list .= '</select>';	
	return $sidebar_list;
}

function get_default_field_labels() {
	$labels = array();
	$labels['vin'] = __('Vin #','car-demon');
	$labels['stock_number'] = __('Stock #','car-demon');
	$labels['mileage'] = __('Mileage','car-demon');
	$labels['body_style'] = __('Body Style','car-demon');
	$labels['year'] = __('Year','car-demon');
	$labels['make'] = __('Make','car-demon');
	$labels['model'] = __('Model','car-demon');
	$labels['retail'] = __('Retail Price','car-demon');
	$labels['rebates'] = __('Rebates','car-demon');
	$labels['discount'] = __('Discount','car-demon');
	$labels['price'] = __('Asking Price','car-demon');
	//= These fields are not changable at this point
	$labels['condition'] = __('Condition','car-demon');
	$labels['transmission'] = __('Transmission','car-demon');
	$labels['exterior_color'] = __('Exterior Color','car-demon');
	$labels['interior_color'] = __('Interior Color','car-demon');
	$labels['engine'] = __('Engine','car-demon');
	$label_options = get_option( 'cd_default_field_labels', $labels );
	if (!isset($label_options['vin'])) $label_options['vin'] = $labels['vin'];
	if (!isset($label_options['stock_number'])) $label_options['stock_number'] = $labels['stock_number'];
	if (!isset($label_options['mileage'])) $label_options['mileage'] = $labels['mileage'];
	if (!isset($label_options['body_style'])) $label_options['body_style'] = $labels['body_style'];
	if (!isset($label_options['year'])) $label_options['year'] = $labels['year'];
	if (!isset($label_options['make'])) $label_options['make'] = $labels['make'];
	if (!isset($label_options['model'])) $label_options['model'] = $labels['model'];
	if (!isset($label_options['retail'])) $label_options['retail'] = $labels['retail'];
	if (!isset($label_options['rebates'])) $label_options['rebates'] = $labels['rebates'];
	if (!isset($label_options['discount'])) $label_options['discount'] = $labels['discount'];
	if (!isset($label_options['price'])) $label_options['price'] = $labels['price'];
	if (!isset($label_options['condition'])) $label_options['condition'] = $labels['condition'];
	if (!isset($label_options['transmission'])) $label_options['transmission'] = $labels['transmission'];
	if (!isset($label_options['exterior_color'])) $label_options['exterior_color'] = $labels['exterior_color'];
	if (!isset($label_options['interior_color'])) $label_options['interior_color'] = $labels['interior_color'];
	if (!isset($label_options['engine'])) $label_options['engine'] = $labels['engine'];
	return $label_options;	
}

function get_show_hide_fields() {
	$fields = array();
	$fields['vin'] = 0;
	$fields['stock_number'] = 0;
	$fields['mileage'] = 0;
	$fields['body_style'] = 0;
	$fields['year'] = 0;
	$fields['make'] = 0;
	$fields['model'] = 0;
	$fields['retail'] = 0;
	$fields['rebates'] = 0;
	$fields['discount'] = 0;
	$fields['price'] = 0;
	$fields['condition'] = 0;
	$fields['transmission'] = 0;
	$fields['exterior_color'] = 0;
	$fields['interior_color'] = 0;
	$fields['engine'] = 0;
	$fields['location'] = 0;
	$field_options = get_option( 'cd_show_hide_labels', $fields );
	if (!isset($field_options['vin'])) $field_options['vin'] = $fields['vin'];
	if (!isset($field_options['stock_number'])) $field_options['stock_number'] = $fields['stock_number'];
	if (!isset($field_options['mileage'])) $field_options['mileage'] = $fields['mileage'];
	if (!isset($field_options['body_style'])) $field_options['body_style'] = $fields['body_style'];
	if (!isset($field_options['year'])) $field_options['year'] = $fields['year'];
	if (!isset($field_options['make'])) $field_options['make'] = $fields['make'];
	if (!isset($field_options['model'])) $field_options['model'] = $fields['model'];
	if (!isset($field_options['retail'])) $field_options['retail'] = $fields['retail'];
	if (!isset($field_options['rebates'])) $field_options['rebates'] = $fields['rebates'];
	if (!isset($field_options['discount'])) $field_options['discount'] = $fields['discount'];
	if (!isset($field_options['price'])) $field_options['price'] = $fields['price'];
	if (!isset($field_options['condition'])) $field_options['condition'] = $fields['condition'];
	if (!isset($field_options['transmission'])) $field_options['transmission'] = $fields['transmission'];
	if (!isset($field_options['exterior_color'])) $field_options['exterior_color'] = $fields['exterior_color'];
	if (!isset($field_options['interior_color'])) $field_options['interior_color'] = $fields['interior_color'];
	if (!isset($field_options['engine'])) $field_options['engine'] = $fields['engine'];
	if (!isset($field_options['location'])) $field_options['location'] = $fields['location'];
	return $field_options;	
}

function car_demon_update_default_labels() {
	$field = $_POST['field'];
	$field = str_replace('label_','',$field);
	$label = $_POST['label'];
	$labels = get_default_field_labels();
	$labels[$field] = $label;
	update_option('cd_default_field_labels', $labels);
	exit();	
}

function car_demon_update_default_fields() {
	$field = $_POST['field'];
	$checked = $_POST['checked'];
	if ($checked == 'false') $checked = '';
	$fields = get_show_hide_fields();
	$fields[$field] = $checked;
	update_option('cd_show_hide_labels', $fields);
	exit();	
}
?>