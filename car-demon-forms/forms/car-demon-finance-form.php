<?php
function car_demon_finance_form($location) {
	$x = '';
	show_finance_form($location);
	return $x;
}
function show_finance_form($location) {
	$stock_num = '';
	$vin = '';
	$location = '';
	$bad = '';
	$fin = '';
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
//		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			echo '<style>';
				include('css/car-demon-finance.css');
			echo '</style>';
//		}
	} else {
		echo '<style>';
			include('css/car-demon-finance.css');
		echo '</style>';	
	}
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('/car-demon-forms/forms', '', $car_demon_pluginpath);
	if ($_SESSION['car_demon_options']['secure_finance'] == 'Yes') {
		if ( empty( $_SERVER['HTTPS'] ) ) {
			$bad = '<p align="center" class="finance_alert">'.__('SOMETHING WENT WRONG! THIS PAGE IS NOT SECURE!', 'car-demon').'</p>';
			$bad .= '<p align="center" class="finance_alert">'.__('THIS FORM HAS BEEN DISABLED FOR YOUR PROTECTION.', 'car-demon').'</p>';
			$bad .= '<p align="center" class="finance_alert">'.__('PLEASE CONTACT THE SITE ADMINISTRATOR FOR MORE INFORMATION.', 'car-demon').'</p>';
			echo $bad;
		}
	}
?>
	<div id="form_results"></div>
	<div class="body_content_credit" id="body_content_credit">
		<form name="frm_app" id="frm_app" action="CreditApp_ex.asp" method="post" class="cdform finance_form">
			<input type="hidden" name="stock_num" value="<?php echo $stock_num; ?>" />
			<input type="hidden" name="vin" value="<?php echo $vin; ?>" />
			<input type="hidden" name="location" value="<?php echo $location; ?>" />
			<?php 
			?>
			<div class="finance_segment">
				<?php 
				include('finance/personal_info.php');
				include('finance/address.php'); 
				?>
			</div>
			<div class="finance_segment">
				<?php 
				include('finance/employer.php');
				include('finance/employer_previous.php');
				include('finance/living_situation.php');
				include('finance/living_situation_previous.php');
				 ?>
			</div>
			<div class="finance_segment_wide">
				<!--// IS THERE A CO-APP //-->
				<div class="have_cosigner">
					<?php _e('Do you have a Co-Signer', 'car-demon'); ?>
				</div>
				<div class="cosigner_question">
					<select name="MakeCoSigner" class="make_co_signer" onChange="MakeTheCoSigner(this);" tabindex="47">
						<option value="<?php _e('No', 'car-demon'); ?>"><?php _e('No', 'car-demon'); ?></option>
						<option value="<?php _e('Yes', 'car-demon'); ?>"><?php _e('Yes', 'car-demon'); ?></option>
					</select>
				</div>
				<!--// END IS THERE A CO-APP //-->
			</div>
			<!--// START CO-APP //-->
			<div id="CoSignerDiv" class="finance_hide_notice finance_segment_wide">
				<div class="cosigner_block">
					<div class="cosigner_title">
						<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/expand.gif" alt="<?php _e('Enter Co-Signer', 'car-demon'); ?>" width="9" height="9" /> <?php _e('Co-Signer', 'car-demon'); ?>
					</div>
				</div>
				<div class="finance_segment">
					<?php 
					include('finance/co_personal_info.php');
					include('finance/co_address.php'); 
					?>
				</div>
				<div class="finance_segment">
					<?php 
					include('finance/co_employer.php');
					include('finance/co_employer_previous.php');
					include('finance/co_living_situation.php');
					include('finance/co_living_situation_previous.php');
					 ?>
				</div>
			</div>
			<!--// END CO-APP //-->
			<div class="finance_segment_wide">
				<?php include('finance/disclosures.php'); ?>
			</div>
			<div class="finance_segment_wide">
				<div align="center">
					<p><?php echo $finance_description; ?>
					<br /><?php echo $bad; ?><br />
					<?php $fin = apply_filters('car_demon_mail_hook_form', $fin, 'finance', 'unk');
					echo $fin;
					?>
					<input type="button" onClick="mainValidation();" name="sbtValidate" id="sbtValidate" tabindex="97" value="Submit This Credit Application" class="search_btn finance_btn" /><br />
					<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/secure.gif" alt="Secure Encryption" width="30" height="49" /></p>
				</div>
			</div>
		</form>
	</div>
