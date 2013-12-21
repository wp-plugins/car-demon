<?php
function car_demon_add_page($title, $type) {
	if ($type == 'contact') {
		$content = '[contact_us]';
	} elseif ($type == 'finance') {
		$content = '[finance_form]';
	} elseif ($type == 'service_appointment') {
		$content = '[service_form]';
	} elseif ($type == 'service_quote') {
		$content = '[service_quote]';
	} elseif ($type == 'parts') {
		$content = '[part_request]';
	} elseif ($type == 'trade') {
		$content = '[trade]';
	} elseif ($type == 'staff') {
		$content = '[staff_page]';
	} elseif ($type == 'qualify') {
		$content = '[qualify]';
	}
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$my_page = array(
		'post_title'    => $title,
		'post_content'  => $content,
		'post_status'   => 'publish',
		'post_author'   => $user_id,
		'post_type'     => 'page',
		'comment_status'=> 'closed'
	);
	// Insert the post into the database
	$post_id = wp_insert_post( $my_page );
	return $post_id;
}
?>