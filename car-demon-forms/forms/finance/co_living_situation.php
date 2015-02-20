<div id="CoAppAddress" class="living_situation">
	<div class="finance_info_cell">
		<?php _e('STEP SIX  - Co-Applicant Living Situation', 'car-demon'); ?>
	</div>
	<div width="47%" class="finance_applicant_cell">
		<?php _e('Years/Month at Current Address', 'car-demon'); ?>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<select name="co_yaca2" id="co_yaca2" tabindex="79" onChange="AddCoAddresss(this,1);">
			<?php echo select_years(); ?>
		</select><span class="cd_fin_dash">-</span>
		<select id="co_maca2" name="co_maca2" tabindex="80" >
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
		<div id="msgDivco_yaca2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('YEARS REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivco_maca2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('MONTHS REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="47%" class="finance_applicant_cell">
		<?php _e('Do you Own or Rent ?', 'car-demon'); ?>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<select id="select3" name="co_roo2" tabindex="81" >
			<option value="<?php _e('Own', 'car-demon'); ?>" selected="selected"><?php _e('Own', 'car-demon'); ?></option>
			<option value="<?php _e('Rent', 'car-demon'); ?>"><?php _e('Rent', 'car-demon'); ?></option>
			<option value="<?php _e('Relatives', 'car-demon'); ?>"><?php _e('Relatives', 'car-demon'); ?></option>
		</select>
		<div id="msgDivco_roo2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="47%" class="finance_applicant_cell">
		<?php _e('Rent Amount/Mortgage Payment $', 'car-demon'); ?>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<input type="text" id="co_ramp2" maxlength="200" onKeyPress="checkNum()" name="co_ramp2" tabindex="82" />
		<div id="msgDivco_ramp2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="47%" class="finance_applicant_cell">
		<?php _e('Best Contact Place And Time', 'car-demon'); ?>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<select id="co_bcp2" name="co_bcp2" tabindex="83" >
			<option value="<?php _e('Home', 'car-demon'); ?>" selected="selected"><?php _e('Home', 'car-demon'); ?></option>
			<option value="<?php _e('Work', 'car-demon'); ?>"><?php _e('Work', 'car-demon'); ?></option>
			<option value=" "></option>
		</select>
		<select id="co_bct2" name="co_bct2" tabindex="84" >
			<option value="<?php _e('Morning   ', 'car-demon'); ?>" selected="selected"><?php _e('Morning', 'car-demon'); ?></option>
			<option value="<?php _e('Afternoon ', 'car-demon'); ?>"><?php _e('Afternoon', 'car-demon'); ?></option>
			<option value="<?php _e('Evening   ', 'car-demon'); ?>"><?php _e('Evening', 'car-demon'); ?></option>
		</select>
		<div id="msgDivco_bcp2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivco_bct2 " class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
</div>