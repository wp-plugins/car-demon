<?php
add_action('manage_cars_for_sale_posts_custom_column', 'manage_cars_for_sale_columns');
add_filter("manage_edit-cars_for_sale_columns", "my_cars_for_sale_columns");
add_action('quick_edit_custom_box',  'max_add_quick_edit', 10, 2);
function my_cars_for_sale_columns($columns) {
	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();

	$columns = array_merge(array(
		"sold" => __('Sold', 'car-demon')
	),$columns);
	if ($show_hide['price'] != true) {
		$columns = array_merge(array(
			"price" => __('Price', 'car-demon')
		),$columns);
	}
	if ($show_hide['discount'] != true) {
		$columns = array_merge(array(
			"discount" => __('Discount', 'car-demon')
		),$columns);
	}
	if ($show_hide['rebates'] != true) {
		$columns = array_merge(array(
			"rebate" => __('Rebate', 'car-demon')
		),$columns);
	}
	if ($show_hide['retail'] != true) {
		$columns = array_merge(array(
			"msrp" => __('Retail Price', 'car-demon')
		),$columns);
	}
	$columns = array_merge(array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __('Vehicle Title', 'car-demon'),
		"photo" => __('Photo', 'car-demon'),
	),$columns);

	return $columns;
}
function manage_cars_for_sale_columns($column) {
	global $post;
	$post_id = $post->ID;
	$post_title = $post->post_title;
	$car_demon_pluginpath = CAR_DEMON_PATH;
	$car_demon_pluginpath = str_replace('admin','',$car_demon_pluginpath);
	$car_pic = wp_get_attachment_thumb_url( get_my_post_thumbnail_id_detail_eil( $post_id ) );
	$car_pic = str_replace('-150x150', '', $car_pic);
	if (empty($car_pic)) {
		$car_demon_pluginpath = CAR_DEMON_PATH;
		$car_demon_pluginpath = str_replace('admin','',$car_demon_pluginpath);
		$car_pic = $car_demon_pluginpath.'images/no_photo.gif';
	}
	$attachments = get_children( array( 'post_parent' => $post_id ) );
	$count = count( $attachments );
	$car_image = '<img width="75" src="'.$car_pic.'" /><br />('.$count.')';

	//= Find out which of the default fields are hidden
	$show_hide = get_show_hide_fields();
	//= Get the labels for the default fields
	$field_labels = get_default_field_labels();

	if ("ID" == $column) {
		echo $post_id;
	} elseif ("photo" == $column) {
		echo $car_image;
	} elseif ("msrp" == $column) {
		if ($show_hide['retail'] != true) {
			echo '<input type="text" id="msrp_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_msrp_value\');" size="6" value="'.get_post_meta($post_id, '_msrp_value', true).'" />';
			$msrp_label = get_post_meta($post_id, '_msrp_label', true);
			if (empty($msrp_label)) {
				$msrp_label = $field_labels['retail'];
			}
			echo '<br /><br />edit label';
			echo '<br /><input type="text" id="msrp_label_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_msrp_label\');" size="6" value="'.$msrp_label.'" />';
		}
	} elseif ("rebate" == $column) {
		if ($show_hide['rebates'] != true) {
			echo  '<input type="text" id="rebate_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_rebates_value\');" size="6" value="'.get_post_meta($post_id, '_rebates_value', true).'" />';
			$rebate_label = get_post_meta($post_id, '_rebate_label', true);
			if (empty($rebate_label)) {
				$rebate_label = $field_labels['rebates'];
			}	
			echo '<br /><br />edit label';
			echo '<br /><input type="text" id="rebate_label_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_rebate_label\');" size="6" value="'.$rebate_label.'" />';
		}
	} elseif ("discount" == $column) {
		if ($show_hide['discount'] != true) {
			echo '<input type="text" id="discount_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_discount_value\');" size="6" value="'.get_post_meta($post_id, '_discount_value', true).'" />';
			$discount_label = get_post_meta($post_id, '_discount_label', true);
			if (empty($discount_label)) {
				$discount_label = $field_labels['discount'];
			}	
			echo '<br /><br />edit label';
			echo '<br /><input type="text" id="discount_label_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_discount_label\');" size="6" value="'.$discount_label.'" />';
		}
	} elseif ("price" == $column) {
		if ($show_hide['price'] != true) {
			$price_color = '';
			$msrp = get_post_meta($post_id, '_msrp_value', true);
			$rebates = get_post_meta($post_id, '_rebates_value', true);
			$discounts = get_post_meta($post_id, '_discount_value', true);
			if (empty($msrp)) {$msrp = 0;}
			if (empty($rebates)) {$rebates = 0;}
			if (empty($discounts)) {$discounts = 0;}
			$calculated_price = $msrp - $rebates - $discounts;
			$final_price = get_post_meta($post_id, '_price_value', true);
			$calculated_discount = $final_price - $msrp;
			echo __('Calculated Price:', 'car-demon').' <span id="calc_price_'.$post_id.'">'.$calculated_price.'</span><br />';
			echo __('Calculated Discount:', 'car-demon').' <span id="calc_discounts_'.$post_id.'">'.$calculated_discount.'</span><br />';
			echo '<span id="calc_error_'.$post_id.'"></span>';
			if ($calculated_price != $final_price) {
				if (!empty($msrp)) {
					$price_color = ' style="background-color:#FFB3B3;"';
				}
			}
			echo '<input'.$price_color.' id="price_'.$post_id.'" type="text" onchange="update_car('.$post_id.', this, \'_price_value\');" size="6" value="'.$final_price.'" />';
			$price_label = get_post_meta($post_id, '_price_label', true);
			if (empty($price_label)) {
				$price_label = $field_labels['price'];
			}	
			echo '<br /><br />edit label';
			echo '<br /><input type="text" id="price_label_'.$post_id.'" onchange="update_car('.$post_id.', this, \'_price_label\');" size="6" value="'.$price_label.'" />';
		}
	} elseif ("sold" == $column) {
		$sold = get_post_meta($post_id, 'sold', true);
		echo '<div id="show_sold_'.$post_id.'">
				<select id="is_sold_'.$post_id.'" onchange="update_car_sold('.$post_id.', this, \'sold\')">
					<option value="'.$sold.'">'.$sold.'</option>
					<option value="Yes">'.__('Yes', 'car-demon').'</option>
					<option value="No">'.__('No', 'car-demon').'</option>
				</select>
			</div>';
	}
}
function max_add_quick_edit($column_name, $post_type) {
	if ($column_name == 'msrp') {
		echo '<table><tr><td>';
		echo '<fieldset class="inline-edit-col-left">';
		echo '<div class="inline-edit-col">';
		echo '<span class="title">'.__('MSRP', 'car-demon').'</span>';
		echo '<input type="text" name="msrp_val" id="msrp_val" />';
		echo '</div>';
		echo '</fieldset><br />';
	}
	if ($column_name == 'rebate') {
		echo '<fieldset class="inline-edit-col-left">';
		echo '<div class="inline-edit-col">';
		echo '<span class="title">'.__('Rebates', 'car-demon').'</span>';
		echo '<input type="text" name="rebates_val" id="rebates_val" />';
		echo '</div>';
		echo '</fieldset><br />';
	}
	if ($column_name == 'discount') {
		echo '<fieldset class="inline-edit-col-left">';
		echo '<div class="inline-edit-col">';
		echo '<span class="title">'.__('Discount', 'car-demon').'</span>';
		echo '<input type="text" name="discount_val" id="discount_val" />';
		echo '</div>';
		echo '</fieldset><br />';
	}
	if ($column_name == 'price') {
		echo '<fieldset class="inline-edit-col-left">';
		echo '<div class="inline-edit-col">';
		echo '<span class="title">'.__('Price', 'car-demon').'</span>';
		echo '<input type="text" name="price_val" id="price_val" />';
		echo '</div>';
		echo '</fieldset><br />';
		echo '</td></tr></table>';
	}
	return;
}
function max_save_quick_edit_data($post_id) {
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;	
	// Check permissions
	if (isset($_POST['post_type'])) {
		$post_type = $_POST['post_type'];
	} else {
		$post_type = '';
	}
	if ( 'page' == $post_type ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	}	
	// OK, we're authenticated: we need to find and save the data
	$post = get_post($post_id);
	if (isset($_POST['msrp_val']) && ($post->post_type != 'revision')) {
		$msrp_val = esc_attr($_POST['msrp_val']);
		if ($msrp_val)
			update_post_meta( $post_id, '_msrp_value', $msrp_val);		
		else
			delete_post_meta( $post_id, '_msrp_value');		
	}
	if (isset($_POST['rebates_val']) && ($post->post_type != 'revision')) {
		$rebates_val = esc_attr($_POST['rebates_val']);
		if ($rebates_val)
			update_post_meta( $post_id, '_rebates_value', $rebates_val);
		else
			delete_post_meta( $post_id, '_rebates_value');		
	}
	if (isset($_POST['discount_val']) && ($post->post_type != 'revision')) {
		$discount_val = esc_attr($_POST['discount_val']);
		if ($discount_val)
			update_post_meta( $post_id, '_discount_value', $discount_val);		
		else
			delete_post_meta( $post_id, '_discount_value');		
	}
	if (isset($_POST['price_val']) && ($post->post_type != 'revision')) {
		$price_val = esc_attr($_POST['price_val']);
		if ($price_val)
			update_post_meta( $post_id, '_price_value', $price_val);		
		else
			delete_post_meta( $post_id, '_price_value');		
	}
	return $post_id;
}
function max_quick_edit_javascript() {
	global $current_screen;
	?>
	<script type="text/javascript">
	<!--
	function set_msrp(msrp_val,rebates,discount,price) {
		// revert Quick Edit menu so that it refreshes properly
		inlineEditPost.revert();
		document.getElementById('msrp_val').value = msrp_val;
		document.getElementById('rebates_val').value = rebates;
		document.getElementById('discount_val').value = discount;
		document.getElementById('price_val').value = price;
	}
	//-->
	</script>
	<?php
}
function max_expand_quick_edit_link($actions, $post) {
	global $current_screen;
	$nonce = wp_create_nonce( 'msrp'.$post->ID);
	$msrp = get_post_meta( $post->ID, '_msrp_value', TRUE);
	$rebates = get_post_meta( $post->ID, '_rebates_value', TRUE);
	$discount = get_post_meta( $post->ID, '_discount_value', TRUE);
	$price = get_post_meta( $post->ID, '_price_value', TRUE);
	$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';
	$actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline','car-demon' ) ) . '" ';
	$actions['inline hide-if-no-js'] .= " onclick=\"set_msrp('$msrp','$rebates','$discount','$price')\">"; 
	$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edits','car-demon' );
	$actions['inline hide-if-no-js'] .= '</a>';
	return $actions;	
}
?>