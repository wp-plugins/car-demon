<?php
function car_demon_search_years() {
	$search_years = '';
	$search_years .= '<select class="" name="search_year" id="search_year" style="width:75px;">';
		$search_years .= '<option value="">Any</option>';
		$search_years .= car_demon_get_my_tax('vehicle_year',0);
	$search_years .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_years;
}

function car_demon_search_makes() {
	$search_makes = '';
	$search_makes .= '<select class="search_dropdown_bg" name="search_make" id="search_make" style="width:150px;">';
		$search_makes .= '<option value="">ALL MAKES</option>';
		$search_makes .= car_demon_get_my_tax('vehicle_make',1);
	$search_makes .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_makes;
}

function car_demon_search_models() {
	$search_models = '';
	$search_models .= '<select class="search_dropdown_bg" name="search_model" id="search_model" onchange="car_demon_fix_model();" style="width:150px;">';
		$search_models .= '<option value="">ALL MODELS</option>';
		$search_models .= car_demon_get_my_tax('vehicle_model',2);
	$search_models .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_models;
}

function car_demon_search_condition() {
	$search_condition = '';
	$search_condition .= '<select name="search_condition" id="search_condition">';
		$search_condition .= '<option value="">ALL CONDITIONS</option>';
		$search_condition .= car_demon_get_my_tax('vehicle_condition');
	$search_condition .= '</select>&nbsp;&nbsp;&nbsp;';
	return $search_condition;
}

function car_demon_get_my_tax($taxonomy,$val) {
	$my_tag_list = '';
	$this_val = '';
	if ($val == 1) {
		if (isset($_GET['search_make'])) {
			$search_make = $_GET['search_make'];
		}
		else {
			$search_make = '';
		}
		$this_val = $search_make;
		$this_val = str_replace(',', '', $this_val);
	}
	if ($val == 2) {
		if (isset($_GET['search_model'])) {
			$search_model = $_GET['search_model'];
		}
		else {
			$search_model = '';
		} 
		$this_val = $search_model;
	}
	$post_type = 'cars_for_sale';
	$args = array(
		'smallest'  => 8, 
		'largest'   => 22,
		'unit'      => 'pt', 
		'number'    => 95,  
		'format'    => 'array',
		'separator' => ' - ',
		'orderby'   => 'name', 
		'order'     => 'ASC',
		'exclude'   => '', 
		'include'   => '', 
		'link'      => 'view', 
		'taxonomy'  => $taxonomy, 
		'echo'      => true );
	$my_tags = wp_tag_cloud( $args );
	if ($my_tags) {
		foreach($my_tags as $my_tag) {
			$my_tag2 = $my_tag;
			$my_tag = str_replace('title=','<title>',$my_tag);
			$my_tag = str_replace('style=','<style>',$my_tag);
			preg_match_all("~<title>([^<]*)<style>~",$my_tag,$bad_things);
			$bad_thing = $bad_things[1][0];
			$my_tag2 = str_replace('</a>','[2]',$my_tag2);
			$my_tag2 = str_replace('>','[1]',$my_tag2);
			$my_tag2 = str_replace('<','',$my_tag2);
			$my_tag2 = str_replace('[1]','<1>',$my_tag2);
			$my_tag2 = str_replace('[2]','<2>',$my_tag2);
			preg_match_all("~<1>([^<]*)<2>~",$my_tag2,$my_tag_names);
			$my_tag_name = $my_tag_names[1][0];
			$count_cars = car_demon_count_active_tax_items($my_tag_name, $post_type, $taxonomy);
			if (empty($count_cars)) {
				$count_cars = car_demon_count_active_items($my_tag_name.'-2', $post_type, $taxonomy);
			}
			if (!empty($count_cars)) {
				if ($val == 1) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_make' );
					$my_slug = $my_term->slug;
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value=",' . $my_slug .'"'.$select.'>';
				}
				elseif ($val == 2) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_model' );
					$my_slug = $my_term->slug;
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value="' . $my_slug . '"'.$select.'>';			
				}
				else {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_year' );
					$my_slug = $my_term->slug;
					if ($my_slug == $this_val) {$select = ' selected';} else {$select = '';}
					$my_tag_list .= '<option value="' . $my_slug . '"'.$select.'>';
				}
				$my_tag_list .= $my_tag_name . ' (' . $count_cars . ')';
				$my_tag_name .= '</option>';
			}
			else {
				if ($val == 2) {
					$my_term = get_term_by( 'name', $my_tag_name, 'vehicle_model' );
					$my_slug = $my_term->slug;
				}
			}
			$total_count = ' - '.$count_cars;
		}
	}
	return $my_tag_list;
}

