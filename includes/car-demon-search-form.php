<?php
function vehicle_search_box($button, $message) {
	$url = get_option('siteurl');
	$box = '<form action="'.$url.'" method="get" class="vehicle_search_box" id="vehicle_search_box" name="vehicle_search_box" />';
		$box .= '<input type="hidden" name="s" value="cars" />';
		$box .= '<input type="hidden" name="car" value="1" />';
		$box .= '<span id="criteria_message">'.$message.'</span>';
		$box .= '<input type="text" name="criteria" class="search_criteria" value="" />';
		$box .= '<input type="submit" name="submit_search" id="submit_search" value="'.$button.'" class="search_btn advanced_btn criteria_btn">';
	$box .= '</form>';
	return $box;
}
function car_demon_search_form() {
	$car_demon_pluginpath = CAR_DEMON_PATH;
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
		<div id="car-demon-searchr2c1" class="search_header_logo"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/search_cars.gif" alt="Search Cars" title="Search Cars" width="20" height="23" />&nbsp;<?php _e('QUICK SEARCH', 'car-demon'); ?>
<?php
	echo '<div class="advanced_search_btn" onclick="document.getElementById(\'advanced_search\').style.display=\'inline\';" title="Advanced Search">+</div></div>';
	echo '<div class="advanced_search" id="advanced_search">Stock #: <input class="search_dropdown_sm" type="text" name="stock" id="stock" size="6" />
	&nbsp;<span class="advanced_search_btn_hide" onclick="document.getElementById(\'advanced_search\').style.display=\'none\';" title="Hide Advanced Search">-</span>
	</div>';
?>
			<div class="search_left">
				<div id="car-demon-searchr3c1" class=""><?php _e('Condition','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_condition(); ?></div>
			</div>
			<div class="search_right">
				<div id="car-demon-searchr3c1" class=""><?php _e('Manufacturer','car-demon'); ?>:</div>
				<div id="car-demon-searchr4c1" class=""><?php echo car_demon_search_makes();?></div>
			</div>
			<div class="search_left">
				<div class="search_labels"><?php _e('Year','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_years(); ?></div>
			</div>
			<div class="search_right">
				<div class=""><?php _e('Model','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_models();?></div>
			</div>
			<div id="car-demon-searchr6c1" class="search_min_price">
				<div class="search_labels"><?php _e('Min Price','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_price('Min'); ?></div>
			</div>
			<div id="car-demon-searchr6c2" class="search_max_price">
				<div class="search_labels"><?php _e('Max Price','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_price('Max'); ?></div>
			</div>
			<div id="car-demon-searchr7c1" class="search_trans">
				<div class="search_labels"><?php _e('Trans','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_tran(); ?></div>
			</div>
			
			<div id="car-demon-searchr7c2" class="search_mileage">
				<div class="search_labels"><?php _e('Mileage','car-demon'); ?>:</div>
				<div><?php echo car_demon_search_miles(); ?></div>
			</div>
			<div id="car-demon-searchr8c1" class="search_body">
				<div class="search_labels"><?php _e('Body Type','car-demon'); ?>:</div>
				<div>
					<?php echo car_demon_search_body(); ?>
				</div>
			</div>
			<div id="car-demon-searchr8c2" class="search_button_box">
			  <input type="submit" name="submit_search" id="submit_search" value="<?php _e('Search','car-demon'); ?>" class="search_btn advanced_btn">
			</div>
		<div id="car-demon-searchr9c1" class="search_footer"></div>
</form>
	</div>
</div>
<?php
}
function car_demon_simple_search($size='l') {
	$car_demon_auto_credit_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('car-demon-auto-credit','car-demon', $car_demon_auto_credit_pluginpath);
	car_demon_search_cars_scripts();
	$url = get_option('siteurl');
	if ($size == 's') {
		$form_size = "search_car_box_frame_narrow";
		$logo = "search_header_logo_simple";
	} else {
		$form_size = "search_car_box_frame_wide";
		$logo = "search_header_logo_simple_wide";
	}
	if (isset($_GET['search_condition'])) {
		$search_condition = $_GET['search_condition'];
	} else {
		$search_condition = '';
	}
?>
<div class="<?php echo $form_size; ?>">
	<div id="car-demon-search-cars_sm" class="search_car_box_sm">
<form action="<?php echo $url ?>" method="get" />
<input type="hidden" name="s" value="cars" />
<input type="hidden" name="car" value="1" />
<input type="hidden" name="search_condition" value="<?php echo $search_condition;?>" />
		<div id="car-demon-searchr2c1" class="<?php echo $logo; ?>"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/search_cars.gif" alt="Search Cars" width="20" height="23" title="Search Cars" />&nbsp;<?php _e('QUICK SEARCH','car-demon'); ?></div>
		<div class="search_left">
			<div id="car-demon-searchr3c1" class="search_manufacturer_title2"><?php _e('Year','car-demon'); ?>:</div>
			<div class="search_year_dropdown"><?php echo car_demon_search_years(); ?></div>
		</div>
		<div class="search_right">
			<div id="car-demon-searchr3c1" class=""><?php _e('Manufacturer','car-demon'); ?>:</div>
			<div id="car-demon-searchr4c1" class=""><?php echo car_demon_search_makes();?></div>
		</div>
		<div class="search_right">
			<div id="car-demon-searchr3c1" class=""><?php _e('Model','car-demon'); ?>:</div>
			<div id="car-demon-searchr5c1" class=""><?php echo car_demon_search_models();?></div>
		</div>
		<div id="car-demon-searchr8c1" class="search_body"><?php _e('Body Type','car-demon'); ?>:<br /><?php echo car_demon_search_body(); ?></div>
		<div id="car-demon-searchr8c2" class="search_button_box">
<?php
	echo '<div class="advanced_search_btn" onclick="document.getElementById(\'advanced_search\').style.display=\'inline\';" title="Advanced Search">+</div>';
	echo '<div class="advanced_search" id="advanced_search">Stock #: <input class="search_dropdown_sm" type="text" name="stock" id="stock" size="6" />
	&nbsp;<span class="advanced_search_btn_hide" onclick="document.getElementById(\'advanced_search\').style.display=\'none\';" title="Hide Advanced Search">-</span>
	</div>';
?>
		  <input type="submit" name="submit_search" id="submit_search" value="<?php _e('Search','car-demon'); ?>" class="search_btn simple_btn">
		</div>
</form>
	</div>
</div>
<?php
}
function car_demon_get_searched_by() {
	$searched = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$query_string = str_replace('%2C', ',', $query_string);
	if (isset($_GET['search_condition'])) {
		if ($_GET['search_condition']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_condition\', \''.$_GET['search_condition'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Condition:</span> ';
			$searched .= $_GET['search_condition'] .', ';
		}
	}
	if (isset($_GET['search_year'])) {
		if ($_GET['search_year']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_year\', \''.$_GET['search_year'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Year:</span> ';
			$searched .= $_GET['search_year'];
		}
	}
	if (isset($_GET['search_make'])) {
		if ($_GET['search_make']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_make\', \''.$_GET['search_make'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Make:</span> ';
			$search_make_array = $_GET['search_make'];
			$search_make_array = explode(',', $search_make_array);
			$search_make = $search_make_array[1];
			$searched .= $search_make .', ';
		}
	}
	if (isset($_GET['search_model'])) {
		if ($_GET['search_model']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_model\', \''.$_GET['search_model'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Model:</span> ';
			$searched .= $_GET['search_model'] .', ';
		}
	}
	if (isset($_GET['search_dropdown_Min_price'])) {
		if ($_GET['search_dropdown_Min_price']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Min_price\', \''.$_GET['search_dropdown_Min_price'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Min Price:</span> ';
			$searched .= $_GET['search_dropdown_Min_price'] .', ';
		}
	}
	if (isset($_GET['search_dropdown_Max_price'])) {
		if ($_GET['search_dropdown_Max_price']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_Max_price\', \''.$_GET['search_dropdown_Max_price'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Max Price:</span> ';
			$searched .= $_GET['search_dropdown_Max_price'] .', ';
		}
	}
	if (isset($_GET['search_dropdown_tran'])) {
		if ($_GET['search_dropdown_tran']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_tran\', \''.$_GET['search_dropdown_tran'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Transmission:</span> ';
			$searched .= $_GET['search_dropdown_tran'] .', ';
		}
	}
	if (isset($_GET['search_dropdown_miles'])) {
		if ($_GET['search_dropdown_miles']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_miles\', \''.$_GET['search_dropdown_miles'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Miles:</span> ';
			$searched .= $_GET['search_dropdown_miles'] .', ';
		}
	}
	if (isset($_GET['search_dropdown_body'])) {
		if ($_GET['search_dropdown_body']) {
			$searched .= '<span class="remove_search" onclick="remove_search(\'search_dropdown_body\', \''.$_GET['search_dropdown_body'].'\', \''.$query_string.'\');">x</span> <span class="remove_search_title">Body Style:</span> ';
			$searched .= $_GET['search_dropdown_body'] .', ';
		}
	}
	$searched .= '@@';
	$searched = str_replace(', @@', '', $searched);
	$searched = str_replace('@@', '', $searched);
	if (!empty($searched)) {
		$searched = '<div class="searched_by">'.$searched.'</div>';
	}
	return $searched;
}
?>