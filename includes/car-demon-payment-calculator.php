<?php
function car_demon_calculator_form($price, $apr, $term, $disclaimer1, $disclaimer2) {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	if (empty($price)) {
		if (isset($_GET['xP'])) {
			$price = $_GET['xP'];
		}
		else {
			$price = "25000";
		}
	}
	wp_enqueue_script('car-demon-payment-calculator-js', WP_CONTENT_URL . '/plugins/car-demon/widgets/js/car-demon-calculator-widget.js');
	if (isset($_SESSION['car_demon_options']['use_form_css'])) {
		if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
			wp_enqueue_style('car-demon-payment-calculator-css', WP_CONTENT_URL . '/plugins/car-demon/widgets/css/car-demon-calculator-widget.css');
		}
	}
	?>
		<form name="calc" action="" class="car_demon_calc">
		  <div align="center"><strong><img src="<?php echo $car_demon_pluginpath; ?>theme-files/images/calculator.gif" class="cd_calculator" width="20" />&nbsp;<span class="car_demon_calc_title"><?php _e('Loan Calculator','car-demon'); ?></span></strong></div>
		  <hr width="100%">
		  <div align="center" class="calc_text"><?php _e('Please fill out the form and click calculate to estimate your monthly payment.','car-demon'); ?></div>
		  <table align="center" class="calc_table">
		<tr> 
		  <td><?php _e('Estimated Price','car-demon'); ?>:</td>
		  <td>$ <input class="calc_box" name="pv" type="text" size="5" maxlength="10" value="<?php echo $price;?>" />
		  </font></td>
		</tr>
		<tr> 
		  <td><?php _e('Annual Percentage Rate','car-demon'); ?>:</td>
		  <td><input class="calc_box" name="rate" type="text" size="2" maxlength="6" value="<?php echo $apr; ?>" />%
				</td></tr>
		<tr> 
		  <td><?php _e('Total Number of Payments','car-demon'); ?>:</td>
		  <td><input class="calc_box" type="text" size="2" maxlength="4" name="numPmtYr" value="<?php echo $term; ?>" />
			<input type="hidden" size="2" maxlength="2" name="numYr" value="5" />
			</td></tr>
	  </table>
	  <div align="center"></div>
	  <p align="center">
		<input type="button" class="calc_btn" value="<?php _e('Calculate','car-demon'); ?>" onClick="returnPayment()" />
		<input type="button" class="calc_btn" value="<?php _e('Reset','car-demon'); ?>" onClick="this.form.reset()" />
		<br />
		<?php echo $disclaimer1; ?></p>
	  <div align="center"> 
	<b><?php _e('*Estimated Monthly Payment','car-demon'); ?>:</b>
	<br />
	<input type="text" size="7" maxlength="7" name="pmt" id="calc_pmt" />
	  </div>
	  <div align="center" class="calc_text">
		<hr width="100%">
		<?php echo $disclaimer2; ?>
		</div>
		</form>
<?php
}
?>