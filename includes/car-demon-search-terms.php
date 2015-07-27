<?php
function car_demon_search_cars() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$my_year = '';
	$array = array();
	if ($_POST['_name'] == 'search_condition') {
		$my_condition = $_POST['_value'];
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_year") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$array[] = array('' => 'ALL');
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				//$cnt_results = count_these_tax_items($my_condition, $my_result->new_name, 3);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_year' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					//$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
					$array[] = array($my_slug => $my_result->new_name);
				}
				else {
					if (!empty($year_srch)) {
						//$array[] = array($my_slug => 'Vintage' . ' (' . $cnt_results . ')');
						$array[] = array($my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	}
	if ($_POST['_name'] == 'search_year') {
		$my_year = $_POST['_value'];
		if (!empty($my_year)) {
			$year_srch = '(('.$prefix.'terms.name)="'. $my_year .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") AND';
		} else {
			$year_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $year_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$array[] = array('' => 'ALL MAKES');
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$cnt_results = count_these_tax_items($my_year, $my_result->new_name, 1);
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_make' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					$array[] = array($my_year.','.$my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
				}
				else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage' . ' (' . $cnt_results . ')');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	}
	elseif ($_POST['_name'] == 'search_year_model') {
		$my_year = $_POST['_value'];
		if (!empty($my_year)) {
			$year_srch = '(('.$prefix.'terms.name)="'. $my_year .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") AND';
		} else {
			$year_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $year_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$array[] = array('' => 'ALL MODELS');
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_model' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					$array[] = array($my_year.','.$my_slug => $my_result->new_name);
				}
				else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	}
	elseif ($_POST['_name'] == 'search_make') {
		$my_string = $_POST['_value'];
		$my_array = explode(',', $my_string);
		$my_year = $my_array[0];
		$my_make = $my_array[1];
		if (!empty($my_make)) {
			$make_str = '(('.$prefix.'terms.slug)="'. $my_make .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_make") AND';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('.$make_str.' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		if (!empty($my_results)) {
			$array[] = array('' => 'ALL MODELS');
			foreach ($my_results as $my_result) {
				$cnt_results = count_these_tax_items($my_year, $my_result->new_name, 2);
				if ($cnt_results > 0 ) {
					$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_model' );
					$my_slug = $my_sel_val->slug;
					$array[] = array($my_slug => $my_result->new_name . ' (' . $cnt_results . ')');
				}
			}
		} else {
			$array[] = array('1' => 'ALL MODELS');
			$array[] = array('2' => 'ALL MODELS');
		}
	}
	elseif ($_POST['_name'] == 'search_make_condition') {
		$my_condition = $_POST['_value'];
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$array[] = array('' => 'ALL MAKES');
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_make' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					$array[] = array($my_year.','.$my_slug => $my_result->new_name);
				}
				else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	}
	elseif ($_POST['_name'] == 'search_model_condition') {
		$my_condition = $_POST['_value'];
		if (!empty($my_condition)) {
			$condition_srch = '(('.$prefix.'terms.name)="'. $my_condition .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition") AND';
		} else {
			$condition_srch = '';
		}
		$str_tax_sql = 'SELECT DISTINCT '.$prefix.'terms_1.name AS new_name
			FROM '.$prefix.'postmeta RIGHT JOIN ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 
			RIGHT JOIN ('.$prefix.'term_relationships 
			LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) 
			LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) ON '.$prefix.'postmeta.post_id = '.$prefix.'term_relationships_1.object_id
			WHERE ('. $condition_srch .' (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold") AND (('.$prefix.'postmeta.meta_value)="no"))
			ORDER BY new_name';
		$my_results = $wpdb->get_results($str_tax_sql);
		$cnt_results = 0;
		$array[] = array('' => 'ALL MODELS');
		if (!empty($my_results)) {
			foreach ($my_results as $my_result) {
				$my_sel_val = get_term_by( 'name', $my_result->new_name, 'vehicle_model' );
				$my_slug = $my_sel_val->slug;
				if ($my_result->new_name != '-') {
					$array[] = array($my_year.','.$my_slug => $my_result->new_name);
				}
				else {
					if (!empty($year_srch)) {
						$array[] = array($my_year.','.$my_slug => 'Vintage');
					}
				}
			}
		} else {
			$array[] = array('0' => 'No Match');
		}
	}
	if (empty($array)) {
		$array[] = array('1' => 'No Match');
		$array[] = array('2' => 'No Match');
	}
	echo json_encode( $array );
}

function count_these_tax_items($old_val, $new_val, $type) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	if ($type == 1 ) {
		if (!empty($old_val)) {
			$year_srch = '(('.$prefix.'terms.name)="'. $old_val .'") AND';
			$year_srch2 = 'AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year")';
			$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
					FROM ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 RIGHT JOIN ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'postmeta.post_id
					WHERE ('. $year_srch .'
					(('.$prefix.'terms_1.name)="'. $new_val .'") 
					'. $year_srch2 .'
					AND (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_make") 
					AND (('.$prefix.'postmeta.meta_key)="sold ") 
					AND (('.$prefix.'postmeta.meta_value)="no "))';
		}
		else {
			$str_sql = 'No Year-Model';
		}
	}
	if ($type == 2 ) {
		$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
				FROM ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 RIGHT JOIN ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'postmeta.post_id
				WHERE ((('.$prefix.'terms.name)="'. $old_val .'") 
				AND (('.$prefix.'terms_1.name)="'. $new_val .'") 
				AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_year") 
				AND (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_model") 
				AND (('.$prefix.'postmeta.meta_key)="sold ") 
				AND (('.$prefix.'postmeta.meta_value)="no "))';
		if (empty($old_val)) {
			$my_term = get_term_by( 'name', $new_val, 'vehicle_model' );
			$my_slug = $my_term->slug;
			$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
				FROM ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships.object_id = '.$prefix.'postmeta.post_id
				WHERE ((('.$prefix.'terms.name)="'. $new_val .'") AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_model") AND (('.$prefix.'postmeta.meta_key)="sold ") AND (('.$prefix.'postmeta.meta_value)="no "))';
		}
	}
	if ($type == 3 ) {
		if (!empty($old_val)) {
			$year_srch = '(('.$prefix.'terms.name)="'. $old_val .'") AND';
			$year_srch2 = 'AND (('.$prefix.'term_taxonomy.taxonomy)="vehicle_condition")';
			$str_sql = 'SELECT Count('.$prefix.'terms.name) AS new_name
					FROM ((('.$prefix.'term_relationships AS '.$prefix.'term_relationships_1 RIGHT JOIN ('.$prefix.'term_relationships LEFT JOIN ('.$prefix.'terms RIGHT JOIN '.$prefix.'term_taxonomy ON '.$prefix.'terms.term_id = '.$prefix.'term_taxonomy.term_id) ON '.$prefix.'term_relationships.term_taxonomy_id = '.$prefix.'term_taxonomy.term_taxonomy_id) ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'term_relationships.object_id) LEFT JOIN '.$prefix.'term_taxonomy AS '.$prefix.'term_taxonomy_1 ON '.$prefix.'term_relationships_1.term_taxonomy_id = '.$prefix.'term_taxonomy_1.term_taxonomy_id) LEFT JOIN '.$prefix.'terms AS '.$prefix.'terms_1 ON '.$prefix.'term_taxonomy_1.term_id = '.$prefix.'terms_1.term_id) LEFT JOIN '.$prefix.'postmeta ON '.$prefix.'term_relationships_1.object_id = '.$prefix.'postmeta.post_id
					WHERE ('. $year_srch .'
					(('.$prefix.'terms_1.name)="'. $new_val .'") 
					'. $year_srch2 .'
					AND (('.$prefix.'term_taxonomy_1.taxonomy)="vehicle_year") 
					AND (('.$prefix.'postmeta.meta_key)="sold ") 
					AND (('.$prefix.'postmeta.meta_value)="no "))';
		}
		else {
			$str_sql = 'No Condition';
		}
	}
	if ($str_sql == 'No Year-Model') {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_make' );
		$my_slug = $my_term->slug;
		$my_total = count_my_active_tax_items($my_slug, 'cars_for_sale', 'vehicle_make');
	}
	elseif ($str_sql == 'No Condition') {
		$my_term = get_term_by( 'name', $new_val, 'vehicle_year' );
		$my_slug = $my_term->slug;
		$my_total = count_my_active_tax_items($my_slug, 'cars_for_sale', 'vehicle_year');	
	} else {
		$total_cars = $wpdb->get_var($str_sql);
		$my_total = $total_cars;	
	}
	return $my_total;
}
function count_my_active_tax_items($my_tag_name, $post_type, $taxonomy) {
	global $wpdb;
	$my_tag_id = get_term_by( 'slug', ''.$my_tag_name.'', ''.$taxonomy.'');
	$my_tag_id = $my_tag_id->term_id;
	if (!empty($my_tag_id)) {
		$my_search .= " AND $wpdb->term_taxonomy.taxonomy = '".$taxonomy."'	AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id.")";
		$query = "SELECT COUNT(*) as num
			FROM $wpdb->posts wposts
				LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
				LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE wposts.post_type='".$post_type."'
				AND wpostmeta.meta_key = 'sold'
				AND wpostmeta.meta_value = 'no'".$my_search;
		$total_cars = $wpdb->get_var($str_sql);
	}
	return $total_cars;
}
?>