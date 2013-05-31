<?php
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
	wp_enqueue_script('car-demon-admin-js', WP_CONTENT_URL . '/plugins/car-demon/admin/js/car-demon-admin.js.php');
	wp_enqueue_style('car-demon-admin-css', WP_CONTENT_URL . '/plugins/car-demon/admin/css/car-demon-admin.css');
}

function car_demon_plugin_options_do_page() {
	screen_icon();
	echo '<div class="wrap">';
		echo "<h2>". __('Car Demon Contact Options', 'car-demon') . "</h2>";
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
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('admin/','',$car_demon_pluginpath);
	$default = array();
	$default['currency_symbol'] = '$';
	$default['currency_symbol_after'] = '';
	$default['vinquery_id'] = '';
	$default['vinquery_type'] = '1';
	$default['use_about'] = 'Yes';
	$default['adfxml'] = 'No';
	$default['use_compare'] = 'Yes';
	$default['dynamic_load'] = 'No';
	$default['secure_finance'] = 'Yes';
	$default['use_theme_files'] = 'Yes';
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
	$default['sort_price'] = 'Yes';
	$default['sort_miles'] = 'Yes';
	$default['hide_tabs'] = 'No';
	$default['popup_images'] = 'No';
	$default['custom_options'] = '';
	$car_demon_options = array();
	$car_demon_options = get_option( 'car_demon_options', $default );
	if (empty($car_demon_options['currency_symbol'])) {$car_demon_options['currency_symbol'] = $default['currency_symbol'];}
	if (empty($car_demon_options['currency_symbol_after'])) {$car_demon_options['currency_symbol_after'] = $default['currency_symbol_after'];} else { $car_demon_options['currency_symbol'] = ""; }
	if (empty($car_demon_options['vinquery_id'])) {$car_demon_options['vinquery_id'] = $default['vinquery_id'];}
	if (empty($car_demon_options['vinquery_type'])) {$car_demon_options['vinquery_type'] = $default['vinquery_type'];}
	if (empty($car_demon_options['use_about'])) {$car_demon_options['use_about'] = $default['use_about'];}
	if (empty($car_demon_options['adfxml'])) {$car_demon_options['use_about'] = $default['adfxml'];}
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
	if (empty($car_demon_options['do_sort'])) {$car_demon_options['do_sort'] = $default['do_sort'];}
	if (empty($car_demon_options['sort_price'])) {$car_demon_options['sort_price'] = $default['sort_price'];}
	if (empty($car_demon_options['sort_miles'])) {$car_demon_options['sort_miles'] = $default['sort_miles'];}
	if (empty($car_demon_options['hide_tabs'])) {$car_demon_options['hide_tabs'] = $default['hide_tabs'];}
	if (empty($car_demon_options['popup_images'])) {$car_demon_options['popup_images'] = $default['popup_images'];}
	if (empty($car_demon_options['custom_options'])) {$car_demon_options['custom_options'] = $default['custom_options'];}
	return $car_demon_options;
}

