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
?>
<div class="car_demon_light_box" id="car_demon_light_box">
	<div class="car_demon_photo_box" id="car_demon_photo_box"">
		<div class="close_light_box" onclick="close_car_demon_lightbox();">(close) X</div>
		<div id="car_demon_light_box_main_email" style="margin-left:80px; margin-top:25px;"></div>
		<div id="car_demon_light_box_main" style="margin-left:80px; margin-top:25px;">
			<img id="car_demon_light_box_main_img" src="" />
			<div class="run_slideshow_div" onclick="car_slide_show();" id="run_slideshow_div">
					<input type="checkbox" id="run_slideshow" /> Run Slideshow
			</div>
			<div class="photo_next" id="photo_next">
				<img src="<?php echo $car_demon_pluginpath; ?>images/btn_next.png" onclick="get_next_img();" title="Next" />
			</div>
			<div class="photo_prev" id="photo_prev">
				<img src="<?php echo $car_demon_pluginpath; ?>images/btn_prev.png" onclick="get_prev_img();" title="Previous" />
			</div>
		</div>
		<div class="hor_lightbox" id="car_demon_thumb_box" style="margin-left:100px;">
		</div>
	</div>
</div>
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
	$car_title = $vehicle_year .'_' . $vehicle_make .'_' . $vehicle_model;
	$car_title = trim($car_title);
	$car_head_title = $vehicle_year .' ' . $vehicle_make .' ' . $vehicle_model;
	$car_title = str_replace(chr(32), '_', $car_title);
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
	$detail_output = '<div style="width:225px;font-size:12px;"><h3 style="margin-top:-10px;">'.$car_head_title.'</h3>';
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
	$tab_cnt = 1;
	$vin_query = '';
	$about_cnt = 2;
	$content = get_the_content();
	$content = trim($content);
	if (empty($content)) {
		$location_lists = get_the_terms($post_id, "vehicle_location");
		if ($location_lists) {
			foreach ($location_lists as $location_list) {
				$location_slug = $location_list->slug;
			}
		}
		else {
			$location_slug = "default";
		}
		$content = get_option($location_slug.'_default_description');
		if (empty($content)) {
			$content = get_default_description();
		}
	}
	if ($_SESSION['car_demon_options']['use_about'] == 'Yes') {
		$about = 1;
		$tab_cnt = $tab_cnt + 1;
	}
	else {
		$about = '';
	}
	if (!empty($_SESSION['car_demon_options']['vinquery_id'])) {
		if ($vehicle_year > 1984) {
			car_demon_get_vin_query($post_id, $vehicle_vin);
		}
	}
	$vin_query_decode_array = get_post_meta($post_id, 'decode_string');
	if ($vin_query_decode_array) {
		$vin_query_decode = $vin_query_decode_array[0];
	}
	else {
		$vin_query_decode = '';
	}
	if (!empty($vin_query_decode['decoded_fuel_economy_city'])) {
		$tab_cnt = $tab_cnt + 5;
		$vin_query = 1;
		$about_cnt = 7;
	}
	else {
		$vin_query = 0;
	}
