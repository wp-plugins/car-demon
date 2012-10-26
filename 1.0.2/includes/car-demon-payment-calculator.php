<?php
function car_demon_calculator_form($price, $apr, $term, $disclaimer1, $disclaimer2) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	if (empty($price)) {
		if ($_GET['xP']<>'') {
			$price = $_GET['xP'];
		}
		else {
			$price = "25000";
		}
	}
	?>
	<script LANGUAGE=javascript>
	<!--
	function returnPayment() {
		var Principal = document.calc.pv.value
		if (document.calc.rate.value==0) {
			var Rate = 0.000000001
		}
		else {
			var Rate = (document.calc.rate.value/100)/12
		}
		var Rate = (document.calc.rate.value/100)/12
		var Term = document.calc.numPmtYr.value
		document.calc.pmt.value ="$" + find_payment(Principal, Rate, Term);
	}
	
	function find_payment(PR, IN, PE) {
		var PAY = ((PR * IN) / (1 - Math.pow(1 + IN, -PE)));
		return PAY.toFixed(2);
	}
	//-->
	</SCRIPT>
	<style>
	.car_demon_calc {
		min-width: 250px;
		font-size:11px;
		background-color: #E9F0F7 !important;
		border: 1px solid;
		padding: 10px 0;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		margin-bottom: 20px;
	}
	.calc_box {
		width:35px;
	}
	.car_demon_calc_title {
		font-size:20px;
	}
	</style>
		<form name="calc" action="" class="car_demon_calc">
		  <div align="center"><strong><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/calculator.gif" with="20" />&nbsp;<span class="car_demon_calc_title"><?php _e('Loan Calculator','car-demon'); ?></span></strong></div>
		  <hr width="100%">
		  <div align="center" style="width:250px;"><?php _e('Please fill out the form and click calculate to estimate your monthly payment.','car-demon'); ?></div>
		  <table align="center" style="margin-left:3px;">
		<tr> 
		  <td><?php _e('Estimated Price','car-demon'); ?>:</td>
		  <td>$ <input class="calc_box" name="pv" type="text" size="5" maxlength="5" value="<?php echo $price;?>" />
		  </font></td>
		</tr>
		<tr> 
		  <td><?php _e('Annual Percentage Rate','car-demon'); ?>:</td>
		  <td><input class="calc_box" name="rate" type="text" size="2" maxlength="2" value="<?php echo $apr; ?>" />%
				</td></tr>
		<tr> 
		  <td><?php _e('Total Number of Payments','car-demon'); ?>:</td>
		  <td><input class="calc_box" type="text" size="2" maxlength="2" name="numPmtYr" value="<?php echo $term; ?>" />
			<input type="hidden" size="2" maxlength="2" name="numYr" value="5" />
			</td></tr>
	  </table>
	  <div align="center"></div>
	  <p align="center">
		<input type="button" class="calc_btn" value="<?php _e('Calculate','car-demon'); ?>" onClick="returnPayment()" />
		<input type="reset" class="calc_btn" value="<?php _e('Reset Form','car-demon'); ?>" />
		<br />
		<?php echo $disclaimer1; ?></p>
	  <div align="center"> 
	<b><?php _e('*Estimated Monthly Payment','car-demon'); ?>:</b>
	<br />
	<input type="text" size="7" maxlength="7" name="pmt" />
	  </div>
	  <div align="center" style="width:250px;">
		<hr width="100%">
		<?php echo $disclaimer2; ?>
		</div>
		</form>
<?php
}
?>