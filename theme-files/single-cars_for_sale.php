<?php
/**
 * The Template for displaying all single cars.
 *
 * @package WordPress
 * @subpackage CarDemon 
 * @since CarDemon 1.0
 */
$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
get_header();
wp_enqueue_script('car-demon-single-car-js', WP_CONTENT_URL . '/plugins/car-demon/theme-files/js/car-demon-single-cars.js.php');
wp_enqueue_style('car-demon-single-car-css', WP_CONTENT_URL . '/plugins/car-demon/theme-files/css/car-demon-single-car.css');
echo car_demon_photo_lightbox();
?>
		<div id="demon-container">
			<div id="demon-content" role="main">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 
	$post_id = get_the_ID();
	$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
	$vehicle_year = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' )),0);
	$vehicle_make = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' )),0);
	$vehicle_model = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' )),0);
	$vehicle_condition = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' )),0);
	$vehicle_body_style = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_body_style', '','', '', '' )),0);
	$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
	$vehicle_stock_number = strip_tags(get_post_meta($post_id, "_stock_value", true));
	$vehicle_exterior_color = strip_tags(get_post_meta($post_id, "_exterior_color_value", true));
	$vehicle_interior_color = strip_tags(get_post_meta($post_id, "_interior_color_value", true));
	$vehicle_mileage = strip_tags(get_post_meta($post_id, "_mileage_value", true));
	$vehicle_fuel = strip_tags(get_post_meta($post_id, "_fuel_type_value", true));
	$vehicle_transmission = strip_tags(get_post_meta($post_id, "_transmission_value", true));
	$vehicle_cylinders = strip_tags(get_post_meta($post_id, "_cylinders_value", true));
	$vehicle_engine = strip_tags(get_post_meta($post_id, "_engine_value", true));
	$vehicle_doors = strip_tags(get_post_meta($post_id, "_doors_value", true));
	$vehicle_trim = strip_tags(get_post_meta($post_id, "_trim_value", true));
	$vehicle_warranty = strip_tags(get_post_meta($post_id, "_warranty_value", true));
	$vehicle_details = rwh($vehicle_vin,3);
	$vehicle_details .= rwh($vehicle_year,3);
	$vehicle_details .= rwh($vehicle_make,3);
	$vehicle_details .= rwh($vehicle_model,3);
	$vehicle_details .= rwh($vehicle_condition,3);
	$vehicle_details .= rwh($vehicle_body_style,3);
	$vehicle_details .= rwh($vehicle_location,3);
	$car_title = get_car_title_slug($post_id);
	$car_head_title = get_car_title($post_id);
	$car_url = get_permalink($post_id);
	//=========================Contact Info==========================
	$car_contact = get_car_contact($post_id);
	$contact_sales_name = $car_contact['sales_name'];
	$contact_sales_email = $car_contact['sales_email'];
	$contact_sales_phone = $car_contact['sales_phone'];
	$contact_sales_mobile = $car_contact['sales_mobile'];
	$contact_sales_mobile_provider = $car_contact['sales_mobile_provider'];
	$contact_trade_name = $car_contact['trade_name'];
	$contact_trade_email = $car_contact['trade_email'];
	$contact_trade_phone = $car_contact['trade_phone'];
	$contact_trade_url = $car_contact['trade_url'];
	$contact_finance_name = $car_contact['finance_name'];
	$contact_finance_email = $car_contact['finance_email'];
	$contact_finance_phone = $car_contact['finance_phone'];
	$contact_finance_url = $car_contact['finance_url'];
	//===============================================================
	$detail_output = '<div class="car_title_div"><h3 class="car_title">'.$car_head_title.'</h3>';
	$detail_output .= '<ul>';
		$detail_output .= '<li><strong>Condition:</strong> '.$vehicle_condition.'</li>';
		$detail_output .= '<li><strong>Mileage:</strong> '.$vehicle_mileage.'</li>';
		$detail_output .= '<li><strong>Stock#:</strong> '.$vehicle_stock_number.'</li>';
		$detail_output .= '<li><strong>VIN#:</strong> '.$vehicle_vin.'</li>';
		$detail_output .= '<li><strong>Color:</strong> '.$vehicle_exterior_color.'</li>';
		$detail_output .= '<li><strong>Transmission:</strong> '.$vehicle_transmission.'</li>';
		$detail_output .= '<li><strong>Engine:</strong> '.$vehicle_engine.'</li>';
		$detail_output .= get_vehicle_price($post_id);
	$detail_output .= '</ul></div>';
	echo car_demon_email_a_friend($post_id, $vehicle_stock_number);
