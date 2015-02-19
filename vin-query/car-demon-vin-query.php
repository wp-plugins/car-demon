<?php
function car_demon_get_vin_query($post_id, $vin) {
	if (!empty($vin)) {
		$report_type = '1';
		if (isset($_SESSION['car_demon_options']['vinquery_type'])) {
			$report_type = $_SESSION['car_demon_options']['vinquery_type'];
		}
		$vinquery_id = $_SESSION['car_demon_options']['vinquery_id'];
		$decode_saved = get_post_meta($post_id, 'decode_saved');
		if (empty($decode_saved)) {
			$url = "http://vinquery.com/ws_POQCXTYNO1D/xml_v100_QA7RTS8Y.aspx?accessCode=".$vinquery_id."&vin=".$vin."&reportType=".$report_type;
			// test URL for sample import
			// $url = WP_CONTENT_URL . '/plugins/car-demon/vin-query/extended_sample.xml';
			$xml = simplexml_load_file($url);
			if ($xml) {
				$car_details = $xml->VIN->Vehicle->Item;
				if (!empty($car_details)) {
					$decode_string = array();
					foreach ($car_details as $car_detail) {
						$key = strtolower($car_detail->attributes()->Key);
						$key = str_replace(chr(32),'_', $key);
						$key = str_replace('-','_', $key);
						$key = str_replace('/','_', $key);
						$key = str_replace('\\','_', $key);
						$key = str_replace('.','', $key);
						$key = "decoded_".$key;
						$value = $car_detail->attributes()->Value;
						$value = $value . ' ' . $car_detail->attributes()->Unit;
						$value = str_replace('\'', '', $value);
						$decode_string[$key] = $value;
					}
				}
				if (!empty($decode_string)) {
					update_post_meta($post_id, 'decode_string', $decode_string);
					update_post_meta($post_id, 'decode_saved', "1");
				}
				if (isset($decode_string['decoded_model_year'])) {
					wp_set_post_terms($post_id, $decode_string['decoded_model_year'], 'vehicle_year', false );
				}
				if (isset($decode_string['decoded_make'])) {
					wp_set_post_terms($post_id, $decode_string['decoded_make'], 'vehicle_make', false );
				}
				if (isset($decode_string['decoded_model'])) {
					wp_set_post_terms($post_id, $decode_string['decoded_model'], 'vehicle_model', false );
				}
				if (isset($decode_string['decoded_transmission_long'])) {
					update_post_meta($post_id, '_transmission_value', $decode_string['decoded_transmission_long']);	
				}
				if (isset($decode_string['decoded_engine_type'])) {
					update_post_meta($post_id, '_engine_value', $decode_string['decoded_engine_type']);
				}
				if (isset($decode_string['decoded_trim_level'])) {
					update_post_meta($post_id, '_trim_value', $decode_string['decoded_trim_level']);
				}
			}
		}
	}
}
function get_vin_query_specs($vin_query_decode, $vehicle_vin, $post_id) {
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();
	$x = '<table class="decode_table">';
		if ($show_hide['vin'] != true) {
			$x .= '<tr class="decode_table_header">';
				$x .= '<td><strong>'.$field_labels['vin'].'</strong></td>';
				$x .= '<td>'.$vehicle_vin.'</td>';
			$x .= '</tr>';
		}
		if (isset($vin_query_decode['decoded_body_style'])) {
			if ($show_hide['body_style'] != true) {
				$x .= '<tr class="decode_table_odd">
					<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.$field_labels['body_style'].'</td>
					<td>'.$vin_query_decode["decoded_body_style"].'</td>
					</tr>';
			}
		}
		if (isset($vin_query_decode['decoded_model_year'])) {
			if ($show_hide['year'] != true) {
				$x .= '<tr class="decode_table_odd">
					<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.$field_labels['year'].'</td>
					<td>'.$vin_query_decode['decoded_model_year'].'</td>
					</tr>';
			}
		}
		if (isset($vin_query_decode['decoded_make'])) {
			if ($show_hide['make'] != true) {
				$x .= '<tr class="decode_table_even">
					<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.$field_labels['make'].'</td>
					<td>'.$vin_query_decode["decoded_make"].'</td>
					</tr>';
			}
		}
		if (isset($vin_query_decode['decoded_model'])) {
			if ($show_hide['model'] != true) {
				$x .= '<tr class="decode_table_odd">
					<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.$field_labels['model'].'</td>
					<td>'.$vin_query_decode["decoded_model"].'</td>
					</tr>';
			}
		}
		$car_demon_options = car_demon_options();
		if (isset($car_demon_options['show_custom_specs'])) {
			$show_custom_specs = $car_demon_options['show_custom_specs'];
		} else {
			$show_custom_specs = 'No';
		}
		//= BEGIN CUSTOM SPEC CODE
		if ($show_custom_specs == 'Yes') {
			$map = cd_get_vehicle_map();
			$specs_map = $map['specs'];
			foreach ($specs_map as $key=>$spec_group) {
				$x .= '<tr class="decode_table_header">
						<td colspan="2"><strong>'.$key.'</strong></td>
					</tr>';
				$spec_group_array = explode(',',$spec_group);
				$odd_even = 'even';
				foreach($spec_group_array as $spec_item) {
					if($odd_even == 'odd') { $odd_even = 'even'; } else {$odd_even = 'odd';}
					$spec_item_slug = trim($spec_item);
					$spec_item_slug = strtolower($spec_item_slug);
					$spec_item_slug = str_replace(' ', '_', $spec_item_slug);
					$x .= custom_spec_field($post_id, $spec_item, 'decoded_'.$spec_item_slug, $odd_even, $vin_query_decode);
				}
			}
		} else {
			  $x .= '<tr class="decode_table_header">
						<td colspan="2"><strong>'.__('Specifications', 'car-demon').'</strong></td>
					</tr>';
				$x .= custom_spec_field($post_id, __('Trim', 'car-demon'), 'decoded_trim_level', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Production Seq. Number', 'car-demon'), 'decoded_production_seq_number', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Exterior Color', 'car-demon'), 'exterior_color', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Interior Color', 'car-demon'), 'interior_color', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Manufactured in', 'car-demon'), 'decoded_manufactured_in', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Engine Type', 'car-demon'), 'decoded_engine_type', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Transmission', 'car-demon'), 'decoded_transmission_long', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Driveline', 'car-demon'), 'decoded_driveline', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Tank(gallon)', 'car-demon'), 'decoded_driveline', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Fuel Economy (City, miles/gallon)', 'car-demon'), 'decoded_fuel_economy_city', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Fuel Economy (Highway, miles/gallon)', 'car-demon'), 'decoded_fuel_economy_highway', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Anti-Brake System', 'car-demon'), 'decoded_anti_brake_system', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Steering Type', 'car-demon'), 'decoded_steering_type', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Length(in.)', 'car-demon'), 'decoded_overall_length', 'even', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Width(in.)', 'car-demon'), 'decoded_overall_width', 'odd', $vin_query_decode);
				$x .= custom_spec_field($post_id, __('Height(in.)', 'car-demon'), 'decoded_overall_height', 'even', $vin_query_decode);
		}
		//= END CUSTOM SPECS
		$x .= '<tr class="decode_table_header">
			<td class="disclaimerrow" colspan="2"><strong>'.__('Disclaimer', 'car-demon').'</strong></td>
			</tr>';
		$x .= '<tr>
			<td colspan="2"><div class="decode_disclaimer">'.__('ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,', 'car-demon').'<br />
			'.__('IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY', 'car-demon').'<br />
			'.__('OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY', 'car-demon').'<br />
			'.__('DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.', 'car-demon').'</div></td>
		  </tr>';
	  $x .= '</table>';
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function custom_spec_field($post_id, $field, $slug, $odd_even, $vin_query_decode) {
	$x = '';
	if (isset($vin_query_decode[$slug])) {$value = $vin_query_decode[$slug]; } else {$value = ''; }
	if (!empty($value)) {
		$x = '
		  <tr class="decode_table_'.$odd_even.'">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.$field.'</td>
			<td class="'.$slug.'">'.$value.'</td>
		  </tr>
		';
	}
	return $x;	
}
?>