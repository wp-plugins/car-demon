<?php
function car_demon_dynamic_load() {
	if ($_SESSION['car_demon_options']['dynamic_load'] == 'Yes') {
		$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';
		$pluginpath = str_replace('includes', 'theme-files', $pluginpath);
		$html = '
			<link rel="stylesheet" type="text/css" media="all" href="'.$pluginpath.'css/jquery.ias.css" />
			<script language="javascript" type="text/javascript" src="'.$pluginpath.'js/jquery.ias.min.js"></script>
			<script>
				jQuery.ias({
				  container 	: ".listing",
						// Enter the selector of the element containing
						// your items that you want to paginate.
				 
				  item		: ".random",
						// Enter the selector of the element that each
						// item has. Make sure the elements are inside
						// the container element.
				 
				  pagination	: ".navigation",
						// Enter the selector of the element that contains
						// your regular pagination links, like next,
						// previous and the page numbers. This element
						// will be hidden when IAS loads.
				 
				  next		: ".nextpostslink",
						// Enter the selector of the link element that
						// links to the next page. The href attribute
						// of this element will be used to get the items
						// from the next page.
				 
				  loader	: "<img src=\''.$pluginpath.'images/ajax-loader.gif\' />"
						// Enter the url to the loader image. This image
						// will be displayed when the next page with items
						// is loaded via AJAX.
				});
			</script>
		';
	}
	return $html;
}
?>