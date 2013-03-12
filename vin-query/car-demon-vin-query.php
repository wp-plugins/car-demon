<?php
function car_demon_get_vin_query($post_id, $vin) {
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

function get_vin_query_specs($vin_query_decode, $vehicle_vin) {
	$x = '
	<table class="decode_table">
	  <tr class="decode_table_header">
		<td><strong>'.__('VIN #', 'car-demon').'</strong></td>
		<td>'.$vehicle_vin.'</td>
	  </tr>';
		if ($vin_query_decode['decoded_model_year']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Model Year', 'car-demon').'</td>
				<td>'.$vin_query_decode['decoded_model_year'].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_make']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Make', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_make"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_model']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Model', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_model"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_trim_level']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Trim', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_trim_level"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_manufactured_in']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Manufactured in', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_manufactured_in"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_production_seq_number']) {
			$x .= '<tr class="decode_table_even">
				<td>&nbsp;&nbsp;&nbsp;'.__('Production Seq. Number', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_production_seq_number"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>Specifications</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_body_style']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Body Style', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_body_style"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_engine_type']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Engine Type', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_engine_type"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_transmission_long']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Transmission', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_transmission_long"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_driveline']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Driveline', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_driveline"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tank']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tank(gallon)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_tank"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_fuel_economy_city']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fuel Economy(City, miles/gallon)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_fuel_economy_city"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_fuel_economy_highway']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fuel Economy(Highway, miles/gallon)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_fuel_economy_highway"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_anti_brake_system']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Anti-Brake System', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_anti_brake_system"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_steering_type']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Steering Type', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_steering_type"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_overall_length']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Length(in.)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_overall_length"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_overall_width']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Width(in.)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_overall_width"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_overall_height']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Height(in.)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_overall_height"].'</td>
				</tr>';
		}
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
	return $x;
}

