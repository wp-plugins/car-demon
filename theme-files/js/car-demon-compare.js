// JavaScript Document
function update_car(post_id,fld) {
	document.getElementById("car_demon_compare").innerHTML = '...processing';
	if (fld.checked == true) {
		var add_it = '1';
	} else {
		var add_it = '0';
		var compareElement = document.getElementById("compare_"+post_id);
		if (compareElement != null) {
			document.getElementById("compare_"+post_id).checked = false;
		}
	}
	jQuery.ajax({
		type: 'POST',
		data: {'post_id': post_id, action: 'cd_compare_handler', 'add_it': add_it},
		url: cdCompareParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("car_demon_compare").innerHTML = html;
			html = html.trim();
			if (html == '<p></p>') {
				jQuery('#car_demon_compare_widget').fadeOut('slow', function() { });
			} else {
				jQuery('#car_demon_compare_widget').fadeIn('slow', function() { });
			}
		}
	})
	return false;
}
function get_compare_list() {
	jQuery.ajax({
		type: 'POST',
		data: {action: 'cd_get_compare_list', 'compare_list': '1'},
		url: cdCompareParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			document.getElementById("car_demon_compare_box_main").innerHTML = html;
		}
	})
	return false;
}
function open_car_demon_compare() {
	jQuery("#car_demon_compare_div").lightbox_me({
		overlayCSS: {background: 'black', opacity: .6}
	});
	document.getElementById('car_demon_compare_box').style.display = "block";
	get_compare_list();
}
function close_car_demon_compare() {
	jQuery("#car_demon_compare_div").trigger('close');
}
function print_compare() {
	w=window.open();
	if(!w)alert('Please enable pop-ups');
	var new_print = '<title>'+cdCompareParams.msg1+'</title>';
	var new_print = new_print + '<meta http-equiv="X-UA-Compatible" content="IE8"/>';
	var new_print = new_print + '<link rel="stylesheet" type="text\/css" media="all" href="'+cdCompareParams.css_url+'" />';
	var new_print = new_print + document.getElementById('car_demon_compare_box_list_cars').innerHTML;
	w.document.write(new_print);
	if (navigator.appName == "Microsoft Internet Explorer") {
		w.document.close();
	}
	w.focus();
	w.print();
	w.close();
	return false;
}