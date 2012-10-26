<?php
function car_demon_header() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	?>
	<script src="<?php echo $car_demon_pluginpath; ?>theme-files/js/jquery.lightbox_me.js"></script>
	<script src="<?php echo $car_demon_pluginpath; ?>theme-files/js/jquery.autocomplete.js"></script>
	<script src="<?php echo $car_demon_pluginpath;?>theme-files/js/car-demon-compare.js.php"></script>
	<link rel="stylesheet" href="<?php echo $car_demon_pluginpath;?>theme-files/css/jquery.autocomplete.css" type="text/css" />
	<style>
		<?php car_demon_css($car_demon_pluginpath); ?>
	</style>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath;?>theme-files/css/car-demon-style.css" />
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<?php
}
?>