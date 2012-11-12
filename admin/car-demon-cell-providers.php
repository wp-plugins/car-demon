<?php
function select_cell_provider($type, $current_val) {
	$us_providers = array(	'None' => ' ',
		'Alltel Wireless' => 'message.alltel.com',
		'AT&amp;T' => 'txt.att.net',
		'Bell Atlantic' => 'message.bam.com',
		'Bell South' => 'sms.bellsouth.com',
		'Boost Mobile' => 'myboostmobile.com',
		'Cellular One' => 'mobile.celloneusa.com',
		'Centennial Wireless' => 'cwemail.com',
		'Cincinnati Bell' => 'mobile.att.net',
		'Cingular (AT&amp;T)' => 'mycingular.textmsg.com',
		'Cricket' => 'mms.mycricket.com',
		'GTE' => 'messagealert.com',
		'Helio' => 'messaging.sprintpcs.com',
		'MCI' => 'pagemci.com',
		'Nextel' => 'messaging.nextel.com',
		'Pacific Bell' => 'pacbellpcs.net',
		'PCS One' => 'pcsone.net',
		'Qwest' => 'qwestmp.com',
		'Sprint' => 'messaging.sprintpcs.com',
		'T Mobile' => 'tmomail.net',
		'Tracfone' => 'cingularme.com',
		'US Cellular' => 'email.uscc.net',
		'Verizon' => 'vtext.com',
		'Virgin Mobile' => 'vmobl.com'
	);
	$x = '<select id="'.$type.'" name="'.$type.'" class="admin_cell_providers">';
		foreach ($us_providers as $us_provider) {
			if ($us_provider == $current_val) {
				$select = ' selected';
			}
			else {
				$select = '';
			}
			$x .= '<option value="'.$us_provider .'"'.$select.'>'. $us_provider .'</option>';
		}
	$x .= '</select>';
	return $x;
}
?>