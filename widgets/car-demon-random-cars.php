<?php
add_action( 'widgets_init', 'car_demon_random_car_load_widgets' );
function car_demon_random_car_load_widgets() {
	register_widget( 'car_demon_random_car_Widget' );
}
class car_demon_random_car_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function car_demon_random_car_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_random_car', 'description' => __('Display random cars.', 'car-demon') );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_random_car-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'car_demon_random_car-widget', __('Car Demon Random Cars', 'car-demon'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$cars = $instance['cars'];
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		echo car_demon_display_random_cars($cars);
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cars'] = strip_tags( $new_instance['cars'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __('Great Deals', 'car-demon'),
			'cars' => __('3', 'car-demon'),
			 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="car_demon_wide" />
			<label for="<?php echo $this->get_field_id( 'cars' ); ?>"><?php _e('# of Cars:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cars' ); ?>" name="<?php echo $this->get_field_name( 'cars' ); ?>" value="<?php echo $instance['cars']; ?>" class="car_demon_wide" />
		</p>
	<?php
	}
}
function count_all_cars() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$str_sql = "
		SELECT Count(wposts.ID) AS cnt
		FROM ".$prefix."posts wposts, ".$prefix."postmeta wpostmeta
		WHERE wposts.ID = wpostmeta.post_id
		AND post_type='cars_for_sale'
		AND post_status='publish'
		AND wpostmeta.meta_key = 'sold'
		AND wpostmeta.meta_value = 'no'";
	$the_count = $wpdb->get_results($str_sql);
	if ($the_count) {
		foreach ($the_count as $rec) {
			$count = $rec->cnt;
		}
	}
	return $count;
}
function car_demon_display_random_cars($stop) {
	$stop = $stop + 1;
	global $wpdb;
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('widgets','',$car_demon_pluginpath);
	$total_cars = count_all_cars();
	$total_cars = $total_cars - 7;
	$start_at = rand(1,$total_cars);
	if ($start_at < 0) $start_at = 0;
	$str_sql = "
		SELECT wposts.ID
		FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
		WHERE wposts.ID = wpostmeta.post_id
		AND post_type='cars_for_sale'
		AND post_status='publish'
		AND wpostmeta.meta_key = 'sold'
		AND wpostmeta.meta_value = 'no'
		ORDER BY ID LIMIT ".$start_at.", 7";
	$the_lists = $wpdb->get_results($str_sql);
	$car = '';
	$car_num = 0;
	$count_limit = 0;
	$stop_at = 0;
	foreach ($the_lists as $the_list) {
		$count_limit = $count_limit+1;
		$stop_at = $stop_at+1;
		if ($stop_at<$stop) {
			$post_id = $the_list->ID;
			$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
			$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
			$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
			$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
			$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
			$title = substr($title, 0, 24);
			$stock_value = get_post_meta($post_id, "_stock_value", true);
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
			$img_output .= wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) );
			$img_output .= "' />";
			$ribbon = get_post_meta($post_id, '_vehicle_ribbon', true);
			if (empty($ribbon)) {
				$ribbon = 'no-ribbon';		
			}
			if ($ribbon != 'custom_ribbon') {
				$ribbon = str_replace('_', '-', $ribbon);
				$current_ribbon = '<img class="similar_car_ribbon" src="'. $car_demon_pluginpath .'theme-files/images/ribbon-'.$ribbon.'.png" width="76" height="76" alt="New Ribbon" id="ribbon">';
			} else {
				$custom_ribbon_file = get_post_meta($post_id, '_custom_ribbon', true);
				$current_ribbon = '<img class="similar_car_ribbon" src="'.$custom_ribbon_file.'" width="76" height="76" alt="New Ribbon" id="ribbon">';
			}
			if (isset($_SESSION['car_demon_options']['dynamic_ribbons'])) {
				if ($_SESSION['car_demon_options']['dynamic_ribbons'] == 'Yes') {
					$current_ribbon = car_demon_dynamic_ribbon_filter($current_ribbon, $post_id, '76');
				}
			}
			$car .= '
				<div class="random">
					<div class="random_img">
						'.$current_ribbon.'
						<img onclick="window.location=\''.$link.'\';" class="random_car_lookup" src="'. $car_demon_pluginpath .'theme-files/images/look_close.png" width="188" height="143" alt="New Ribbon" id="look_close" class="look_close">
						'.$img_output.'
					</div>
					<div class="random_description">
						'.$detail_output.'
					</div>
				</div>';
		}
	}
	return $car;
}
?>