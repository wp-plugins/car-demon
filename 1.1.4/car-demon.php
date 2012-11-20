<?php
/*
Plugin Name: Car Demon
Plugin URI: http://www.CarDemons.com/
Description:  Car Demon is a PlugIn designed for car dealers.
Author: CarDemons
Version: 1.1.4
Author URI: http://www.CarDemons.com/
Text Domain: car-demon
Domain Path: /theme-files/languages/

*/

$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
include( 'includes/car-demon-search-form.php' );
include( 'includes/car-demon-create-post-types-tax.php' );
include( 'includes/car-demon-register-sidebars.php');
include( 'includes/car-demon-search-fields.php' );
include( 'includes/car-demon-vehicle-price.php' );
include( 'includes/car-demon-list-cars.php' );
include( 'includes/car-demon-compare-vehicles.php' );
include( 'includes/car-demon-get-contact-info.php' );
include( 'includes/car-demon-user-control.php' );
include( 'includes/car-demon-staff-pages.php' );
include( 'includes/car-demon-dynamic-load.php' );
include( 'admin/car-demon-admin.php' );
include( 'admin/car-demon-cell-providers.php' );
include( 'admin/car-demon-columns.php' );
include( 'admin/car-demon-admin-settings.php' );
include( 'admin/car-demon-build-pages.php' );
include( 'widgets/car-demon-calculator-widget.php' );
include( 'includes/car-demon-payment-calculator.php' );
include( 'widgets/car-demon-tag-cloud.php' );
include( 'widgets/car-demon-random-cars.php' );
include( 'widgets/car-demon-car-search-widget.php' );
include( 'widgets/car-demon-vehicle-contact-widget.php' );
include( 'widgets/car-demon-compare-widget.php' );
include( 'forms/car-demon-service-form.php' );
include( 'forms/car-demon-service-quote.php' );
include( 'forms/car-demon-part-request.php' );
include( 'forms/car-demon-contact-us.php' );
include( 'forms/car-demon-trade-form.php' );
include( 'forms/car-demon-finance-form.php' );
include( 'vin-query/car-demon-vin-query.php' );
include( 'vin-query/car-demon-vin-query-admin.php' );
include( 'car-demon-header.php' );

add_filter('the_content', 'car_demon_shortcodes');
add_filter('wp_head', 'car_demon_header');

function car_demon_init() {
	load_plugin_textdomain( 'car-demon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'car_demon_init');

function start_car_demon() {
	if (!session_id()) {
		session_start();
	}
	if ( ! is_admin() ) {
		require('forms/car-demon-form-key-class.php');
		global $cd_formKey;
		$cd_formKey = new cd_formKey();
	}
	if (isset($_GET['sales_code'])) {
		$sales_code = $_GET['sales_code'];
	} else {
		$sales_code = '';
	}
	if ($sales_code=="0") {
		setcookie("sales_code",$sales_code, time()-3600*24*90, '/');
		$redirect = car_demon_self_url();
		$redirect = str_replace('?sales_code='.$sales_code, '', $redirect);
		$redirect = str_replace('&sales_code='.$sales_code, '', $redirect);
		if (empty($redirect)) { $redirect = site_url(); }
		header('Location:'.$redirect);
	}
	if (!empty($sales_code)) {
		setcookie("sales_code",$_GET['sales_code'], time()+3600*24*90, '/');
		$redirect = car_demon_self_url();
		$redirect = str_replace('?sales_code='.$sales_code, '', $redirect);
		$redirect = str_replace('&sales_code='.$sales_code, '', $redirect);
		if (empty($redirect)) { $redirect = site_url(); }
		header('Location:'.$redirect);
	}
	car_demon_subdomains();
}
add_action('wp', 'start_car_demon');

if (!is_admin()) {
  add_filter('widget_text', 'car_demon_text_filter', 11);
  add_filter('the_content', 'car_demon_text_filter', 11);
}

function car_demon_text_filter($body) {
	$text = replace_contact_info_tags(0, $body);
	return $text;
}

function car_demon_self_url() {
    if(!isset($_SERVER['REQUEST_URI'])){
        $serverrequri = $_SERVER['PHP_SELF'];
    }else{
        $serverrequri = $_SERVER['REQUEST_URI'];
    }
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = car_demon_str_left(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri;   
}

function car_demon_subdomains() {
	if (empty($_COOKIE['domain_check'])) {
		$site_url = car_demon_get_site_url();
		setcookie("domain_check","1", time()+3600*24*90, '/', $site_url);
		if (empty($_COOKIE['sales_code'])) {
			$this_url = $_SERVER['SERVER_NAME'];
			$this_url = str_replace('www.', '', $this_url);
			$this_url = str_replace('http://', '', $this_url);
			$this_url = str_replace('https://', '', $this_url);
			$site_url =  car_demon_get_site_url();
			$site_url = str_replace('www.', '', $site_url);
			$site_url = str_replace('http://', '', $site_url);
			$site_url = str_replace('https://', '', $site_url);
			$site_url = 'check-'.$site_url;
			if (strpos($site_url, $this_url) == 0) {
				global $wpdb;
				$prefix = $wpdb->prefix;
				$sql = 'SELECT user_id from '.$prefix.'usermeta where meta_key="custom_url" AND meta_value like "%'.$this_url.'%"';
				$posts = $wpdb->get_results($sql);
				if ($posts) {
					foreach ($posts as $post) {
						$user_id = $post->user_id;
					}
					$site_url = str_replace('check-', '', $site_url);
					if (empty($redirect)) { $redirect = site_url().'?sales_code='.$user_id; }
					header('Location:'.$redirect);
				}
			}
		}
	}
}

function car_demon_get_site_url() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = 'SELECT option_value from '.$prefix.'options WHERE option_name="siteurl"';
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$site_url = $post->option_value;
		}
	}
	return $site_url;
}

