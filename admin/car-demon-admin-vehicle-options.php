<?php
function cd_get_vehicle_map() {
	$map = array();
	$map = get_option('cd_vehicle_option_map');
	if (!isset($map['description'])) {
		$map['description'] = get_default_description_maps();
	}
	if (!isset($map['specs'])) {
		$map['specs'] = get_default_specs_maps();
	}
	if (!isset($map['safety'])) {
		$map['safety'] = get_default_safety_maps();
	}
	if (!isset($map['convenience'])) {
		$map['convenience'] = get_default_convenience_maps();
	}
	if (!isset($map['comfort'])) {
		$map['comfort'] = get_default_comfort_maps();
	}
	if (!isset($map['entertainment'])) {
		$map['entertainment'] = get_default_entertainment_maps();
	}
	if (!isset($map['about_us'])) {
		$map['about_us'] = get_default_about_us_maps();
	}
	return $map;
}

function get_default_description_maps() {
	$map = array();
	return $map;
}
function get_default_specs_maps() {
	$map = array();
	$map[__('Specifications', 'car-demon')] = __('Trim Level, Production Seq. Number, Exterior Color, Interior Color, Manufactured in, Engine Type, Transmission, Driveline, Tank (gallon),Fuel Economy (City miles/gallon),Fuel Economy (Highway miles/gallon),Anti-Brake System,Steering Type,Length (in.),Width (in.),Height (in.)','car-demon');
	return $map;
}
function get_default_safety_maps() {
	$map = array();
	$map[__('Equipment - Anti-Theft & Locks','car-demon')] = __('Child Safety Door Locks,Locking Pickup Truck Tailgate,Power Door Locks,Vehicle Anti-Theft', 'car-demon');
	$map[__('Equipment - Braking & Traction','car-demon')] = __('4WD/AWD,ABS(2-Wheel/4-Wheel),Automatic Load-Leveling,Electronic Brake Assistance,Limited Slip Differential,Locking Differential,Traction Control,Vehicle Stability Control System', 'car-demon');
	$map[__('Equipment - Safety','car-demon')] = __('Driver Airbag,Front Side Airbag,Front Side Airbag with Head Protection,Passenger Airbag,Side Head Curtain Airbag,Second Row Side Airbag,Second Row Side Airbag with Head Protection,Electronic Parking Aid,First Aid Kit,Trunk Anti-Trap Device', 'car-demon');
	return $map;
}
function get_default_convenience_maps() {
	$map = array();
	$map[__('Equipment - Remote Controls & Release','car-demon')] = __('Keyless Entry,Remote Ignition', 'car-demon');
	$map[__('Equipment - Interior Features','car-demon')] = __('Cruise Control,Tachometer,Tilt Steering Wheel,Tilt Steering Column,Heated Steering Wheel,Leather Steering Wheel,Steering Wheel Mounted Controls,Telescopic Steering Column,Adjustable Foot Pedals,Genuine Wood Trim,Tire Inflation/Pressure Monitor,Trip Computer', 'car-demon');
	$map[__('Equipment - Storage','car-demon')] = __('Cargo Area Cover,Cargo Area Tiedowns,Cargo Net,Load Bearing Exterior Rack,Pickup Truck Bed Liner', 'car-demon');
	$map[__('Equipment - Roof','car-demon')] = __('Power Sunroof/Moonroof,Manual Sunroof/Moonroof,Removable/Convertible Top', 'car-demon');
	$map[__('Equipment - Climate Control','car-demon')] = __('Air Conditioning,Separate Driver/Front Passenger Climate Controls', 'car-demon');
	return $map;
}
function get_default_comfort_maps() {
	$map = array();
	$map[__('Equipment - Seat','car-demon')] = __('Driver Multi-Adjustable Power Seat,Front Cooled Seat,Front Heated Seat,Front Power Lumbar Support,Front Power Memory Seat,Front Split Bench Seat,Leather Seat,Passenger Multi-Adjustable Power Seat,Second Row Folding Seat,Second Row Heated Seat,Second Row Multi-Adjustable Power Seat,Second Row Removable Seat,Third Row Removable Seat', 'car-demon');
	$map[__('Equipment - Exterior Lighting','car-demon')] = __('Automatic Headlights,Daytime Running Lights,Fog Lights,High Intensity Discharge Headlights,Pickup Truck Cargo Box Light', 'car-demon');
	$map[__('Equipment - Exterior Features','car-demon')] = __('Bodyside/Cab Step or Running Board,Front Air Dam,Rear Spoiler,Skid Plate or Underbody Protection,Splash Guards,Wind Deflector or Buffer for Convertible,Power Sliding Side Van Door,Power Trunk Lid', 'car-demon');
	$map[__('Equipment - Wheels','car-demon')] = __('Alloy Wheels,Chrome Wheels,Steel Wheels', 'car-demon');
	$map[__('Equipment - Tires','car-demon')] = __('Full Size Spare Tire,Run Flat Tires', 'car-demon');
	$map[__('Equipment - Windows','car-demon')] = __('Power Windows,Glass Rear Window on Convertible,Sliding Rear Pickup Truck Window', 'car-demon');
	$map[__('Equipment - Mirrors','car-demon')] = __('Electrochromic Exterior Rearview Mirror,Heated Exterior Mirror,Electrochromic Interior Rearview Mirror,Power Adjustable Exterior Mirror', 'car-demon');
	$map[__('Equipment - Wipers','car-demon')] = __('Interval Wipers,Rain Sensing Wipers,Rear Wiper,Rear Window Defogger', 'car-demon');
	$map[__('Equipment - Towings','car-demon')] = __('Tow Hitch Receiver,Towing Preparation Package', 'car-demon');
	return $map;
}
function get_default_entertainment_maps() {
	$map = array();
	$map[__('Equipment - Entertainment, Communication & Navigation','car-demon')] = __('AM/FM Radio,Cassette Player,CD Player,CD Changer,DVD Player,Hands Free/Voice Activated Telephone,Navigation Aid,Second Row Sound Controls or Accessories,Subwoofer,Telematic Systems', 'car-demon');
	return $map;
}
function get_default_about_us_maps() {
	$map = array();
	return $map;
}

