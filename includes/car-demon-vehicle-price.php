<?php
function get_vehicle_price($post_id) {
	$is_sold = get_post_meta($post_id, 'sold', true);
	$spacer = '';
	$vehicle_condition = '';
	if (isset($_SESSION['car_demon_options']['currency_symbol'])) {
		$currency_symbol = $_SESSION['car_demon_options']['currency_symbol'];
	} else {
		$currency_symbol = "$";
	}
	if (isset($_SESSION['car_demon_options']['currency_symbol_after'])) {
		$currency_symbol_after = $_SESSION['car_demon_options']['currency_symbol_after'];
		if (!empty($currency_symbol_after)) {
			$currency_symbol = "";
		}
	} else {
		$currency_symbol_after = "";
	}	
	if ($is_sold == "Yes") {
		$sold = "<div class='car_sold'>".__("SOLD", "car-demon")."</div>";
		return $sold;
	}
	$vehicle_location = strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' ));
	if ($vehicle_location == '') {
		$vehicle_location = 'Default';
		$vehicle_location_slug = 'default';
	} else {
		$vehicle_location_term = get_term_by('name', $vehicle_location, 'vehicle_location');
		$vehicle_location_slug = $vehicle_location_term->slug;
		$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
	}
	if ($vehicle_condition == 'New') {
		$show_price = get_option($vehicle_location_slug.'_show_new_prices');
	} else {
		$show_price = get_option($vehicle_location_slug.'_show_used_prices');
	}
	$price = '';
	if ($show_price == 'Yes') {
		$vehicle_price = get_post_meta($post_id, "_price_value", true);
		$vehicle_price_pack = (int)$vehicle_price;
		if ($vehicle_price == 0) {
			$vehicle_price = ' Call '.$currency_symbol;
		}
		$selling_price = get_post_meta($post_id, "_msrp_value", true);
		$rebate = get_post_meta($post_id, "_rebates_value", true);
		$dealer_discount = get_post_meta($post_id, "_discount_value", true);
		$your_price = $vehicle_price;
		$spacer = "";
		if (!empty($selling_price)) {
			$price .= '<div id="selling_price" class="car_selling_price"><div class="car_price_text">'. $currency_symbol. $selling_price . $currency_symbol_after .'</div> :'.__('Selling Price', 'car-demon').'</div>';
		}
		if (!empty($rebate)) {
			$price .= '<div id="rebate" class="car_rebate"><div class="car_price_text">'. $currency_symbol. $rebate . $currency_symbol_after. '</div> :'.__('Rebate', 'car-demon').'</div>';
		}
		else {
			$spacer = '<div class="car_rebate"><div class="car_price_text">&nbsp;</div>&nbsp;</div>';
		}
		if (!empty($dealer_discount)){
			$price .= '<div class="car_dealer_discounts"><div class="car_price_text">'. $currency_symbol . $dealer_discount . $currency_symbol_after .'</div> :'.__('Xtra Discount', 'car-demon').'</div>';
		}
		else {
			$spacer = '<div class="car_rebate"><div class="car_price_text">&nbsp;</div>&nbsp;</div>';		
		}
		$price .= '<div id="your_price_text" class="car_your_price">'.__('YOUR PRICE', 'car-demon').':</div>';
		$price .= '<div id="your_price" class="car_final_price">'. $currency_symbol .$your_price . $currency_symbol_after .'</div>';
	} else {
		if ($vehicle_condition == 'New') {
			$price .= '<p>&nbsp;</p><div class="car_retail_price">'.get_option($vehicle_location_slug.'_no_new_price').'</div>';
		}
		else {
			$price .= '<p>&nbsp;</p><div class="car_retail_price">'.get_option($vehicle_location_slug.'_no_used_price').'</div>';
		}
	}
	$sold_status = get_post_meta($post_id, "sold", true);
  	if ($sold_status == 'yes') {
		$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
		$pluginpath = str_replace('includes','',$pluginpath);
		$price = '<div id="your_price_text" class="your_price_text">';
			$price .= '<img src="'.$pluginpath.'theme-files\images\sold.gif" alt="Sold" title="Sold" /><br />';
		$price .= '</div>';
	}
	$price .= '</div>';
	$price = '<div class="car_price_details" id="car_price_details">'.$spacer.$price;
	return $price;
}
?>