<?php
	if ( !empty($bad) ) {
		echo '
			<script>
				document.frm_app.sbtValidate.disabled=true;
				document.frm_app.fn.disabled=true;
				document.frm_app.mi.disabled=true;
				document.frm_app.ln.disabled=true;
				document.frm_app.hpn.disabled=true;
				document.frm_app.ea.disabled=true;
				document.frm_app.ssn.disabled=true;
				document.frm_app.app_apt_num.disabled=true;
				document.frm_app.app_street_num.disabled=true;
				document.frm_app.app_street_name.disabled=true;
				document.frm_app.app_street_type.disabled=true;
				document.frm_app.app_po_box_num.disabled=true;
				document.frm_app.app_rural_route.disabled=true;
				document.frm_app.cty.disabled=true;
				document.frm_app.st.disabled=true;
				document.frm_app.zi.disabled=true;
				document.frm_app.bdm.disabled=true;
				document.frm_app.bdy.disabled=true;
				document.frm_app.bdd.disabled=true;
				document.frm_app.en.disabled=true;
				document.frm_app.p.disabled=true;
				document.frm_app.yac.disabled=true;
				document.frm_app.mac.disabled=true;
				document.frm_app.epn.disabled=true;
				document.frm_app.gmi.disabled=true;
				document.frm_app.oi.disabled=true;
				document.frm_app.yaca.disabled=true;
				document.frm_app.maca.disabled=true;
				document.frm_app.roo.disabled=true;
				document.frm_app.ramp.disabled=true;
				document.frm_app.bcp.disabled=true;
				document.frm_app.bct.disabled=true;
				document.frm_app.comment.disabled=true;
				document.frm_app.MakeCoSigner.disabled=true;
				document.frm_app.pick_voi[0].disabled=true;
				document.frm_app.pick_voi[1].disabled=true;
				document.frm_app.pick_voi[2].disabled=true;
				document.frm_app.finance_location[0].disabled=true;
				document.frm_app.finance_location[1].disabled=true;
				document.frm_app.txtDisclosure.disabled=true;
			</script>
		';
	}
	include('js/car-demon-finance-form-js.php');
}
function select_years() {
	$start = 0;
	$years = "<option></option>";
	do {
		$years .= "<option value='". $start ."'>". $start ."</option>";
		$start = $start + 1;
	} while ($start < 100);
	return $years;	
}
function get_the_days() {
	$start = 1;
	do {
		$days = "<option value='". $start ."'>". $start ."</option>";
		$start = $start + 1;
	} while ($start < 32);
	return $days;
}
function get_the_years() {
	$start = 0;
	$this_year = date("Y");
	$this_year = $this_year - 18;
	do {
		$years = "<option value='". $this_year ."'>". $this_year ."</option>";
		$start = $start + 1;
		$this_year = $this_year - 1;
	} while ($start < 100);
	return $years;
}
function get_finance_for_vehicle($stock_num) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value='".$stock_num."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
			$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
			$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
			$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
			$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
			$vehicle_body_style = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),0);
			$vehicle_photo = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
		}
	}
	$x = '
		<input type="hidden" name="purchase_stock" id="purchase_stock" value="'.$stock_num.'" />
		<ol class="cd-ol" id="show_voi">
			<li id="" class="cd-box-title">'.__('Vehicle of Interest', 'car-demon').'</li>
			<li id="not_voi" class="cd-box-title"><input type="checkbox" onclick="show_voi()" class="finance_checkbox not_my_car" />&nbsp;'.__('This is', 'car-demon').' '.__('NOT', 'car-demon').' '.__('the vehicle I\'m interested in.', 'car-demon').'</li>
			';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('Stock #', 'car-demon').'</span></label><label class="finance_label"><span class="finance_label">'.$stock_num.'</span></label></li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('VIN', 'car-demon').'</span></label><label class="finance_label"><span class="finance_label">'.$vehicle_vin.'</span></label></li>';
			$vehicle = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model .' '. $vehicle_body_style;
			$x .= '<li id="" class=""><label for="cd_field_2"><span>'.__('Vehicle', 'car-demon').'</span></label><label class="finance_label"><span class="finance_label">'.$vehicle.'</span></label></li>';
			$x .= '<li id="" class=""><img src="'.$vehicle_photo.'" width="300" class="random_widget_image finance_img" title="'.$vehicle.'" alt="'.$vehicle.'" /></li>';
			$x .= '
			</li>
		</ol>
	';
	return $x;
}
function select_finance_for_vehicle($hide=0) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath_images = str_replace('car-demon-forms/forms','',$car_demon_pluginpath);	
	if ($hide == 1) {
		$hidden = " finance_hidden";
	} else {
		$hidden = '';
	}
	$x = '
		<ol class="cd-ol'.$hidden.'" id="find_voi">
			<li id="voi_title" class="cd-box-title finance_box_title">'.__('What Vehicle are you Interested In Purchasing?', 'car-demon').'</li>
			<li id="" class="cd-box-title"><input onclick="select_voi(\'stock\');" name="pick_voi" id="pick_voi_1" type="radio" value="1" /> '.__('I know the stock#', 'car-demon').'</li>
			<li id="select_stock" class="finance_select_stock"><span>Stock #</span>&nbsp;<input class="ac_input" type="text" id="select_stock_txt" /></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_2" onclick="select_voi(\'search\');" type="radio" value="2" /> '.__('I would like to search for it', 'car-demon').'</li>
			<li id="select_description" class="finance_select_stock"><span>Description</span>&nbsp;<input type="text"  id="select_car_txt" /><span>&nbsp;'.__('(enter year, make or model)', 'car-demon').'</span></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_3" onclick="select_voi(\'na\');" type="radio" checked="checked" value="3" /> '.__('I haven\'t made up my mind.', 'car-demon').'</li>
			<li id="" class="cd-box-title">
				<img src="'.$car_demon_pluginpath_images.'theme-files/images/no_vehicle.gif" width="175" class="finance_no_img" />
			</li>
			<li id="li-7items" class="cd-box-group">
	';

	$x .= '
			</li>
		</ol>
	';
	return $x;
}
function finance_locations_radio() {
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
	$cnt = 0;
	$location_list = '';
	$location_name_list = '';
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
	if (empty($_GET['stock_num'])) {
		$hidden = "";	
	} else {
		$hidden = " finance_hidden";
	}
	$html = '
		<fieldset class="cd-fs2 finance_locations'.$hidden.'" id="finance_locations">
		<legend class="fin_legend">'.__('Finance Location', 'car-demon').'</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">'.__('Select your preferred Finance Location', 'car-demon').'</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_finance = " checked='checked'";
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_finance.' id="finance_location_'.$x.'" name="finance_location" value="'.get_option($current_location.'_finance_name').'" class="cd-box fldrequired"><span for="finance_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_finance_name').'</span></span>
			<br>
		';
	}
	$html .= '
			</li>
		</ol>
		</fieldset>
	';
	return $html;
}
function get_finance_location_name($selected_car) {
	global $wpdb;
	$x= '';
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value = '".$selected_car."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$location_name = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
			$terms = get_the_terms($post_id, 'vehicle_location');
			if ($terms) {
				foreach ($terms as $term) {
					if ($term->name == $location_name) {
						$x = $term->slug;
					}		
				}
			}
		}
	}
	return $x;
}
function get_this_dislaimer($stock_num) {
	if (empty($stock_num)) {
		$finance_disclaimer = get_option('default_finance_disclaimer');
	} else {
		$location_disclaimer = get_finance_location_name($stock_num);
		$finance_disclaimer = get_option($location_disclaimer.'_finance_disclaimer');
	}
	if (strlen($finance_disclaimer) < 2) {
		$finance_disclaimer = get_default_finance_disclaimer();
	}
	return $finance_disclaimer;
}
function get_finance_description($stock_num) {
	if (empty($stock_num)) {
		$finance_description = get_option('default_finance_description');
	} else {
		$location_description = get_finance_location_name($stock_num);
		$finance_description = get_option($location_description.'_finance_description');
	}
	if (strlen($finance_description) < 2) {
		$finance_description = get_default_finance_description();
	}
	return $finance_description;
}
?>