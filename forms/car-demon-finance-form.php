<?php
function car_demon_finance_form() {
	$x = '';
	show_finance_form();
	return $x;
}

function show_finance_form() {
	$stock_num = '';
	$vin = '';
	$location = '';
	$bad = '';
	$fin = '';
	include('js/car-demon-finance-form-js.php');
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('/forms', '', $car_demon_pluginpath);
	if ($_SESSION['car_demon_options']['secure_finance'] == 'Yes') {
		if ( empty( $_SERVER['HTTPS'] ) ) {
			$bad = '<p align="center" style="font-size:18px;font-weight:bold;color:#FF0000;">'.__('SOMETHING WENT WRONG! THIS PAGE IS NOT SECURE!', 'car-demon').'</p>';
			$bad .= '<p align="center" style="font-size:18px;font-weight:bold;color:#FF0000;">'.__('THIS FORM HAS BEEN DISABLED FOR YOUR PROTECTION.', 'car-demon').'</p>';
			$bad .= '<p align="center" style="font-size:18px;font-weight:bold;color:#FF0000;">'.__('PLEASE CONTACT THE SITE ADMINISTRATOR FOR MORE INFORMATION.', 'car-demon').'</p>';
			echo $bad;
		}
	}
?>
<style>
.main_finance_table {
	width: 960px;
	text-align:center;
}
.sub_finance_table {
	width: 805px;
}
.finance_column {
	width: 400px;
}
.fin_legend {
	text-align: left;
}
</style>
	<div id="form_results"></div>
	<div class="body_content_credit" id="body_content_credit">
	<form name="frm_app" id="frm_app" action="CreditApp_ex.asp" method="post" class="cdform" style="margin-left:0px;">
	<input type="hidden" name="stock_num" value="<?php echo $stock_num; ?>" />
	<input type="hidden" name="vin" value="<?php echo $vin; ?>" />
	<input type="hidden" name="location" value="<?php echo $location; ?>" />
	<?php 
		global $cd_formKey;
		$cd_formKey->outputKey();
	?>
	<table class="main_finance_table" border="0" cellpadding="0" cellspacing="0" style="color:#000000;">
	<tbody>
		<tr>
			<td valign="top" bgcolor="#e3e3e3" align="center">
				<table class="sub_finance_table" border="0" align="center">
					<tr>
						<td>
			<div style="background:#EEEEEE;">
				<div style="float:left;">
										<table style="color:#000000;" class="finance_column" border="0" align="center" cellpadding="1" cellspacing="2">
										<tbody>
											<tr>
												<td colspan="2" bgcolor="#FFFF00" id="tdAlert"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/menu_single.jpg" alt="Applicant" width="10" height="10" /> STEP ONE - Personal Info</td>
											</tr>
											<tr height="25">
												<td width="46%" align="right" bgcolor="#EEEEEE">
													<span>* </span>First Name
												</td>
												<td width="54%" bgcolor="#EEEEEE">
													<input name="fn" id="fn" value="" maxlength="200" tabindex="1" />
													<br />
													<div id="msgDivfn" style="display:none"><font color="#FF0000" size="-1"><b>FIRST NAME REQUIRED</b></font></div>
												</td>
											</tr>
											<tr height="25">
												<td align="right" bgcolor="#EEEEEE"><span>*</span> Middle Initial </td>
												<td bgcolor="#EEEEEE"><input name="mi" id="mi" tabindex="2" size="2" maxlength="1" /></td>
											</tr>
											<tr height="25">
												<td width="46%" align="right" bgcolor="#EEEEEE">
													<span>* </span>Last Name
												</td>
												<td width="54%" bgcolor="#EEEEEE">
													<input name="ln" id="ln" value="" maxlength="200" tabindex="2" />
													<br />
													<div id="msgDivln" style="display:none"><font color="#FF0000" size="-1"><b>LAST NAME REQUIRED</b></font></div>
												</td>
											</tr>
											<tr height="25">
												<td width="46%" align="right" bgcolor="#EEEEEE"><span>* </span>Phone Number</td>
												<td width="54%" bgcolor="#EEEEEE">
												<input name="hpn" id="hpn" onKeyPress="checkNum()" value="" maxlength="200" tabindex="3" />
												<br />
												<div id="msgDivhpn" style="display:none"><font color="#FF0000" size="-1"><b>PHONE REQUIRED</b></font></div>	</td>
											</tr>
											<tr height="25">
												<td width="46%" align="right" bgcolor="#EEEEEE"><span>* </span>Email Address</td>
												<td width="54%" bgcolor="#EEEEEE">
												<input id="ea" maxlength="200" name="ea" tabindex="4" />
												<br />
												<div id="msgDivea" style="display:none"><font color="#FF0000" size="-1"><b>EMAIL REQUIRED</b></font></div></td>
											</tr>
											<tr height="25">
												<td width="46%" align="right" bgcolor="#EEEEEE"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/secure_sm.gif" alt="Your info is secure" width="10" height="16" /> Social Security Number </td>
												<td width="54%" bgcolor="#EEEEEE"><input id="ssn" maxlength="11"  onKeyPress="checkNum()" name="ssn" tabindex="5" />
												<br />
												<div id="msgDivssn" style="display:none"><font color="#FF0000" size="-1"><b>SSN REQUIRED</b></font></div>				</td>
											</tr>
											<tr height="25">
												<td colspan="2" align="right" bgcolor="#EEEEEE"><div align="center">( We secure and protect your confidential information ) </div></td>
											</tr>
				  <tr>
					<td><div align="right">Apt/Suite</div></td>
					<td><input name="app_apt_num" type="text" id="app_apt_num" tabindex="6" size="6" maxlength="5" />
	<br />
	<div id="msgDivapp_apt_num" style="display:none"><font color="#FF0000" size="-1"><b>APT/SUITE REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr>
					<td><div align="right">Street Number</div></td>
					<td><input name="app_street_num" type="text" id="app_street_num" onKeyPress="checkNum()" tabindex="7" onChange="ValidateAppAddress();" size="6" maxlength="6" />
					  <br />
	  <div id="msgDivapp_street_num" style="display:none"><font color="#FF0000" size="-1"><b>STREET NUM REQUIRED</b></font></div>				</td></tr>
				  <tr>
					<td><div align="right"> Street Name</div></td>
					<td><input name="app_street_name" type="text" maxlength="20" id="app_street_name" tabindex="8" />
	<br />
	<div id="msgDivapp_street_name" style="display:none"><font color="#FF0000" size="-1"><b>STREET NAME REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr>
					<td><div align="right">Street Type</div></td>
					<td><select name="app_street_type" id="app_street_type" class="select" tabindex="9">
						<option selected="selected" value=""></option>
						<option value="AVENUE">AVENUE</option>
						<option value="BOULEVARD">BOULEVARD</option>
						<option value="CIRCLE">CIRCLE</option>
						<option value="COURT">COURT</option>
						<option value="CRESCENT">CRESCENT</option>
						<option value="DRIVE">DRIVE</option>
						<option value="FREEWAY">FREEWAY</option>
						<option value="HIGHWAY">HIGHWAY</option>
						<option value="LANE">LANE</option>
						<option value="PARKWAY">PARKWAY</option>
						<option value="PLACE">PLACE</option>
						<option value="PLAZA">PLAZA</option>
						<option value="ROAD">ROAD</option>
						<option value="SQUARE">SQUARE</option>
						<option value="STREET">STREET</option>
						<option value="TERRACE">TERRACE</option>
						<option value="TRAIL">TRAIL</option>
						<option value="TURNPIKE">TURNPIKE</option>
						<option value="WAY">WAY</option>
					</select>
	<br />
	<div id="msgDivapp_street_type" style="display:none"><font color="#FF0000" size="-1"><b>STREET TYPE REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr>
					<td><div align="right">PO Box </div></td>
					<td><input name="app_po_box_num" type="text" id="app_po_box_num" onKeyPress="checkNum()" tabindex="10" size="6" maxlength="6" onChange="ValidateAppAddress();" />
					<br />
					<div id="msgDivapp_po_box_num" style="display:none"><font color="#FF0000" size="-1"><b>PO BOX REQUIRED</b></font></div>              </tr>
				  <tr>
					<td><div align="right">Rural Route</div></td>
					<td><input name="app_rural_route" type="text" id="app_rural_route" tabindex="11" size="6" maxlength="6" onChange="ValidateAppAddress();" />
					<br />
					<div id="msgDivapp_rural_route" style="display:none"><font color="#FF0000" size="-1"><b>RURAL ROUTE REQUIRED</b></font></div>              </tr>
				  
				  <tr height="25">
					<td width="46%" align="right" bgcolor="#EEEEEE">City</td>
					<td width="54%" bgcolor="#EEEEEE"><input name="cty" id="cty" value="" maxlength="200" tabindex="12" />
	<br />
	<div id="msgDivcty" style="display:none"><font color="#FF0000" size="-1"><b>CITY REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="46%" align="right" bgcolor="#EEEEEE">State</td>
					<td width="54%" bgcolor="#EEEEEE">
					  <select name="st" id="st" tabindex="13" class="select" style="width:65px;">
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
	<br />
	<div id="msgDivst" style="display:none"><font color="#FF0000" size="-1"><b>STATE REQUIRED</b></font></div>				  </td>
				  </tr>
				  <tr height="25">
					<td width="46%" align="right" bgcolor="#EEEEEE">Zip</td>
					<td width="54%" bgcolor="#EEEEEE">
					<input name="zi" id="zi" value="" onKeyPress="checkNum()" maxlength="200" tabindex="14" />
	<br />
	<div id="msgDivzi" style="display:none"><font color="#FF0000" size="-1"><b>ZIP REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="46%" align="right" bgcolor="#EEEEEE">Birth Date (Month-Date-Year)</td>
					<td width="54%" bgcolor="#EEEEEE">
					<select name="bdm" tabindex="15">
					<option selected="selected"></option>
					<option value="1">Jan</option>
					<option value="2">Feb</option>
					<option value="3">Mar</option>
					<option value="4">Apr</option>
					<option value="5">May</option>
					<option value="6">Jun</option>
					<option value="7">Jul</option>
					<option value="8">Aug</option>
					<option value="9">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
					</select>
	-				
	<select name="bdd" id="bdd" tabindex="16">
	<option></option>
	<?php
	echo get_the_days()
	?>
	</select>
					  -                    
						<select name="bdy" id="bdy" tabindex="17">
						<option></option>
	<?php
	echo get_the_years();
	?>
						</select>
	<br />
	<br />
	<div id="msgDivbdd" style="display:none"><font color="#FF0000" size="-1"><b>DAY REQUIRED</b></font></div>
	<div id="msgDivbdm" style="display:none"><font color="#FF0000" size="-1"><b>MONTH REQUIRED</b></font></div>
	<div id="msgDivbdy" style="display:none"><font color="#FF0000" size="-1"><b>YEAR REQUIRED</b></font></div></td>
				  </tr>
				</tbody>
			</table>
			
</div>
<div style="float:left;">	
	<div id="AppEmployer">
		<table class="finance_column" style="color:#000000;" border="0" align="center" cellpadding="1" cellspacing="2">
				<tbody>
				  <tr height="25">
					<td colspan="2" align="left" bgcolor="#FFFF00"><span>STEP TWO  - Employment Info </span></td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Employer Name</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="en" id="en" maxlength="200" tabindex="18" />
	<br />
	<div id="msgDiven" style="display:none"><font color="#FF0000" size="-1"><b>EMPLOYER REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Position</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="p" id="p" maxlength="200" tabindex="19" />
	<br />
	<div id="msgDivp" style="display:none"><font color="#FF0000" size="-1"><b>POSITION REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Years/Month at Company</span></td>
					<td width="35%" bgcolor="#EEEEEE"><span>
					  <select name="yac" id="yac" tabindex="20" onChange="AddEmployers(this,1);">
						<?php echo select_years(); ?>
					  </select>
					  /
					  <select id="mac" name="mac" tabindex="21">
						<option value="" selected="selected"></option>
						<option value="0">0 Months</option>
						<option value="1">1 Month</option>
						<option value="2">2 Months</option>
						<option value="3">3 Months</option>
						<option value="4">4 Months</option>
						<option value="5">5 Months</option>
						<option value="6">6 Months</option>
						<option value="7">7 Months</option>
						<option value="8">8 Months</option>
						<option value="9">9 Months</option>
						<option value="10">10 Months</option>
						<option value="11">11 Months</option>
					  </select>
					</span>
	<br /><div id="msgDivyac" style="display:none"><font color="#FF0000" size="-1"><b>YEARS REQUIRED</b></font></div>
	<br /><div id="msgDivmac" style="display:none"><font color="#FF0000" size="-1"><b>MONTHS REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Employer Phone Number</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="epn" id="epn" onKeyPress="checkNum()" maxlength="200" tabindex="22" />
	<br />
	<div id="msgDivepn" style="display:none"><font color="#FF0000" size="-1"><b>PHONE REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Gross Monthly Income</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="gmi" id="gmi" maxlength="200" onKeyPress="checkNum()" tabindex="23" />
	<br />
	<div id="msgDivgmi" style="display:none"><font color="#FF0000" size="-1"><b>INCOME REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Other Income</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="oi" id="oi" tabindex="24" onKeyPress="checkNum()" value="0" maxlength="200" />
	<br />
	<div id="msgDivoi" style="display:none"><font color="#FF0000" size="-1"><b>OTHER INCOME REQUIRED</b></font></div>				</td>
				  </tr>
				</tbody>
			  </table>
	</div>
	<div id="AppEmployer1" style="display:none">
		<table width="100%" style="color:#000000;" border="0" align="center" cellpadding="1" cellspacing="2">
				<tbody>
				  <tr height="25">
					<td colspan="2" align="left" bgcolor="#FFFF00"><span>PREVIOUS EMPLOYMENT</span></td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Employer Name</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="p2en" id="p2en" maxlength="200" tabindex="25" />
	<br />
	<div id="msgDivp2en" style="display:none"><font color="#FF0000" size="-1"><b>EMPLOYER REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Position</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="p2p" id="p2p" maxlength="200" tabindex="26" />
	<br />
	<div id="msgDivp2p" style="display:none"><font color="#FF0000" size="-1"><b>POSITION REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Years/Month at Company</span></td>
					<td width="35%" bgcolor="#EEEEEE"><span>
					  <select name="p2yac" id="p2yac" tabindex="27" onChange="AddEmployers(this,2);">
						<?php echo select_years(); ?>
					  </select>
					  /
					  <select id="p2mac" name="p2mac" tabindex="28">
						<option value="" selected="selected"></option>
						<option value="0">0 Months</option>
						<option value="1">1 Month</option>
						<option value="2">2 Months</option>
						<option value="3">3 Months</option>
						<option value="4">4 Months</option>
						<option value="5">5 Months</option>
						<option value="6">6 Months</option>
						<option value="7">7 Months</option>
						<option value="8">8 Months</option>
						<option value="9">9 Months</option>
						<option value="10">10 Months</option>
						<option value="11">11 Months</option>
					  </select>
	<br /><div id="msgDivp2mac" style="display:none"><font color="#FF0000" size="-1"><b>MONTHS REQUIRED</b></font></div>
	<div id="msgDivp2yac" style="display:none"><font color="#FF0000" size="-1"><b>YEARS REQUIRED</b></font></div>
					</span></td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Employer Phone Number</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="p2epn" onKeyPress="checkNum()" id="p2epn" maxlength="200" tabindex="29" />
	<br />
	<div id="msgDivp2epn" style="display:none"><font color="#FF0000" size="-1"><b>PHONE REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Gross Monthly Income</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="p2gmi" id="p2gmi" onKeyPress="checkNum()" maxlength="200" tabindex="30" />
	<br />
	<div id="msgDivp2gmi" style="display:none"><font color="#FF0000" size="-1"><b>INCOME REQUIRED</b></font></div>				</td>
				  </tr>
				  <tr height="25">
					<td width="55%" align="right" bgcolor="#EEEEEE"><span>Other Income</span></td>
					<td width="35%" bgcolor="#EEEEEE"><input name="p2oi" id="p2oi" onKeyPress="checkNum()" tabindex="31" value="0" maxlength="200" />
	<br />
	<div id="msgDivp2oi" style="display:none"><font color="#FF0000" size="-1"><b>OTHER INCOME REQUIRED</b></font></div>				</td>
				  </tr>
				</tbody>
			  </table>
	</div>
	<div id="AppEmployer2" style="display:none"></div>
	<div id="AppEmployer3" style="display:none"></div>
	
	<div id="AppAddress">
		<table  class="finance_column" style="color:#000000;" border="0" align="center" cellpadding="1" cellspacing="2">
		<tbody>
		  <tr height="25">
			<td colspan="2" align="left" bgcolor="#FFFF00">STEP THREE  - Living Situation </td>
		  </tr>
		  <tr height="25">
			<td width="47%" align="right" bgcolor="#EEEEEE">Years/Month at Current Address</td>
			<td width="53%" bgcolor="#EEEEEE"><select name="yaca" id="yaca" tabindex="32" onChange="AddAddresses(this,1);">
				<?php echo select_years(); ?>
			  </select>
			  /
			  <select id="maca" name="maca" tabindex="33">
				<option value="" selected="selected"></option>
				<option value="0">0 Months</option>
				<option value="1">1 Month</option>
				<option value="2">2 Months</option>
				<option value="3">3 Months</option>
				<option value="4">4 Months</option>
				<option value="5">5 Months</option>
				<option value="6">6 Months</option>
				<option value="7">7 Months</option>
				<option value="8">8 Months</option>
				<option value="9">9 Months</option>
				<option value="10">10 Months</option>
				<option value="11">11 Months</option>
			  </select>
	<br /><div id="msgDivyaca" style="display:none"><font color="#FF0000" size="-1"><b>YEARS REQUIRED</b></font></div>
	<br /><div id="msgDivmaca" style="display:none"><font color="#FF0000" size="-1"><b>MONTHS REQUIRED</b></font></div>		  </td>
		  </tr>
		  <tr height="25">
			<td width="47%" align="right" bgcolor="#EEEEEE">Do you Own or Rent ?</td>
			<td width="53%" bgcolor="#EEEEEE"><select id="roo" name="roo" tabindex="34">
				<option value="Own" selected="selected">Own</option>
				<option value="Rent">Rent</option>
				<option value="Relatives">Relatives</option>
			</select>
	<br /><div id="msgDivroo" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>		</td>
		  </tr>
		  <tr height="25">
			<td width="47%" align="right" bgcolor="#EEEEEE">Rent Amount/Mortgage Payment $</td>
			<td width="53%" bgcolor="#EEEEEE"><input id="ramp" maxlength="200" name="ramp" onKeyPress="checkNum()" tabindex="35" />
	<br /><div id="msgDivramp" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>		</td>
		  </tr>
		  <tr height="25">
			<td width="47%" align="right" bgcolor="#EEEEEE">Best Contact Place And Time</td>
			<td width="53%" bgcolor="#EEEEEE"><select id="bcp" name="bcp" tabindex="36" >
				<option value="Home" selected="selected">Home</option>
				<option value="Work">Work</option>
				<option value=" "></option>
			  </select>
				<select id="bct" name="bct" tabindex="37" >
				  <option value="Morning   " selected="selected">Morning</option>
				  <option value="Afternoon ">Afternoon</option>
				  <option value="Evening   ">Evening</option>
			  </select>
	<br /><div id="msgDivbcp" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>
	<br /><div id="msgDivbct" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>		  </td>
		  </tr>
		  <tr height="25">
		    <td align="right" bgcolor="#EEEEEE"><div align="left">Comments</div></td>
		    <td bgcolor="#EEEEEE">&nbsp;</td>
		    </tr>
		  <tr height="25">
		    <td colspan="2" align="right" bgcolor="#EEEEEE"><div align="left">
		      <textarea name="comment" id="comment" rows="5" style="width:350px;margin-left:25px;" tabindex="38"></textarea>
		      </div></td>
		    </tr>
		</tbody>
	  </table>
	</div>
	<div id="AppAddress1" style="display:none">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="2">
				  <tbody>
					<tr height="25">
					  <td width="100%" colspan="2" align="left" bgcolor="#FFFF00">PREVIOUS ADDRESS</td>
					</tr>
				  </tbody>
			  </table>
	<table width="100%" style="color:#000000;" border="0" align="center" cellpadding="1" cellspacing="2">
	  <tbody>
		<tr>
		  <td><div align="right">Apt/Suite</div></td>
		  <td><input name="p1app_apt_num" type="text" id="p1app_apt_num" tabindex="38" size="6" maxlength="5" />
	<br />
	<div id="msgDivp1app_apt_num" style="display:none"><font color="#FF0000" size="-1"><b>APT/SUITE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">Street Number</div></td>
		  <td><input name="p1app_street_num" type="text" id="p1app_street_num" onKeyPress="checkNum()" tabindex="39" size="6" maxlength="6" onChange="ValidateAppPrevAddress();" />
	<br />
	<div id="msgDivp1app_street_num" style="display:none"><font color="#FF0000" size="-1"><b>STREET NUM  REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">App Street Name</div></td>
		  <td><input name="p1app_street_name" type="text" maxlength="20" id="p1app_street_name" tabindex="40" />
	<br />
	<div id="msgDivp1app_street_name" style="display:none"><font color="#FF0000" size="-1"><b>STREET NAME REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">Street Type</div></td>
		  <td><select name="p1app_street_type" id="p1app_street_type" tabindex="41" class="select">
			<option selected="selected" value=""></option>
			<option value="AVENUE">AVENUE</option>
			<option value="BOULEVARD">BOULEVARD</option>
			<option value="CIRCLE">CIRCLE</option>
			<option value="COURT">COURT</option>
			<option value="CRESCENT">CRESCENT</option>
			<option value="DRIVE">DRIVE</option>
			<option value="FREEWAY">FREEWAY</option>
			<option value="HIGHWAY">HIGHWAY</option>
			<option value="LANE">LANE</option>
			<option value="PARKWAY">PARKWAY</option>
			<option value="PLACE">PLACE</option>
			<option value="PLAZA">PLAZA</option>
			<option value="ROAD">ROAD</option>
			<option value="SQUARE">SQUARE</option>
			<option value="STREET">STREET</option>
			<option value="TERRACE">TERRACE</option>
			<option value="TRAIL">TRAIL</option>
			<option value="TURNPIKE">TURNPIKE</option>
			<option value="WAY">WAY</option>
		  </select>
	<br />
	<div id="msgDivp1app_street_type" style="display:none"><font color="#FF0000" size="-1"><b>STREET TYPE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">PO Box </div></td>
		  <td><input name="p1app_po_box_num" type="text" id="p1app_po_box_num" tabindex="42" size="6" maxlength="6" onChange="ValidateAppPrevAddress();" />
	<br />
	<div id="msgDivp1app_po_box_num" style="display:none"><font color="#FF0000" size="-1"><b>PO BOX REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">Rural Route</div></td>
		  <td><input name="p1app_rural_route" type="text" id="p1app_rural_route" tabindex="43" size="6" maxlength="6" onChange="ValidateAppPrevAddress();" />
	<br />
	<div id="msgDivp1app_rural_route" style="display:none"><font color="#FF0000" size="-1"><b>RURAL ROUTE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr height="25">
		  <td align="right" bgcolor="#EEEEEE">City</td>
		  <td bgcolor="#EEEEEE"><input id="p1cty" maxlength="200" name="p1cty" tabindex="44" />
	<br />
	<div id="msgDivp1cty" style="display:none"><font color="#FF0000" size="-1"><b>CITY REQUIRED</b></font></div>	  </td>
		</tr>
		<tr height="25">
		  <td align="right" bgcolor="#EEEEEE">State</td>
		  <td bgcolor="#EEEEEE"><select name="p1st" id="p1st" tabindex="45" class="select" style="width:65px;">
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
	<br />
	<div id="msgDivp1st" style="display:none"><font color="#FF0000" size="-1"><b>STATE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr height="25">
		  <td align="right" bgcolor="#EEEEEE">Zip</td>
		  <td bgcolor="#EEEEEE"><input id="p1zi" maxlength="200" onKeyPress="checkNum()" name="p1zi" tabindex="46" />
	<br />
	<div id="msgDivp1zi" style="display:none"><font color="#FF0000" size="-1"><b>ZIP REQUIRED</b></font></div>	  </td>
		</tr>
	  </tbody>
	</table>
	</div>
	<div id="AppAddress3" style="display:none"></div>
</div></div>
		<div>
			<div style="margin-left:40%;float:left;" class="have_cosigner">Do you have a Co-Signer</div>
			<div style="margin-left:10px;float:left;">
			<select name="MakeCoSigner" onChange="MakeTheCoSigner(this);" tabindex="47">
			  <option value="No">No</option>
			  <option value="Yes">Yes</option>
			</select>
			</div>
		</div>
		  <div id="CoSignerDiv" style="display:none">
			<div style="background-color:#dddddd;">
			  <div>&nbsp;<div class="style57" style="color:#ffffff;background-color:#999999;"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/expand.gif" alt="Enter Co-Signer" width="9" height="9" /> Co-Signer</div></div></div>
			<div>
				<div style="float:left;">
					<table style="color:#000000;" class="finance_column" border="0" align="center" cellpadding="1" cellspacing="2">
						<tbody>
						  <tr>
							<td colspan="2" bgcolor="#FFFF00" id="tdAlert"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/menu_joint.jpg" alt="Co-Signer" width="20" height="10" /> STEP FOUR - Co-Applicant Information (Optional)</td>
						  </tr>
						  <tr height="25">
							<td width="46%" align="right" bgcolor="#FFFFCC">First Name</td>
							<td width="54%" bgcolor="#FFFFCC"><input id="co_fn2" maxlength="200" name="co_fn2" tabindex="48" /><br />
							<div id="msgDivco_fn2" style="display:none"><font color="#FF0000" size="-1"><b>FIRST NAME REQUIRED</b></font></div>                      </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">Middle Initial </td>
							<td bgcolor="#FFFFCC"><input id="co_mi" maxlength="1" size="2" name="co_mi" tabindex="48" />                      </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">Last Name</td>
							<td bgcolor="#FFFFCC"><input id="co_ln2" maxlength="200" name="co_ln2" tabindex="49" /><br />
							<div id="msgDivco_ln2" style="display:none"><font color="#FF0000" size="-1"><b>LAST NAME REQUIRED</b></font></div>                      </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">Home Phone Number</td>
							<td bgcolor="#FFFFCC"><input id="co_hpn2" maxlength="200" name="co_hpn2" tabindex="50" /><br />
							<div id="msgDivco_hpn2" style="display:none"><font color="#FF0000" size="-1"><b>PHONE REQUIRED</b></font></div>                      </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">Email Address</td>
							<td bgcolor="#FFFFCC"><input id="co_ea2" maxlength="200" name="co_ea2" tabindex="51" /><br />
							<div id="msgDivco_ea2" style="display:none"><font color="#FF0000" size="-1"><b>EMAIL REQUIRED</b></font></div>                      </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC"><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/secure_sm.gif" alt="Your info is secure" width="10" height="16" /> Social Security Number</td>
							<td bgcolor="#FFFFCC"><input id="co_ssn2" maxlength="11" name="co_ssn2" tabindex="52" /><br />
							<div id="msgDivco_ssn2" style="display:none"><font color="#FF0000" size="-1"><b>SSN REQUIRED</b></font></div>                      </tr>
						  <tr height="25">
							<td colspan="2" align="right" bgcolor="#FFFFCC"><div align="center">( We secure and protect your confidential information ) </div></td>
						  </tr>
						  
						  <tr>
							<td><div align="right">Apt/Suite</div></td>
							<td><input name="co_app_apt_num" type="text" id="co_app_apt_num" tabindex="53" size="6" maxlength="5" />
	<br />
	<div id="msgDivco_app_apt_num" style="display:none"><font color="#FF0000" size="-1"><b>APT/SUITE REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr>
							<td><div align="right">Street Number</div></td>
							<td><input name="co_app_street_num" type="text" onKeyPress="checkNum()" id="co_app_street_num" tabindex="54" size="6" maxlength="6" onChange="ValidateCoAppAddress();" />
	<br />
	<div id="msgDivco_app_street_num" style="display:none"><font color="#FF0000" size="-1"><b>STREET NUM REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr>
							<td><div align="right"> Street Name</div></td>
							<td><input name="co_app_street_name" type="text" maxlength="20" id="co_app_street_name" tabindex="55" />
	<br />
	<div id="msgDivco_app_street_name" style="display:none"><font color="#FF0000" size="-1"><b>STREET NAME REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr>
							<td><div align="right">Street Type</div></td>
							<td><select name="co_app_street_type" id="co_app_street_type" tabindex="56" class="select">
								<option selected="selected" value=""></option>
						<option value="AVENUE">AVENUE</option>
						<option value="BOULEVARD">BOULEVARD</option>
						<option value="CIRCLE">CIRCLE</option>
						<option value="COURT">COURT</option>
						<option value="CRESCENT">CRESCENT</option>
						<option value="DRIVE">DRIVE</option>
						<option value="FREEWAY">FREEWAY</option>
						<option value="HIGHWAY">HIGHWAY</option>
						<option value="LANE">LANE</option>
						<option value="PARKWAY">PARKWAY</option>
						<option value="PLACE">PLACE</option>
						<option value="PLAZA">PLAZA</option>
						<option value="ROAD">ROAD</option>
						<option value="SQUARE">SQUARE</option>
						<option value="STREET">STREET</option>
						<option value="TERRACE">TERRACE</option>
						<option value="TRAIL">TRAIL</option>
						<option value="TURNPIKE">TURNPIKE</option>
						<option value="WAY">WAY</option>
							</select>
	<br />
	<div id="msgDivco_app_street_type" style="display:none"><font color="#FF0000" size="-1"><b>STREET TYPE REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr>
							<td><div align="right">PO Box </div></td>
							<td><input name="co_app_po_box_num" type="text" id="co_app_po_box_num" tabindex="57" size="6" maxlength="6" onChange="ValidateCoAppAddress();" />
	<br />
	<div id="msgDivco_app_po_box_num" style="display:none"><font color="#FF0000" size="-1"><b>PO BOX REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr>
							<td><div align="right">Rural Route</div></td>
							<td><input name="co_app_rural_route" type="text" id="co_app_rural_route" tabindex="58" size="6" maxlength="6" onChange="ValidateCoAppAddress();" />
	<br />
	<div id="msgDivco_app_rural_route" style="display:none"><font color="#FF0000" size="-1"><b>RURAL ROUTE REQUIRED</b></font></div>						</td>
						  </tr>
						  
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">City</td>
							<td bgcolor="#FFFFCC"><input id="co_cty2" maxlength="200" name="co_cty2" tabindex="59" /><br />
							<div id="msgDivco_cty2" style="display:none"><font color="#FF0000" size="-1"><b>CITY REQUIRED</b></font></div>                      </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">State</td>
							<td bgcolor="#FFFFCC"><select name="co_st2" id="co_st2" tabindex="60" class="select" style="width:65px;">
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
	<br />
	<div id="msgDivco_st2" style="display:none"><font color="#FF0000" size="-1"><b>STATE REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">Zip</td>
							<td bgcolor="#FFFFCC"><input id="co_zi2" onKeyPress="checkNum()" maxlength="200" name="co_zi2" tabindex="61" />
	<br />
	<div id="msgDivco_zi2" style="display:none"><font color="#FF0000" size="-1"><b>ZIP REQUIRED</b></font></div>						</td>
						  </tr>
						  <tr height="25">
							<td align="right" bgcolor="#FFFFCC">Birth Date (Month-Date-Year)</td>
							<td bgcolor="#FFFFCC"><select name="co_bdm" tabindex="62">
								<option></option>
								<option value="1">Jan</option>
								<option value="2">Feb</option>
								<option value="3">Mar</option>
								<option value="4">Apr</option>
								<option value="5">May</option>
								<option value="6">Jun</option>
								<option value="7">Jul</option>
								<option value="8">Aug</option>
								<option value="9">Sep</option>
								<option value="10">Oct</option>
								<option value="11">Nov</option>
								<option value="12">Dec</option>
							  </select>
							   - 
	<select name="co_bdd" id="co_bdd" tabindex="63">
	<option></option>
	<?php
	echo get_the_days();
	?>
	</select>
	-
	<select name="co_bdy" id="co_bdy" tabindex="64">
	  <option></option>
	<?php
	echo get_the_years();
	?>
	</select>
	<br />
	<div id="msgDivco_bdd" style="display:none"><font color="#FF0000" size="-1"><b>DAY REQUIRED</b></font></div>
	<br /><div id="msgDivco_bdm" style="display:none"><font color="#FF0000" size="-1"><b>MONTH REQUIRED</b></font></div>
	<br /><div id="msgDivco_bdy" style="display:none"><font color="#FF0000" size="-1"><b>YEAR REQUIRED</b></font></div></td>
						  </tr>
						</tbody>
					</table>
					</div>
<div style="float:left;">
	<div id="CoEmployer">
	<table  class="finance_column" style="color:#000000;" border="0" cellpadding="1" cellspacing="2">
						<tr height="25">
						  <td colspan="2" align="left" bgcolor="#FFFF00">STEP FIVE  - Co-Applicant Employment Info </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Employer Name</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="co_en2" id="co_en2" maxlength="200" tabindex="65" />
						  <br />
						  <div id="msgDivco_en2" style="display:none"><font color="#FF0000" size="-1"><b>EMPLOYER REQUIRED</b></font></div>                    </tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Position</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="co_p2" id="co_p2" maxlength="200" tabindex="66" />
						  <br />
						  <div id="msgDivco_p2" style="display:none"><font color="#FF0000" size="-1"><b>POSITION REQUIRED</b></font></div>					  </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Years/Month at Company</td>
						  <td width="49%" bgcolor="#FFFFCC"><select name="co_yac2" id="co_yac2" tabindex="67" onChange="AddCoEmployer(this,1);">
							<?php echo select_years(); ?>
						  </select>
							/
							<select id="co_mac2" name="co_mac2" tabindex="68">
						  <option value="" selected="selected"></option>
						  <option value="0">0 Months</option>
						  <option value="1">1 Month</option>
						  <option value="2">2 Months</option>
						  <option value="3">3 Months</option>
						  <option value="4">4 Months</option>
						  <option value="5">5 Months</option>
						  <option value="6">6 Months</option>
						  <option value="7">7 Months</option>
						  <option value="8">8 Months</option>
						  <option value="9">9 Months</option>
						  <option value="10">10 Months</option>
						  <option value="11">11 Months</option>
						</select>
	<br /><div id="msgDivco_yac2" style="display:none"><font color="#FF0000" size="-1"><b>YEARS REQUIRED</b></font></div>
	<br /><div id="msgDivco_mac2" style="display:none"><font color="#FF0000" size="-1"><b>MONTHS REQUIRED</b></font></div>                      </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Employer Phone Number</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="co_epn2" onKeyPress="checkNum()" id="co_epn2" maxlength="200" tabindex="69" />
	<br />
	<div id="msgDivco_epn2" style="display:none"><font color="#FF0000" size="-1"><b>PHONE REQUIRED</b></font></div>                    </tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Gross Monthly Income</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="co_gmi2" onKeyPress="checkNum()" id="co_gmi2" maxlength="200" tabindex="70" />
	<br />
	<div id="msgDivco_gmi2" style="display:none"><font color="#FF0000" size="-1"><b>INCOME REQUIRED</b></font></div>					  </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Other Income</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="co_oi2" id="co_oi2" onKeyPress="checkNum()" tabindex="71" value="0" maxlength="200" />
	<br />
	<div id="msgDivco_oi2" style="display:none"><font color="#FF0000" size="-1"><b>OTHER INCOME REQUIRED</b></font></div>					  </td>
						</tr>
					  </table>
	</div>
	<div id="CoEmployer1" style="display:none">
	<table width="100%" style="color:#000000;" border="0" cellpadding="1" cellspacing="2">
						<tr height="25">
						  <td colspan="2" align="left" bgcolor="#FFFF00">Co-Applicant Previous Employment </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Employer Name</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="p1co_en2" id="p1co_en2" maxlength="200" tabindex="72" />
	<br />
	<div id="msgDivp1co_en2" style="display:none"><font color="#FF0000" size="-1"><b>EMPLOYER REQUIRED</b></font></div>					  </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Position</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="p1co_p2" id="p1co_p2" maxlength="200" tabindex="73" />
	<br />
	<div id="msgDivp1co_p2" style="display:none"><font color="#FF0000" size="-1"><b>POSITION REQUIRED</b></font></div>					  </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Years/Month at Company</td>
						  <td width="49%" bgcolor="#FFFFCC"><select name="p1co_yac2" id="p1co_yac2" tabindex="74" onChange="AddCoEmployer(this,2);">
							<?php echo select_years(); ?>
						  </select>
							/
							<select id="p1co_mac2" name="p1co_mac2" tabindex="75">
						  <option value="" selected="selected"></option>
						  <option value="0">0 Months</option>
						  <option value="1">1 Month</option>
						  <option value="2">2 Months</option>
						  <option value="3">3 Months</option>
						  <option value="4">4 Months</option>
						  <option value="5">5 Months</option>
						  <option value="6">6 Months</option>
						  <option value="7">7 Months</option>
						  <option value="8">8 Months</option>
						  <option value="9">9 Months</option>
						  <option value="10">10 Months</option>
						  <option value="11">11 Months</option>
						</select>
	<br /><div id="msgDivp1co_mac2" style="display:none"><font color="#FF0000" size="-1"><b>MONTHS REQUIRED</b></font></div>
	<br /><div id="msgDivp1co_yac2" style="display:none"><font color="#FF0000" size="-1"><b>YEARS REQUIRED</b></font></div>                      </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Employer Phone Number</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="p1co_epn2" onKeyPress="checkNum()" id="p1co_epn2" maxlength="200" tabindex="76" />
	<br />
	<div id="msgDivp1co_epn2" style="display:none"><font color="#FF0000" size="-1"><b>PHONE REQUIRED</b></font></div>					  </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Gross Monthly Income</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="p1co_gmi2" onKeyPress="checkNum()" id="p1co_gmi2" maxlength="200" tabindex="77" />
	<br />
	<div id="msgDivp1co_gmi2" style="display:none"><font color="#FF0000" size="-1"><b>INCOME REQUIRED</b></font></div>					  </td>
						</tr>
						<tr height="25">
						  <td width="51%" align="right" bgcolor="#FFFFCC">Other Income</td>
						  <td width="49%" bgcolor="#FFFFCC"><input name="p1co_oi2" onKeyPress="checkNum()" id="p1co_oi2" tabindex="78" value="0" maxlength="200" />
	<br />
	<div id="msgDivp1co_oi2" style="display:none"><font color="#FF0000" size="-1"><b>OTHER INCOME REQUIRED</b></font></div>					  </td>
						</tr>
					  </table>
	</div>
	<div id="CoEmployer2" style="display:none"></div>
	<div id="CoEmployer3" style="display:none"></div>
	
	<div id="CoAppAddress">
		<table class="finance_column" style="color:#000000;" border="0" align="center" cellpadding="1" cellspacing="2">
						  <tbody>
							<tr height="25">
							  <td colspan="2" align="left" bgcolor="#FFFF00">STEP SIX  - Co-Applicant Living Situation </td>
							</tr>
							<tr height="25">
							  <td width="47%" align="right" bgcolor="#FFFFCC">Years/Month at Current Address</td>
							  <td width="51%" bgcolor="#FFFFCC"><select name="co_yaca2" id="co_yaca2" tabindex="79" onChange="AddCoAddresss(this,1);">
								<?php echo select_years(); ?>
							  </select>
								/
								<select id="select2" name="co_maca2" tabindex="80" >
							<option value="" selected="selected"></option>
							<option value="0">0 Months</option>
							<option value="1">1 Month</option>
							<option value="2">2 Months</option>
							<option value="3">3 Months</option>
							<option value="4">4 Months</option>
							<option value="5">5 Months</option>
							<option value="6">6 Months</option>
							<option value="7">7 Months</option>
							<option value="8">8 Months</option>
							<option value="9">9 Months</option>
							<option value="10">10 Months</option>
							<option value="11">11 Months</option>
						</select>
	<br /><div id="msgDivco_yaca2" style="display:none"><font color="#FF0000" size="-1"><b>YEARS REQUIRED</b></font></div>
	<br /><div id="msgDivco_maca2" style="display:none"><font color="#FF0000" size="-1"><b>MONTHS REQUIRED</b></font></div>					</td>
							</tr>
							<tr height="25">
							  <td width="47%" align="right" bgcolor="#FFFFCC">Do you Own or Rent ?</td>
							  <td width="51%" bgcolor="#FFFFCC"><select id="select3" name="co_roo2" tabindex="81" >
								  <option value="Own" selected="selected">Own</option>
								  <option value="Rent">Rent</option>
								  <option value="Relatives">Relatives</option>
							  </select>
	<br /><div id="msgDivco_roo2" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>						  </td>
							</tr>
							<tr height="25">
							  <td width="47%" align="right" bgcolor="#FFFFCC">Rent Amount/Mortgage Payment $</td>
							  <td width="51%" bgcolor="#FFFFCC"><input id="co_ramp2" maxlength="200" onKeyPress="checkNum()" name="co_ramp2" tabindex="82" />
	<br /><div id="msgDivco_ramp2" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>						  </td>
							</tr>
							<tr height="25">
							  <td width="47%" align="right" bgcolor="#FFFFCC">Best Contact Place And Time</td>
							  <td width="51%" bgcolor="#FFFFCC"><select id="select4" name="co_bcp2" tabindex="83" >
								  <option value="Home" selected="selected">Home</option>
								  <option value="Work">Work</option>
								  <option value=" "></option>
								</select>
								  <select id="select5" name="co_bct2" tabindex="84" >
									<option value="Morning   " selected="selected">Morning</option>
									<option value="Afternoon ">Afternoon</option>
									<option value="Evening   ">Evening</option>
								</select>
								<br /><div id="msgDivco_bcp2" style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>
								<br /><div id="msgDivco_bct2 " style="display:none"><font color="#FF0000" size="-1"><b>REQUIRED</b></font></div>							</td>
							</tr>
						  </tbody>
					  </table>
	</div>
	<div id="CoAppAddress1" style="display:none">
		<table width="100%" border="0" align="center" cellpadding="1" cellspacing="2">
						  <tbody>
							<tr height="25">
							  <td align="left" bgcolor="#FFFF00">Previous Co-Applicant Address </td>
							</tr>
							<tr height="25">
							  <td align="right" bgcolor="#FFFFCC">
	<table width="100%" style="color:#000000;" border="0" align="center" cellpadding="1" cellspacing="2">
	  <tbody>
		<tr>
		  <td><div align="right">Apt/Suite</div></td>
		  <td><input name="p1co_app_apt_num" type="text" id="p1co_app_apt_num" tabindex="85" size="6" maxlength="5" />
	<br />
	<div id="msgDivp1co_app_apt_num" style="display:none"><font color="#FF0000" size="-1"><b>APT/SUITE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">Street Number</div></td>
		  <td><input name="p1co_app_street_num" type="text" id="p1co_app_street_num" onKeyPress="checkNum()" tabindex="86" size="6" maxlength="6" onChange="ValidateCoAppPrevAddress();" />
	<br />
	<div id="msgDivp1co_app_street_num" style="display:none"><font color="#FF0000" size="-1"><b>STREET NUM REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right"> Street Name</div></td>
		  <td><input name="p1co_app_street_name" type="text" maxlength="20" id="p1co_app_street_name" tabindex="87" />
	<br />
	<div id="msgDivp1co_app_street_name" style="display:none"><font color="#FF0000" size="-1"><b>STREET NAME REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">Street Type</div></td>
		  <td><select name="p1co_app_street_type" id="p1co_app_street_type" tabindex="88" class="select">
			<option selected="selected" value=""></option>
			<option value="AVENUE">AVENUE</option>
			<option value="BOULEVARD">BOULEVARD</option>
			<option value="CIRCLE">CIRCLE</option>
			<option value="COURT">COURT</option>
			<option value="CRESCENT">CRESCENT</option>
			<option value="DRIVE">DRIVE</option>
			<option value="FREEWAY">FREEWAY</option>
			<option value="HIGHWAY">HIGHWAY</option>
			<option value="LANE">LANE</option>
			<option value="PARKWAY">PARKWAY</option>
			<option value="PLACE">PLACE</option>
			<option value="PLAZA">PLAZA</option>
			<option value="ROAD">ROAD</option>
			<option value="SQUARE">SQUARE</option>
			<option value="STREET">STREET</option>
			<option value="TERRACE">TERRACE</option>
			<option value="TRAIL">TRAIL</option>
			<option value="TURNPIKE">TURNPIKE</option>
			<option value="WAY">WAY</option>
		  </select>
	<br />
	<div id="msgDivp1co_app_street_type" style="display:none"><font color="#FF0000" size="-1"><b>STREET TYPE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">PO Box </div></td>
		  <td><input name="p1co_app_po_box_num" type="text" id="p1co_app_po_box_num" tabindex="89" size="6" maxlength="6" onChange="ValidateCoAppPrevAddress();" />
	<br />
	<div id="msgDivp1co_app_po_box_num" style="display:none"><font color="#FF0000" size="-1"><b>PO BOX REQUIRED</b></font></div>	  </td>
		</tr>
		<tr>
		  <td><div align="right">Rural Route</div></td>
		  <td><input name="p1co_app_rural_route" type="text" id="p1co_app_rural_route" tabindex="90" size="6" maxlength="6" onChange="ValidateCoAppPrevAddress();" />
	<br />
	<div id="msgDivp1co_app_rural_route" style="display:none"><font color="#FF0000" size="-1"><b>RURAL ROUTE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr height="25">
		  <td align="right" bgcolor="#FFFFCC">City</td>
		  <td bgcolor="#FFFFCC"><input id="p1co_cty2" maxlength="200" name="p1co_cty2" tabindex="91" />
	<br />
	<div id="msgDivp1co_cty2" style="display:none"><font color="#FF0000" size="-1"><b>CITY REQUIRED</b></font></div>	  </td>
		</tr>
		<tr height="25">
		  <td align="right" bgcolor="#FFFFCC">State</td>
		  <td bgcolor="#FFFFCC"><select name="p1co_st2" id="p1co_st2" tabindex="92" class="select" style="width:65px;">
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
	<br />
	<div id="msgDivp1co_st2" style="display:none"><font color="#FF0000" size="-1"><b>STATE REQUIRED</b></font></div>	  </td>
		</tr>
		<tr height="25">
		  <td align="right" bgcolor="#FFFFCC">Zip</td>
		  <td bgcolor="#FFFFCC"><input id="p1co_zi2" onKeyPress="checkNum()" maxlength="200" name="p1co_zi2" tabindex="93" />
	<br />
	<div id="msgDivp1co_zi2" style="display:none"><font color="#FF0000" size="-1"><b>ZIP REQUIRED</b></font></div>	  </td>
		</tr>
	  </tbody>
	</table>						  </td>
							  </tr>
						  </tbody>
					  </table>
	</div>
	<div id="CoAppAddress2" style="display:none"></div>
	<div id="CoAppAddress3" style="display:none"></div>				  </td>
				  </tr>
				</table>
		  </td>
	  </tr>
	</table>
	<table class="main_finance_table" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tbody>
		<tr>
		  <td colspan="3"><div>
<fieldset class="cd-fs2">
			<textarea id="txtDisclosure" name="txtDisclosure" rows="4" style="float:right;width:430px;margin-right:30px;height:365px;font-size:9px;" tabindex="96">
<?php echo get_this_dislaimer($_GET['stock_num']); ?>
							  </textarea>
<legend class="fin_legend">Purchase Information</legend>
<?php
		if (empty($_GET['stock_num'])) {
			echo select_finance_for_vehicle(0);
			echo '<ol class="cd-ol" id="show_voi"></ol>';
		}
		else {
			echo select_finance_for_vehicle(1);
			echo get_finance_for_vehicle($_GET['stock_num']);
		}
		echo finance_locations_radio();
?>
		  </fieldset>
<?php
	if (isset($_GET['stock_num'])) {
		$finance_description = get_finance_description($_GET['stock_num']);
	}
	else {
		$finance_description = '';
	}
?>
		  </div></td>
		</tr>
		<tr>
		  <td colspan="3"><div align="center">
			<p><?php echo $finance_description; ?>
				<br /><?php echo $bad; ?><br />
				<?php $fin = apply_filters('car_demon_mail_hook_form', $fin, 'finance', 'unk');
				echo $fin;
				?>
			  <input type="button" onClick="mainValidation();" name="sbtValidate" id="sbtValidate" tabindex="97" value="Submit This Credit Application" class="search_btn" style="height:75px;width:260px;" /><br />
				<img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/secure.gif" alt="Secure Encryption" width="30" height="49" /></p>
		  </div></td>
		</tr>
	  </tbody>
	</table>
	</form>
		   </div>
<?php
	if ( !empty($bad) ) {
		echo '
			<script>
				document.frm_app.sbtValidate.disabled=true;
				document.frm_app.fn.disabled=true;
				document.frm_app.mi.disabled=true;
				document.frm_app.ln.disabled=true;
				document.frm_app.hpn.disabled=true;
				document.frm_app.ea.disabled=true;
				document.frm_app.ssn.disabled=true;
				document.frm_app.app_apt_num.disabled=true;
				document.frm_app.app_street_num.disabled=true;
				document.frm_app.app_street_name.disabled=true;
				document.frm_app.app_street_type.disabled=true;
				document.frm_app.app_po_box_num.disabled=true;
				document.frm_app.app_rural_route.disabled=true;
				document.frm_app.cty.disabled=true;
				document.frm_app.st.disabled=true;
				document.frm_app.zi.disabled=true;
				document.frm_app.bdm.disabled=true;
				document.frm_app.bdy.disabled=true;
				document.frm_app.bdd.disabled=true;
				document.frm_app.en.disabled=true;
				document.frm_app.p.disabled=true;
				document.frm_app.yac.disabled=true;
				document.frm_app.mac.disabled=true;
				document.frm_app.epn.disabled=true;
				document.frm_app.gmi.disabled=true;
				document.frm_app.oi.disabled=true;
				document.frm_app.yaca.disabled=true;
				document.frm_app.maca.disabled=true;
				document.frm_app.roo.disabled=true;
				document.frm_app.ramp.disabled=true;
				document.frm_app.bcp.disabled=true;
				document.frm_app.bct.disabled=true;
				document.frm_app.comment.disabled=true;
				document.frm_app.MakeCoSigner.disabled=true;
				document.frm_app.pick_voi[0].disabled=true;
				document.frm_app.pick_voi[1].disabled=true;
				document.frm_app.pick_voi[2].disabled=true;
				document.frm_app.finance_location[0].disabled=true;
				document.frm_app.finance_location[1].disabled=true;
				document.frm_app.txtDisclosure.disabled=true;
			</script>
		';
	}
}

