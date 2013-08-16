<!--// START CO-APP INFO //-->
	<div class="finance_info_cell" id="tdAlert">
		<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/menu_joint.jpg" alt="Co-Signer" width="20" height="10" /> <?php _e('STEP FOUR - Co-Applicant Information', 'car-demon'); ?> <?php _e('(Optional)', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('First Name', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" id="co_fn2" maxlength="200" name="co_fn2" tabindex="48" />
		<div id="msgDivco_fn2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('FIRST NAME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>                      
	<div class="finance_applicant_cell">
		<?php _e('Middle Initial', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" id="co_mi" maxlength="1" size="2" name="co_mi" tabindex="48" />                      
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Last Name', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" id="co_ln2" maxlength="200" name="co_ln2" tabindex="49" />
		<div id="msgDivco_ln2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('LAST NAME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Home Phone Number', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" id="co_hpn2" maxlength="200" name="co_hpn2" tabindex="50" />
		<div id="msgDivco_hpn2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('PHONE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Email Address', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" id="co_ea2" maxlength="200" name="co_ea2" tabindex="51" />
		<div id="msgDivco_ea2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('EMAIL REQUIRED', 'car-demon'); ?></font>
		</div>                      
	</div>
	<div class="finance_segment_wide">
		<div class="finance_applicant_cell">
			<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/secure_sm.gif" alt="Your info is secure" width="10" height="16" /> Social Security Number
		</div>
		<div class="finance_applicant_cell">
			<input type="text" id="co_ssn2" maxlength="11" name="co_ssn2" tabindex="52" />
		</div>
		<div id="msgDivco_ssn2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('SSN REQUIRED', 'car-demon'); ?></font>
		</div>
		<div class="finance_segment_wide">
			<div align="center">
				<?php _e('( We secure and protect your confidential information )', 'car-demon'); ?>
			</div>
		</div>
	</div>
<!--// END CO-APP INFO //-->