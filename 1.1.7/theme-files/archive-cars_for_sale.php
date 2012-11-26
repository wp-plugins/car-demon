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
				<?php 
				/*======= Car Demon Loop ======================================================= */
				while ( have_posts() ) : the_post();
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