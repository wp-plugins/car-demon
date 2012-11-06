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
	$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$pluginpath = str_replace('admin', '', $pluginpath);
	$pluginpath .= 'includes';
	$js = '<script>
	function update_car(post_id, this_fld, fld) {
		var new_value = this_fld.value;
		jQuery.ajax({
			type: \'POST\',
			data: {\'post_id\': post_id, \'val\': new_value, \'fld\': fld},
			url: "'. $pluginpath .'/car-demon-handler.php?update_car=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
			var new_body = html;
				this_fld.style.background = "#99CC99";
				var delay = function() { this_fld.style.background = "#FFFFFF" };
				setTimeout(delay, 1000);
				var msrp = document.getElementById("msrp_"+post_id).value;
				var rebate = document.getElementById("rebate_"+post_id).value;
				var discount = document.getElementById("discount_"+post_id).value;				
				var price = document.getElementById("price_"+post_id).value;
				if (msrp == "") { msrp = 0; }
				if (rebate == "") { rebate = 0; }
				if (discount == "") { discount = 0; }
				if (price == "") { price = 0; }
				msrp = parseInt(msrp);
				rebate = parseInt(rebate);
				discount = parseInt(discount);
				price = parseInt(price);
				var calc_price = msrp - rebate - discount;
				document.getElementById("calc_price_"+post_id).innerHTML = calc_price
				document.getElementById("calc_discounts_"+post_id).innerHTML = rebate + discount;
				if (price != calc_price) {
					if (msrp != 0) {
						document.getElementById("price_"+post_id).style.background = "#FFB3B3";
						document.getElementById("calc_error_"+post_id).innerHTML = "Calc Error: " + (calc_price - price) + "<br />";
					}
					else {
						document.getElementById("price_"+post_id).style.background = "#FFFFFF";
						document.getElementById("calc_error_"+post_id).innerHTML = "";
					}
				}
				else {
					document.getElementById("calc_error_"+post_id).innerHTML = "";
					document.getElementById("price_"+post_id).style.background = "#FFFFFF";
				}
			}
		})
		return false;
	}
	function update_car_sold(post_id, this_fld, fld) {
		var new_value = this_fld.options[this_fld.selectedIndex].value;
		jQuery.ajax({
			type: \'POST\',
			data: {\'post_id\': post_id, \'val\': new_value, \'fld\': fld},
			url: "'. $pluginpath .'/car-demon-handler.php?update_car=1",
			timeout: 2000,
			error: function() {},
			dataType: "html",
			success: function(html){
			var new_body = html;
				this_fld.style.background = "#99CC99";
				var delay = function() { this_fld.style.background = "#FFFFFF" };
				setTimeout(delay, 1000);
			}
		})
		return false;
	}
	function show_custom_slide(slide_num) {
		document.getElementById("custom_slide_"+slide_num).style.display = "inline";
		document.getElementById("show_slide_"+slide_num).style.display = "none";
		document.getElementById("hide_slide_"+slide_num).style.display = "inline";
	}
	function hide_custom_slide(slide_num) {
		document.getElementById("custom_slide_"+slide_num).style.display = "none";
		document.getElementById("show_slide_"+slide_num).style.display = "inline";
		document.getElementById("hide_slide_"+slide_num).style.display = "none";
	}
	function clear_custom_slide(slide_num) {
		document.getElementById("custom_slide"+slide_num+"_title").value = "";
		document.getElementById("custom_slide"+slide_num+"_img").value = "";
		document.getElementById("custom_slide"+slide_num+"_link").value = "";
		document.getElementById("custom_slide"+slide_num+"_text").value = "";
	}
	</script>
	';
	echo $js;
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
	?>
	<style>
	.cd_admin_form_label {
		font-size:14px;
		padding-right:20px;
		width:160px;
		display: inline;
		float:left;
		font-weight: bold;
	}
	.cd_admin_form input {
		width: 400px;
	}
	.cd_admin_form {
		width: 600px;
		background-color: #FFFFFF;
		-webkit-border-radius: 15px;
		-moz-border-radius: 15px;
		border-radius: 15px;
		padding-top: 5px;
		padding-bottom: 15px;
		padding-right: 5px;
		padding-left: 5px;
		border: solid;
		border-width: 2px;
		border-color: #999999;
		margin-bottom: 10px;
		margin-top: 20px;
		-moz-box-shadow: 2px 2px 3px #333;
		-webkit-box-shadow: 2px 2px 3px #333;
		box-shadow: 2px 2px 3px #333333;
		/* For IE 8 */
		-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";
		/* For IE 5.5 - 7 */
		filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000');
	}
	</style>
	<?php
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
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
		<h1><?php echo $location_name_list_array[$x]; ?> Contact Information</h1>
		<span class="cd_admin_form_label">Facebook Fan Page</span>
		<input type="text" value="<?php echo get_option($current_location.'_facebook_page') ?>" name="<?php echo $current_location; ?>_facebook_page" id="<?php echo $current_location; ?>_facebook_page" />
		<br />
		<span class="cd_admin_form_label">New Sales Name</span>
		<input type="text" value="<?php echo get_option($current_location.'_new_sales_name') ?>" name="<?php echo $current_location; ?>_new_sales_name" id="<?php echo $current_location; ?>_new_sales_name" />
		<br />
		<span class="cd_admin_form_label">New Sales Number</span>
		<input type="text" value="<?php echo get_option($current_location.'_new_sales_number') ?>" name="<?php echo $current_location; ?>_new_sales_number" id="<?php echo $current_location; ?>_new_sales_number" />
		<span class="cd_admin_form_label">New Mobile Number</span>
		<input type="text" value="<?php echo get_option($current_location.'_new_mobile_number') ?>" name="<?php echo $current_location; ?>_new_mobile_number" id="<?php echo $current_location; ?>_new_mobile_number" />
		<br />
		<span class="cd_admin_form_label">New Mobile Provider</span>
		<?php
			$current_val = get_option($current_location.'_new_mobile_provider');
			echo select_cell_provider($current_location.'_new_mobile_provider', $current_val);
		?> <span style="font-size:10px;">- blank disables SMS for new.</span>
		<br />
		<span class="cd_admin_form_label">New Sales Email</span>
		<input type="text" value="<?php echo get_option($current_location.'_new_sales_email') ?>" name="<?php echo $current_location; ?>_new_sales_email" id="<?php echo $current_location; ?>_new_sales_email" />
		<br />
		<span class="cd_admin_form_label">Used Sales Name</span>
		<input type="text" value="<?php echo get_option($current_location.'_used_sales_name') ?>" name="<?php echo $current_location; ?>_used_sales_name" id="<?php echo $current_location; ?>_used_sales_name" />
		<br />
		<span class="cd_admin_form_label">Used Sales Number</span>
		<input type="text" value="<?php echo get_option($current_location.'_used_sales_number') ?>" name="<?php echo $current_location; ?>_used_sales_number" id="<?php echo $current_location; ?>_used_sales_number" />
		<br />		
		<span class="cd_admin_form_label">Used Mobile Number</span>
		<input type="text" value="<?php echo get_option($current_location.'_used_mobile_number') ?>" name="<?php echo $current_location; ?>_used_mobile_number" id="<?php echo $current_location; ?>_used_mobile_number" />
		<br />
		<span class="cd_admin_form_label">Used Mobile Provider</span>
		<?php
			$current_val = get_option($current_location.'_used_mobile_provider');
			echo select_cell_provider($current_location.'_used_mobile_provider', $current_val);
		?> <span style="font-size:10px;">- blank disables SMS for used.</span>
		<br />
		<span class="cd_admin_form_label">Used Sales Email</span>
		<input type="text" value="<?php echo get_option($current_location.'_used_sales_email') ?>" name="<?php echo $current_location; ?>_used_sales_email" id="<?php echo $current_location; ?>_used_sales_email" />
		<br />
		<?php
			$default_description = get_option($current_location.'_default_description');
			if (strlen($default_description) < 2) {
				$default_description = get_default_description();
			}
		?>
		<span class="cd_admin_form_label">Default Description</span>
		<textarea name="<?php echo $current_location; ?>_default_description" id="<?php echo $current_location; ?>_default_description" style="width:580px;height:100px;"><?php echo $default_description; ?></textarea>
		<br />
		<span class="cd_admin_form_label">Service Name</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_name')) ?>" name="<?php echo $current_location; ?>_service_name" id="<?php echo $current_location; ?>_service_name" />
		<br />
		<span class="cd_admin_form_label">Service Number</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_number')) ?>" name="<?php echo $current_location; ?>_service_number" id="<?php echo $current_location; ?>_service_number" />
		<br />
		<span class="cd_admin_form_label">Service Email</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_service_email')) ?>" name="<?php echo $current_location; ?>_service_email" id="<?php echo $current_location; ?>_service_email" />
		<br />
		<span class="cd_admin_form_label">Parts Name</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_name')) ?>" name="<?php echo $current_location; ?>_parts_name" id="<?php echo $current_location; ?>_parts_name" />
		<br />
		<span class="cd_admin_form_label">Parts Number</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_number')) ?>" name="<?php echo $current_location; ?>_parts_number" id="<?php echo $current_location; ?>_parts_number" />
		<br />
		<span class="cd_admin_form_label">Parts Email</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_parts_email')) ?>" name="<?php echo $current_location; ?>_parts_email" id="<?php echo $current_location; ?>_parts_email" />
		<br />
		<span class="cd_admin_form_label">Finance Name</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_name')) ?>" name="<?php echo $current_location; ?>_finance_name" id="<?php echo $current_location; ?>_finance_name" />
		<br />
		<span class="cd_admin_form_label">Finance Number</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_number')) ?>" name="<?php echo $current_location; ?>_finance_number" id="<?php echo $current_location; ?>_finance_number" />
		<br />
		<span class="cd_admin_form_label">Finance Email</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_email')) ?>" name="<?php echo $current_location; ?>_finance_email" id="<?php echo $current_location; ?>_finance_email" />
		<br />
		<span class="cd_admin_form_label">Link to Finance Form</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_finance_url')) ?>" name="<?php echo $current_location; ?>_finance_url" id="<?php echo $current_location; ?>_finance_url" />
		<br />
		<span class="cd_admin_form_label">Pop Up Finance Form</span>
		<select name="<?php echo $current_location; ?>_finance_popup" id="<?php echo $current_location; ?>_finance_popup">
			<option value="<?php echo strip_tags(get_option($current_location.'_finance_popup')) ?>"><?php echo get_option($current_location.'_finance_popup') ?></option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>
		&nbsp;Width: <input name="<?php echo $current_location; ?>_finance_width" id="<?php echo $current_location; ?>_finance_width" type="text" style="width:50px;" value="<?php echo get_option($current_location.'_finance_width') ?>" />&nbsp;Height: <input name="<?php echo $current_location; ?>_finance_height" id="<?php echo $current_location; ?>_finance_height" type="text" style="width:50px;" value="<?php echo get_option($current_location.'_finance_height') ?>" /> (800px X 600px optimal)
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
		<span class="cd_admin_form_label">*Finance Disclaimer</span>
		<textarea name="<?php echo $current_location; ?>_finance_disclaimer" id="<?php echo $current_location; ?>_finance_disclaimer" style="width:580px;height:100px;"><?php echo $finance_disclaimer; ?></textarea>
		<br />
		<span class="cd_admin_form_label">*Finance Description</span>
		<textarea name="<?php echo $current_location; ?>_finance_description" id="<?php echo $current_location; ?>_finance_description" style="width:580px;height:100px;"><?php echo $finance_description; ?></textarea>
		<br />
		<span class="cd_admin_form_label">Trade Name</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_name')) ?>" name="<?php echo $current_location; ?>_trade_name" id="<?php echo $current_location; ?>_trade_name" />
		<br />
		<span class="cd_admin_form_label">Trade Number</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_number')) ?>" name="<?php echo $current_location; ?>_trade_number" id="<?php echo $current_location; ?>_trade_number" />
		<br />
		<span class="cd_admin_form_label">Trade Email</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_email')) ?>" name="<?php echo $current_location; ?>_trade_email" id="<?php echo $current_location; ?>_trade_email" />
		<br />
		<span class="cd_admin_form_label">Link to Trade Form</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_trade_url')) ?>" name="<?php echo $current_location; ?>_trade_url" id="<?php echo $current_location; ?>_trade_url" />
		<br />
		<span class="cd_admin_form_label">Show Prices on New</span>
		<select name="<?php echo $current_location; ?>_show_new_prices" id="<?php echo $current_location; ?>_show_new_prices">
			<option value="<?php echo strip_tags(get_option($current_location.'_show_new_prices')) ?>"><?php echo strip_tags(get_option($current_location.'_show_new_prices')) ?></option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>&nbsp;If No use: 
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_no_new_price')) ?>" name="<?php echo $current_location; ?>_no_new_price" id="<?php echo $current_location; ?>_no_new_price" style="width:282px;" />
		<br />
		<span class="cd_admin_form_label">Show Prices on Used</span>
		<select name="<?php echo $current_location; ?>_show_used_prices" id="<?php echo $current_location; ?>_show_used_prices">
			<option value="<?php echo strip_tags(get_option($current_location.'_show_used_prices')) ?>"><?php echo strip_tags(get_option($current_location.'_show_used_prices')) ?></option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
		</select>&nbsp;If No use: 
			<input type="text" value="<?php echo strip_tags(get_option($current_location.'_no_used_price')) ?>" name="<?php echo $current_location; ?>_no_used_price" id="<?php echo $current_location; ?>_no_used_price" style="width:282px;" />
		<br />
		<span class="cd_admin_form_label">New Large Photo Url</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_new_large_photo_url')) ?>" name="<?php echo $current_location; ?>_new_large_photo_url" id="<?php echo $current_location; ?>_new_large_photo_url" />
		<br />
		<span class="cd_admin_form_label">New Small Photo Url</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_new_small_photo_url')) ?>" name="<?php echo $current_location; ?>_new_small_photo_url" id="<?php echo $current_location; ?>_new_small_photo_url" />
		<br />
		<span class="cd_admin_form_label">Used Large Photo Url</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_used_large_photo_url')) ?>" name="<?php echo $current_location; ?>_used_large_photo_url" id="<?php echo $current_location; ?>_used_large_photo_url" />
		<br />
		<span class="cd_admin_form_label">Used Small Photo Url</span>
		<input type="text" value="<?php echo strip_tags(get_option($current_location.'_used_small_photo_url')) ?>" name="<?php echo $current_location; ?>_used_small_photo_url" id="<?php echo $current_location; ?>_used_small_photo_url" />
		<?php
			$car_demon_settings_hook = apply_filters('car_demon_admin_hook', $holder, $current_location);
		?>
		<br /><span style="font-weight:bold;color:#FF0000;">* The Default disclaimer and description are provided as examples ONLY and may or may not be legally suitable for your state. Please have a lawyer review your disclaimer and description before using.</span>
		<br />
		<input type="submit" name="sbtSendIt" value="Update Options" style="margin-left: 300px; margin-top: 15px; width: 100px;" />
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
		if ($_POST[$current_location.'_new_mobile_number']) { update_option($current_location.'_new_mobile_number', $_POST[$current_location.'_new_mobile_number']); }
		if ($_POST[$current_location.'_new_mobile_provider']) { update_option($current_location.'_new_mobile_provider', $_POST[$current_location.'_new_mobile_provider']); }
		if ($_POST[$current_location.'_used_mobile_number']) { update_option($current_location.'_used_mobile_number', $_POST[$current_location.'_used_mobile_number']); }
		if ($_POST[$current_location.'_used_mobile_provider']) { update_option($current_location.'_used_mobile_provider', $_POST[$current_location.'_used_mobile_provider']); }
		if ($_POST[$current_location.'_facebook_page']) { update_option($current_location.'_facebook_page', $_POST[$current_location.'_facebook_page']); }
		if ($_POST[$current_location.'_new_sales_name']) { update_option($current_location.'_new_sales_name', $_POST[$current_location.'_new_sales_name']); }
		if ($_POST[$current_location.'_new_sales_number']) { update_option($current_location.'_new_sales_number', $_POST[$current_location.'_new_sales_number']); }
		if ($_POST[$current_location.'_new_sales_email']) { update_option($current_location.'_new_sales_email', $_POST[$current_location.'_new_sales_email']); }
		if ($_POST[$current_location.'_used_sales_name']) { update_option($current_location.'_used_sales_name', $_POST[$current_location.'_used_sales_name']); }
		if ($_POST[$current_location.'_used_sales_number']) { update_option($current_location.'_used_sales_number', $_POST[$current_location.'_used_sales_number']); }
		if ($_POST[$current_location.'_used_sales_email']) { update_option($current_location.'_used_sales_email', $_POST[$current_location.'_used_sales_email']); }
		if ($_POST[$current_location.'_default_description']) { update_option($current_location.'_default_description', $_POST[$current_location.'_default_description']); }
		if ($_POST[$current_location.'_service_name']) { update_option($current_location.'_service_name', $_POST[$current_location.'_service_name']); }
		if ($_POST[$current_location.'_service_number']) { update_option($current_location.'_service_number', $_POST[$current_location.'_service_number']); }
		if ($_POST[$current_location.'_service_email']) { update_option($current_location.'_service_email', $_POST[$current_location.'_service_email']); }
		if ($_POST[$current_location.'_parts_name']) { update_option($current_location.'_parts_name', $_POST[$current_location.'_parts_name']); }
		if ($_POST[$current_location.'_parts_number']) { update_option($current_location.'_parts_number', $_POST[$current_location.'_parts_number']); }
		if ($_POST[$current_location.'_parts_email']) { update_option($current_location.'_parts_email', $_POST[$current_location.'_parts_email']); }
		if ($_POST[$current_location.'_finance_name']) { update_option($current_location.'_finance_name', $_POST[$current_location.'_finance_name']); }
		if ($_POST[$current_location.'_finance_number']) { update_option($current_location.'_finance_number', $_POST[$current_location.'_finance_number']); }
		if ($_POST[$current_location.'_finance_email']) { update_option($current_location.'_finance_email', $_POST[$current_location.'_finance_email']); }
		if ($_POST[$current_location.'_finance_url']) { update_option($current_location.'_finance_url', $_POST[$current_location.'_finance_url']); }
		if ($_POST[$current_location.'_finance_popup']) { update_option($current_location.'_finance_popup', $_POST[$current_location.'_finance_popup']); }
		if ($_POST[$current_location.'_finance_width']) { update_option($current_location.'_finance_width', $_POST[$current_location.'_finance_width']); }
		if ($_POST[$current_location.'_finance_height']) { update_option($current_location.'_finance_height', $_POST[$current_location.'_finance_height']); }
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
		if ($_POST[$current_location.'_trade_name']) { update_option($current_location.'_trade_name', $_POST[$current_location.'_trade_name']); }
		if ($_POST[$current_location.'_trade_number']) { update_option($current_location.'_trade_number', $_POST[$current_location.'_trade_number']); }
		if ($_POST[$current_location.'_trade_email']) { update_option($current_location.'_trade_email', $_POST[$current_location.'_trade_email']); }
		if ($_POST[$current_location.'_trade_url']) { update_option($current_location.'_trade_url', $_POST[$current_location.'_trade_url']); }
		if ($_POST[$current_location.'_show_new_prices']) { update_option($current_location.'_show_new_prices', $_POST[$current_location.'_show_new_prices']); }
		if ($_POST[$current_location.'_show_used_prices']) { update_option($current_location.'_show_used_prices', $_POST[$current_location.'_show_used_prices']); }
		if ($_POST[$current_location.'_new_large_photo_url']) { update_option($current_location.'_new_large_photo_url', $_POST[$current_location.'_new_large_photo_url']); }
		if ($_POST[$current_location.'_new_small_photo_url']) { update_option($current_location.'_new_small_photo_url', $_POST[$current_location.'_new_small_photo_url']); }
		if ($_POST[$current_location.'_used_large_photo_url']) { update_option($current_location.'_used_large_photo_url', $_POST[$current_location.'_used_large_photo_url']); }
		if ($_POST[$current_location.'_used_small_photo_url']) { update_option($current_location.'_used_small_photo_url', $_POST[$current_location.'_used_small_photo_url']); }
		if ($_POST[$current_location.'_no_new_price']) { update_option($current_location.'_no_new_price', $_POST[$current_location.'_no_new_price']); }
		if ($_POST[$current_location.'_no_used_price']) { update_option($current_location.'_no_used_price', $_POST[$current_location.'_no_used_price']); }
		$car_demon_settings_hook = apply_filters('car_demon_admin_update_hook', $holder, $current_location);
	}
}

