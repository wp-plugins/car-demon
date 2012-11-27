<?php
get_header(); 
$car_demon_query = car_demon_query_archive();
query_posts($car_demon_query);
$total_results = $wp_query->found_posts;

echo car_demon_dynamic_load();
?>
		<div id="demon-container">
			<div id="demon-content" class="listing" role="main">
				<?php
					echo $_SESSION['car_demon_options']['before_listings'];
					if ( have_posts() )
						echo car_demon_sorting('achive');
						the_post();
				?>
					<h1 class="page-title">
						<?php _e( 'Search Results:', 'car-demon' ); ?>
					</h1>
					<h4 class="results_found"><?php _e('Results Found','car-demon'); echo ': '.$total_results;?></h4>
				<?php
					rewind_posts();
				?>
				<?php 
				echo car_demon_nav('top', $wp_query);
				/*======= Car Demon Loop ======================================================= */
				while ( have_posts() ) : the_post();
					$post_id = $post->ID;
					echo car_demon_display_car_list($post_id);
				endwhile; // End the loop. Whew. ?>
				<?php
				echo car_demon_nav('bottom', $wp_query);				
				?>
			</div><!-- #content -->
		</div><!-- #container -->
<?php get_sidebar(); ?>

<?php get_footer();?>