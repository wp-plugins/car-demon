<div id="CoAppAddress1" class="finance_hide_notice living_situation">
	<div class="finance_info_cell">
		<?php _e('Previous Co-Applicant Address', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('Apt/Suite', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input name="p1co_app_apt_num" type="text" id="p1co_app_apt_num" tabindex="85" size="6" maxlength="5" />
		<div id="msgDivp1co_app_apt_num" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('APT/SUITE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('Street Number', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input name="p1co_app_street_num" type="text" id="p1co_app_street_num" onKeyPress="checkNum()" tabindex="86" size="6" maxlength="6" onChange="ValidateCoAppPrevAddress();" />
		<div id="msgDivp1co_app_street_num" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('STREET NUM REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('Street Name', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input name="p1co_app_street_name" type="text" maxlength="20" id="p1co_app_street_name" tabindex="87" />
		<div id="msgDivp1co_app_street_name" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('STREET NAME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('Street Type', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<select name="p1co_app_street_type" id="p1co_app_street_type" tabindex="88" class="select">
			<option selected="selected" value=""></option>
			<option value="<?php _e('AVENUE', 'car-demon'); ?>"><?php _e('AVENUE', 'car-demon'); ?></option>
			<option value="<?php _e('BOULEVARD', 'car-demon'); ?>"><?php _e('BOULEVARD', 'car-demon'); ?></option>
			<option value="<?php _e('CIRCLE', 'car-demon'); ?>"><?php _e('CIRCLE', 'car-demon'); ?></option>
			<option value="<?php _e('COURT', 'car-demon'); ?>"><?php _e('COURT', 'car-demon'); ?></option>
			<option value="<?php _e('CRESCENT', 'car-demon'); ?>"><?php _e('CRESCENT', 'car-demon'); ?></option>
			<option value="<?php _e('DRIVE', 'car-demon'); ?>"><?php _e('DRIVE', 'car-demon'); ?></option>
			<option value="<?php _e('FREEWAY', 'car-demon'); ?>"><?php _e('FREEWAY', 'car-demon'); ?></option>
			<option value="<?php _e('HIGHWAY', 'car-demon'); ?>"><?php _e('HIGHWAY', 'car-demon'); ?></option>
			<option value="<?php _e('LANE', 'car-demon'); ?>"><?php _e('LANE', 'car-demon'); ?></option>
			<option value="<?php _e('PARKWAY', 'car-demon'); ?>"><?php _e('PARKWAY', 'car-demon'); ?></option>
			<option value="<?php _e('PLACE', 'car-demon'); ?>"><?php _e('PLACE', 'car-demon'); ?></option>
			<option value="<?php _e('PLAZA', 'car-demon'); ?>"><?php _e('PLAZA', 'car-demon'); ?></option>
			<option value="<?php _e('ROAD', 'car-demon'); ?>"><?php _e('ROAD', 'car-demon'); ?></option>
			<option value="<?php _e('SQUARE', 'car-demon'); ?>"><?php _e('SQUARE', 'car-demon'); ?></option>
			<option value="<?php _e('STREET', 'car-demon'); ?>"><?php _e('STREET', 'car-demon'); ?></option>
			<option value="<?php _e('TERRACE', 'car-demon'); ?>"><?php _e('TERRACE', 'car-demon'); ?></option>
			<option value="<?php _e('TRAIL', 'car-demon'); ?>"><?php _e('TRAIL', 'car-demon'); ?></option>
			<option value="<?php _e('TURNPIKE', 'car-demon'); ?>"><?php _e('TURNPIKE', 'car-demon'); ?></option>
			<option value="<?php _e('WAY', 'car-demon'); ?>"><?php _e('WAY', 'car-demon'); ?></option>
		</select>
		<div id="msgDivp1co_app_street_type" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('STREET TYPE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('PO Box', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input name="p1co_app_po_box_num" type="text" id="p1co_app_po_box_num" tabindex="89" size="6" maxlength="6" onChange="ValidateCoAppPrevAddress();" />
		<div id="msgDivp1co_app_po_box_num" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('PO BOX REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('Rural Route', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input name="p1co_app_rural_route" type="text" id="p1co_app_rural_route" tabindex="90" size="6" maxlength="6" onChange="ValidateCoAppPrevAddress();" />
		<div id="msgDivp1co_app_rural_route" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('RURAL ROUTE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('City', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input type="text" id="p1co_cty2" maxlength="200" name="p1co_cty2" tabindex="91" />
		<div id="msgDivp1co_cty2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('CITY REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('State', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<select name="p1co_st2" id="p1co_st2" tabindex="92" class="select finance_select">
			<option selected="selected"></option>
			<option value="AK">AK</option>
			<option value="AL">AL</option>
			<option value="AR">AR</option>
			<option value="AS">AS</option>
			<option value="AZ">AZ</option>
			<option value="CA">CA</option>
			<option value="CO">CO</option>
			<option value="CT">CT</option>
			<option value="DC">DC</option>
			<option value="DE">DE</option>
			<option value="FL">FL</option>
			<option value="GA">GA</option>
			<option value="GU">GU</option>
			<option value="HI">HI</option>
			<option value="IA">IA</option>
			<option value="ID">ID</option>
			<option value="IL">IL</option>
			<option value="IN">IN</option>
			<option value="KS">KS</option>
			<option value="KY">KY</option>
			<option value="LA">LA</option>
			<option value="MA">MA</option>
			<option value="MD">MD</option>
			<option value="ME">ME</option>
			<option value="MI">MI</option>
			<option value="MN">MN</option>
			<option value="MO">MO</option>
			<option value="MP">MP</option>
			<option value="MS">MS</option>
			<option value="MT">MT</option>
			<option value="NC">NC</option>
			<option value="ND">ND</option>
			<option value="NE">NE</option>
			<option value="NH">NH</option>
			<option value="NJ">NJ</option>
			<option value="NM">NM</option>
			<option value="NV">NV</option>
			<option value="NY">NY</option>
			<option value="OH">OH</option>
			<option value="OK">OK</option>
			<option value="OR">OR</option>
			<option value="PA">PA</option>
			<option value="PR">PR</option>
			<option value="RI">RI</option>
			<option value="SC">SC</option>
			<option value="SD">SD</option>
			<option value="TN">TN</option>
			<option value="TX">TX</option>
			<option value="UT">UT</option>
			<option value="VA">VA</option>
			<option value="VI">VI</option>
			<option value="VT">VT</option>
			<option value="WA">WA</option>
			<option value="WI">WI</option>
			<option value="WV">WV</option>
			<option value="WY">WY</option>
		</select>
		<div id="msgDivp1co_st2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('STATE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_coapplicant_cell">
		<?php _e('Zip', 'car-demon'); ?>
	</div>
	<div class="finance_coapplicant_cell">
		<input type="text" id="p1co_zi2" onKeyPress="checkNum()" maxlength="200" name="p1co_zi2" tabindex="93" />
		<div id="msgDivp1co_zi2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('ZIP REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div id="CoAppAddress2" class="finance_hide_notice"></div>
	<div id="CoAppAddress3" class="finance_hide_notice"></div>
</div>