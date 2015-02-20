<div id="CoEmployer">
	<div class="finance_info_cell">
		<?php _e('STEP FIVE  - Co-Applicant Employment Info', 'car-demon'); ?>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Employer Name', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="co_en2" id="co_en2" maxlength="200" tabindex="65" />
		<div id="msgDivco_en2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('EMPLOYER REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div class="finance_applicant_cell">
		<?php _e('Position', 'car-demon'); ?>
	</div>
	<div class="finance_applicant_cell">
		<input type="text" name="co_p2" id="co_p2" maxlength="200" tabindex="66" />
		<div id="msgDivco_p2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('POSITION REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Years/Month at Company', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<select name="co_yac2" id="co_yac2" tabindex="67" onChange="AddCoEmployer(this,1);">
		<?php echo select_years(); ?>
		</select><span class="cd_fin_dash">-</span>
		<select id="co_mac2" name="co_mac2" tabindex="68">
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
		<div id="msgDivco_yac2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('YEARS REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivco_mac2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('MONTHS REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Employer Phone Number', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="co_epn2" onKeyPress="checkNum()" id="co_epn2" maxlength="200" tabindex="69" />
		<div id="msgDivco_epn2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('PHONE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Gross Monthly Income', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="co_gmi2" onKeyPress="checkNum()" id="co_gmi2" maxlength="200" tabindex="70" />
		<div id="msgDivco_gmi2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Other Income', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="co_oi2" id="co_oi2" onKeyPress="checkNum()" tabindex="71" value="0" maxlength="200" />
		<div id="msgDivco_oi2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('OTHER INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div id="CoEmployer2" class="finance_hide_notice"></div>
	<div id="CoEmployer3" class="finance_hide_notice"></div>
</div>