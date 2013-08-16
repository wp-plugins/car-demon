<?php
function car_demon_register_sidebars() {
	register_sidebar(array(
		'name'=> __('Vehicle Detail Sidebar', 'car-demon'),
		'id' => 'car_page',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
}
function car_demon_register_mobile_sidebars() {
	register_sidebar(array(
		'name'=> __('Mobile Header', 'car-demon'),
		'id' => 'mobile_header',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> __('Mobile Front Page', 'car-demon'),
		'id' => 'mobile_front_page',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));	
	register_sidebar(array(
		'name'=> __('Mobile Footer', 'car-demon'),
		'id' => 'mobile_footer',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));	
	register_sidebar(array(
		'name'=> __('Mobile Car Page', 'car-demon'),
		'id' => 'mobile_car_page',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
}
?>