function car_demon_count_active_tax_items($my_tag_name, $post_type, $taxonomy) {
	global $wpdb;
	$my_tag_id = get_term_by( 'slug', ''.$my_tag_name.'', ''.$taxonomy.'');
	if ($my_tag_id == false) {
		$my_tag_id = get_term_by( 'name', ''.$my_tag_name.'', ''.$taxonomy.'');
	}
	$my_tag_id = $my_tag_id->term_id;
	if (!empty($my_tag_id)) {
		$my_search = " AND $wpdb->term_taxonomy.taxonomy = '".$taxonomy."'	AND $wpdb->term_taxonomy.term_id IN(".$my_tag_id.")";
		$query = "SELECT COUNT(*) as num
			FROM $wpdb->posts wposts
				LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id 
				LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE wposts.post_type='".$post_type."'
				AND wpostmeta.meta_key = 'sold'
				AND wpostmeta.meta_value = 'no'".$my_search;
		$total_cars = mysql_fetch_array(mysql_query($query));
		$total_cars = $total_cars['num'];
	}
	return $total_cars;
}

function car_demon_search_cars_scripts() {
	$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
	$car_demon_pluginpath = str_replace('includes','',$car_demon_pluginpath);
	?>
	<script language="JavaScript" type="text/javascript"> 
	function car_demon_fix_model() {
		var e = document.getElementById("search_model");
		var my_val = e.options[e.selectedIndex].value;
	}
	function car_demon_disable_form() {
		if (document.getElementById("search_year")) {
			document.getElementById("search_year").disabled = true;
		}
		if (document.getElementById("search_condition")) {
			document.getElementById("search_condition").disabled = true;
		}
		document.getElementById("search_make").disabled = true;
		document.getElementById("search_model").disabled = true;
		document.getElementById("submit_search").disabled = true;
	}
	function car_demon_enable_form() {
		if (document.getElementById("search_year")) {
			document.getElementById("search_year").disabled = false;
		}
		if (document.getElementById("search_condition")) {
			document.getElementById("search_condition").disabled = false;
		}
		document.getElementById("search_make").disabled = false;
		document.getElementById("search_model").disabled = false;
		document.getElementById("submit_search").disabled = false;
	}
	jQuery(function _() {
		jQuery('#search_condition').change (function(){
			car_demon_disable_form();
			var search_condition = document.getElementById("search_condition").options[document.getElementById("search_condition").selectedIndex].value;
			if (document.getElementById("search_year")) {
				var options = {
					 type: "POST",
					 url: "<?php echo $car_demon_pluginpath ?>includes/car-demon-search-terms.php?_name=search_condition&_value="+search_condition,
					 data: "{}",
					 contentType: "application/json; charset=utf-8",
					 dataType: "json",
					 success: function(msg) {
						 $("#search_year").html("");
						 var returnedArray = msg;
						 for (i = 0; i < returnedArray.length; i++) {
							for ( key in returnedArray[i] ) {	
								jQuery("#search_year").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
							}
						 }
					 }
				 };
				 jQuery.ajax(options);
			}
			var options = {
				 type: "POST",
				 url: "<?php echo $car_demon_pluginpath ?>includes/car-demon-search-terms.php?_name=search_make_condition&_value="+search_condition,
				 data: "{}",
				 contentType: "application/json; charset=utf-8",
				 dataType: "json",
				 success: function(msg) {
					 jQuery("#search_make").html("");
					 var returnedArray = msg;
					 for (i = 0; i < returnedArray.length; i++) {
						for ( key in returnedArray[i] ) {	
							jQuery("#search_make").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
						}
					 }
				 }
			 };
			 jQuery.ajax(options);
			var options = {
				 type: "POST",
				 url: "<?php echo $car_demon_pluginpath ?>includes/car-demon-search-terms.php?_name=search_model_condition&_value="+search_condition,
				 data: "{}",
				 contentType: "application/json; charset=utf-8",
				 dataType: "json",
				 success: function(msg) {
					 jQuery("#search_model").html("");
					 var returnedArray = msg;
					 for (i = 0; i < returnedArray.length; i++) {
						for ( key in returnedArray[i] ) {	
							jQuery("#search_model").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
						}
					 }
		 			car_demon_enable_form();
				 }
			 };
			 jQuery.ajax(options);
		});
		jQuery('#search_make').change (function(){
			car_demon_disable_form();
			var search_make = document.getElementById("search_make").options[document.getElementById("search_make").selectedIndex].value;
			var options = {
				 type: "POST",
				 url: "<?php echo $car_demon_pluginpath ?>includes/car-demon-search-terms.php?_name=search_make&_value="+search_make,
				 data: "{}",
				 contentType: "application/json; charset=utf-8",
				 dataType: "json",
				 success: function(msg) {
					 jQuery("#search_model").html("");
					 var returnedArray = msg;
					 for (i = 0; i < returnedArray.length; i++) {
						for ( key in returnedArray[i] ) {	
							jQuery("#search_model").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
						}
					 }
		 			car_demon_enable_form();
				 }
			 };
			 jQuery.ajax(options);
		});
		jQuery('#search_year').change (function(){
			car_demon_disable_form();
			if (document.getElementById("search_condition")) {
				document.getElementById("search_condition").selectedIndex = 0;
			}
			var search_year = document.getElementById("search_year").options[document.getElementById("search_year").selectedIndex].value;
			var options = {
				 type: "POST",
				 url: "<?php echo $car_demon_pluginpath ?>includes/car-demon-search-terms.php?_name=search_year&_value="+search_year,
				 data: "{}",
				 contentType: "application/json; charset=utf-8",
				 dataType: "json",
				 success: function(msg) {
					 jQuery("#search_make").html("");
					 var returnedArray = msg;
					 for (i = 0; i < returnedArray.length; i++) {
						for ( key in returnedArray[i] ) {	
							jQuery("#search_make").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
						}
					 }
		 			car_demon_enable_form();
				 }
			 };
 			 jQuery.ajax(options);
			var options = {
				 type: "POST",
				 url: "<?php echo $car_demon_pluginpath ?>includes/car-demon-search-terms.php?_name=search_year_model&_value="+search_year,
				 data: "{}",
				 contentType: "application/json; charset=utf-8",
				 dataType: "json",
				 success: function(msg) {
					 jQuery("#search_model").html("");
					 var returnedArray = msg;
					 for (i = 0; i < returnedArray.length; i++) {
						for ( key in returnedArray[i] ) {	
							jQuery("#search_model").get(0).add(new Option(returnedArray[i][key],[key]), document.all ? i : null);
						}
					 }
		 			car_demon_enable_form();
				 }
			 };
			 jQuery.ajax(options);
		});
	});
	</script> 
	<style> 
	input.btn {
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		font-weight:bold;
		padding-left: 15px;
		padding-right: 15px;
		padding-bottom: 2px;
		padding-top: 2px;
		background-color:#fed;
		border: 1px solid;
		border-color: #696 #363 #363 #696;
		filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=0,StartColorStr='#ffffffff',EndColorStr='#ffeeddaa');
		cursor:pointer;
	}
	input.btnhov {
		  border-color: #c63 #930 #930 #c63;
		  color:#FF0000;
	}	
	#loading {
		position:absolute;
		top:0px;
		right:0px;
		background:#ff0000;
		color:#fff;
		font-size:14px;
		font-familly:Arial;
		padding:2px;
		display:none;
	}
	</style> 
	<?php
}
?>