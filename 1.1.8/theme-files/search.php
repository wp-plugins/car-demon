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
		echo $_SESSION['car_demon_options']['before_listings'];
		echo car_demon_sorting('search');
		if (isset($_GET['car'])) {
		?>
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