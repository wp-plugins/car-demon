<?php
add_action("wp_ajax_cd_compare_handler", "cd_compare_handler");
add_action("wp_ajax_nopriv_cd_compare_handler", "cd_compare_handler");
//=====
add_action("wp_ajax_cd_get_compare_list", "cd_get_compare_list");
add_action("wp_ajax_nopriv_cd_get_compare_list", "cd_get_compare_list");
function car_demon_header() {
	$car_demon_pluginpath = CAR_DEMON_PATH;
	wp_enqueue_script('cd-jquery-lightbox-js', $car_demon_pluginpath.'theme-files/js/jquery.lightbox_me.js');
	if (isset($_SESSION['car_demon_options']['use_vehicle_css'])) {
		if ($_SESSION['car_demon_options']['use_vehicle_css'] != 'No') {
			wp_enqueue_style('car-demon-css', $car_demon_pluginpath.'theme-files/css/car-demon.css.php');
			wp_enqueue_style('car-demon-style-css', $car_demon_pluginpath.'theme-files/css/car-demon-style.css');
		}
	} else {
		wp_enqueue_style('car-demon-css', $car_demon_pluginpath.'theme-files/css/car-demon.css.php');
		wp_enqueue_style('car-demon-style-css', $car_demon_pluginpath.'theme-files/css/car-demon-style.css');
	}
	?>
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $car_demon_pluginpath; ?>theme-files/css/car-demon-ie.css" />
	<![endif]-->
	<?php
	//======
	if ($_SESSION['car_demon_options']['use_compare'] == 'Yes') {
		wp_register_script('cd-compare-js', $car_demon_pluginpath.'theme-files/js/car-demon-compare.js');
		wp_localize_script( 'cd-compare-js', 'cdCompareParams', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'msg1' => __('Compare Vehicles','car-demon'),
			'css_url' => get_bloginfo( 'stylesheet_url' )
		));
		wp_enqueue_script('cd-compare-js');
	}
}
?>