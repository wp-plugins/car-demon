<?php
function get_vehicle_price($post_id) {
	$is_sold = get_post_meta($post_id, 'sold', true);
	$spacer = '';
	$vehicle_condition = '';
	if ($is_sold == "Yes") {
		$sold = "<div style='margin-top:15px;font-size:42px;color:#FF0000;font-weight:bold;text-align:center;'>SOLD</div>";
		return $sold;
	}
	$vehicle_location = strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' ));
	if ($vehicle_location == '') {
		$vehicle_location = 'Default';
		$vehicle_location_slug = 'default';
	}
	else {
		$vehicle_location_term = get_term_by('name', $vehicle_location, 'vehicle_location');
		$vehicle_location_slug = $vehicle_location_term->slug;
		$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
	}
	if ($vehicle_condition == 'New') {
		$show_price = get_option($vehicle_location_slug.'_show_new_prices');
	}
	else {
		$show_price = get_option($vehicle_location_slug.'_show_used_prices');
	}
	$price = '';
	if ($show_price == 'Yes') {
		$vehicle_price = get_post_meta($post_id, "_price_value", true);
		$vehicle_price_pack = (int)$vehicle_price;
		if ($vehicle_price == 0) {
			$vehicle_price = ' Call $';
		}
		$selling_price = get_post_meta($post_id, "_msrp_value", true);
		$rebate = get_post_meta($post_id, "_rebates_value", true);
		$dealer_discount = get_post_meta($post_id, "_discount_value", true);
		$your_price = $vehicle_price;
		$spacer = "";
		if (!empty($selling_price)) {
			$price .= '<div id="selling_price" class="car_selling_price" style="font-size:16px; font-weight:bold"><div class="car_price_text" style="float: left;width: 65px;">$'. $selling_price .'</div> :'.__('Selling Price', 'car-demon').'</div>';
		}
		if (!empty($rebate)) {
			$price .= '<div id="rebate" class="car_rebate" style="font-size:16px; font-weight:bold"><div class="car_price_text" style="float: left;width: 65px;">$'. $rebate .'</div> :'.__('Rebate', 'car-demon').'</div>';
		}
		else {
			$spacer = '<div class="car_rebate" style="font-size:16px; font-weight:bold"><div class="car_price_text" style="float: left;width: 65px;">&nbsp;</div>&nbsp;</div>';
		}
		if (!empty($dealer_discount)){
			$price .= '<div class="car_dealer_discounts" style="font-size:16px; font-weight:bold; color:#FF0000;"><div class="car_price_text" style="float: left;width: 65px;">$'. $dealer_discount .'</div> :'.__('Xtra Discount', 'car-demon').'</div>';
		}
		else {
			$spacer = '<div class="car_rebate" style="font-size:16px; font-weight:bold"><div class="car_price_text" style="float: left;width: 65px;">&nbsp;</div>&nbsp;</div>';		
		}
		$price .= '<div id="your_price_text" class="car_your_price" style="font-size:20px; font-weight:bold; text-align:center;">'.__('YOUR PRICE', 'car-demon').':</div>';
		$price .= '<div id="your_price" class="car_final_price" style="font-size:28px; font-weight:bold; text-align:center;margin-top:3px;">$' .$your_price .'</div>';
	}
	else {
		if ($vehicle_condition == 'New') {
			$price .= '<p>&nbsp;</p><div class="car_retail_price" style="font-size:16px; font-weight:bold">'.get_option($vehicle_location_slug.'_no_new_price').'</div>';
		}
		else {
			$price .= '<p>&nbsp;</p><div class="car_retail_price" style="font-size:16px; font-weight:bold">'.get_option($vehicle_location_slug.'_no_used_price').'</div>';
		}
	}
	$sold_status = get_post_meta($post_id, "sold", true);
  	if ($sold_status == 'yes') {
		$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
		$pluginpath = str_replace('includes','',$pluginpath);
		$price = '<div id="your_price_text" class="your_price_text" style="font-size:24px; font-weight:bold; text-align:center;">';
			$price .= '<img src="'.$pluginpath.'images\sold.gif" alt="Sold" title="Sold" /><br />';
		$price .= '</div>';
	}
	$price .= '</div>';
	$price = '<div class="car_price_details" id="car_price_details" style="font-size:16px; font-weight:bold;">'.$spacer.$price;
	return $price;
}
?>