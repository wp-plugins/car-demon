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
function get_vin_query_specs($vin_query_decode, $vehicle_vin) {
	$x = '
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td><strong>'.__('VIN #', 'car-demon').'</strong></td>
		<td>'.$vehicle_vin.'</td>
	  </tr>';
		if (isset($vin_query_decode['decoded_model_year'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Model Year', 'car-demon').'</td>
				<td>'.$vin_query_decode['decoded_model_year'].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_make'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Make', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_make"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_model'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Model', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_model"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_trim_level'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Trim', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_trim_level"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_manufactured_in'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Manufactured in', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_manufactured_in"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_production_seq_number'])) {
			$x .= '<tr class="decode_table_even">
				<td>&nbsp;&nbsp;&nbsp;'.__('Production Seq. Number', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_production_seq_number"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>Specifications</strong></td>
			</tr>';
		if (isset($vin_query_decode['decoded_body_style'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Body Style', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_body_style"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_engine_type'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Engine Type', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_engine_type"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_transmission_long'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Transmission', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_transmission_long"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_driveline'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Driveline', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_driveline"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_tank'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tank(gallon)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_tank"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_fuel_economy_city'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fuel Economy(City, miles/gallon)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_fuel_economy_city"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_fuel_economy_highway'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fuel Economy(Highway, miles/gallon)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_fuel_economy_highway"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_anti_brake_system'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Anti-Brake System', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_anti_brake_system"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_steering_type'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Steering Type', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_steering_type"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_overall_length'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Length(in.)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_overall_length"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_overall_width'])) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Width(in.)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_overall_width"].'</td>
				</tr>';
		}
		if (isset($vin_query_decode['decoded_overall_height'])) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Height(in.)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_overall_height"].'</td>
				</tr>';
		}
		/*
		$option_array = get_post_meta($new_id, '_vehicle_options');	
		$option_array = explode(',',$option_array);
		foreach($option_array as $option_item) {
			if (isset($vin_query_decode['decoded_'.$option_item])) {
				$label = str_replace('_',' ',$option_item);
				$label = strtoupper($label);
				$x .= '<tr class="decode_table_even">
					<td class="decode_table_label">'.$label.'</td>
					<td>'.$vin_query_decode['decoded_'.$option_item].'</td>
					</tr>';
			}
		}
		*/
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
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}
?>