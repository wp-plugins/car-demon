<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage CarDemon 
 * @since CarDemon 1.0
 */
get_header(); ?>
	<div id="demon-container">
		<div id="demon-content" role="main">
			<div id="post-0" class="post error404 not-found">
				<h1 class="entry-title"><?php _e( 'Not Found', 'car-demon' ); ?></h1>
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
		</div><!-- #content -->
	</div><!-- #container -->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>
<?php get_footer(); ?>
