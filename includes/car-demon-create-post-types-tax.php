<?php
if (!function_exists('car_demon_recreate_post_type')) {
	add_action( 'init', 'car_demon_create_post_type' );
}
add_action( 'init', 'car_demon_mytax_init' );
function car_demon_create_post_type() {
  $slug = get_option('car-demon-slug');
	if (empty($slug)) {
		$slug = 'cars-for-sale';
		update_option('car-demon-slug', $slug);
	}
  register_post_type( 'cars_for_sale',
    array(
      'labels' => array(
        'name' => __( 'Cars For Sale','car-demon' ),
        'singular_name' => __( 'Car New Car','car-demon' ),
      ),
      'public' => true,
      'rewrite' => array('slug' => $slug),
	  'has_archive' => true,
	  'supports' => array('title','editor','thumbnail','comments','excerpt'),
	  'menu_icon' => plugins_url( '../images/cd_icon.png', __FILE__ )
    )
  );
}
function car_demon_mytax_init() {
  register_taxonomy(
    'vehicle_year',
    'cars_for_sale',
    array(
      'label' => __('Year', 'car-demon'),
      'sort' => true,
      'args' => array('orderby' => 'term_order'),
      'rewrite' => array('slug' => 'vehicle_year'),
	  'show_ui' => false
    )
  );
  register_taxonomy(
    'vehicle_make',
    'cars_for_sale',
    array(
      'label' => __('Make', 'car-demon'),
      'sort' => true,
      'args' => array('orderby' => 'term_order'),
      'rewrite' => array('slug' => 'make'),
	  'show_ui' => false
    )
  );
  register_taxonomy(
    'vehicle_model',
    'cars_for_sale',
    array(
      'label' => __('Model', 'car-demon'),
      'sort' => true,
      'args' => array('orderby' => 'term_order'),
      'rewrite' => array('slug' => 'model'),
	  'show_ui' => false
    )
  );
  register_taxonomy(
    'vehicle_condition',
    'cars_for_sale',
    array(
      'label' => __('Condition', 'car-demon'),
      'sort' => true,
      'args' => array('orderby' => 'term_order'),
      'rewrite' => array('slug' => 'condition')
    )
  );
  register_taxonomy(
    'vehicle_body_style',
    'cars_for_sale',
    array(
      'label' => __('Body Style', 'car-demon'),
      'sort' => true,
      'args' => array('orderby' => 'term_order'),
      'rewrite' => array('slug' => 'body_style'),
	  'show_ui' => false
    )
  );
  register_taxonomy(
    'vehicle_location',
    'cars_for_sale',
    array(
      'label' => __('Location', 'car-demon'),
      'sort' => true,
      'args' => array('orderby' => 'term_order'),
      'rewrite' => array('slug' => 'location'),
  	  'show_ui' => true
    )
  );
}
?>