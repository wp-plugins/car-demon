<?php
global $pagenow;
if ($pagenow == 'profile.php' || $pagenow == 'user-edit.php') {
	add_action('admin_print_scripts', 'car_demon_profile_scripts');
	add_action('admin_print_styles', 'car_demon_profile_styles');
}
if ($pagenow == 'users.php') {
//	add_action('manage_users_custom_column', 'cardemon_custom_user_column', 15, 3);
//	add_filter('manage_users_columns', 'cardemon_user_column', 15, 1);
}
function cardemon_user_column( $defaults ) {
    $defaults['mysite-usercolumn-company'] = __('Company', 'user-column');
    $defaults['mysite-usercolumn-otherfield1'] = __('Other field 1', 'user-column');
    $defaults['mysite-usercolumn-otherfield2'] = __('Other field 2', 'user-column');
    return $defaults;
}
function cardemon_custom_user_column($value, $column_name, $id) {
    if( $column_name == 'mysite-usercolumn-company' ) {
        return get_the_author_meta( 'company', $id );
    }
    elseif( $column_name == 'mysite-usercolumn-otherfield1' ) {
        return get_the_author_meta( 'otherfield1', $id );
    }
    elseif( $column_name == 'mysite-usercolumn-otherfield2' ) {
        return get_the_author_meta( 'otherfield2', $id );
    }
}
//=================
function car_demon_profile_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('cd-upload', '/wp-content/plugins/car-demon/theme-files/js/profile_uploader.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('cd-upload');
}
 
