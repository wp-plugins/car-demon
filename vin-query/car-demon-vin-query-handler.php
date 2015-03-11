<?php
function car_demon_vinquery() {
	if (isset($_POST['post_id'])) $post_id = $_POST['post_id'];
	if (isset($_POST['option'])) {
		if ($_POST['option'] == "post") {
			$vin = $_POST['vin'];
			car_demon_get_vin_query($post_id, $vin);
		}
		if ($_POST['option'] == 'add_car_images') {
			$attachment_id = $_POST['attachment_id'];
			$post_id = $_POST['post_id'];
			$cd_post = array();
			$cd_post['ID'] = $attachment_id;
			$cd_post['post_parent'] = $post_id;
			wp_update_post( $cd_post );
			$guid = wp_get_attachment_url($attachment_id);
			$cnt = (rand(10,1000));
			$photo_array = '<div id="car_photo_'.$cnt.'" name="car_photo_'.$cnt.'" class="car_photo_admin_box">';
				$photo_array .= '<div class="car_photo_remove" onclick="remove_attached_car_image('.$post_id.', \''.$attachment_id.'\', '.$cnt.')">';
					$photo_array .= 'X';
				$photo_array .= '</div>';
				$photo_array .= '<div align="center">';
					$photo_array .= '<img class="car_demon_thumbs" style="cursor:pointer" src="'.trim($guid).'" width="162" />';
				$photo_array .= '</div>';
			$photo_array .= '</div>';
			echo $photo_array;
		}
		if ($_POST['option'] == 'remove_car_linked_image') {
			$post_id = $_POST['post_id'];
			$image_list = get_post_meta($post_id, '_images_value', true);
			$image_list = str_replace($_POST['car_link'].',','',$image_list);
			$image_list = str_replace($_POST['car_link'],'',$image_list);
			update_post_meta($post_id, '_images_value', $image_list);
			echo $image_list;
			echo 'Image Removed';
		}
		if ($_POST['option'] == 'remove_car_attached_image') {
			$post_id = $_POST['post_id'];
			wp_delete_attachment($_POST['attachment_id']);
			echo 'Image Removed';
		}
		if ($_POST['option'] == "dashboard") {
			$vin = $_POST['vin'];
			$title = $_POST['title'];
			$stock = $_POST['stock'];
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			$post = array(
				'post_title' => $title,
				'post_status' => 'draft',
				'post_type' => 'cars_for_sale',
				'post_author' => $user_id,
				);
			$user_location = esc_attr( get_the_author_meta( 'user_location', $user_id ) );
			$post_id = wp_insert_post($post);
			$car_demon_options = car_demon_options();
			if (!empty($car_demon_options['vinquery_id'])) {
				car_demon_get_vin_query($post_id, $vin);
			}
			update_post_meta($post_id, '_vin_value', $vin);
			if ($user_location) {
				wp_set_post_terms($post_id, $user_location, 'vehicle_location', false );
			}
			update_post_meta($post_id, '_stock_value', $stock);
			update_post_meta($post_id, 'sold', 'no');
			$blog_url = site_url();
			$url = $blog_url . '/wp-admin/post.php?post='.$post_id.'&action=edit';
			echo $url;
		}
		if ($_POST['option'] == "decode_string") {
			$details = get_post_meta($post_id, 'decode_string', true);
			update_post_meta($post_id, 'decode_string', $details);
		}
		if ($_POST['option'] == "update") {
			$fld = $_POST['fld'];
			$post_id = $_POST['post_id'];
			if ($fld == 'vin') {
				$fld = '_vin_value';
			}
			$val = $_POST['val'];
			if	($fld == 'vehicle_options') {
				$fld = '_vehicle_options';
			}
			update_post_meta($post_id, $fld, $val);
		}
		if ($_POST['option'] == 'update_data') {
			$fld = $_POST['fld'];
			$val = $_POST['val'];
			$post_id = $_POST['post_id'];
			if ($fld == 'vin') {
				$fld = '_vin_value';
			}
			$val = $_POST['val'];
			if	($fld == 'vehicle_options') {
				$fld = '_vehicle_options';
			}
			$vin_query_decode = get_post_meta($post_id, "decode_string", true);
			$vin_query_decode[$fld] = $_POST['val'];
			if ($fld == 'decoded_body_style') {
				wp_set_post_terms( $post_id, $val, 'vehicle_body_style', false );
			} elseif ($fld == 'decoded_model_year') {
				wp_set_post_terms( $post_id, $val, 'vehicle_year', false );
			} elseif ($fld == 'decoded_make') {
				wp_set_post_terms( $post_id, $val, 'vehicle_make', false );
			} elseif ($fld == 'decoded_model') {
				wp_set_post_terms( $post_id, $val, 'vehicle_model', false );
			} elseif ($fld == 'decoded_transmission_long') {
				update_post_meta($post_id, '_transmission_value', $_POST['val']);
			} elseif ($fld == 'decoded_transmission') {
				$vin_query_decode['decoded_transmission_long'] = $_POST['val'];
				update_post_meta($post_id, '_transmission_value', $_POST['val']);
			} elseif ($fld == 'decoded_engine_type') {
				update_post_meta($post_id, '_engine_value', $_POST['val']);	
			} elseif ($fld == 'condition') {
				wp_set_post_terms( $post_id, $val, 'vehicle_condition', false );
			} elseif ($fld == 'decoded_trim_level') {
				update_post_meta($post_id, '_trim_value', $_POST['val']);
			} elseif ($fld == 'stock_num') {
				update_post_meta($post_id, '_stock_value', $_POST['val']);
			} elseif ($fld == 'msrp') {
				update_post_meta($post_id, '_msrp_value', $_POST['val']);
			} elseif ($fld == 'rebates') {
				update_post_meta($post_id, '_rebates_value', $_POST['val']);
			} elseif ($fld == 'discount') {
				update_post_meta($post_id, '_discount_value', $_POST['val']);
			} elseif ($fld == 'price') {
				update_post_meta($post_id, '_price_value', $_POST['val']);
			} elseif ($fld == 'exterior_color') {
				update_post_meta($post_id, '_exterior_color_value', $_POST['val']);
			} elseif ($fld == 'interior_color') {
				update_post_meta($post_id, '_interior_color_value', $_POST['val']);
			} elseif ($fld == 'mileage') {
				update_post_meta($post_id, '_mileage_value', $_POST['val']);
			} elseif ($fld == 'vehicle_options') {
				update_post_meta($post_id, '_vehicle_options', $_POST['val']);
			} else {
				update_post_meta($post_id, $fld, $val);	
			}
			update_post_meta($post_id, 'decode_string', $vin_query_decode);
		}
		if ($_POST['option'] == 'remove') {
			delete_post_meta($post_id, "decode_string");
			delete_post_meta($post_id, "decode_saved");
			delete_post_meta($post_id, "_transmission_value");
			delete_post_meta($post_id, "_engine_value");
			delete_post_meta($post_id, "_trim_value");
			delete_post_meta($post_id, "_exterior_color_value");
			delete_post_meta($post_id, "_interior_color_value");
			$val = '';
			wp_set_post_terms( $post_id, $val, 'vehicle_body_style', false );
			wp_set_post_terms( $post_id, $val, 'vehicle_year', false );
			wp_set_post_terms( $post_id, $val, 'vehicle_make', false );
			wp_set_post_terms( $post_id, $val, 'vehicle_model', false );
			wp_set_post_terms( $post_id, $val, 'vehicle_condition', false );
		}
	}
	exit();
}
function car_demon_decode_new_vin($vin) {
	$does_vin_exist = does_vin_exist($vin);
	if ($does_vin_exist != 0) {
		$has_car_been_decoded = get_post_meta($does_vin_exist, "decode_results", true);
		if (empty($has_car_been_decoded)) {
			car_demon_decode_vin($post_id, $vin);
		}
		$pluginpath = CAR_DEMON_PATH;
		$rootpath = str_replace('wp-content/plugins/car-demons/','',$pluginpath);
		$this_post = get_post($does_vin_exist); 
		$title = $this_post->post_title;
		echo '<p><strong>This VIN number has been decoded;</strong></p>';
		echo '<p><strong>'.$title.'</strong></p>';
		echo '<p><a href="'. get_permalink( $does_vin_exist ) .'" target="new_win">View Vehicle on site</a></p>';
		echo '<p><a href="'. $rootpath .'wp-admin/post.php?post='.$does_vin_exist.'&action=edit&message=1">Edit This Vehicle</a></p>';
	} else {
		echo add_vehicle_mini_form($vin);
	}
}
?>