function select_years() {
	$start = 0;
	$years = "<option></option>";
	do {
		$years .= "<option value='". $start ."'>". $start ."</option>";
		$start = $start + 1;
	} while ($start < 100);
	return $years;	
}

function get_the_days() {
	$start = 1;
	do {
		$days .= "<option value='". $start ."'>". $start ."</option>";
		$start = $start + 1;
	} while ($start < 32);
	return $days;
}

function get_the_years() {
	$start = 0;
	$this_year = date("Y");
	$this_year = $this_year - 18;
	do {
		$years .= "<option value='". $this_year ."'>". $this_year ."</option>";
		$start = $start + 1;
		$this_year = $this_year - 1;
	} while ($start < 100);
	return $years;
}

function get_finance_for_vehicle($stock_num) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value='".$stock_num."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_vin = rwh(get_post_meta($post_id, "_vin_value", true),0);
			$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
			$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
			$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
			$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
			$vehicle_body_style = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),0);
			$vehicle_photo = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
		}
	}
	$x = '
		<input type="hidden" name="purchase_stock" id="purchase_stock" value="'.$stock_num.'" />
		<ol class="cd-ol" id="show_voi">
			<li id="" class="cd-box-title">Vehicle of Interest</li>
			<li id="not_voi" class="cd-box-title"><input type="checkbox" onclick="show_voi()" style="width:15px;margin-left:15px;" />&nbsp;This is <b>NOT</b> the vehicle I\'m interested in.</li>
			';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>Stock #</span></label><label style="width:250px;"><span style="width:250px;">'.$stock_num.'</span></label></li>';
			$x .= '<li id="" class=""><label for="cd_field_2"><span>VIN</span></label><label style="width:250px;"><span style="width:250px;">'.$vehicle_vin.'</span></label></li>';
			$vehicle = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model .' '. $vehicle_body_style;
			$x .= '<li id="" class=""><label for="cd_field_2"><span>Vehicle</span></label><label style="width:250px;"><span style="width:250px;">'.$vehicle.'</span></label></li>';
			$x .= '<li id="" class=""><img src="'.$vehicle_photo.'" width="300" class="random_widget_image" style="margin-left:75px;" title="'.$vehicle.'" alt="'.$vehicle.'" /></li>';
			$x .= '
			</li>
		</ol>
	';
	return $x;
}