function car_demon_profile_styles() {
	wp_enqueue_style('thickbox');
}
function car_demon_add_custom_user_profile_fields( $user ) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/includes', '', $car_demon_pluginpath);
	$user_location = esc_attr( get_the_author_meta( 'user_location', $user->ID ) );
	$staff_type = esc_attr( get_the_author_meta( 'staff_type', $user->ID ) );
	$custom_photo = esc_attr( get_the_author_meta( 'profile_photo', $user->ID ) );
	$custom_url = esc_attr( get_the_author_meta( 'custom_url', $user->ID ) );
	if (empty($custom_url)) {
		$custom_url =  site_url().'?sales_code='.$user->ID;
		$custom_url = str_replace('http://', '', $custom_url);
		$custom_url = str_replace('https://', '', $custom_url);
	}
	?>
	<h3><?php _e('Extra Profile Information','car-demon'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="photo"><?php _e('Photo','car-demon'); ?></label>
			</th>
			<td>
				<input type="text" name="profile_photo" id="profile_photo" value="<?php echo $custom_photo; ?>" class="regular-text" />
				<input id="upload_profile_button" type="button" value="<?php _e('Upload Profile Photo','car-demon'); ?>" />
				<br />
				<span class="description"><?php _e('Please select a custom photo.','car-demon'); ?></span>
				<?php
				if (!empty($custom_photo)) {
					echo '<br /><img src="'.$custom_photo.'" width="200" id="custom_user_photo" />';
				}
				else {
					echo '<br /><img src="'.$car_demon_pluginpath.'theme-files/images/spacer.gif" width="1" id="custom_user_photo" />';
				}
				?>
			</td>
		</tr>
		<tr>
			<th>
				<label for="job_title"><?php _e('Job Title','car-demon'); ?></label>
			</th>
			<td>
				<input type="text" name="job_title" id="job_title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter a job title for the user.','car-demon'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="location"><?php _e('Location','car-demon'); ?></label>
			</th>
			<td>
				<?php echo car_demon_select_user_location($user_location); ?><br />
				<span class="description"><?php _e('Please select user location.','car-demon'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="receive_lead_types"><?php _e('Staff Pages','car-demon'); ?></label>
			</th>
			<td>
					<input type="radio" name="staff_type" value=""<?php echo $staff_type == '' ? ' checked="checked"' : ''; ?> /> Do Not List<br />
					<input type="radio" name="staff_type" value="corporate_manager"<?php echo $staff_type == 'corporate_manager' ? ' checked="checked"' : ''; ?> /> Corporate Manager<br />
					<input type="radio" name="staff_type" value="corporate_staff"<?php echo $staff_type == 'corporate_staff' ? ' checked="checked"' : ''; ?> /> Corporate Staff<br />
					<input type="radio" name="staff_type" value="general_manager"<?php echo $staff_type == 'general_manager' ? ' checked="checked"' : ''; ?> /> General Manager<br />
					<input type="radio" name="staff_type" value="sales_manager"<?php echo $staff_type == 'sales_manager' ? ' checked="checked"' : ''; ?> /> Sales Manager<br />
					<input type="radio" name="staff_type" value="sales_staff"<?php echo $staff_type == 'sales_staff' ? ' checked="checked"' : ''; ?> /> Sales Staff<br />
					<input type="radio" name="staff_type" value="finance_manager"<?php echo $staff_type == 'finance_manager' ? ' checked="checked"' : ''; ?> /> Finance Manager<br />
					<input type="radio" name="staff_type" value="finance_staff"<?php echo $staff_type == 'finance_staff' ? ' checked="checked"' : ''; ?> /> Finance Staff<br />
					<input type="radio" name="staff_type" value="service_manager"<?php echo $staff_type == 'service_manager' ? ' checked="checked"' : ''; ?> /> Service Manager<br />
					<input type="radio" name="staff_type" value="service_staff"<?php echo $staff_type == 'service_staff' ? ' checked="checked"' : ''; ?> /> Service Staff<br />
					<input type="radio" name="staff_type" value="parts_manager"<?php echo $staff_type == 'parts_manager' ? ' checked="checked"' : ''; ?> /> Parts Manager<br />
					<input type="radio" name="staff_type" value="parts_staff"<?php echo $staff_type == 'parts_staff' ? ' checked="checked"' : ''; ?> /> Parts Staff<br />
					<input type="radio" name="staff_type" value="office_manager"<?php echo $staff_type == 'office_manager' ? ' checked="checked"' : ''; ?> /> Office Manager<br />
					<input type="radio" name="staff_type" value="office_staff"<?php echo $staff_type == 'office_staff' ? ' checked="checked"' : ''; ?> /> Office Staff<br />
					<input type="radio" name="staff_type" value="support_staff"<?php echo $staff_type == 'support_staff' ? ' checked="checked"' : ''; ?> /> Support Staff<br />
				<span class="description"><?php _e('Select the staff type of the user to determine where they show up on the staff pages.','car-demon'); ?></span>
			</td>
		</tr>
		
		
	</table>
	<h3><?php _e('Advanced Sales Information','car-demon'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="custom_url"><?php _e('Custom URL','car-demon'); ?></label>
			</th>
			<td>
				<input type="text" name="custom_url" id="custom_url" value="<?php echo $custom_url; ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter user\'s custom URL. ie. www.custom_url.com, please make sure this URL is mapped to this site.','car-demon'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="facebook_page"><?php _e('Facebook Page','car-demon'); ?></label>
			</th>
			<td>
				<input type="text" name="facebook_page" id="facebook_page" value="<?php echo esc_attr( get_the_author_meta( 'facebook_page', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter the URL to user\'s Facebook Profile.','car-demon'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="phone_number"><?php _e('Phone Number','car-demon'); ?></label>
			</th>
			<td>
				<input type="text" name="phone_number" id="phone_number" value="<?php echo esc_attr( get_the_author_meta( 'phone_number', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter user\'s phone number.','car-demon'); ?></span>
			</td>
		</tr>
	</table>
	<h3><?php _e('Custom Site Leads','car-demon'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="receive_lead_types"><?php _e('Receive Lead Types','car-demon'); ?></label>
			</th>
			<td>
				<input type="checkbox" name="lead_new_cars" value="1"<?php echo get_the_author_meta('lead_new_cars', $user->ID) == '1' ? ' checked="checked"' : ''; ?> /> New Car Sales &nbsp;
				<input type="checkbox" name="lead_used_cars" value="1"<?php echo get_the_author_meta('lead_used_cars', $user->ID) == '1' ? ' checked="checked"' : ''; ?> /> Used Car Sales &nbsp;
				<input type="checkbox" name="lead_trade" value="1"<?php echo get_the_author_meta('lead_trade', $user->ID) == '1' ? ' checked="checked"' : ''; ?> /> Trade &nbsp;
				<input type="checkbox" name="lead_finance" value="1"<?php echo get_the_author_meta('lead_finance', $user->ID) == '1' ? ' checked="checked"' : ''; ?> /> Finance &nbsp;
				<input type="checkbox" name="lead_parts" value="1"<?php echo get_the_author_meta('lead_parts', $user->ID) == '1' ? ' checked="checked"' : ''; ?> /> Parts &nbsp;
				<input type="checkbox" name="lead_service" value="1"<?php echo get_the_author_meta('lead_service', $user->ID) == '1' ? ' checked="checked"' : ''; ?> /> Service &nbsp;
				<br />
				<span class="description"><?php _e('Select the lead types user may receive on their custom site.','car-demon'); ?></span>
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="receive_lead_types"><?php _e('Lead Locations','car-demon'); ?></label>
			</th>
			<td>
				<select name="lead_locations" id="lead_locations">
					<option value=""<?php echo get_the_author_meta('lead_locations', $user->ID) == '' ? ' selected' : ''; ?>>Own location only</option>
					<option value="1"<?php echo get_the_author_meta('lead_locations', $user->ID) == '1' ? ' selected' : ''; ?>>All Locations</option>
				</select>
				<br />
				<span class="description"><?php _e('Can user receive leads from ALL locations or just their assigned location?','car-demon'); ?></span>
			</td>
		</tr>
		
		
	</table>
<?php }
function car_demon_save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	update_user_meta( $user_id, 'profile_photo', $_POST['profile_photo'] );
	update_user_meta( $user_id, 'job_title', $_POST['job_title'] );
	update_user_meta( $user_id, 'user_location', $_POST['user_location'] );
	update_user_meta( $user_id, 'staff_type', $_POST['staff_type'] );
	update_user_meta( $user_id, 'custom_url', $_POST['custom_url'] );
	update_user_meta( $user_id, 'facebook_page', $_POST['facebook_page'] );
	update_user_meta( $user_id, 'phone_number', $_POST['phone_number'] );
	update_user_meta( $user_id, 'lead_locations', $_POST['lead_locations'] );	
	if (isset($_POST['lead_new_cars'])) { update_user_meta( $user_id, 'lead_new_cars', $_POST['lead_new_cars'] ); }
		else { delete_user_meta( $user_id, 'lead_new_cars'); }
	if (isset($_POST['lead_used_cars'])) { update_user_meta( $user_id, 'lead_used_cars', $_POST['lead_used_cars'] ); }
		else { delete_user_meta( $user_id, 'lead_used_cars'); }
	if (isset($_POST['lead_trade'])) { update_user_meta( $user_id, 'lead_trade', $_POST['lead_trade'] ); }
		else { delete_user_meta( $user_id, 'lead_trade'); }
	if (isset($_POST['lead_finance'])) { update_user_meta( $user_id, 'lead_finance', $_POST['lead_finance'] ); }
		else { delete_user_meta( $user_id, 'lead_finance'); }
	if (isset($_POST['lead_parts'])) { update_user_meta( $user_id, 'lead_parts', $_POST['lead_parts'] ); }
		else { delete_user_meta( $user_id, 'lead_parts'); }
	if (isset($_POST['lead_service'])) { update_user_meta( $user_id, 'lead_service', $_POST['lead_service'] ); }
		else { delete_user_meta( $user_id, 'lead_service'); }
}
add_action( 'show_user_profile', 'car_demon_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'car_demon_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'car_demon_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'car_demon_save_custom_user_profile_fields' );
function car_demon_select_user_location($current_user_location) {
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
	$cnt = 0;
	foreach ($locations as $location) {
		$cnt = $cnt + 1;
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$location_name_list = 'Default'.$location_name_list;
		$cnt = 1;
	} else {
		$location_list = '@'.$location_list;
		$location_list = str_replace("@,","", $location_list);
		$location_list = str_replace("@","", $location_list);
		$location_name_list = '@'.$location_name_list;
		$location_name_list = str_replace("@,","", $location_name_list);
		$location_name_list = str_replace("@","", $location_name_list);
	}
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;
	if ($cnt == 1) {
		$select_trade = " checked='checked'";
	}
	$html = '<select name="user_location">';
	$html .= '<option></option>';
	foreach ($location_name_list_array as $current_location) {
		$selected = $location_list_array[$x] == $current_user_location ? ' selected' : '';
		$html .= '<option value="'.$location_list_array[$x].'"'.$selected.'>'.$current_location.'</option>';
		$x = $x + 1;
	}
	$html .= '</select>';
	return $html;
}
?>