?>
				<div id="demon-post-<?php the_ID(); ?>" class="car_content">
					<div class="start_car">&nbsp;</div>
					<div class="car_buttons_div">
					<?php if (!empty($contact_finance_url)) { 
							if ($car_contact['finance_popup'] == 'Yes') {
							?>
							<div class="featured-button">
								<p><a onclick="window.open('<?php echo $contact_finance_url .'?stock_num='.$vehicle_stock_number; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>','finwin','width=<?php echo $car_contact['finance_width']; ?>, height=<?php echo $car_contact['finance_height']; ?>, menubar=0, resizable=0')"><?php _e('GET FINANCED', 'car-demon'); ?></a></p>
							</div>
							<?php 
							}
							else {
							?>
							<div class="featured-button">
								<p><a href="<?php echo $contact_finance_url .'?stock_num='.$vehicle_stock_number; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>"><?php _e('GET FINANCED', 'car-demon'); ?></a></p>
							</div>
					<?php 
							}
						} 
						if (!empty($contact_trade_url)) {
						?>
							<div class="featured-button">
								<p><a href="<?php echo $contact_trade_url .'?stock_num='.$vehicle_stock_number; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>"><?php _e('TRADE-IN QUOTE', 'car-demon'); ?></a></p>
							</div>
					<?php
						}
					?>
						<div class="email_a_friend">
							<a href="http://www.facebook.com/share.php?u=<?php echo $car_url; ?>&amp;t=<?php echo $car_head_title; ?>" target="fb_win">
								<img title="<?php _e('Share on Facebook', 'car-demon'); ?>" src="<?php echo $car_demon_pluginpath; ?>images/social_fb.png" />
							</a>
							<a target="tweet_win" href="http://twitter.com/share?text=Check out this <?php echo $car_head_title; ?>" title="<?php _e('Click to share this on Twitter', 'car-demon'); ?>">
								<img title="<?php _e('Share on Twitter', 'car-demon'); ?>" src="<?php echo $car_demon_pluginpath; ?>images/social_twitter.png" />
							</a>
							<img onclick="email_friend();" title="<?php _e('Email to a Friend', 'car-demon'); ?>" src="<?php echo $car_demon_pluginpath; ?>images/social_email.png" />
						</div>
					</div>
					<div class="car-demon-entry-content">
					<?php echo car_photos($post_id, $detail_output, $vehicle_condition); ?>
						<?php echo car_demon_display_similar_cars($vehicle_body_style, $post_id); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'car-demon' ), 'after' => '</div>' ) ); ?>
					</div><!-- .car-demon-entry-content -->
					<?php echo car_demon_vehicle_detail_tabs($post_id); ?>
				</div><!-- #post-## -->
<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #container -->
<div id="sideBar1" class="car_side_bar">
<ul>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Vehicle Detail Sidebar')) : ?>
	<?php endif; ?>
</ul>
<br class="clear_car">
</div>
<?php get_footer(); 

function car_photos($post_id, $details, $vehicle_condition) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$mileage_value = '';
	$car_title = '';
	$car_js = '';
	$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
	if (empty($ribbon)) {
		$ribbon = 'no-ribbon';		
	}
	if ($ribbon != 'custom_ribbon') {
		$ribbon = str_replace('_', '-', $ribbon);
		$current_ribbon = '<img src="'. $car_demon_pluginpath .'images/ribbon-'.$ribbon.'.png" width="112" height="112" id="ribbon">';
	} else {
		$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);
		$current_ribbon = '<img src="'.$custom_ribbon_file.'" width="112" height="112" id="ribbon">';
	}
	if (isset($_SESSION['car_demon_options']['dynamic_ribbons'])) {
		if ($_SESSION['car_demon_options']['dynamic_ribbons'] == 'Yes') {
			$current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '112');
		}
	}
	$this_car = '<div>';
		$this_car .= '<div class="car_detail_div">';
			$this_car .= '<div class="car_main_photo_box">';
				$this_car .= $current_ribbon;
				$this_car .= '<img onclick="open_car_demon_lightbox(\''.$car_title.'_pic\');" src="'. $car_demon_pluginpath .'images/look_close.png" width="358" height="271" alt="New Ribbon" id="look_close">';
				$this_car .= '<div id="main_thumb"><img onclick="open_car_demon_lightbox(\''.$car_title.'_pic\');" onerror="ImgError(this, \'no_photo.gif\');" id="'.$car_title.'_pic" name="'.$car_title.'_pic" class="car_demon_main_photo" width="350px" src="';
				$main_guid = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
				$this_car .= $main_guid;
				$this_car .= '" /></div>';
			$this_car .= '</div>';
			$this_car .= '<div class="car_details_box">';
				$this_car .= $details;
			$this_car .= '</div>';
		$this_car .= '</div>';
		// Thumbnails
		$thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
		$this_car .= '<div class="nohor" id="car_demon_thumbs">';
		$cnt = 0;
		$car_js .= 'carImg['.$cnt.']="'.trim($main_guid).'";'.chr(13);
		$photo_array = '<img class="car_demon_thumbs" onClick=\'MM_swapImage("'.$car_title.'_pic","","'.trim($main_guid).'",1);active_img('.$cnt.')\' src="'.trim($main_guid).'" width="53" />';
		$this_car .= $photo_array;
		foreach($thumbnails as $thumbnail) {
			$guid = $thumbnail->guid;
			if (!empty($guid)) {
				if ($main_guid != $guid) {
					$cnt = $cnt + 1;
					$car_js .= 'carImg['.$cnt.']="'.trim($guid).'";'.chr(13);
					$photo_array = '<img class="car_demon_thumbs" onClick=\'MM_swapImage("'.$car_title.'_pic","","'.trim($guid).'",1);active_img('.$cnt.')\' src="'.trim($guid).'" width="53" />';
					$this_car .= $photo_array;
				}
			}
		}
		$this_car .= '</div>';
		// End Thumbnails
	$this_car .= '</div>';
	$total_pics = $cnt;
	$car_js = '
		<input type="hidden" id="current_img_num" value="0" />
		<input type="hidden" id="current_img_name" />
		<input type="hidden" id="image_count" value="'.$total_pics.'" />
		<script>
			function photo_img_array() {
				var carImg = new Array;
				'.$car_js.'
				return carImg;
			}
			setInterval(function(){car_slide_show()},3000);
		</script>';
	$html = $this_car.$car_js;
	$html = apply_filters( 'car_demon_photo_hook', $html, $post_id, $details, $vehicle_condition, 'desktop' );
	return $html;
}