//= Display tabs for front end and admin ($type)
//===================================================
function get_option_tab($tab, $post_id, $type='') {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('vin-query', '', $car_demon_pluginpath);
	$vin_query_decode_array = get_post_meta($post_id, 'decode_string');
	if ($vin_query_decode_array) {
		$vin_query_decode = $vin_query_decode_array[0];
	} else {
		$vin_query_decode = '';
	}
	$vehicle_option_array = get_post_meta($post_id, '_vehicle_options', true);
	$vehicle_option_array = explode(',',$vehicle_option_array);
	$map = cd_get_vehicle_map();
	$flag = '';
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
	if (isset($map[$tab])) {
		foreach($map[$tab] as $tab_group => $value) {
			//= Loop through all of the tab option groups, get their items
			$div_flag = false;
			$x .= '<tr class="decode_table_header">
				<td colspan="2"><strong>'.$tab_group.'</strong></td>
			  </tr>';
			if (!empty($value)) {
				$option_array = explode(',',$value);
				if($option_array) {
					foreach($option_array as $option) {
						if ($flag == true) {
							$class = 'decode_table_even';
							$flag = false;
						} else {
							$class = 'decode_table_odd';
							$flag = true;
						}
						//= Loop through list of items in each group
						$option = trim($option);
						$slug = strtolower($option);
						$slug = str_replace(' ','_',$slug);
						$slug = str_replace('/','_',$slug);
						$slug = str_replace('(','_',$slug);
						$slug = str_replace(')','_',$slug);
						$slug = str_replace('-','_',$slug);
						$slug = str_replace('2_wheel_4_wheel_','brakes',$slug);
						if ($type == 'admin') {
							if (isset($vin_query_decode['decoded_'.$slug])) {
								$content = decode_select('decoded_'.$slug, $vin_query_decode['decoded_'.$slug], $post_id);
							} else {
								$content = decode_select('decoded_'.$slug, '', $post_id);
							}
							$x .= '<tr class="'.$class.'">
								<td class="decode_table_label">'.$option.'</td>
								<td>'.$content.'</td>
								</tr>';
						} else {
							if (isset($vin_query_decode['decoded_'.$slug])) {
								$content = $vin_query_decode['decoded_'.$slug];
								if (isset($vin_query_decode['decoded_'.$slug]) || isset($vin_query_decode[$slug]) || isset($vin_query_decode['decoded_'.$option]) || isset($vin_query_decode[$option])) {
									if(!empty($content)) {
										$x .= '<tr class="'.$class.'">
											<td class="decode_table_label">'.$option.'</td>
											<td>'.$content.'</td>
											</tr>';
									}
								}
							}
						}
					}
				}
				//= Loop through option meta and get those too
				if (!empty($vehicle_option_array)) {
					foreach($vehicle_option_array as $option_item) {
						$option_item = trim($option_item);
						$option_item = ucwords($option_item);
						if (in_array($option_item, $option_array)) {
							$label = str_replace('_',' ',$option_item);
							$label = ucwords($label);
							if ($type == 'admin') {
								$content = decode_select('decoded_'.$slug, 'Std.', $post_id);
								$x .= '<tr class="'.$class.'">
									<td class="decode_table_label">'.$label.'</td>
									<td>'.$content.'</td>
									</tr>';
							} else {
								$content = 'Std.';	
								if (!in_array('decoded_'.$slug, $vin_query_decode)) {
									if (!empty($content)) {
										$x .= '<tr class="'.$class.'">
											<td class="decode_table_label">'.$label.'</td>
											<td>'.$content.'</td>
											</tr>';
									}
								}
							}
						}
					}
				}
				//= End Option Loop
			}
		}
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
	if ($type != 'admin') {
		$standard_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_standard.gif" title="'.__('Standard Option', 'car-demon').'" alt="'.__('Standard Option', 'car-demon').'" />';
		$x = str_replace("Std.", $standard_img, $x);
		$opt_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_optional.gif" title="'.__('Optional', 'car-demon').'" alt="'.__('Optional', 'car-demon').'" />';
		$x = str_replace("Opt.", $opt_img, $x);
		$na_img = '<img src="'.$car_demon_pluginpath . 'theme-files/images/opt_na.gif" title="'.__('NA', 'car-demon').'" alt="'.__('NA', 'car-demon').'" />';
		$x = str_replace("N/A", $na_img, $x);
	}
	return $x;
}
?>