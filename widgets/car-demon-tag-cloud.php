<?php
add_action( 'widgets_init', 'car_demon_custom_tag_cloud_load_widgets' );
/**
 * Register our widget.
 * 'car_demon_custom_tag_cloud_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function car_demon_custom_tag_cloud_load_widgets() {
	register_widget( 'car_demon_custom_tag_cloud_Widget' );
}
/**
 * custom_term_cloud Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class car_demon_custom_tag_cloud_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function car_demon_custom_tag_cloud_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_custom_term_cloud', 'description' => __('A Custom Tag Cloud.', 'car-demon') );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_custom_term_cloud-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'car_demon_custom_term_cloud-widget', __('Car Demon Cloud Widget', 'car-demon'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$post_type = $instance['post_type'];
		$taxonomy = $instance['taxonomy'];
		$max_num = $instance['max_num'];
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		echo $before_title . $title . $after_title;
		get_my_cloud($post_type, $taxonomy, $max_num);
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
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		$instance['taxonomy'] = strip_tags( $new_instance['taxonomy'] );
		$instance['max_num'] = strip_tags( $new_instance['max_num'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Popular Models', 'car-demon'), 'post_type' => __('cars_for_sale', 'car-demon'), 'taxonomy' => __('vehicle_model', 'car-demon'), 'max_num' => __('45', 'car-demon') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="car_demon_wide" />
		</p>
		<!-- Your post_type: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e('Post Type:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" value="<?php echo $instance['post_type']; ?>" class="car_demon_wide" />
		</p>
		<!-- Your taxonomy: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e('Taxonomy:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>" value="<?php echo $instance['taxonomy']; ?>" class="car_demon_wide" />
		</p>
		<!-- Maximum Tags: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'max_num' ); ?>"><?php _e('max_num:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'max_num' ); ?>" name="<?php echo $this->get_field_name( 'max_num' ); ?>" value="<?php echo $instance['max_num']; ?>" class="car_demon_wide" />
		</p>
	<?php
	}
}
/**
BUILD THE CLOUD
*/
function get_my_cloud($post_type, $taxonomy, $max_num) {
	$my_tag_list = '';
	$args = array(
		'smallest'  => 8, 
		'largest'   => 22,
		'unit'      => 'pt', 
		'number'    => $max_num,  
		'format'    => 'array',
		'separator' => ' - ',
		'orderby'   => 'name', 
		'order'     => 'ASC',
		'exclude'   => '', 
		'include'   => '', 
		'link'      => 'view', 
		'taxonomy'  => $taxonomy,
		'echo'      => true );
	$my_tags = wp_tag_cloud( $args );
	if ($my_tags) {
		foreach($my_tags as $my_tag) {
			$my_tag2 = $my_tag;
			$my_tag = str_replace('title=','<title>',$my_tag);
			$my_tag = str_replace('style=','<style>',$my_tag);
			preg_match_all("~<title>([^<]*)<style>~",$my_tag,$bad_things);
			$bad_thing = $bad_things[1][0];
	
			$my_tag2 = str_replace('</a>','[2]',$my_tag2);
			$my_tag2 = str_replace('>','[1]',$my_tag2);
			$my_tag2 = str_replace('<','',$my_tag2);
			$my_tag2 = str_replace('[1]','<1>',$my_tag2);
			$my_tag2 = str_replace('[2]','<2>',$my_tag2);
			preg_match_all("~<1>([^<]*)<2>~",$my_tag2,$my_tag_names);
			$my_tag_name = $my_tag_names[1][0];
			$count_cars = car_demon_count_active_items($my_tag_name, $post_type, $taxonomy);
// car_demon_count_active_items is not returning a numerical value here, but it does work when called elsewhere...huh
			if (empty($count_cars)) {
				$count_cars = car_demon_count_active_items($my_tag_name.'-2', $post_type, $taxonomy);
			}
			$my_tag = str_replace($bad_thing,'',$my_tag);
			$my_tag = str_replace('<title>','',$my_tag);
			$my_tag = str_replace('<style>','style=',$my_tag);
			$my_tag = str_replace('<a ','<a title="We have '.$count_cars.' in stock now" ',$my_tag);
			if (!empty($count_cars)) {
				$my_tag_list .= $my_tag . ' - ';
			}
			$total_count = ' - '.$count_cars;
		}
	}
	echo $my_tag_list;
}
function vehicle_cloud($taxonomy, $max_num, $max_font, $min_font) {
	$post_type = 'cars_for_sale';
	$my_tag_list = '';
	$args = array(
		'smallest'  => $min_font,
		'largest'   => $max_font,
		'unit'      => 'pt', 
		'number'    => $max_num,  
		'format'    => 'array',
		'separator' => ' - ',
		'orderby'   => 'name', 
		'order'     => 'ASC',
		'exclude'   => '', 
		'include'   => '', 
		'link'      => 'view', 
		'taxonomy'  => $taxonomy,
		'echo'      => true );
	$my_tags = wp_tag_cloud( $args );
	if ($my_tags) {
		foreach($my_tags as $my_tag) {
			$my_tag2 = $my_tag;
			$my_tag = str_replace('title=','<title>',$my_tag);
			$my_tag = str_replace('style=','<style>',$my_tag);
			preg_match_all("~<title>([^<]*)<style>~",$my_tag,$bad_things);
			$bad_thing = $bad_things[1][0];
	
			$my_tag2 = str_replace('</a>','[2]',$my_tag2);
			$my_tag2 = str_replace('>','[1]',$my_tag2);
			$my_tag2 = str_replace('<','',$my_tag2);
			$my_tag2 = str_replace('[1]','<1>',$my_tag2);
			$my_tag2 = str_replace('[2]','<2>',$my_tag2);
			preg_match_all("~<1>([^<]*)<2>~",$my_tag2,$my_tag_names);
			$my_tag_name = $my_tag_names[1][0];
			$count_cars = car_demon_count_active_items($my_tag_name, $post_type, $taxonomy);
			if (empty($count_cars)) {
				$count_cars = car_demon_count_active_items($my_tag_name.'-2', $post_type, $taxonomy);
			}
			$my_tag = str_replace($bad_thing,'',$my_tag);
			$my_tag = str_replace('<title>','',$my_tag);
			$my_tag = str_replace('<style>','style=',$my_tag);
			$my_tag = str_replace('<a ','<a title="We have '.$count_cars.' in stock now" ',$my_tag);
			if (!empty($count_cars)) {
				$my_tag_list .= $my_tag . ' - ';
			}
			$total_count = ' - '.$count_cars;
		}
	}
	$my_tag_list = str_replace(' - ','<br />',$my_tag_list);
	return $my_tag_list;
}
function car_demon_count_active_items($my_tag_name, $post_type, $taxonomy) {
	global $wpdb;
	$total_cars = 0;
	$my_tag_id = get_term_by( 'slug', ''.$my_tag_name.'', ''.$taxonomy.'');
	if ($my_tag_id) {
		$my_tag_id = $my_tag_id->term_id;
	}
	if (!empty($my_tag_id)) {
		$my_search = " AND $wpdb->term_taxonomy.taxonomy = '".$taxonomy."'	AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id.")";
		$query = "SELECT COUNT(*) as num
			FROM $wpdb->posts wposts
				LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
				LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE wposts.post_type='".$post_type."'
				AND wpostmeta.meta_key = 'sold'
				AND wpostmeta.meta_value = 'no'".$my_search;
		$total_cars = $wpdb->get_var($query);
	}
	return $total_cars;
}
?>