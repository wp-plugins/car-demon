<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage CarDemon 
 * @since CarDemon 1.0
 */

if ($_GET['car']) {
	add_filter( 'wp_title', 'car_demon_filter_search_title', 10, 3 );
	$order_by = '_price_value';
	$order_by_dir = 'ASC';
	if (isset($_GET['order_by'])) {
		$order_by = $_GET['order_by'];
	}
	if (isset($_GET['order_by_dir'])) {
		$order_by_dir = $_GET['order_by_dir'];
	}	
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$min_price = $_GET['search_dropdown_Min_price'];
	$max_price = $_GET['search_dropdown_Max_price'];
	$meta_query = array(
			array(
				'key' => 'sold',
				'value' => 'no',
				'compare' => '='
			)
		);
	if ($_GET['stock']) {
		$meta_query = array_merge($meta_query, array(array('key' => '_stock_value','value' => $_GET['stock'], 'compare' => '=', 'type' => 'text')));
	}
	if ($_GET['search_dropdown_miles']) {
		$meta_query = array_merge($meta_query, array(array('key' => '_mileage_value','value' => $_GET['search_dropdown_miles'], 'compare' => '<', 'type' => 'numeric')));
	}
	if ($_GET['search_dropdown_tran']) {
		$meta_query = array_merge($meta_query, array(array('key' => '_transmission_value','value' => $_GET['search_dropdown_tran'], 'compare' => '=', 'type' => 'text')));
	}
	if ($max_price > 0) {
		if ($min_price == 0) { $min_price = 1; }
		$meta_query = array_merge($meta_query, array(array('key' => '_price_value','value' => array( $min_price, $max_price ), 'compare' => 'BETWEEN', 'type' => 'numeric')));
	}
	else {
		if ($min_price > 0) {
			$meta_query = array_merge($meta_query, array(array('key' => '_price_value','value' => $min_price, 'compare' => '>', 'type' => 'numeric')));
		}
	}
	$my_query = array(
			'post_type' => 'cars_for_sale',
			'is_paged' => true,
			'paged' => $paged,
			'posts_per_page' => 9,
			'meta_query' => $meta_query,
			'orderby' => 'meta_value_num',
			'meta_key' => $order_by,
			'order'    => $order_by_dir
		);
		if ($_GET['search_year']) {
			$my_query = array_merge ($my_query, array('vehicle_year' => $_GET['search_year']));
		}
		if ($_GET['search_condition']) {
			$my_query = array_merge ($my_query, array('vehicle_condition' => $_GET['search_condition']));
		}
		if ($_GET['search_make']) {
			$my_query = array_merge ($my_query, array('vehicle_make' => $_GET['search_make']));
		}
		if (isset($_GET['search_model'])) {
			$my_query = array_merge ($my_query, array('vehicle_model' => $_GET['search_model']));
		}
		if ($_GET['search_dropdown_body']) {
			$my_query = array_merge ($my_query, array('vehicle_body_style' => $_GET['search_dropdown_body']));
		}
//	query_posts( $my_query );
	$search_query = new WP_Query();
    $search_query->query($my_query);
	$total_results = $search_query->found_posts;
}
$searched = car_demon_get_searched_by();
header('HTTP/1.1 200 OK');
get_header();
echo car_demon_dynamic_load();
?>
		<div id="demon-container">
			<div id="demon-content" role="main">
<?php if ( $search_query->have_posts() ) :
$wpurl = site_url();
$query_string = $_SERVER['QUERY_STRING'];
$query_string = str_replace('&order_by_dir=desc', '', $query_string);
$query_string = str_replace('&order_by_dir=asc', '', $query_string);
$query_string = str_replace('&order_by=_price_value', '', $query_string);
$query_string = str_replace('&order_by=_mileage_value', '', $query_string);
$wpurl_img = $wpurl.'/wp-content/plugins/car-demon/theme-files/images/';
$wpurl = $wpurl .'?'. $query_string;
echo 'Sort By:';
$sort_asc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="Sort Low to High" /></a>&nbsp;';
$sort_desc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="Sort High to Low" /></a>';
	echo '&nbsp;&nbsp;&nbsp;Price '.$sort_asc_img.$sort_desc_img;
$sort_asc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="Sort Low to High" /></a>&nbsp;';
$sort_desc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="Sort High to Low" /></a>';
	echo '&nbsp;&nbsp;&nbsp;Mileage '.$sort_asc_img.$sort_desc_img;

if (isset($_GET['car'])) {
?>
<style>
.wp-pagenavi .pages {
	display:none;
}
</style>
				<h1 class="page-title"><?php printf( __( 'Search Results: %s', 'car-demon' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<h4 style="margin:0px;"><?php _e('Results Found','car-demon'); echo ': '.$total_results;?></h4>
				<?php echo $searched; ?>
				<?php if ( $search_query->max_num_pages > 1 ) : ?>
						<div id="cd-nav-above" class="navigation-top" style="float:left;width:100%;margin:7px;">
						<?php if(function_exists('wp_pagenavi')) {  
									$nav_list_str = wp_pagenavi(array( 'query' => $search_query, 'echo' => false )); 
									$nav_list_str = str_replace('nextpostslink','nextpostslink-top',$nav_list_str);
									echo $nav_list_str;
								}
								else { ?>
								<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'CarDemon' ) ); ?></div>
								<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'CarDemon' ) ); ?></div>
								<?php } ?>
						</div><!-- #nav-above -->
					<?php else: ?>
						<div id="cd-nav-above" class="navigation-top" style="float:left;width:100%"><span class="wp-pagenavi"><span class="pages"><?php echo $wp_query->post_count; ?> Results Found</span></span>
						</div>
				<?php endif; ?>
<div id="demon-content" class="listing" role="main">
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 
	while ( $search_query->have_posts() ) : $search_query->the_post();
		$post_id = $search_query->post->ID;
		echo car_demon_display_car_list($post_id);
	endwhile;
	/* Display navigation to next/previous pages when applicable */ ?>
</div>
	<?php if (  $search_query->max_num_pages > 1 ) : ?>
			<div id="cd-nav-below" class="navigation" style="float:left;">
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(array( 'query' => $search_query )); } 
					else { ?>
						<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'CarDemon' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'CarDemon' ) ); ?></div>
					<?php } ?>
			</div><!-- #nav-below -->
	<?php endif;
}
else {
				 get_template_part( 'loop', 'search' );
}
else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'CarDemon' ); ?></h2>
					<div class="entry-content">
						<p style="color:#FF0000;font-size:16px;font-weight:bold;"><?php _e( 'Sorry, but nothing matched your search criteria. Please try using a broader search selection.', 'CarDemon' ); ?></p>
						<?php echo $searched; ?>
						<table>
							<tr>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
							</tr>
							<tr>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
							</tr>
							<tr>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
								<td width="200">
									<?php echo car_demon_display_random_cars(1); ?>
								</td>
							</tr>
						</table>
						<?php //get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); 

function car_demon_filter_search_title($title) {
	$title = str_replace('Page not found','Search Results', $title);
	return $title;
}
?>