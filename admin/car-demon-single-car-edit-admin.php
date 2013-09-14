<?php

if ($_GET['action'] == 'edit' ) {
	wp_enqueue_script('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_style('thickbox');
}

?>