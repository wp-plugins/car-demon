<?php
header('Content-type: text/javascript');
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
}
else {
	$is_it_iis = 'Win';
}

if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/includes/js', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
}
else {
	$newPath = str_replace('wp-content\plugins\car-demon\includes\js', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$car_demon_pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
$car_demon_pluginpath = str_replace('includes/js','',$car_demon_pluginpath);
$wpurl = site_url();
?>
// JavaScript Document
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
					 jQuery("#search_year").html("");
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

function remove_search(fld, val, query_string) {
	remove_this = "&"+fld+"="+val;
	var reg = new RegExp(remove_this,"g");
	var query_string = query_string.replace(reg, "");
	window.location = "<?php echo $wpurl; ?>?"+query_string;
}