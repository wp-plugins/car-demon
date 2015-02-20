<!--// EMPLOYMENT INFO //-->
<div id="AppEmployer">
	<div class="finance_info_cell">
		<span><?php _e('STEP TWO  - Employment Info', 'car-demon'); ?> </span>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Employer Name', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="en" id="en" maxlength="200" tabindex="18" />
		<div id="msgDiven" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('EMPLOYER REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Position', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="p" id="p" maxlength="200" tabindex="19" />
		<div id="msgDivp" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('POSITION REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Years/Month at Company', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<span>
			<select name="yac" id="yac" tabindex="20" onChange="AddEmployers(this,1);">
				<?php echo select_years(); ?>
			 </select><span class="cd_fin_dash">-</span>
			  <select id="mac" name="mac" tabindex="21">
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
		</span>
		<div id="msgDivyac" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('YEARS REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivmac" class="finance_hide_notice">
		`	<font color="#FF0000" size="-1"><?php _e('MONTHS REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Employer Phone Number', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="epn" id="epn" onKeyPress="checkNum()" maxlength="200" tabindex="22" />
		<div id="msgDivepn" class="finance_hide_notice"><font color="#FF0000" size="-1">
			<?php _e('PHONE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Gross Monthly Income', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="gmi" id="gmi" maxlength="200" onKeyPress="checkNum()" tabindex="23" />
		<div id="msgDivgmi" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<span><?php _e('Other Income', 'car-demon'); ?></span>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="oi" id="oi" tabindex="24" onKeyPress="checkNum()" value="0" maxlength="200" />
		<div id="msgDivoi" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('OTHER INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
</div>
<!--// END EMPLOYMENT INFO //-->