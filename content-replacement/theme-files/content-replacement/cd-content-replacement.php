<?php
//=	Content Replacement Functions
add_filter( 'the_content', 'cd_filter_vehicle_content', 20 );
add_filter( 'the_excerpt', 'cd_filter_vehicle_content', 20 );
function cd_filter_vehicle_content($content) {
	if (!is_admin()) {
		$post_id = get_the_ID();
		$post_type = get_post_type( $post_id );
		//= If it's a cars_for_sale post type then keep going
		if ('cars_for_sale' == $post_type) {
			if ( is_single() ) {
				//= If it's single then it's the single vehicle page
				$content .= cd_single_vehicle_content($post_id, $content);
			}
			if (is_post_type_archive('cars_for_sale') || is_tax( 'vehicle_year' ) || is_tax( 'vehicle_make' ) || is_tax( 'vehicle_model' ) || is_tax( 'vehicle_condition' ) || is_tax( 'vehicle_body_style' ) || is_tax( 'vehicle_location' )) {
				// If it's an archive page then filter the loop
				$content = cd_loop_vehicle_content($post_id, $content);
			}
		}
		//= If it's a search page then it needs to be filtered
		if ( is_search() ) {
			//= Handle search page
			if ($post_type == 'cars_for_sale') {
				if (isset($_GET['car'])) {
					if (empty($content)) {
						$content = cd_loop_vehicle_content($post_id, $content);
					}
				}
			}
		}
		// Returns the content.
	}
    return $content;
}

include('cdcr-single-content.php');
function cd_single_vehicle_content($post_id, $content) {
	$output = cdcr_single_content_2($content);
	$x = apply_filters('car_demon_single_car_filter', $output, $post_id );
	return $x;
}

include('cdcr-loop.php');
function cd_loop_vehicle_content($post_id, $content) {
	global $wp_query;
	$x = '';
	//  Code to Hook First Post
	//	$current_post = $wp_query->current_post;
	//	$current_post = intval($current_post);
	//	if( $current_post == 0 ) { 
			/*first post*/
			//= If it's the first post then add everything you want above listings here
	//	}
	$x .= cdcr_loop($content, $post_id);
	return $x;
}

//= Switch out the Query if it's a search page or archive page
function cd_replace_query($query) {
	if ( is_preview() && is_admin() && is_singular() && is_404() ) {
		return $query;
	}
	if ($query->is_home()) {
		return $query;
	}
	//= Set $query equal to Car Demon Search query
	if ($query->is_main_query()) {
		$search_query = car_demon_query_search();
		if (isset($_GET['s'])) {
			if (isset($_GET['car'])) {
				if ($_GET['car']=='1') {
					$search_query = car_demon_query_search();
					//= Load the code for the autoload on scroll
					echo car_demon_dynamic_load();
					foreach ($search_query as $key=>$val) {
						$query->query_vars[$key] = $val;
					}
					return $query;
				}
			}
		}
	}
	if ($query->is_main_query()) {
		if (is_post_type_archive('cars_for_sale')) {
			//= Set $query equal to Car Demon archive Query
			$search_query = car_demon_query_archive();
			//= Load the code for the autoload on scroll
			echo car_demon_dynamic_load();
			foreach ($search_query as $key=>$val) {
				$query->query_vars[$key] = $val;
			}
		}
	}
	/*
	Additional Sample code for potential expansion
    if (is_post_type_archive('cars_for_sale')) {
        //= Set $query equal to Car Demon Query
		//= The Archive template should be taking care of this for us

    }
	$cd_post_type = get_query_var( 'post_type' );
	if ( $cd_post_type == 'cars_for_sale' ) {
		//= Do stuff if it the cars_for_sale post type

	}
	*/
    return $query;
}
add_filter('pre_get_posts','cd_replace_query');

function cdcr_getCurrentURL() {
	$protocol = "http";
	if($_SERVER["SERVER_PORT"]==443 || (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]==”on”)) {
		$protocol .= "s";
		$protocol_port = $_SERVER["SERVER_PORT"];
	} else {
		$protocol_port = 80;
	}
	$host = $_SERVER["HTTP_HOST"];
	$port = $_SERVER["SERVER_PORT"];
	$request_path = $_SERVER["PHP_SELF"];
	$querystr = $_SERVER["QUERY_STRING"];
	$url = $protocol."://".$host.(($port!=$protcol_port && strpos($host,":")==-1)?":".$port:"").$request_path.(empty($querystr)?'':'?'.$querystr);
	return $url;
}
?>