function car_demon_options() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('admin/','',$car_demon_pluginpath);
	$default = array();
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
	$car_demon_options = array();
	$car_demon_options = get_option( 'car_demon_options', $default );
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
	return $car_demon_options;
}

function car_demon_settings_options_do_page() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div><h1>'.__('Car Demon Settings', 'car-demon').'</h1>';
	if (isset($_POST['reset_car_demon'])) {
		reset_car_demon();
	}
	else {
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
		echo '<br />'.__('Include ADFxml with Leads?', 'car-demon').':<br />';
		echo '<select name="adfxml">
				<option value="'.$car_demon_options['adfxml'].'">'.$car_demon_options['adfxml'].'</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select><br />';
		echo '<br />'.__('Use About Tab on Vehicle Pages', 'car-demon').':<br />';
		echo '<select name="use_about">
				<option value="'.$car_demon_options['use_about'].'">'.$car_demon_options['use_about'].'</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select><br />';
		echo '<br />'.__('Use Compare Vehicle Option', 'car-demon').':<br />';
		echo '<select name="use_compare">
				<option value="'.$car_demon_options['use_compare'].'">'.$car_demon_options['use_compare'].'</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select><br />';
		echo '<br />'.__('Load Next Inventory Page on Scroll', 'car-demon').':<br />';
		echo '<select name="dynamic_load">
				<option value="'.$car_demon_options['dynamic_load'].'">'.$car_demon_options['dynamic_load'].'</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select><br />';
		echo '<br />'.__('Use included theme files?', 'car-demon-theme').':<br />';
		echo '<select name="use_theme_files">
			<option value="'.$car_demon_options['use_theme_files'].'">'.$car_demon_options['use_theme_files'].'</option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
			</select><br />';
		echo '<br />'.__('Is Mobile Theme Installed?', 'car-demon-theme').':<br />';
		echo '<select name="mobile_theme">
			<option value="'.$car_demon_options['mobile_theme'].'">'.$car_demon_options['mobile_theme'].'</option>
			<option value="Yes">Yes</option>
			<option value="No">No</option>
			</select><br />';
		if ($car_demon_options['mobile_theme'] == 'Yes') {
			echo '<br />'.__('Use Default Mobile Header with Mobile Logo?', 'car-demon-theme').':<br />';
			echo '<select name="mobile_header">
				<option value="'.$car_demon_options['mobile_header'].'">'.$car_demon_options['mobile_header'].'</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
				</select><br />';
			echo '<br />'.__('Mobile Header Logo', 'car-demon-theme').'<br />';
			echo '<table><tr valign="top">
				<td><label for="upload_mobile_logo">
				<input name="mobile_logo" id="mobile_logo" type="text" size="36" value="'.$car_demon_options['mobile_logo'].'" />
				<input id="upload_mobile_logo_button" type="button" value="'.__('Upload Logo', 'car-demon-theme').'" />
				<br />'.__('Enter a URL or upload an image for the Mobile Logo. 169x58 px', 'car-demon-theme').'
				</label></td>
				</tr></table>';
			echo '<br />'.__('Mobile Chat Code', 'car-demon-theme').'<br />';
			echo '<textarea name="mobile_chat_code" rows="5" cols="60">'.$car_demon_options['mobile_chat_code'].'</textarea><br />';
		}
		echo '<p><input type="submit" value="Update Car Demon" />';
		echo '<input type="submit" name="reset_car_demon" value="Reset to Default" /></p>';
	echo '</form>';
	echo '<hr />';
	echo '<h2>Car Demon Pages</h2>';
	echo '<div style="width:500px;">';
		echo '<p>Car Demon comes with support for adding several custom forms and pages. You can quickly and easily add these pages by clicking the buttons below or you can create your own pages and simply paste the shortcode you want to use into the content of the page.</p>';
	echo '</div>';
	if (isset($_POST['add_page'])) {
		$title = $_POST['title'];
		$type = $_POST['type'];
		$new_page_id = car_demon_add_page($title, $type);
		$link = get_permalink($new_page_id);
		echo '<a href="'.$link.'" target="_blank">New '.$title.' Page</a>';
	}
	echo '<div style="float:left;width:300px;">';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="contact" />';
			echo '<input type="hidden" name="title" value="Contact Us" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Contact Us Page" />';
		echo '</form>';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="service_appointment" />';
			echo '<input type="hidden" name="title" value="Service Appointment" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Service Appointment Page" />';
		echo '</form>';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="service_quote" />';
			echo '<input type="hidden" name="title" value="Service Quote" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Service Quote Page" />';
		echo '</form>';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="parts" />';
			echo '<input type="hidden" name="title" value="Parts Quote" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Parts Quote Page" />';
		echo '</form>';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="trade" />';
			echo '<input type="hidden" name="title" value="Trade In" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Trade In Page" />';
		echo '</form>';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="finance" />';
			echo '<input type="hidden" name="title" value="Finance Application" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Finanace Application Page" />';
		echo '</form>';
		echo '<form method="POST" action="">';
			echo '<input type="hidden" name="type" value="staff" />';
			echo '<input type="hidden" name="title" value="Staff Page" />';
			echo '<input type="submit" style="width:200px;cursor:pointer;" name="add_page" value="Add Staff Page" />';
		echo '</form>';
	echo '</div>';
	echo '<div style="float:left;width:300px;margin-left:10px;">';
		echo '<h3 style="margin-top:0px;">Shortcodes</h3>';
		echo '[-contact_us-]<br />';
		echo '[-service_form-]<br />';
		echo '[-service_quote-]<br />';
		echo '[-part_request-]<br />';
		echo '[-trade-]<br />';
		echo '[-finance_form-]<br />';
		echo '[-staff_page-]<br />';
	echo '</div></div>';
}

