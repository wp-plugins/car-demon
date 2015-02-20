<!--// PREVIOUS EMPLOYMENT INFO //-->
<div id="AppEmployer1" class="finance_hide_notice previous_employment">
	<div class="finance_info_cell">
		<span><?php _e('PREVIOUS EMPLOYMENT', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Employer Name', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="p2en" id="p2en" maxlength="200" tabindex="25" />
		<div id="msgDivp2en" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('EMPLOYER REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Position', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="p2p" id="p2p" maxlength="200" tabindex="26" />
		<div id="msgDivp2p" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('POSITION REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Years/Month at Company', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<select name="p2yac" id="p2yac" tabindex="27" onChange="AddEmployers(this,2);">
			<?php echo select_years(); ?>
		</select>
		<span class="cd_fin_dash">-</span>
		<select id="p2mac" name="p2mac" tabindex="28">
			<option value="" selected="selected"></option>
			<option value="0">0 <?php _e('Months', 'car-demon'); ?></option>
			<option value="1">1 <?php _e('Months', 'car-demon'); ?></option>
			<option value="2">2 <?php _e('Months', 'car-demon'); ?></option>
			<option value="3">3 <?php _e('Months', 'car-demon'); ?></option>
			<option value="4">4 <?php _e('Months', 'car-demon'); ?></option>
			<option value="5">5 <?php _e('Months', 'car-demon'); ?></option>
			<option value="6">6 <?php _e('Months', 'car-demon'); ?></option>
			<option value="7">7 <?php _e('Months', 'car-demon'); ?></option>
			<option value="8">8 <?php _e('Months', 'car-demon'); ?></option>
			<option value="9">9 <?php _e('Months', 'car-demon'); ?></option>
			<option value="10">10 <?php _e('Months', 'car-demon'); ?></option>
			<option value="11">11 <?php _e('Months', 'car-demon'); ?></option>
		</select>
	</div>
	<div id="msgDivp2mac" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('MONTHS REQUIRED', 'car-demon'); ?></font>
	</div>
	<div id="msgDivp2yac" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('YEARS REQUIRED', 'car-demon'); ?></font>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Employer Phone Number', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="p2epn" onKeyPress="checkNum()" id="p2epn" maxlength="200" tabindex="29" />
		<div id="msgDivp2epn" class="finance_hide_notice"><font color="#FF0000" size="-1">
			<?php _e('PHONE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Gross Monthly Income', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="p2gmi" id="p2gmi" onKeyPress="checkNum()" maxlength="200" tabindex="30" />
		<div id="msgDivp2gmi" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Other Income', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="p2oi" id="p2oi" onKeyPress="checkNum()" tabindex="31" value="0" maxlength="200" />
		<div id="msgDivp2oi" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('OTHER INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div id="AppEmployer2" class="finance_hide_notice"></div>
	<div id="AppEmployer3" class="finance_hide_notice"></div>
</div>
<!--// END PREVIOUS EMPLOYMENT INFO //-->