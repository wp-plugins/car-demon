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
			update_post_meta($post_id, 'decode_string', $decode_string);
			update_post_meta($post_id, 'decode_saved', "1");
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
	<table width="100%" style="font-size:12px;">
	  <tr style="color:Black;background-color:#CCCCCC;">
		<td><strong>VIN #</strong></td>
		<td>'.$vehicle_vin.'</td>
	  </tr>';
		if ($vin_query_decode['decoded_model_year']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Model Year</td>
				<td>'.$vin_query_decode['decoded_model_year'].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_make']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Make</td>
				<td>'.$vin_query_decode["decoded_make"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_model']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Model</td>
				<td>'.$vin_query_decode["decoded_model"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_trim_level']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Trim</td>
				<td>'.$vin_query_decode["decoded_trim_level"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_manufactured_in']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Manufactured in</td>
				<td>'.$vin_query_decode["decoded_manufactured_in"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_production_seq_number']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td>&nbsp;&nbsp;&nbsp;Production Seq. Number</td>
				<td>'.$vin_query_decode["decoded_production_seq_number"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Specifications</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_body_style']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Body Style</td>
				<td>'.$vin_query_decode["decoded_body_style"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_engine_type']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Engine Type</td>
				<td>'.$vin_query_decode["decoded_engine_type"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_transmission_long']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Transmission</td>
				<td>'.$vin_query_decode["decoded_transmission_long"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_driveline']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Driveline</td>
				<td>'.$vin_query_decode["decoded_driveline"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tank']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Tank(gallon)</td>
				<td>'.$vin_query_decode["decoded_tank"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_fuel_economy_city']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Fuel Economy(City, miles/gallon)</td>
				<td>'.$vin_query_decode["decoded_fuel_economy_city"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_fuel_economy_highway']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Fuel Economy(Highway, miles/gallon)</td>
				<td>'.$vin_query_decode["decoded_fuel_economy_highway"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_anti_brake_system']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Anti-Brake System</td>
				<td>'.$vin_query_decode["decoded_anti_brake_system"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_steering_type']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Steering Type</td>
				<td>'.$vin_query_decode["decoded_steering_type"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_overall_length']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Length(in.)</td>
				<td>'.$vin_query_decode["decoded_overall_length"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_overall_width']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Width(in.)</td>
				<td>'.$vin_query_decode["decoded_overall_width"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_overall_height']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Height(in.)</td>
				<td>'.$vin_query_decode["decoded_overall_height"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td class="disclaimerrow" colspan="2"><strong>Disclaimer</strong></td>
			</tr>';
		$x .= '<tr style="">
			<td colspan="2"><div style="max-width:580px;font-size:8px;">ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,<br />
			IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY<br />
			OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY<br />
			DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.</div></td>
			</tr>';
	  $x .= '</table>';
	return $x;
}

