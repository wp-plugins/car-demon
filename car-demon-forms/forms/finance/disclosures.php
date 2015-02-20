<fieldset class="cd-fs2">
	<textarea id="txtDisclosure" name="txtDisclosure" rows="4" class="finance_disclosure" tabindex="96">
		<?php 
		if (isset($_GET['stock_num'])) {
			$this_stock_num = $_GET['stock_num'];
		} else {
			$this_stock_num = '';
		}
		echo get_this_dislaimer($this_stock_num); ?>
	</textarea>
	<legend class="fin_legend">Purchase Information</legend>
	<?php
	if (empty($this_stock_num)) {
		echo select_finance_for_vehicle(0);
		echo '<ol class="cd-ol" id="show_voi"></ol>';
	} else {
		echo select_finance_for_vehicle(1);
		echo get_finance_for_vehicle($_GET['stock_num']);
	}
	if ($location == 'normal') {
		echo finance_locations_radio();
	} else {
		if (empty($location)) {
			echo '<div id="finance_locations"></div><span id="select_location"><input type="radio" style="display:none;" name="finance_location" id="finance_location_1" value="default" checked /></span>';
		} else {
			echo '<div id="finance_locations"></div><span id="select_location"><input type="radio" style="display:none;" name="finance_location" id="finance_location_1" value="'.$location.'" checked /></span>';
		}
	}
	?>
</fieldset>
<?php
if (isset($_GET['stock_num'])) {
	$finance_description = get_finance_description($_GET['stock_num']);
} else {
	$finance_description = '';
}
?>