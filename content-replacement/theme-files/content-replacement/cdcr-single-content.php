<?php
function cdcr_single_content($content) {
	$cd_cdrf_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$post_id = get_the_ID();
	$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
	$car_title = get_car_title_slug($post_id);
	$car_head_title = get_car_title($post_id);
	$car_url = get_permalink($post_id);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_details = get_post_meta($post_id, 'decode_string', true);
	//=========================Contact Info===========================
	$car_contact = get_car_contact($post_id);
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	//$detail_output = '<h3 class="car_title">'.$car_head_title.'</h3>';
	$detail_output = '<div class="car_title_div">';
		$detail_output .= '<ul>';
			$detail_output .= '<li><strong>Condition:</strong> '.$vehicle_details['condition'].'</li>';
			$detail_output .= '<li><strong>Mileage:</strong> '.$vehicle_details['mileage'].'</li>';
			$detail_output .= '<li><strong>Stock#:</strong> '.$vehicle_details['stock_num'].'</li>';
			$detail_output .= '<li><strong>VIN#:</strong> '.$vehicle_vin.'</li>';
			$detail_output .= '<li><strong>Color:</strong> '.$vehicle_details['exterior_color'].'/'.$vehicle_details['interior_color'].'</li>';
			$detail_output .= '<li><strong>Transmission:</strong> '.$vehicle_details['decoded_transmission_long'].'</li>';
			$detail_output .= '<li><strong>Engine:</strong> '.$vehicle_details['decoded_engine_type'].'</li>';
			$detail_output .= get_vehicle_price($post_id);
		$detail_output .= '</ul>';
	$detail_output .= '</div>';
	$x = car_photos($post_id, $detail_output, $vehicle_condition);
	$x .= car_demon_vehicle_detail_tabs($post_id, true);
	$x .= '<div class="similar_cars_container">
			'. car_demon_display_similar_cars($vehicle_details['decoded_body_style'], $post_id) .'
		  </div>';

	return $x;	
}
function cdcr_single_content_o() {
	$cd_cdrf_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$post_id = get_the_ID();
	$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
	$car_title = get_car_title_slug($post_id);
	$car_head_title = get_car_title($post_id);
	$car_url = get_permalink($post_id);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_details = get_post_meta($post_id, 'decode_string', true);
	//=========================Contact Info===========================
	$car_contact = get_car_contact($post_id);
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	$detail_output = '<div class="car_title_div"><h3 class="car_title">'.$car_head_title.'</h3>';
	$detail_output .= '<ul>';
		$detail_output .= '<li><strong>Condition:</strong> '.$vehicle_details['condition'].'</li>';
		$detail_output .= '<li><strong>Mileage:</strong> '.$vehicle_details['mileage'].'</li>';
		$detail_output .= '<li><strong>Stock#:</strong> '.$vehicle_details['stock_num'].'</li>';
		$detail_output .= '<li><strong>VIN#:</strong> '.$vehicle_vin.'</li>';
		$detail_output .= '<li><strong>Color:</strong> '.$vehicle_details['exterior_color'].'/'.$vehicle_details['interior_color'].'</li>';
		$detail_output .= '<li><strong>Transmission:</strong> '.$vehicle_details['decoded_transmission_long'].'</li>';
		$detail_output .= '<li><strong>Engine:</strong> '.$vehicle_details['decoded_engine_type'].'</li>';
		$detail_output .= get_vehicle_price($post_id);
	$detail_output .= '</ul></div>';
//	echo cd_cdrf_email_a_friend($post_id, $vehicle_stock_number);

	//= Build Single Vehicle Content HTML
	$x = '
	<div id="vehicle" class="vehicle-single">
		<div id="post-'. $post_id .'" '. post_class() .'>
			<div id="demon-post-'. $post_id .'" class="car_content">
				<div class="start_car">&nbsp;</div>
				<div class="car_buttons_div">

					<div class="email_a_friend">
						<a href="http://www.facebook.com/share.php?u='. $car_url .'&amp;t='. $car_head_title .'" target="fb_win">
							<img title="'. __('Share on Facebook', 'car-demon') .'" src="'. $cd_cdrf_pluginpath .'images/social_fb.png" />
						</a>
						<a target="tweet_win" href="http://twitter.com/share?text=Check out this '. $car_head_title .'" title="'. __('Click to share this on Twitter', 'car-demon') .'">
							<img title="'. __('Share on Twitter', 'car-demon') .'" src="'. $cd_cdrf_pluginpath .'images/social_twitter.png" />
						</a>
						<img onclick="email_friend();" title="'. __('Email to a Friend', 'car-demon') .'" src="'. $cd_cdrf_pluginpath .'images/social_email.png" />
					</div>
				</div>
				<div class="car-demon-entry-content">
					'. car_photos($post_id, $detail_output, $vehicle_condition) .'
					<div class="similar_cars_container">
						'. car_demon_display_similar_cars($vehicle_details['decoded_body_style'], $post_id) .'
					</div>
				</div><!-- .car-demon-entry-content -->
				'. car_demon_vehicle_detail_tabs($post_id) .'
			</div><!-- #carbody-## -->
		</div><!-- #post-## -->
	</div><!-- end of #content -->
	';
	$x = 'huh';
	return $x;	
}
?>