function get_vin_query_safety($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x = '
	<table width="100%" style="font-size:12px;">';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Legend</strong></td>
	  </tr>';
	  $x .= '<tr style="">
		<td colspan="2">&nbsp;&nbsp;&nbsp;Std. - Standard: indicates a manufacturer-installed feature that comes standard.<br/>
		  &nbsp;&nbsp;&nbsp;Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.<br/>
		  &nbsp;&nbsp;&nbsp;N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.</td>
	  </tr>';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Equipment - Anti-Theft & Locks</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_child_safety_door_locks']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Child Safety Door Locks</td>
				<td>'.$vin_query_decode["decoded_child_safety_door_locks"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_locking_pickup_truck_tailgate']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Locking Pickup Truck Tailgate</td>
				<td>'.$vin_query_decode["decoded_locking_pickup_truck_tailgate"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_power_door_locks']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Power Door Locks</td>
				<td>'.$vin_query_decode["decoded_power_door_locks"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_vehicle_anti_theft']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Vehicle Anti-Theft</td>
				<td>'.$vin_query_decode["decoded_vehicle_anti_theft"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Braking & Traction</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_4wd_awd']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;4WD/AWD</td>
				<td>'.$vin_query_decode["decoded_4wd_awd"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_abs_brakes']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;ABS(2-Wheel/4-Wheel)</td>
				<td>'.$vin_query_decode["decoded_abs_brakes"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_automatic_load_leveling']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Automatic Load-Leveling</td>
				<td>'.$vin_query_decode["decoded_automatic_load_leveling"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_electronic_brake_assistance']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Electronic Brake Assistance</td>
				<td>'.$vin_query_decode["decoded_electronic_brake_assistance"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_limited_slip_differential']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Limited Slip Differential</td>
				<td>'.$vin_query_decode["decoded_limited_slip_differential"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_locking_differential']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Locking Differential</td>
				<td>'.$vin_query_decode["decoded_locking_differential"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_traction_control']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Traction Control</td>
				<td>'.$vin_query_decode["decoded_traction_control"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_vehicle_stability_control_system']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Vehicle Stability Control System</td>
				<td>'.$vin_query_decode["decoded_vehicle_stability_control_system"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Safety</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_driver_airbag']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Driver Airbag</td>
				<td>'.$vin_query_decode["decoded_driver_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_front_side_airbag']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Side Airbag</td>
				<td>'.$vin_query_decode["decoded_front_side_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_front_side_airbag_with_head_protection']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Side Airbag with Head Protection</td>
				<td>'.$vin_query_decode["decoded_front_side_airbag_with_head_protection"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_passenger_airbag']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Passenger Airbag</td>
				<td>'.$vin_query_decode["decoded_passenger_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_side_head_curtain_airbag']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Side Head Curtain Airbag</td>
				<td>'.$vin_query_decode["decoded_side_head_curtain_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_second_row_side_airbag']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Side Airbag</td>
				<td>'.$vin_query_decode["decoded_second_row_side_airbag"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_second_row_side_airbag_with_head_protection']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Side Airbag with Head Protection</td>
				<td>'.$vin_query_decode["decoded_second_row_side_airbag_with_head_protection"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_electronic_parking_aid']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Electronic Parking Aid</td>
				<td>'.$vin_query_decode["decoded_electronic_parking_aid"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_first_aid_kit']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;First Aid Kit</td>
				<td>'.$vin_query_decode["decoded_first_aid_kit"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_trunk_anti_trap_device']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Trunk Anti-Trap Device</td>
				<td>'.$vin_query_decode["decoded_trunk_anti_trap_device"].'</td>
				</tr>';
		}
		$x .= '<tr>
			<td class="lastrowinpage" colspan="2">&nbsp;</td>
			</tr>';
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td class="disclaimerrow" colspan="2"><strong>Disclaimer</strong></td>
			</tr>';
		$x .= '<tr style="">
			<td colspan="2"><div style="max-width:580px;font-size:8px;">ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,<br />
			IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY<br />
			OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY<br />
			DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.</div></td>
			</tr>';
	  $x .= '</table>';
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="Standard Option" alt="Standard Option" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="Optional" alt="Optional" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="NA" alt="NA" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function get_vin_query_convienience($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x = '
	<table width="100%" style="font-size:12px;">';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Legend</strong></td>
	  </tr>';
	  $x .= '<tr style="">
		<td colspan="2">&nbsp;&nbsp;&nbsp;Std. - Standard: indicates a manufacturer-installed feature that comes standard.<br/>
		  &nbsp;&nbsp;&nbsp;Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.<br/>
		  &nbsp;&nbsp;&nbsp;N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.</td>
	  </tr>';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Equipment - Remote Controls & Release</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_keyless_entry']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Keyless Entry</td>
				<td>'.$vin_query_decode["decoded_keyless_entry"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_remote_ignition']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Remote Ignition</td>
				<td>'.$vin_query_decode["decoded_remote_ignition"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Interior Features</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_cruise_control']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Cruise Control</td>
				<td>'.$vin_query_decode["decoded_cruise_control"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tachometer']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Tachometer</td>
				<td>'.$vin_query_decode["decoded_tachometer"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tilt_steering']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Tilt Steering Wheel</td>
				<td>'.$vin_query_decode["decoded_tilt_steering"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tilt_steering_column']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Tilt Steering Column</td>
				<td>'.$vin_query_decode["decoded_tilt_steering_column"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_heated_steering_wheel']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Heated Steering Wheel</td>
				<td>'.$vin_query_decode["decoded_heated_steering_wheel"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_leather_steering_wheel']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Leather Steering Wheel</td>
				<td>'.$vin_query_decode["decoded_leather_steering_wheel"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_steering_wheel_mounted_controls']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Steering Wheel Mounted Controls</td>
				<td>'.$vin_query_decode["decoded_steering_wheel_mounted_controls"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_telescopic_steering_column']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Telescopic Steering Column</td>
				<td>'.$vin_query_decode["decoded_telescopic_steering_column"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_adjustable_foot_pedals']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Adjustable Foot Pedals</td>
				<td>'.$vin_query_decode["decoded_adjustable_foot_pedals"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_genuine_wood_trim']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Genuine Wood Trim</td>
				<td>'.$vin_query_decode["decoded_genuine_wood_trim"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_tire_pressure_monitor']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Tire Inflation/Pressure Monitor</td>
				<td>'.$vin_query_decode["decoded_tire_pressure_monitor"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_trip_computer']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Trip Computer</td>
				<td>'.$vin_query_decode["decoded_trip_computer"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Storage</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_cargo_area_cover']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Cargo Area Cover</td>
				<td>'.$vin_query_decode["decoded_cargo_area_cover"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_cargo_area_tiedowns']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Cargo Area Tiedowns</td>
				<td>'.$vin_query_decode["decoded_cargo_area_tiedowns"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_cargo_net']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Cargo Net</td>
				<td>'.$vin_query_decode["decoded_cargo_net"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_load_bearing_exterior_rack']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Load Bearing Exterior Rack</td>
				<td>'.$vin_query_decode["decoded_load_bearing_exterior_rack"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_pickup_truck_bed_liner']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Pickup Truck Bed Liner</td>
				<td>'.$vin_query_decode["decoded_pickup_truck_bed_liner"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Roof</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_power_sunroof']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Power Sunroof/Moonroof</td>
				<td>'.$vin_query_decode["decoded_power_sunroof"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_removable_top']) {
			$x .= '<tr style="color:Black;background-color:white;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Manual Sunroof/Moonroof</td>
				<td>'.$vin_query_decode["decoded_removable_top"].'</td>
				</tr>';
		}
		if ($vin_query_decode['decoded_manual_sunroof']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
				<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Removable/Convertible Top</td>
				<td>'.$vin_query_decode["decoded_manual_sunroof"].'</td>
				</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td class="disclaimerrow" colspan="2"><strong>Disclaimer</strong></td>
			</tr>';
	  $x .= '<tr style="">
		<td colspan="2"><div style="max-width:580px;font-size:8px;">ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,<br />
		IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY<br />
		OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY<br />
		DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.</div></td>
	  </tr>';
	$x .= '</table>';
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="Standard Option" alt="Standard Option" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="Optional" alt="Optional" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="NA" alt="NA" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function get_vin_query_comfort($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x ='
	<table width="100%" style="font-size:12px;">';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Legend</strong></td>
	  </tr>';
	  $x .= '<tr style="">
		<td colspan="2">&nbsp;&nbsp;&nbsp;Std. - Standard: indicates a manufacturer-installed feature that comes standard.<br/>
		  &nbsp;&nbsp;&nbsp;Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.<br/>
		  &nbsp;&nbsp;&nbsp;N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.</td>
	  </tr>';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Equipment - Climate Control</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_air_conditioning']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Air Conditioning</td>
			<td>'.$vin_query_decode["decoded_air_conditioning"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_separate_driver_front_passenger_climate_controls']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Separate Driver/Front Passenger Climate Controls</td>
			<td>'.$vin_query_decode["decoded_separate_driver_front_passenger_climate_controls"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Seat</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_driver_multi_adjustable_power_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Driver Multi-Adjustable Power Seat</td>
			<td>'.$vin_query_decode["decoded_driver_multi_adjustable_power_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_cooled_seat']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Cooled Seat</td>
			<td>'.$vin_query_decode["decoded_front_cooled_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_heated_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Heated Seat</td>
			<td>'.$vin_query_decode["decoded_front_heated_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_power_lumbar_support']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Power Lumbar Support</td>
			<td>'.$vin_query_decode["decoded_front_power_lumbar_support"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_power_memory_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Power Memory Seat</td>
			<td>'.$vin_query_decode["decoded_front_power_memory_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_split_bench_seat']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Split Bench Seat</td>
			<td>'.$vin_query_decode["decoded_front_split_bench_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_leather_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Leather Seat</td>
			<td>'.$vin_query_decode["decoded_leather_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_passenger_multi_adjustable_power_seat']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Passenger Multi-Adjustable Power Seat</td>
			<td>'.$vin_query_decode["decoded_passenger_multi_adjustable_power_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_folding_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Folding Seat</td>
			<td>'.$vin_query_decode["decoded_second_row_folding_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_heated_seat']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Heated Seat</td>
			<td>'.$vin_query_decode["decoded_second_row_heated_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_multi_adjustable_power_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Multi-Adjustable Power Seat</td>
			<td>'.$vin_query_decode["decoded_second_row_multi_adjustable_power_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_removable_seat']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Removable Seat</td>
			<td>'.$vin_query_decode["decoded_second_row_removable_seat"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_third_row_removable_seat']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Third Row Removable Seat</td>
			<td>'.$vin_query_decode["decoded_third_row_removable_seat"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Exterior Lighting</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_automatic_headlights']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Automatic Headlights</td>
			<td>'.$vin_query_decode["decoded_automatic_headlights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_daytime_running_lights']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Daytime Running Lights</td>
			<td>'.$vin_query_decode["decoded_daytime_running_lights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_fog_lights']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Fog Lights</td>
			<td>'.$vin_query_decode["decoded_fog_lights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_high_intensity_discharge_headlights']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;High Intensity Discharge Headlights</td>
			<td>'.$vin_query_decode["decoded_high_intensity_discharge_headlights"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_pickup_truck_cargo_box_light']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Pickup Truck Cargo Box Light</td>
			<td>'.$vin_query_decode["decoded_pickup_truck_cargo_box_light"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Exterior Features</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_running_boards']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Bodyside/Cab Step or Running Board</td>
			<td>'.$vin_query_decode["decoded_running_boards"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_front_air_dam']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Front Air Dam</td>
			<td>'.$vin_query_decode["decoded_front_air_dam"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rear_spoiler']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Rear Spoiler</td>
			<td>'.$vin_query_decode["decoded_rear_spoiler"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_skid_plate']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Skid Plate or Underbody Protection</td>
			<td>'.$vin_query_decode["decoded_skid_plate"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_splash_guards']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Splash Guards</td>
			<td>'.$vin_query_decode["decoded_splash_guards"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_wind_deflector_for_convertibles']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Wind Deflector or Buffer for Convertible</td>
			<td>'.$vin_query_decode["decoded_wind_deflector_for_convertibles"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_power_sliding_side_van_door']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Power Sliding Side Van Door</td>
			<td>'.$vin_query_decode["decoded_power_sliding_side_van_door"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_power_trunk_lid']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Power Trunk Lid</td>
			<td>'.$vin_query_decode["decoded_power_trunk_lid"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Wheels</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_alloy_wheels']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Alloy Wheels</td>
			<td>'.$vin_query_decode["decoded_alloy_wheels"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_chrome_wheels']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Chrome Wheels</td>
			<td>'.$vin_query_decode["decoded_chrome_wheels"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_steel_wheels']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Steel Wheels</td>
			<td>'.$vin_query_decode["decoded_steel_wheels"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Tires</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_full_size_spare_tire']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Full Size Spare Tire</td>
			<td>'.$vin_query_decode["decoded_full_size_spare_tire"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_run_flat_tires']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Run Flat Tires</td>
			<td>'.$vin_query_decode["decoded_run_flat_tires"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Windows</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_power_windows']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Power Windows</td>
			<td>'.$vin_query_decode["decoded_power_windows"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_glass_rear_window_on_convertible']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Glass Rear Window on Convertible</td>
			<td>'.$vin_query_decode["decoded_glass_rear_window_on_convertible"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_sliding_rear_pickup_truck_window']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Sliding Rear Pickup Truck Window</td>
			<td>'.$vin_query_decode["decoded_sliding_rear_pickup_truck_window"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Mirrors</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_electrochromic_exterior_rearview_mirror']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Electrochromic Exterior Rearview Mirror</td>
			<td>'.$vin_query_decode["decoded_electrochromic_exterior_rearview_mirror"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_heated_exterior_mirror']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Heated Exterior Mirror</td>
			<td>'.$vin_query_decode["decoded_heated_exterior_mirror"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_electrochromic_interior_rearview_mirror']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Electrochromic Interior Rearview Mirror</td>
			<td>'.$vin_query_decode["decoded_electrochromic_interior_rearview_mirror"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_power_adjustable_exterior_mirror']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Power Adjustable Exterior Mirror</td>
			<td>'.$vin_query_decode["decoded_power_adjustable_exterior_mirror"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Wipers</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_interval_wipers']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Interval Wipers</td>
			<td>'.$vin_query_decode["decoded_interval_wipers"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rain_sensing_wipers']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Rain Sensing Wipers</td>
			<td>'.$vin_query_decode["decoded_rain_sensing_wipers"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rear_wiper']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Rear Wiper</td>
			<td>'.$vin_query_decode["decoded_rear_wiper"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_rear_window_defogger']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Rear Window Defogger</td>
			<td>'.$vin_query_decode["decoded_rear_window_defogger"].'</td>
			</tr>';
		}
		$x .= '<tr style="color:Black;background-color:#CCCCCC;">
			<td colspan="2"><strong>Equipment - Towings</strong></td>
			</tr>';
		if ($vin_query_decode['decoded_tow_hitch_receiver']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Tow Hitch Receiver</td>
			<td>'.$vin_query_decode["decoded_tow_hitch_receiver"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_towing_preparation_package']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Towing Preparation Package</td>
			<td>'.$vin_query_decode["decoded_towing_preparation_package"].'</td>
			</tr>';
		}
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td class="disclaimerrow" colspan="2"><strong>Disclaimer</strong></td>
	  </tr>';
	  $x .= '<tr style="">
		<td colspan="2"><div style="max-width:580px;font-size:8px;">ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,<br />
		IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY<br />
		OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY<br />
		DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.</div></td>
	  </tr>';
	$x .= '</table>';
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="Standard Option" alt="Standard Option" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="Optional" alt="Optional" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="NA" alt="NA" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}