function car_demon_display_similar_cars($body_style, $current_id) {
	global $wpdb;
	$show_it = '';
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('widgets','',$car_demon_pluginpath);
	$my_tag_id = get_term_by('slug', $body_style, 'vehicle_body_style');
	if (!empty($body_style)) {
		$my_search = " AND $wpdb->term_taxonomy.taxonomy = 'vehicle_body_style' AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id->term_id.")";
		$str_sql = "SELECT wposts.ID
			FROM $wpdb->posts wposts
				LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
				LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE wposts.post_type='cars_for_sale'
				AND wpostmeta.meta_key = 'sold'
				AND wpostmeta.meta_value = 'no'".$my_search.'
				ORDER BY ID LIMIT 4';
		$the_lists = $wpdb->get_results($str_sql);
	} else {
		$the_lists = '';
	}
	$car = '';
	$cnt = 0;
	if (!empty($the_lists)) {
		$car .= '<h3>'.__('Other Great Deals', 'car-demon').'</h3>';
		$car .= '<div class="similar_cars_box">';
		foreach ($the_lists as $the_list) {
			$post_id = $the_list->ID;
			if ($post_id != $current_id) {
				$cnt = $cnt + 1;
				if ($cnt < 4) {
					$show_it = 1;
					$stock_value = get_post_meta($post_id, "_stock_value", true);
					$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
					$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
					$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
					$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
					$title = get_car_title($post_id);
					$mileage_value = get_post_meta($post_id, "_mileage_value", true);
					$detail_output = '<span class="random_title">'.$title.'</span><br />';
					$detail_output .= '<span class="random_text">';
						$detail_output .= __('Condition:', 'car-demon').' '.$vehicle_condition.'<br />';			
					$detail_output .= '</span>';
					$detail_output .= '<span class="random_text">';
						$detail_output .= __('Mileage:', 'car-demon').' '.$mileage_value.'<br />';
					$detail_output .= '</span>';
					$detail_output .= '<span class="random_text">';
						$detail_output .= __('Stock#:', 'car-demon').' '.$stock_value;
					$detail_output .= '</span>';
					$link = get_permalink($post_id);
					$img_output = "<img onclick='window.location=\"".$link."\";' title='".__("Click for price on this", "car-demon")." ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='random_widget_image' width='180px' height='135px' src='";
					$img_output .= wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
					$img_output .= "' />";
					$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
					if (empty($ribbon)) {
						$ribbon = 'no-ribbon';		
					}					
					if ($ribbon != 'custom_ribbon') {
						$ribbon = str_replace('_', '-', $ribbon);
						$current_ribbon = '<img class="similar_car_ribbon" src="'. $car_demon_pluginpath .'images/ribbon-'.$ribbon.'.png" width="76" height="76" id="ribbon">';
					} else {
						$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);
						$current_ribbon = '<img class="similar_car_ribbon" src="'.$custom_ribbon_file.'" width="76" height="76" id="ribbon">';
					}
					if (isset($_SESSION['car_demon_options']['dynamic_ribbons'])) {
						if ($_SESSION['car_demon_options']['dynamic_ribbons'] == 'Yes') {
							$current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '76');
						}
					}
					$car .= '
						<div class="random similar_car">
							<div class="random_img">
								'.$current_ribbon.'
								<img class="look_close similar_car_look_close" onclick="window.location=\''.$link.'\';" src="'. $car_demon_pluginpath .'images/look_close.png" width="188" height="143" id="look_close">
								'.$img_output.'
							</div>
							<div class="random_description">
								'.$detail_output.'
							</div>
						</div>';
				}
			}
		}
		$car .= '</div>';
	}
	if ($show_it != 1) {
		$car = '';
	}
	return $car;
}
?>