function car_demon_str_left($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}

function car_demon_shortcodes($content) {
	if (strpos($content, '[-service_form-]')) {
		$service_form = car_demon_service_form();
		$content = str_replace('[-service_form-]', $service_form, $content);
	}
	if (strpos($content, '[-service_quote-]')) {
		$service_quote = car_demon_service_quote();
		$content = str_replace('[-service_quote-]', $service_quote, $content);
	}
	if (strpos($content, '[-part_request-]')) {
		$part_quote = car_demon_part_request();
		$content = str_replace('[-part_request-]', $part_quote, $content);
	}	
	if (strpos($content, '[-contact_us-]')) {
		$contact_us = car_demon_contact_request();
		$content = str_replace('[-contact_us-]', $contact_us, $content);
	}	
	if (strpos($content, '[-trade-]')) {
		$trade = car_demon_trade_form();
		$content = str_replace('[-trade-]', $trade, $content);
	}
	if (strpos($content, '[-finance_form-]')) {
		$finance_form = car_demon_finance_form();
		$content = str_replace('[-finance_form-]', $finance_form, $content);
	}
	if (strpos($content, '[-staff_page-]')) {
		$staff_page = car_demon_staff_page();
		$content = str_replace('[-staff_page-]', $staff_page, $content);
	}
	return $content;
}

function car_demon_session() {
	$car_demon_options = car_demon_options();
	if (!session_id()) {
		session_start();
	}
	$_SESSION['car_demon_options'] = $car_demon_options;
	//--Register Mobile Widget Areas
	if ($car_demon_options['mobile_theme'] == 'Yes') {
		car_demon_register_mobile_sidebars();
	}
}
add_action( 'init', 'car_demon_session', 1 );

function register_car_demon_menus(){
	if ($_SESSION['car_demon_options']['mobile_theme'] == 'Yes') {
		register_nav_menus(
			array(
				'mobile-menu' => __( 'Mobile Menu','car-demon' )
			)	
		);
	}
}
add_action( 'init', 'register_car_demon_menus' );

if ( function_exists('register_sidebar') ){
	car_demon_register_sidebars();
}

if ( ! function_exists( 'CarDemon_comment' ) ) :
function CarDemon_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	echo '<li ';
		comment_class();
	echo join(array(
		' id="li-comment-',
		$comment->comment_ID,
		'">',
		'<span>',
		$comment->comment_author,
		' said: </span>',
		$comment->comment_content,
		'</li>'
	));
}
endif;

if ( ! function_exists( 'CarDemon_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 */
function CarDemon_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'car-demon' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'car-demon' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'car-demon' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

if ( ! function_exists( 'CarDemon_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 */
function CarDemon_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'car-demon' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'car-demon' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

// Add support for Featured Images
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    add_image_size('index-categories', 150, 150, true);
    add_image_size('page-single', 350, 350, true);
}

function car_demon_theme_js($content) {
	$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$content .= '
	<script>
		function ImgError(source, pic){
			source.src = "'.$pluginpath.'images/"+pic;
			source.onerror = "";
			return true;
		}
	</script>';
	echo $content;
	return;
}
add_action('wp_head', 'car_demon_theme_js');

function rwh($x,$y) {
	if ($y == 0) {
		$new_string = $x;	
	} else {
		$new_string = '<h'.$y.'>'.$x.'</h'.$y.'>';
	}
	return $new_string;
}

function get_car_from_stock($selected_car) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value = '".$selected_car."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
			$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
			$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
			$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
			$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
			$car = $vehicle_condition .' '. $vehicle_year .' '. $vehicle_make .' '. $vehicle_model;
			$car_link = get_permalink($post_id);
			if ($_COOKIE["sales_code"]) {
				$car_link = $car_link .'?sales_code='.$_COOKIE["sales_code"];
			}
			$car_img = wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
			$car_img = str_replace(chr(32), "%20", $car_img);
			$car_pic = '<p align="center"><a href="'.$car_link.'" /><img width="400" src="'.$car_img.'" title="'.$car.'" /></a></p>';
			$x = $post->meta_value .' '. $car .'<br />'.$car_pic;
		}
	}
	return $x;
}

