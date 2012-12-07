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
do_action( 'car_demon_before_main_content' );
	if ( have_posts() ) while ( have_posts() ) : the_post(); 
		$post_id = get_the_ID();
		$vehicle_vin = rwh(strip_tags(get_post_meta($post_id, "_vin_value", true)),0);
		$car_title = get_car_title_slug($post_id);
		$car_head_title = get_car_title($post_id);
		$car_url = get_permalink($post_id);
		$vehicle_location = rwh(strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' )),0);
		$vehicle_details = get_post_meta($post_id, 'decode_string', true);
		//=========================Contact Info===========================
		$car_contact = get_car_contact($post_id);
		$contact_trade_url = $car_contact['trade_url'];
		$contact_finance_url = $car_contact['finance_url'];
		//===============================================================
		$detail_output = '<div class="car_title_div"><h3 class="car_title">'.$car_head_title.'</h3>';
		$detail_output .= '<ul>';
			$detail_output .= '<li><strong>Condition:</strong> '.$vehicle_details['condition'].'</li>';
			$detail_output .= '<li><strong>Mileage:</strong> '.$vehicle_details['mileage'].'</li>';
			$detail_output .= '<li><strong>Stock#:</strong> '.$vehicle_details['stock_num'].'</li>';
			$detail_output .= '<li><strong>VIN#:</strong> '.$vehicle_vin.'</li>';
			$detail_output .= '<li><strong>Color:</strong> '.$vehicle_details['exterior_color'].'/'.$vehicle_details['interior_color'].'</li>';
			$detail_output .= '<li><strong>Transmission:</strong> '.$vehicle_details['decoded_transmission_long'].'</li>';
			$detail_output .= '<li><strong>Engine:</strong> '.$vehicle_details['decoded_engine_type'].'</li>';
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
				<?php echo car_demon_display_similar_cars($vehicle_details['decoded_body_style'], $post_id); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'car-demon' ), 'after' => '</div>' ) ); ?>
			</div><!-- .car-demon-entry-content -->
			<?php echo car_demon_vehicle_detail_tabs($post_id); ?>
		</div><!-- #post-## -->
		<?php endwhile; // end of the loop. ?>
<?php
do_action( 'car_demon_after_main_content' );
do_action( 'car_demon_vehicle_sidebar' );
get_footer(); ?>