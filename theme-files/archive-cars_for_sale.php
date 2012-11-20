<?php
get_header(); 
global $query_string;
$order_by = '_price_value';
$order_by_dir = 'ASC';
if (isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
}
else {
	$order_by = '';
}
if (isset($_GET['order_by_dir'])) {
	$order_by_dir = $_GET['order_by_dir'];
}
else {
	$order_by_dir = '';
}
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
if (isset($_GET['search_dropdown_Min_price'])) {
	$min_price = $_GET['search_dropdown_Min_price'];
}
else {
	$min_price = '';
}
if (isset($_GET['search_dropdown_Max_price'])) {
	$max_price = $_GET['search_dropdown_Max_price'];
}
else {
	$max_price = '';
}
$meta_query = array(
		array(
			'key' => 'sold',
			'value' => 'no',
			'compare' => '='
		)
	);
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
$my_query = wp_parse_args( $query_string, $my_query );
query_posts($my_query);
$total_results = $wp_query->found_posts;

echo car_demon_dynamic_load();
?>
		<div id="demon-container">
			<div id="demon-content" class="listing" role="main">
				<?php
					if ( have_posts() )
$wpurl = site_url();
$query_string = $_SERVER['QUERY_STRING'];
$query_string = str_replace('&order_by_dir=desc', '', $query_string);
$query_string = str_replace('&order_by_dir=asc', '', $query_string);
$query_string = str_replace('&order_by=_price_value', '', $query_string);
$query_string = str_replace('&order_by=_mileage_value', '', $query_string);
$wpurl_img = $wpurl.'/wp-content/plugins/car-demon/theme-files/images/';
$wpurl = '?'. $query_string;
echo __('Sort By:', 'car-demon');
$sort_asc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="'_('Sort Low to High', 'car-demon'.')" /></a>&nbsp;';
$sort_desc_img = '<a href="'.$wpurl.'&order_by=_price_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="'.__('Sort High to Low', 'car-demon').'" /></a>';
	echo '&nbsp;&nbsp;&nbsp;'.__('Price', 'car-demon').' '.$sort_asc_img.$sort_desc_img;
$sort_asc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=asc"><img src="'.$wpurl_img.'sort_asc.png" title="'.__('Sort Low to High', 'car-demon').'" /></a>&nbsp;';
$sort_desc_img = '<a href="'.$wpurl.'&order_by=_mileage_value&order_by_dir=desc"><img src="'.$wpurl_img.'sort_desc.png" title="'.__('Sort High to Low', 'car-demon').'" /></a>';
	echo '&nbsp;&nbsp;&nbsp;'.__('Mileage', 'car-demon').' '.$sort_asc_img.$sort_desc_img;
						the_post();
				?>
					<h1 class="page-title">
						<?php _e( 'Search Results:', 'car-demon' ); ?>
					</h1>
					<h4 class="results_found"><?php _e('Results Found','car-demon'); echo ': '.$total_results;?></h4>
				<?php
					rewind_posts();
				//	get_template_part( 'loop', 'archive' );
				?>
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
						<div id="cd-nav-above" class="navigation-top  inventory_nav_top">
						<?php if(function_exists('wp_pagenavi')) {  
									$nav_list_str = wp_pagenavi(array( 'echo' => false )); 
									$nav_list_str = str_replace('nextpostslink','nextpostslink-top',$nav_list_str);
									echo $nav_list_str;
								}
								else { ?>
								<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'car-demon' ) ); ?></div>
								<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'car-demon' ) ); ?></div>
								<?php } ?>
						</div><!-- #nav-above -->
					<?php else: ?>
						<div id="nav-above" class="navigation-top inventory_nav" ><span class="wp-pagenavi"><span class="pages"><?php echo $wp_query->post_count; ?> Results Found</span></span>
						</div>
				<?php endif; ?>
<?php while ( have_posts() ) : the_post();
	$post_id = $post->ID;
	echo car_demon_display_car_list($post_id);
endwhile; // End the loop. Whew. ?>
				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if (  $wp_query->max_num_pages > 1 ) : ?>
						<div id="cd-nav-below" class="navigation inventory_nav_bottom">
						<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } 
								else { ?>
									<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'car-demon' ) ); ?></div>
									<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'car-demon' ) ); ?></div>
								<?php } ?>
						</div><!-- #nav-below -->
				<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->
<?php get_sidebar(); ?>

<?php get_footer();?>