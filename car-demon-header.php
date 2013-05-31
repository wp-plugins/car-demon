<?php
function car_demon_header() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	wp_enqueue_script('cd-jquery-lightbox-js', $car_demon_pluginpath.'theme-files/js/jquery.lightbox_me.js');
	wp_enqueue_script('cd-jquery-autocomplete-js', $car_demon_pluginpath.'theme-files/js/jquery.autocomplete.js');
	wp_enqueue_script('cd-compare-js', $car_demon_pluginpath.'theme-files/js/car-demon-compare.js.php');
	wp_enqueue_style('cd-jquery-autocomplete-css', $car_demon_pluginpath.'theme-files/css/jquery.autocomplete.css');
	wp_enqueue_style('car-demon-css', $car_demon_pluginpath.'theme-files/css/car-demon.css.php');
	wp_enqueue_style('car-demon-style-css', $car_demon_pluginpath.'theme-files/css/car-demon-style.css');
	?>
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<?php
}
?>