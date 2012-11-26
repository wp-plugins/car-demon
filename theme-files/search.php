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
?>
		<div id="demon-container">
			<div id="demon-content" role="main">
<?php if ( $search_query->have_posts() ) :
		echo car_demon_sorting('search');
		if (isset($_GET['car'])) {
		?>
				<h1 class="page-title"><?php echo __( 'Search Results:', 'car-demon' ); ?></h1>
				<h4 class="results_found"><?php _e('Results Found','car-demon'); echo ': '.$total_results;?></h4>
				<?php echo $searched; ?>
				<?php if ( $search_query->max_num_pages > 1 ) : ?>
						<div id="cd-nav-above" class="navigation-top inventory_nav_top">
						<?php if(function_exists('wp_pagenavi')) {  
									$nav_list_str = wp_pagenavi(array( 'query' => $search_query, 'echo' => false )); 
									$nav_list_str = str_replace('nextpostslink','nextpostslink-top',$nav_list_str);
									echo $nav_list_str;
								}
								else { ?>
								<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'car-demon' ) ); ?></div>
								<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'car-demon' ) ); ?></div>
								<?php } ?>
						</div><!-- #nav-above -->
					<?php else: ?>
						<div id="cd-nav-above" class="navigation-top inventory_nav"><span class="wp-pagenavi"><span class="pages"><?php echo $wp_query->post_count; ?> <?php _e('Results Found', 'car-demon'); ?></span></span>
						</div>
				<?php endif; ?>
				<div id="demon-content" class="listing" role="main">
					<?php
					/*======= Car Demon Loop ======================================================= */								 
					while ( $search_query->have_posts() ) : $search_query->the_post();
						$post_id = $search_query->post->ID;
						echo car_demon_display_car_list($post_id);
					endwhile;
					/* Display navigation to next/previous pages when applicable */ ?>
				</div>
				<?php if (  $search_query->max_num_pages > 1 ) : ?>
						<div id="cd-nav-below" class="navigation inventory_nav_bottom">
						<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(array( 'query' => $search_query )); } 
								else { ?>
									<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'car-demon' ) ); ?></div>
									<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'car-demon' ) ); ?></div>
								<?php } ?>
						</div><!-- #nav-below -->
				<?php endif;
			}
			else {
				 get_template_part( 'loop', 'search' );
			}
			else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'car-demon' ); ?></h2>
					<div class="entry-content">
						<p class="sorry"><?php _e( 'Sorry, but nothing matched your search criteria. Please try using a broader search selection.', 'car-demon' ); ?></p>
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
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
			<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>