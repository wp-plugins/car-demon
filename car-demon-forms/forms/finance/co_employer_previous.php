<div id="CoEmployer1" class="finance_hide_notice previous_employment">
	<div class="finance_info_cell">
		<?php _e('Co-Applicant Previous Employment', 'car-demon'); ?>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Employer Name', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="p1co_en2" id="p1co_en2" maxlength="200" tabindex="72" />
		<div id="msgDivp1co_en2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('EMPLOYER REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Position', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="p1co_p2" id="p1co_p2" maxlength="200" tabindex="73" />
		<div id="msgDivp1co_p2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('POSITION REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Years/Month at Company', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<select name="p1co_yac2" id="p1co_yac2" tabindex="74" onChange="AddCoEmployer(this,2);">
		<?php echo select_years(); ?>
		</select><span class="cd_fin_dash">-</span>
		<select id="p1co_mac2" name="p1co_mac2" tabindex="75">
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
		<div id="msgDivp1co_mac2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('MONTHS REQUIRED', 'car-demon'); ?></font>
		</div>
		<div id="msgDivp1co_yac2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('YEARS REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Employer Phone Number', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="p1co_epn2" onKeyPress="checkNum()" id="p1co_epn2" maxlength="200" tabindex="76" />
		<div id="msgDivp1co_epn2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('PHONE REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Gross Monthly Income', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="p1co_gmi2" onKeyPress="checkNum()" id="p1co_gmi2" maxlength="200" tabindex="77" />
		<div id="msgDivp1co_gmi2" class="finance_hide_notice">
			<font color="#FF0000" size="-1"><?php _e('INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
	<div width="51%" class="finance_applicant_cell">
		<?php _e('Other Income', 'car-demon'); ?>
	</div>
	<div width="49%" class="finance_applicant_cell">
		<input type="text" name="p1co_oi2" onKeyPress="checkNum()" id="p1co_oi2" tabindex="78" value="0" maxlength="200" />
		<div id="msgDivp1co_oi2" class="finance_hide_notice"><font color="#FF0000" size="-1">
			<?php _e('OTHER INCOME REQUIRED', 'car-demon'); ?></font>
		</div>
	</div>
</div>