function get_vin_query_safety($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x = '
	<table class="decode_table">';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Legend', 'car-demon').'</strong></td>
	  </tr>';
	  $x .= '<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;'.__('Std. - Standard: indicates a manufacturer-installed feature that comes standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.', 'car-demon').'</td>
	  </tr>';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Equipment - Anti-Theft & Locks', 'car-demon').'</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_child_safety_door_locks']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Child Safety Door Locks', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_child_safety_door_locks"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_locking_pickup_truck_tailgate']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Locking Pickup Truck Tailgate', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_locking_pickup_truck_tailgate"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_power_door_locks']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Power Door Locks', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_power_door_locks"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_vehicle_anti_theft']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Vehicle Anti-Theft', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_vehicle_anti_theft"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Braking & Traction', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_4wd_awd']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('4WD/AWD', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_4wd_awd"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_abs_brakes']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('ABS(2-Wheel/4-Wheel)', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_abs_brakes"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_automatic_load_leveling']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Automatic Load-Leveling', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_automatic_load_leveling"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_electronic_brake_assistance']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Electronic Brake Assistance', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_electronic_brake_assistance"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_limited_slip_differential']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Limited Slip Differential', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_limited_slip_differential"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_locking_differential']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Locking Differential', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_locking_differential"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_traction_control']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Traction Control', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_traction_control"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_vehicle_stability_control_system']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Vehicle Stability Control System', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_vehicle_stability_control_system"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Safety', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_driver_airbag']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Driver Airbag', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_driver_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_front_side_airbag']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Side Airbag', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_front_side_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_front_side_airbag_with_head_protection']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Side Airbag with Head Protection', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_front_side_airbag_with_head_protection"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_passenger_airbag']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Passenger Airbag', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_passenger_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_side_head_curtain_airbag']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Side Head Curtain Airbag', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_side_head_curtain_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_second_row_side_airbag']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Side Airbag', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_second_row_side_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_second_row_side_airbag_with_head_protection']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Side Airbag with Head Protection', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_second_row_side_airbag_with_head_protection"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_electronic_parking_aid']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Electronic Parking Aid', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_electronic_parking_aid"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_first_aid_kit']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('First Aid Kit', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_first_aid_kit"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_trunk_anti_trap_device']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Trunk Anti-Trap Device', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_trunk_anti_trap_device"].'</td>
				</tr>';
		}
		$x .= '<tr>
			<td class="lastrowinpage" colspan="2">&nbsp;</td>
			</tr>';
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
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function get_vin_query_convienience($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	wp_enqueue_style('car-demon-vin-query-css', WP_CONTENT_URL . '/plugins/car-demon/vin-query/css/car-demon-vin-query.css');
	$x = '
	<table class="decode_table">';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Legend', 'car-demon').'</strong></td>
	  </tr>';
	  $x .= '<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;'.__('Std. - Standard: indicates a manufacturer-installed feature that comes standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.', 'car-demon').'</td>
	  </tr>';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Equipment - Remote Controls & Release', 'car-demon').'</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_keyless_entry']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Keyless Entry', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_keyless_entry"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_remote_ignition']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Remote Ignition', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_remote_ignition"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Interior Features', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_cruise_control']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Cruise Control', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_cruise_control"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tachometer']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tachometer', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_tachometer"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tilt_steering']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tilt Steering Wheel', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_tilt_steering"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tilt_steering_column']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tilt Steering Column', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_tilt_steering_column"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_heated_steering_wheel']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Heated Steering Wheel', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_heated_steering_wheel"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_leather_steering_wheel']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Leather Steering Wheel', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_leather_steering_wheel"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_steering_wheel_mounted_controls']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Steering Wheel Mounted Controls', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_steering_wheel_mounted_controls"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_telescopic_steering_column']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Telescopic Steering Column', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_telescopic_steering_column"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_adjustable_foot_pedals']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Adjustable Foot Pedals', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_adjustable_foot_pedals"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_genuine_wood_trim']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Genuine Wood Trim', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_genuine_wood_trim"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tire_pressure_monitor']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tire Inflation/Pressure Monitor', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_tire_pressure_monitor"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_trip_computer']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Trip Computer', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_trip_computer"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Storage', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_cargo_area_cover']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Cargo Area Cover', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_cargo_area_cover"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_cargo_area_tiedowns']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Cargo Area Tiedowns', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_cargo_area_tiedowns"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_cargo_net']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Cargo Net', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_cargo_net"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_load_bearing_exterior_rack']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Load Bearing Exterior Rack', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_load_bearing_exterior_rack"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_pickup_truck_bed_liner']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Pickup Truck Bed Liner', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_pickup_truck_bed_liner"].'</td>
				</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Roof', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_power_sunroof']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Power Sunroof/Moonroof', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_power_sunroof"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_removable_top']) {
			$x .= '<tr class="decode_table_even">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Manual Sunroof/Moonroof', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_removable_top"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_manual_sunroof']) {
			$x .= '<tr class="decode_table_odd">
				<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Removable/Convertible Top', 'car-demon').'</td>
				<td>'.$vin_query_decode["decoded_manual_sunroof"].'</td>
				</tr>';
		}
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
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function get_vin_query_comfort($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x ='
	<table class="decode_table">';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>Legend</strong></td>
	  </tr>';
	  $x .= '<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;'.__('Std. - Standard: indicates a manufacturer-installed feature that comes standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.', 'car-demon').'</td>
	  </tr>';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Equipment - Climate Control', 'car-demon').'</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_air_conditioning']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Air Conditioning', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_air_conditioning"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_separate_driver_front_passenger_climate_controls']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Separate Driver/Front Passenger Climate Controls', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_separate_driver_front_passenger_climate_controls"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Seat', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_driver_multi_adjustable_power_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Driver Multi-Adjustable Power Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_driver_multi_adjustable_power_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_cooled_seat']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Cooled Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_front_cooled_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_heated_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Heated Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_front_heated_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_power_lumbar_support']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Power Lumbar Support', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_front_power_lumbar_support"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_power_memory_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Power Memory Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_front_power_memory_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_split_bench_seat']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Split Bench Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_front_split_bench_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_leather_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Leather Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_leather_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_passenger_multi_adjustable_power_seat']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Passenger Multi-Adjustable Power Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_passenger_multi_adjustable_power_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_folding_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Folding Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_second_row_folding_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_heated_seat']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Heated Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_second_row_heated_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_multi_adjustable_power_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Multi-Adjustable Power Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_second_row_multi_adjustable_power_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_removable_seat']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Removable Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_second_row_removable_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_third_row_removable_seat']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Third Row Removable Seat', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_third_row_removable_seat"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Exterior Lighting', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_automatic_headlights']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Automatic Headlights', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_automatic_headlights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_daytime_running_lights']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Daytime Running Lights', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_daytime_running_lights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_fog_lights']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Fog Lights', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_fog_lights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_high_intensity_discharge_headlights']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('High Intensity Discharge Headlights', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_high_intensity_discharge_headlights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_pickup_truck_cargo_box_light']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Pickup Truck Cargo Box Light', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_pickup_truck_cargo_box_light"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Exterior Features', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_running_boards']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Bodyside/Cab Step or Running Board', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_running_boards"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_air_dam']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Front Air Dam', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_front_air_dam"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rear_spoiler']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Rear Spoiler', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_rear_spoiler"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_skid_plate']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Skid Plate or Underbody Protection', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_skid_plate"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_splash_guards']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Splash Guards', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_splash_guards"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_wind_deflector_for_convertibles']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Wind Deflector or Buffer for Convertible', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_wind_deflector_for_convertibles"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_power_sliding_side_van_door']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Power Sliding Side Van Door', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_power_sliding_side_van_door"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_power_trunk_lid']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Power Trunk Lid', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_power_trunk_lid"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Wheels', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_alloy_wheels']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Alloy Wheels', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_alloy_wheels"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_chrome_wheels']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Chrome Wheels', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_chrome_wheels"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_steel_wheels']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Steel Wheels', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_steel_wheels"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Tires', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_full_size_spare_tire']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Full Size Spare Tire', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_full_size_spare_tire"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_run_flat_tires']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Run Flat Tires', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_run_flat_tires"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Windows', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_power_windows']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Power Windows', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_power_windows"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_glass_rear_window_on_convertible']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Glass Rear Window on Convertible', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_glass_rear_window_on_convertible"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_sliding_rear_pickup_truck_window']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Sliding Rear Pickup Truck Window', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_sliding_rear_pickup_truck_window"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Mirrors', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_electrochromic_exterior_rearview_mirror']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Electrochromic Exterior Rearview Mirror', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_electrochromic_exterior_rearview_mirror"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_heated_exterior_mirror']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Heated Exterior Mirror', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_heated_exterior_mirror"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_electrochromic_interior_rearview_mirror']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Electrochromic Interior Rearview Mirror', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_electrochromic_interior_rearview_mirror"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_power_adjustable_exterior_mirror']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Power Adjustable Exterior Mirror', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_power_adjustable_exterior_mirror"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Wipers', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_interval_wipers']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Interval Wipers', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_interval_wipers"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rain_sensing_wipers']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Rain Sensing Wipers', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_rain_sensing_wipers"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rear_wiper']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Rear Wiper', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_rear_wiper"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rear_window_defogger']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Rear Window Defogger', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_rear_window_defogger"].'</td>
			</tr>';
		}
		$x .= '<tr class="decode_table_header">
			<td colspan="2"><strong>'.__('Equipment - Towings', 'car-demon').'</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_tow_hitch_receiver']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Tow Hitch Receiver', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_tow_hitch_receiver"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_towing_preparation_package']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Towing Preparation Package', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_towing_preparation_package"].'</td>
			</tr>';
		}
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
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function get_vin_query_entertainment($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x = '
	<table class="decode_table">';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Legend', 'car-demon').'</strong></td>
	  </tr>';
	  $x .= '<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;'.__('Std. - Standard: indicates a manufacturer-installed feature that comes standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.', 'car-demon').'<br/>
		  &nbsp;&nbsp;&nbsp;'.__('N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.', 'car-demon').'</td>
	  </tr>';
	  $x .= '<tr class="decode_table_header">
		<td colspan="2"><strong>'.__('Equipment - Entertainment, Communication & Navigation', 'car-demon').'</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_am_fm_radio']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('AM/FM Radio', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_am_fm_radio"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_cassette_player']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Cassette Player', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_cassette_player"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_cd_player']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('CD Player', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_cd_player"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_cd_changer']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('CD Changer', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_cd_changer"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_dvd_player']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('DVD Player', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_dvd_player"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_voice_activated_telephone']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Hands Free/Voice Activated Telephone', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_voice_activated_telephone"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_navigation_aid']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Navigation Aid', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_navigation_aid"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_sound_controls']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Second Row Sound Controls or Accessories', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_second_row_sound_controls"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_subwoofer']) {
			$x .= '<tr class="decode_table_odd">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Subwoofer', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_subwoofer"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_telematics_system']) {
			$x .= '<tr class="decode_table_even">
			<td class="decode_table_label">&nbsp;&nbsp;&nbsp;'.__('Telematic Systems', 'car-demon').'</td>
			<td>'.$vin_query_decode["decoded_telematics_system"].'</td>
			</tr>';
		}
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
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}
?>