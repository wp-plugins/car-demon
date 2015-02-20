<!--// LIVING SITUATION //-->
<div id="AppAddress" class="living_situation">
	<div class="finance_info_cell">
		<?php _e('STEP THREE - Living Situation', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Years/Month at Current Address', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<select name="yaca" id="yaca" tabindex="32" onChange="AddAddresses(this,1);">
			<?php echo select_years(); ?>
		</select><span class="cd_fin_dash">-</span>
		<select id="maca" name="maca" tabindex="33">
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
		<div id="msgDivyaca" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('YEARS REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivmaca" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('MONTHS REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Do you Own or Rent ?', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<select id="roo" name="roo" tabindex="34">
			<option value="<?php _e('Own', 'car-demon'); ?>" selected="selected"><?php _e('Own', 'car-demon'); ?></option>
			<option value="<?php _e('Rent', 'car-demon'); ?>"><?php _e('Rent', 'car-demon'); ?></option>
			<option value="<?php _e('Relatives', 'car-demon'); ?>"><?php _e('Relatives', 'car-demon'); ?></option>
		</select>
		<div id="msgDivroo" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Rent Amount/Mortgage Payment $', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" id="ramp" maxlength="200" name="ramp" onKeyPress="checkNum()" tabindex="35" />
		<div id="msgDivramp" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Best Contact Place And Time', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<select id="bcp" name="bcp" tabindex="36" >
			<option value="<?php _e('Home', 'car-demon'); ?>" selected="selected"><?php _e('Home', 'car-demon'); ?></option>
			<option value="<?php _e('Work', 'car-demon'); ?>"><?php _e('Work', 'car-demon'); ?></option>
			<option value=" "></option>
		</select>
		<select id="bct" name="bct" tabindex="37" >
			<option value="<?php _e('Morning', 'car-demon'); ?>   " selected="selected"><?php _e('Morning', 'car-demon'); ?></option>
			<option value="<?php _e('Afternoon', 'car-demon'); ?> "><?php _e('Afternoon', 'car-demon'); ?></option>
			<option value="<?php _e('Evening', 'car-demon'); ?>   "><?php _e('Evening', 'car-demon'); ?></option>
		</select>
		<div id="msgDivbcp" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivbct" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Comments', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">&nbsp;</div>
	<div class="finance_applicant_cell">
		<textarea name="comment" id="comment" rows="5" class="finance_comment" tabindex="38"></textarea>
	</div>
</div>
<!--// END LIVING SITUATION //-->