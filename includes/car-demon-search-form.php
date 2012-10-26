<?php
function car_demon_search_form() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	car_demon_search_cars_scripts();
	$url = get_option('siteurl');
?>
<div class="search_car_box_frame">
	<div id="car-demon-search-cars" class="search_car_box">
<form action="<?php echo $url ?>" method="get" />
<input type="hidden" name="s" value="cars" />
<input type="hidden" name="car" value="1" />
		<div id="car-demon-searchr1c1" class="search_header"></div>
		<div id="car-demon-searchr2c1" class="search_header_logo"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/search_cars.png" alt="Search Cars" title="Search Cars" />&nbsp;QUICK SEARCH
<?php
	echo '&nbsp;<span style="cursor:pointer;color:#00CC00;" onclick="document.getElementById(\'advanced_search\').style.display=\'inline\';" title="Advanced Search">+</span></div>';
	echo '<div style="display:none;width:250px;float:left;height:24px;" id="advanced_search">Stock #: <input class="search_dropdown_sm" type="text" name="stock" id="stock" size="6" />
	&nbsp;<span style="cursor:pointer;color:#FF0000;font-weight:bold;font-size:24px;" onclick="document.getElementById(\'advanced_search\').style.display=\'none\';" title="Hide Advanced Search">-</span>
			</div>';
?>
		<div>
			<div style="float:left;width:85px;">
				<div id="car-demon-searchr3c1" class=""><?php _e('Condition','car-demon'); ?>:</div>
				<div><?php echo car_demon_select_condition(); ?></div>
			</div>
			<div style="float:left;">
				<div id="car-demon-searchr3c1" class=""><?php _e('Manufacturer','car-demon'); ?>:</div>
				<div id="car-demon-searchr4c1" class=""><?php echo car_demon_search_makes();?></div>
			</div>
		</div>
		<div>
			<div style="float:left;width:85px;">
				<div id="car-demon-searchr3c1" class="search_manufacturer_title2"><?php _e('Year','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_years(); ?></div>
			</div>
			<div style="float:left;">
				<div id="car-demon-searchr3c1" class=""><?php _e('Model','car-demon'); ?>:</div>
				<div id="car-demon-searchr5c1" class=""><?php echo car_demon_search_models();?></div>
			</div>
		</div>
		<div id="car-demon-searchr6c1" class="search_min_price"><?php _e('Min Price','car-demon'); ?>:<br /><?php echo car_demon_search_price('Min'); ?></div>
		<div id="car-demon-searchr6c2" class="search_max_price"><?php _e('Max Price','car-demon'); ?>:<br /><?php echo car_demon_search_price('Max'); ?></div>
		<div id="car-demon-searchr7c1" class="search_trans"><?php _e('Trans','car-demon'); ?>:<br /><?php echo car_demon_search_tran(); ?></div>
		<div id="car-demon-searchr7c2" class="search_mileage"><?php _e('Mileage','car-demon'); ?>:<br /><?php echo car_demon_search_miles(); ?></div>
		<div id="car-demon-searchr8c1" class="search_body"><?php _e('Body Type','car-demon'); ?>:<br /><?php echo car_demon_search_body(); ?></div>
		<div id="car-demon-searchr8c2" class="search_button_box" style="margin-top:20px;">
		  <input type="submit" name="submit_search" id="submit_search" value="<?php _e('Search','car-demon'); ?>" class="search_btn">
		</div>
		<div id="car-demon-searchr9c1" class="search_footer"></div>
</form>
	</div>
</div>
<?php
}

