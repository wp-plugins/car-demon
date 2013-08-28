<?php
header("Content-Type: text/xml");
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/feeds', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\feeds', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
query_posts('posts_per_page=300&post_type=cars_for_sale&meta-key=sold&meta-value=no');
echo "<inventorySummary>";
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$post_id = $post->ID;
		echo "<vehicle>";
			echo "<id>".$post_id."</id>";
			$vehicle_year = strip_tags(get_the_term_list( $post_id, 'vehicle_year', '','', '', '' ));
			$vehicle_make = strip_tags(get_the_term_list( $post_id, 'vehicle_make', '','', '', '' ));
			$vehicle_model = strip_tags(get_the_term_list( $post_id, 'vehicle_model', '','', '', '' ));
			$title = $vehicle_year . ' ' . $vehicle_make . ' '. $vehicle_model;
			echo "<title>".$title."</title>";
			$vehicle_location = strip_tags(get_the_term_list( $post_id, 'vehicle_location', '','', '', '' ));
			echo "<location>".$vehicle_location."</location>";
			echo "<make>".$vehicle_make."</make>";
			echo "<model>".$vehicle_model."</model>";
			echo "<year>".$vehicle_year."</year>";
			$vehicle_condition = strip_tags(get_the_term_list( $post_id, 'vehicle_condition', '','', '', '' ));
			echo "<condition>".$vehicle_condition."</condition>";
			$vehicle_exterior_color = get_post_meta($post_id, "_exterior_color_value", true);
			echo "<exterior_color>".$vehicle_exterior_color."</exterior_color>";
			$vehicle_interior_color = get_post_meta($post_id, "_interior_color_value", true);
			echo "<interior_color>".$vehicle_interior_color."</interior_color>";
			$vehicle_mileage = get_post_meta($post_id, "_mileage_value", true);
			echo "<mileage>".$vehicle_mileage."</mileage>";
			$vehicle_stock_number = get_post_meta($post_id, "_stock_value", true);
			echo "<stock>".$vehicle_stock_number."</stock>";
			$vehicle_vin = get_post_meta($post_id, "_vin_value", true);
			echo "<vin>".$vehicle_vin."</vin>";
			$vehicle_price = get_post_meta($post_id, "_price_value", true);
			echo "<price>".$vehicle_price."</price>";
			echo "<dealer>Max Motors</dealer>";
			echo "<url>". get_permalink($post_id) ."</url>";
			echo "<photo>".wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ) )."</photo>";
		echo "</vehicle>";
	endwhile; else:
	endif;
echo "</inventorySummary>";
wp_reset_query();
?>