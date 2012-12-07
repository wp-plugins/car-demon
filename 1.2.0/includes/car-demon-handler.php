<?php
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}

if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/includes', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\includes', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
if (isset($_GET['qr_printed'])) {
	$post_id = $_POST['post_id'];
	if ($_POST['val']=='checked') {
		update_post_meta( $post_id, '_qr_printed', 'checked');
	} else {
		delete_post_meta( $post_id, '_qr_printed');
	}
}

if (isset($_GET['compare_car'])) {
	if (isset($_SESSION['car_demon_compare'])) {
		$compare_these = $_SESSION['car_demon_compare'];
	} else {
		$compare_these = '';
	}
	if ($_POST['action'] == 1) {
		$compare_these = str_replace(','.$_POST['post_id'],'',$compare_these);
		$compare_these = str_replace($_POST['post_id'],'',$compare_these);
		$compare_these = $compare_these.','.$_POST['post_id'];	
	} else {
		$compare_these = str_replace(','.$_POST['post_id'],'',$compare_these);
		$compare_these = str_replace($_POST['post_id'],'',$compare_these);
	}
	$compare_these = '@'.$compare_these;
	$compare_these = str_replace('@,','',$compare_these);
	$compare_these = str_replace('@','',$compare_these);
	$_SESSION['car_demon_compare'] = $compare_these;
	echo show_compare_vehicles();
}

if (isset($_GET['get_compare_list'])) {
	echo show_compare_list();
}

if (isset($_GET['update_car'])) {
	$post_id = $_POST['post_id'];
	$fld = $_POST['fld'];
	$val = $_POST['val'];
	update_post_meta( $post_id, $fld, $val);
}

?>