?>				
				<div id="email_friend_div" style="display:none;">
				<div id="ef_contact_final_msg_tmp" style="display:none; width:625px; height:550px; padding:5px;"></div>
					<div style="background:#DDDDDD; width:625px; height:550px; padding:5px;" id="main_email_friend_div_tmp">
					<h2>Send this car to a friend</h2><hr />
						<form enctype="multicontact/form-data" action="?send_contact=1" method="post" class="cdform contact-appointment " id="email_friend_form_tmp" name="email_friend_form_tmp" style="margin: 10px auto 0 auto;">
						<?php 
							global $cd_formKey;
							$cd_formKey->outputKey();
						?>
						<input type="hidden" name="ef_stock_num_tmp" id="ef_stock_num_tmp" value="<?php echo $vehicle_stock_number; ?>" />
								<fieldset class="cd-fs1">
								<legend>Your Information</legend>
								<ol class="cd-ol">
									<li class=""><label for="cd_field_2"><span>Your Name</span></label><input type="text" name="ef_cd_name_tmp" id="ef_cd_name_tmp" class="single fldrequired" value="Your Name" onfocus="ef_clearField(this)" onblur="ef_setField(this)"><span class="reqtxt">*</span></li>
									<li class=""><label for="cd_field_4"><span>Your Email</span></label><input type="text" name="ef_cd_email_tmp" id="ef_cd_email_tmp" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
									<li class=""><label for="cd_field_2"><span>Friend's Name</span></label><input type="text" name="ef_cd_friend_name_tmp" id="ef_cd_friend_name_tmp" class="single fldrequired" value="Friend Name" onfocus="ef_clearField(this)" onblur="ef_setField(this)"><span class="reqtxt">*</span></li>
									<li class=""><label for="cd_field_4"><span>Friend's Email</span></label><input type="text" name="ef_cd_friend_email_tmp" id="ef_cd_friend_email_tmp" class="single fldemail fldrequired" value=""><span class="emailreqtxt">*</span></li>
								</ol>
								</fieldset>
								<fieldset class="cd-fs4">
								<legend>Your Message</legend>
								<ol class="cd-ol">
									<li id="li-5" class=""><textarea style="margin-left:10px;width:250px;height:70px;" name="ef_comment_tmp" id="ef_comment_tmp" class="area fldrequired">Check out this <?php echo $car_head_title; ?>, stock number <?php echo $vehicle_stock_number; ?>!</textarea><br><span class="reqtxt" style="margin-left:10px;"><br>* required</span></li>
								</ol>
								</fieldset>
								<div id="ef_contact_msg_tmp" style="display:none;"></div>
								<p class="cd-sb"><input type="button" style="margin-left:120px;" name="ef_search_btn_tmp" id="ef_sendbutton_tmp" class="search_btn" value="Send Now!" onclick="return ef_car_demon_validate()"></p>
						</form>
					</div>
				</div>
				<div id="demon-post-<?php the_ID(); ?>" style="background-color:#FFFFFF;height:100%;">
					<div style="margin-top:2px;height:1px;">&nbsp;</div>
					<div style="width:700px;height:63px;">
<?php if (!empty($contact_finance_url)) { 
		if ($car_contact['finance_popup'] == 'Yes') {
		?>
						<div class="featured-button">
							<p><a onclick="window.open('<?php echo $contact_finance_url .'?stock_num='.$vehicle_stock_number; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>','finwin','width=<?php echo $car_contact['finance_width']; ?>, height=<?php echo $car_contact['finance_height']; ?>, menubar=0, resizable=0')">GET FINANCED</a></p>
						</div>
<?php 
		}
		else {
		?>
						<div class="featured-button">
							<p><a href="<?php echo $contact_finance_url .'?stock_num='.$vehicle_stock_number; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>">GET FINANCED</a></p>
						</div>
<?php 
		}
	} 

	if (!empty($contact_trade_url)) {
?>
						<div class="featured-button">
							<p><a href="<?php echo $contact_trade_url .'?stock_num='.$vehicle_stock_number; ?>&sales_code=<?php echo $car_contact['sales_code']; ?>">TRADE-IN QUOTE</a></p>
						</div>
<?php
	}
?>
						<div class="email_a_friend">
							<a href="http://www.facebook.com/share.php?u=<?php echo $car_url; ?>&amp;t=<?php echo $car_head_title; ?>" target="fb_win">
								<img style="cursor:pointer;" title="Share on Facebook" src="<?php echo $car_demon_pluginpath; ?>images/social_fb.png" />
							</a>
							<a target="tweet_win" href="http://twitter.com/share?text=Check out this <?php echo $car_head_title; ?>" title="Click to share this on Twitter">
								<img style="cursor:pointer;" title="Share on Twitter" src="<?php echo $car_demon_pluginpath; ?>images/social_twitter.png" />
							</a>
							<img onclick="email_friend();" style="cursor:pointer;" title="Email to a Friend" src="<?php echo $car_demon_pluginpath; ?>images/social_email.png" />
						</div>
					</div>
					<div class="car-demon-entry-content">
					<?php echo car_photos($post_id, $detail_output, $vehicle_condition); ?>
<?php echo car_demon_display_similar_cars($vehicle_body_style, $post_id); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'CarDemon' ), 'after' => '</div>' ) ); ?>
					</div><!-- .car-demon-entry-content -->