function update_car_demon_settings() {
	$new = array();
	if (isset($_POST['vinquery_id'])) $new['vinquery_id'] = $_POST['vinquery_id'];
	if (isset($_POST['vinquery_type'])) $new['vinquery_type'] = $_POST['vinquery_type'];
	if (isset($_POST['use_about'])) $new['use_about'] = $_POST['use_about'];
	if (isset($_POST['adfxml'])) $new['adfxml'] = $_POST['adfxml'];
	if (isset($_POST['use_compare'])) $new['use_compare'] = $_POST['use_compare'];
	if (isset($_POST['secure_finance'])) $new['secure_finance'] = $_POST['secure_finance'];
	if (isset($_POST['use_theme_files'])) $new['use_theme_files'] = $_POST['use_theme_files'];
	if (isset($_POST['dynamic_load'])) $new['dynamic_load'] = $_POST['dynamic_load'];
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
	update_option( 'car_demon_options', $new );
	echo '<h3 style="color:#FF0000;">'.__('SETTINGS HAVE BEEN UPDATED', 'car-demon').'</h3>';
}

function reset_car_demon() {
	delete_option('car_demon_options');
	echo '<h3 style="color:#FF0000;">'.__('SETTINGS HAVE BEEN RESET', 'car-demon').'</h3>';
}

function get_default_finance_description() {
	$x = '<span>This is not a contract to purchase. It is an application for financing a possible automotive purchase. <br />
		  <strong> You are not obligated to purchase</strong> a vehicle if you submit this form. </span>
		  <br />
		  <span>Your information is kept confidential and is used only to assist in obtaining financing for a potential vehicle purchase.<br />
		  *By clicking this button you agree to the terms  posted above.
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