function car_demon_simple_search($size='l') {
	$car_demon_auto_credit_themepath = str_replace(str_replace('\\', '/', ABSPATH), $url = get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_auto_credit_themepath = str_replace('includes','',$car_demon_auto_credit_themepath);
	$car_demon_themepath = str_replace('car-demon-auto-credit','car-demon', $car_demon_auto_credit_themepath);
	car_demon_search_cars_scripts();
	$url = get_option('siteurl');
	if ($size == 's') {
		$form_size = "width:230px;height:230px;margin-left:auto;margin-right:auto;";
	}
	else {
		$form_size = "width:980px;height:70px;margin-left:auto;margin-right:auto;";
	}
?>
<div class="search_car_box_frame_sm" style="<?php echo $form_size; ?>">
	<div id="car-demon-search-cars_sm" class="search_car_box_sm">
<form action="<?php echo $url ?>" method="get" />
<input type="hidden" name="s" value="cars" />
<input type="hidden" name="car" value="1" />
		<div id="car-demon-searchr2c1" class="search_header_logo" style="margin-left:60px !important; margin-top:10px !important; margin-right:-60px !important;"><img src="<?php echo $car_demon_themepath; ?>theme-files/images/search_cars.png" alt="Search Cars" title="Search Cars" />&nbsp;<?php _e('QUICK SEARCH','car-demon'); ?></div>
		<div style="float:left;width:85px;">
			<div id="car-demon-searchr3c1" class="search_manufacturer_title2"><?php _e('Year','car-demon'); ?>:</div>
			<div><?php echo car_demon_select_years(); ?></div>
		</div>
		<div style="float:left;">
			<div id="car-demon-searchr3c1" class=""><?php _e('Manufacturer','car-demon'); ?>:</div>
			<div id="car-demon-searchr4c1" class=""><?php echo car_demon_search_makes();?></div>
		</div>
		<div style="float:left;">
			<div id="car-demon-searchr3c1" class=""><?php _e('Model','car-demon'); ?>:</div>
			<div id="car-demon-searchr5c1" class=""><?php echo car_demon_search_models();?></div>
		</div>
		<div id="car-demon-searchr8c1" class="search_body"><?php _e('Body Type','car-demon'); ?>:<br /><?php echo car_demon_search_body(); ?></div>
		<div id="car-demon-searchr8c2" class="search_button_box" style="margin-top:20px;">
		  <input type="submit" name="submit_search" id="submit_search" value="<?php _e('Search','car-demon'); ?>" class="search_btn">
		</div>
</form>
	</div>
</div>
<?php
}

function car_demon_select_condition() {
	$select_new = '';
	$select_used = '';
	if (isset($_GET['search_condition'])) {
		$search_condition = $_GET['search_condition'];
	}
	else {
		$search_condition = '';
	}
	if ($search_condition == 'Preowned') {
		$select_used = ' selected';
	}
	elseif ($search_condition == 'New') {
		$select_new = ' selected';
	}
	$x = '<select id="search_condition" name="search_condition">
			<option value="">Any</option>
			<option value="Preowned"'.$select_used.'>Used</option>
			<option value="New"'.$select_new.'>New</option>	
		</select>';
	return $x;
}

function car_demon_select_years() {
	$taxonomies = array('vehicle_year');
	$args = array('orderby'=>'name','hide_empty'=>true);
	$x = car_demon_get_years_dropdown($taxonomies, $args);
	return $x;
}

function car_demon_get_years_dropdown($taxonomies, $args) {
	$current_year = $_GET['search_year'];
	$myterms = get_terms($taxonomies, $args);
	$output = '<select id="search_year" name="search_year" class="">
			<option value="">Any</option>';
	$selected = '';
	foreach($myterms as $term){
		$root_url = home_url();
		$term_taxonomy=$term->taxonomy;
		$term_slug=$term->slug;
		$term_name=$term->name;
		if ($term_slug == $current_year) {$selected = ' selected';} else {$selected = '';}
		$link = $root_url.'/'.$term_taxonomy.'/'.$term_slug;
		$output .="<option value='".$term_slug."'".$selected.">".$term_name."</option>";
	}
	$output .="</select>";
	return $output;
}

function car_demon_search_price($size) {
	if (isset($_GET['search_dropdown_'.$size.'_price'])) {
		$price = $_GET['search_dropdown_'.$size.'_price'];
	}
	else {
		$price = '';
	}
	$x = '<select id="search_dropdown_'.$size.'_price" name="search_dropdown_'.$size.'_price" class="search_dropdown_sm">';
		$x .= '<option value="0">No '.$size.'</option>';
		if ($price == '1000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="1000"'.$select.'>$1,000</option>';
		if ($price == '10000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="10000"'.$select.'>$10,000</option>';
		if ($price == '15000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="15000"'.$select.'>$15,000</option>';
		if ($price == '20000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="20000"'.$select.'>$20,000</option>';
		if ($price == '30000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="30000"'.$select.'>$30,000</option>';
		if ($price == '40000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="40000"'.$select.'>$40,000</option>';
		if ($price == '50000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="50000"'.$select.'>$50,000</option>';
		if ($price == '75000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="75000"'.$select.'>$75,000</option>';
		if ($price == '100000') {$select = ' selected';} else {$select = '';}
			$x .= '<option value="100000"'.$select.'>$100,000</option>';		
	$x .= '</select>';
	return $x;
}

function car_demon_search_tran() {
	global $wpdb;
	if (isset($_GET['search_dropdown_tran'])) {
		$current_trans = $_GET['search_dropdown_tran'];
	}
	else {
		$current_trans = '';
	}
	$prefix = $wpdb->prefix;
	$sql = 'SELECT DISTINCT meta_value from '.$prefix.'postmeta WHERE meta_key="_transmission_value"';
	$trans = $wpdb->get_results($sql);
	$x = '<select id="search_dropdown_tran" name="search_dropdown_tran" class="search_dropdown_sm">';
		$x .= '<option value="">Any</option>';
		if (!empty($trans)) {
			foreach($trans as $tran) {
				if (!empty($tran->meta_value)) {
					if ($current_trans == $tran->meta_value) {$select = ' selected';} else {$select = '';}
					$x .='<option value="'.$tran->meta_value.'"'.$select.'>'.$tran->meta_value.'</option>';
				}
			}
		}
	$x .= '</select>';
	return $x;
}

function car_demon_search_miles() {
	if (isset($_GET['search_dropdown_miles'])) {
		$miles = $_GET['search_dropdown_miles'];
	}
	else {
		$miles = '';
	}
	$x = '<select id="search_dropdown_miles" name="search_dropdown_miles" class="search_dropdown_sm">';
	if ($miles == '1000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value=""'.$select.'>Any</option>';
	if ($miles == '5000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="5000"'.$select.'>< 5,000</option>';
	if ($miles == '10000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="10000"'.$select.'>< 10,000</option>';
	if ($miles == '20000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="20000"'.$select.'>< 20,000</option>';
	if ($miles == '30000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="30000"'.$select.'>< 30,000</option>';
	if ($miles == '40000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="40000"'.$select.'>< 40,000</option>';
	if ($miles == '50000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="50000"'.$select.'>< 50,000</option>';
	if ($miles == '60000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="60000"'.$select.'>< 60,000</option>';
	if ($miles == '70000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="70000"'.$select.'>< 70,000</option>';
	if ($miles == '80000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="80000"'.$select.'>< 80,000</option>';
	if ($miles == '90000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="90000"'.$select.'>< 90,000</option>';
	if ($miles == '100000') {$select = ' selected';} else {$select = '';}
		$x .= '<option value="100000"'.$select.'>< 100,000</option>';
		$x .='</select>';
	return $x;
}

function car_demon_search_body() {
	$taxonomies = array('vehicle_body_style');
	$args = array('orderby'=>'count','hide_empty'=>true);
	$x = car_demon_get_terms_dropdown($taxonomies, $args);
	return $x;
}

function car_demon_get_terms_dropdown($taxonomies, $args){
	if (isset($_GET['search_dropdown_body'])) {
		$body = $_GET['search_dropdown_body'];
	}
	else {
		$body = '';
	}
	$myterms = get_terms($taxonomies, $args);
	$output = '<select id="search_dropdown_body" name="search_dropdown_body" class="search_dropdown_sm">
			<option value="">Any</option>';
	foreach($myterms as $term){
		$root_url = home_url();
		$term_taxonomy=$term->taxonomy;
		$term_slug=$term->slug;
		$term_name =$term->name;
		$link = $root_url.'/'.$term_taxonomy.'/'.$term_slug;
		if ($term_slug == $body) {$selected = ' selected';} else {$selected = '';}
		$output .="<option value='".$term_slug."'".$selected.">".$term_name."</option>";
	}
	$output .="</select>";
return $output;
}

function car_demon_get_searched_by() {
	$searched = '';
	if ($_GET['search_condition']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_condition\', \''.$_GET['search_condition'].'\');">x</span> <span class="remove_search_title">Condition:</span> ';
		$searched .= $_GET['search_condition'] .', ';
	}
	if ($_GET['search_year']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_year\', \''.$_GET['search_year'].'\');">x</span> <span class="remove_search_title">Year:</span> ';
		$searched .= $_GET['search_year'];
	}
	if ($_GET['search_make']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_make\', \''.$_GET['search_make'].'\');">x</span> <span class="remove_search_title">Make:</span> ';
		$search_make_array = $_GET['search_make'];
		$search_make_array = explode(',', $search_make_array);
		$search_make = $search_make_array[1];
		$searched .= $search_make .', ';
	}
	if (isset($_GET['search_model'])) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_model\', \''.$_GET['search_model'].'\');">x</span> <span class="remove_search_title">Model:</span> ';
		$searched .= $_GET['search_model'] .', ';
	}
	if ($_GET['search_dropdown_Min_price']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Min_price\', \''.$_GET['search_dropdown_Min_price'].'\');">x</span> <span class="remove_search_title">Min Price:</span> ';
		$searched .= $_GET['search_dropdown_Min_price'] .', ';
	}
	if ($_GET['search_dropdown_Max_price']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Max_price\', \''.$_GET['search_dropdown_Max_price'].'\');">x</span> <span class="remove_search_title">Max Price:</span> ';
		$searched .= $_GET['search_dropdown_Max_price'] .', ';
	}
	if ($_GET['search_dropdown_tran']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_tran\', \''.$_GET['search_dropdown_tran'].'\');">x</span> <span class="remove_search_title">Transmission:</span> ';
		$searched .= $_GET['search_dropdown_tran'] .', ';
	}
	if ($_GET['search_dropdown_miles']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_miles\', \''.$_GET['search_dropdown_miles'].'\');">x</span> <span class="remove_search_title">Miles:</span> ';
		$searched .= $_GET['search_dropdown_miles'] .', ';
	}
	if ($_GET['search_dropdown_body']) {
		$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_body\', \''.$_GET['search_dropdown_body'].'\');">x</span> <span class="remove_search_title">Body Style:</span> ';
		$searched .= $_GET['search_dropdown_body'] .', ';
	}
	$searched .= '@@';
	$searched = str_replace(', @@', '', $searched);
	$searched = str_replace('@@', '', $searched);
	if (!empty($searched)) {
		$searched = '<div style="width:600px;min-height:20px;margin-left:15px;">'.$searched.'</div>';
		$query_string = $_SERVER['QUERY_STRING'];
		$query_string = str_replace('%2C', ',', $query_string);
		$wpurl = site_url();
		$searched .= '
			<script>
				function remove_search(fld, val) {
					var query_string = "'.$query_string.'";
					remove_this = "&"+fld+"="+val;
					var reg = new RegExp(remove_this,"g");
					var query_string = query_string.replace(reg, "");
					window.location = "'.$wpurl.'?"+query_string;
				}
			</script>
		';
	}
	return $searched;
}
?>