function get_vin_query_entertainment($vin_query_decode) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$x = '
	<table width="100%" style="font-size:12px;">';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Legend</strong></td>
	  </tr>';
	  $x .= '<tr style="">
		<td colspan="2">&nbsp;&nbsp;&nbsp;Std. - Standard: indicates a manufacturer-installed feature that comes standard.<br/>
		  &nbsp;&nbsp;&nbsp;Opt. - Optional: indicates a manufacturer-installed feature that does not come standard.<br/>
		  &nbsp;&nbsp;&nbsp;N/A - Not Available: indicates a feature that is not available as a manufacturer-installed item.</td>
	  </tr>';
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td colspan="2"><strong>Equipment - Entertainment, Communication & Navigation</strong></td>
	  </tr>';
		if ($vin_query_decode['decoded_am_fm_radio']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;AM/FM Radio</td>
			<td>'.$vin_query_decode["decoded_am_fm_radio"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_cassette_player']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Cassette Player</td>
			<td>'.$vin_query_decode["decoded_cassette_player"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_cd_player']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;CD Player</td>
			<td>'.$vin_query_decode["decoded_cd_player"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_cd_changer']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;CD Changer</td>
			<td>'.$vin_query_decode["decoded_cd_changer"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_dvd_player']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;DVD Player</td>
			<td>'.$vin_query_decode["decoded_dvd_player"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_voice_activated_telephone']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Hands Free/Voice Activated Telephone</td>
			<td>'.$vin_query_decode["decoded_voice_activated_telephone"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_navigation_aid']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Navigation Aid</td>
			<td>'.$vin_query_decode["decoded_navigation_aid"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_second_row_sound_controls']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Second Row Sound Controls or Accessories</td>
			<td>'.$vin_query_decode["decoded_second_row_sound_controls"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_subwoofer']) {
			$x .= '<tr style="color:Black;background-color:#EEEEEE;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Subwoofer</td>
			<td>'.$vin_query_decode["decoded_subwoofer"].'</td>
			</tr>';
		}
		if ($vin_query_decode['decoded_telematics_system']) {
			$x .= '<tr style="color:Black;background-color:white;">
			<td style="white-space:nowrap;width:320px;">&nbsp;&nbsp;&nbsp;Telematic Systems</td>
			<td>'.$vin_query_decode["decoded_telematics_system"].'</td>
			</tr>';
		}
	  $x .= '<tr style="color:Black;background-color:#CCCCCC;">
		<td class="disclaimerrow" colspan="2"><strong>Disclaimer</strong></td>
	  </tr>';
	  $x .= '<tr style="">
		<td colspan="2"><div style="max-width:580px;font-size:8px;">ALTHOUGH THIS SITE CHECKS REGULARLY WITH ITS DATA SOURCES TO CONFIRM THE ACCURACY AND COMPLETENESS OF THE DATA,<br />
		IT MAKES NO GUARANTY OR WARRANTY, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY OR MERCHANTABILITY<br />
		OR FITNESS FOR PARTICULAR PURPOSE, WITH RESPECT TO THE DATA PRESENTED IN THIS REPORT. USER ASSUMES ALL RISKS IN USING ANY<br />
		DATA IN THIS REPORT FOR HIS OR HER OWN APPLICATIONS. ALL DATA IN THIS REPORT ARE SUBJECT TO CHANGE WITHOUT NOTICE.</div></td>
	  </tr>';
	$x .= '</table>';
	$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="Standard Option" alt="Standard Option" />';
	$x = str_replace("Std.", $standard_img, $x);
	$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="Optional" alt="Optional" />';
	$x = str_replace("Opt.", $opt_img, $x);
	$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="NA" alt="NA" />';
	$x = str_replace("N/A", $na_img, $x);
	return $x;
}
?>