function select_finance_for_vehicle($hide=0) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath_images = str_replace('forms','',$car_demon_pluginpath);	
	if ($hide == 1) {
		$hidden = " style='display:none;'";
	}
	else {
		$hidden = '';
	}
	$x = '
		<ol class="cd-ol" id="find_voi"'.$hidden.' style="margin-left:20px;">
			<li id="voi_title" class="cd-box-title" style="width:375px;">What Vehicle are you Interested In Purchasing?</li>
			<li id="" class="cd-box-title"><input onclick="select_voi(\'stock\');" name="pick_voi" id="pick_voi_1" type="radio" value="1" /> I know the stock#</li>
			<li id="select_stock" style="display:none;margin-left:20px;"><span>Stock #</span>&nbsp;<input class="ac_input" type="text" id="select_stock_txt" /></li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_2" onclick="select_voi(\'search\');" type="radio" value="2" /> I would like to search for it</li>
			<li id="select_description" style="display:none;margin-left:20px;"><span>Description</span>&nbsp;<input type="text"  id="select_car_txt" />&nbsp;(enter year, make or model)</li>
			<li id="" class="cd-box-title"><input name="pick_voi" id="pick_voi_3" onclick="select_voi(\'na\');" type="radio" checked="checked" value="3" /> I haven\'t made up my mind.</li>
			<li id="" class="cd-box-title">
				<img src="'.$car_demon_pluginpath_images.'theme-files/images/no_vehicle.gif" width="175" style="margin-left:150px;" />
			</li>
			<li id="li-7items" class="cd-box-group">
	';
	$x .= '
	<script>
	   $("#select_stock_txt").autocomplete(
		  "'.$car_demon_pluginpath_images.'theme-files/forms/car-demon-trade-form-handler.php",
		  {
		  		extraParams: {action:"findStock"},
				delay:10,
				minChars:2,
				matchSubset:1,
				matchContains:1,
				cacheLength:10,
				onItemSelect:selectItem,
				onFindValue:findValue,
				formatItem:formatItem,
				autoFill:true,
				width:300
			}
		);
	   $("#select_car_txt").autocomplete(
		  "'.$car_demon_pluginpath_images.'theme-files/forms/car-demon-trade-form-handler.php",
		  {
		  		extraParams: {action:"findVehicle"},
				delay:10,
				minChars:2,
				matchSubset:1,
				matchContains:1,
				cacheLength:10,
				onItemSelect:selectCarItem,
				onFindValue:findValue,
				formatItem:formatCarItem,
				autoFill:false,
				width:300
			}
		);
	</script>';
	$x .= '
			</li>
		</ol>
	';
	return $x;
}

