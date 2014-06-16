// JavaScript Document
jQuery( "#cd_open_description" ).click(function() {
	jQuery("#description_tab").show( 500, function(){});
});
jQuery( "#cd_close_description" ).click(function() {
	jQuery("#description_tab").hide( 500, function(){});
});
jQuery( "#cd_add_description" ).click(function() {
	jQuery("#frm_add_description").show( 500, function(){});
});
jQuery( "#cancel_description" ).click(function() {
	jQuery("#frm_add_description").hide( 500, function(){});
});

jQuery( "#cd_open_specs" ).click(function() {
	jQuery("#specs_tab").show( 500, function(){});
});
jQuery( "#cd_close_specs" ).click(function() {
	jQuery("#specs_tab").hide( 500, function(){});
});
jQuery( "#cd_add_specs" ).click(function() {
	jQuery("#frm_add_specs").show( 500, function(){});
});
jQuery( "#cancel_specs" ).click(function() {
	jQuery("#frm_add_specs").hide( 500, function(){});
});

jQuery( "#cd_open_safety" ).click(function() {
	jQuery("#safety_tab").show( 500, function(){});
});
jQuery( "#cd_close_safety" ).click(function() {
	jQuery("#safety_tab").hide( 500, function(){});
});
jQuery( "#cd_add_safety" ).click(function() {
	jQuery("#frm_add_safety").show( 500, function(){});
});
jQuery( "#cancel_safety" ).click(function() {
	jQuery("#frm_add_safety").hide( 500, function(){});
});

jQuery( "#cd_open_convenience" ).click(function() {
	jQuery("#convenience_tab").show( 500, function(){});
});
jQuery( "#cd_close_convenience" ).click(function() {
	jQuery("#convenience_tab").hide( 500, function(){});
});
jQuery( "#cd_add_convenience" ).click(function() {
	jQuery("#frm_add_convenience").show( 500, function(){});
});
jQuery( "#cancel_convenience" ).click(function() {
	jQuery("#frm_add_convenience").hide( 500, function(){});
});

jQuery( "#cd_open_comfort" ).click(function() {
	jQuery("#comfort_tab").show( 500, function(){});
});
jQuery( "#cd_close_comfort" ).click(function() {
	jQuery("#comfort_tab").hide( 500, function(){});
});
jQuery( "#cd_add_comfort" ).click(function() {
	jQuery("#frm_add_comfort").show( 500, function(){});
});
jQuery( "#cancel_comfort" ).click(function() {
	jQuery("#frm_add_comfort").hide( 500, function(){});
});

jQuery( "#cd_open_entertainment" ).click(function() {
	jQuery("#entertainment_tab").show( 500, function(){});
});
jQuery( "#cd_close_entertainment" ).click(function() {
	jQuery("#entertainment_tab").hide( 500, function(){});
});
jQuery( "#cd_add_entertainment" ).click(function() {
	jQuery("#frm_add_entertainment").show( 500, function(){});
});
jQuery( "#cancel_entertainment" ).click(function() {
	jQuery("#frm_add_entertainment").hide( 500, function(){});
});

jQuery( "#cd_open_about_us" ).click(function() {
	jQuery("#about_us_tab").show( 500, function(){});
});
jQuery( "#cd_close_about_us" ).click(function() {
	jQuery("#about_us_tab").hide( 500, function(){});
});
jQuery( "#cd_add_about_us" ).click(function() {
	jQuery("#frm_add_about_us").show( 500, function(){});
});
jQuery( "#cancel_about_us" ).click(function() {
	jQuery("#frm_add_about_us").hide( 500, function(){});
});

function update_default_labels(fld) {
	var field = fld.id;
	var label = fld.value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_update_default_labels', 'field': field, 'label': label},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			document.getElementById(field).style.background = "#99CC99";
			var delay = function() { document.getElementById(field).style.background = "" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}

function show_hide_default_fields(fld) {
	var field = fld.value;
	var checked = fld.checked;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_update_default_fields', 'field': field, 'checked': checked},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			document.getElementById('sh_'+field).style.background = "#99CC99";
			var delay = function() { document.getElementById('sh_'+field).style.background = "" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}

function add_option_group(group) {
	var group_options = document.getElementById('group_options_'+group).value;
	var title = document.getElementById('group_option_title_'+group).value;
	var fail = 0;
	if (group_options=='') {
		var fail = 1;	
	}
	if (title=='') {
		var fail = 1;	
	}
	if (fail == 0) {
		jQuery.ajax({
			type: 'POST',
			data: {'action': 'car_demon_add_option_group', 'group': group, 'title': title, 'group_options': group_options},
			url: cdAdminParams.ajaxurl,
			timeout: 5000,
			error: function() {},
			dataType: "html",
			success: function(html){
				var new_body = html;
				location.reload();
			}
		})
		return false;
	} else {
		alert(cdAdminParams.error1);
	}
}

function remove_option_group(group, group_title) {
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_remove_option_group', 'group': group, 'group_title': group_title},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			document.getElementById('group_'+group_title).style.display = 'none';
		}
	})
	return false;
}

