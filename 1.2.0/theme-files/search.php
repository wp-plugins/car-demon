<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage CarDemon 
 * @since CarDemon 1.0
 */
$car_demon_query = car_demon_query_search();
$search_query = new WP_Query();
$search_query->query($car_demon_query);
$total_results = $search_query->found_posts;
$searched = car_demon_get_searched_by();
header('HTTP/1.1 200 OK');
get_header();
echo car_demon_dynamic_load();
do_action( 'car_demon_before_main_content' );
if ( $search_query->have_posts() ) {
	echo $_SESSION['car_demon_options']['before_listings'];
	echo car_demon_sorting('search');
	if (isset($_GET['car'])) { ?>
		<h1 class="page-title"><?php echo __( 'Search Results:', 'car-demon' ); ?></h1>
		<h4 class="results_found"><?php _e('Results Found','car-demon'); echo ': '.$total_results;?></h4>
		<?php echo $searched; ?>
		<?php echo car_demon_nav('top', $search_query); ?>
		<div id="demon-content" class="listing" role="main">
			<?php
			/*======= Car Demon Loop ======================================================= */								 
			while ( $search_query->have_posts() ) : $search_query->the_post();
				$post_id = $search_query->post->ID;
				echo car_demon_display_car_list($post_id);
			endwhile;
			/* Display navigation to next/previous pages when applicable */ ?>
		</div>
		<?php echo car_demon_nav('bottom', $search_query);
	} else {
		 get_template_part( 'loop', 'search' );
	}
} else {
	echo car_demon_no_search_results($searched);
}
do_action( 'car_demon_after_main_content' );
do_action( 'car_demon_sidebar' );
get_footer();
?>