function car_demon_settings_options_do_page() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div><h1>'.__('Car Demon Settings', 'car-demon').'</h1>';
	if (isset($_POST['reset_car_demon'])) {
		reset_car_demon();
	} else {
		if(isset($_POST['update_car_demon']) == 1) {
			update_car_demon_settings();
		}
	}
	$car_demon_options = car_demon_options();
	echo '<form action="" method="post">';
		echo '<input type="hidden" name="update_car_demon" value="1" />';
		echo '<hr />';
		echo '<br />'.__('Disable Finance Form if it isn\'t loaded with SSL', 'car-demon').':<br />';
		echo '<select name="secure_finance">
			<option value="'.$car_demon_options['secure_finance'].'">'.$car_demon_options['secure_finance'].'</option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
			</select><br />';
		echo '<br />*'.__('Currency Symbol', 'car-demon').':<br />';
		echo '<input type="text" name="currency_symbol" value="'.$car_demon_options['currency_symbol'].'" /><br />';
		echo '<br />*'.__('Currency Symbol After Price', 'car-demon').':<br />';
		echo '<input type="text" name="currency_symbol_after" value="'.$car_demon_options['currency_symbol_after'].'" /><br />';
		echo '<br />*'.__('VinQuery.com Access Code', 'car-demon').':<br />';
		echo '<input type="text" name="vinquery_id" value="'.$car_demon_options['vinquery_id'].'" />';
		echo '*(optional)<br />';
		$vinquery_type_num = $car_demon_options['vinquery_type'];
		if (empty($vinquery_type_num)) {
			$vinquery_type_num = 1;
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
		echo '<br />'.__('Blind Carbon Copy Forms to Admin?', 'car-demon').':<br />';
		echo '<select name="cc_admin">
				<option value="'.$car_demon_options['cc_admin'].'">'.$car_demon_options['cc_admin'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Show sold vehicles in search results?', 'car-demon').':<br />';
		echo '<select name="show_sold">
				<option value="'.$car_demon_options['show_sold'].'">'.$car_demon_options['show_sold'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Include ADFxml with Leads?', 'car-demon').':<br />';
		echo '<select name="adfxml">
				<option value="'.$car_demon_options['adfxml'].'">'.$car_demon_options['adfxml'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
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
		echo '<br />'.__('Use Compare Vehicle Option', 'car-demon').':<br />';
		echo '<select name="use_compare">
				<option value="'.$car_demon_options['use_compare'].'">'.$car_demon_options['use_compare'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Load Next Inventory Page on Scroll', 'car-demon').':<br />';
		echo '<select name="dynamic_load">
				<option value="'.$car_demon_options['dynamic_load'].'">'.$car_demon_options['dynamic_load'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Use Popup Images', 'car-demon').':<br />';
		echo '<select name="popup_images">
				<option value="'.$car_demon_options['popup_images'].'">'.$car_demon_options['popup_images'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<hr />'.__('Show sorting options on vehicle listing pages?', 'car-demon').':<br />';
		echo '<select name="do_sort">
				<option value="'.$car_demon_options['do_sort'].'">'.$car_demon_options['do_sort'].'</option>
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
			</select><hr />';
		echo '<br />'.__('Custom Vehicle Options', 'car-demon').':<br />';
		echo '<textarea name="custom_options" cols="60" rows="6">'.$car_demon_options['custom_options'].' </textarea><br />';
		echo __('Separate options with a comma. The option you enter here will appear on the vehicle edit page under "Custom Options"','car-demon');
		echo '<hr />';
		echo '<br />'.__('Use included theme files?', 'car-demon').':<br />';
		echo '<select name="use_theme_files">
				<option value="'.$car_demon_options['use_theme_files'].'">'.$car_demon_options['use_theme_files'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Use Title field for Vehicle Titles? - If No then title will be "Year Make Model"', 'car-demon').':<br />';
		echo '<select name="use_post_title">
				<option value="'.$car_demon_options['use_post_title'].'">'.$car_demon_options['use_post_title'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Use Dynamic Ribbons?', 'car-demon').':<br />';
		echo '<select name="dynamic_ribbons">
				<option value="'.$car_demon_options['dynamic_ribbons'].'">'.$car_demon_options['dynamic_ribbons'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Validate Phone Numbers?', 'car-demon').':<br />';
		echo '<select name="validate_phone">
				<option value="'.$car_demon_options['validate_phone'].'">'.$car_demon_options['validate_phone'].'</option>
				<option value="Yes">'.__('Yes', 'car-demon').'</option>
				<option value="No">'.__('No', 'car-demon').'</option>
			</select><br />';
		echo '<br />'.__('Display before listings:', 'car-demon').'<br />';
		echo '<textarea name="before_listings" rows="5" cols="60">'.$car_demon_options['before_listings'].'</textarea><br />';
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
		echo '<p><input type="submit" value="'.__('Update Car Demon', 'car-demon').'" />';
		echo '<input type="submit" name="reset_car_demon" value="'.__('Reset to Default', 'car-demon').'" /></p>';
	echo '</form>';
	echo '<hr />';
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
		echo '[contact_us]<br />';
		echo '[service_form]<br />';
		echo '[service_quote]<br />';
		echo '[part_request]<br />';
		echo '[trade]<br />';
		echo '[finance_form]<br />';
		echo '[qualify]<br />';
		echo '[staff_page]<br />';
	echo '</div></div>';
}

function update_car_demon_settings() {
	$new = array();
	$new = get_option( 'car_demon_options' );
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
	if (isset($_POST['mobile_theme'])) $new['mobile_theme'] = $_POST['mobile_theme'];
	if (isset($_POST['mobile_logo'])) $new['mobile_logo'] = $_POST['mobile_logo'];
	if (isset($_POST['mobile_header'])) $new['mobile_header'] = $_POST['mobile_header'];
	if (isset($_POST['validate_phone'])) $new['validate_phone'] = $_POST['validate_phone'];
	if (isset($_POST['before_listings'])) $new['before_listings'] = $_POST['before_listings'];
	if (isset($_POST['use_post_title'])) $new['use_post_title'] = $_POST['use_post_title'];
	if (isset($_POST['show_sold'])) $new['show_sold'] = $_POST['show_sold'];
	if (isset($_POST['cc_admin'])) $new['cc_admin'] = $_POST['cc_admin'];
	if (isset($_POST['do_sort'])) $new['do_sort'] = $_POST['do_sort'];
	if (isset($_POST['sort_price'])) $new['sort_price'] = $_POST['sort_price'];
	if (isset($_POST['sort_miles'])) $new['sort_miles'] = $_POST['sort_miles'];
	if (isset($_POST['hide_tabs'])) $new['hide_tabs'] = $_POST['hide_tabs'];
	if (isset($_POST['popup_images'])) $new['popup_images'] = $_POST['popup_images'];
	if (isset($_POST['custom_options'])) $new['custom_options'] = $_POST['custom_options'];
	update_option( 'car_demon_options', $new );
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
?>