function get_car_id_from_stock($selected_car) {
	global $wpdb;
	$post_id = '';
	$prefix = $wpdb->prefix;
	$sql = "Select post_id, meta_value from ".$prefix."postmeta WHERE meta_key='_stock_value' and meta_value = '".$selected_car."'";
	$posts = $wpdb->get_results($sql);
	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->post_id;
		}
	}
	return $post_id;
}

function build_location_hcard($location, $condition) {
	$location = str_replace(chr(32), '-', $location);
	$location = strtolower($location);
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	if ($condition == 'New') {
		$user_full_name = get_option($location.'_new_sales_name');
		$user_email = get_option($location.'_new_sales_email');
		$user_phone = get_option($location.'_new_sales_number');
		$custom_photo = get_option($location.'_new_small_photo_url');
	} else {
		$user_full_name = get_option($location.'_used_sales_name');
		$user_email = get_option($location.'_used_sales_email');
		$user_phone = get_option($location.'_used_sales_number');
		$custom_photo = get_option($location.'_used_small_photo_url');
	}
	$user_description = '';
	$job_title = $condition.' '.__('sales','car-demon');	
	if (empty($custom_photo)) {
		$custom_photo = $car_demon_pluginpath.'images/person.gif';
	}
	$facebook = $location.'_facebook_page';
	$x = '<div id="staff-card-'.$user_id.'" class="staff_card">';
	$x .= '<img src="'.$custom_photo.'" alt="photo of '.$user_full_name.'" class="photo" />';
		$x .='<span class="fn n">';
			$x .='<span class="given-name">'.$user_full_name.'</span>';
		$x .='</span>';
		$x .='<div class="job_title">'.$job_title.'</div>';
		$x .='<div class="org">'.$user_location.'</div>';
		$x .='<a class="email" href="mailto:'.$user_email.'">'.$user_email.'</a>';

		$x .='<div class="tel">'.$user_phone.'</div>';
		if (!empty($facebook)) { $x .='<a class="url" href="'.$facebook.'">Facebook</a>'; }
		if (!empty($user_description)) {
			if (strlen($user_description) < 100) {
				$x .='<div class="user_description">'.$user_description.'</div>';
			}
			else {
				$x .='<div class="user_description">';
					$x .= $user_description;
				$x .= '</div>';
			}
		}
	$x .='</div>';
	return $x;
}

add_action('after_setup_theme', 'cardemon_language');

function cardemon_language(){
    load_theme_textdomain('car-demon', get_template_directory() . '/languages');
}

add_action("template_redirect", 'car_demon_theme_redirect');

function car_demon_theme_redirect() {
	if ($_SESSION['car_demon_options']['use_theme_files'] == 'Yes') {
		global $wp;
		$plugindir = dirname( __FILE__ );
		// Custom Post Type cars_for_sale
		$template_directory = get_template_directory();
		if (isset($wp->query_vars["post_type"]) == 'cars_for_sale') {
			if (isset($wp->query_vars["cars_for_sale"])) {
				$templatefilename = 'single-cars_for_sale.php';
			} else {
				$templatefilename = 'archive-cars_for_sale.php';	
			}
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		// Custom Taxonomy
		} elseif (isset($wp->query_vars["vehicle_condition"])) {
			$templatefilename = 'archive-cars_for_sale.php';
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		} elseif (isset($wp->query_vars["vehicle_year"])) {
			$templatefilename = 'archive-cars_for_sale.php';
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		} elseif (isset($wp->query_vars["vehicle_make"])) {
			$templatefilename = 'archive-cars_for_sale.php';
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		} elseif (isset($wp->query_vars["vehicle_model"])) {
			$templatefilename = 'archive-cars_for_sale.php';
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		} elseif (isset($wp->query_vars["vehicle_location"])) {
			$templatefilename = 'archive-cars_for_sale.php';
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		} elseif (isset($wp->query_vars["vehicle_body_style"])) {
			$templatefilename = 'archive-cars_for_sale.php';
			if (file_exists($template_directory . '/' . $templatefilename)) {
				$return_template = $template_directory . '/' . $templatefilename;
			} else {
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
			}
			do_car_demon_theme_redirect($return_template);
		// Search Cars
		} elseif (isset($wp->query_vars["s"]) == 'cars') {
			if ($_GET['car']==1) {
				$templatefilename = 'search.php';
				$return_template = $plugindir . '/theme-files/' . $templatefilename;
				global $post, $wp_query;
				$wp_query->is_404 = false;
				include($return_template);
				die();
			}
		}
	}
}

function do_car_demon_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

if ( !is_admin() ) add_filter( 'pre_get_posts', 'get_car_demon_posts' );

function get_car_demon_posts( $query ) {
	if ( is_post_type_archive('cars_for_sale') || is_tax('vehicle_condition') || is_tax('vehicle_year') || is_tax('vehicle_make') || is_tax('vehicle_model') || is_tax('vehicle_body_style') ){
		if ($query->is_main_query()) {
			$query->set( 'post_type', array( 'cars_for_sale' ) );
			$query->set('posts_per_page', 9);
		}
	}
	return $query;
}

?>