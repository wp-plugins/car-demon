<div class="finance_applicant_cell">
	<?php _e('Apt/Suite', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input name="co_app_apt_num" type="text" id="co_app_apt_num" tabindex="53" size="6" maxlength="5" />
	<div id="msgDivco_app_apt_num" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('APT/SUITE REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('Street Number', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input name="co_app_street_num" type="text" onKeyPress="checkNum()" id="co_app_street_num" tabindex="54" size="6" maxlength="6" onChange="ValidateCoAppAddress();" />
	<div id="msgDivco_app_street_num" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('STREET NUM REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('Street Name', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input name="co_app_street_name" type="text" maxlength="20" id="co_app_street_name" tabindex="55" />
	<div id="msgDivco_app_street_name" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('STREET NAME REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('Street Type', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<select name="co_app_street_type" id="co_app_street_type" tabindex="56" class="select">
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
	<div id="msgDivco_app_street_type" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('STREET TYPE REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('PO Box', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input name="co_app_po_box_num" type="text" id="co_app_po_box_num" tabindex="57" size="6" maxlength="6" onChange="ValidateCoAppAddress();" />
	<div id="msgDivco_app_po_box_num" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('PO BOX REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('Rural Route', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input name="co_app_rural_route" type="text" id="co_app_rural_route" tabindex="58" size="6" maxlength="6" onChange="ValidateCoAppAddress();" />
	<div id="msgDivco_app_rural_route" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('RURAL ROUTE REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('City', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" id="co_cty2" maxlength="200" name="co_cty2" tabindex="59" />
	<div id="msgDivco_cty2" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('CITY REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('State', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<select name="co_st2" id="co_st2" tabindex="60" class="select finance_select">
		<option></option>
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
	<div id="msgDivco_st2" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('STATE REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	Zip
</div>
<div class="finance_applicant_cell">
	<input type="text" id="co_zi2" onKeyPress="checkNum()" maxlength="200" name="co_zi2" tabindex="61" />
	<div id="msgDivco_zi2" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('ZIP REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<?php _e('Birth Date', 'car-demon'); ?> <?php _e('(Month-Date-Year)', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<select name="co_bdm" id="co_bdm" tabindex="62">
		<option></option>
		<option value="1"><?php _e('Jan', 'car-demon'); ?></option>
		<option value="2"><?php _e('Feb', 'car-demon'); ?></option>
		<option value="3"><?php _e('Mar', 'car-demon'); ?></option>
		<option value="4"><?php _e('Apr', 'car-demon'); ?></option>
		<option value="5"><?php _e('May', 'car-demon'); ?></option>
		<option value="6"><?php _e('Jun', 'car-demon'); ?></option>
		<option value="7"><?php _e('Jul', 'car-demon'); ?></option>
		<option value="8"><?php _e('Aug', 'car-demon'); ?></option>
		<option value="9"><?php _e('Sep', 'car-demon'); ?></option>
		<option value="10"><?php _e('Oct', 'car-demon'); ?></option>
		<option value="11"><?php _e('Nov', 'car-demon'); ?></option>
		<option value="12"><?php _e('Dec', 'car-demon'); ?></option>
	</select><span class="cd_fin_dash">-</span>
	<select name="co_bdd" id="co_bdd" tabindex="63">
		<option></option>
		<?php
		echo get_the_days();
		?>
	</select><span class="cd_fin_dash">-</span>
	<select name="co_bdy" id="co_bdy" tabindex="64">
		<option></option>
		<?php
		echo get_the_years();
		?>
	</select>
	<div id="msgDivco_bdd" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('DAY REQUIRED', 'car-demon'); ?></font>
	</div>
	<div id="msgDivco_bdm" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('MONTH REQUIRED', 'car-demon'); ?></font>
	</div>
	<div id="msgDivco_bdy" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('YEAR REQUIRED', 'car-demon'); ?></font>
	</div>
</div>