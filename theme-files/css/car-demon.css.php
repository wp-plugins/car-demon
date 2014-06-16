<?php
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);
$newPath = dirname(__FILE__);
if (!stristr(PHP_OS, 'WIN')) {
	$is_it_iis = 'Apache';
} else {
	$is_it_iis = 'Win';
}
if ($is_it_iis == 'Apache') {
	$newPath = str_replace('wp-content/plugins/car-demon/theme-files/css', '', $newPath);
	include_once($newPath."/wp-load.php");
	include_once($newPath."/wp-includes/wp-db.php");
} else {
	$newPath = str_replace('wp-content\plugins\car-demon\theme-files\css', '', $newPath);
	include_once($newPath."\wp-load.php");
	include_once($newPath."\wp-includes/wp-db.php");
}
$car_demon_pluginpath = CAR_DEMON_PATH;
$car_demon_pluginpath = str_replace('theme-files/css','',$car_demon_pluginpath);
$theme_color = '4D525D';
$theme_color_highlight = '333';
$theme_color_shadow = '999';
$theme_color_button = '0000aa';
$theme_color_button_hover = '999';
$theme_color_button_shadow = '999';
if (isset($_SESSION['car_demon_options']['theme_color'])) { $theme_color = $_SESSION['car_demon_options']['theme_color']; }
if (isset($_SESSION['car_demon_options']['theme_color_highlight'])) { $theme_color_highlight = $_SESSION['car_demon_options']['theme_color_highlight']; }
if (isset($_SESSION['car_demon_options']['theme_color_shadow'])) { $theme_color_shadow = $_SESSION['car_demon_options']['theme_color_shadow']; }
if (isset($_SESSION['car_demon_options']['theme_color_button'])) { $theme_color_button = $_SESSION['car_demon_options']['theme_color_button']; }
if (isset($_SESSION['car_demon_options']['theme_color_button_hover'])) { $theme_color_button_hover = $_SESSION['car_demon_options']['theme_color_button_hover']; }
if (isset($_SESSION['car_demon_options']['theme_color_button_shadow'])) { $theme_color_button_shadow = $_SESSION['car_demon_options']['theme_color_button_shadow']; }