function update_option_group(group, group_title) {
	var group_options = document.getElementById('vehicle_option_group_items_'+group_title).value;
	var group_title = document.getElementById('vehicle_option_group_'+group_title).value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_update_option_group', 'group': group, 'group_title': group_title, 'group_options': group_options},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
			var new_body = html;
			alert(cdAdminParams.msg_update);
		}
	})
	return false;
}

function update_car(post_id, this_fld, fld) {
	var new_value = this_fld.value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_admin_update', 'post_id': post_id, 'val': new_value, 'fld': fld},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			this_fld.style.background = "#99CC99";
			var delay = function() { this_fld.style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
			if (document.getElementById("msrp_"+post_id)) {
				var msrp = document.getElementById("msrp_"+post_id).value;
			} else {
				var msrp = 0;	
			}
			if (document.getElementById("rebate_"+post_id)) {
				var rebate = document.getElementById("rebate_"+post_id).value;
			} else {
				var rebate = 0;	
			}
			if (document.getElementById("discount_"+post_id)) {
				var discount = document.getElementById("discount_"+post_id).value;				
			} else {
				var discount = 0;	
			}
			if (document.getElementById("price_"+post_id)) {
				var price = document.getElementById("price_"+post_id).value;
			} else {
				var price = 0;	
			}
			if (msrp == "") { msrp = 0; }
			if (rebate == "") { rebate = 0; }
			if (discount == "") { discount = 0; }
			if (price == "") { price = 0; }
			msrp = parseInt(msrp);
			rebate = parseInt(rebate);
			discount = parseInt(discount);
			price = parseInt(price);
			var calc_price = msrp - rebate - discount;
			document.getElementById("calc_price_"+post_id).innerHTML = calc_price
			document.getElementById("calc_discounts_"+post_id).innerHTML = rebate + discount;
			if (price != calc_price) {
				if (msrp != 0) {
					document.getElementById("price_"+post_id).style.background = "#FFB3B3";
					document.getElementById("calc_error_"+post_id).innerHTML = "Calc Error: " + (calc_price - price) + "<br />";
				}
				else {
					document.getElementById("price_"+post_id).style.background = "#FFFFFF";
					document.getElementById("calc_error_"+post_id).innerHTML = "";
				}
			}
			else {
				document.getElementById("calc_error_"+post_id).innerHTML = "";
				document.getElementById("price_"+post_id).style.background = "#FFFFFF";
			}
		}
	})
	return false;
}
function update_car_sold(post_id, this_fld, fld) {
	var new_value = this_fld.options[this_fld.selectedIndex].value;
	jQuery.ajax({
		type: 'POST',
		data: {'action': 'car_demon_admin_update', 'post_id': post_id, 'val': new_value, 'fld': fld},
		url: cdAdminParams.ajaxurl,
		timeout: 5000,
		error: function() {},
		dataType: "html",
		success: function(html){
		var new_body = html;
			this_fld.style.background = "#99CC99";
			var delay = function() { this_fld.style.background = "#FFFFFF" };
			setTimeout(delay, 1000);
		}
	})
	return false;
}
function show_custom_slide(slide_num) {
	document.getElementById("custom_slide_"+slide_num).style.display = "inline";
	document.getElementById("show_slide_"+slide_num).style.display = "none";
	document.getElementById("hide_slide_"+slide_num).style.display = "inline";
}
function hide_custom_slide(slide_num) {
	document.getElementById("custom_slide_"+slide_num).style.display = "none";
	document.getElementById("show_slide_"+slide_num).style.display = "inline";
	document.getElementById("hide_slide_"+slide_num).style.display = "none";
}
function clear_custom_slide(slide_num) {
	document.getElementById("custom_slide"+slide_num+"_title").value = "";
	document.getElementById("custom_slide"+slide_num+"_img").value = "";
	document.getElementById("custom_slide"+slide_num+"_link").value = "";
	document.getElementById("custom_slide"+slide_num+"_text").value = "";
}
function fnMoveItems(lstbxFrom,lstbxTo) {
	var varFromBox = document.all(lstbxFrom);
	var varToBox = document.all(lstbxTo); 
	if ((varFromBox != null) && (varToBox != null)) { 
		if (varFromBox.length < 1) {
			alert('There are no items in the source ListBox');
			return false;
		}
		if (varFromBox.options.selectedIndex == -1) { // when no Item is selected the index will be -1
			alert('Please select an Item to move');
			return false;
		}
		while ( varFromBox.options.selectedIndex >= 0 ) { 
			var newOption = new Option(); // Create a new instance of ListItem 
			newOption.text = varFromBox.options[varFromBox.options.selectedIndex].text; 
			newOption.value = varFromBox.options[varFromBox.options.selectedIndex].value; 
			var OldToDoBox = varToBox.value + ',';
			OldToDoBox = OldToDoBox.trim();
			if (OldToDoBox==',') {
				OldToDoBox = '';
			}
			varToBox.value = OldToDoBox + varFromBox.options[varFromBox.selectedIndex].text;
			varFromBox.remove(varFromBox.options.selectedIndex); //Remove the item from Source Listbox 
		} 
	}
	return false; 
}