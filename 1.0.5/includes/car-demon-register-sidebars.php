<?php
function car_demon_register_sidebars() {
	register_sidebar(array(
		'name'=> 'Vehicle Detail Sidebar',
		'id' => 'car_page',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
}

function car_demon_register_mobile_sidebars() {
	register_sidebar(array(
		'name'=> 'Mobile Header',
		'id' => 'mobile_header',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Mobile Front Page',
		'id' => 'mobile_front_page',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));	
	register_sidebar(array(
		'name'=> 'Mobile Footer',
		'id' => 'mobile_footer',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));	
	register_sidebar(array(
		'name'=> 'Mobile Car Page',
		'id' => 'mobile_car_page',
		'before_widget' => '<li id="%1$s" class="car-demon-widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="offscreen">',
		'after_title' => '</h2>',
	));
}
?>