function finance_locations_radio() {
	$args = array(
		'style'              => 'none',
		'show_count'         => 0,
		'use_desc_for_title' => 0,
		'hierarchical'       => true,
		'echo'               => 0,
		'taxonomy'           => 'vehicle_location'
		);
	$locations = get_categories( $args );
	$cnt = 0;
	$location_list = '';
	$location_name_list = '';
	foreach ($locations as $location) {
		$cnt = $cnt + 1;
		$location_list .= ','.$location->slug;
		$location_name_list .= ','.$location->cat_name;
	}
	if (empty($locations)) {
		$location_list = 'default'.$location_list;
		$location_name_list = 'Default'.$location_name_list;
		$cnt = 1;
	}
	else {
		$location_list = '@'.$location_list;
		$location_list = str_replace("@,","", $location_list);
		$location_list = str_replace("@","", $location_list);
		$location_name_list = '@'.$location_name_list;
		$location_name_list = str_replace("@,","", $location_name_list);
		$location_name_list = str_replace("@","", $location_name_list);
	}
	$location_name_list_array = explode(',',$location_name_list);
	$location_list_array = explode(',',$location_list);
	$x = 0;
	if (empty($_GET['stock_num'])) {
		$hidden = "";	
	}
	else {
		$hidden = " style='display:none;'";
	}
	$html = '
		<fieldset class="cd-fs2" id="finance_locations"'.$hidden.' style="width:425px;margin-left:20px;">
		<legend class="fin_legend">Finance Location</legend>
		<ol class="cd-ol">
			<li id="select_location" class="cd-box-title">Select your preferred Finance Location</li>
			<li id="li-7items" class="cd-box-group">
	';
	if ($cnt == 1) {
		$select_finance = " checked='checked'";
	}
	foreach ($location_list_array as $current_location) {
		$x = $x + 1;
		$html .= '
			<input type="radio"'.$select_finance.' id="finance_location_'.$x.'" name="finance_location" value="'.get_option($current_location.'_finance_name').'" class="cd-box fldrequired"><span for="finance_location_'.$x.'" class="cdlabel_right"><span>'.get_option($current_location.'_finance_name').'</span></span>
			<br>
		';
	}
	$html .= '
			</li>
		</ol>
		</fieldset>
	';
	return $html;
}

function get_finance_location_name($selected_car) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value = '".$selected_car."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$location_name = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
			$terms = get_the_terms($post_id, 'vehicle_location');
			if ($terms) {
				foreach ($terms as $term) {
					if ($term->name == $location_name) {
						$x = $term->slug;
					}		
				}
			}
		}
	}
	return $x;
}

function get_this_dislaimer($stock_num) {
	if (empty($stock_num)) {
		$finance_disclaimer = get_option('default_finance_disclaimer');
	}
	else {
		$location_disclaimer = get_finance_location_name($stock_num);
		$finance_disclaimer = get_option($location_disclaimer.'_finance_disclaimer');
	}
	if (strlen($finance_disclaimer) < 2) {
		$finance_disclaimer = get_default_finance_disclaimer();
	}
	return $finance_disclaimer;
}

function get_finance_description($stock_num) {
	if (empty($stock_num)) {
		$finance_description = get_option('default_finance_description');
	}
	else {
		$location_description = get_finance_location_name($stock_num);
		$finance_description = get_option($location_description.'_finance_description');
	}
	if (strlen($finance_description) < 2) {
		$finance_description = get_default_finance_description();
	}
	return $finance_description;
}
?>