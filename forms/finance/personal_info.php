<!--// PERSONAL INFO //-->
<div class="finance_info_cell" id="tdAlert">
<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/menu_single.jpg" alt="Applicant" width="10" height="10" />
<?php _e('STEP ONE - Personal Info', 'car-demon'); ?>
</div>
<div class="personal_info">
<div class="finance_applicant_cell">
	<span>* </span><?php _e('First Name', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" name="fn" id="fn" value="" maxlength="200" tabindex="1" />
	<div id="msgDivfn" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('FIRST NAME REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<span>*</span> <?php _e('Middle Initial', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" name="mi" id="mi" tabindex="2" size="2" maxlength="1" />
</div>
<div class="finance_applicant_cell">
	<span>* </span><?php _e('Last Name', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" name="ln" id="ln" value="" maxlength="200" tabindex="2" />
	<div id="msgDivln" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('LAST NAME REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<span>* </span><?php _e('Phone Number', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" name="hpn" id="hpn" onKeyPress="checkNum()" value="" maxlength="200" tabindex="3" />
	<div id="msgDivhpn" class="finance_hide_notice"><font color="#FF0000" size="-1">
		<?php _e('PHONE REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<span>* </span><?php _e('Email Address', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" id="ea" maxlength="200" name="ea" tabindex="4" />
	<div id="msgDivea" class="finance_hide_notice"><font color="#FF0000" size="-1">
		<?php _e('EMAIL REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell">
	<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/secure_sm.gif" alt="<?php _e('Your info is secure', 'car-demon'); ?>" width="10" height="16" /> <?php _e('Social Security Number', 'car-demon'); ?>
</div>
<div class="finance_applicant_cell">
	<input type="text" id="ssn" maxlength="11"  onKeyPress="checkNum()" name="ssn" tabindex="5" />
	<div id="msgDivssn" class="finance_hide_notice">
		<font color="#FF0000" size="-1"><?php _e('SSN REQUIRED', 'car-demon'); ?></font>
	</div>
</div>
<div class="finance_applicant_cell_wide">
	( <?php _e('We secure and protect your confidential information', 'car-demon'); ?> )
</div>
</div>
<!--// END PERSONAL INFO //-->