<div id="car_features_box" class="car_features_box">
	<div class="car_features">  
		<ul class="tabs"> 
			<li><a href="javascript:car_demon_switch_tabs(1, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_1" class="active">Description</a></li>  
			<?php if ($vin_query == 1) { ?>
				<li><a href="javascript:car_demon_switch_tabs(2, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_2">Specs</a></li>  
				<li><a href="javascript:car_demon_switch_tabs(3, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_3">Safety</a></li>
				<li><a href="javascript:car_demon_switch_tabs(4, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_4">Convenience</a></li>
				<li><a href="javascript:car_demon_switch_tabs(5, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_5">Comfort</a></li>
				<li><a href="javascript:car_demon_switch_tabs(6, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_6">Entertainment</a></li>
			<?php } ?>
			<?php if ($about == 1) { ?>
				<li><a href="javascript:car_demon_switch_tabs(<?php echo $about_cnt;?>, <?php echo $tab_cnt;?>, 'tab_', 'content_');" id="tab_<?php echo $about_cnt;?>">About</a></li>
			<?php } ?>
		</ul>  
		<div id="content_1" class="car_features_content"><?php echo $content; ?></div> 
		<?php if ($vin_query == 1) {
			$specs = get_vin_query_specs($vin_query_decode, $vehicle_vin);
			$safety = get_vin_query_safety($vin_query_decode);
			$convienience = get_vin_query_convienience($vin_query_decode);
			$comfort = get_vin_query_comfort($vin_query_decode);
			$entertainment = get_vin_query_entertainment($vin_query_decode);
			?>
			<div id="content_2" class="car_features_content"><?php echo $specs; ?></div> 
			<div id="content_3" class="car_features_content"><?php echo $safety; ?></div>  
			<div id="content_4" class="car_features_content"><?php echo $convienience; ?></div>
			<div id="content_5" class="car_features_content"><?php echo $comfort; ?></div>
			<div id="content_6" class="car_features_content"><?php echo $entertainment; ?></div>
		<?php } ?>
		<?php if ($about == 1) { ?>
				<div id="content_<?php echo $about_cnt;?>" class="car_features_content" style="display:none;">
					<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
						<div id="entry-author-info">
							<?php
							if ($_COOKIE['sales_code']) {
								$user_id = $_COOKIE['sales_code'];
								$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
								$location_approved = 0;
								if ($vehicle_location == $user_location) {
									$location_approved = 1;
								}
								else {
									$location_approved = esc_attr( get_the_author_meta( 'lead_locations', $user_id ) );
								}
							}
							if ($location_approved == 1) {
								$user_sales_type = 0;
								if ($vehicle_condition == 'New') {
									$user_sales_type = get_the_author_meta('lead_new_cars', $user_id);	
								}
								else {
									$user_sales_type = get_the_author_meta('lead_used_cars', $user_id);		
								}
							}
							if ($user_sales_type == 1) {
								echo build_user_hcard($_COOKIE['sales_code'], 1);
							}
							else {
								echo build_location_hcard($vehicle_location, $vehicle_condition);
							} ?>
						</div><!-- #entry-author-info -->
					<?php endif; ?>
				</div>
		<?php } ?>
	</div>
</div>
				</div><!-- #post-## -->
<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #container -->
<div id="sideBar1" style="float:left;">
<ul>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Vehicle Detail Sidebar')) : ?>
	<?php endif; ?>
</ul>
<br style="clear: both">
</div>
<?php get_footer(); 