if (isset($_SESSION['car_demon_options']['use_form_css'])) {
	if ($_SESSION['car_demon_options']['use_form_css'] != 'No') {
		?>
		/* =Search Button
		-------------------------------------------------------------- */
		.search_btn {
			-moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
			-webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
			box-shadow:inset 0px 1px 0px 0px #bbdaf7;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #<?php echo $theme_color_button; ?>), color-stop(1, #<?php echo $theme_color_button_shadow; ?>) );
			background:-moz-linear-gradient( center top, #<?php echo $theme_color_button; ?> 5%, #<?php echo $theme_color_button_shadow; ?> 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#<?php echo $theme_color_highlight; ?>', endColorstr='#<?php echo $theme_color_button_shadow; ?>');
			background-color:#<?php echo $theme_color_button; ?>;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #84bbf3;
			display:inline-block;
			color:#ffffff !important;
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			padding:6px 24px;
			text-decoration:none;
			text-shadow:1px 1px 0px #528ecc;
			cursor:pointer;
		}.search_btn:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #<?php echo $theme_color_button_hover; ?>), color-stop(1, #<?php echo $theme_color_button_shadow; ?>) );
			background:-moz-linear-gradient( center top, #<?php echo $theme_color_button_hover; ?> 5%, #<?php echo $theme_color_button_shadow; ?> 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#<?php echo $theme_color_button_hover; ?>', endColorstr='#<?php echo $theme_color_button_shadow; ?>');
			background-color:#<?php echo $theme_color_button_hover; ?>;
		}.search_btn:active {
			position:relative;
			top:1px;
		}
		/* =Calc Button
		-------------------------------------------------------------- */
		.calc_btn {
			-moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
			-webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
			box-shadow:inset 0px 1px 0px 0px #bbdaf7;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #<?php echo $theme_color_button; ?>), color-stop(1, #<?php echo $theme_color_button_shadow; ?>) );
			background:-moz-linear-gradient( center top, #<?php echo $theme_color_button; ?> 5%, #<?php echo $theme_color_button_shadow; ?> 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#<?php echo $theme_color_button; ?>', endColorstr='#<?php echo $theme_color_button_shadow; ?>');
			background-color:#<?php echo $theme_color_button; ?>;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #84bbf3;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:11px;
			font-weight:bold;
			padding:6px;
			text-decoration:none;
			text-shadow:1px 1px 0px #528ecc;
			width:75px;
			cursor:pointer;
		}.calc_btn:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #<?php echo $theme_color_button_hover; ?>), color-stop(1, #<?php echo $theme_color_button_shadow; ?>) );
			background:-moz-linear-gradient( center top, #<?php echo $theme_color_button_hover; ?> 5%, #<?php echo $theme_color_button_shadow; ?> 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#<?php echo $theme_color_button_hover; ?>', endColorstr='#<?php echo $theme_color_button_shadow; ?>');
			background-color:#<?php echo $theme_color_button_hover; ?>;
		}.calc_btn:active {
			position:relative;
			top:1px;
		}
<?php
	}
}
?>
.featured-button {
	background:url(<?php echo $car_demon_pluginpath; ?>theme-files/images/featured-button.png) 0 0 no-repeat;
	border:none;
	cursor:pointer;
	display:block;
	height:61px;
	width:230px;
	float:left;
	margin-left:10px;
	margin-bottom:10px;
}
.featured-button a {
	color:#444;
	text-decoration:none;
}
.featured-button:hover {
	background-position:0 -61px;
	color:#444;
	text-decoration:none;
}
.featured-button p {
	font-size:18px;
	font-weight:700;
	height:61px;
	line-height:61px;
	text-align:center;
	text-shadow:0 1px #fff;
	text-transform:uppercase;
}
.email_a_friend {
	margin-left:10px;
	margin-top: 7px;
	float: left;
}
.remove_contact {
	cursor: pointer;
}
.hide_contacts {
	display:none;
}
.remove_contact_btn {
	cursor: pointer;
	display:none;
	margin-left:10px;
	margin-top:4px;
}
.add_contact_btn {
	cursor: pointer;
	margin-left:10px;
	margin-top:4px;
}
.contact_msg {
	display:none;
	background: #f1cadf;
	margin:10px;
	padding:5px;
	font-weight:bold;
}
.cdform {
	width: 100%;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
}
.cdform fieldset {
	margin-top: 10px;
	padding: 5px 0 15px 0;
	border: 1px solid #ADADAD;
	border-left-color: #ECECEC;
	border-top-color: #ECECEC;
	background: #F7F7F7;
}
.cdform legend {
	margin-left: 10px;
	padding: 0 2px;
	font: normal 20px Times;
	color: 
	#666;
}
.cdform label {
	width: 70px;
	margin: 4px 10px 0 0;
	display: -moz-inline-box;
	display: inline-block;
	text-align: right;
	vertical-align: top;
}
.cd-box-group label {
	width: 120px;
	margin: 4px 10px 10px 6px;
	display: -moz-inline-box;
	display: inline-block;
	text-align: left;
	vertical-align: top;
}
.cd-box-title {
	margin-left:5px!important;
}
.cdlabel_right {
	width: 190px;
	margin: 4px 10px 0 0;
	display: -moz-inline-box;
	display: inline-block;
	text-align: left;
	vertical-align: top;
}
.cdform textarea {
	width: 94%;
    margin-left: 2%;
}
.cd_date {
	font-size:11px;
}
span.reqtxt, span.emailreqtxt {
	margin: 3px 0 0 3px;
	font-size: 0.9em;
	display: -moz-inline-box;
	vertical-align: top;
	color:#ff0000;
}
ol.cd-ol {
	margin: 0!important;
	padding: 0!important;
}
ol.cd-ol li {
	background: none;
	margin: 5px 0;
	padding: 0;
	list-style: none!important;
	text-align: left;
	line-height: 1.3em;
}
.cd-sb {
	width:240px !important;
}
.vcard .photo {
	width: inherit !important;
}
/* Navigation Style
-------------------------------------------------------------- */
.navigation {
	color:#444;
	display:block;
	font-size:16px;
	height:28px;
	line-height:28px;
	margin:20px 0;
	padding:0 5px;
}
.navigation a {
	color:#444;
}
.navigation .previous {
	float:left;
}
.navigation .next {
	float:right;
}
.navigation .bracket {
	font-size:36px;
}
.wp-pagenavi a, .wp-pagenavi a:link {
	padding: 2px 4px 2px 4px; 
	margin: 2px;
	text-decoration: none;
	border: 1px solid #3c78a7;
	color: #3c78a7;
	background-color: #FFFFFF;	
}
.wp-pagenavi a:visited {
	padding: 2px 4px 2px 4px; 
	margin: 2px;
	text-decoration: none;
	border: 1px solid #3c78a7;
	color: #3c78a7;
	background-color: #FFFFFF;	
}
.wp-pagenavi a:hover {	
	border: 1px solid #303030;
	color: #303030;
	background-color: #FFFFFF;
}
.wp-pagenavi a:active {
	padding: 2px 4px 2px 4px; 
	margin: 2px;
	text-decoration: none;
	border: 1px solid #3c78a7;
	color: #3c78a7;
	background-color: #FFFFFF;	
}
.wp-pagenavi span.pages {
	padding: 2px 4px 2px 4px; 
	margin: 2px 2px 2px 2px;
	color: #303030;
	border: 1px solid #303030;
	background-color: #FFFFFF;
}
.wp-pagenavi span.current {
	padding: 2px 4px 2px 4px; 
	margin: 2px;
	font-weight: bold;
	border: 1px solid #303030;
	color: #303030;
	background-color: #FFFFFF;
}
.wp-pagenavi span.extend {
	padding: 2px 4px 2px 4px; 
	margin: 2px;	
	border: 1px solid #303030;
	color: #303030;
	background-color: #FFFFFF;
}
.search_car_box select {
	height: 20px;
}