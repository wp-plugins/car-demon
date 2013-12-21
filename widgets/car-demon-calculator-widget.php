<?php
add_action( 'widgets_init', 'car_demon_calculator_load_widgets' );
function car_demon_calculator_load_widgets() {
	register_widget( 'car_demon_calculator_Widget' );
}
class car_demon_calculator_Widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function car_demon_calculator_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'car_demon_calculator', 'description' => __('Loan Calculator.', 'car-demon') );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'car_demon_calculator-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'car_demon_calculator-widget', __('Car Demon Loan Calculator', 'car-demon'), $widget_ops, $control_ops );
	}
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$price = $instance['price'];
		$apr = $instance['apr'];
		$term = $instance['term'];
		$disclaimer1 = $instance['disclaimer1'];
		$disclaimer2 = $instance['disclaimer2'];
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		if (is_singular('cars_for_sale')) {
			global $wp_query;
			$post_id = $wp_query->post->ID;
			$price = get_post_meta($post_id, "_price_value", true);
			car_demon_calculator_form($price, $apr, $term, $disclaimer1, $disclaimer2);		
		}
		else {
			car_demon_calculator_form($price, $apr, $term, $disclaimer1, $disclaimer2);
		}
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
		$instance['price'] = strip_tags( $new_instance['price'] );
		$instance['apr'] = strip_tags( $new_instance['apr'] );
		$instance['term'] = strip_tags( $new_instance['term'] );
		$instance['disclaimer1'] = strip_tags( $new_instance['disclaimer1'] );
		$instance['disclaimer2'] = strip_tags( $new_instance['disclaimer2'] );
		return $instance;
	}
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => __('Loan Calculator', 'car-demon'),
			'price' => __('25000', 'car-demon'),
			'apr' => __('10', 'car-demon'),
			'term' => __('60', 'car-demon'),
			'disclaimer1' => __('It is not an offer for credit nor a quote.', 'car-demon'),
			'disclaimer2' => __('This calculator provides an estimated monthly payment. Your actual payment may vary based upon your specific loan and final purchase price.', 'car-demon')
			 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="car_demon_wide" />
			<label for="<?php echo $this->get_field_id( 'price' ); ?>"><?php _e('Price:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'price' ); ?>" name="<?php echo $this->get_field_name( 'price' ); ?>" value="<?php echo $instance['price']; ?>" class="car_demon_wide" />
			<label for="<?php echo $this->get_field_id( 'apr' ); ?>"><?php _e('APR:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'apr' ); ?>" name="<?php echo $this->get_field_name( 'apr' ); ?>" value="<?php echo $instance['apr']; ?>" class="car_demon_wide" />
			<label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e('Term:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name( 'term' ); ?>" value="<?php echo $instance['term']; ?>" class="car_demon_wide" />
			<label for="<?php echo $this->get_field_id( 'disclaimer1' ); ?>"><?php _e('Disclaimer #1:', 'car-demon'); ?></label>
			<input id="<?php echo $this->get_field_id( 'disclaimer1' ); ?>" name="<?php echo $this->get_field_name( 'disclaimer1' ); ?>" value="<?php echo $instance['disclaimer1']; ?>" class="car_demon_wide" />
			<label for="<?php echo $this->get_field_id( 'disclaimer2' ); ?>"><?php _e('Disclaimer #2:', 'car-demon'); ?></label>
			<br /><textarea class="calc_disclaimer" id="<?php echo $this->get_field_id( 'disclaimer2' ); ?>" name="<?php echo $this->get_field_name( 'disclaimer2' ); ?>"><?php echo $instance['disclaimer2']; ?></textarea>
		</p>
	<?php
	}
}
?>