function car_photos($post_id, $details, $vehicle_condition) {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$mileage_value = '';
	$car_title = '';
	$car_js = '';
	$ribbon = 'ribbon-just-added';
	$ribbon = 'ribbon-great-deal';
	if ($vehicle_condition == 'New') {
		$ribbon = 'ribbon-new';
	}
	else {
		if ($mileage_value < 60000) { $ribbon = 'ribbon-low-miles';	}
		$tmp_price = get_post_meta($post_id, "_price_value", true);
		if ($tmp_price < 12000) { $ribbon = 'ribbon-low-price';	}
	}
	$this_car = '<div>';
		$this_car .= '<div style="width:600px;height:300px;margin-left:5px;">';
			$this_car .= '<div style="cursor:pointer;float:left;">';
				$this_car .= '<img src="'. $car_demon_pluginpath .'images/'.$ribbon.'.png" width="112" height="112" alt="New Ribbon" id="ribbon">';
				$this_car .= '<img onclick="open_car_demon_lightbox(\''.$car_title.'_pic\');" src="'. $car_demon_pluginpath .'images/look_close.png" width="358" height="271" alt="New Ribbon" id="look_close">';
				$this_car .= '<div id="main_thumb"><img onclick="open_car_demon_lightbox(\''.$car_title.'_pic\');" onerror="ImgError(this, \'no_photo.gif\');" id="'.$car_title.'_pic" name="'.$car_title.'_pic" class="car_demon_main_photo" width="350px" src="';
				$main_guid = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
				$this_car .= $main_guid;
				$this_car .= '" /></div>';
			$this_car .= '</div>';
			$this_car .= '<div style="float:left;margin-left:15px;" >';
				$this_car .= $details;
			$this_car .= '</div>';
		$this_car .= '</div>';
		// Thumbnails
		$thumbnails = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' =>'image') );
		$this_car .= '<div class="nohor" id="car_demon_thumbs">';
		$cnt = 0;
		$car_js .= 'carImg['.$cnt.']="'.trim($main_guid).'";'.chr(13);
		$photo_array = '<img class="car_demon_thumbs" style="cursor:pointer" onClick=\'MM_swapImage("'.$car_title.'_pic","","'.trim($main_guid).'",1);active_img('.$cnt.')\' src="'.trim($main_guid).'" width="53" />';
		$this_car .= $photo_array;
		foreach($thumbnails as $thumbnail) {
			$guid = $thumbnail->guid;
			if (!empty($guid)) {
				if ($main_guid != $guid) {
					$cnt = $cnt + 1;
					$car_js .= 'carImg['.$cnt.']="'.trim($guid).'";'.chr(13);
					$photo_array = '<img class="car_demon_thumbs" style="cursor:pointer" onClick=\'MM_swapImage("'.$car_title.'_pic","","'.trim($guid).'",1);active_img('.$cnt.')\' src="'.trim($guid).'" width="53" />';
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
		$car .= '<div style="width:650px;height:225px;margin-top:10px;">';
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
					$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
					$title = substr($title, 0, 24);
					$mileage_value = get_post_meta($post_id, "_mileage_value", true);
					$detail_output = '<span class="random_title">'.$title.'</span><br />';
					$detail_output .= '<span class="random_text">';
						$detail_output .= 'Condition: '.$vehicle_condition.'<br />';			
					$detail_output .= '</span>';
					$detail_output .= '<span class="random_text">';
						$detail_output .= 'Mileage: '.$mileage_value.'<br />';
					$detail_output .= '</span>';
					$detail_output .= '<span class="random_text">';
						$detail_output .= 'Stock#: '.$stock_value;
					$detail_output .= '</span>';
					$link = get_permalink($post_id);
					$img_output = "<img onclick='window.location=\"".$link."\";' title='Click for price on this ".$title."' onerror='ImgError(this, \"no_photo.gif\");' class='random_widget_image' width='180px' height='135px' src='";
					$img_output .= wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
					$img_output .= "' />";
					$ribbon = 'ribbon-just-added';
					$ribbon = 'ribbon-great-deal';
					if ($vehicle_condition == 'New') {
						$ribbon = 'ribbon-new';
					}
					else {
						if ($mileage_value < 60000) { $ribbon = 'ribbon-low-miles';	}
						$tmp_price = get_post_meta($post_id, "_price_value", true);
						if ($tmp_price < 12000) { $ribbon = 'ribbon-low-price';	}
					}
					$car .= '
						<div class="random" style="float:left;width:210px;height:185px;">
							<div class="random_img">
								<img onclick="window.location=\''.$link.'\';" style="margin-left:15px;" src="'. $car_demon_pluginpath .'images/'.$ribbon.'.png" width="76" height="76" alt="New Ribbon" id="ribbon">
								<img onclick="window.location=\''.$link.'\';" style="margin-left:15px;" src="'. $car_demon_pluginpath .'images/look_close.png" width="188" height="143" alt="New Ribbon" id="look_close" class="look_close">
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